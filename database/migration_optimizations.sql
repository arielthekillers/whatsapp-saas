-- ==========================================================
-- MIGRATION: Security & Performance Optimizations
-- Adds role column to users and essential database performance indexes
-- ==========================================================

-- 1. Add role column to users table if not exists
ALTER TABLE users 
    ADD COLUMN IF NOT EXISTS role ENUM('admin', 'customer') NOT NULL DEFAULT 'customer' AFTER email;

-- Make existing first user an admin (optional convenience)
UPDATE users SET role = 'admin' WHERE id = 1;

-- 2. Performance Indexes
-- Index on api_keys api_key_hash for fast API auth lookup
ALTER TABLE api_keys ADD INDEX idx_api_keys_hash (api_key_hash);

-- Index on rate_limit_counters scope_key & window_start
ALTER TABLE rate_limit_counters ADD INDEX idx_rate_limit_window (scope_key, window_start);
