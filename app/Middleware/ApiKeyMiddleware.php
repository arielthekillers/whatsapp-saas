<?php
declare(strict_types=1);

namespace App\Middleware;

use App\Helpers\ApiResponse;
use App\Repositories\ApiKeyRepository;

class ApiKeyMiddleware
{
    /** Exit dengan 401 kalau gagal. @return array{api_key_id:int, user_id:int} */
    public static function authenticate(ApiKeyRepository $repo): array
    {
        $header = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        if (!preg_match('/^Bearer\s+(.+)$/i', $header, $m)) {
            ApiResponse::error('UNAUTHORIZED', 'Header Authorization: Bearer <API_KEY> wajib diisi.', 401);
        }

        $hash   = hash('sha256', trim($m[1]));
        $record = $repo->findActiveByHash($hash);

        if ($record === null) {
            ApiResponse::error('UNAUTHORIZED', 'API key tidak valid atau sudah dicabut.', 401);
        }

        $repo->touchLastUsed($record['api_key_id']);

        return $record;
    }
}
