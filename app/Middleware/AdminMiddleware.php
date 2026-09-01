<?php
declare(strict_types=1);

namespace App\Middleware;

use App\Config\Database;
use App\Helpers\Response;
use PDO;

class AdminMiddleware
{
    /** 
     * Verifies that user is logged in AND has role === 'admin'
     * @return array{id:int,name:string,email:string,role:string} 
     */
    public static function handle(): array
    {
        $user = AuthMiddleware::handle();

        $db = Database::connection();
        $stmt = $db->prepare('SELECT role FROM users WHERE id = :id');
        $stmt->execute([':id' => $user['id']]);
        $role = $stmt->fetchColumn();

        if ($role !== 'admin') {
            $_SESSION['flash_dashboard_error'] = 'Akses ditolak: Anda tidak memiliki wewenang Admin.';
            Response::redirect('/dashboard');
        }

        $user['role'] = (string) $role;
        return $user;
    }
}
