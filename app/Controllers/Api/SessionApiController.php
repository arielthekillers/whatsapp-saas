<?php
declare(strict_types=1);

namespace App\Controllers\Api;

use App\Config\Env;
use App\Helpers\ApiResponse;
use App\Repositories\SessionRepository;
use App\Services\SessionNameGenerator;
use App\Services\WahaService;
use App\Support\ApiAuth;
use Throwable;

class SessionApiController
{
    private SessionRepository $sessions;

    public function __construct()
    {
        $this->sessions = new SessionRepository();
    }

    public function index(): void
    {
        $ctx  = ApiAuth::resolve();
        $rows = $this->sessions->findAllForUser($ctx['user_id']);
        ApiResponse::success(['sessions' => array_map([$this, 'present'], $rows)]);
    }

    public function show(int $id): void
    {
        $ctx = ApiAuth::resolve();
        $row = $this->sessions->findForUser($ctx['user_id'], $id);
        if ($row === null) {
            ApiResponse::error('SESSION_NOT_FOUND', 'Session tidak ditemukan.', 404);
        }
        ApiResponse::success($this->present($row));
    }

    public function store(): void
    {
        $ctx    = ApiAuth::resolve();
        $userId = $ctx['user_id'];

        $body  = json_decode((string) file_get_contents('php://input'), true) ?? [];
        $label = trim((string) ($body['name'] ?? ''));
        if ($label === '') {
            ApiResponse::error('VALIDATION_ERROR', 'Field name wajib diisi.', 422);
        }

        $sub          = $ctx['subscription'];
        $sessionLimit = $sub['session_limit'] ?? 1;
        $activeCount  = $this->sessions->countActiveForUser($userId);
        if ($activeCount >= $sessionLimit) {
            ApiResponse::error('SESSION_LIMIT_REACHED', 'Batas jumlah session pada plan Anda sudah tercapai.', 409);
        }

        $wahaSessionName = SessionNameGenerator::generate($userId, $label);
        $instanceId      = $this->sessions->getDefaultWahaInstanceId();
        $sessionId       = $this->sessions->create($userId, $instanceId, $label, $wahaSessionName);

        try {
            $callbackUrl = rtrim((string) Env::get('APP_URL', ''), '/') . '/webhook/waha';
            (new WahaService())->createAndStartSession($wahaSessionName, [
                ['url' => $callbackUrl, 'events' => ['session.status', 'message', 'message.ack']],
            ]);
            $this->sessions->updateStatus($sessionId, 'STARTING');
        } catch (Throwable $e) {
            $this->sessions->updateStatus($sessionId, 'FAILED');
            error_log('[waha] Gagal membuat session via API user #' . $userId . ': ' . $e->getMessage());
        }

        ApiResponse::success($this->present($this->sessions->findForUser($userId, $sessionId)), 201);
    }

    public function start(int $id): void
    {
        $ctx = ApiAuth::resolve();
        $row = $this->sessions->findForUser($ctx['user_id'], $id);
        if ($row === null) {
            ApiResponse::error('SESSION_NOT_FOUND', 'Session tidak ditemukan.', 404);
        }

        try {
            (new WahaService())->createAndStartSession($row['waha_session_name']);
            $this->sessions->updateStatus($id, 'STARTING');
        } catch (Throwable $e) {
            error_log('[waha] Gagal start session via API #' . $id . ': ' . $e->getMessage());
            ApiResponse::error('WAHA_ERROR', 'Gagal menghubungi WAHA.', 502);
        }

        ApiResponse::success(['status' => 'STARTING']);
    }

    public function stop(int $id): void
    {
        $ctx = ApiAuth::resolve();
        $row = $this->sessions->findForUser($ctx['user_id'], $id);
        if ($row === null) {
            ApiResponse::error('SESSION_NOT_FOUND', 'Session tidak ditemukan.', 404);
        }

        try {
            (new WahaService())->stopSession($row['waha_session_name']);
            $this->sessions->updateStatus($id, 'STOPPED');
        } catch (Throwable $e) {
            error_log('[waha] Gagal stop session via API #' . $id . ': ' . $e->getMessage());
            ApiResponse::error('WAHA_ERROR', 'Gagal menghubungi WAHA.', 502);
        }

        ApiResponse::success(['status' => 'STOPPED']);
    }

    private function present(array $row): array
    {
        return [
            'id'                => (int) $row['id'],
            'name'              => $row['name'],
            'phone_number'      => $row['phone_number'],
            'status'            => $row['status'],
            'created_at'        => $row['created_at'],
            'last_connected_at' => $row['last_connected_at'],
        ];
    }
}
