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

        return [
            'id'    => (int) $_SESSION['user_id'],
            'name'  => (string) ($_SESSION['user_name'] ?? ''),
            'email' => (string) ($_SESSION['user_email'] ?? ''),
        ];
    }
}
