<?php
declare(strict_types=1);

namespace App\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Support\ApiAuth;

class UsageApiController
{
    public function show(): void
    {
        $ctx = ApiAuth::resolve();
        $sub = $ctx['subscription'];

        if ($sub === null) {
            ApiResponse::error('NO_ACTIVE_SUBSCRIPTION', 'Tidak ada subscription aktif untuk akun ini.', 402);
        }

        ApiResponse::success([
            'plan'                 => $sub['plan_name'],
            'messages_used'        => (int) $sub['messages_used'],
            'messages_limit'       => (int) $sub['messages_limit'],
            'session_limit'        => (int) $sub['session_limit'],
            'subscription_ends_at' => $sub['end_at'],
        ]);
    }
}
