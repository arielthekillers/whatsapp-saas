<?php
declare(strict_types=1);

namespace App\Controllers\Dashboard;

use App\Config\Database;
use App\Config\Env;
use App\Helpers\Csrf;
use App\Helpers\Response;
use App\Middleware\AuthMiddleware;
use App\Repositories\PlanRepository;
use App\Repositories\SubscriptionRepository;
use PDO;

class BillingController
{
    private PDO $db;
    private PlanRepository $plans;
    private SubscriptionRepository $subscriptions;

    public function __construct()
    {
        $this->db = Database::connection();
        $this->plans = new PlanRepository();
        $this->subscriptions = new SubscriptionRepository();
    }

    public function index(): void
    {
        $user = AuthMiddleware::handle();

        $activeSub = $this->subscriptions->findActiveForUser($user['id']);
        $allPlans  = $this->plans->findAllActive();

        $stmt = $this->db->prepare('
            SELECT p.*, pl.name AS plan_name
            FROM payments p
            LEFT JOIN plans pl ON pl.id = p.plan_id
            WHERE p.user_id = :user_id
            ORDER BY p.id DESC
        ');
        $stmt->execute([':user_id' => $user['id']]);
        $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $success = $_SESSION['flash_billing_success'] ?? null;
        unset($_SESSION['flash_billing_success']);
        $error = $_SESSION['flash_billing_error'] ?? null;
        unset($_SESSION['flash_billing_error']);

        require __DIR__ . '/../../../views/billing/index.php';
    }

    /**
     * POST /billing/checkout
     * Creates a pending payment record and redirects to transfer instructions page.
     */
    public function checkout(): void
    {
        $user = AuthMiddleware::handle();

        if (!Csrf::verify($_POST['_csrf'] ?? null)) {
            Response::redirect('/billing');
        }

        $planId = (int) ($_POST['plan_id'] ?? 0);
        $months = (int) ($_POST['duration_months'] ?? 1);

        if (!in_array($months, [1, 3, 6, 12], true)) {
            $months = 1;
        }

        $plan = $this->plans->findById($planId);
        if (!$plan) {
            $_SESSION['flash_billing_error'] = 'Paket langganan tidak ditemukan.';
            Response::redirect('/billing');
        }

        // Hitung diskon berdasarkan durasi
        $discount = match($months) {
            3  => 0.05,
            6  => 0.10,
            12 => 0.20,
            default => 0.0,
        };

        $basePrice   = (float) $plan['price'];
        $totalAmount = ($basePrice * $months) * (1.0 - $discount);

        $externalId  = 'INV-' . strtoupper(bin2hex(random_bytes(6)));
        $providerVal = 'bank_transfer:' . $months;

        $stmt = $this->db->prepare('
            INSERT INTO payments (user_id, plan_id, provider, external_id, amount, status)
            VALUES (:user_id, :plan_id, :provider, :external_id, :amount, "pending")
        ');
        $stmt->execute([
            ':user_id'     => $user['id'],
            ':plan_id'     => $planId,
            ':provider'    => $providerVal,
            ':external_id' => $externalId,
            ':amount'      => $totalAmount,
        ]);

        Response::redirect('/billing/pay/' . $externalId);
    }

    /**
     * GET /billing/pay/{externalId}
     * Shows bank transfer instructions and confirmation form.
     */
    public function pay(string $externalId): void
    {
        $user = AuthMiddleware::handle();

        $stmt = $this->db->prepare('
            SELECT p.*, pl.id AS plan_id, pl.name AS plan_name, pl.description AS plan_description
            FROM payments p
            JOIN plans pl ON pl.id = p.plan_id
            WHERE p.external_id = :external_id AND p.user_id = :user_id AND p.status = "pending"
            LIMIT 1
        ');
        $stmt->execute([
            ':external_id' => $externalId,
            ':user_id'     => $user['id'],
        ]);
        $payment = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$payment) {
            $_SESSION['flash_billing_error'] = 'Invoice tidak ditemukan atau sudah diproses.';
            Response::redirect('/billing');
        }

        // Bank info dari .env
        $bankName          = Env::get('BANK_NAME', 'BCA');
        $bankAccount       = Env::get('BANK_ACCOUNT_NUMBER', '-');
        $bankHolder        = Env::get('BANK_ACCOUNT_HOLDER', 'Sintesa Corporation');
        $bankWhatsapp      = Env::get('BANK_WHATSAPP', '');

        require __DIR__ . '/../../../views/billing/pay.php';
    }

    /**
     * POST /billing/pay/{externalId}/confirm
     * User submits proof of payment — marks payment as "verifying".
     */
    public function confirmTransfer(string $externalId): void
    {
        $user = AuthMiddleware::handle();

        if (!Csrf::verify($_POST['_csrf'] ?? null)) {
            Response::redirect('/billing/pay/' . $externalId);
        }

        $senderName    = trim($_POST['sender_name'] ?? '');
        $transferDate  = trim($_POST['transfer_date'] ?? '');
        $transferNote  = trim($_POST['transfer_note'] ?? '');

        if (empty($senderName) || empty($transferDate)) {
            $_SESSION['flash_billing_error'] = 'Nama pengirim dan tanggal transfer wajib diisi.';
            Response::redirect('/billing/pay/' . $externalId);
        }

        // Simpan bukti/catatan konfirmasi ke metadata payment
        $stmt = $this->db->prepare('
            UPDATE payments
            SET status = "verifying",
                transfer_note = :note
            WHERE external_id = :external_id AND user_id = :user_id AND status = "pending"
        ');
        $stmt->execute([
            ':note'        => "Pengirim: {$senderName} | Tanggal: {$transferDate}" . ($transferNote ? " | Catatan: {$transferNote}" : ''),
            ':external_id' => $externalId,
            ':user_id'     => $user['id'],
        ]);

        if ($stmt->rowCount() === 0) {
            $_SESSION['flash_billing_error'] = 'Invoice tidak ditemukan atau sudah diproses sebelumnya.';
            Response::redirect('/billing');
        }

        $_SESSION['flash_billing_success'] = 'Konfirmasi transfer berhasil dikirim! Tim kami akan memverifikasi pembayaran Anda dalam 1×24 jam kerja.';
        Response::redirect('/billing');
    }

    /**
     * POST /billing/pay/{externalId}/cancel
     * User cancels a pending invoice.
     */
    public function cancelPayment(string $externalId): void
    {
        $user = AuthMiddleware::handle();

        $stmt = $this->db->prepare('
            UPDATE payments
            SET status = "expired"
            WHERE external_id = :external_id AND user_id = :user_id AND status IN ("pending","verifying")
        ');
        $stmt->execute([
            ':external_id' => $externalId,
            ':user_id'     => $user['id'],
        ]);

        $_SESSION['flash_billing_error'] = 'Invoice dibatalkan.';
        Response::redirect('/billing');
    }
}
