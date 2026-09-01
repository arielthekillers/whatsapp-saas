<?php
declare(strict_types=1);

namespace App\Controllers\Dashboard;

use App\Config\Database;
use App\Helpers\Csrf;
use App\Helpers\Response;
use App\Middleware\AuthMiddleware;
use PDO;

class ProfileController
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function index(): void
    {
        $user = AuthMiddleware::handle();
        
        $success = $_SESSION['flash_profile_success'] ?? null;
        unset($_SESSION['flash_profile_success']);
        $error = $_SESSION['flash_profile_error'] ?? null;
        unset($_SESSION['flash_profile_error']);

        require __DIR__ . '/../../../views/profile/index.php';
    }

    public function updatePassword(): void
    {
        $user = AuthMiddleware::handle();

        if (!Csrf::verify($_POST['_csrf'] ?? null)) {
            Response::redirect('/profile');
        }

        $currentPassword = (string) ($_POST['current_password'] ?? '');
        $newPassword = (string) ($_POST['new_password'] ?? '');
        $confirmPassword = (string) ($_POST['confirm_password'] ?? '');

        if (strlen($newPassword) < 8) {
            $_SESSION['flash_profile_error'] = 'Password baru harus minimal 8 karakter.';
            Response::redirect('/profile');
        }

        if ($newPassword !== $confirmPassword) {
            $_SESSION['flash_profile_error'] = 'Konfirmasi password baru tidak cocok.';
            Response::redirect('/profile');
        }

        // Dapatkan password hash saat ini dari DB
        $stmt = $this->db->prepare('SELECT password FROM users WHERE id = ? LIMIT 1');
        $stmt->execute([$user['id']]);
        $dbPassword = $stmt->fetchColumn();

        if (!$dbPassword || !password_verify($currentPassword, $dbPassword)) {
            $_SESSION['flash_profile_error'] = 'Password saat ini salah.';
            Response::redirect('/profile');
        }

        // Update password baru
        $newHash = password_hash($newPassword, PASSWORD_BCRYPT);
        $stmtUpdate = $this->db->prepare('UPDATE users SET password = ? WHERE id = ?');
        $stmtUpdate->execute([$newHash, $user['id']]);

        $_SESSION['flash_profile_success'] = 'Password Anda telah berhasil diperbarui!';
        Response::redirect('/profile');
    }
}
