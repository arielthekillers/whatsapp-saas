<?php
declare(strict_types=1);

namespace App\Services;

use App\Config\Database;
use App\Helpers\Crypto;
use App\Repositories\WebhookRepository;
use PDO;
use RuntimeException;

class WebhookDispatchService
{
    private PDO $db;
    private WebhookRepository $webhooks;

    public function __construct()
    {
        $this->db = Database::connection();
        $this->webhooks = new WebhookRepository();
    }

    /**
     * Mengirim payload webhook ke URL customer dengan HMAC signature.
     */
    public function dispatch(int $webhookLogId): void
    {
        // 1. Ambil log webhook beserta data webhooknya
        $stmt = $this->db->prepare('
            SELECT wl.*, w.url, w.secret_enc 
            FROM webhook_logs wl
            JOIN webhooks w ON wl.webhook_id = w.id
            WHERE wl.id = :id LIMIT 1
        ');
        $stmt->execute([':id' => $webhookLogId]);
        $log = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$log) {
            throw new RuntimeException("Webhook log #{$webhookLogId} not found.");
        }

        $url = $log['url'];
        $secretEnc = $log['secret_enc'];
        $payloadJson = $log['payload']; // Mentah berbentuk string JSON dari DB
        $event = $log['event'];

        // 2. Dekripsi secret key untuk penandatanganan HMAC
        $secret = '';
        try {
            $secret = Crypto::decrypt($secretEnc);
        } catch (\Exception $e) {
            // Jika dekripsi gagal, gunakan string kosong
            $secret = '';
        }

        // 3. Hitung signature HMAC-SHA256
        $signature = hash_hmac('sha256', $payloadJson, $secret);

        // 4. Lakukan POST cURL
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payloadJson);
        curl_setopt_array($ch, [
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'User-Agent: Wapify-Webhook-Dispatcher/1.0',
                'X-Wapify-Event: ' . $event,
                'X-Wapify-Signature: ' . $signature
            ],
            CURLOPT_TIMEOUT => 10,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_SSL_VERIFYPEER => false // untuk mempermudah dev lokal
        ]);

        $responseBody = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        // 5. Catat hasil pengiriman ke database
        if ($curlError) {
            $status = 'failed';
            $responseBody = 'cURL Error: ' . $curlError;
            $this->webhooks->updateWebhookLogStatus($webhookLogId, 0, $responseBody, $status);
            throw new RuntimeException("cURL failed sending to {$url}: {$curlError}");
        } else {
            $status = ($httpCode >= 200 && $httpCode < 300) ? 'success' : 'failed';
            $this->webhooks->updateWebhookLogStatus($webhookLogId, $httpCode, $responseBody, $status);

            if ($status === 'failed') {
                throw new RuntimeException("Webhook endpoint returned non-2xx status code: {$httpCode}");
            }
        }
    }
}
