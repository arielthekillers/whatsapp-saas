<?php
declare(strict_types=1);

namespace App\Support;

use App\Config\Env;
use App\Helpers\ApiResponse;
use App\Middleware\ApiKeyMiddleware;
use App\Repositories\ApiKeyRepository;
use App\Repositories\SubscriptionRepository;
use App\Services\RateLimiterService;

/**
 * Dipanggil di awal setiap method controller Api\*. Menggabungkan
 * autentikasi API key dan rate limiting per plan supaya tidak
 * duplikat di setiap controller.
 *
 * @return array{user_id:int, api_key_id:int, subscription: array|null}
 */
class ApiAuth
{
    public static function resolve(): array
    {
        $record = ApiKeyMiddleware::authenticate(new ApiKeyRepository());

        $subscription = (new SubscriptionRepository())->findActiveForUser($record['user_id']);
        $limitPerMinute = $subscription['rate_limit_per_minute'] ?? (int) Env::get('DEFAULT_RATE_LIMIT_PER_MINUTE', 30);

        $limiter = new RateLimiterService();
        if (!$limiter->allow('user:' . $record['user_id'], (int) $limitPerMinute)) {
            ApiResponse::error('RATE_LIMITED', 'Terlalu banyak request. Coba lagi dalam beberapa saat.', 429);
        }

        return [
            'user_id'      => $record['user_id'],
            'api_key_id'   => $record['api_key_id'],
            'subscription' => $subscription,
        ];
    }
}
