<?php
declare(strict_types=1);

namespace App\Helpers;

use App\Config\Env;
use RuntimeException;

class Crypto
{
    private static ?string $key = null;

    private static function getKey(): string
    {
        if (self::$key === null) {
            $raw = Env::get('CRYPTO_KEY');
            if (!$raw) {
                // Fallback secure key for development if not set in .env
                $raw = 'wApIfY-sEcReT-kEy-32-ChArAcTeRs-Long!';
            }
            self::$key = hash('sha256', $raw, true);
        }
        return self::$key;
    }

    public static function encrypt(string $value): string
    {
        $key = self::getKey();
        $iv = random_bytes(16);
        $encrypted = openssl_encrypt($value, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
        if ($encrypted === false) {
            throw new RuntimeException('Encryption failed.');
        }
        return base64_encode($iv . $encrypted);
    }

    public static function decrypt(string $payload): string
    {
        $key = self::getKey();
        $decoded = base64_decode($payload);
        if (strlen($decoded) < 17) {
            throw new RuntimeException('Invalid encrypted payload.');
        }
        $iv = substr($decoded, 0, 16);
        $encrypted = substr($decoded, 16);
        $decrypted = openssl_decrypt($encrypted, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
        if ($decrypted === false) {
            throw new RuntimeException('Decryption failed.');
        }
        return $decrypted;
    }
}
