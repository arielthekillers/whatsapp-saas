<?php
declare(strict_types=1);

namespace App\Services;

class SessionNameGenerator
{
    /**
     * Nama session WAHA harus unik & tidak boleh membocorkan ID mentah
     * customer (mis. mudah ditebak/diurutkan). Format:
     * u<hash8>_<slug>_<random6hex>
     */
    public static function generate(int $userId, string $label): string
    {
        $slug = strtolower((string) preg_replace('/[^a-z0-9]+/i', '_', trim($label)));
        $slug = trim($slug, '_');
        if ($slug === '') {
            $slug = 'session';
        }

        $userToken = substr(md5('user_' . $userId), 0, 8);
        $unique    = bin2hex(random_bytes(3));

        return "u{$userToken}_{$slug}_{$unique}";
    }
}
