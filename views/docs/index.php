<?php $title = 'Dokumentasi API'; require __DIR__ . '/../layouts/header.php'; require __DIR__ . '/../layouts/nav.php'; ?>

<style>
  .code-tab {
    color: #94a3b8 !important;
    border-bottom: 2px solid transparent;
  }
  .code-tab.active {
    border-bottom: 2px solid #a855f7 !important;
    color: #ffffff !important;
    font-weight: 700;
  }
  .code-tab:hover {
    color: #f1f5f9 !important;
  }
  /* Syntax Highlighting Colors untuk Dark Mode Code Block */
  pre, .code-box {
    background-color: #0f172a !important; /* Deep Slate Dark */
    color: #f8fafc !important;            /* Bright Crisp White */
  }
  .json-key { color: #c084fc !important; font-weight: 700; }     /* Bright Purple */
  .json-string { color: #4ade80 !important; font-weight: 500; }  /* Bright Green */
  .json-number { color: #facc15 !important; font-weight: 700; }  /* Bright Yellow */
  .json-boolean { color: #f87171 !important; font-weight: 700; } /* Bright Red */
  .json-comment { color: #94a3b8 !important; font-style: italic; } /* Slate 400 */
</style>

<div>
  <!-- Header Title -->
  <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4 pb-2">
    <div>
      <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200 mb-2">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
        Wapify REST API v1.0
      </div>
      <h1 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight font-display">Dokumentasi API &amp; Integrasi</h1>
      <p class="text-sm text-gray-500 mt-1">Panduan lengkap integrasi pengiriman pesan WhatsApp otomatis dari aplikasi, web, CRM, atau bot Anda.</p>
    </div>
    
    <div class="flex items-center gap-3 shrink-0">
      <button onclick="downloadPostmanCollection()" type="button" class="inline-flex items-center gap-2 text-xs font-bold px-4 py-2.5 rounded-xl shadow-md transition-all hover:opacity-95" style="background: linear-gradient(135deg, #EA580C 0%, #D97706 100%); color: #FFFFFF !important;">
        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
        <span style="color: #FFFFFF !important;">Download Postman Collection (.json)</span>
      </button>
    </div>
  </div>

  <!-- Quick Start 3 Steps Guide (Untuk Pemula) -->
  <div class="mb-12 bg-white rounded-3xl border border-purple-100 p-6 sm:p-8 shadow-sm relative overflow-hidden">
    <!-- Circle Glow Background di Belakang Card (z-0) -->
    <div class="absolute -top-12 -right-12 w-56 h-56 rounded-full pointer-events-none z-0 opacity-70" style="background: radial-gradient(circle, rgba(216,180,254,0.8) 0%, rgba(243,232,255,0) 70%);"></div>

    <div class="relative z-10">
      <h2 class="text-lg font-black text-gray-900 font-display mb-1 flex items-center gap-2">
        <span class="text-purple-600 text-xl">🚀</span> Quick Start 3-Langkah Mudah (Untuk Pemula)
      </h2>
      <p class="text-xs text-gray-500 mb-6">Mulai mengirim pesan WhatsApp otomatis pertama Anda dalam kurang dari 2 menit.</p>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 relative z-10">
        <!-- Step 1 -->
        <div class="rounded-2xl p-5 border border-gray-100 space-y-2.5 relative z-10 shadow-xs" style="background-color: #F9FAFB;">
          <div class="flex items-center gap-3">
            <div class="w-7 h-7 rounded-xl bg-purple-600 text-white font-black flex items-center justify-center text-xs font-display shadow-xs shrink-0">1</div>
            <h3 class="font-bold text-gray-900 text-sm">Buat Sesi WhatsApp</h3>
          </div>
          <p class="text-xs text-gray-500 leading-relaxed">Buka menu <a href="<?= url('/sessions') ?>" class="text-purple-600 font-bold hover:underline">Sessions</a>, tambah sesi baru lalu scan QR Code dengan aplikasi WhatsApp Anda.</p>
        </div>

        <!-- Step 2 -->
        <div class="rounded-2xl p-5 border border-gray-100 space-y-2.5 relative z-10 shadow-xs" style="background-color: #F9FAFB;">
          <div class="flex items-center gap-3">
            <div class="w-7 h-7 rounded-xl bg-purple-600 text-white font-black flex items-center justify-center text-xs font-display shadow-xs shrink-0">2</div>
            <h3 class="font-bold text-gray-900 text-sm">Ambil API Key Anda</h3>
          </div>
          <p class="text-xs text-gray-500 leading-relaxed">Buka menu <a href="<?= url('/api-keys') ?>" class="text-purple-600 font-bold hover:underline">API Keys</a>, buat kunci rahasia baru (dimulai dengan prefix <code class="bg-purple-100 text-purple-700 px-1 py-0.5 rounded text-[10px]">wsk_</code>).</p>
        </div>

        <!-- Step 3 -->
        <div class="rounded-2xl p-5 border border-gray-100 space-y-2.5 relative z-10 shadow-xs" style="background-color: #F9FAFB;">
          <div class="flex items-center gap-3">
            <div class="w-7 h-7 rounded-xl bg-purple-600 text-white font-black flex items-center justify-center text-xs font-display shadow-xs shrink-0">3</div>
            <h3 class="font-bold text-gray-900 text-sm">Kirim HTTP Request</h3>
          </div>
          <p class="text-xs text-gray-500 leading-relaxed">Tembak endpoint POST <code class="bg-gray-200 text-gray-800 px-1 py-0.5 rounded text-[10px]">/v1/messages/send</code> menyertakan API Key di header Authorization.</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Layout Documentation Grid -->
  <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
    
    <!-- Sidebar Menu Navigasi Docs dengan Vector Icons Modern -->
    <div class="lg:col-span-1 space-y-4 sticky top-24 self-start bg-white p-5 rounded-2xl border border-gray-100 shadow-xs">
      <div>
        <h3 class="text-[10px] font-black uppercase tracking-wider text-gray-400 mb-2.5">Konsep Utama</h3>
        <div class="space-y-1 text-xs">
          <a href="#auth" class="flex items-center gap-2.5 font-semibold text-gray-600 hover:text-purple-600 py-1.5 transition-colors">
            <svg class="w-4 h-4 text-purple-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            <span>Otentikasi API</span>
          </a>
          <a href="#idempotency" class="flex items-center gap-2.5 font-semibold text-gray-600 hover:text-purple-600 py-1.5 transition-colors">
            <svg class="w-4 h-4 text-blue-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            <span>Idempotency Key</span>
          </a>
          <a href="#error-codes" class="flex items-center gap-2.5 font-semibold text-gray-600 hover:text-purple-600 py-1.5 transition-colors">
            <svg class="w-4 h-4 text-amber-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            <span>Kode Error &amp; Status</span>
          </a>
        </div>
      </div>

      <div>
        <h3 class="text-[10px] font-black uppercase tracking-wider text-gray-400 mb-2.5">Endpoint Kirim Pesan</h3>
        <div class="space-y-1 text-xs">
          <a href="#send-text" class="flex items-center gap-2.5 font-semibold text-gray-600 hover:text-purple-600 py-1.5 transition-colors">
            <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
            <span>Pesan Teks</span>
          </a>
          <a href="#send-image" class="flex items-center gap-2.5 font-semibold text-gray-600 hover:text-purple-600 py-1.5 transition-colors">
            <svg class="w-4 h-4 text-sky-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <span>Gambar &amp; Media</span>
          </a>
          <a href="#send-file" class="flex items-center gap-2.5 font-semibold text-gray-600 hover:text-purple-600 py-1.5 transition-colors">
            <svg class="w-4 h-4 text-indigo-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
            <span>Dokumen / PDF</span>
          </a>
          <a href="#send-location" class="flex items-center gap-2.5 font-semibold text-gray-600 hover:text-purple-600 py-1.5 transition-colors">
            <svg class="w-4 h-4 text-rose-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            <span>Lokasi (Map)</span>
          </a>
          <a href="#send-contact" class="flex items-center gap-2.5 font-semibold text-gray-600 hover:text-purple-600 py-1.5 transition-colors">
            <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            <span>Kartu Kontak</span>
          </a>
        </div>
      </div>

      <div>
        <h3 class="text-[10px] font-black uppercase tracking-wider text-gray-400 mb-2.5">Manajemen &amp; Webhook</h3>
        <div class="space-y-1 text-xs">
          <a href="#list-sessions" class="flex items-center gap-2.5 font-semibold text-gray-600 hover:text-purple-600 py-1.5 transition-colors">
            <svg class="w-4 h-4 text-teal-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
            <span>Daftar Sesi WA</span>
          </a>
          <a href="#webhook-events" class="flex items-center gap-2.5 font-semibold text-gray-600 hover:text-purple-600 py-1.5 transition-colors">
            <svg class="w-4 h-4 text-purple-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
            <span>Webhook Event Spec</span>
          </a>
        </div>
      </div>
    </div>

    <!-- Main Content Area -->
    <div class="lg:col-span-3 space-y-12 text-sm text-gray-600 leading-relaxed">
      
      <!-- Section 1: Authentication -->
      <section id="auth" class="bg-white rounded-2xl border border-gray-100 shadow-xs p-6 space-y-4">
        <div class="border-b border-gray-100 pb-3">
          <h2 class="text-xl font-extrabold text-gray-900 font-display">Otentikasi HTTP (Authorization)</h2>
          <p class="text-xs text-gray-500 mt-0.5">Seluruh request API mewajibkan pengiriman API Key rahasia melalui HTTP Header.</p>
        </div>

        <p>Gunakan header <code>Authorization</code> dengan skema <code>Bearer</code> disusul API Key Anda:</p>
        
        <div class="relative rounded-2xl p-5 font-mono text-xs overflow-x-auto border border-slate-800 shadow-md" style="background-color: #0f172a; color: #f8fafc;">
          <button onclick="copyCode(this)" class="absolute top-3 right-3 bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white px-3 py-1.5 rounded-lg text-xs font-bold transition-all border border-slate-700">Copy</button>
          <pre><span class="json-key">Authorization:</span> Bearer wsk_your_secret_api_key_here</pre>
        </div>
      </section>

      <!-- Section 2: Idempotency Key -->
      <section id="idempotency" class="bg-white rounded-2xl border border-gray-100 shadow-xs p-6 space-y-4">
        <div class="border-b border-gray-100 pb-3">
          <h2 class="text-xl font-extrabold text-gray-900 font-display">Idempotency Key (Mencegah Pesan Ganda)</h2>
          <p class="text-xs text-gray-500 mt-0.5">Mekanisme aman untuk mencegah pengiriman pesan berulang saat koneksi terputus.</p>
        </div>

        <p>Anda dapat menyertakan header <code>X-Idempotency-Key: UUID_UNIK</code> atau memasukkan field <code>"idempotency_key": "ORDER-12345"</code> ke dalam payload JSON.</p>
      </section>

      <!-- Section 3: HTTP Error Status Codes -->
      <section id="error-codes" class="bg-white rounded-2xl border border-gray-100 shadow-xs p-6 space-y-4">
        <div class="border-b border-gray-100 pb-3">
          <h2 class="text-xl font-extrabold text-gray-900 font-display">Kode Status HTTP &amp; Penanganan Error</h2>
          <p class="text-xs text-gray-500 mt-0.5">API Wapify menggunakan standar kode respon HTTP konvensional.</p>
        </div>

        <div class="overflow-x-auto">
          <table class="w-full text-xs text-left border-collapse">
            <thead>
              <tr class="bg-gray-50 text-gray-500 border-b border-gray-100">
                <th class="p-3 font-bold">Status</th>
                <th class="p-3 font-bold">Keterangan</th>
                <th class="p-3 font-bold">Solusi / Penyebab</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
              <tr>
                <td class="p-3 font-bold text-emerald-600">200 OK</td>
                <td class="p-3">Permintaan berhasil diproses</td>
                <td class="p-3 text-gray-500">Pesan berhasil dikirim / dimasukkan ke antrean.</td>
              </tr>
              <tr>
                <td class="p-3 font-bold text-amber-600">400 Bad Request</td>
                <td class="p-3">Format payload JSON tidak valid</td>
                <td class="p-3 text-gray-500">Periksa nomor tujuan, tipe pesan, atau field yang wajib diisi.</td>
              </tr>
              <tr>
                <td class="p-3 font-bold text-red-600">401 Unauthorized</td>
                <td class="p-3">API Key salah / tidak disertakan</td>
                <td class="p-3 text-gray-500">Periksa header Authorization Bearer token Anda.</td>
              </tr>
              <tr>
                <td class="p-3 font-bold text-red-600">429 Too Many Requests</td>
                <td class="p-3">Melebihi Rate Limit paket / kuota habis</td>
                <td class="p-3 text-gray-500">Upgrade paket langganan di menu Billing.</td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>

      <!-- Section 4: Kirim Pesan Teks (Interactive Multi-Language Tabs) -->
      <section id="send-text" class="bg-white rounded-2xl border border-gray-100 shadow-xs p-6 space-y-4">
        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
          <div>
            <h2 class="text-xl font-extrabold text-gray-900 font-display">1. Kirim Pesan Teks</h2>
            <p class="text-xs font-mono text-purple-700 font-bold mt-1">POST <?= url('/v1/messages/send') ?></p>
          </div>
          <span class="bg-purple-100 text-purple-700 text-xs px-3 py-1 rounded-full font-black uppercase">POST</span>
        </div>

        <p>Endpoint utama untuk mengirim pesan teks percakapan biasa ke nomor WhatsApp.</p>

        <!-- Code Snippet Switcher -->
        <div class="code-block-wrapper rounded-2xl overflow-hidden border border-slate-800 shadow-md" style="background-color: #0f172a;">
          <div class="flex items-center justify-between px-4 py-2.5 border-b border-slate-800 text-xs" style="background-color: #1e293b;">
            <div class="flex gap-2">
              <button type="button" onclick="switchTab(this, 'text-curl')" class="code-tab active text-slate-400 hover:text-white px-3 py-1.5 text-xs transition-colors rounded-lg cursor-pointer">cURL</button>
              <button type="button" onclick="switchTab(this, 'text-php')" class="code-tab text-slate-400 hover:text-white px-3 py-1.5 text-xs transition-colors rounded-lg cursor-pointer">PHP (cURL)</button>
              <button type="button" onclick="switchTab(this, 'text-node')" class="code-tab text-slate-400 hover:text-white px-3 py-1.5 text-xs transition-colors rounded-lg cursor-pointer">Node.js (Axios)</button>
              <button type="button" onclick="switchTab(this, 'text-python')" class="code-tab text-slate-400 hover:text-white px-3 py-1.5 text-xs transition-colors rounded-lg cursor-pointer">Python (Requests)</button>
            </div>
            <button type="button" onclick="copyActiveCode(this)" class="bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white px-3 py-1.5 rounded-lg text-xs font-bold transition-all border border-slate-700 cursor-pointer">Copy</button>
          </div>

          <div class="p-5 font-mono text-xs text-slate-100 overflow-x-auto">
            <!-- cURL -->
            <div id="text-curl" class="tab-content">
<pre>curl -X POST <?= url('/v1/messages/send') ?> \
  -H <span class="json-string">"Authorization: Bearer YOUR_API_KEY"</span> \
  -H <span class="json-string">"Content-Type: application/json"</span> \
  -d <span class="json-string">'{
    <span class="json-key">"session"</span>: <span class="json-string">"test"</span>,
    <span class="json-key">"to"</span>: <span class="json-string">"081359774765"</span>,
    <span class="json-key">"type"</span>: <span class="json-string">"text"</span>,
    <span class="json-key">"text"</span>: <span class="json-string">"Halo! Ini pesan pengujian dari API Wapify."</span>
  }'</span></pre>
            </div>

            <!-- PHP -->
            <div id="text-php" class="tab-content hidden">
<pre><span class="json-key">&lt;?php</span>
<span class="json-key">$apiKey</span> = <span class="json-string">'YOUR_API_KEY'</span>;
<span class="json-key">$url</span> = <span class="json-string">'<?= url('/v1/messages/send') ?>'</span>;

<span class="json-key">$data</span> = [
    <span class="json-string">'session'</span> =&gt; <span class="json-string">'test'</span>,
    <span class="json-string">'to'</span>      =&gt; <span class="json-string">'081359774765'</span>,
    <span class="json-string">'type'</span>    =&gt; <span class="json-string">'text'</span>,
    <span class="json-string">'text'</span>    =&gt; <span class="json-string">'Halo! Ini pesan dari PHP script Wapify.'</span>
];

<span class="json-key">$ch</span> = curl_init(<span class="json-key">$url</span>);
curl_setopt(<span class="json-key">$ch</span>, CURLOPT_HTTPHEADER, [
    <span class="json-string">'Authorization: Bearer '</span> . <span class="json-key">$apiKey</span>,
    <span class="json-string">'Content-Type: application/json'</span>
]);
curl_setopt(<span class="json-key">$ch</span>, CURLOPT_POST, <span class="json-boolean">true</span>);
curl_setopt(<span class="json-key">$ch</span>, CURLOPT_POSTFIELDS, json_encode(<span class="json-key">$data</span>));
curl_setopt(<span class="json-key">$ch</span>, CURLOPT_RETURNTRANSFER, <span class="json-boolean">true</span>);

<span class="json-key">$response</span> = curl_exec(<span class="json-key">$ch</span>);
curl_close(<span class="json-key">$ch</span>);

echo <span class="json-key">$response</span>;</pre>
            </div>

            <!-- Node.js -->
            <div id="text-node" class="tab-content hidden">
<pre><span class="json-key">const</span> axios = require(<span class="json-string">'axios'</span>);

<span class="json-key">const</span> sendWhatsApp = <span class="json-key">async</span> () =&gt; {
  <span class="json-key">try</span> {
    <span class="json-key">const</span> res = <span class="json-key">await</span> axios.post(<span class="json-string">'<?= url('/v1/messages/send') ?>'</span>, {
      <span class="json-key">session</span>: <span class="json-string">'test'</span>,
      <span class="json-key">to</span>: <span class="json-string">'081359774765'</span>,
      <span class="json-key">type</span>: <span class="json-string">'text'</span>,
      <span class="json-key">text</span>: <span class="json-string">'Halo dari Node.js Axios!'</span>
    }, {
      <span class="json-key">headers</span>: {
        <span class="json-string">'Authorization'</span>: <span class="json-string">'Bearer YOUR_API_KEY'</span>,
        <span class="json-string">'Content-Type'</span>: <span class="json-string">'application/json'</span>
      }
    });

    console.log(res.data);
  } <span class="json-key">catch</span> (err) {
    console.error(err.response?.data || err.message);
  }
};

sendWhatsApp();</pre>
            </div>

            <!-- Python -->
            <div id="text-python" class="tab-content hidden">
<pre><span class="json-key">import</span> requests

url = <span class="json-string">"<?= url('/v1/messages/send') ?>"</span>
headers = {
    <span class="json-string">"Authorization"</span>: <span class="json-string">"Bearer YOUR_API_KEY"</span>,
    <span class="json-string">"Content-Type"</span>: <span class="json-string">"application/json"</span>
}
payload = {
    <span class="json-key">"session"</span>: <span class="json-string">"test"</span>,
    <span class="json-key">"to"</span>: <span class="json-string">"081359774765"</span>,
    <span class="json-key">"type"</span>: <span class="json-string">"text"</span>,
    <span class="json-key">"text"</span>: <span class="json-string">"Halo dari Python Requests!"</span>
}

response = requests.post(url, json=payload, headers=headers)
print(response.json())</pre>
            </div>

          </div>
        </div>
      </section>

      <!-- Section 5: Kirim Gambar & Media -->
      <section id="send-image" class="bg-white rounded-2xl border border-gray-100 shadow-xs p-6 space-y-4">
        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
          <div>
            <h2 class="text-xl font-extrabold text-gray-900 font-display">2. Kirim Gambar / Media</h2>
            <p class="text-xs font-mono text-purple-700 font-bold mt-1">POST <?= url('/v1/messages/send') ?></p>
          </div>
          <span class="bg-purple-100 text-purple-700 text-xs px-3 py-1 rounded-full font-black uppercase">POST</span>
        </div>

        <p>Kirim file gambar (JPG, PNG, WEBP) melalui URL publik disertai teks caption.</p>

        <div class="relative rounded-2xl p-5 font-mono text-xs overflow-x-auto border border-slate-800 shadow-md" style="background-color: #0f172a; color: #f8fafc;">
          <button onclick="copyCode(this)" class="absolute top-3 right-3 bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white px-3 py-1.5 rounded-lg text-xs font-bold transition-all border border-slate-700">Copy</button>
<pre>{
  <span class="json-key">"session"</span>: <span class="json-string">"test"</span>,
  <span class="json-key">"to"</span>: <span class="json-string">"081359774765"</span>,
  <span class="json-key">"type"</span>: <span class="json-string">"image"</span>,
  <span class="json-key">"url"</span>: <span class="json-string">"https://picsum.photos/600/400"</span>,
  <span class="json-key">"text"</span>: <span class="json-string">"Keterangan / Caption Gambar"</span>
}</pre>
        </div>
      </section>

      <!-- Section 6: Kirim Dokumen -->
      <section id="send-file" class="bg-white rounded-2xl border border-gray-100 shadow-xs p-6 space-y-4">
        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
          <div>
            <h2 class="text-xl font-extrabold text-gray-900 font-display">3. Kirim Dokumen &amp; File PDF</h2>
            <p class="text-xs font-mono text-purple-700 font-bold mt-1">POST <?= url('/v1/messages/send') ?></p>
          </div>
          <span class="bg-purple-100 text-purple-700 text-xs px-3 py-1 rounded-full font-black uppercase">POST</span>
        </div>

        <p>Kirimkan file PDF, ZIP, XLSX, atau dokumen umum lainnya via URL publik.</p>

        <div class="relative rounded-2xl p-5 font-mono text-xs overflow-x-auto border border-slate-800 shadow-md" style="background-color: #0f172a; color: #f8fafc;">
          <button onclick="copyCode(this)" class="absolute top-3 right-3 bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white px-3 py-1.5 rounded-lg text-xs font-bold transition-all border border-slate-700">Copy</button>
<pre>{
  <span class="json-key">"session"</span>: <span class="json-string">"test"</span>,
  <span class="json-key">"to"</span>: <span class="json-string">"081359774765"</span>,
  <span class="json-key">"type"</span>: <span class="json-string">"file"</span>,
  <span class="json-key">"url"</span>: <span class="json-string">"https://example.com/invoice-123.pdf"</span>,
  <span class="json-key">"filename"</span>: <span class="json-string">"Invoice-123.pdf"</span>
}</pre>
        </div>
      </section>

      <!-- Section 5: Kirim Lokasi (Map) -->
      <section id="send-location" class="bg-white rounded-2xl border border-gray-100 shadow-xs p-6 space-y-4">
        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
          <div>
            <h2 class="text-xl font-extrabold text-gray-900 font-display">4. Kirim Lokasi (Map)</h2>
            <p class="text-xs font-mono text-purple-700 font-bold mt-1">POST <?= url('/v1/messages/send') ?></p>
          </div>
          <span class="bg-purple-100 text-purple-700 text-xs px-3 py-1 rounded-full font-black uppercase">POST</span>
        </div>

        <p>Kirim koordinat lokasi peta (latitude &amp; longitude) ke obrolan WhatsApp penerima.</p>

        <div class="relative rounded-2xl p-5 font-mono text-xs overflow-x-auto border border-slate-800 shadow-md" style="background-color: #0f172a; color: #f8fafc;">
          <button onclick="copyCode(this)" class="absolute top-3 right-3 bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white px-3 py-1.5 rounded-lg text-xs font-bold transition-all border border-slate-700">Copy</button>
<pre>{
  <span class="json-key">"session"</span>: <span class="json-string">"test"</span>,
  <span class="json-key">"to"</span>: <span class="json-string">"081359774765"</span>,
  <span class="json-key">"type"</span>: <span class="json-string">"location"</span>,
  <span class="json-key">"latitude"</span>: <span class="json-number">-6.175392</span>,
  <span class="json-key">"longitude"</span>: <span class="json-number">106.827153</span>,
  <span class="json-key">"location_title"</span>: <span class="json-string">"Monas Jakarta"</span>
}</pre>
        </div>
      </section>

      <!-- Section 6: Kirim Kartu Kontak -->
      <section id="send-contact" class="bg-white rounded-2xl border border-gray-100 shadow-xs p-6 space-y-4">
        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
          <div>
            <h2 class="text-xl font-extrabold text-gray-900 font-display">5. Kirim Kartu Kontak</h2>
            <p class="text-xs font-mono text-purple-700 font-bold mt-1">POST <?= url('/v1/messages/send') ?></p>
          </div>
          <span class="bg-purple-100 text-purple-700 text-xs px-3 py-1 rounded-full font-black uppercase">POST</span>
        </div>

        <p>Kirimkan vCard kontak nomor telepon lain ke obrolan WhatsApp target.</p>

        <div class="relative rounded-2xl p-5 font-mono text-xs overflow-x-auto border border-slate-800 shadow-md" style="background-color: #0f172a; color: #f8fafc;">
          <button onclick="copyCode(this)" class="absolute top-3 right-3 bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white px-3 py-1.5 rounded-lg text-xs font-bold transition-all border border-slate-700">Copy</button>
<pre>{
  <span class="json-key">"session"</span>: <span class="json-string">"test"</span>,
  <span class="json-key">"to"</span>: <span class="json-string">"081359774765"</span>,
  <span class="json-key">"type"</span>: <span class="json-string">"contact"</span>,
  <span class="json-key">"contacts"</span>: [
    {
      <span class="json-key">"name"</span>: <span class="json-string">"Customer Support Wapify"</span>,
      <span class="json-key">"phone"</span>: <span class="json-string">"6281346398695"</span>
    }
  ]
}</pre>
        </div>
      </section>

      <!-- Section 7: Melihat Daftar Sesi WA -->
      <section id="list-sessions" class="bg-white rounded-2xl border border-gray-100 shadow-xs p-6 space-y-4">
        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
          <div>
            <h2 class="text-xl font-extrabold text-gray-900 font-display">6. Melihat Daftar Sesi WhatsApp (Sessions)</h2>
            <p class="text-xs font-mono text-emerald-700 font-bold mt-1">GET <?= url('/v1/sessions') ?></p>
          </div>
          <span class="bg-emerald-100 text-emerald-700 text-xs px-3 py-1 rounded-full font-black uppercase">GET</span>
        </div>

        <p>Memantau daftar sesi koneksi WhatsApp Anda beserta status ketersediaannya secara terprogram.</p>

        <div class="relative rounded-2xl p-5 font-mono text-xs overflow-x-auto border border-slate-800 shadow-md" style="background-color: #0f172a; color: #f8fafc;">
          <button onclick="copyCode(this)" class="absolute top-3 right-3 bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white px-3 py-1.5 rounded-lg text-xs font-bold transition-all border border-slate-700">Copy</button>
<pre>[
  {
    <span class="json-key">"id"</span>: <span class="json-number">1</span>,
    <span class="json-key">"name"</span>: <span class="json-string">"test"</span>,
    <span class="json-key">"status"</span>: <span class="json-string">"WORKING"</span>,
    <span class="json-key">"phone_number"</span>: <span class="json-string">"+6281346398695"</span>,
    <span class="json-key">"created_at"</span>: <span class="json-string">"2026-09-10 01:07:40"</span>
  }
]</pre>
        </div>
      </section>

      <!-- Section 8: Incoming Webhook Event Spec -->
      <section id="webhook-events" class="bg-white rounded-2xl border border-gray-100 shadow-xs p-6 space-y-4">
        <div class="border-b border-gray-100 pb-3 flex items-center gap-2.5">
          <div class="w-8 h-8 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0">
            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
          </div>
          <div>
            <h2 class="text-xl font-extrabold text-gray-900 font-display">7. Webhook Event Spec (Pesan Masuk &amp; Status)</h2>
            <p class="text-xs text-gray-500 mt-0.5">Struktur JSON payload yang dikirimkan server Wapify ke URL Webhook Anda saat ada event masuk.</p>
          </div>
        </div>

        <p>Setiap kali ada pesan baru dari pelanggan atau status pengiriman pesan berubah (<code class="bg-gray-100 text-purple-700 px-1 py-0.5 rounded text-xs font-bold">sent</code>, <code class="bg-gray-100 text-purple-700 px-1 py-0.5 rounded text-xs font-bold">delivered</code>, <code class="bg-gray-100 text-purple-700 px-1 py-0.5 rounded text-xs font-bold">read</code>), server kami akan mengirim HTTP POST ke URL Webhook Anda:</p>

        <div class="relative rounded-2xl p-5 font-mono text-xs overflow-x-auto border border-slate-800 shadow-md" style="background-color: #0f172a; color: #f8fafc;">
          <button onclick="copyCode(this)" class="absolute top-3 right-3 bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white px-3 py-1.5 rounded-lg text-xs font-bold transition-all border border-slate-700">Copy</button>
<pre>{
  <span class="json-key">"event"</span>: <span class="json-string">"message.incoming"</span>,
  <span class="json-key">"session"</span>: <span class="json-string">"test"</span>,
  <span class="json-key">"data"</span>: {
    <span class="json-key">"id"</span>: <span class="json-string">"wamid.HBgL..."</span>,
    <span class="json-key">"from"</span>: <span class="json-string">"6281359774765@c.us"</span>,
    <span class="json-key">"body"</span>: <span class="json-string">"Halo CS Wapify, saya mau tanya paket"</span>,
    <span class="json-key">"timestamp"</span>: <span class="json-number">1725960000</span>
  }
}</pre>
        </div>
      </section>

    </div>
  </div>
</div>

<script>
function switchTab(btn, tabId) {
    var container = btn.closest('.code-block-wrapper');
    var tabs = container.querySelectorAll('.code-tab');
    var contents = container.querySelectorAll('.tab-content');

    tabs.forEach(t => t.classList.remove('active'));
    contents.forEach(c => c.classList.add('hidden'));

    btn.classList.add('active');
    document.getElementById(tabId).classList.remove('hidden');
}

function copyCode(btn) {
    var pre = btn.nextElementSibling;
    var text = pre.innerText;
    navigator.clipboard.writeText(text).then(() => {
        var originalText = btn.innerText;
        btn.innerText = 'Copied!';
        btn.classList.add('bg-green-600', 'text-white');
        setTimeout(() => {
            btn.innerText = originalText;
            btn.classList.remove('bg-green-600', 'text-white');
        }, 2000);
    });
}

function copyActiveCode(btn) {
    var container = btn.closest('.code-block-wrapper');
    var activeContent = container.querySelector('.tab-content:not(.hidden) pre');
    if (!activeContent) return;

    navigator.clipboard.writeText(activeContent.innerText).then(() => {
        var originalText = btn.innerText;
        btn.innerText = 'Copied!';
        btn.classList.add('bg-green-600', 'text-white');
        setTimeout(() => {
            btn.innerText = originalText;
            btn.classList.remove('bg-green-600', 'text-white');
        }, 2000);
    });
}

function downloadPostmanCollection() {
    var collectionData = {
        "info": {
            "name": "Wapify WhatsApp REST API v1.0",
            "schema": "https://schema.getpostman.com/json/collection/v2.1.0/collection.json"
        },
        "item": [
            {
                "name": "1. Kirim Pesan Teks",
                "request": {
                    "method": "POST",
                    "header": [
                        { "key": "Authorization", "value": "Bearer {{API_KEY}}", "type": "text" },
                        { "key": "Content-Type", "value": "application/json", "type": "text" }
                    ],
                    "body": {
                        "mode": "raw",
                        "raw": JSON.stringify({
                            "session": "test",
                            "to": "081359774765",
                            "type": "text",
                            "text": "Halo! Pesan otomatis via Postman Collection."
                        }, null, 2)
                    },
                    "url": { "raw": "<?= url('/v1/messages/send') ?>" }
                }
            },
            {
                "name": "2. Kirim Gambar & Media",
                "request": {
                    "method": "POST",
                    "header": [
                        { "key": "Authorization", "value": "Bearer {{API_KEY}}", "type": "text" },
                        { "key": "Content-Type", "value": "application/json", "type": "text" }
                    ],
                    "body": {
                        "mode": "raw",
                        "raw": JSON.stringify({
                            "session": "test",
                            "to": "081359774765",
                            "type": "image",
                            "url": "https://picsum.photos/600/400",
                            "text": "Gambar via Postman"
                        }, null, 2)
                    },
                    "url": { "raw": "<?= url('/v1/messages/send') ?>" }
                }
            },
            {
                "name": "3. Daftar Sesi WhatsApp",
                "request": {
                    "method": "GET",
                    "header": [
                        { "key": "Authorization", "value": "Bearer {{API_KEY}}", "type": "text" }
                    ],
                    "url": { "raw": "<?= url('/v1/sessions') ?>" }
                }
            }
        ]
    };

    var dataStr = "data:text/json;charset=utf-8," + encodeURIComponent(JSON.stringify(collectionData, null, 2));
    var dlAnchorElem = document.createElement('a');
    dlAnchorElem.setAttribute("href", dataStr);
    dlAnchorElem.setAttribute("download", "Wapify_API_Postman_Collection.json");
    document.body.appendChild(dlAnchorElem);
    dlAnchorElem.click();
    dlAnchorElem.remove();
}
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
