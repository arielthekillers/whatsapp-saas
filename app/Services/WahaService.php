<?php
declare(strict_types=1);

namespace App\Services;

use App\Config\Env;
use RuntimeException;

/**
 * WahaService — satu-satunya kelas yang boleh berkomunikasi langsung
 * dengan WAHA REST API. Tidak ada bagian lain aplikasi yang boleh
 * melakukan HTTP request ke WAHA_BASE_URL selain lewat kelas ini.
 *
 * PENTING (lihat aturan #32 di spesifikasi project):
 * Endpoint di bawah ini mengikuti dokumentasi publik WAHA
 * (https://waha.devlike.pro/docs/) per saat kode ini ditulis:
 *   - POST   /api/sessions                         (create + start session)
 *   - GET    /api/sessions[?all=true]               (list sessions)
 *   - GET    /api/sessions/{session}                (detail session)
 *   - POST   /api/sessions/{session}/stop
 *   - POST   /api/sessions/{session}/restart
 *   - POST   /api/sessions/{session}/logout
 *   - DELETE /api/sessions/{session}
 *   - GET    /api/{session}/auth/qr?format=image    (QR sebagai image/png)
 *   - POST   /api/sendText   {session, chatId, text}
 *   - POST   /api/sendImage  {session, chatId, file:{mimetype,url,filename}, caption}
 *   - POST   /api/sendFile   {session, chatId, file:{mimetype,url,filename}}
 *   - POST   /api/sendLocation {session, chatId, latitude, longitude, title}
 *   - POST   /api/sendContactVcard {session, chatId, contacts:[...]}
 *
 * chatId WhatsApp personal berformat "<nomor_tanpa_plus>@c.us" (mis. "628123456789@c.us").
 *
 * Jika instalasi WAHA-mu berbeda versi/API (mis. masih memakai
 * endpoint legacy /api/session/start), SEMUA penyesuaian cukup
 * dilakukan di file ini. Sebelum production, cocokkan dengan Swagger
 * UI di {WAHA_BASE_URL}/ milikmu sendiri.
 */
class WahaService
{
    private string $baseUrl;
    private string $apiKey;
    private int $timeout;

    public function __construct(?string $baseUrl = null, ?string $apiKey = null)
    {
        $this->baseUrl = rtrim($baseUrl ?? (string) Env::get('WAHA_BASE_URL', ''), '/');
        $this->apiKey  = $apiKey ?? (string) Env::get('WAHA_API_KEY', '');
        $this->timeout = (int) Env::get('WAHA_TIMEOUT', 15);

        if ($this->baseUrl === '') {
            throw new RuntimeException('WAHA_BASE_URL belum dikonfigurasi di .env');
        }
    }

    /** Format nomor telepon (628xxxx) menjadi chatId WAHA. */
    public static function toChatId(string $phoneNumber): string
    {
        $digits = preg_replace('/\D/', '', $phoneNumber);
        return $digits . '@c.us';
    }

    // ---------------------------------------------------------------
    // Session management
    // ---------------------------------------------------------------

    public function createAndStartSession(string $sessionName, array $webhooks = []): array
    {
        $body = ['name' => $sessionName, 'start' => true];
        if (!empty($webhooks)) {
            $body['config'] = ['webhooks' => $webhooks];
        }
        return $this->request('POST', '/api/sessions', $body);
    }

    public function getSession(string $sessionName): array
    {
        return $this->request('GET', '/api/sessions/' . rawurlencode($sessionName));
    }

    public function getSessions(bool $all = false): array
    {
        return $this->request('GET', '/api/sessions' . ($all ? '?all=true' : ''));
    }

    public function stopSession(string $sessionName): array
    {
        return $this->request('POST', '/api/sessions/' . rawurlencode($sessionName) . '/stop');
    }

    public function restartSession(string $sessionName): array
    {
        return $this->request('POST', '/api/sessions/' . rawurlencode($sessionName) . '/restart');
    }

    public function logoutSession(string $sessionName): array
    {
        return $this->request('POST', '/api/sessions/' . rawurlencode($sessionName) . '/logout');
    }

    public function deleteSession(string $sessionName): array
    {
        return $this->request('DELETE', '/api/sessions/' . rawurlencode($sessionName));
    }

    // ---------------------------------------------------------------
    // QR
    // ---------------------------------------------------------------

    public function getQrCodeBase64(string $sessionName): string
    {
        $raw = $this->requestRaw(
            'GET',
            '/api/' . rawurlencode($sessionName) . '/auth/qr?format=image',
            null,
            ['Accept: image/png']
        );
        return 'data:image/png;base64,' . base64_encode($raw);
    }

    // ---------------------------------------------------------------
    // Messaging
    // ---------------------------------------------------------------

    public function sendText(string $sessionName, string $chatId, string $text): array
    {
        return $this->request('POST', '/api/sendText', [
            'session' => $sessionName,
            'chatId'  => $chatId,
            'text'    => $text,
        ]);
    }

    public function sendImage(string $sessionName, string $chatId, string $url, string $mimetype = 'image/jpeg', ?string $filename = null, ?string $caption = null): array
    {
        return $this->request('POST', '/api/sendImage', [
            'session' => $sessionName,
            'chatId'  => $chatId,
            'file'    => array_filter([
                'mimetype' => $mimetype,
                'url'      => $url,
                'filename' => $filename,
            ]),
            'caption' => $caption,
        ]);
    }

    public function sendFile(string $sessionName, string $chatId, string $url, ?string $mimetype = null, ?string $filename = null): array
    {
        return $this->request('POST', '/api/sendFile', [
            'session' => $sessionName,
            'chatId'  => $chatId,
            'file'    => array_filter([
                'mimetype' => $mimetype,
                'url'      => $url,
                'filename' => $filename,
            ]),
        ]);
    }

    public function sendLocation(string $sessionName, string $chatId, float $lat, float $lng, ?string $title = null): array
    {
        return $this->request('POST', '/api/sendLocation', array_filter([
            'session'   => $sessionName,
            'chatId'    => $chatId,
            'latitude'  => $lat,
            'longitude' => $lng,
            'title'     => $title,
        ], static fn ($v) => $v !== null));
    }

    public function sendContact(string $sessionName, string $chatId, array $contacts): array
    {
        return $this->request('POST', '/api/sendContactVcard', [
            'session'  => $sessionName,
            'chatId'   => $chatId,
            'contacts' => $contacts,
        ]);
    }

    // ---------------------------------------------------------------
    // HTTP internals
    // ---------------------------------------------------------------

    private function request(string $method, string $path, ?array $body = null): array
    {
        $raw = $this->requestRaw(
            $method,
            $path,
            $body !== null ? json_encode($body) : null,
            ['Content-Type: application/json', 'Accept: application/json']
        );

        if ($raw === '') {
            return [];
        }

        $decoded = json_decode($raw, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException('WAHA mengembalikan response non-JSON untuk ' . $method . ' ' . $path);
        }
        return $decoded ?? [];
    }

    private function requestRaw(string $method, string $path, ?string $rawBody, array $extraHeaders): string
    {
        $url = $this->baseUrl . $path;
        $ch  = curl_init($url);

        $headers = array_merge(['X-Api-Key: ' . $this->apiKey, 'Connection: keep-alive'], $extraHeaders);

        curl_setopt_array($ch, [
            CURLOPT_CUSTOMREQUEST  => $method,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_TIMEOUT        => $this->timeout,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_TCP_KEEPALIVE  => 1,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
        ]);

        if ($rawBody !== null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $rawBody);
        }

        $response = curl_exec($ch);
        $errno    = curl_errno($ch);
        $error    = curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($errno !== 0) {
            throw new RuntimeException("Gagal menghubungi WAHA ({$method} {$path}): {$error}");
        }

        if ($httpCode >= 400) {
            throw new RuntimeException("WAHA HTTP {$httpCode} untuk {$method} {$path}: " . substr((string) $response, 0, 300));
        }

        return (string) $response;
    }
}
