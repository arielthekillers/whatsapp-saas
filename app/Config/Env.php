<?php
declare(strict_types=1);

namespace App\Config;

class Env
{
    private static array $vars = [];
    private static bool $loaded = false;

    public static function load(string $path): void
    {
        if (self::$loaded) {
            return;
        }

        if (!is_file($path)) {
            throw new \RuntimeException(".env file tidak ditemukan di {$path}. Salin dari .env.example terlebih dahulu.");
        }

        foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
                continue;
            }
            [$key, $value] = explode('=', $line, 2);
            $key = trim($key);
            $value = trim(trim($value), "\"'");
            self::$vars[$key] = $value;
            if (getenv($key) === false) {
                putenv("{$key}={$value}");
            }
        }

        self::$loaded = true;
    }

    public static function get(string $key, $default = null)
    {
        if (array_key_exists($key, self::$vars)) {
            return self::$vars[$key];
        }
        $val = getenv($key);
        return $val === false ? $default : $val;
    }
}
