<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Config\Database;
use PDO;
use RuntimeException;

class SessionRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function countActiveForUser(int $userId): int
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM whatsapp_sessions WHERE user_id = ? AND status != 'LOGGED_OUT'"
        );
        $stmt->execute([$userId]);
        return (int) $stmt->fetchColumn();
    }

    public function create(int $userId, int $wahaInstanceId, string $name, string $wahaSessionName): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO whatsapp_sessions (user_id, waha_instance_id, name, waha_session_name, status)
             VALUES (?, ?, ?, ?, "CREATED")'
        );
        $stmt->execute([$userId, $wahaInstanceId, $name, $wahaSessionName]);
        return (int) $this->db->lastInsertId();
    }

    public function findAllForUser(int $userId): array
    {
        $stmt = $this->db->prepare('SELECT * FROM whatsapp_sessions WHERE user_id = ? ORDER BY created_at DESC');
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function findForUser(int $userId, int $sessionId): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM whatsapp_sessions WHERE user_id = ? AND id = ? LIMIT 1');
        $stmt->execute([$userId, $sessionId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    /** Customer API mengacu ke session dengan nama tampilan (mis. "marketing"), bukan ID internal WAHA. */
    public function findByNameForUser(int $userId, string $name): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM whatsapp_sessions WHERE user_id = ? AND name = ? ORDER BY id DESC LIMIT 1'
        );
        $stmt->execute([$userId, $name]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function updateStatus(int $sessionId, string $status, ?string $qrCode = null): void
    {
        if (func_num_args() >= 3) {
            $stmt = $this->db->prepare('UPDATE whatsapp_sessions SET status = ?, qr_code = ? WHERE id = ?');
            $stmt->execute([$status, $qrCode, $sessionId]);
        } else {
            $stmt = $this->db->prepare('UPDATE whatsapp_sessions SET status = ? WHERE id = ?');
            $stmt->execute([$status, $sessionId]);
        }
    }

    public function clearQr(int $sessionId): void
    {
        $stmt = $this->db->prepare('UPDATE whatsapp_sessions SET qr_code = NULL WHERE id = ?');
        $stmt->execute([$sessionId]);
    }

    public function getDefaultWahaInstanceId(): int
    {
        $stmt = $this->db->query("SELECT id FROM waha_instances WHERE status = 'active' ORDER BY id ASC LIMIT 1");
        $id = $stmt->fetchColumn();
        if (!$id) {
            throw new RuntimeException(
                'Tidak ada WAHA instance aktif. Tambahkan baris di tabel waha_instances (lihat README.md).'
            );
        }
        return (int) $id;
    }

    public function findByWahaSessionName(string $name): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM whatsapp_sessions WHERE waha_session_name = ? LIMIT 1');
        $stmt->execute([$name]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function updatePhoneNumber(int $sessionId, string $phoneNumber): void
    {
        $stmt = $this->db->prepare('UPDATE whatsapp_sessions SET phone_number = ? WHERE id = ?');
        $stmt->execute([$phoneNumber, $sessionId]);
    }
}
