<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Config\Database;
use PDO;
use Throwable;

class SubscriptionRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    /**
     * Dipanggil sekali saat registrasi. Kalau plan FREE belum ada di
     * tabel plans (belum di-seed), fungsi ini lewat begitu saja --
     * user tetap bisa pakai dashboard, tapi endpoint API akan menolak
     * dengan NO_ACTIVE_SUBSCRIPTION sampai admin membuat plan.
     */
    public function ensureFreeSubscription(int $userId): void
    {
        if ($this->findActiveForUser($userId) !== null) {
            return;
        }

        $stmt = $this->db->prepare(
            'SELECT id, duration_days, message_limit FROM plans WHERE name = "FREE" AND status = "active" LIMIT 1'
        );
        $stmt->execute();
        $plan = $stmt->fetch();
        if (!$plan) {
            return;
        }

        $this->db->beginTransaction();
        try {
            $stmt = $this->db->prepare(
                'INSERT INTO subscriptions (user_id, plan_id, start_at, end_at, status)
                 VALUES (?, ?, NOW(), DATE_ADD(NOW(), INTERVAL ? DAY), "active")'
            );
            $stmt->execute([$userId, $plan['id'], $plan['duration_days']]);
            $subscriptionId = (int) $this->db->lastInsertId();

            $stmt = $this->db->prepare(
                'INSERT INTO subscription_usage (subscription_id, messages_used, messages_limit)
                 VALUES (?, 0, ?)'
            );
            $stmt->execute([$subscriptionId, $plan['message_limit']]);

            $this->db->commit();
        } catch (Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function findActiveForUser(int $userId): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT s.id AS subscription_id, s.end_at, s.status,
                    p.id AS plan_id, p.name AS plan_name, p.session_limit, p.rate_limit_per_minute,
                    u.messages_used, u.messages_limit
             FROM subscriptions s
             JOIN plans p ON p.id = s.plan_id
             JOIN subscription_usage u ON u.subscription_id = s.id
             WHERE s.user_id = ? AND s.status = "active"
             ORDER BY s.id DESC LIMIT 1'
        );
        $stmt->execute([$userId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    /**
     * Reservasi kuota atomik: hanya berhasil (rowCount 1) kalau
     * messages_used masih di bawah messages_limit saat UPDATE
     * dieksekusi -- aman terhadap request paralel karena satu
     * statement UPDATE dikunci per-row oleh InnoDB.
     */
    public function incrementUsage(int $subscriptionId): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE subscription_usage
             SET messages_used = messages_used + 1
             WHERE subscription_id = ? AND messages_used < messages_limit'
        );
        $stmt->execute([$subscriptionId]);
        return $stmt->rowCount() === 1;
    }
}
