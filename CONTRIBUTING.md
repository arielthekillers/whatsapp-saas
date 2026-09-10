# Panduan Kontribusi (CONTRIBUTING.md)

Terima kasih telah berkontribusi pada proyek **WhatsApp API SaaS (WAHA Platform)**. Dokumen ini berisi alur kerja, standar commit, dan aturan deployment yang wajib dipatuhi oleh seluruh developer dan agen AI.

---

## 🚨 Aturan Utama: Commit & Push

> [!IMPORTANT]
> **1. COMMIT LOKAL:** Agen AI boleh dan berkewajiban melakukan `git commit` dari terminal **HANYA SETELAH ADA PERINTAH EKSPLISIT DARI USER** (misal: *"commit"*, *"lakukan commit"*).
>
> **2. PUSH KE GITHUB:** Agen AI **TIDAK BOLEH** melakukan `git push` ke server/remote repository. Proses push dilakukan sendiri oleh USER via **GitHub Desktop** atau terminal jika sudah siap dideploy.
>
> Hal ini bertujuan agar user memiliki kontrol penuh atas proses rilis ke server produksi dan mencegah pemicuan *automated deployment pipeline* (CI/CD / webhook server) yang berlebihan.

### Prosedur Alur Kerja:
1. **Pengembangan & Pengujian Lokal:** Agen AI melakukan perubahan kode dan pengujian di lingkungan lokal.
2. **Perintah Commit dari User:** Setelah user meninjau perubahan dan memberikan perintah *"commit"*, Agen AI mengeksekusi `git add` & `git commit` menggunakan standar Conventional Commits di terminal lokal.
3. **Push via GitHub Desktop / Terminal:** User melakukan `git push` ke remote repository / server secara manual di GitHub Desktop saat fitur siap dideploy.


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

### 3. Language & Description (Aturan Bahasa & Deskripsi)
- **100% ENGLISH ONLY**: Semua pesan commit WAJIB ditulis dalam **Bahasa Inggris** (tidak boleh dicampur Bahasa Indonesia) agar seluruh *git commit history* di GitHub seragam dan profesional.
- Gunakan kalimat ringkas, jelas, dan imperatif / present-tense (contoh: `feat(messages): add direct send form and multi-session message log UI` bukan `tambah fitur pesan`).
- Jangan mengakhiri pesan commit dengan tanda titik (`.`).

---

## 💡 Contoh Pesan Commit (100% English Examples)

- `feat(messages): add direct send form and multi-session log view`
- `fix(waha): resolve QR code polling loop timeout`
- `docs(api): update media endpoints and caption parameters`
- `refactor(ui): harmonize page header layouts across dashboard modules`
- `chore(docker): update PHP base image to 8.2-fpm`

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
