<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Config\Database;
use App\Config\Env;
use App\Helpers\Audit;
use App\Helpers\Csrf;
use App\Helpers\Response;
use App\Middleware\AdminMiddleware;
use App\Repositories\PlanRepository;
use PDO;

class AdminController
{
    private PDO $db;
    private PlanRepository $plans;

    public function __construct()
    {
        $this->db = Database::connection();
        $this->plans = new PlanRepository();
    }

    public function index(): void
    {
        $user = AdminMiddleware::handle();

        // 1. Stats ringkasan
        $totalUsers   = (int) $this->db->query('SELECT COUNT(*) FROM users')->fetchColumn();
        $totalSessions = (int) $this->db->query('SELECT COUNT(*) FROM whatsapp_sessions')->fetchColumn();
        $totalRevenue  = (float) $this->db->query('SELECT COALESCE(SUM(amount), 0) FROM payments WHERE status = "paid"')->fetchColumn();
        $pendingPayments = (int) $this->db->query('SELECT COUNT(*) FROM payments WHERE status IN ("pending","verifying")')->fetchColumn();

        // 2. Semua user + subscription aktif
        $stmtUsers = $this->db->prepare('
            SELECT u.id AS user_id, u.name, u.email, u.status AS user_status, u.created_at,
                   s.id AS sub_id, s.end_at, s.status AS sub_status,
                   p.name AS plan_name, p.id AS plan_id,
                   usg.messages_used, usg.messages_limit
            FROM users u
            LEFT JOIN subscriptions s ON u.id = s.user_id AND s.status = "active"
            LEFT JOIN plans p ON s.plan_id = p.id
            LEFT JOIN subscription_usage usg ON s.id = usg.subscription_id
            ORDER BY u.id DESC
        ');
        $stmtUsers->execute();
        $usersList = $stmtUsers->fetchAll(PDO::FETCH_ASSOC);

        // 3. Pembayaran pending verifikasi
        $stmtPending = $this->db->prepare('
            SELECT p.*, u.name AS user_name, u.email AS user_email,
                   pl.name AS plan_name, pl.id AS plan_id, pl.message_limit, pl.duration_days
            FROM payments p
            JOIN users u ON u.id = p.user_id
            LEFT JOIN plans pl ON pl.id = p.plan_id
            WHERE p.status IN ("pending", "verifying")
            ORDER BY p.id DESC
        ');
        $stmtPending->execute();
        $pendingList = $stmtPending->fetchAll(PDO::FETCH_ASSOC);

        // 4. Daftar paket aktif
        $allPlans = $this->plans->findAllActive();

        // 5. WAHA Health Check
        $wahaUrl = (string) Env::get('WAHA_BASE_URL', 'http://localhost:3000');
        $wahaStatus = $this->checkWahaHealth($wahaUrl);

        // 6. Job Queue Stats
        $jobStats = [
            'pending'   => (int) $this->db->query('SELECT COUNT(*) FROM jobs WHERE status = "pending"')->fetchColumn(),
            'completed' => (int) $this->db->query('SELECT COUNT(*) FROM jobs WHERE status = "completed"')->fetchColumn(),
            'failed'    => (int) $this->db->query('SELECT COUNT(*) FROM jobs WHERE status = "failed"')->fetchColumn(),
        ];

        // 7. Pengumuman Aktif
        $activeAnnouncement = $this->db->query('SELECT * FROM announcements WHERE is_active = 1 ORDER BY id DESC LIMIT 1')->fetch(PDO::FETCH_ASSOC);

        // 8. Audit Logs
        $auditLogs = $this->db->query('
            SELECT a.*, u.name AS admin_name
            FROM audit_logs a
            LEFT JOIN users u ON u.id = a.admin_id
            ORDER BY a.id DESC LIMIT 15
        ')->fetchAll(PDO::FETCH_ASSOC);

        $success = $_SESSION['flash_admin_success'] ?? null;
        unset($_SESSION['flash_admin_success']);
        $error = $_SESSION['flash_admin_error'] ?? null;
        unset($_SESSION['flash_admin_error']);

        require __DIR__ . '/../../../views/admin/index.php';
    }

    /** Cek status koneksi WAHA */
    private function checkWahaHealth(string $baseUrl): array
    {
        $ch = curl_init(rtrim($baseUrl, '/') . '/api/version');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 3,
            CURLOPT_CONNECTTIMEOUT => 2,
        ]);
        $startTime = microtime(true);
        $res = curl_exec($ch);
        $latency = round((microtime(true) - $startTime) * 1000);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode >= 200 && $httpCode < 400) {
            return ['status' => 'ONLINE', 'latency' => $latency, 'url' => $baseUrl];
        }
        return ['status' => 'OFFLINE', 'latency' => 0, 'url' => $baseUrl];
    }

    /** POST /admin/payment/approve */
    public function approvePayment(): void
    {
        $admin = AdminMiddleware::handle();

        if (!Csrf::verify($_POST['_csrf'] ?? null)) {
            Response::redirect('/admin');
        }

        $paymentId = (int) ($_POST['payment_id'] ?? 0);

        $this->db->beginTransaction();
        try {
            $stmt = $this->db->prepare('
                SELECT p.*, pl.id AS plan_id, pl.message_limit, pl.duration_days, pl.name AS plan_name
                FROM payments p
                JOIN plans pl ON pl.id = p.plan_id
                WHERE p.id = :id AND p.status IN ("pending","verifying")
                FOR UPDATE
            ');
            $stmt->execute([':id' => $paymentId]);
            $payment = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$payment) {
                throw new \Exception('Pembayaran tidak ditemukan atau sudah diproses.');
            }

            $parts  = explode(':', $payment['provider'] ?? '');
            $months = isset($parts[1]) ? (int)$parts[1] : 1;
            $scaledLimit = (int) $payment['message_limit'] * $months;

            // Check current active subscription for user
            $subRepo = new \App\Repositories\SubscriptionRepository();
            $currentActive = $subRepo->findActiveForUser($payment['user_id']);

            $isUpgrade = ($currentActive === null) || ((float)$payment['price'] > (float)($currentActive['plan_price'] ?? 0));

            if ($isUpgrade) {
                // INSTANT UPGRADE: Cancel old sub, activate new sub immediately
                if ($currentActive) {
                    $this->db->prepare('
                        UPDATE subscriptions SET status = "cancelled"
                        WHERE id = :sub_id
                    ')->execute([':sub_id' => $currentActive['subscription_id']]);
                }

                $stmtSub = $this->db->prepare('
                    INSERT INTO subscriptions (user_id, plan_id, start_at, end_at, status)
                    VALUES (:user_id, :plan_id, NOW(), DATE_ADD(NOW(), INTERVAL :months MONTH), "active")
                ');
                $stmtSub->execute([
                    ':user_id' => $payment['user_id'],
                    ':plan_id' => $payment['plan_id'],
                    ':months'  => $months,
                ]);
                $subId = (int) $this->db->lastInsertId();

                $this->db->prepare('
                    INSERT INTO subscription_usage (subscription_id, messages_used, messages_limit)
                    VALUES (:sub_id, 0, :limit)
                ')->execute([':sub_id' => $subId, ':limit' => $scaledLimit]);

                $this->db->prepare('
                    UPDATE payments
                    SET status = "paid", paid_at = NOW(), subscription_id = :sub_id
                    WHERE id = :id
                ')->execute([':sub_id' => $subId, ':id' => $paymentId]);

                $this->db->commit();

                Audit::log($admin['id'], 'APPROVE_INSTANT_UPGRADE', 'payment', (string) $paymentId, [
                    'user_id'  => $payment['user_id'],
                    'amount'   => $payment['amount'],
                    'plan'     => $payment['plan_name'],
                    'months'   => $months
                ]);

                $_SESSION['flash_admin_success'] = "✅ Pembayaran #{$payment['external_id']} disetujui. Instant Upgrade ke paket {$payment['plan_name']} telah AKTIF.";
            } else {
                // SCHEDULED DOWNGRADE / EXTENSION QUEUE: Schedule after current sub ends
                $queueStart = $currentActive['end_at'];

                $stmtSub = $this->db->prepare('
                    INSERT INTO subscriptions (user_id, plan_id, start_at, end_at, status)
                    VALUES (:user_id, :plan_id, :queue_start, DATE_ADD(:queue_start, INTERVAL :months MONTH), "queued")
                ');
                $stmtSub->execute([
                    ':user_id'     => $payment['user_id'],
                    ':plan_id'     => $payment['plan_id'],
                    ':queue_start' => $queueStart,
                    ':months'      => $months,
                ]);
                $subId = (int) $this->db->lastInsertId();

                $this->db->prepare('
                    INSERT INTO subscription_usage (subscription_id, messages_used, messages_limit)
                    VALUES (:sub_id, 0, :limit)
                ')->execute([':sub_id' => $subId, ':limit' => $scaledLimit]);

                $this->db->prepare('
                    UPDATE payments
                    SET status = "paid", paid_at = NOW(), subscription_id = :sub_id
                    WHERE id = :id
                ')->execute([':sub_id' => $subId, ':id' => $paymentId]);

                $this->db->commit();

                Audit::log($admin['id'], 'APPROVE_SCHEDULED_QUEUE', 'payment', (string) $paymentId, [
                    'user_id'     => $payment['user_id'],
                    'amount'      => $payment['amount'],
                    'plan'        => $payment['plan_name'],
                    'months'      => $months,
                    'queue_start' => $queueStart
                ]);

                $_SESSION['flash_admin_success'] = "✅ Pembayaran #{$payment['external_id']} disetujui. Paket {$payment['plan_name']} telah DIANTREKAN dan akan otomatis aktif pada {$queueStart} setelah paket aktif saat ini selesai.";
            }

            Response::redirect('/admin');
        } catch (\Exception $e) {
            $this->db->rollBack();
            $_SESSION['flash_admin_error'] = 'Gagal approve pembayaran: ' . $e->getMessage();
            Response::redirect('/admin');
        }
    }

    /** POST /admin/payment/reject */
    public function rejectPayment(): void
    {
        $admin = AdminMiddleware::handle();

        if (!Csrf::verify($_POST['_csrf'] ?? null)) {
            Response::redirect('/admin');
        }

        $paymentId = (int) ($_POST['payment_id'] ?? 0);
        $reason    = trim($_POST['reason'] ?? 'Pembayaran tidak valid.');

        $this->db->prepare('
            UPDATE payments
            SET status = "expired", transfer_note = CONCAT(IFNULL(transfer_note, ""), " | Ditolak: ", :reason)
            WHERE id = :id AND status IN ("pending","verifying")
        ')->execute([':reason' => $reason, ':id' => $paymentId]);

        Audit::log($admin['id'], 'REJECT_PAYMENT', 'payment', (string) $paymentId, ['reason' => $reason]);

        $_SESSION['flash_admin_success'] = "❌ Pembayaran #{$paymentId} telah ditolak.";
        Response::redirect('/admin');
    }

    /** POST /admin/plan/update */
    public function updatePlan(): void
    {
        $admin = AdminMiddleware::handle();

        if (!Csrf::verify($_POST['_csrf'] ?? null)) {
            Response::redirect('/admin');
        }

        $targetUserId = (int) ($_POST['user_id'] ?? 0);
        $newPlanId    = (int) ($_POST['plan_id'] ?? 0);

        $plan = $this->plans->findById($newPlanId);
        if (!$plan) {
            $_SESSION['flash_admin_error'] = 'Paket tidak valid.';
            Response::redirect('/admin');
            return;
        }

        $this->db->beginTransaction();
        try {
            $this->db->prepare('
                UPDATE subscriptions SET status = "cancelled"
                WHERE user_id = :uid AND status = "active"
            ')->execute([':uid' => $targetUserId]);

            $stmtSub = $this->db->prepare('
                INSERT INTO subscriptions (user_id, plan_id, start_at, end_at, status)
                VALUES (:user_id, :plan_id, NOW(), DATE_ADD(NOW(), INTERVAL :duration DAY), "active")
            ');
            $stmtSub->execute([
                ':user_id'  => $targetUserId,
                ':plan_id'  => $newPlanId,
                ':duration' => $plan['duration_days'],
            ]);
            $subId = (int) $this->db->lastInsertId();

            $this->db->prepare('
                INSERT INTO subscription_usage (subscription_id, messages_used, messages_limit)
                VALUES (:sub_id, 0, :limit)
            ')->execute([':sub_id' => $subId, ':limit' => $plan['message_limit']]);

            $this->db->commit();

            Audit::log($admin['id'], 'OVERRIDE_PLAN', 'user', (string) $targetUserId, ['new_plan' => $plan['name']]);

            $_SESSION['flash_admin_success'] = "Paket pengguna #{$targetUserId} diperbarui menjadi {$plan['name']}.";
            Response::redirect('/admin');
        } catch (\Exception $e) {
            $this->db->rollBack();
            $_SESSION['flash_admin_error'] = 'Gagal: ' . $e->getMessage();
            Response::redirect('/admin');
        }
    }

    /** POST /admin/user/status — Suspend/Ban/Activate User */
    public function updateUserStatus(): void
    {
        $admin = AdminMiddleware::handle();

        if (!Csrf::verify($_POST['_csrf'] ?? null)) {
            Response::redirect('/admin');
        }

        $targetUserId = (int) ($_POST['user_id'] ?? 0);
        $newStatus    = trim((string) ($_POST['status'] ?? 'active'));

        if (!in_array($newStatus, ['active', 'suspended', 'banned'], true)) {
            $_SESSION['flash_admin_error'] = 'Status tidak valid.';
            Response::redirect('/admin');
            return;
        }

        $stmt = $this->db->prepare('UPDATE users SET status = :status WHERE id = :id');
        $stmt->execute([':status' => $newStatus, ':id' => $targetUserId]);

        Audit::log($admin['id'], 'CHANGE_USER_STATUS', 'user', (string) $targetUserId, ['new_status' => $newStatus]);

        $_SESSION['flash_admin_success'] = "Status pengguna #{$targetUserId} diubah menjadi '{$newStatus}'.";
        Response::redirect('/admin');
    }

    /** POST /admin/jobs/retry-failed — Process failed background jobs again */
    public function retryFailedJobs(): void
    {
        $admin = AdminMiddleware::handle();

        if (!Csrf::verify($_POST['_csrf'] ?? null)) {
            Response::redirect('/admin');
        }

        $stmt = $this->db->prepare('UPDATE jobs SET status = "pending", attempts = 0 WHERE status = "failed"');
        $stmt->execute();
        $count = $stmt->rowCount();

        Audit::log($admin['id'], 'RETRY_FAILED_JOBS', 'job_queue', null, ['count' => $count]);

        $_SESSION['flash_admin_success'] = "{$count} job gagal dikembalikan ke antrean pending.";
        Response::redirect('/admin');
    }

    /** POST /admin/announcement — Broadcast Announcement */
    public function saveAnnouncement(): void
    {
        $admin = AdminMiddleware::handle();

        if (!Csrf::verify($_POST['_csrf'] ?? null)) {
            Response::redirect('/admin');
        }

        $message = trim((string) ($_POST['message'] ?? ''));
        $type    = trim((string) ($_POST['type'] ?? 'info'));

        if ($message === '') {
            $_SESSION['flash_admin_error'] = 'Pesan pengumuman wajib diisi.';
            Response::redirect('/admin');
            return;
        }

        $this->db->exec('UPDATE announcements SET is_active = 0');

        $stmt = $this->db->prepare('INSERT INTO announcements (message, type, is_active, created_at) VALUES (:msg, :type, 1, NOW())');
        $stmt->execute([':msg' => $message, ':type' => $type]);

        Audit::log($admin['id'], 'CREATE_ANNOUNCEMENT', 'announcement', (string) $this->db->lastInsertId(), ['message' => $message]);

        $_SESSION['flash_admin_success'] = 'Pengumuman sistem berhasil dipublikasikan!';
        Response::redirect('/admin');
    }

    /** POST /admin/announcement/delete */
    public function deleteAnnouncement(): void
    {
        $admin = AdminMiddleware::handle();

        if (!Csrf::verify($_POST['_csrf'] ?? null)) {
            Response::redirect('/admin');
        }

        $this->db->exec('UPDATE announcements SET is_active = 0');
        Audit::log($admin['id'], 'DELETE_ANNOUNCEMENT', 'announcement', null);

        $_SESSION['flash_admin_success'] = 'Pengumuman dinonaktifkan.';
        Response::redirect('/admin');
    }

    /** GET /admin/export-payments — Export Payment Records to CSV */
    public function exportPaymentsCsv(): void
    {
        AdminMiddleware::handle();

        $stmt = $this->db->query('
            SELECT p.external_id, u.name AS user_name, u.email AS user_email,
                   pl.name AS plan_name, p.amount, p.status, p.created_at, p.paid_at
            FROM payments p
            JOIN users u ON u.id = p.user_id
            LEFT JOIN plans pl ON pl.id = p.plan_id
            ORDER BY p.id DESC
        ');
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=payments_export_' . date('Y-m-d') . '.csv');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['External ID', 'User Name', 'User Email', 'Plan', 'Amount', 'Status', 'Created At', 'Paid At']);

        foreach ($rows as $row) {
            fputcsv($output, $row);
        }
        fclose($output);
        exit;
    }
}
