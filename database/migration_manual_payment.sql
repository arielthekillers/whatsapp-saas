-- ==========================================================
-- MIGRATION: Add manual payment system columns to payments
-- Run this if the payments table was created without these columns
-- ==========================================================

-- Add plan_id column (direct reference, replacing price-matching join)
ALTER TABLE payments 
    ADD COLUMN IF NOT EXISTS plan_id INT UNSIGNED NULL AFTER user_id,
    ADD FOREIGN KEY IF NOT EXISTS fk_payments_plan (plan_id) REFERENCES plans(id);

-- Add transfer_note for manual payment confirmation details
ALTER TABLE payments 
    ADD COLUMN IF NOT EXISTS transfer_note TEXT NULL AFTER status;

-- Add subscription_id for linking after approval
ALTER TABLE payments
    ADD COLUMN IF NOT EXISTS subscription_id INT UNSIGNED NULL AFTER transfer_note,
    ADD COLUMN IF NOT EXISTS paid_at DATETIME NULL AFTER subscription_id;

-- Update payments status ENUM to include 'verifying'
-- (verifying = user has submitted transfer proof, admin yet to approve)
ALTER TABLE payments 
    MODIFY COLUMN status ENUM('pending','verifying','paid','expired','failed') NOT NULL DEFAULT 'pending';

-- Update provider value convention:
-- Old: 'mock_payment:3' → New: 'bank_transfer:3'
-- This is non-breaking, controller handles both prefixes
UPDATE payments SET provider = REPLACE(provider, 'mock_payment:', 'bank_transfer:') 
WHERE provider LIKE 'mock_payment:%';
