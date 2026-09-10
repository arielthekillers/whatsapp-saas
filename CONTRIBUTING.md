# Panduan Kontribusi (CONTRIBUTING.md)

Terima kasih telah berkontribusi pada proyek **WhatsApp API SaaS (WAHA Platform)**. Dokumen ini berisi alur kerja, standar commit, dan aturan deployment yang wajib dipatuhi oleh seluruh developer dan agen AI.

---

## 🚨 Aturan Utama: Commit & Push (GitHub Desktop)

> [!IMPORTANT]
> **AI / AGEN TIDAK BOLEH MELAKUKAN COMMIT DARI TERMINAL SEBELUM ADA PERINTAH EKSPLISIT.**
>
> **PROSES COMMIT & PUSH AKAN DILAKUKAN SENDIRI OLEH USER VIA GITHUB DESKTOP.**
>
> Hal ini bertujuan agar user memiliki kontrol penuh atas perubahan yang masuk dan mencegah pemicuan *automated deployment pipeline* (CI/CD / webhook server) yang berlebihan pada server.

### Prosedur Alur Kerja:
1. **Pengembangan & Pengujian Lokal:** Agen AI / Developer melakukan perubahan kode dan pengujian di lingkungan lokal.
2. **Review Perubahan:** Agen AI memberikan ringkasan perubahan serta saran pesan commit berstandar Conventional Commits kepada user.
3. **Commit & Push via GitHub Desktop:** User meninjau perubahan di **GitHub Desktop**, memasukkan pesan commit, lalu melakukan Commit & Push secara manual jika sudah siap dideploy.


---

## 📝 Standar Pesan Commit (Conventional Commits)

Format pesan commit mengikuti standar **[Conventional Commits](https://www.conventionalcommits.org/)**:

```
<type>(<scope>): <description>

[optional body]

[optional footer(s)]
```

### 1. Type (Tipe Perubahan)
- `feat`: Penambahan fitur baru.
- `fix`: Perbaikan bug/error.
- `docs`: Perubahan atau penambahan dokumentasi.
- `style`: Penataan format kode (indentasi, spasi, semi-colon) tanpa mengubah logika.
- `refactor`: Refaktor kode yang tidak menambah fitur maupun memperbaiki bug.
- `perf`: Perubahan kode untuk meningkatkan performa.
- `test`: Penambahan atau perbaikan unit test / integration test.
- `chore`: Perubahan tugas build, dependensi, atau konfigurasi (misal: docker, CI/CD, `.gitignore`).

### 2. Scope (Cakupan - Opsional)
Area atau komponen aplikasi yang diubah, misalnya: `(auth)`, `(billing)`, `(waha)`, `(api)`, `(admin)`, `(docs)`.

### 3. Description (Deskripsi Ringkas)
- Gunakan kalimat ringkas, jelas, dan menggunakan bahasa Indonesia atau Inggris (konsisten).
- Gunakan kalimat imperatif/present tense (contoh: `tambah endpoint send message` atau `fix session timeout`).
- Jangan diakhiri dengan tanda titik.

---

## 💡 Contoh Pesan Commit

- `feat(billing): tambah fitur konfirmasi pembayaran manual`
- `fix(waha): tangani error timeout saat scan QR code`
- `docs: buat file contributing md dan atur standar commit`
- `refactor(api): sederhanakan logika rate limiting di middleware`
- `chore(docker): perbarui versi php ke 8.2-fpm`

---

## 🔄 Alur Percabangan (Git Branching Strategy)

- `main` / `master`: Branch produksi (hanya di-push saat rilis stabil).
- `development` / `dev`: Branch utama pengembangan.
- `feature/<nama-fitur>`: Branch untuk pembuatan fitur baru.
- `hotfix/<nama-bug>`: Branch untuk perbaikan cepat bug di produksi.

---

## 🧪 Pengujian Sebelum Push

Sebelum meminta izin commit/push ke user:
1. Pastikan tidak ada sintaks PHP yang rusak (`php -l <file>`).
2. Pastikan file `.env` tidak terikut dalam tracking git.
3. Pastikan aplikasi dapat berjalan normal di lingkungan lokal.
