<?php
declare(strict_types=1);

namespace App\Controllers\Dashboard;

use App\Helpers\Csrf;
use App\Helpers\Response;
use App\Middleware\AuthMiddleware;
use App\Repositories\ApiKeyRepository;

class ApiKeyController
{
    private ApiKeyRepository $keys;

    public function __construct()
    {
        $this->keys = new ApiKeyRepository();
    }

    public function index(): void
    {
        $user   = AuthMiddleware::handle();
        $keys   = $this->keys->findAllForUser($user['id']);
        $newKey = $_SESSION['flash_new_api_key'] ?? null;
        unset($_SESSION['flash_new_api_key']);

        require __DIR__ . '/../../../views/api-keys/index.php';
    }

    public function store(): void
    {
        $user = AuthMiddleware::handle();

        if (!Csrf::verify($_POST['_csrf'] ?? null)) {
            Response::redirect('/api-keys');
        }

        $name = trim((string) ($_POST['name'] ?? ''));
        if ($name === '') {
            $name = 'API Key';
        }

        $created = $this->keys->create($user['id'], $name);
        // Raw key hanya ditampilkan sekali via flash session, tidak pernah disimpan plaintext.
        $_SESSION['flash_new_api_key'] = $created['raw_key'];

        Response::redirect('/api-keys');
    }

    public function revoke(int $id): void
    {
        $user = AuthMiddleware::handle();

        if (!Csrf::verify($_POST['_csrf'] ?? null)) {
            Response::redirect('/api-keys');
        }

        $this->keys->revoke($user['id'], $id);
        Response::redirect('/api-keys');
    }

    public function delete(int $id): void
    {
        $user = AuthMiddleware::handle();

        if (!Csrf::verify($_POST['_csrf'] ?? null)) {
            Response::redirect('/api-keys');
        }

        $this->keys->delete($user['id'], $id);
        Response::redirect('/api-keys');
    }
}
