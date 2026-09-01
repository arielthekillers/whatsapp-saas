<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Config\Database;
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

        $success = $_SESSION['flash_admin_success'] ?? null;
        unset($_SESSION['flash_admin_success']);
        $error = $_SESSION['flash_admin_error'] ?? null;
        unset($_SESSION['flash_admin_error']);

        require __DIR__ . '/../../../views/admin/index.php';
    }

    /**
     * POST /admin/payment/approve
     * Admin approves a pending/verifying payment → activates subscription.
     */
    public function approvePayment(): void
    {
        $user = AdminMiddleware::handle();

        if (!Csrf::verify($_POST['_csrf'] ?? null)) {
            Response::redirect('/admin');
        }

        $paymentId = (int) ($_POST['payment_id'] ?? 0);
        $months    = (int) ($_POST['months'] ?? 1);

        $this->db->beginTransaction();
        try {
            $stmt = $this->db->prepare('
                SELECT p.*, pl.id AS plan_id, pl.message_limit, pl.duration_days
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

            // Ekstrak bulan dari provider field
            $parts  = explode(':', $payment['provider'] ?? '');
            $months = isset($parts[1]) ? (int)$parts[1] : 1;
            $scaledLimit = (int) $payment['message_limit'] * $months;

            // Nonaktifkan subscription lama
            $this->db->prepare('
                UPDATE subscriptions SET status = "cancelled"
                WHERE user_id = :uid AND status = "active"
            ')->execute([':uid' => $payment['user_id']]);

            // Buat subscription baru
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

            // Inisialisasi kuota
            $this->db->prepare('
                INSERT INTO subscription_usage (subscription_id, messages_used, messages_limit)
                VALUES (:sub_id, 0, :limit)
            ')->execute([':sub_id' => $subId, ':limit' => $scaledLimit]);

            // Update status payment
            $this->db->prepare('
                UPDATE payments
                SET status = "paid", paid_at = NOW(), subscription_id = :sub_id
                WHERE id = :id
            ')->execute([':sub_id' => $subId, ':id' => $paymentId]);

            $this->db->commit();
            $_SESSION['flash_admin_success'] = "✅ Pembayaran #{$payment['external_id']} berhasil disetujui. Paket {$payment['plan_name']} aktif untuk {$months} bulan.";
            Response::redirect('/admin');
        } catch (\Exception $e) {
            $this->db->rollBack();
            $_SESSION['flash_admin_error'] = 'Gagal approve pembayaran: ' . $e->getMessage();
            Response::redirect('/admin');
        }
    }

    /**
     * POST /admin/payment/reject
     * Admin rejects a pending payment.
     */
    public function rejectPayment(): void
    {
        $user = AdminMiddleware::handle();

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

        $_SESSION['flash_admin_success'] = "❌ Pembayaran #{$paymentId} telah ditolak.";
        Response::redirect('/admin');
    }

    /**
     * POST /admin/plan/update
     * Admin manually overrides a user's active plan.
     */
    public function updatePlan(): void
    {
        $user = AdminMiddleware::handle();

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
            $_SESSION['flash_admin_success'] = "Paket pengguna #{$targetUserId} diperbarui menjadi {$plan['name']}.";
            Response::redirect('/admin');
        } catch (\Exception $e) {
            $this->db->rollBack();
            $_SESSION['flash_admin_error'] = 'Gagal: ' . $e->getMessage();
            Response::redirect('/admin');
        }
    }
}
