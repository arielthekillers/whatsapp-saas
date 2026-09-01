-- ==========================================================
-- MIGRATION: Payment Status Cancelled Support
-- Adds 'cancelled' status to payments table
-- ==========================================================

ALTER TABLE payments MODIFY COLUMN status ENUM('pending','verifying','paid','failed','expired','cancelled') NOT NULL DEFAULT 'pending';
