-- ==========================================================
-- MIGRATION: Update Pricing Plans (LITE / PRO / BUSINESS)
-- ==========================================================

SET FOREIGN_KEY_CHECKS = 0;

-- Nonaktifkan semua paket lama agar tidak ada paket ganda/duplikat
UPDATE plans SET status = 'inactive';

-- Insert atau Aktifkan 3 paket utama baru
INSERT INTO plans (name, description, price, duration_days, message_limit, session_limit, rate_limit_per_minute, status)
VALUES
('LITE', 'Untuk integrasi project kecil & testing API', 29000.00, 30, 2000, 1, 30, 'active'),
('PRO', 'Untuk sistem informasi, notifikasi, & bot produksi', 79000.00, 30, 15000, 3, 100, 'active'),
('BUSINESS', 'Untuk skala tinggi & sistem dengan banyak nomor', 149000.00, 30, 100000, 10, 500, 'active')
ON DUPLICATE KEY UPDATE 
    description = VALUES(description),
    price = VALUES(price),
    message_limit = VALUES(message_limit),
    session_limit = VALUES(session_limit),
    rate_limit_per_minute = VALUES(rate_limit_per_minute),
    status = 'active';

SET FOREIGN_KEY_CHECKS = 1;
