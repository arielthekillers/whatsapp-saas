-- ==========================================================
-- MIGRATION: Advanced Admin Features
-- Creates announcements table for system-wide broadcasts
-- ==========================================================

CREATE TABLE IF NOT EXISTS announcements (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    message     TEXT NOT NULL,
    type        ENUM('info','warning','danger') NOT NULL DEFAULT 'info',
    is_active   TINYINT(1) NOT NULL DEFAULT 1,
    created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
