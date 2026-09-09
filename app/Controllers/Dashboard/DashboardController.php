<?php
declare(strict_types=1);

namespace App\Controllers\Dashboard;

use App\Helpers\Response;
use App\Middleware\AuthMiddleware;
use App\Repositories\SessionRepository;
use App\Repositories\SubscriptionRepository;

class DashboardController
{
    public function index(): void
    {
        $user = AuthMiddleware::handle();

        if (($_SESSION['user_role'] ?? 'customer') === 'admin') {
            Response::redirect('/admin');
        }

        $sessions = (new SessionRepository())->findAllForUser($user['id']);
        $subscriptionRepo = new SubscriptionRepository();
        $activeSub = $subscriptionRepo->findActiveForUser($user['id']);

        require __DIR__ . '/../../../views/dashboard/index.php';
    }
}
