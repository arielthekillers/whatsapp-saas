<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\SubscriptionRepository;

/**
 * Kuota direservasi SEBELUM memanggil WAHA (atomic UPDATE di
 * SubscriptionRepository::incrementUsage), bukan dicatat setelah fakta --
 * ini mencegah customer melebihi kuota saat banyak request paralel.
 */
class QuotaService
{
    public function __construct(private SubscriptionRepository $subscriptions)
    {
    }

    /** @return array{ok:bool, code?:string, message?:string, subscription?:array} */
    public function reserveMessage(int $userId): array
    {
        $sub = $this->subscriptions->findActiveForUser($userId);

        if ($sub === null) {
            return ['ok' => false, 'code' => 'NO_ACTIVE_SUBSCRIPTION', 'message' => 'Tidak ada subscription aktif untuk akun ini.'];
        }

        if (strtotime($sub['end_at']) < time()) {
            return ['ok' => false, 'code' => 'SUBSCRIPTION_EXPIRED', 'message' => 'Subscription Anda sudah kedaluwarsa.'];
        }

        $reserved = $this->subscriptions->incrementUsage($sub['subscription_id']);
        if (!$reserved) {
            return ['ok' => false, 'code' => 'QUOTA_EXCEEDED', 'message' => 'Kuota pesan pada periode ini sudah habis.'];
        }

        return ['ok' => true, 'subscription' => $sub];
    }
}
