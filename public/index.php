<?php
declare(strict_types=1);

require __DIR__ . '/../autoload.php';

use App\Config\Env;
use App\Support\Router;

Env::load(__DIR__ . '/../.env');

$isProd = Env::get('APP_ENV', 'production') === 'production';

ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_samesite', 'Lax');
if ($isProd) {
    ini_set('session.cookie_secure', '1');
}
session_start();

error_reporting(E_ALL);
ini_set('display_errors', $isProd ? '0' : '1');
ini_set('log_errors', '1');
ini_set('error_log', __DIR__ . '/../storage/logs/error.log');

$router = new Router();
require __DIR__ . '/../routes/web.php';

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
