<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Config\Database;
use PDO;

class WebhookRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function create(int $userId, ?int $sessionId, string $url, string $secretEnc): int
    {
        $stmt = $this->db->prepare('
            INSERT INTO webhooks (user_id, session_id, url, secret_enc, status)
            VALUES (:user_id, :session_id, :url, :secret_enc, "active")
        ');
        $stmt->execute([
            ':user_id' => $userId,
            ':session_id' => $sessionId,
            ':url' => $url,
            ':secret_enc' => $secretEnc
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function listByUser(int $userId): array
    {
        $stmt = $this->db->prepare('
            SELECT w.*, ws.name AS session_name 
            FROM webhooks w
            LEFT JOIN whatsapp_sessions ws ON w.session_id = ws.id
            WHERE w.user_id = :user_id
            ORDER BY w.created_at DESC
        ');
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByIdAndUser(int $id, int $userId): ?array
    {
        $stmt = $this->db->prepare('
            SELECT * FROM webhooks WHERE id = :id AND user_id = :user_id
        ');
        $stmt->execute([
            ':id' => $id,
            ':user_id' => $userId
        ]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res ?: null;
    }

    public function delete(int $id, int $userId): bool
    {
        $stmt = $this->db->prepare('
            DELETE FROM webhooks WHERE id = :id AND user_id = :user_id
        ');
        return $stmt->execute([
            ':id' => $id,
            ':user_id' => $userId
        ]);
    }

    public function getActiveWebhooksForSession(int $userId, ?int $sessionId): array
    {
        // Get webhooks that are active and either match the session ID or are general webhooks (session_id IS NULL)
        if ($sessionId === null) {
            $stmt = $this->db->prepare('
                SELECT * FROM webhooks 
                WHERE user_id = :user_id AND status = "active" AND session_id IS NULL
            ');
            $stmt->execute([':user_id' => $userId]);
        } else {
            $stmt = $this->db->prepare('
                SELECT * FROM webhooks 
                WHERE user_id = :user_id AND status = "active" AND (session_id = :session_id OR session_id IS NULL)
            ');
            $stmt->execute([
                ':user_id' => $userId,
                ':session_id' => $sessionId
            ]);
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function saveInboundEvent(int $instanceId, ?string $eventId, string $eventType, string $sessionName, array $rawPayload): bool
    {
        try {
            $stmt = $this->db->prepare('
                INSERT INTO webhook_inbound_events (waha_instance_id, waha_event_id, event_type, session_name, raw_payload)
                VALUES (:instance_id, :event_id, :event_type, :session_name, :raw_payload)
            ');
            return $stmt->execute([
                ':instance_id' => $instanceId,
                ':event_id' => $eventId,
                ':event_type' => $eventType,
                ':session_name' => $sessionName,
                ':raw_payload' => json_encode($rawPayload)
            ]);
        } catch (\PDOException $e) {
            // Duplicate key means it was already saved (idempotent), we return true to acknowledge
            if ($e->getCode() === '23000') {
                return true;
            }
            throw $e;
        }
    }

    public function createWebhookLog(int $webhookId, string $event, array $payload): int
    {
        $stmt = $this->db->prepare('
            INSERT INTO webhook_logs (webhook_id, event, payload, status, attempt)
            VALUES (:webhook_id, :event, :payload, "pending", 1)
        ');
        $stmt->execute([
            ':webhook_id' => $webhookId,
            ':event' => $event,
            ':payload' => json_encode($payload)
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function updateWebhookLogStatus(int $logId, int $responseCode, ?string $responseBody, string $status): bool
    {
        $stmt = $this->db->prepare('
            UPDATE webhook_logs 
            SET response_code = :response_code, response_body = :response_body, status = :status
            WHERE id = :id
        ');
        return $stmt->execute([
            ':id' => $logId,
            ':response_code' => $responseCode,
            ':response_body' => $responseBody,
            ':status' => $status
        ]);
    }
}
