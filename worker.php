<?php
declare(strict_types=1);

require __DIR__ . '/autoload.php';

use App\Config\Env;
use App\Config\Database;
use App\Repositories\JobRepository;
use App\Services\WahaWebhookService;
use App\Services\WebhookDispatchService;

Env::load(__DIR__ . '/.env');

$db = Database::connection();
$jobRepo = new JobRepository();
$wahaWebhookService = new WahaWebhookService();
$webhookDispatchService = new WebhookDispatchService();

echo "Wapify Queue Worker started. Press Ctrl+C to stop.\n";

// Set timeout tak terbatas untuk CLI script
set_time_limit(0);

while (true) {
    $hasWork = false;

    // ==========================================
    // 1. PROSES WEBHOOK MENTAH DARI WAHA (INBOUND)
    // ==========================================
    try {
        $db->beginTransaction();
        
        $stmt = $db->prepare('
            SELECT * FROM webhook_inbound_events 
            WHERE processed_at IS NULL 
            ORDER BY id ASC 
            LIMIT 10
            FOR UPDATE SKIP LOCKED
        ');
        $stmt->execute();
        $inboundEvents = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($inboundEvents)) {
            $hasWork = true;
            $ids = array_column($inboundEvents, 'id');
            $idList = implode(',', array_map('intval', $ids));
            
            // Tandai diproses terlebih dahulu agar lock segera dilepas setelah commit
            $db->exec("UPDATE webhook_inbound_events SET processed_at = NOW() WHERE id IN ($idList)");
            $db->commit();

            echo "[" . date('Y-m-d H:i:s') . "] Memproses " . count($inboundEvents) . " event masuk dari WAHA...\n";
            
            foreach ($inboundEvents as $event) {
                try {
                    $rawPayload = json_decode($event['raw_payload'], true) ?? [];
                    $wahaWebhookService->processEvent(
                        (int) $event['id'],
                        $event['event_type'],
                        $event['session_name'],
                        $rawPayload
                    );
                } catch (\Exception $ex) {
                    echo "Error memproses inbound event #{$event['id']}: " . $ex->getMessage() . "\n";
                }
            }
        } else {
            $db->commit();
        }
    } catch (\Exception $e) {
        if ($db->inTransaction()) {
            $db->rollBack();
        }
        echo "Error database inbound: " . $e->getMessage() . "\n";
    }

    // ==========================================
    // 2. PROSES ANTREAN BACKGROUND JOBS (OUTBOUND)
    // ==========================================
    try {
        $jobs = $jobRepo->fetchAndLock(5);
        if (!empty($jobs)) {
            $hasWork = true;
            foreach ($jobs as $job) {
                $jobId = (int) $job['id'];
                $type = $job['type'];
                $payload = json_decode($job['payload'], true) ?? [];
                
                echo "[" . date('Y-m-d H:i:s') . "] Memproses background job #{$jobId} [{$type}]...\n";

                try {
                    if ($type === 'webhook_delivery') {
                        $logId = (int) ($payload['webhook_log_id'] ?? 0);
                        $webhookDispatchService->dispatch($logId);
                    }
                    
                    // Sukses: tandai selesai
                    $jobRepo->markCompleted($jobId);
                    echo "Job #{$jobId} selesai.\n";
                } catch (\Exception $ex) {
                    echo "Gagal memproses job #{$jobId}: " . $ex->getMessage() . "\n";
                    
                    // Gagal: lakukan exponential backoff retry (maksimal 5 kali coba)
                    $attempts = (int) $job['attempts'];
                    $delay = 30 * pow(2, $attempts); // 30s, 60s, 120s, 240s...
                    $jobRepo->releaseOrFail($jobId, $delay, 5);
                }
            }
        }
    } catch (\Exception $e) {
        echo "Error background jobs: " . $e->getMessage() . "\n";
    }

    // Jika tidak ada antrean kerja, tidur selama 1 detik untuk menghemat CPU
    if (!$hasWork) {
        sleep(1);
    }
}
