<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Config\Database;
use PDO;

class PlanRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function findAllActive(): array
    {
        $stmt = $this->db->prepare('SELECT * FROM plans WHERE status = "active" ORDER BY price ASC');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM plans WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }
}
