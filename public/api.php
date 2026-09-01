<?php
declare(strict_types=1);

require __DIR__ . '/../autoload.php';

use App\Config\Env;
use App\Support\Router;

Env::load(__DIR__ . '/../.env');

error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('log_errors', '1');
ini_set('error_log', __DIR__ . '/../storage/logs/error.log');

$router = new Router();
require __DIR__ . '/../routes/api.php';

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
