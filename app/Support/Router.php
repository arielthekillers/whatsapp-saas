<?php
declare(strict_types=1);

namespace App\Support;

class Router
{
    private array $routes = [];

    public function get(string $path, $handler): void
    {
        $this->add('GET', $path, $handler);
    }

    public function post(string $path, $handler): void
    {
        $this->add('POST', $path, $handler);
    }

    private function add(string $method, string $path, $handler): void
    {
        $pattern = preg_replace('#\{[a-zA-Z_]+\}#', '([^/]+)', trim($path, '/'));
        $this->routes[] = [
            'method'  => $method,
            'pattern' => '#^' . $pattern . '$#',
            'handler' => $handler,
        ];
    }
    public function dispatch(string $method, string $uri): void
    {
        $path = (string) parse_url($uri, PHP_URL_PATH);

        // Strip project base directory if running in subdirectory
        $projectRoot = str_replace('\\', '/', dirname(dirname(__DIR__)));
        $docRoot = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT'] ?? '');
        if ($docRoot !== '' && str_starts_with($projectRoot, $docRoot)) {
            $basePath = substr($projectRoot, strlen($docRoot));
            if ($basePath !== '' && str_starts_with($path, $basePath)) {
                $path = substr($path, strlen($basePath));
            }
        }

        // Strip public prefix if accessed through public/ directory directly
        if (str_starts_with($path, '/public')) {
            $path = substr($path, 7);
        }

        $path = trim($path, '/');

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }
            if (preg_match($route['pattern'], $path, $matches)) {
                array_shift($matches);
                $this->invoke($route['handler'], $matches);
                return;
            }
        }

        http_response_code(404);
        require __DIR__ . '/../../views/errors/404.php';
    }

    private function invoke($handler, array $params): void
    {
        $casted = array_map(static fn ($p) => ctype_digit($p) ? (int) $p : $p, $params);

        if (is_array($handler)) {
            [$class, $method] = $handler;
            (new $class())->$method(...$casted);
            return;
        }

        $handler(...$casted);
    }
}
