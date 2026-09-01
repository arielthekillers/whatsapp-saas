<?php
declare(strict_types=1);

/**
 * Router untuk `php -S` (built-in dev server) saja.
 * Di production, gunakan Apache/Nginx rewrite:
 *   /v1/*      -> public/api.php
 *   /webhook/* -> public/webhook.php
 *   selainnya  -> public/index.php
 * Jalankan dev server dengan:
 *   php -S 0.0.0.0:8000 -t public public/router.php
 */

$uri = urldecode((string) parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

if (str_starts_with($uri, '/v1/')) {
    require __DIR__ . '/api.php';
    return true;
}

if ($uri === '/webhook/waha') {
    require __DIR__ . '/webhook.php';
    return true;
}

// Sajikan file statis apa adanya (mis. /assets/...) kalau memang ada.
$staticFile = __DIR__ . $uri;
if ($uri !== '/' && is_file($staticFile)) {
    return false;
}

require __DIR__ . '/index.php';
