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

header('X-Frame-Options: SAMEORIGIN');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('X-XSS-Protection: 1; mode=block');

$router = new Router();
require __DIR__ . '/../routes/web.php';

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
