<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Config\Database;
use PDO;

class JobRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function push(string $type, array $payload, int $delaySeconds = 0): int
    {
        $stmt = $this->db->prepare('
            INSERT INTO jobs (type, payload, status, attempts, available_at)
            VALUES (:type, :payload, "pending", 0, DATE_ADD(NOW(), INTERVAL :delay SECOND))
        ');
        $stmt->execute([
            ':type' => $type,
            ':payload' => json_encode($payload),
            ':delay' => $delaySeconds
        ]);
        return (int) $this->db->lastInsertId();
    }

    /**
     * Mengambil job pending dengan SELECT ... FOR UPDATE SKIP LOCKED
     * agar terhindar dari race condition antar worker.
     */
    public function fetchAndLock(int $limit = 5): array
    {
        try {
            $this->db->beginTransaction();

            // 1. Dapatkan ID job yang tersedia dan kunci barisnya
            $stmt = $this->db->prepare('
                SELECT id FROM jobs 
                WHERE status = "pending" AND available_at <= NOW()
                ORDER BY id ASC 
                LIMIT :limit 
                FOR UPDATE SKIP LOCKED
            ');
            // Bind value secara eksplisit karena PDO membutuhkan integer
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            $ids = $stmt->fetchAll(PDO::FETCH_COLUMN);

            if (empty($ids)) {
                $this->db->commit();
                return [];
            }

            // 2. Tandai job tersebut sebagai "processing" dan simpan waktu kuncinya
            $idList = implode(',', array_map('intval', $ids));
            $this->db->exec("
                UPDATE jobs 
                SET status = 'processing', locked_at = NOW(), attempts = attempts + 1 
                WHERE id IN ($idList)
            ");

            // 3. Ambil data lengkap job tersebut
            $stmtData = $this->db->prepare("
                SELECT * FROM jobs WHERE id IN ($idList)
            ");
            $stmtData->execute();
            $jobs = $stmtData->fetchAll(PDO::FETCH_ASSOC);

            $this->db->commit();
            return $jobs;
        } catch (\Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw $e;
        }
    }

    public function markCompleted(int $id): bool
    {
        $stmt = $this->db->prepare('
            UPDATE jobs 
            SET status = "completed", locked_at = NULL 
            WHERE id = :id
        ');
        return $stmt->execute([':id' => $id]);
    }

    public function releaseOrFail(int $id, int $delaySeconds = 30, int $maxAttempts = 5): bool
    {
        // Get the current job attempts
        $stmtCheck = $this->db->prepare('SELECT attempts FROM jobs WHERE id = :id');
        $stmtCheck->execute([':id' => $id]);
        $job = $stmtCheck->fetch(PDO::FETCH_ASSOC);
        
        if ($job && (int) $job['attempts'] >= $maxAttempts) {
            $stmt = $this->db->prepare('
                UPDATE jobs 
                SET status = "failed", locked_at = NULL 
                WHERE id = :id
            ');
            return $stmt->execute([':id' => $id]);
        } else {
            $stmt = $this->db->prepare('
                UPDATE jobs 
                SET status = "pending", locked_at = NULL, available_at = DATE_ADD(NOW(), INTERVAL :delay SECOND) 
                WHERE id = :id
            ');
            return $stmt->execute([
                ':id' => $id,
                ':delay' => $delaySeconds
            ]);
        }
    }
}
