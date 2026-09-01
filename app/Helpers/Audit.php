<?php
declare(strict_types=1);

namespace App\Helpers;

use App\Config\Database;
use Exception;

class Audit
{
    public static function log(int $adminId, string $action, ?string $targetType = null, ?string $targetId = null, ?array $metadata = null): void
    {
        try {
            $db = Database::connection();
            $stmt = $db->prepare('
                INSERT INTO audit_logs (admin_id, action, target_type, target_id, metadata, created_at)
                VALUES (:admin_id, :action, :target_type, :target_id, :metadata, NOW())
            ');
            $stmt->execute([
                ':admin_id'    => $adminId,
                ':action'      => $action,
                ':target_type' => $targetType,
                ':target_id'   => $targetId,
                ':metadata'    => $metadata !== null ? json_encode($metadata) : null,
            ]);
        } catch (Exception $e) {
            error_log('Audit log failure: ' . $e->getMessage());
        }
    }
}
