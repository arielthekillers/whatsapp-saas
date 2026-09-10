-- ==========================================================
-- MIGRATION: Fix Billing & Subscriptions Schema Discrepancies
-- Run this on your database if /billing throws HTTP 500 error
-- ==========================================================

-- 1. Ensure plan_id & transfer_note exist on payments table
ALTER TABLE payments 
    ADD COLUMN IF NOT EXISTS plan_id INT UNSIGNED NULL AFTER user_id,
    ADD COLUMN IF NOT EXISTS transfer_note TEXT NULL AFTER status;

-- 2. Ensure payments status ENUM contains all status values
ALTER TABLE payments 
    MODIFY COLUMN status ENUM('pending','verifying','paid','failed','expired','cancelled') NOT NULL DEFAULT 'pending';

-- 3. Ensure subscriptions status ENUM contains 'queued'
ALTER TABLE subscriptions 
    MODIFY COLUMN status ENUM('active','cancelled','expired','queued') NOT NULL DEFAULT 'active';
