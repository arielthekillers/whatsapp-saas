-- ==========================================================
-- MIGRATION: Subscription Queue & Upgrade/Downgrade Logic
-- Allows subscriptions status to include 'queued'
-- ==========================================================

ALTER TABLE subscriptions MODIFY COLUMN status ENUM('active','cancelled','expired','queued') NOT NULL DEFAULT 'active';
