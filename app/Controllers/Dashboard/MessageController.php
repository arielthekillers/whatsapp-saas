<?php
declare(strict_types=1);

namespace App\Controllers\Dashboard;

use App\Middleware\AuthMiddleware;
use App\Repositories\MessageRepository;
use App\Repositories\SessionRepository;
use App\Repositories\SubscriptionRepository;
use App\Services\QuotaService;
use App\Services\WahaService;
use App\Helpers\Response;
use Throwable;

class MessageController
{
    private SessionRepository $sessions;
    private MessageRepository $messages;
    private SubscriptionRepository $subscriptions;

    public function __construct()
    {
        $this->sessions = new SessionRepository();
        $this->messages = new MessageRepository();
        $this->subscriptions = new SubscriptionRepository();
    }

    public function index(): void
    {
        $user = AuthMiddleware::handle();
        $userId = (int) $user['id'];

        $selectedSessionId = isset($_GET['session_id']) && $_GET['session_id'] !== '' ? (int)$_GET['session_id'] : null;
        $selectedType = isset($_GET['type']) && $_GET['type'] !== '' ? trim((string)$_GET['type']) : null;
        $search = isset($_GET['q']) ? trim((string)$_GET['q']) : '';

        // Ambil semua sesi milik user untuk dropdown filter
        $userSessions = $this->sessions->findAllForUser($userId);

        // Ambil daftar riwayat pesan dengan paginasi/filter
        $db = \App\Config\Database::connection();
        $sql = 'SELECT m.*, s.name as session_name 
                FROM messages m
                LEFT JOIN whatsapp_sessions s ON m.session_id = s.id
                WHERE m.user_id = :user_id';
        $params = [':user_id' => $userId];

        if ($selectedSessionId !== null) {
            $sql .= ' AND m.session_id = :session_id';
            $params[':session_id'] = $selectedSessionId;
        }

        if ($selectedType !== null) {
            $sql .= ' AND m.message_type = :message_type';
            $params[':message_type'] = $selectedType;
        }

        if ($search !== '') {
            $sql .= ' AND (m.recipient LIKE :q OR m.payload LIKE :q)';
            $params[':q'] = '%' . $search . '%';
        }

        $sql .= ' ORDER BY m.id DESC LIMIT 100';
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $messageLogs = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // Ambil info paket aktif
        $activeSub = $this->subscriptions->findActiveForUser($userId);

        // Variable mapping untuk views/messages/index.php
        $sessions = $userSessions;
        $messages = $messageLogs;
        $searchQuery = $search;

        $success = $_SESSION['flash_success'] ?? null;
        $error   = $_SESSION['flash_error'] ?? null;
        unset($_SESSION['flash_success'], $_SESSION['flash_error']);

        require __DIR__ . '/../../../views/messages/index.php';
    }

    public function send(): void
    {
        $user = AuthMiddleware::handle();
        $userId = (int) $user['id'];

        $sessionId = (int) ($_POST['session_id'] ?? 0);
        $recipient = trim((string) ($_POST['recipient'] ?? ''));
        $type      = trim(strtolower((string) ($_POST['message_type'] ?? $_POST['type'] ?? 'text')));
        $text      = trim((string) ($_POST['message_text'] ?? $_POST['text'] ?? ''));
        $mediaUrl  = trim((string) ($_POST['media_url'] ?? $_POST['url'] ?? ''));
        $filename  = trim((string) ($_POST['filename'] ?? ''));

        if ($sessionId <= 0 || $recipient === '') {
            $_SESSION['flash_error'] = 'Silakan pilih Sesi WhatsApp dan masukkan Nomor Tujuan.';
            Response::redirect('/messages');
            return;
        }

        if ($type === 'text' && $text === '') {
            $_SESSION['flash_error'] = 'Pesan bertipe Teks wajib mengisi kolom isi pesan.';
            Response::redirect('/messages');
            return;
        }

        if (in_array($type, ['image', 'video', 'file'], true) && $mediaUrl === '') {
            $_SESSION['flash_error'] = 'Pesan bertipe Media/File wajib menyertakan URL Media.';
            Response::redirect('/messages');
            return;
        }

        // Cek Sesi WA
        $session = $this->sessions->findByIdForUser($userId, $sessionId);
        if (!$session) {
            $_SESSION['flash_error'] = 'Sesi WhatsApp tidak ditemukan.';
            Response::redirect('/messages');
            return;
        }

        if ($session['status'] !== 'WORKING') {
            $_SESSION['flash_error'] = "Sesi WA '{$session['name']}' belum terhubung (Status: {$session['status']}).";
            Response::redirect('/messages');
            return;
        }

        // Cek Kuota
        $activeSub = $this->subscriptions->findActiveForUser($userId);
        if (!$activeSub) {
            $_SESSION['flash_error'] = 'Anda tidak memiliki paket langganan aktif.';
            Response::redirect('/messages');
            return;
        }

        $quota = new QuotaService($this->subscriptions);
        $res   = $quota->reserveMessage($userId);
        if (!$res['ok']) {
            $_SESSION['flash_error'] = $res['message'];
            Response::redirect('/messages');
            return;
        }

        // Kirim via WAHA Service
        $chatId = WahaService::toChatId($recipient);
        $waha   = new WahaService();
        $wahaSessionName = $session['waha_session_name'];

        try {
            $wahaRes = [];
            switch ($type) {
                case 'image':
                    $wahaRes = $waha->sendImage($wahaSessionName, $chatId, $mediaUrl, 'image/jpeg', $filename ?: null, $text ?: null);
                    break;
                case 'video':
                    $wahaRes = $waha->sendVideo($wahaSessionName, $chatId, $mediaUrl, 'video/mp4', $filename ?: null, $text ?: null);
                    break;
                case 'file':
                    $wahaRes = $waha->sendFile($wahaSessionName, $chatId, $mediaUrl, null, $filename ?: null, $text ?: null);
                    break;
                case 'text':
                default:
                    $wahaRes = $waha->sendText($wahaSessionName, $chatId, $text);
                    break;
            }

            $wahaMsgId = $wahaRes['id'] ?? null;
            $payload = [
                'type' => $type,
                'text' => $text,
                'url'  => $mediaUrl,
                'filename' => $filename
            ];

            // Simpan Log ke DB
            $this->messages->create(
                $userId,
                $sessionId,
                'outbound',
                $type,
                $recipient,
                null,
                $payload,
                'sent',
                $wahaMsgId
            );

            $_SESSION['flash_success'] = 'Pesan WhatsApp berhasil dikirim ke ' . htmlspecialchars($recipient) . '!';
        } catch (Throwable $e) {
            error_log('[MessageController] Error send: ' . $e->getMessage());
            $_SESSION['flash_error'] = 'Gagal mengirim pesan: ' . $e->getMessage();
        }

        Response::redirect('/messages');
    }
}
