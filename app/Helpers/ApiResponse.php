<?php
declare(strict_types=1);

namespace App\Helpers;

/**
 * Response helper khusus untuk customer-facing public API (/v1/...).
 * Format konsisten sesuai spesifikasi:
 *   sukses: {"success": true, "data": {...}}
 *   gagal : {"success": false, "error": {"code": "...", "message": "..."}}
 */
class ApiResponse
{
    public static function success(array $data = [], int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        $out = ['success' => true];
        if (!empty($data)) {
            $out['data'] = $data;
        }
        echo json_encode($out, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        exit;
    }

    /** Selalu exit -- dipanggil di titik kegagalan mana pun dalam controller. */
    public static function error(string $code, string $message, int $status): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'error'   => ['code' => $code, 'message' => $message],
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        exit;
    }
}
