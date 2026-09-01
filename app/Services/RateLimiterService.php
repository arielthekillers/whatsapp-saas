<?php
declare(strict_types=1);

namespace App\Services;

use App\Config\Database;
use PDO;

/**
 * Rate limiter fixed-window per menit, disimpan di tabel
 * rate_limit_counters. Cukup murah untuk volume sedang tanpa Redis;
 * kalau volume tumbuh besar, ganti implementasi ini dengan Redis
 * INCR + EXPIRE tanpa mengubah pemanggilnya (ApiAuth).
 */
class RateLimiterService
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function allow(string $scopeKey, int $limitPerMinute): bool
    {
        $windowStart = date('Y-m-d H:i:00');

        $stmt = $this->db->prepare(
            'INSERT INTO rate_limit_counters (scope_key, window_start, request_count)
             VALUES (?, ?, 1)
             ON DUPLICATE KEY UPDATE request_count = request_count + 1'
        );
        $stmt->execute([$scopeKey, $windowStart]);

        $check = $this->db->prepare(
            'SELECT request_count FROM rate_limit_counters WHERE scope_key = ? AND window_start = ?'
        );
        $check->execute([$scopeKey, $windowStart]);

        return (int) $check->fetchColumn() <= $limitPerMinute;
    }
}
