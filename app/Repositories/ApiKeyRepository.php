<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Config\Database;
use PDO;

/**
 * API key BUKAN password -- sudah berupa random string berentropi tinggi,
 * jadi cukup di-hash dengan SHA-256 (cepat, indexable) alih-alih bcrypt.
 * Plaintext hanya pernah ada di memori sesaat setelah generate, tidak
 * pernah disimpan.
 */
class ApiKeyRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    /** @return array{id:int, raw_key:string, prefix:string} */
    public function create(int $userId, string $name): array
    {
        $raw    = 'wsk_' . bin2hex(random_bytes(24));
        $hash   = hash('sha256', $raw);
        $prefix = substr($raw, 0, 12);

        $stmt = $this->db->prepare(
            'INSERT INTO api_keys (user_id, name, api_key_prefix, api_key_hash) VALUES (?, ?, ?, ?)'
        );
        $stmt->execute([$userId, $name, $prefix, $hash]);

        return ['id' => (int) $this->db->lastInsertId(), 'raw_key' => $raw, 'prefix' => $prefix];
    }

    public function findActiveByHash(string $hash): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT ak.id AS api_key_id, ak.user_id, u.status AS user_status
             FROM api_keys ak
             JOIN users u ON u.id = ak.user_id
             WHERE ak.api_key_hash = ? AND ak.status = "active"
             LIMIT 1'
        );
        $stmt->execute([$hash]);
        $row = $stmt->fetch();

        if (!$row || $row['user_status'] !== 'active') {
            return null;
        }
        return $row;
    }

    public function touchLastUsed(int $apiKeyId): void
    {
        $stmt = $this->db->prepare('UPDATE api_keys SET last_used_at = NOW() WHERE id = ?');
        $stmt->execute([$apiKeyId]);
    }

    public function findAllForUser(int $userId): array
    {
        $stmt = $this->db->prepare(
            'SELECT id, name, api_key_prefix, last_used_at, status, created_at
             FROM api_keys WHERE user_id = ? ORDER BY created_at DESC'
        );
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function revoke(int $userId, int $apiKeyId): void
    {
        $stmt = $this->db->prepare('UPDATE api_keys SET status = "revoked" WHERE id = ? AND user_id = ?');
        $stmt->execute([$apiKeyId, $userId]);
    }

    public function delete(int $userId, int $apiKeyId): void
    {
        $stmt = $this->db->prepare('DELETE FROM api_keys WHERE id = ? AND user_id = ?');
        $stmt->execute([$apiKeyId, $userId]);
    }
}
