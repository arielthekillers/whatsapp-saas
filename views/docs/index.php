<?php $title = 'Dokumentasi API'; require __DIR__ . '/../layouts/header.php'; require __DIR__ . '/../layouts/nav.php'; ?>
<div class="max-w-5xl mx-auto px-4 py-8">
  <div class="mb-8">
    <h1 class="text-2xl font-bold tracking-tight text-gray-900 font-display">Dokumentasi API Developer</h1>
    <p class="text-gray-500 text-sm mt-1">Integrasikan pengiriman pesan WhatsApp otomatis Wapify ke dalam aplikasi dan sistem Anda.</p>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
    <!-- Sidebar Menu Dokumentasi -->
    <div class="lg:col-span-1 space-y-2 sticky top-24 self-start">
      <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-2">Panduan Utama</h3>
      <a href="#auth" class="block text-sm text-gray-600 hover:text-purple-600 font-medium py-1">Otentikasi API</a>
      <a href="#idempotency" class="block text-sm text-gray-600 hover:text-purple-600 font-medium py-1">Idempotency Key</a>
      <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 mt-4 mb-2">Endpoints Pesan</h3>
      <a href="#send-text" class="block text-sm text-gray-600 hover:text-purple-600 font-medium py-1">1. Pesan Teks</a>
      <a href="#send-image" class="block text-sm text-gray-600 hover:text-purple-600 font-medium py-1">2. Gambar & Media</a>
      <a href="#send-file" class="block text-sm text-gray-600 hover:text-purple-600 font-medium py-1">3. Dokumen & File</a>
      <a href="#send-location" class="block text-sm text-gray-600 hover:text-purple-600 font-medium py-1">4. Lokasi / Map</a>
      <a href="#send-contact" class="block text-sm text-gray-600 hover:text-purple-600 font-medium py-1">5. Kartu Kontak</a>
      <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 mt-4 mb-2">Management</h3>
      <a href="#list-sessions" class="block text-sm text-gray-600 hover:text-purple-600 font-medium py-1">Daftar Sesi WhatsApp</a>
    </div>

    <!-- Konten Dokumentasi -->
    <div class="lg:col-span-3 space-y-12 text-sm text-gray-600 leading-relaxed">
      <!-- Section Otentikasi -->
      <section id="auth" class="space-y-3">
        <h2 class="text-lg font-bold text-gray-800 font-display border-b pb-2">Otentikasi API</h2>
        <p>Seluruh permintaan HTTP ke API Wapify wajib menyertakan kunci API berupa <strong>Bearer Token</strong> di header HTTP <code>Authorization</code>.</p>
        <p>Generasi Kunci API baru Anda dapat dilakukan melalui halaman <a href="<?= url('/api-keys') ?>" class="text-purple-600 hover:underline font-semibold">API Keys</a>.</p>
        <div class="bg-gray-900 text-gray-200 rounded-xl p-4 font-mono text-xs overflow-x-auto space-y-1">
          <p><span class="text-purple-400">GET</span> /v1/sessions HTTP/1.1</p>
          <p>Host: <?= parse_url(url(), PHP_URL_HOST) ?? 'wapify.biz.id' ?></p>
          <p><span class="text-blue-400">Authorization:</span> Bearer wsk_your_secret_api_key_here</p>
        </div>
      </section>

      <!-- Section Idempotency -->
      <section id="idempotency" class="space-y-3">
        <h2 class="text-lg font-bold text-gray-800 font-display border-b pb-2">Idempotency Key</h2>
        <p>Gunakan header <code>X-Idempotency-Key</code> (atau field <code>idempotency_key</code> pada body JSON) untuk mencegah duplikasi pesan saat re-try network timeout.</p>
        <p>Jika server menerima permintaan ulang dengan ID yang sama, response yang disimpan akan dikembalikan secara instant tanpa mengirim ulang pesan.</p>
      </section>

      <!-- Section Kirim Pesan Teks -->
      <section id="send-text" class="space-y-3">
        <div class="flex items-center justify-between border-b pb-2">
          <h2 class="text-lg font-bold text-gray-800 font-display">1. Kirim Pesan Teks</h2>
          <span class="bg-purple-100 text-purple-700 text-xs px-2.5 py-1 rounded-full font-bold uppercase">POST</span>
        </div>
        <p>Endpoint dasar untuk mengirimkan pesan teks biasa.</p>
        <p><code class="font-mono text-xs bg-gray-100 px-2 py-1 rounded font-bold text-gray-800"><?= url('/v1/messages/send') ?></code></p>
        
        <h4 class="font-bold text-gray-800 pt-2 text-xs uppercase tracking-wider">Payload Body (JSON)</h4>
        <div class="bg-gray-900 text-gray-200 rounded-xl p-4 font-mono text-xs overflow-x-auto">
<pre>{
  "session": "test",             // Nama sesi WhatsApp di dashboard
  "to": "081359774765",         // Nomor penerima (08xx / 628xx)
  "type": "text",               // Tipe pesan (default: text)
  "text": "Halo! Ini pesan teks via API Wapify."
}</pre>
        </div>

        <div class="bg-gray-900 text-gray-200 rounded-xl p-4 font-mono text-xs overflow-x-auto">
<pre>curl -X POST <?= url('/v1/messages/send') ?> \
  -H "Authorization: Bearer YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{
    "session": "test",
    "to": "081359774765",
    "type": "text",
    "text": "Halo! Ini pesan teks via API Wapify."
  }'</pre>
        </div>
      </section>

      <!-- Section Kirim Gambar -->
      <section id="send-image" class="space-y-3">
        <div class="flex items-center justify-between border-b pb-2">
          <h2 class="text-lg font-bold text-gray-800 font-display">2. Kirim Gambar & Media</h2>
          <span class="bg-purple-100 text-purple-700 text-xs px-2.5 py-1 rounded-full font-bold uppercase">POST</span>
        </div>
        <p>Kirimkan file gambar beserta teks keterangan (caption).</p>
        
        <h4 class="font-bold text-gray-800 pt-2 text-xs uppercase tracking-wider">Payload Body (JSON)</h4>
        <div class="bg-gray-900 text-gray-200 rounded-xl p-4 font-mono text-xs overflow-x-auto">
<pre>{
  "session": "test",
  "to": "081359774765",
  "type": "image",
  "url": "https://picsum.photos/600/400",  // URL gambar publik
  "text": "Ini caption gambar test",       // Optional
  "mimetype": "image/jpeg"                // Optional
}</pre>
        </div>
      </section>

      <!-- Section Kirim Dokumen -->
      <section id="send-file" class="space-y-3">
        <div class="flex items-center justify-between border-b pb-2">
          <h2 class="text-lg font-bold text-gray-800 font-display">3. Kirim Dokumen / File</h2>
          <span class="bg-purple-100 text-purple-700 text-xs px-2.5 py-1 rounded-full font-bold uppercase">POST</span>
        </div>
        <p>Kirimkan file PDF, ZIP, Excel, atau file umum lainnya via URL publik.</p>
        
        <h4 class="font-bold text-gray-800 pt-2 text-xs uppercase tracking-wider">Payload Body (JSON)</h4>
        <div class="bg-gray-900 text-gray-200 rounded-xl p-4 font-mono text-xs overflow-x-auto">
<pre>{
  "session": "test",
  "to": "081359774765",
  "type": "file",
  "url": "https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf",
  "filename": "laporan.pdf"     // Optional nama file saat di-download
}</pre>
        </div>
      </section>

      <!-- Section Kirim Lokasi -->
      <section id="send-location" class="space-y-3">
        <div class="flex items-center justify-between border-b pb-2">
          <h2 class="text-lg font-bold text-gray-800 font-display">4. Kirim Lokasi (Map)</h2>
          <span class="bg-purple-100 text-purple-700 text-xs px-2.5 py-1 rounded-full font-bold uppercase">POST</span>
        </div>
        <p>Kirim koordinat lokasi peta ke penerima WhatsApp.</p>
        
        <h4 class="font-bold text-gray-800 pt-2 text-xs uppercase tracking-wider">Payload Body (JSON)</h4>
        <div class="bg-gray-900 text-gray-200 rounded-xl p-4 font-mono text-xs overflow-x-auto">
<pre>{
  "session": "test",
  "to": "081359774765",
  "type": "location",
  "latitude": -6.175392,
  "longitude": 106.827153,
  "location_title": "Monas Jakarta"  // Optional
}</pre>
        </div>
      </section>

      <!-- Section Kirim Kontak -->
      <section id="send-contact" class="space-y-3">
        <div class="flex items-center justify-between border-b pb-2">
          <h2 class="text-lg font-bold text-gray-800 font-display">5. Kirim Kartu Kontak</h2>
          <span class="bg-purple-100 text-purple-700 text-xs px-2.5 py-1 rounded-full font-bold uppercase">POST</span>
        </div>
        <p>Kirimkan vCard kontak WhatsApp ke obrolan target.</p>
        
        <h4 class="font-bold text-gray-800 pt-2 text-xs uppercase tracking-wider">Payload Body (JSON)</h4>
        <div class="bg-gray-900 text-gray-200 rounded-xl p-4 font-mono text-xs overflow-x-auto">
<pre>{
  "session": "test",
  "to": "081359774765",
  "type": "contact",
  "contacts": [
    {
      "name": "Customer Support",
      "phone": "6281346398695"
    }
  ]
}</pre>
        </div>
      </section>

      <!-- Section List Sessions -->
      <section id="list-sessions" class="space-y-3">
        <div class="flex items-center justify-between border-b pb-2">
          <h2 class="text-lg font-bold text-gray-800 font-display">Melihat Daftar Sesi (Sessions)</h2>
          <span class="bg-green-100 text-green-700 text-xs px-2.5 py-1 rounded-full font-bold uppercase">GET</span>
        </div>
        <p>Memantau status dan daftar sesi koneksi WhatsApp Anda secara terprogram.</p>
        <p><code class="font-mono text-xs bg-gray-100 px-2 py-1 rounded font-bold text-gray-800"><?= url('/v1/sessions') ?></code></p>

        <h4 class="font-bold text-gray-800 pt-2 text-xs uppercase tracking-wider">Contoh Response (JSON)</h4>
        <div class="bg-gray-900 text-gray-200 rounded-xl p-4 font-mono text-xs overflow-x-auto">
<pre>[
  {
    "id": 1,
    "name": "test",
    "status": "WORKING",
    "phone_number": "+6281346398695",
    "created_at": "2026-09-10 01:07:40"
  }
]</pre>
        </div>
      </section>
    </div>
  </div>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
