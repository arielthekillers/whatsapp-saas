<?php
declare(strict_types=1);

/**
 * Autoloader sederhana untuk namespace App\ -> /app/*.
 * Tidak pakai Composer karena project ini sengaja dibuat PHP native.
 */
spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';
    if (!str_starts_with($class, $prefix)) {
        return;
    }
    $relative = substr($class, strlen($prefix));
    $path = __DIR__ . '/app/' . str_replace('\\', '/', $relative) . '.php';
    if (is_file($path)) {
        require $path;
    }
});

if (!function_exists('url')) {
    function url(string $path = ''): string {
        static $basePath = null;
        if ($basePath === null) {
            $projectRoot = str_replace('\\', '/', __DIR__);
            $docRoot = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT'] ?? '');
            $basePath = '';
            if ($docRoot !== '' && str_starts_with($projectRoot, $docRoot)) {
                $basePath = substr($projectRoot, strlen($docRoot));
            }
            $basePath = rtrim($basePath, '/');
        }
        return $basePath . '/' . ltrim($path, '/');
    }
}

