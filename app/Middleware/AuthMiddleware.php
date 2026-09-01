<?php
declare(strict_types=1);

namespace App\Middleware;

use App\Helpers\Response;

class AuthMiddleware
{
    /** @return array{id:int,name:string,email:string} */
    public static function handle(): array
    {
        if (empty($_SESSION['user_id'])) {
            Response::redirect('/login');
        }

        $db = \App\Config\Database::connection();
        $stmt = $db->prepare('SELECT status FROM users WHERE id = :id');
        $stmt->execute([':id' => $_SESSION['user_id']]);
        $status = $stmt->fetchColumn();

        if ($status !== 'active') {
            $_SESSION = [];
            if (session_status() === PHP_SESSION_ACTIVE) {
                session_destroy();
            }
            session_start();
            $_SESSION['flash_login_error'] = 'Akun Anda dinonaktifkan/ditangguhkan oleh administrator.';
            Response::redirect('/login');
        }

        return [
            'id'    => (int) $_SESSION['user_id'],
            'name'  => (string) ($_SESSION['user_name'] ?? ''),
            'email' => (string) ($_SESSION['user_email'] ?? ''),
        ];
    }
}
