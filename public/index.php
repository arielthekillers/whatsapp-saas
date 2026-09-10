<?php
declare(strict_types=1);

require __DIR__ . '/../autoload.php';

use App\Config\Env;
use App\Support\Router;

Env::load(__DIR__ . '/../.env');

$isProd = Env::get('APP_ENV', 'production') === 'production';

ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_samesite', 'Lax');
if ($isProd && isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    ini_set('session.cookie_secure', '1');
}
session_start();

header('X-Frame-Options: SAMEORIGIN');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('X-XSS-Protection: 1; mode=block');

try {
    $router = new Router();
    require __DIR__ . '/../routes/web.php';

    $router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
} catch (\Throwable $e) {
    $logDir = __DIR__ . '/../storage/logs';
    if (!is_dir($logDir)) {
        @mkdir($logDir, 0755, true);
    }
    $logMsg = sprintf("[%s] Error: %s in %s:%d\nStack trace:\n%s\n\n",
        date('Y-m-d H:i:s'),
        $e->getMessage(),
        $e->getFile(),
        $e->getLine(),
        $e->getTraceAsString()
    );
    @file_put_contents($logDir . '/error.log', $logMsg, FILE_APPEND);

    http_response_code(500);
    if (Env::get('APP_ENV') === 'development') {
        echo '<h1>500 Internal Server Error</h1>';
        echo '<p><strong>Message:</strong> ' . htmlspecialchars($e->getMessage()) . '</p>';
        echo '<p><strong>File:</strong> ' . htmlspecialchars($e->getFile()) . ':' . $e->getLine() . '</p>';
        echo '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
    } else {
        $dashUrl = url('/dashboard');
        echo '<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><title>500 - Server Error</title><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css"></head><body class="bg-gray-100 flex items-center justify-center min-h-screen p-4"><div class="max-w-md w-full bg-white rounded-2xl p-8 shadow-xl text-center"><div class="w-16 h-16 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-3xl mx-auto mb-4">⚠️</div><h1 class="text-xl font-bold text-gray-900 mb-2">Terjadi Kesalahan Server</h1><p class="text-sm text-gray-600 mb-6">Sistem sedang mengalami gangguan sementara. Silakan coba muat ulang halaman beberapa saat lagi.</p><div class="flex items-center justify-center gap-3"><button onclick="window.history.back()" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-sm px-5 py-2.5 rounded-xl transition-colors">Kembali</button><a href="' . htmlspecialchars($dashUrl) . '" class="bg-purple-600 hover:bg-purple-700 text-white font-bold text-sm px-5 py-2.5 rounded-xl transition-colors">Ke Dashboard</a></div></div></body></html>';
    }
}

