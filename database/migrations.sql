-- =====================================================================
-- WhatsApp API SaaS berbasis WAHA — Database Migration
-- Engine: MySQL 8.0+ / MariaDB 10.6+  (butuh SKIP LOCKED -> MySQL 8+/MariaDB 10.6+)
-- Charset: utf8mb4
-- =====================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- ROLES & ADMINS  (terpisah dari customer `users`)
-- ---------------------------------------------------------------------
CREATE TABLE roles (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name            VARCHAR(50)  NOT NULL UNIQUE,      -- superadmin, support, finance
    created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE admins (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    role_id         INT UNSIGNED NOT NULL,
    name            VARCHAR(150) NOT NULL,
    email           VARCHAR(150) NOT NULL UNIQUE,
    password        VARCHAR(255) NOT NULL,
    status          ENUM('active','suspended') NOT NULL DEFAULT 'active',
    created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(id)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- USERS (customers)
-- ---------------------------------------------------------------------
CREATE TABLE users (
    id                  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name                VARCHAR(150) NOT NULL,
    email               VARCHAR(150) NOT NULL UNIQUE,
    password            VARCHAR(255) NOT NULL,
    email_verified_at   DATETIME NULL,
    status              ENUM('active','suspended','banned') NOT NULL DEFAULT 'active',
    created_at          DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at          DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_users_status (status)
) ENGINE=InnoDB;

CREATE TABLE password_resets (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id         INT UNSIGNED NOT NULL,
    token_hash      VARCHAR(255) NOT NULL,
    expires_at      DATETIME NOT NULL,
    used_at         DATETIME NULL,
    created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_pwreset_user (user_id),
    INDEX idx_pwreset_expires (expires_at)
) ENGINE=InnoDB;

CREATE TABLE email_verifications (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id         INT UNSIGNED NOT NULL,
    token_hash      VARCHAR(255) NOT NULL,
    expires_at      DATETIME NOT NULL,
    verified_at     DATETIME NULL,
    created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_emailverif_user (user_id)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- PLANS / FEATURES
-- ---------------------------------------------------------------------
CREATE TABLE plans (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name            VARCHAR(100) NOT NULL,
    description     TEXT NULL,
    price           DECIMAL(12,2) NOT NULL DEFAULT 0,
    duration_days   INT UNSIGNED NOT NULL DEFAULT 30,
    message_limit   INT UNSIGNED NOT NULL DEFAULT 0,   -- per billing cycle
    session_limit   INT UNSIGNED NOT NULL DEFAULT 1,
    rate_limit_per_minute INT UNSIGNED NOT NULL DEFAULT 30,
    status          ENUM('active','inactive') NOT NULL DEFAULT 'active',
    created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Fitur boolean/flag per plan (mis. "custom_webhook_headers", "priority_support")
-- dipisah dari `plans` supaya query/enforce per-fitur tidak perlu parse JSON.
CREATE TABLE plan_features (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    plan_id         INT UNSIGNED NOT NULL,
    feature_key     VARCHAR(100) NOT NULL,
    feature_value   VARCHAR(255) NOT NULL,
    FOREIGN KEY (plan_id) REFERENCES plans(id) ON DELETE CASCADE,
    UNIQUE KEY uq_plan_feature (plan_id, feature_key)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- SUBSCRIPTIONS
-- ---------------------------------------------------------------------
CREATE TABLE subscriptions (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id         INT UNSIGNED NOT NULL,
    plan_id         INT UNSIGNED NOT NULL,
    start_at        DATETIME NOT NULL,
    end_at          DATETIME NOT NULL,
    status          ENUM('active','expired','cancelled') NOT NULL DEFAULT 'active',
    created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (plan_id) REFERENCES plans(id),
    INDEX idx_sub_user_status (user_id, status),
    INDEX idx_sub_end_at (end_at)
) ENGINE=InnoDB;

-- Counter quota berjalan per subscription (di-reset tiap cycle baru dibuat)
CREATE TABLE subscription_usage (
    id                  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    subscription_id     INT UNSIGNED NOT NULL,
    messages_used       INT UNSIGNED NOT NULL DEFAULT 0,
    messages_limit      INT UNSIGNED NOT NULL,
    updated_at          DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (subscription_id) REFERENCES subscriptions(id) ON DELETE CASCADE,
    UNIQUE KEY uq_sub_usage (subscription_id)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- WAHA INSTANCES (disiapkan sejak awal, boleh cuma 1 baris di awal)
-- ---------------------------------------------------------------------
CREATE TABLE waha_instances (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name            VARCHAR(100) NOT NULL,
    base_url        VARCHAR(255) NOT NULL,
    api_key_enc     TEXT NOT NULL,              -- encrypted, not plaintext
    status          ENUM('active','draining','down') NOT NULL DEFAULT 'active',
    last_health_check_at DATETIME NULL,
    created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- WHATSAPP SESSIONS
-- ---------------------------------------------------------------------
CREATE TABLE whatsapp_sessions (
    id                  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id             INT UNSIGNED NOT NULL,
    waha_instance_id    INT UNSIGNED NOT NULL,
    name                VARCHAR(100) NOT NULL,             -- nama tampilan customer
    waha_session_name   VARCHAR(150) NOT NULL UNIQUE,       -- nama unik internal, mis: usr7f3c_marketing_a1b2
    phone_number        VARCHAR(30) NULL,
    status              ENUM('CREATED','STARTING','SCAN_QR','WORKING','STOPPED','FAILED','LOGGED_OUT') NOT NULL DEFAULT 'CREATED',
    qr_code             TEXT NULL,                          -- sementara, dihapus setelah WORKING
    last_connected_at   DATETIME NULL,
    created_at          DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at          DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (waha_instance_id) REFERENCES waha_instances(id),
    INDEX idx_sessions_user (user_id),
    INDEX idx_sessions_status (status)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- API KEYS
-- ---------------------------------------------------------------------
CREATE TABLE api_keys (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id         INT UNSIGNED NOT NULL,
    name            VARCHAR(100) NOT NULL,
    api_key_prefix  VARCHAR(12) NOT NULL,           -- ditampilkan di UI utk identifikasi (mis. "wsk_ab12")
    api_key_hash    VARCHAR(255) NOT NULL,          -- hash saja, plaintext tidak pernah disimpan
    last_used_at    DATETIME NULL,
    status          ENUM('active','revoked') NOT NULL DEFAULT 'active',
    created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_apikey_user (user_id),
    INDEX idx_apikey_prefix (api_key_prefix)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- CUSTOMER WEBHOOKS
-- ---------------------------------------------------------------------
CREATE TABLE webhooks (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id         INT UNSIGNED NOT NULL,
    session_id      INT UNSIGNED NULL,              -- NULL = berlaku utk semua session milik user
    url             VARCHAR(500) NOT NULL,
    secret_enc      TEXT NOT NULL,                  -- encrypted (bukan hash, perlu dibaca ulang utk HMAC)
    status          ENUM('active','inactive') NOT NULL DEFAULT 'active',
    created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (session_id) REFERENCES whatsapp_sessions(id) ON DELETE CASCADE,
    INDEX idx_webhook_user (user_id)
) ENGINE=InnoDB;

CREATE TABLE webhook_logs (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    webhook_id      INT UNSIGNED NOT NULL,
    event           VARCHAR(100) NOT NULL,
    payload         JSON NOT NULL,
    response_code   INT NULL,
    response_body   TEXT NULL,
    attempt         TINYINT UNSIGNED NOT NULL DEFAULT 1,
    status          ENUM('pending','success','failed') NOT NULL DEFAULT 'pending',
    created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (webhook_id) REFERENCES webhooks(id) ON DELETE CASCADE,
    INDEX idx_wlog_webhook (webhook_id),
    INDEX idx_wlog_status (status),
    INDEX idx_wlog_created (created_at)
) ENGINE=InnoDB;

-- Event mentah masuk dari WAHA, sebelum diteruskan ke customer.
-- idempotency by waha_event_id mencegah proses ganda kalau WAHA retry.
CREATE TABLE webhook_inbound_events (
    id                  BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    waha_instance_id    INT UNSIGNED NOT NULL,
    waha_event_id       VARCHAR(150) NULL,
    event_type          VARCHAR(100) NOT NULL,
    session_name        VARCHAR(150) NOT NULL,
    raw_payload         JSON NOT NULL,
    processed_at        DATETIME NULL,
    created_at          DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (waha_instance_id) REFERENCES waha_instances(id),
    UNIQUE KEY uq_waha_event (waha_instance_id, waha_event_id),
    INDEX idx_inbound_processed (processed_at)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- MESSAGES
-- ---------------------------------------------------------------------
CREATE TABLE messages (
    id                  BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id             INT UNSIGNED NOT NULL,
    session_id          INT UNSIGNED NOT NULL,
    direction           ENUM('outbound','inbound') NOT NULL,
    message_type        VARCHAR(30) NOT NULL,           -- text, image, file, location, contact
    recipient           VARCHAR(30) NULL,
    waha_message_id     VARCHAR(150) NULL,
    idempotency_key     VARCHAR(150) NULL,              -- dari customer, mencegah kirim dobel
    payload             JSON NOT NULL,
    status              ENUM('queued','sent','delivered','read','failed') NOT NULL DEFAULT 'queued',
    created_at          DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (session_id) REFERENCES whatsapp_sessions(id) ON DELETE CASCADE,
    UNIQUE KEY uq_msg_idempotency (user_id, idempotency_key),
    INDEX idx_msg_session (session_id),
    INDEX idx_msg_waha_id (waha_message_id),
    INDEX idx_msg_created (created_at)
) ENGINE=InnoDB;

-- Status/ack terpisah dari record pesan utama karena WAHA mengirim
-- `message` dan `message.ack` sebagai event terpisah dengan timing berbeda.
CREATE TABLE message_events (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    message_id      BIGINT UNSIGNED NOT NULL,
    event_type      VARCHAR(50) NOT NULL,       -- ack, delivered, read, failed
    raw_payload     JSON NULL,
    created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (message_id) REFERENCES messages(id) ON DELETE CASCADE,
    INDEX idx_msgevent_message (message_id)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- USAGE LOGS (audit trail, terpisah dari counter cepat di subscription_usage)
-- ---------------------------------------------------------------------
CREATE TABLE usage_logs (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id         INT UNSIGNED NOT NULL,
    session_id      INT UNSIGNED NULL,
    type            VARCHAR(50) NOT NULL,       -- message_sent, message_received, session_created, ...
    amount          INT NOT NULL DEFAULT 1,
    metadata        JSON NULL,
    created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_usage_user_created (user_id, created_at)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- IDEMPOTENCY KEYS (generik, untuk endpoint API selain send message jika perlu)
-- ---------------------------------------------------------------------
CREATE TABLE idempotency_keys (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id         INT UNSIGNED NOT NULL,
    key_value       VARCHAR(150) NOT NULL,
    endpoint        VARCHAR(150) NOT NULL,
    response_snapshot JSON NULL,
    created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY uq_idem (user_id, endpoint, key_value)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- RATE LIMIT COUNTERS (fixed window, murah utk fallback tanpa Redis)
-- ---------------------------------------------------------------------
CREATE TABLE rate_limit_counters (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    scope_key       VARCHAR(150) NOT NULL,      -- mis: "user:123", "ip:1.2.3.4"
    window_start    DATETIME NOT NULL,          -- dibulatkan ke menit
    request_count   INT UNSIGNED NOT NULL DEFAULT 0,
    UNIQUE KEY uq_ratelimit (scope_key, window_start)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- PAYMENTS
-- ---------------------------------------------------------------------
CREATE TABLE payments (
    id                  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id             INT UNSIGNED NOT NULL,
    subscription_id     INT UNSIGNED NULL,
    provider            VARCHAR(50) NOT NULL,       -- midtrans, xendit, manual
    external_id         VARCHAR(150) NULL,
    amount              DECIMAL(12,2) NOT NULL,
    status              ENUM('pending','paid','failed','expired','refunded') NOT NULL DEFAULT 'pending',
    paid_at             DATETIME NULL,
    created_at          DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at          DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (subscription_id) REFERENCES subscriptions(id),
    INDEX idx_payment_user (user_id),
    INDEX idx_payment_status (status)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- BACKGROUND JOBS (queue sederhana berbasis DB)
-- ---------------------------------------------------------------------
CREATE TABLE jobs (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    type            VARCHAR(100) NOT NULL,      -- webhook_delivery, usage_processing, ...
    payload         JSON NOT NULL,
    status          ENUM('pending','processing','completed','failed') NOT NULL DEFAULT 'pending',
    attempts         TINYINT UNSIGNED NOT NULL DEFAULT 0,
    available_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,  -- untuk exponential backoff
    locked_at       DATETIME NULL,
    created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_jobs_status_available (status, available_at)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- AUDIT LOG (admin actions — siapa mengubah apa)
-- ---------------------------------------------------------------------
CREATE TABLE audit_logs (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    admin_id        INT UNSIGNED NULL,
    action          VARCHAR(150) NOT NULL,
    target_type     VARCHAR(100) NULL,
    target_id       VARCHAR(100) NULL,
    metadata        JSON NULL,
    created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (admin_id) REFERENCES admins(id) ON DELETE SET NULL,
    INDEX idx_audit_admin (admin_id),
    INDEX idx_audit_created (created_at)
) ENGINE=InnoDB;

SET FOREIGN_KEY_CHECKS = 1;

-- =====================================================================
-- SEED DATA MINIMAL
-- =====================================================================
INSERT INTO roles (name) VALUES ('superadmin'), ('support'), ('finance');

INSERT INTO plans (name, description, price, duration_days, message_limit, session_limit, rate_limit_per_minute)
VALUES
('FREE', 'Paket gratis untuk mencoba layanan', 0, 30, 1000, 1, 30),
('BASIC', 'Untuk bisnis kecil', 99000, 30, 10000, 3, 100),
('PRO', 'Untuk kebutuhan volume tinggi', 499000, 30, 100000, 10, 500);
