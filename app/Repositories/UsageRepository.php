<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Config\Database;
use PDO;

class UsageRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function log(int $userId, ?int $sessionId, string $type, int $amount = 1, array $metadata = []): void
    {
        $stmt = $this->db->prepare(
            'INSERT INTO usage_logs (user_id, session_id, type, amount, metadata) VALUES (?, ?, ?, ?, ?)'
        );
        $stmt->execute([$userId, $sessionId, $type, $amount, json_encode($metadata, JSON_UNESCAPED_UNICODE)]);
    }
}
