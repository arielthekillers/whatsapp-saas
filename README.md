# WhatsApp API SaaS (WAHA Platform)

Platform SaaS penyedia REST API WhatsApp berbasis **WAHA (WhatsApp HTTP API)** dengan manajemen multi-session, kuota pesan, billing pembayaran manual, webhook, dan admin panel.

---

## 🚀 Fitur Utama

### 👤 Customer Dashboard
- **Autentikasi**: Register, Login, Logout, & Manajemen Profil/Password.
- **WhatsApp Sessions**: Tambah sesi, scan QR code, pantau status real-time, dan hentikan sesi.
- **API Keys**: Generate & revoke API Key (dikunci dengan SHA-256) untuk akses REST API.
- **Usage & Quota**: Monitoring penggunaan kuota pesan dan rate limit per menit secara real-time.
- **Webhooks**: Daftarkan URL webhook untuk menerima event pesan masuk/status secara otomatis.
- **Billing & Subscription**: Pilih paket/plan, checkout, dan upload/konfirmasi pembayaran transfer bank.
- **Dokumentasi API**: Terintegrasi langsung di dashboard (`/docs`).

### 🛡️ Admin Panel (`/admin`)
- **Manajemen Plan**: Atur harga, limit sesi, dan batas kuota pesan bulanan.
- **Verifikasi Pembayaran**: Setujui (approve) atau tolak (reject) konfirmasi pembayaran manual pelanggan.

### 🔌 REST API (`/v1/*`)
- **Kirim Pesan**: `POST /v1/messages/send` dengan fitur *Idempotency Key* (mencegah pesan ganda).
- **Manajemen Sesi API**: `GET/POST /v1/sessions`, `POST /v1/sessions/{id}/start`, `POST /v1/sessions/{id}/stop`.
- **Informasi Akun & Penggunaan**: `GET /v1/account`, `GET /v1/usage`.
- **Proteksi**: Autentikasi `Bearer Token`, Atomic Quota Reservation, dan Fixed-Window Rate Limiting per menit.

---

## 🛠️ Persyaratan Sistem

- **PHP 8.2+** (ekstensi `pdo_mysql`, `curl`)
- **MySQL 8.0+** atau **MariaDB 10.6+**
- **WAHA Instance** (WhatsApp HTTP API service yang sudah berjalan)

---

## ⚡ Panduan Instalasi Cepat

### 1. Clone & Konfigurasi `.env`
```bash
git clone <URL_REPO_ANDA> whatsapp-saas
cd whatsapp-saas
cp .env.example .env
```
Edit file `.env` dan sesuaikan koneksi database serta alamat WAHA Anda:
```env
DB_HOST=127.0.0.1
DB_NAME=whatsapp_saas
DB_USER=root
DB_PASS=

WAHA_BASE_URL=http://localhost:3000
WAHA_API_KEY=
```

### 2. Import Database
```bash
mysql -u root -p -e "CREATE DATABASE whatsapp_saas CHARACTER SET utf8mb4"
mysql -u root -p whatsapp_saas < database/migrations.sql
mysql -u root -p whatsapp_saas < database/migration_manual_payment.sql
```

### 3. Tambah WAHA Instance Default
```sql
INSERT INTO waha_instances (name, base_url, api_key_enc, status)
VALUES ('primary', 'http://127.0.0.1:3000', 'not-used-yet', 'active');
```

---

## 🏃 Menjalankan Aplikasi

### Local Development Server
```bash
php -S 0.0.0.0:8000 -t public public/router.php
```
Akses aplikasi di browser:
- **Landing Page**: `http://localhost:8000/`
- **Register/Login**: `http://localhost:8000/register`
- **Dashboard**: `http://localhost:8000/dashboard`
- **Admin Panel**: `http://localhost:8000/admin`

---

## 🐳 Docker Deployment

Aplikasi ini sudah dilengkapi Dockerfile & supervisord:
```bash
docker build -t whatsapp-saas .
docker run -d -p 8000:8000 --env-file .env whatsapp-saas
```

---

## 📖 Contoh Penggunaan API

**Kirim Pesan WhatsApp via Curl:**
```bash
curl -X POST http://localhost:8000/v1/messages/send \
  -H "Authorization: Bearer wsk_your_api_key_here" \
  -H "Content-Type: application/json" \
  -H "Idempotency-Key: invoice-99182" \
  -d '{
    "session": "marketing",
    "to": "628123456789",
    "text": "Halo, pesan ini dikirim dari WhatsApp API SaaS!"
  }'
```

---

## 📄 Lisensi
[MIT License](LICENSE)
