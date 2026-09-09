<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Config\Database;
use App\Config\Env;
use PDO;

class SettingRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    /**
     * Ambil nilai setting berdasarkan key.
     * Jika tidak ada di DB, gunakan fallback $default atau Env::get($key).
     */
    public static function get(string $key, ?string $default = null): ?string
    {
        try {
            $db = Database::connection();
            $stmt = $db->prepare('SELECT `value` FROM settings WHERE `key` = ? LIMIT 1');
            $stmt->execute([$key]);
            $val = $stmt->fetchColumn();

            if ($val !== false && $val !== null && $val !== '') {
                return (string) $val;
            }
        } catch (\Throwable $e) {
            // Ignore error jika tabel settings belum dibuat
        }

        return (string) Env::get($key, $default ?? '');
    }

    /**
     * Simpan / update nilai setting di database.
     */
    public static function set(string $key, string $value): void
    {
        $db = Database::connection();
        $stmt = $db->prepare('
            INSERT INTO settings (`key`, `value`)
            VALUES (:key, :val)
            ON DUPLICATE KEY UPDATE `value` = :val
        ');
        $stmt->execute([':key' => $key, ':val' => $value]);
    }

    /**
     * Ambil semua settings dalam bentuk key => value map.
     */
    public function getAll(): array
    {
        try {
            $stmt = $this->db->query('SELECT `key`, `value` FROM settings');
            return $stmt ? $stmt->fetchAll(PDO::FETCH_KEY_PAIR) : [];
        } catch (\Throwable $e) {
            return [];
        }
    }
}
