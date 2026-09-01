<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Config\Database;
use PDO;

class MessageRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function findByIdempotency(int $userId, string $idempotencyKey): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM messages WHERE user_id = ? AND idempotency_key = ? LIMIT 1'
        );
        $stmt->execute([$userId, $idempotencyKey]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function create(
        int $userId,
        int $sessionId,
        string $direction,
        string $messageType,
        ?string $recipient,
        ?string $idempotencyKey,
        array $payload,
        string $status,
        ?string $wahaMessageId = null
    ): int {
        $stmt = $this->db->prepare(
            'INSERT INTO messages (user_id, session_id, direction, message_type, recipient, waha_message_id, idempotency_key, payload, status)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $userId,
            $sessionId,
            $direction,
            $messageType,
            $recipient,
            $wahaMessageId,
            $idempotencyKey,
            json_encode($payload, JSON_UNESCAPED_UNICODE),
            $status,
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function findByWahaMessageId(string $wahaMessageId): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM messages WHERE waha_message_id = ? LIMIT 1');
        $stmt->execute([$wahaMessageId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function updateStatus(int $id, string $status): bool
    {
        $stmt = $this->db->prepare('UPDATE messages SET status = ? WHERE id = ?');
        return $stmt->execute([$status, $id]);
    }

    public function logEvent(int $messageId, string $eventType, array $rawPayload): bool
    {
        $stmt = $this->db->prepare('
            INSERT INTO message_events (message_id, event_type, raw_payload)
            VALUES (?, ?, ?)
        ');
        return $stmt->execute([
            $messageId,
            $eventType,
            json_encode($rawPayload)
        ]);
    }
}
