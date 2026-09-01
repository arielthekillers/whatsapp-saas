<?php $title = 'Dokumentasi API'; require __DIR__ . '/../layouts/header.php'; require __DIR__ . '/../layouts/nav.php'; ?>
<div class="max-w-4xl mx-auto px-4 py-8">
  <div class="mb-8">
    <h1 class="text-2xl font-bold tracking-tight text-gray-900 font-display">Dokumentasi API Developer</h1>
    <p class="text-gray-500 text-sm mt-1">Integrasikan pengiriman pesan WhatsApp otomatis Wapify ke dalam sistem internal Anda.</p>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
    <!-- Sidebar Menu Dokumentasi -->
    <div class="lg:col-span-1 space-y-2">
      <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-2">Panduan</h3>
      <a href="#auth" class="block text-sm text-gray-600 hover:text-purple-600 font-medium py-1">Otentikasi</a>
      <a href="#idempotency" class="block text-sm text-gray-600 hover:text-purple-600 font-medium py-1">Idempotency Key</a>
      <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 mt-4 mb-2">Endpoints</h3>
      <a href="#send-text" class="block text-sm text-gray-600 hover:text-purple-600 font-medium py-1">Kirim Pesan Teks</a>
      <a href="#list-sessions" class="block text-sm text-gray-600 hover:text-purple-600 font-medium py-1">List WhatsApp Sessions</a>
    </div>

    <!-- Konten Dokumentasi -->
    <div class="lg:col-span-3 space-y-12 text-sm text-gray-600 leading-relaxed">
      <!-- Section Otentikasi -->
      <section id="auth" class="space-y-3">
        <h2 class="text-lg font-bold text-gray-800 font-display border-b pb-2">Otentikasi API</h2>
        <p>Seluruh permintaan HTTP ke endpoint API Wapify wajib menyertakan kunci otentikasi berupa <strong>Bearer Token</strong> di dalam header permintaan.</p>
        <p>Anda dapat menggenerasi Kunci API baru di menu <a href="<?= url('/api-keys') ?>" class="text-purple-600 hover:underline font-semibold">API Keys</a>.</p>
        <div class="bg-gray-900 text-gray-200 rounded-xl p-4 font-mono text-xs overflow-x-auto space-y-1">
          <p><span class="text-purple-400">GET</span> /v1/sessions HTTP/1.1</p>
          <p>Host: localhost</p>
          <p><span class="text-blue-400">Authorization:</span> Bearer wsk_your_secret_api_key_here</p>
        </div>
      </section>

      <!-- Section Idempotency -->
      <section id="idempotency" class="space-y-3">
        <h2 class="text-lg font-bold text-gray-800 font-display border-b pb-2">Idempotency Key</h2>
        <p>Untuk menghindari pengiriman pesan WhatsApp ganda akibat timeout koneksi internet, Anda wajib menyertakan header <code>X-Idempotency-Key</code> dengan string unik acak (mis. UUID v4) per permintaan kirim pesan.</p>
        <p>Jika server kami menerima request dengan kecocokan ID Key yang sama dalam status sukses sebelumnya, server akan mengembalikan snapshoot response lama tanpa mengirim ulang pesan WhatsApp.</p>
      </section>

      <!-- Section Kirim Pesan -->
      <section id="send-text" class="space-y-3">
        <h2 class="text-lg font-bold text-gray-800 font-display border-b pb-2">Kirim Pesan WhatsApp (Teks)</h2>
        <p>Gunakan endpoint ini untuk mengirimkan pesan WhatsApp secara langsung.</p>
        <p><span class="bg-purple-100 text-purple-700 text-xs px-2.5 py-1 rounded-full font-bold uppercase">POST</span> <code class="font-mono text-xs bg-gray-100 px-2 py-0.5 rounded font-bold text-gray-800">/v1/messages/send</code></p>
        
        <h4 class="font-bold text-gray-800 pt-2 text-xs uppercase tracking-wider">Payload Body (JSON)</h4>
        <div class="bg-gray-900 text-gray-200 rounded-xl p-4 font-mono text-xs overflow-x-auto">
<pre>{
  "session": "marketing",           // nama sesi Anda di dashboard
  "chatId": "6281359774765@c.us",    // format no telepon @c.us
  "text": "Halo, ini pesan test integrasi API Wapify!"
}</pre>
        </div>

        <div class="flex justify-between items-center pt-2">
          <h4 class="font-bold text-gray-800 text-xs uppercase tracking-wider">Contoh Implementasi cURL</h4>
          <button type="button" onclick="copyToClipboard(`curl -X POST http://localhost/whatsapp-saas/v1/messages/send \\\n  -H &quot;Authorization: Bearer wsk_your_secret_api_key&quot; \\\n  -H &quot;X-Idempotency-Key: uuid-unique-string-12345&quot; \\\n  -H &quot;Content-Type: application/json&quot; \\\n  -d '{\n    &quot;session&quot;: &quot;marketing&quot;,\n    &quot;chatId&quot;: &quot;6281359774765@c.us&quot;,\n    &quot;text&quot;: &quot;Halo, ini pesan test integrasi API Wapify!&quot;\n  }'`, this)" class="text-xs text-purple-600 hover:text-purple-700 font-semibold flex items-center">
            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
            Salin Code
          </button>
        </div>
        <div class="bg-gray-900 text-gray-200 rounded-xl p-4 font-mono text-xs overflow-x-auto relative">
<pre>curl -X POST http://localhost/whatsapp-saas/v1/messages/send \
  -H "Authorization: Bearer wsk_your_secret_api_key" \
  -H "X-Idempotency-Key: uuid-unique-string-12345" \
  -H "Content-Type: application/json" \
  -d '{
    "session": "marketing",
    "chatId": "6281359774765@c.us",
    "text": "Halo, ini pesan test integrasi API Wapify!"
  }'</pre>
        </div>
      </section>

      <!-- Section List Sessions -->
      <section id="list-sessions" class="space-y-3">
        <h2 class="text-lg font-bold text-gray-800 font-display border-b pb-2">Melihat Daftar Sesi (Sessions)</h2>
        <p>Gunakan endpoint ini untuk memantau status sesi koneksi WhatsApp Anda.</p>
        <p><span class="bg-green-100 text-green-700 text-xs px-2.5 py-1 rounded-full font-bold uppercase">GET</span> <code class="font-mono text-xs bg-gray-100 px-2 py-0.5 rounded font-bold text-gray-800">/v1/sessions</code></p>

        <h4 class="font-bold text-gray-800 pt-2 text-xs uppercase tracking-wider">Contoh Response (JSON)</h4>
        <div class="bg-gray-900 text-gray-200 rounded-xl p-4 font-mono text-xs overflow-x-auto">
<pre>[
  {
    "id": 1,
    "name": "marketing",
    "status": "WORKING",
    "phone_number": "6281359774765",
    "created_at": "2026-08-31 14:00:00"
  }
]</pre>
        </div>
      </section>
    </div>
  </div>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
