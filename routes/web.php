<?php

declare(strict_types=1);

use App\Controllers\Dashboard\ApiKeyController;
use App\Controllers\Dashboard\AuthController;
use App\Controllers\Dashboard\DashboardController;
use App\Controllers\Dashboard\SessionController;
use App\Controllers\Dashboard\UsageController;
use App\Controllers\Dashboard\LandingController;
use App\Controllers\Dashboard\WebhookController;
use App\Controllers\Dashboard\BillingController;
use App\Controllers\Dashboard\ProfileController;
use App\Controllers\Admin\AdminController;

/** @var \App\Support\Router $router */

$router->get('/', [LandingController::class, 'index']);

$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/register', [AuthController::class, 'showRegister']);
$router->post('/register', [AuthController::class, 'register']);
$router->post('/logout', [AuthController::class, 'logout']);

$router->get('/dashboard', [DashboardController::class, 'index']);

$router->get('/sessions', [SessionController::class, 'index']);
$router->get('/sessions/create', [SessionController::class, 'showCreate']);
$router->post('/sessions', [SessionController::class, 'store']);
$router->get('/sessions/{id}', [SessionController::class, 'show']);
$router->get('/sessions/{id}/status', [SessionController::class, 'refreshStatus']);
$router->post('/sessions/{id}/stop', [SessionController::class, 'stop']);
$router->post('/sessions/{id}/logout', [SessionController::class, 'logoutSession']);

$router->get('/api-keys', [ApiKeyController::class, 'index']);
$router->post('/api-keys', [ApiKeyController::class, 'store']);
$router->post('/api-keys/{id}/revoke', [ApiKeyController::class, 'revoke']);
$router->post('/api-keys/{id}/delete', [ApiKeyController::class, 'delete']);

$router->get('/usage', [UsageController::class, 'index']);

$router->get('/webhooks', [WebhookController::class, 'index']);
$router->post('/webhooks', [WebhookController::class, 'store']);
$router->post('/webhooks/{id}/delete', [WebhookController::class, 'delete']);

$router->get('/billing', [BillingController::class, 'index']);
$router->post('/billing/checkout', [BillingController::class, 'checkout']);
$router->get('/billing/pay/{externalId}', [BillingController::class, 'pay']);
$router->post('/billing/pay/{externalId}/confirm', [BillingController::class, 'confirmTransfer']);
$router->post('/billing/pay/{externalId}/cancel', [BillingController::class, 'cancelPayment']);

$router->get('/profile', [ProfileController::class, 'index']);
$router->post('/profile/password', [ProfileController::class, 'updatePassword']);

$router->get('/docs', function() {
    $user = \App\Middleware\AuthMiddleware::handle();
    require __DIR__ . '/../views/docs/index.php';
});

$router->get('/admin', [AdminController::class, 'index']);
$router->post('/admin/plan/update', [AdminController::class, 'updatePlan']);
$router->post('/admin/payment/approve', [AdminController::class, 'approvePayment']);
$router->post('/admin/payment/reject', [AdminController::class, 'rejectPayment']);
$router->post('/admin/user/status', [AdminController::class, 'updateUserStatus']);
$router->post('/admin/jobs/retry-failed', [AdminController::class, 'retryFailedJobs']);
$router->post('/admin/announcement', [AdminController::class, 'saveAnnouncement']);
$router->post('/admin/announcement/delete', [AdminController::class, 'deleteAnnouncement']);
$router->get('/admin/export-payments', [AdminController::class, 'exportPaymentsCsv']);



