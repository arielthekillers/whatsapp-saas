<?php
declare(strict_types=1);

require __DIR__ . '/../autoload.php';

use App\Config\Env;
use App\Repositories\WebhookRepository;
use App\Repositories\SessionRepository;

Env::load(__DIR__ . '/../.env');

// Membaca payload raw
$raw = file_get_contents('php://input');
if (!$raw) {
    http_response_code(400);
    echo json_encode(['error' => 'Empty payload']);
    exit;
}

$payload = json_decode($raw, true);
if (!is_array($payload)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON']);
    exit;
}

// Log mentah ke waha.log untuk keperluan debug
$logPath = __DIR__ . '/../storage/logs/waha.log';
$logDir = dirname($logPath);
if (!is_dir($logDir)) {
    mkdir($logDir, 0777, true);
}
file_put_contents($logPath, '[' . date('Y-m-d H:i:s') . '] ' . $raw . PHP_EOL, FILE_APPEND);

// Parsing data wajib dari WAHA Webhook
$eventId = (string) ($payload['id'] ?? '');
$eventType = (string) ($payload['event'] ?? '');
$sessionName = (string) ($payload['session'] ?? '');
$eventData = $payload['payload'] ?? [];

if ($eventType === '' || $sessionName === '') {
    http_response_code(400);
    echo json_encode(['error' => 'Missing event or session fields']);
    exit;
}

try {
    $sessionRepo = new SessionRepository();
    // Mendapatkan WAHA instance ID default
    $instanceId = $sessionRepo->getDefaultWahaInstanceId();

    $webhookRepo = new WebhookRepository();
    // Simpan ke webhook_inbound_events (idempoten berdasarkan waha_event_id + waha_instance_id)
    $webhookRepo->saveInboundEvent(
        $instanceId,
        $eventId !== '' ? $eventId : null,
        $eventType,
        $sessionName,
        $payload
    );

    // Kirim response 200 OK secepatnya ke WAHA agar koneksi tidak memblokir
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
} catch (\Exception $e) {
    // Log error jika database bermasalah
    $errorLog = __DIR__ . '/../storage/logs/error.log';
    file_put_contents($errorLog, '[' . date('Y-m-d H:i:s') . '] Webhook Error: ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
    
    // Tetap kembalikan 200 OK agar WAHA tidak membanjiri server dengan retry timeout
    http_response_code(200);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
