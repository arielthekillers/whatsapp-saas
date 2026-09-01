<?php
declare(strict_types=1);

namespace App\Controllers\Dashboard;

use App\Helpers\Csrf;
use App\Helpers\Response;
use App\Helpers\Crypto;
use App\Middleware\AuthMiddleware;
use App\Repositories\WebhookRepository;
use App\Repositories\SessionRepository;

class WebhookController
{
    private WebhookRepository $webhooks;
    private SessionRepository $sessions;

    public function __construct()
    {
        $this->webhooks = new WebhookRepository();
        $this->sessions = new SessionRepository();
    }

    public function index(): void
    {
        $user = AuthMiddleware::handle();

        $subscription = (new \App\Repositories\SubscriptionRepository())->findActiveForUser($user['id']);
        if (!$subscription) {
            $_SESSION['flash_billing_error'] = 'Anda belum memiliki paket aktif. Silakan pilih paket langganan terlebih dahulu untuk mengakses fitur Webhook.';
            Response::redirect('/billing');
            return;
        }

        $webhooks = $this->webhooks->listByUser($user['id']);
        $sessions = $this->sessions->findAllForUser($user['id']);
        
        $newSecret = $_SESSION['flash_new_webhook_secret'] ?? null;
        unset($_SESSION['flash_new_webhook_secret']);
        $error = $_SESSION['flash_webhook_error'] ?? null;
        unset($_SESSION['flash_webhook_error']);

        require __DIR__ . '/../../../views/webhooks/index.php';
    }

    public function store(): void
    {
        $user = AuthMiddleware::handle();

        if (!Csrf::verify($_POST['_csrf'] ?? null)) {
            Response::redirect('/webhooks');
        }

        $subscription = (new \App\Repositories\SubscriptionRepository())->findActiveForUser($user['id']);
        if (!$subscription) {
            $_SESSION['flash_billing_error'] = 'Anda belum memiliki paket aktif. Silakan pilih paket langganan terlebih dahulu untuk menghubungkan Webhook.';
            Response::redirect('/billing');
            return;
        }

        $url = trim((string) ($_POST['url'] ?? ''));
        $sessionIdRaw = $_POST['session_id'] ?? '';
        $sessionId = $sessionIdRaw === '' ? null : (int) $sessionIdRaw;

        if ($url === '' || !filter_var($url, FILTER_VALIDATE_URL)) {
            $_SESSION['flash_webhook_error'] = 'URL Webhook wajib diisi dengan format yang valid (mis. https://domain.com/webhook).';
            Response::redirect('/webhooks');
        }

        // Generate secret key untuk HMAC signature
        $rawSecret = 'wps_' . bin2hex(random_bytes(16));
        $secretEnc = Crypto::encrypt($rawSecret);

        $this->webhooks->create($user['id'], $sessionId, $url, $secretEnc);

        // Flash secret plaintext sekali saja untuk dicatat customer
        $_SESSION['flash_new_webhook_secret'] = $rawSecret;

        Response::redirect('/webhooks');
    }

    public function delete(int $id): void
    {
        $user = AuthMiddleware::handle();

        if (!Csrf::verify($_POST['_csrf'] ?? null)) {
            Response::redirect('/webhooks');
        }

        $this->webhooks->delete($id, $user['id']);
        Response::redirect('/webhooks');
    }
}
