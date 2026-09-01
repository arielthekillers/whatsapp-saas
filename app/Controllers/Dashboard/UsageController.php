<?php
declare(strict_types=1);

namespace App\Controllers\Dashboard;

use App\Config\Database;
use App\Middleware\AuthMiddleware;
use App\Repositories\SubscriptionRepository;
use PDO;

class UsageController
{
    public function index(): void
    {
        $user = AuthMiddleware::handle();
        $db = Database::connection();
        $userId = $user['id'];

        $subscription = (new SubscriptionRepository())->findActiveForUser($userId);

        // Hitung hari tersisa
        $daysRemaining = 0;
        $daysTotal = 0;
        $daysUsed = 0;
        $daysPct = 0;
        if ($subscription) {
            $endAt = new \DateTime($subscription['end_at']);
            $startAt = null;

            // Ambil start_at dari tabel subscriptions
            $stmtSub = $db->prepare('SELECT start_at FROM subscriptions WHERE id = ? LIMIT 1');
            $stmtSub->execute([$subscription['subscription_id']]);
            $subRow = $stmtSub->fetch(PDO::FETCH_ASSOC);
            if ($subRow) {
                $startAt = new \DateTime($subRow['start_at']);
            }

            $now = new \DateTime();
            $daysRemaining = max(0, (int) $now->diff($endAt)->days);
            if ($startAt) {
                $daysTotal = max(1, (int) $startAt->diff($endAt)->days);
                $daysUsed = max(0, (int) $startAt->diff($now)->days);
                $daysPct = min(100, (int) round($daysUsed / $daysTotal * 100));
            }
        }

        // Pesan dikirim per hari (7 hari terakhir)
        $stmtDaily = $db->prepare('
            SELECT DATE(created_at) AS day, COUNT(*) AS total
            FROM messages
            WHERE user_id = ? AND direction = "outbound" AND created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
            GROUP BY DATE(created_at)
            ORDER BY day ASC
        ');
        $stmtDaily->execute([$userId]);
        $dailyMessages = $stmtDaily->fetchAll(PDO::FETCH_ASSOC);

        // Ringkasan tipe pesan terkirim
        $stmtTypes = $db->prepare('
            SELECT message_type, COUNT(*) AS total
            FROM messages
            WHERE user_id = ? AND direction = "outbound"
            GROUP BY message_type
            ORDER BY total DESC
        ');
        $stmtTypes->execute([$userId]);
        $messageTypes = $stmtTypes->fetchAll(PDO::FETCH_ASSOC);

        // Total pesan masuk & keluar
        $stmtTotal = $db->prepare('
            SELECT
                SUM(CASE WHEN direction = "outbound" THEN 1 ELSE 0 END) AS sent,
                SUM(CASE WHEN direction = "inbound" THEN 1 ELSE 0 END) AS received
            FROM messages WHERE user_id = ?
        ');
        $stmtTotal->execute([$userId]);
        $totals = $stmtTotal->fetch(PDO::FETCH_ASSOC);

        // Jumlah session aktif
        $stmtSessions = $db->prepare('
            SELECT COUNT(*) AS total, SUM(status = "WORKING") AS working
            FROM whatsapp_sessions WHERE user_id = ?
        ');
        $stmtSessions->execute([$userId]);
        $sessionStats = $stmtSessions->fetch(PDO::FETCH_ASSOC);

        // Pesan dikirim hari ini
        $stmtToday = $db->prepare('
            SELECT COUNT(*) AS total FROM messages
            WHERE user_id = ? AND direction = "outbound" AND DATE(created_at) = CURDATE()
        ');
        $stmtToday->execute([$userId]);
        $sentToday = (int) $stmtToday->fetchColumn();

        // 10 Pesan terakhir dikirim
        $stmtRecent = $db->prepare('
            SELECT m.created_at, m.message_type, m.recipient, m.status,
                   ws.name AS session_name
            FROM messages m
            LEFT JOIN whatsapp_sessions ws ON ws.id = m.session_id
            WHERE m.user_id = ? AND m.direction = "outbound"
            ORDER BY m.id DESC LIMIT 10
        ');
        $stmtRecent->execute([$userId]);
        $recentMessages = $stmtRecent->fetchAll(PDO::FETCH_ASSOC);

        require __DIR__ . '/../../../views/usage/index.php';
    }
}
