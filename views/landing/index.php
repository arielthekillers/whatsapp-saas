<?php $title = 'Wapify — Integrasi WhatsApp Tanpa Ribet';
require __DIR__ . '/../layouts/header.php'; ?>

<!-- Load Outfit & Inter fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link
  href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap"
  rel="stylesheet">

<style>
  body {
    font-family: 'Inter', sans-serif;
  }

  h1,
  h2,
  h3,
  h4,
  .font-display {
    font-family: 'Outfit', sans-serif;
  }

  .gradient-text {
    background: linear-gradient(135deg, #8B5CF6 0%, #3B82F6 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
  }

  .gradient-bg {
    background: linear-gradient(135deg, #7C3AED 0%, #2563EB 100%);
  }

  .gradient-bg-soft {
    background: linear-gradient(135deg, #F5F3FF 0%, #EFF6FF 100%);
  }

  .glass {
    background: rgba(255, 255, 255, 0.7);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
  }

  .hero-glow {
    position: absolute;
    width: 600px;
    height: 600px;
    background: radial-gradient(circle, rgba(124, 58, 237, 0.08) 0%, rgba(37, 99, 235, 0) 70%);
    top: -10%;
    right: -10%;
    z-index: -1;
    pointer-events: none;
  }

  .accordion-content {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease-out, padding 0.3s ease-out;
  }

  .accordion-open .accordion-content {
    max-height: 200px;
    padding-top: 1rem;
  }

  .accordion-open .accordion-icon {
    transform: rotate(180deg);
  }
</style>

<div class="relative overflow-hidden bg-gray-50 min-h-screen">
  <div class="hero-glow"></div>

  <!-- Header / Nav -->
  <header class="sticky top-0 z-50 glass border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <!-- Modern WA logo icon -->
        <div class="gradient-bg p-2.5 rounded-xl shadow-md text-white">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
          </svg>
        </div>
        <span class="text-2xl font-bold tracking-tight text-gray-900 font-display">Wapify</span>
      </div>

      <nav class="hidden md:flex items-center gap-8 text-sm font-semibold text-gray-600">
        <a href="#features" class="hover:text-purple-600 transition-colors">Fitur</a>
        <a href="#pricing" class="hover:text-purple-600 transition-colors">Harga</a>
        <a href="#steps" class="hover:text-purple-600 transition-colors">Alur</a>
        <a href="#faq" class="hover:text-purple-600 transition-colors">FAQ</a>
        <a href="#contact" class="hover:text-purple-600 transition-colors">Kontak</a>
      </nav>

      <div class="flex items-center gap-3">
        <a href="<?= url('/login') ?>"
          class="text-sm font-semibold text-gray-700 hover:text-purple-600 transition-colors px-4 py-2">Masuk</a>
        <a href="<?= url('/register') ?>"
          class="gradient-bg text-white text-sm font-semibold px-5 py-2.5 rounded-xl shadow-lg shadow-purple-500/20 hover:opacity-95 transition-all">Daftar
          Sekarang</a>
      </div>
    </div>
  </header>

  <!-- Hero Section -->
  <section class="max-w-7xl mx-auto px-6 pt-16 pb-20 grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
    <div class="lg:col-span-6 space-y-6 text-center lg:text-left">
      <div
        class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-purple-100/80 text-purple-700 text-xs font-semibold tracking-wide">
        <span class="flex h-2 w-2 relative">
          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-purple-400 opacity-75"></span>
          <span class="relative inline-flex rounded-full h-2 w-2 bg-purple-500"></span>
        </span>
        Integrasi WhatsApp Tanpa Ribet
      </div>
      <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-gray-900 leading-tight">
        Revolusi Komunikasi Bisnis Anda Dengan <span class="gradient-text">Wapify</span>
      </h1>
      <p class="text-lg text-gray-600 max-w-xl mx-auto lg:mx-0">
        Kirim pesan, broadcast, dan kelola chatbot dengan infrastruktur WAHA REST API yang sangat andal, cepat, dan
        terjangkau untuk skala bisnis kecil hingga enterprise.
      </p>
      <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start pt-4">
        <a href="<?= url('/register') ?>"
          class="gradient-bg text-white font-semibold px-8 py-4 rounded-xl shadow-xl shadow-purple-500/20 hover:scale-[1.02] hover:shadow-purple-500/30 transition-all text-center">
          Mulai Uji Coba Gratis
        </a>
        <a href="#pricing"
          class="bg-white border border-gray-200 text-gray-700 font-semibold px-8 py-4 rounded-xl hover:bg-gray-50 transition-all text-center">
          Lihat Paket Harga
        </a>
      </div>
    </div>

    <!-- Hero Image / Visual Mockup -->
    <div class="lg:col-span-6 relative">
      <div
        class="absolute inset-0 gradient-bg opacity-10 rounded-3xl filter blur-xl transform translate-x-4 translate-y-4">
      </div>
      <div class="relative bg-white border border-gray-200 rounded-3xl shadow-2xl p-6">
        <div class="flex items-center justify-between border-b pb-4 mb-4">
          <div class="flex gap-1.5">
            <span class="w-3 h-3 bg-red-400 rounded-full"></span>
            <span class="w-3 h-3 bg-yellow-400 rounded-full"></span>
            <span class="w-3 h-3 bg-green-400 rounded-full"></span>
          </div>
          <span class="text-xs text-gray-400 font-mono">wapify.app/dashboard</span>
        </div>

        <!-- Mockup Content -->
        <div class="space-y-4">
          <div class="bg-purple-50 rounded-xl p-4 flex items-center justify-between border border-purple-100">
            <div class="flex items-center gap-3">
              <div class="gradient-bg p-2 rounded-lg text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
              </div>
              <div>
                <p class="text-xs text-gray-400 font-medium uppercase tracking-wider">Status API Utama</p>
                <p class="text-sm font-bold text-gray-800">Connected to WAHA REST Server</p>
              </div>
            </div>
            <span class="bg-green-100 text-green-700 text-xs px-2.5 py-1 rounded-full font-semibold">Active</span>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div class="border border-gray-100 rounded-xl p-4">
              <p class="text-xs text-gray-400">Total API Keys</p>
              <p class="text-xl font-bold mt-1">4 Active Keys</p>
            </div>
            <div class="border border-gray-100 rounded-xl p-4">
              <p class="text-xs text-gray-400">Pesan Terkirim (Bulan Ini)</p>
              <p class="text-xl font-bold mt-1">94,302 / 100K</p>
            </div>
          </div>

          <div class="border border-gray-100 rounded-xl p-4">
            <p class="text-xs text-gray-400 mb-2">Simulasi Pengiriman Pesan (cURL)</p>
            <pre class="bg-gray-900 text-gray-300 text-[10px] sm:text-xs font-mono p-3 rounded-lg overflow-x-auto">
curl -X POST http://localhost/wapify/v1/messages/send \
  -H "Authorization: Bearer wsk_xxxx..." \
  -d '{"to": "6281359774765", "text": "Halo dari Wapify!"}'</pre>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Features Section -->
  <section id="features" class="py-20 bg-white border-t border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-6">
      <div class="text-center max-w-2xl mx-auto mb-16 space-y-4">
        <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900">Infrastruktur API WhatsApp yang Handal</h2>
        <p class="text-gray-500">Kelola sesi WhatsApp, generate API keys dengan aman, serta kirim pesan instan dengan
          kuota aman dan perlindungan rate-limit bawaan.</p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Card 1 -->
        <div
          class="bg-gray-50/50 hover:bg-white border border-gray-100 hover:border-purple-100 hover:shadow-xl hover:-translate-y-1 transition-all rounded-3xl p-8 group">
          <div
            class="w-12 h-12 rounded-2xl gradient-bg-soft flex items-center justify-center text-purple-600 mb-6 group-hover:scale-110 transition-transform">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
            </svg>
          </div>
          <h3 class="text-xl font-bold text-gray-900 mb-3">Integrasi WhatsApp REST API</h3>
          <p class="text-gray-500 text-sm leading-relaxed mb-4">Kirim pesan teks, lokasi, kontak, dan file dokumen
            secara programatik lewat HTTP request yang bersih dan terstandarisasi.</p>
          <ul class="space-y-2 text-xs text-gray-500 font-medium">
            <li class="flex items-center gap-2">✓ Kirim pesan teks & media</li>
            <li class="flex items-center gap-2">✓ Idempotency Key untuk cegah duplikasi</li>
            <li class="flex items-center gap-2">✓ Response format JSON standar</li>
          </ul>
        </div>

        <!-- Card 2 -->
        <div
          class="bg-gray-50/50 hover:bg-white border border-gray-100 hover:border-purple-100 hover:shadow-xl hover:-translate-y-1 transition-all rounded-3xl p-8 group">
          <div
            class="w-12 h-12 rounded-2xl gradient-bg-soft flex items-center justify-center text-purple-600 mb-6 group-hover:scale-110 transition-transform">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M12 4v1m6 11h2m-6 0h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
            </svg>
          </div>
          <h3 class="text-xl font-bold text-gray-900 mb-3">Manajemen Sesi &amp; QR Code</h3>
          <p class="text-gray-500 text-sm leading-relaxed mb-4">Buat session baru secara dinamis, scan QR code langsung
            di dashboard, serta pantau status session (starting, scanning, working) via polling otomatis.</p>
          <ul class="space-y-2 text-xs text-gray-500 font-medium">
            <li class="flex items-center gap-2">✓ Scan QR code dinamis di web</li>
            <li class="flex items-center gap-2">✓ Deteksi otomatis status koneksi</li>
            <li class="flex items-center gap-2">✓ CRUD session (Start, Stop, Logout)</li>
          </ul>
        </div>

        <!-- Card 3 -->
        <div
          class="bg-gray-50/50 hover:bg-white border border-gray-100 hover:border-purple-100 hover:shadow-xl hover:-translate-y-1 transition-all rounded-3xl p-8 group">
          <div
            class="w-12 h-12 rounded-2xl gradient-bg-soft flex items-center justify-center text-purple-600 mb-6 group-hover:scale-110 transition-transform">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M15 7a2 2 0 012 2m-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2h6a2 2 0 002-2V9a2 2 0 00-2-2z" />
            </svg>
          </div>
          <h3 class="text-xl font-bold text-gray-900 mb-3">Kunci API Terenkripsi</h3>
          <p class="text-gray-500 text-sm leading-relaxed mb-4">Generate API key untuk autentikasi request dari server
            eksternal Anda. Disimpan aman menggunakan hash SHA-256 yang kuat.</p>
          <ul class="space-y-2 text-xs text-gray-500 font-medium">
            <li class="flex items-center gap-2">✓ Hashing aman SHA-256</li>
            <li class="flex items-center gap-2">✓ Pembuatan instan dari dashboard</li>
            <li class="flex items-center gap-2">✓ Cabut (revoke) key kapan saja</li>
          </ul>
        </div>

        <!-- Card 4 -->
        <div
          class="bg-gray-50/50 hover:bg-white border border-gray-100 hover:border-purple-100 hover:shadow-xl hover:-translate-y-1 transition-all rounded-3xl p-8 group">
          <div
            class="w-12 h-12 rounded-2xl gradient-bg-soft flex items-center justify-center text-purple-600 mb-6 group-hover:scale-110 transition-transform">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
          </div>
          <h3 class="text-xl font-bold text-gray-900 mb-3">Proteksi Kuota &amp; Rate-Limit</h3>
          <p class="text-gray-500 text-sm leading-relaxed mb-4">Reservasi kuota secara atomik langsung di database
            sebelum diteruskan ke API WAHA. Dilengkapi filter rate-limiting per-menit otomatis.</p>
          <ul class="space-y-2 text-xs text-gray-500 font-medium">
            <li class="flex items-center gap-2">✓ Reservasi kuota atomik di DB</li>
            <li class="flex items-center gap-2">✓ Rate limit per menit berbasis plan</li>
            <li class="flex items-center gap-2">✓ Aman terhadap race conditions</li>
          </ul>
        </div>
      </div>
    </div>
  </section>

  <!-- Pricing Section (Dynamic from Database) -->
  <section id="pricing" class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-6">
      <div class="text-center max-w-2xl mx-auto mb-16 space-y-4">
        <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900">Pilihan Paket Langganan Terbaik</h2>
        <p class="text-gray-500">Semua paket dilengkapi dengan akses API keys yang aman, reservasi kuota atomik, dan
          proteksi rate-limit bawaan.</p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-stretch">
        <?php foreach ($plans as $plan): ?>
          <?php
          $isPopular = $plan['name'] === 'PRO';
          $borderClass = $isPopular ? 'border-2 border-purple-500 shadow-2xl scale-[1.03] relative z-10' : 'border border-gray-200 shadow-sm';
          $btnClass = $isPopular ? 'gradient-bg text-white hover:opacity-95' : 'bg-gray-100 hover:bg-gray-200 text-gray-800';
          ?>
          <div class="bg-white rounded-3xl p-8 flex flex-col justify-between <?= $borderClass ?>">
            <?php if ($isPopular): ?>
              <span
                class="absolute top-0 right-8 transform -translate-y-1/2 gradient-bg text-white text-xs font-bold px-3 py-1.5 rounded-full tracking-wider uppercase shadow-md">Recommended</span>
            <?php endif; ?>

            <div class="space-y-6">
              <div>
                <h3 class="text-xl font-bold text-gray-900"><?= htmlspecialchars($plan['name']) ?></h3>
                <p class="text-xs text-gray-400 mt-1">
                  <?= htmlspecialchars($plan['description'] ?? 'Akses fitur premium') ?></p>
              </div>

              <div class="flex items-baseline text-gray-900">
                <span class="text-3xl font-extrabold tracking-tight">Rp</span>
                <span
                  class="text-5xl font-black tracking-tight mx-1"><?= number_format($plan['price'], 0, ',', '.') ?></span>
                <span class="text-sm font-semibold text-gray-500">/ <?= (int) $plan['duration_days'] ?> hari</span>
              </div>

              <ul class="space-y-3.5 border-t pt-6">
                <li class="flex items-center text-sm text-gray-600 gap-3">
                  <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                  </svg>
                  <span><strong><?= number_format($plan['message_limit']) ?></strong> Pesan / bulan</span>
                </li>
                <li class="flex items-center text-sm text-gray-600 gap-3">
                  <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                  </svg>
                  <span>Maks. <strong><?= (int) $plan['session_limit'] ?></strong> WA Session</span>
                </li>
                <li class="flex items-center text-sm text-gray-600 gap-3">
                  <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                  </svg>
                  <span>Rate limit: <strong><?= (int) $plan['rate_limit_per_minute'] ?></strong> req/menit</span>
                </li>
                <li class="flex items-center text-sm text-gray-600 gap-3">
                  <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                  </svg>
                  <span>Keamanan API Keys Terenkripsi</span>
                </li>
              </ul>
            </div>

            <div class="mt-8">
              <a href="<?= url('/register') ?>"
                class="block w-full text-center font-bold py-3.5 px-4 rounded-xl transition-all <?= $btnClass ?>">
                Pilih Paket
              </a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- Interactive Timeline / Steps Section -->
  <section id="steps" class="py-20 bg-white border-t border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-6">
      <div class="text-center max-w-2xl mx-auto mb-16 space-y-4">
        <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900">Tidak Ada Kesulitan untuk Memulai</h2>
        <p class="text-gray-500">Mulai integrasikan sistem Anda ke WhatsApp hanya dengan 3 langkah sederhana.</p>
      </div>

      <!-- Horizontal Steps Timeline -->
      <div class="relative max-w-5xl mx-auto pt-8">
        <!-- Horizontal connecting line (hidden on mobile, visible on desktop) -->
        <div class="hidden md:block absolute top-[44px] left-[15%] right-[15%] h-0.5 bg-purple-200 z-0"></div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative z-10">
          <!-- Step 1 -->
          <div
            class="bg-gray-50/80 hover:bg-white border border-gray-100 hover:border-purple-100 hover:shadow-xl transition-all rounded-3xl p-8 flex flex-col items-center text-center group">
            <!-- Step number bubble -->
            <div
              class="w-12 h-12 rounded-full gradient-bg border-4 border-white text-white flex items-center justify-center font-bold shadow-lg mb-6 hover:scale-110 transition-transform">
              1</div>
            <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center mb-4">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
              </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">Buat Akun</h3>
            <p class="text-sm text-gray-500 leading-relaxed">Mulai dengan mengisi pendaftaran. Akun baru Anda otomatis
              mendapatkan subscription plan **FREE** agar kuota langsung aktif.</p>
          </div>

          <!-- Step 2 -->
          <div
            class="bg-gray-50/80 hover:bg-white border border-gray-100 hover:border-purple-100 hover:shadow-xl transition-all rounded-3xl p-8 flex flex-col items-center text-center group">
            <div
              class="w-12 h-12 rounded-full gradient-bg border-4 border-white text-white flex items-center justify-center font-bold shadow-lg mb-6 hover:scale-110 transition-transform">
              2</div>
            <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 mb-4 flex items-center justify-center">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M12 4v1m6 11h2m-6 0h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
              </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">Hubungkan WhatsApp</h3>
            <p class="text-sm text-gray-500 leading-relaxed">Buat session baru di dashboard Anda dan scan QR code
              langsung menggunakan aplikasi WhatsApp di handphone Anda.</p>
          </div>

          <!-- Step 3 -->
          <div
            class="bg-gray-50/80 hover:bg-white border border-gray-100 hover:border-purple-100 hover:shadow-xl transition-all rounded-3xl p-8 flex flex-col items-center text-center group">
            <div
              class="w-12 h-12 rounded-full gradient-bg border-4 border-white text-white flex items-center justify-center font-bold shadow-lg mb-6 hover:scale-110 transition-transform">
              3</div>
            <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 mb-4 flex items-center justify-center">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M15 7a2 2 0 012 2m-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2h6a2 2 0 002-2V9a2 2 0 00-2-2z" />
              </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">Mulai Kirim Pesan</h3>
            <p class="text-sm text-gray-500 leading-relaxed">Generate API key rahasia di menu API Keys, lalu
              integrasikan ke aplikasi Anda untuk mulai mengirim pesan WhatsApp via API.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Testimonials Section -->
  <section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-6">
      <div class="text-center max-w-2xl mx-auto mb-16 space-y-4">
        <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900">Apa Kata Pelanggan Kami</h2>
        <p class="text-gray-500">Bergabunglah dengan ribuan bisnis yang puas berkembang bersama Wapify.</p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Testimonial 1 -->
        <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm flex flex-col justify-between">
          <div class="space-y-4">
            <div class="flex text-yellow-400">
              <!-- 5 Stars -->
              <?php for ($i = 0; $i < 5; $i++): ?>
                <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20">
                  <path
                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
              <?php endfor; ?>
            </div>
            <p class="text-gray-600 text-sm italic leading-relaxed">"Wapify sangat mempermudah pengiriman pesan otomatis
              dari sistem internal kami. REST API yang bersih dan dokumentasi cURL yang ringkas membuat integrasi
              selesai dalam beberapa jam saja!"</p>
          </div>
          <div class="mt-6 border-t pt-4">
            <p class="font-bold text-gray-900 text-sm">Andika Pradana</p>
            <p class="text-xs text-gray-400">Owner Online Store</p>
          </div>
        </div>

        <!-- Testimonial 2 -->
        <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm flex flex-col justify-between">
          <div class="space-y-4">
            <div class="flex text-yellow-400">
              <?php for ($i = 0; $i < 5; $i++): ?>
                <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20">
                  <path
                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
              <?php endfor; ?>
            </div>
            <p class="text-gray-600 text-sm italic leading-relaxed">"Sistem QR code polling di dashboard sangat andal.
              Begitu koneksi HP terputus, dashboard langsung memberi tahu dan kami dapat dengan cepat menyambungkan
              kembali tanpa perlu menyalakan ulang backend server."</p>
          </div>
          <div class="mt-6 border-t pt-4">
            <p class="font-bold text-gray-900 text-sm">Rifki Pratama</p>
            <p class="text-xs text-gray-400">CEO, Kursus Online</p>
          </div>
        </div>

        <!-- Testimonial 3 -->
        <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm flex flex-col justify-between">
          <div class="space-y-4">
            <div class="flex text-yellow-400">
              <?php for ($i = 0; $i < 5; $i++): ?>
                <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20">
                  <path
                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
              <?php endfor; ?>
            </div>
            <p class="text-gray-600 text-sm italic leading-relaxed">"Penanganan rate-limiting dan idempotensi pesan di
              platform ini benar-benar luar biasa. Pengiriman ulang notifikasi otomatis tidak pernah lagi mengirim pesan
              dobel ke pelanggan."</p>
          </div>
          <div class="mt-6 border-t pt-4">
            <p class="font-bold text-gray-900 text-sm">Rina Madina</p>
            <p class="text-xs text-gray-400">Founder, Digital Solutions</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- FAQ Section -->
  <section id="faq" class="py-20 bg-white border-t border-b border-gray-100">
    <div class="max-w-4xl mx-auto px-6">
      <div class="text-center max-w-2xl mx-auto mb-16 space-y-4">
        <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900">Pertanyaan yang Sering Diajukan</h2>
        <p class="text-gray-500">Semua yang perlu Anda ketahui tentang harga dan layanan kami</p>
      </div>

      <div class="space-y-4">
        <!-- Accordion 1 -->
        <div
          class="border border-gray-200 rounded-2xl bg-gray-50/50 hover:bg-white transition-all overflow-hidden accordion-item">
          <button type="button"
            class="w-full flex items-center justify-between p-6 text-left font-bold text-gray-900 focus:outline-none"
            onclick="toggleAccordion(this)">
            <span>Bisakah saya mengganti paket kapan saja?</span>
            <svg class="w-5 h-5 text-purple-500 accordion-icon transform transition-transform" fill="none"
              stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
          </button>
          <div class="accordion-content">
            <div class="px-6 pb-6 text-sm text-gray-500 leading-relaxed">
              Ya! Anda dapat upgrade atau downgrade paket kapan saja. Saat upgrade, Anda akan dikenakan biaya
              proporsional untuk sisa periode penagihan Anda. Saat downgrade, perubahan akan berlaku di awal periode
              penagihan berikutnya.
            </div>
          </div>
        </div>

        <!-- Accordion 2 -->
        <div
          class="border border-gray-200 rounded-2xl bg-gray-50/50 hover:bg-white transition-all overflow-hidden accordion-item">
          <button type="button"
            class="w-full flex items-center justify-between p-6 text-left font-bold text-gray-900 focus:outline-none"
            onclick="toggleAccordion(this)">
            <span>Metode pembayaran apa yang Anda terima?</span>
            <svg class="w-5 h-5 text-purple-500 accordion-icon transform transition-transform" fill="none"
              stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
          </button>
          <div class="accordion-content">
            <div class="px-6 pb-6 text-sm text-gray-500 leading-relaxed">
              Kami menerima berbagai metode pembayaran instan termasuk QRIS, Transfer Bank Virtual Account (BCA,
              Mandiri, BNI, BRI), Kartu Kredit, serta dompet digital Gopay dan OVO.
            </div>
          </div>
        </div>

        <!-- Accordion 3 -->
        <div
          class="border border-gray-200 rounded-2xl bg-gray-50/50 hover:bg-white transition-all overflow-hidden accordion-item">
          <button type="button"
            class="w-full flex items-center justify-between p-6 text-left font-bold text-gray-900 focus:outline-none"
            onclick="toggleAccordion(this)">
            <span>Apakah ada kontrak atau bisakah saya batal kapan saja?</span>
            <svg class="w-5 h-5 text-purple-500 accordion-icon transform transition-transform" fill="none"
              stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
          </button>
          <div class="accordion-content">
            <div class="px-6 pb-6 text-sm text-gray-500 leading-relaxed">
              Tidak ada kontrak mengikat. Anda dapat membatalkan langganan Anda kapan saja langsung dari dashboard
              customer Anda. Layanan akan tetap aktif sampai masa aktif paket Anda berakhir.
            </div>
          </div>
        </div>

        <!-- Accordion 4 -->
        <div
          class="border border-gray-200 rounded-2xl bg-gray-50/50 hover:bg-white transition-all overflow-hidden accordion-item">
          <button type="button"
            class="w-full flex items-center justify-between p-6 text-left font-bold text-gray-900 focus:outline-none"
            onclick="toggleAccordion(this)">
            <span>Apa yang terjadi jika saya melebihi batas paket?</span>
            <svg class="w-5 h-5 text-purple-500 accordion-icon transform transition-transform" fill="none"
              stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
          </button>
          <div class="accordion-content">
            <div class="px-6 pb-6 text-sm text-gray-500 leading-relaxed">
              Jika limit pesan habis, pengiriman pesan melalui API akan dibatasi sampai siklus berikutnya dimulai atau
              Anda melakukan top-up kuota pesan tambahan secara instan.
            </div>
          </div>
        </div>

        <!-- Accordion 5 -->
        <div
          class="border border-gray-200 rounded-2xl bg-gray-50/50 hover:bg-white transition-all overflow-hidden accordion-item">
          <button type="button"
            class="w-full flex items-center justify-between p-6 text-left font-bold text-gray-900 focus:outline-none"
            onclick="toggleAccordion(this)">
            <span>Apakah data saya aman dan pribadi?</span>
            <svg class="w-5 h-5 text-purple-500 accordion-icon transform transition-transform" fill="none"
              stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
          </button>
          <div class="accordion-content">
            <div class="px-6 pb-6 text-sm text-gray-500 leading-relaxed">
              Sangat aman. Kami menggunakan enkripsi kelas industri untuk menyimpan semua kredensial session WhatsApp
              Anda. Data chat Anda diteruskan langsung dan tidak disimpan permanen di server database kami.
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- CTA Section -->
  <section class="py-20 gradient-bg text-white">
    <div class="max-w-4xl mx-auto px-6 text-center space-y-6">
      <h2 class="text-3xl sm:text-4xl font-extrabold">Siap untuk Memulai?</h2>
      <p class="text-purple-100 max-w-xl mx-auto">Bergabunglah dengan ribuan bisnis yang sudah berkembang dengan
        efisiensi integrasi otomatis dari Wapify. Mulai uji coba gratis hari ini!</p>
      <div class="flex gap-4 justify-center pt-4">
        <a href="<?= url('/register') ?>"
          class="bg-white text-purple-700 font-bold px-8 py-3.5 rounded-xl hover:scale-105 transition-all shadow-xl">Daftar
          Sekarang</a>
        <a href="https://wa.me/6281359774765?text=Halo%20Wapify%2C%20saya%20tertarik%20dengan%20layanan%20integrasi%20WhatsApp%20API.%20Bisa%20tolong%20berikan%20informasi%20lebih%20lanjut%3F"
          target="_blank"
          class="bg-purple-600/40 text-white border border-purple-400 font-bold px-8 py-3.5 rounded-xl hover:bg-purple-600/60 transition-all">Hubungi
          Sales</a>
      </div>
    </div>
  </section>

  <!-- Footer / Contact Info -->
  <footer id="contact" class="bg-gray-900 text-gray-400 py-16 border-t border-gray-800">
    <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-3 gap-12">
      <!-- Col 1 -->
      <div class="space-y-4">
        <div class="flex items-center gap-3 text-white">
          <div class="gradient-bg p-2 rounded-xl text-white">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
            </svg>
          </div>
          <span class="text-xl font-bold tracking-tight font-display">Wapify</span>
        </div>
        <p class="text-sm">Platform SaaS integrasi WhatsApp REST API paling andal dan cepat untuk menskalakan
          operasional bisnis Anda.</p>
        <p class="text-xs pt-4 text-gray-600">&copy; <?= date('Y') ?> Wapify By Sintesa Corp. Hak Cipta Dilindungi.</p>
      </div>

      <!-- Col 2: Contact Info -->
      <div class="space-y-4">
        <h3 class="text-white font-bold font-display uppercase tracking-wider text-sm">Hubungi Kami</h3>
        <ul class="space-y-3 text-sm">
          <li class="flex items-start gap-3">
            <svg class="w-5 h-5 text-purple-500 mt-0.5" fill="none" stroke="currentColor" stroke-width="2"
              viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
            </svg>
            <a href="https://wa.me/6281359774765?text=Halo%20Wapify%2C%20saya%20tertarik%20dengan%20layanan%20integrasi%20WhatsApp%20API.%20Bisa%20tolong%20berikan%20informasi%20lebih%20lanjut%3F"
              target="_blank" class="hover:text-white transition-colors">081359774765</a>
          </li>
          <li class="flex items-start gap-3">
            <svg class="w-5 h-5 text-purple-500 mt-0.5" fill="none" stroke="currentColor" stroke-width="2"
              viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
            <a href="mailto:sintesa.corporation@gmail.com"
              class="hover:text-white transition-colors">sintesa.corporation@gmail.com</a>
          </li>
          <li class="flex items-start gap-3">
            <svg class="w-5 h-5 text-purple-500 mt-0.5" fill="none" stroke="currentColor" stroke-width="2"
              viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <span>Jl. Sultan Syahrir, Bontang, Kalimantan Timur</span>
          </li>
        </ul>
      </div>

      <!-- Col 3: Quick Links -->
      <div class="space-y-4">
        <h3 class="text-white font-bold font-display uppercase tracking-wider text-sm">Navigasi</h3>
        <ul class="space-y-2 text-sm">
          <li><a href="#features" class="hover:text-white transition-colors">Fitur Platform</a></li>
          <li><a href="#pricing" class="hover:text-white transition-colors">Paket &amp; Harga</a></li>
          <li><a href="#faq" class="hover:text-white transition-colors">Bantuan / FAQ</a></li>
          <li><a href="<?= url('/login') ?>" class="hover:text-white transition-colors">Masuk Dashboard</a></li>
          <li><a href="<?= url('/register') ?>" class="hover:text-white transition-colors">Buat Akun Gratis</a></li>
        </ul>
      </div>
    </div>
  </footer>
</div>

<script>
  function toggleAccordion(button) {
    const item = button.closest('.accordion-item');
    const isOpen = item.classList.contains('accordion-open');

    // Close all items
    document.querySelectorAll('.accordion-item').forEach(el => {
      el.classList.remove('accordion-open');
    });

    // If it was closed, open it
    if (!isOpen) {
      item.classList.add('accordion-open');
    }
  }
</script>

<!-- Scroll To Top Button -->
<button id="scrollTopBtn" onclick="scrollToTop()"
  title="Kembali ke atas"
  style="
    position: fixed;
    bottom: 32px;
    right: 32px;
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: linear-gradient(135deg, #7C3AED, #3B82F6);
    color: white;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 8px 24px rgba(124, 58, 237, 0.35);
    opacity: 0;
    transform: translateY(16px) scale(0.85);
    transition: opacity 0.3s ease, transform 0.3s ease, box-shadow 0.2s ease;
    pointer-events: none;
    z-index: 9999;
  "
  onmouseover="this.style.boxShadow='0 12px 32px rgba(124,58,237,0.5)'; this.style.transform='translateY(0) scale(1.08)'"
  onmouseout="this.style.boxShadow='0 8px 24px rgba(124,58,237,0.35)'; this.style.transform='translateY(0) scale(1)'"
>
  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
    <path d="M18 15l-6-6-6 6"/>
  </svg>
</button>

<script>
  var scrollBtn = document.getElementById('scrollTopBtn');

  window.addEventListener('scroll', function () {
    if (window.scrollY > 400) {
      scrollBtn.style.opacity = '1';
      scrollBtn.style.transform = 'translateY(0) scale(1)';
      scrollBtn.style.pointerEvents = 'auto';
    } else {
      scrollBtn.style.opacity = '0';
      scrollBtn.style.transform = 'translateY(16px) scale(0.85)';
      scrollBtn.style.pointerEvents = 'none';
    }
  });

  function scrollToTop() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>