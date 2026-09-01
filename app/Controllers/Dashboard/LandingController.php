<?php
declare(strict_types=1);

namespace App\Controllers\Dashboard;

use App\Config\Database;

class LandingController
{
    public function index(): void
    {
        $db = Database::connection();
        $stmt = $db->prepare('SELECT * FROM plans WHERE status = "active" ORDER BY price ASC');
        $stmt->execute();
        $plans = $stmt->fetchAll();

        require __DIR__ . '/../../../views/landing/index.php';
    }
}
