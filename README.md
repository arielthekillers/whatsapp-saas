# WhatsApp API SaaS berbasis WAHA — Phase 1

Bootstrap aplikasi: koneksi DB, autentikasi customer, `WahaService`,
manajemen WhatsApp session + QR, dashboard dasar.

## 1. Requirement

- PHP 8.2+ dengan ekstensi `pdo_mysql` dan `curl`
- MySQL 8.0+ atau MariaDB 10.6+
- WAHA sudah berjalan dan bisa diakses dari server ini

## 2. Setup

```bash
cp .env.example .env
# edit .env: isi DB_*, WAHA_BASE_URL, WAHA_API_KEY, APP_URL

mysql -u root -p -e "CREATE DATABASE whatsapp_saas CHARACTER SET utf8mb4"
mysql -u root -p whatsapp_saas < database/migrations.sql
```

Tambahkan minimal satu baris WAHA instance (Phase 1 masih single-instance;
kolom `api_key_enc` boleh diisi placeholder karena `WahaService` saat ini
membaca kredensial dari `.env`, bukan dari tabel ini — tabel ini disiapkan
untuk multi-instance di fase berikutnya):

```sql
INSERT INTO waha_instances (name, base_url, api_key_enc, status)
VALUES ('primary', 'http://127.0.0.1:3000', 'not-used-yet', 'active');
```

## 3. Menjalankan (development)

```bash
php -S 0.0.0.0:8000 -t public public/router.php
```

`router.php` dipakai supaya built-in dev server bisa membedakan
`/v1/*` (API), `/webhook/waha`, dan dashboard di satu perintah yang
sama. Di production (Apache/Nginx), arahkan lewat rewrite rule biasa
ke `public/index.php`, `public/api.php`, `public/webhook.php`.

Buka `http://localhost:8000/register` untuk membuat akun pertama —
akun baru otomatis mendapat subscription plan **FREE** (asalkan sudah
di-seed lewat `migrations.sql`) supaya quota & rate limit langsung aktif.

## 4. Yang sudah jalan sampai Phase 2

**Phase 1**
- Register / Login / Logout (session-based, CSRF protected, password di-hash)
- Dashboard ringkas, CRUD WhatsApp session + QR Code + polling status
- `WahaService` sebagai satu-satunya titik komunikasi ke WAHA REST API

**Phase 2**
- Halaman **API Keys** (`/api-keys`) — generate key (ditampilkan sekali,
  disimpan sebagai SHA-256 hash), cabut key
- Public API `/v1/...` dengan autentikasi `Authorization: Bearer <API_KEY>`:
  - `POST /v1/messages/send` — kirim pesan teks, dengan **idempotency key**
    (body `idempotency_key` atau header `Idempotency-Key`) supaya retry
    customer tidak mengirim pesan dobel
  - `GET/POST /v1/sessions`, `GET /v1/sessions/{id}`,
    `POST /v1/sessions/{id}/start`, `POST /v1/sessions/{id}/stop`
  - `GET /v1/account`, `GET /v1/usage`
- **Quota**: direservasi atomik di database SEBELUM memanggil WAHA
  (`UPDATE ... WHERE messages_used < messages_limit`), aman terhadap
  request paralel
- **Rate limit**: fixed-window per menit berdasarkan `rate_limit_per_minute`
  pada plan aktif customer, tabel `rate_limit_counters`
- **Session limit** per plan ditegakkan baik di dashboard maupun API
- Halaman **Usage** (`/usage`) menampilkan pemakaian kuota customer

### Contoh curl

```bash
# Kirim pesan
curl -X POST http://localhost:8000/v1/messages/send \
  -H "Authorization: Bearer wsk_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx" \
  -H "Content-Type: application/json" \
  -H "Idempotency-Key: order-12345" \
  -d '{"session": "marketing", "to": "628123456789", "text": "Halo dari API"}'

# Cek usage
curl http://localhost:8000/v1/usage \
  -H "Authorization: Bearer wsk_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"
```

## 5. Yang BELUM ada (menyusul di phase berikutnya)

- Phase 3: webhook WAHA penuh (saat ini `public/webhook.php` baru placeholder
  yang mencatat payload ke log dan membalas 200), customer webhook, HMAC
  signature, retry + exponential backoff, worker CLI (`worker.php`)
- Phase 4: halaman upgrade/downgrade plan, integrasi payment gateway
  (saat ini plan FREE di-assign otomatis, belum ada alur beli plan berbayar)
- Phase 5: admin panel, dokumentasi API publik (`/api-docs`), landing page

## 6. Catatan penting

- `WahaService` (`app/Services/WahaService.php`) mengikuti dokumentasi
  publik WAHA per saat kode ini dibuat. **Wajib dicek ulang** terhadap
  Swagger UI WAHA milikmu sendiri (`{WAHA_BASE_URL}/`) sebelum production —
  lihat komentar di bagian atas file tersebut. Semua penyesuaian endpoint
  cukup dilakukan di satu file ini.
- Nama session internal WAHA di-generate otomatis (`SessionNameGenerator`)
  agar tidak membocorkan ID customer mentah dan menghindari collision.
- QR code disimpan sementara di kolom `qr_code` dan otomatis dihapus
  begitu status menjadi `WORKING`.
- Field ID pesan pada response `sendText` WAHA (`app/Controllers/Api/MessageApiController.php`)
  diasumsikan berada di `id` atau `_data.id` — **verifikasi terhadap response
  WAHA-mu yang sebenarnya** dan sesuaikan bila perlu.
- API key di-hash dengan SHA-256 (bukan bcrypt) karena API key sudah berupa
  random string berentropi tinggi, bukan password pilihan manusia — ini
  praktik standar dan lebih cepat untuk lookup per-request.
