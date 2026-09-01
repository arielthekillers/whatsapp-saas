<?php

declare(strict_types=1);

use App\Controllers\Api\AccountApiController;
use App\Controllers\Api\MessageApiController;
use App\Controllers\Api\SessionApiController;
use App\Controllers\Api\UsageApiController;

/** @var \App\Support\Router $router */

$router->post('/v1/messages/send', [MessageApiController::class, 'send']);

$router->get('/v1/sessions', [SessionApiController::class, 'index']);
$router->post('/v1/sessions', [SessionApiController::class, 'store']);
$router->get('/v1/sessions/{id}', [SessionApiController::class, 'show']);
$router->post('/v1/sessions/{id}/start', [SessionApiController::class, 'start']);
$router->post('/v1/sessions/{id}/stop', [SessionApiController::class, 'stop']);

$router->get('/v1/account', [AccountApiController::class, 'show']);
$router->get('/v1/usage', [UsageApiController::class, 'show']);
