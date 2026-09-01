<?php
declare(strict_types=1);

namespace App\Controllers\Dashboard;

use App\Middleware\AuthMiddleware;
use App\Repositories\SessionRepository;

class DashboardController
{
    public function index(): void
    {
        $user     = AuthMiddleware::handle();
        $sessions = (new SessionRepository())->findAllForUser($user['id']);
        require __DIR__ . '/../../../views/dashboard/index.php';
    }
}
