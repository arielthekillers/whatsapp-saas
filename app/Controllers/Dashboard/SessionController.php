<?php
declare(strict_types=1);

namespace App\Controllers\Dashboard;

use App\Config\Env;
use App\Helpers\Csrf;
use App\Helpers\Response;
use App\Middleware\AuthMiddleware;
use App\Repositories\SessionRepository;
use App\Repositories\SubscriptionRepository;
use App\Services\SessionNameGenerator;
use App\Services\WahaService;
use Throwable;

class SessionController
{
    private SessionRepository $sessions;

    public function __construct()
    {
        $this->sessions = new SessionRepository();
    }

    public function index(): void
    {
        $user     = AuthMiddleware::handle();
        $sessions = $this->sessions->findAllForUser($user['id']);
        require __DIR__ . '/../../../views/sessions/index.php';
    }

    public function showCreate(): void
    {
        $user = AuthMiddleware::handle();
        $subscription = (new SubscriptionRepository())->findActiveForUser($user['id']);

        if (!$subscription) {
            $_SESSION['flash_billing_error'] = 'Anda belum memiliki paket aktif. Silakan pilih paket langganan terlebih dahulu untuk membuat WhatsApp Session.';
            Response::redirect('/billing');
            return;
        }

        $error = null;
        require __DIR__ . '/../../../views/sessions/create.php';
    }

    public function store(): void
    {
        $user = AuthMiddleware::handle();

        if (!Csrf::verify($_POST['_csrf'] ?? null)) {
            $error = 'Sesi tidak valid, silakan coba lagi.';
            require __DIR__ . '/../../../views/sessions/create.php';
            return;
        }

        $subscription = (new SubscriptionRepository())->findActiveForUser($user['id']);
        if (!$subscription) {
            $_SESSION['flash_billing_error'] = 'Anda belum memiliki paket aktif. Silakan pilih paket langganan terlebih dahulu untuk membuat WhatsApp Session.';
            Response::redirect('/billing');
            return;
        }

        $label = trim((string) ($_POST['name'] ?? ''));
        if ($label === '') {
            $error = 'Nama session wajib diisi.';
            require __DIR__ . '/../../../views/sessions/create.php';
            return;
        }

        $sessionLimit = (int) ($subscription['session_limit'] ?? 0);
        if ($this->sessions->countActiveForUser($user['id']) >= $sessionLimit) {
            $error = 'Batas jumlah session pada paket ' . htmlspecialchars($subscription['plan_name'] ?? '') . ' Anda (' . $sessionLimit . ' session) sudah tercapai. Silakan upgrade paket Anda.';
            require __DIR__ . '/../../../views/sessions/create.php';
            return;
        }

        $wahaSessionName = SessionNameGenerator::generate($user['id'], $label);
        $instanceId      = $this->sessions->getDefaultWahaInstanceId();
        $sessionId       = $this->sessions->create($user['id'], $instanceId, $label, $wahaSessionName);

        try {
            $waha        = new WahaService();
            $callbackUrl = rtrim((string) Env::get('APP_URL', ''), '/') . '/webhook/waha';
            $waha->createAndStartSession($wahaSessionName, [
                ['url' => $callbackUrl, 'events' => ['session.status', 'message', 'message.ack']],
            ]);
            $this->sessions->updateStatus($sessionId, 'STARTING');
        } catch (Throwable $e) {
            $this->sessions->updateStatus($sessionId, 'FAILED');
            error_log('[waha] Gagal membuat session di WAHA: ' . $e->getMessage());
        }

        Response::redirect('/sessions/' . $sessionId);
    }

    public function show(int $id): void
    {
        $user    = AuthMiddleware::handle();
        $session = $this->sessions->findForUser($user['id'], $id);

        if (!$session) {
            http_response_code(404);
            require __DIR__ . '/../../../views/errors/404.php';
            return;
        }

        require __DIR__ . '/../../../views/sessions/show.php';
    }

    /** Endpoint AJAX dipanggil dari views/sessions/show.php untuk polling status + QR. */
    public function refreshStatus(int $id): void
    {
        $user    = AuthMiddleware::handle();
        $session = $this->sessions->findForUser($user['id'], $id);

        if (!$session) {
            Response::json(['success' => false, 'error' => ['code' => 'SESSION_NOT_FOUND', 'message' => 'Session tidak ditemukan']], 404);
            return;
        }

        try {
            $waha   = new WahaService();
            $remote = $waha->getSession($session['waha_session_name']);
            $status = $remote['status'] ?? $session['status'];

            $qrDataUri = null;
            if (in_array($status, ['SCAN_QR_CODE', 'SCAN_QR'], true)) {
                $qrDataUri = $waha->getQrCodeBase64($session['waha_session_name']);
            }

            $this->sessions->updateStatus($id, $status, $qrDataUri);
            if ($status === 'WORKING') {
                $this->sessions->clearQr($id);
            }

            Response::json(['success' => true, 'data' => ['status' => $status, 'qr' => $qrDataUri]]);
        } catch (Throwable $e) {
            error_log('[waha] Gagal refresh status session #' . $id . ': ' . $e->getMessage());
            Response::json(['success' => false, 'error' => ['code' => 'WAHA_ERROR', 'message' => 'Gagal menghubungi WAHA']], 502);
        }
    }

    public function stop(int $id): void
    {
        $user    = AuthMiddleware::handle();
        $session = $this->sessions->findForUser($user['id'], $id);

        if ($session) {
            try {
                (new WahaService())->stopSession($session['waha_session_name']);
                $this->sessions->updateStatus($id, 'STOPPED');
            } catch (Throwable $e) {
                error_log('[waha] Gagal stop session #' . $id . ': ' . $e->getMessage());
            }
        }

        Response::redirect('/sessions/' . $id);
    }

    public function logoutSession(int $id): void
    {
        $user    = AuthMiddleware::handle();
        $session = $this->sessions->findForUser($user['id'], $id);

        if ($session) {
            try {
                (new WahaService())->logoutSession($session['waha_session_name']);
                $this->sessions->updateStatus($id, 'LOGGED_OUT');
            } catch (Throwable $e) {
                error_log('[waha] Gagal logout session #' . $id . ': ' . $e->getMessage());
            }
        }

        Response::redirect('/sessions/' . $id);
    }
}
