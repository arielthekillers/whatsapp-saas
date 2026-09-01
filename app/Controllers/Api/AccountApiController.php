<?php
declare(strict_types=1);

namespace App\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Repositories\UserRepository;
use App\Support\ApiAuth;

class AccountApiController
{
    public function show(): void
    {
        $ctx  = ApiAuth::resolve();
        $user = (new UserRepository())->findById($ctx['user_id']);
        $sub  = $ctx['subscription'];

        ApiResponse::success([
            'name'                  => $user['name'],
            'email'                 => $user['email'],
            'plan'                  => $sub['plan_name'] ?? null,
            'subscription_ends_at'  => $sub['end_at'] ?? null,
        ]);
    }
}
