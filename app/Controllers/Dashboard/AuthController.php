<?php
declare(strict_types=1);

namespace App\Controllers\Dashboard;

use App\Helpers\Csrf;
use App\Helpers\Response;
use App\Repositories\SubscriptionRepository;
use App\Repositories\UserRepository;

class AuthController
{
    private UserRepository $users;
    private SubscriptionRepository $subscriptions;

    public function __construct()
    {
        $this->users = new UserRepository();
        $this->subscriptions = new SubscriptionRepository();
    }

    public function showLogin(): void
    {
        if (!empty($_SESSION['user_id'])) {
            Response::redirect('/dashboard');
        }
        $error = null;
        require __DIR__ . '/../../../views/auth/login.php';
    }

    public function login(): void
    {
        if (!Csrf::verify($_POST['_csrf'] ?? null)) {
            $error = 'Sesi tidak valid, silakan muat ulang halaman dan coba lagi.';
            require __DIR__ . '/../../../views/auth/login.php';
            return;
        }

        $email    = trim((string) ($_POST['email'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');

        // Anti Brute-force rate limiting: max 5 failed attempts per 15 mins per IP
        $clientIp = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        $attemptKey = 'login_attempts_' . md5($clientIp);
        $attempts = $_SESSION[$attemptKey] ?? ['count' => 0, 'time' => time()];

        if ($attempts['count'] >= 5 && (time() - $attempts['time']) < 900) {
            $remaining = ceil((900 - (time() - $attempts['time'])) / 60);
            $error = "Terlalu banyak percobaan login gagal. Silakan coba lagi dalam {$remaining} menit.";
            require __DIR__ . '/../../../views/auth/login.php';
            return;
        }

        $user = $this->users->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            if ((time() - $attempts['time']) > 900) {
                $attempts = ['count' => 1, 'time' => time()];
            } else {
                $attempts['count']++;
            }
            $_SESSION[$attemptKey] = $attempts;

            $error = 'Email atau password salah.';
            require __DIR__ . '/../../../views/auth/login.php';
            return;
        }

        unset($_SESSION[$attemptKey]);

        if ($user['status'] !== 'active') {
            $error = 'Akun Anda tidak aktif. Hubungi support kami.';
            require __DIR__ . '/../../../views/auth/login.php';
            return;
        }

        session_regenerate_id(true);
        $_SESSION['user_id']    = (int) $user['id'];
        $_SESSION['user_name']  = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role']  = $user['role'] ?? 'customer';

        if ($_SESSION['user_role'] === 'admin') {
            Response::redirect('/admin');
        }

        Response::redirect('/dashboard');
    }

    public function showRegister(): void
    {
        if (!empty($_SESSION['user_id'])) {
            Response::redirect('/dashboard');
        }
        $error = null;
        require __DIR__ . '/../../../views/auth/register.php';
    }

    public function register(): void
    {
        if (!Csrf::verify($_POST['_csrf'] ?? null)) {
            $error = 'Sesi tidak valid, silakan muat ulang halaman dan coba lagi.';
            require __DIR__ . '/../../../views/auth/register.php';
            return;
        }

        $name     = trim((string) ($_POST['name'] ?? ''));
        $email    = trim((string) ($_POST['email'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');
        $passwordConfirm = (string) ($_POST['password_confirm'] ?? '');

        if ($name === '' || $email === '' || strlen($password) < 8) {
            $error = 'Nama dan email wajib diisi, password minimal 8 karakter.';
            require __DIR__ . '/../../../views/auth/register.php';
            return;
        }

        if ($password !== $passwordConfirm) {
            $error = 'Konfirmasi password tidak cocok.';
            require __DIR__ . '/../../../views/auth/register.php';
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Format email tidak valid.';
            require __DIR__ . '/../../../views/auth/register.php';
            return;
        }

        if ($this->users->findByEmail($email)) {
            $error = 'Email sudah terdaftar. Silakan login.';
            require __DIR__ . '/../../../views/auth/register.php';
            return;
        }

        $hash   = password_hash($password, PASSWORD_BCRYPT);
        $userId = $this->users->create($name, $email, $hash);

        // Setiap customer baru otomatis dapat subscription FREE supaya
        // quota/rate-limit langsung berlaku sejak awal (Phase 4 akan
        // menambahkan upgrade/downgrade plan berbayar di atas ini).
        $this->subscriptions->ensureFreeSubscription($userId);

        session_regenerate_id(true);
        $_SESSION['user_id']    = $userId;
        $_SESSION['user_name']  = $name;
        $_SESSION['user_email'] = $email;

        Response::redirect('/dashboard');
    }

    public function logout(): void
    {
        $_SESSION = [];
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
        Response::redirect('/login');
    }
}
