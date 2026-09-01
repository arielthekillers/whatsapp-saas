<?php
require __DIR__ . '/../autoload.php';

use App\Config\Database;
use App\Config\Env;

Env::load(__DIR__ . '/../.env');

$db = Database::connection();
$hash = password_hash('password123', PASSWORD_BCRYPT);

$stmt = $db->prepare("UPDATE users SET password = :hash WHERE email = 'test@email.com'");
$stmt->execute([':hash' => $hash]);

$passInDb = $db->query("SELECT password FROM users WHERE email = 'test@email.com'")->fetchColumn();
$verified = password_verify('password123', $passInDb);

echo "Password update result: " . ($verified ? "VERIFIED SUCCESS ✅" : "FAILED ❌") . "\n";
