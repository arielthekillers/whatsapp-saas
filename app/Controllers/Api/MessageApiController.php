<?php
declare(strict_types=1);

namespace App\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Repositories\MessageRepository;
use App\Repositories\SessionRepository;
use App\Repositories\SubscriptionRepository;
use App\Repositories\UsageRepository;
use App\Services\QuotaService;
use App\Services\WahaService;
use App\Support\ApiAuth;
use Throwable;

class MessageApiController
{
    public function send(): void
    {
        $ctx    = ApiAuth::resolve();
        $userId = $ctx['user_id'];

        $body = json_decode((string) file_get_contents('php://input'), true);
        if (!is_array($body)) {
            ApiResponse::error('INVALID_JSON', 'Body request harus JSON valid.', 400);
        }

        $sessionName = trim((string) ($body['session'] ?? ''));
        $to          = trim((string) ($body['to'] ?? ''));
        $text        = (string) ($body['text'] ?? '');
        $type        = trim(strtolower((string) ($body['type'] ?? 'text'))); // text, image, file, location, contact
        
        $mediaUrl    = trim((string) ($body['url'] ?? ''));
        $mimetype    = trim((string) ($body['mimetype'] ?? ''));
        $filename    = trim((string) ($body['filename'] ?? ''));
        
        $latitude    = isset($body['latitude']) ? (float)$body['latitude'] : null;
        $longitude   = isset($body['longitude']) ? (float)$body['longitude'] : null;
        $locationTitle = trim((string) ($body['location_title'] ?? ''));
        
        $contacts    = $body['contacts'] ?? null; // array

        $idemKey     = trim((string) ($body['idempotency_key'] ?? ($_SERVER['HTTP_IDEMPOTENCY_KEY'] ?? '')));

        // Validasi parameter dasar
        if ($sessionName === '' || $to === '') {
            ApiResponse::error('VALIDATION_ERROR', 'Field session dan to wajib diisi.', 422);
        }

        if ($type === 'text' && $text === '') {
            ApiResponse::error('VALIDATION_ERROR', 'Pesan bertipe text wajib mengisi field text.', 422);
        }

        if (in_array($type, ['image', 'file'], true) && $mediaUrl === '') {
            ApiResponse::error('VALIDATION_ERROR', 'Pesan bertipe image/file wajib menyertakan field url.', 422);
        }

        if ($type === 'location' && ($latitude === null || $longitude === null)) {
            ApiResponse::error('VALIDATION_ERROR', 'Pesan bertipe location wajib menyertakan field latitude dan longitude.', 422);
        }

        if ($type === 'contact' && !is_array($contacts)) {
            ApiResponse::error('VALIDATION_ERROR', 'Pesan bertipe contact wajib menyertakan array contacts.', 422);
        }

        // Dapatkan informasi subscription & active plan
        $subscriptions = new SubscriptionRepository();
        $activeSub = $subscriptions->findActiveForUser($userId);
        
        if ($activeSub === null) {
            ApiResponse::error('NO_ACTIVE_SUBSCRIPTION', 'Akun Anda tidak memiliki paket langganan aktif.', 402);
        }

        $planName = strtoupper($activeSub['plan_name'] ?? 'LITE');

        // =====================================================================
        // FILTER FITUR SESUAI PAKET (SYSTEMATIC FEATURE FILTERING)
        // =====================================================================
        if ($type !== 'text' && $planName === 'LITE') {
            ApiResponse::error('FEATURE_RESTRICTED', 'Kirim media/file dibatasi pada paket LITE. Silakan upgrade ke paket PRO atau ENTERPRISE.', 403);
        }

        if (in_array($type, ['location', 'contact'], true) && $planName === 'PRO') {
            ApiResponse::error('FEATURE_RESTRICTED', 'Kirim pesan Lokasi dan Kontak dibatasi pada paket PRO. Silakan upgrade ke paket ENTERPRISE.', 403);
        }

        // =====================================================================
        // Idempotency check
        // =====================================================================
        $messages = new MessageRepository();
        if ($idemKey !== '') {
            $existing = $messages->findByIdempotency($userId, $idemKey);
            if ($existing !== null) {
                ApiResponse::success([
                    'message_id'        => $existing['waha_message_id'],
                    'status'            => $existing['status'],
                    'idempotent_replay' => true,
                ]);
            }
        }

        // Dapatkan Session
        $sessions = new SessionRepository();
        $session  = $sessions->findByNameForUser($userId, $sessionName);

        if ($session === null) {
            ApiResponse::error('SESSION_NOT_FOUND', 'WhatsApp session tidak ditemukan.', 404);
        }
        if ($session['status'] !== 'WORKING') {
            ApiResponse::error('SESSION_NOT_READY', "Session belum terhubung (status saat ini: {$session['status']}).", 409);
        }

        // Potong/Reservasi Kuota
        $quota       = new QuotaService($subscriptions);
        $reservation = $quota->reserveMessage($userId);
        if (!$reservation['ok']) {
            $status = $reservation['code'] === 'QUOTA_EXCEEDED' ? 429 : 402;
            ApiResponse::error($reservation['code'], $reservation['message'], $status);
        }

        $chatId = WahaService::toChatId($to);
        $waha = new WahaService();

        try {
            // Pengiriman via WAHA sesuai Tipe Pesan
            switch ($type) {
                case 'image':
                    $result = $waha->sendImage($session['waha_session_name'], $chatId, $mediaUrl, $mimetype !== '' ? $mimetype : 'image/jpeg', $filename !== '' ? $filename : null, $text !== '' ? $text : null);
                    break;
                case 'file':
                    $result = $waha->sendFile($session['waha_session_name'], $chatId, $mediaUrl, $mimetype !== '' ? $mimetype : null, $filename !== '' ? $filename : null);
                    break;
                case 'location':
                    $result = $waha->sendLocation($session['waha_session_name'], $chatId, $latitude, $longitude, $locationTitle !== '' ? $locationTitle : null);
                    break;
                case 'contact':
                    $result = $waha->sendContact($session['waha_session_name'], $chatId, $contacts);
                    break;
                case 'text':
                default:
                    $result = $waha->sendText($session['waha_session_name'], $chatId, $text);
                    break;
            }
        } catch (Throwable $e) {
            error_log('[waha] Gagal kirim pesan type ' . $type . ' user #' . $userId . ': ' . $e->getMessage());
            ApiResponse::error('WAHA_ERROR', 'Gagal mengirim pesan via WhatsApp API: ' . $e->getMessage(), 502);
        }

        $wahaMessageId = $result['id'] ?? ($result['_data']['id'] ?? null);

        $payloadLog = [
            'type' => $type,
            'chatId' => $chatId,
            'text' => $text,
            'url' => $mediaUrl,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'contacts' => $contacts
        ];

        $messageId = $messages->create(
            $userId,
            (int) $session['id'],
            'outbound',
            $type,
            $to,
            $idemKey !== '' ? $idemKey : null,
            array_filter($payloadLog, static fn($v) => $v !== null),
            'sent',
            is_string($wahaMessageId) ? $wahaMessageId : null
        );

        (new UsageRepository())->log($userId, (int) $session['id'], 'message_sent');

        ApiResponse::success([
            'message_id' => $wahaMessageId ?? (string) $messageId,
        ]);
    }
}
