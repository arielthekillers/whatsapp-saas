<?php $title = 'Admin Panel'; require __DIR__ . '/../layouts/header.php'; require __DIR__ . '/../layouts/nav.php'; ?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6 text-gray-800">

  <!-- Clean Title Bar -->
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-gray-200">
    <div>
      <h1 class="text-xl font-bold text-gray-900 tracking-tight">Dashboard Admin</h1>
      <p class="text-xs text-gray-500 mt-0.5">Kelola pengguna, verifikasi pembayaran, status WAHA API, dan logs sistem.</p>
    </div>

    <div class="flex items-center gap-2">
      <a href="<?= url('/admin/export-payments') ?>" class="inline-flex items-center gap-1.5 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 text-xs font-medium px-3 py-1.5 rounded-lg transition-colors shadow-xs">
        <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        Export CSV
      </a>
      <a href="<?= url('/admin') ?>" class="inline-flex items-center gap-1.5 bg-purple-600 hover:bg-purple-700 text-white text-xs font-medium px-3 py-1.5 rounded-lg transition-colors shadow-xs">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
        Refresh Data
      </a>
    </div>
  </div>

  <?php if (!empty($success)): ?>
    <div class="rounded-lg bg-emerald-50 border border-emerald-200 p-3 text-xs text-emerald-800 font-medium flex items-center gap-2">
      <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
      <span><?= htmlspecialchars($success) ?></span>
    </div>
  <?php endif; ?>

  <?php if (!empty($error)): ?>
    <div class="rounded-lg bg-rose-50 border border-rose-200 p-3 text-xs text-rose-800 font-medium flex items-center gap-2">
      <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
      <span><?= htmlspecialchars($error) ?></span>
    </div>
  <?php endif; ?>

  <!-- Vibrant Stat Cards Grid (With Inline Gradient Fallback) -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
    <!-- Stat 1: Total Pelanggan (Purple/Indigo) -->
    <div style="background: linear-gradient(135deg, #7C3AED 0%, #4F46E5 100%);" class="relative overflow-hidden rounded-2xl p-5 text-white shadow-lg shadow-purple-500/20 transition-all hover:scale-[1.01]">
      <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-white/10 rounded-full blur-xl pointer-events-none"></div>
      <div class="flex items-center justify-between relative z-10">
        <div>
          <span class="text-xs font-semibold uppercase tracking-wider text-purple-100">Total Pelanggan</span>
          <h3 class="text-3xl font-extrabold mt-1 tracking-tight text-white"><?= number_format($totalUsers) ?></h3>
          <p class="text-[11px] text-purple-100/80 mt-1">User terdaftar</p>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-white/20 backdrop-blur-md text-white flex items-center justify-center shrink-0 border border-white/20 shadow-inner">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
        </div>
      </div>
    </div>

    <!-- Stat 2: WhatsApp Sessions (Blue) -->
    <div style="background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%);" class="relative overflow-hidden rounded-2xl p-5 text-white shadow-lg shadow-blue-500/20 transition-all hover:scale-[1.01]">
      <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-white/10 rounded-full blur-xl pointer-events-none"></div>
      <div class="flex items-center justify-between relative z-10">
        <div>
          <span class="text-xs font-semibold uppercase tracking-wider text-blue-100">WhatsApp Sessions</span>
          <h3 class="text-3xl font-extrabold mt-1 tracking-tight text-white"><?= number_format($totalSessions) ?></h3>
          <p class="text-[11px] text-blue-100/80 mt-1">Sesi terhubung</p>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-white/20 backdrop-blur-md text-white flex items-center justify-center shrink-0 border border-white/20 shadow-inner">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
        </div>
      </div>
    </div>

    <!-- Stat 3: Total Pendapatan (Emerald Green) -->
    <div style="background: linear-gradient(135deg, #059669 0%, #047857 100%);" class="relative overflow-hidden rounded-2xl p-5 text-white shadow-lg shadow-emerald-500/20 transition-all hover:scale-[1.01]">
      <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-white/10 rounded-full blur-xl pointer-events-none"></div>
      <div class="flex items-center justify-between relative z-10">
        <div>
          <span class="text-xs font-semibold uppercase tracking-wider text-emerald-100">Total Pendapatan</span>
          <h3 class="text-2xl sm:text-3xl font-extrabold mt-1 tracking-tight text-white">Rp <?= number_format($totalRevenue, 0, ',', '.') ?></h3>
          <p class="text-[11px] text-emerald-100/80 mt-1">Omset lunas</p>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-white/20 backdrop-blur-md text-white flex items-center justify-center shrink-0 border border-white/20 shadow-inner">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
      </div>
    </div>

    <!-- Stat 4: Menunggu Verifikasi (Amber/Red) -->
    <div style="background: linear-gradient(135deg, #D97706 0%, #DC2626 100%);" class="relative overflow-hidden rounded-2xl p-5 text-white shadow-lg shadow-amber-500/20 transition-all hover:scale-[1.01]">
      <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-white/10 rounded-full blur-xl pointer-events-none"></div>
      <div class="flex items-center justify-between relative z-10">
        <div>
          <span class="text-xs font-semibold uppercase tracking-wider text-amber-100">Menunggu Verifikasi</span>
          <h3 class="text-3xl font-extrabold mt-1 tracking-tight text-white"><?= number_format($pendingPayments) ?></h3>
          <p class="text-[11px] text-amber-100/80 mt-1">Invoice perlu diaudit</p>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-white/20 backdrop-blur-md text-white flex items-center justify-center shrink-0 border border-white/20 shadow-inner">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
        </div>
      </div>
    </div>
  </div>

  <!-- Clean Segmented Tab Nav -->
  <div class="border-b border-gray-200">
    <nav class="-mb-px flex space-x-6 overflow-x-auto text-xs" aria-label="Tabs">
      <button type="button" onclick="switchAdminTab('overview', this)" class="admin-tab-btn border-purple-600 text-purple-600 py-2.5 border-b-2 font-semibold inline-flex items-center gap-2 whitespace-nowrap focus:outline-none">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
        Overview
      </button>

      <button type="button" onclick="switchAdminTab('verifikasi', this)" class="admin-tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-2.5 border-b-2 font-medium inline-flex items-center gap-2 whitespace-nowrap focus:outline-none">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span>Verifikasi Pembayaran</span>
        <?php if (!empty($pendingList)): ?>
          <span class="bg-amber-100 text-amber-800 text-[10px] font-bold px-1.5 py-0.5 rounded-full"><?= count($pendingList) ?></span>
        <?php endif; ?>
      </button>

      <button type="button" onclick="switchAdminTab('pengguna', this)" class="admin-tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-2.5 border-b-2 font-medium inline-flex items-center gap-2 whitespace-nowrap focus:outline-none">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
        <span>Manajemen Pengguna</span>
        <span class="bg-gray-100 text-gray-600 text-[10px] font-bold px-1.5 py-0.5 rounded-full"><?= count($usersList) ?></span>
      </button>

      <button type="button" onclick="switchAdminTab('audit', this)" class="admin-tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-2.5 border-b-2 font-medium inline-flex items-center gap-2 whitespace-nowrap focus:outline-none">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        Audit Log
      </button>
    </nav>
  </div>

  <!-- TAB 1: OVERVIEW -->
  <div id="tab-overview" class="admin-tab-content space-y-6">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">
      
      <!-- Left Column: WAHA Server & Job Queue Health -->
      <div class="lg:col-span-6 flex flex-col justify-between space-y-6">
        
        <!-- WAHA Status Card (Dynamic Green if ONLINE, Gray if OFFLINE) -->
        <?php $isOnline = ($wahaStatus['status'] === 'ONLINE'); ?>
        <div style="<?= $isOnline ? 'background: linear-gradient(135deg, #F0FDF4 0%, #DCFCE7 100%); border: 1px solid #86EFAC;' : 'background: linear-gradient(135deg, #F9FAFB 0%, #F3F4F6 100%); border: 1px solid #E5E7EB;' ?>" class="rounded-2xl p-5 shadow-xs space-y-4 transition-all flex-1 flex flex-col justify-between">
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
              <div style="<?= $isOnline ? 'background: #10B981; color: white;' : 'background: #6B7280; color: white;' ?>" class="w-10 h-10 rounded-xl flex items-center justify-center font-bold text-base shadow-sm">
                ⚡
              </div>
              <div>
                <h3 class="font-bold <?= $isOnline ? 'text-emerald-950' : 'text-gray-800' ?> text-sm">Server WAHA API</h3>
                <p class="text-xs <?= $isOnline ? 'text-emerald-700' : 'text-gray-500' ?>">WhatsApp Service Gateway</p>
              </div>
            </div>

            <span style="<?= $isOnline ? 'background: #10B981; color: white;' : 'background: #6B7280; color: white;' ?>" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold shadow-xs">
              <?php if ($isOnline): ?>
                <span class="relative flex h-2 w-2">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2 w-2 bg-white"></span>
                </span>
              <?php else: ?>
                <span class="w-2 h-2 rounded-full bg-gray-300"></span>
              <?php endif; ?>
              <?= $wahaStatus['status'] ?>
            </span>
          </div>

          <div style="<?= $isOnline ? 'background: rgba(255,255,255,0.7); border: 1px solid #A7F3D0;' : 'background: rgba(255,255,255,0.7); border: 1px solid #E5E7EB;' ?>" class="rounded-xl p-3 text-xs space-y-1.5 backdrop-blur-xs">
            <div class="flex justify-between items-center">
              <span class="text-gray-500">Base URL API:</span>
              <code class="font-mono <?= $isOnline ? 'text-emerald-800' : 'text-gray-700' ?> font-semibold"><?= htmlspecialchars($wahaStatus['url']) ?></code>
            </div>
            <div class="flex justify-between items-center">
              <span class="text-gray-500">Latency Ping:</span>
              <span class="font-bold <?= $isOnline ? 'text-emerald-700' : 'text-gray-600' ?> font-mono"><?= $wahaStatus['latency'] ?> ms</span>
            </div>
          </div>
        </div>

        <!-- Background Job Queue Card -->
        <div class="bg-white rounded-2xl border border-gray-200/80 p-5 shadow-xs space-y-4 flex-1 flex flex-col justify-between">
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-2.5">
              <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-sm">⚙️</div>
              <div>
                <h3 class="font-bold text-gray-900 text-sm">Background Job Queue</h3>
                <p class="text-xs text-gray-500">Antrean tugas pesan &amp; webhook</p>
              </div>
            </div>
            <?php if ($jobStats['failed'] > 0): ?>
              <form method="POST" action="<?= url('/admin/jobs/retry-failed') ?>">
                <?= \App\Helpers\Csrf::field() ?>
                <button type="submit" class="bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold px-3 py-1.5 rounded-xl transition-all shadow-xs">
                  Retry Failed
                </button>
              </form>
            <?php endif; ?>
          </div>

          <div class="grid grid-cols-3 gap-3 text-center text-xs">
            <div class="bg-amber-50/70 border border-amber-200/70 rounded-xl p-3">
              <span class="text-amber-700 block text-xs font-bold uppercase tracking-wider">Pending</span>
              <span class="font-extrabold text-amber-900 text-lg font-mono mt-0.5 block"><?= number_format($jobStats['pending']) ?></span>
            </div>
            <div class="bg-emerald-50/70 border border-emerald-200/70 rounded-xl p-3">
              <span class="text-emerald-700 block text-xs font-bold uppercase tracking-wider">Completed</span>
              <span class="font-extrabold text-emerald-900 text-lg font-mono mt-0.5 block"><?= number_format($jobStats['completed']) ?></span>
            </div>
            <div class="bg-rose-50/70 border border-rose-200/70 rounded-xl p-3">
              <span class="text-rose-700 block text-xs font-bold uppercase tracking-wider">Failed</span>
              <span class="font-extrabold text-rose-900 text-lg font-mono mt-0.5 block"><?= number_format($jobStats['failed']) ?></span>
            </div>
          </div>
        </div>

      </div>

      <!-- Right Column: Broadcast Pengumuman Box (Equal Height with Left Column) -->
      <div class="lg:col-span-6 h-full flex flex-col">
        <div class="bg-white rounded-2xl border border-gray-200/80 p-5 shadow-xs space-y-4 flex-1 flex flex-col justify-between">
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-2.5">
              <div class="w-9 h-9 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-sm">📢</div>
              <div>
                <h3 class="font-bold text-gray-900 text-sm">Broadcast Pengumuman</h3>
                <p class="text-xs text-gray-500">Pesan global di dashboard pelanggan</p>
              </div>
            </div>
            <?php if (!empty($activeAnnouncement)): ?>
              <form method="POST" action="<?= url('/admin/announcement/delete') ?>">
                <?= \App\Helpers\Csrf::field() ?>
                <button type="submit" class="text-rose-600 hover:text-rose-800 text-xs font-bold hover:underline">
                  ✕ Hapus Broadcast
                </button>
              </form>
            <?php endif; ?>
          </div>

          <form method="POST" action="<?= url('/admin/announcement') ?>" class="space-y-4 flex-1 flex flex-col justify-between">
            <?= \App\Helpers\Csrf::field() ?>
            <div class="flex-1 flex flex-col">
              <label class="block text-xs font-semibold text-gray-700 mb-1">Pesan Pengumuman</label>
              <textarea name="message" rows="4" required placeholder="Tulis pengumuman sistem... (bisa beberapa baris)" class="w-full flex-1 text-xs rounded-xl border border-gray-200 p-3 focus:ring-2 focus:ring-purple-500 focus:outline-none bg-gray-50/50 resize-y font-sans"><?= htmlspecialchars($activeAnnouncement['message'] ?? '') ?></textarea>
            </div>

            <div class="space-y-2 pt-2">
              <label class="block text-xs font-semibold text-gray-700">Tipe Banner Pengumuman</label>
              <div class="flex flex-wrap items-center gap-2.5">
                <label style="background: #F3E8FF; border: 1px solid #D8B4FE; color: #6B21A8;" class="flex items-center gap-2 cursor-pointer px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all">
                  <input type="radio" name="type" value="info" <?= ($activeAnnouncement['type'] ?? 'info') === 'info' ? 'checked' : '' ?> class="text-purple-600 focus:ring-purple-500">
                  <span style="background: #9333EA;" class="w-2.5 h-2.5 rounded-full inline-block"></span>
                  <span>Info</span>
                </label>

                <label style="background: #FEF3C7; border: 1px solid #FDE68A; color: #92400E;" class="flex items-center gap-2 cursor-pointer px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all">
                  <input type="radio" name="type" value="warning" <?= ($activeAnnouncement['type'] ?? '') === 'warning' ? 'checked' : '' ?> class="text-amber-600 focus:ring-amber-500">
                  <span style="background: #F59E0B;" class="w-2.5 h-2.5 rounded-full inline-block"></span>
                  <span>Peringatan</span>
                </label>

                <label style="background: #FFE4E6; border: 1px solid #FECDD3; color: #9F1239;" class="flex items-center gap-2 cursor-pointer px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all">
                  <input type="radio" name="type" value="danger" <?= ($activeAnnouncement['type'] ?? '') === 'danger' ? 'checked' : '' ?> class="text-rose-600 focus:ring-rose-500">
                  <span style="background: #E11D48;" class="w-2.5 h-2.5 rounded-full inline-block"></span>
                  <span>Bahaya</span>
                </label>
              </div>
            </div>

            <div class="pt-2">
              <button type="submit" style="background: linear-gradient(135deg, #7C3AED 0%, #4F46E5 100%);" class="w-full text-white font-bold text-xs px-5 py-2.5 rounded-xl shadow-md shadow-purple-500/25 hover:opacity-95 transition-all flex items-center justify-center gap-2 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                Publikasikan Pengumuman
              </button>
            </div>
          </form>
        </div>
      </div>

    </div>
  </div>

  <!-- TAB 2: VERIFIKASI PEMBAYARAN (Clean Flat Table Layout) -->
  <div id="tab-verifikasi" class="admin-tab-content hidden space-y-4">
    <div>
      <h2 class="text-sm font-semibold text-gray-900">Verifikasi Pembayaran Transfer Bank</h2>
      <p class="text-xs text-gray-500">Periksa bukti transfer pengirim dan aktifkan paket langganan secara instan.</p>
    </div>

    <?php if (!empty($pendingList)): ?>
      <div class="bg-white rounded-xl border border-gray-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full text-xs text-left">
            <thead class="bg-gray-50 text-gray-500 font-semibold border-b border-gray-200">
              <tr>
                <th class="px-5 py-3 font-semibold">Invoice &amp; Pelanggan</th>
                <th class="px-5 py-3 font-semibold">Paket &amp; Durasi</th>
                <th class="px-5 py-3 font-semibold">Catatan Transfer</th>
                <th class="px-5 py-3 font-semibold">Total Nominal</th>
                <th class="px-5 py-3 font-semibold text-right">Tindakan</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-gray-700">
              <?php foreach ($pendingList as $pmt): ?>
                <?php
                  $parts  = explode(':', $pmt['provider'] ?? '');
                  $months = isset($parts[1]) ? (int)$parts[1] : 1;
                  $label  = match($months) { 3 => '3 Bulan (-5%)', 6 => '6 Bulan (-10%)', 12 => '1 Tahun (-20%)', default => '1 Bulan' };
                  $isVerifying = ($pmt['status'] === 'verifying');
                ?>
                <tr class="hover:bg-gray-50/60 transition-colors">
                  <td class="px-5 py-3.5">
                    <div class="space-y-0.5">
                      <div class="flex items-center gap-2">
                        <span class="font-medium text-gray-900"><?= htmlspecialchars($pmt['user_name']) ?></span>
                        <span class="inline-flex items-center gap-1 text-[11px] font-medium <?= $isVerifying ? 'bg-blue-50 text-blue-700' : 'bg-amber-50 text-amber-700' ?> px-2 py-0.5 rounded-md">
                          <span class="w-1.5 h-1.5 rounded-full <?= $isVerifying ? 'bg-blue-500' : 'bg-amber-500' ?>"></span>
                          <?= $isVerifying ? 'Dikonfirmasi' : 'Menunggu' ?>
                        </span>
                      </div>
                      <p class="text-xs text-gray-500"><?= htmlspecialchars($pmt['user_email']) ?> • <span class="font-mono text-gray-400"><?= htmlspecialchars($pmt['external_id']) ?></span></p>
                    </div>
                  </td>
                  <td class="px-5 py-3.5">
                    <span class="font-semibold text-gray-900"><?= htmlspecialchars($pmt['plan_name']) ?></span>
                    <span class="text-gray-500 block text-xs"><?= $label ?></span>
                  </td>
                  <td class="px-5 py-3.5 max-w-xs">
                    <?php if ($pmt['transfer_note']): ?>
                      <p class="text-xs text-gray-700 truncate"><?= htmlspecialchars($pmt['transfer_note']) ?></p>
                    <?php else: ?>
                      <span class="text-gray-400">-</span>
                    <?php endif; ?>
                  </td>
                  <td class="px-5 py-3.5">
                    <span class="font-semibold text-gray-900">Rp <?= number_format((float)$pmt['amount'], 0, ',', '.') ?></span>
                  </td>
                  <td class="px-5 py-3.5 text-right whitespace-nowrap">
                    <div class="inline-flex items-center gap-2 justify-end">
                      <form method="POST" action="<?= url('/admin/payment/approve') ?>" onsubmit="return confirm('Setujui pembayaran ini dan aktifkan paket?')">
                        <?= \App\Helpers\Csrf::field() ?>
                        <input type="hidden" name="payment_id" value="<?= (int)$pmt['id'] ?>">
                        <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white text-xs font-medium px-3 py-1.5 rounded-lg transition-colors">
                          Setujui
                        </button>
                      </form>

                      <form method="POST" action="<?= url('/admin/payment/reject') ?>" onsubmit="var r=prompt('Alasan penolakan:','Transfer tidak sesuai');if(!r)return false;this.querySelector('[name=reason]').value=r;return true;">
                        <?= \App\Helpers\Csrf::field() ?>
                        <input type="hidden" name="payment_id" value="<?= (int)$pmt['id'] ?>">
                        <input type="hidden" name="reason" value="">
                        <button type="submit" class="bg-white hover:bg-gray-50 text-rose-600 border border-gray-200 text-xs font-medium px-3 py-1.5 rounded-lg transition-colors">
                          Tolak
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    <?php else: ?>
      <div class="bg-white rounded-xl border border-gray-200 p-6 text-center space-y-1">
        <p class="font-semibold text-gray-900 text-xs">Tidak Ada Pembayaran Menunggu Verifikasi</p>
        <p class="text-xs text-gray-500">Seluruh transaksi invoice telah diproses atau dibatalkan.</p>
      </div>
    <?php endif; ?>
  </div>

  <!-- TAB 3: MANAJEMEN PENGGUNA -->
  <div id="tab-pengguna" class="admin-tab-content hidden space-y-4">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
      <div>
        <h2 class="text-sm font-semibold text-gray-900">Daftar Pengguna &amp; Kontrol Akses</h2>
        <p class="text-xs text-gray-500">Atur status akun pengguna dan kuota langganan.</p>
      </div>
      <div class="w-full sm:w-56">
        <input type="text" id="user-search-input" onkeyup="filterUserTable()" placeholder="Cari nama/email..." class="w-full text-xs rounded-lg border border-gray-200 px-3 py-1.5 focus:ring-1 focus:ring-purple-500 focus:outline-none bg-white">
      </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-xs overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-xs text-left" id="user-table">
          <thead class="bg-gray-50 text-gray-500 font-semibold border-b border-gray-200">
            <tr>
              <th class="px-5 py-3 font-semibold">Pelanggan</th>
              <th class="px-5 py-3 font-semibold">Status</th>
              <th class="px-5 py-3 font-semibold">Paket Aktif</th>
              <th class="px-5 py-3 font-semibold">Pemakaian Kuota</th>
              <th class="px-5 py-3 font-semibold">Masa Berlaku</th>
              <th class="px-5 py-3 font-semibold text-right">Ubah Paket Manual</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 text-gray-700">
            <?php foreach ($usersList as $usr): ?>
              <tr class="hover:bg-gray-50/60 transition-colors user-row">
                <td class="px-5 py-3">
                  <div>
                    <p class="font-semibold text-gray-900 user-name"><?= htmlspecialchars($usr['name']) ?></p>
                    <p class="text-xs text-gray-500 user-email"><?= htmlspecialchars($usr['email']) ?></p>
                  </div>
                </td>
                <td class="px-5 py-3">
                  <form method="POST" action="<?= url('/admin/user/status') ?>" class="inline-block">
                    <?= \App\Helpers\Csrf::field() ?>
                    <input type="hidden" name="user_id" value="<?= (int) $usr['user_id'] ?>">
                    <select name="status" onchange="if(confirm('Ubah status user ini?')) this.form.submit();" class="text-xs font-medium rounded-md border border-gray-200 py-1 px-2 focus:outline-none <?= $usr['user_status'] === 'active' ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' ?>">
                      <option value="active" <?= $usr['user_status'] === 'active' ? 'selected' : '' ?>>active</option>
                      <option value="suspended" <?= $usr['user_status'] === 'suspended' ? 'selected' : '' ?>>suspended</option>
                      <option value="banned" <?= $usr['user_status'] === 'banned' ? 'selected' : '' ?>>banned</option>
                    </select>
                  </form>
                </td>
                <td class="px-5 py-3">
                  <span class="font-medium text-gray-900"><?= htmlspecialchars($usr['plan_name'] ?? 'Tidak Aktif') ?></span>
                </td>
                <td class="px-5 py-3 text-gray-600">
                  <?php if ($usr['plan_id']): ?>
                    <span class="font-medium text-gray-900"><?= number_format((float)$usr['messages_used']) ?></span>
                    <span class="text-gray-400">/ <?= number_format((float)$usr['messages_limit']) ?></span>
                  <?php else: ?>
                    <span class="text-gray-400">-</span>
                  <?php endif; ?>
                </td>
                <td class="px-5 py-3 text-gray-600">
                  <?= $usr['end_at'] ? htmlspecialchars($usr['end_at']) : '<span class="text-gray-400">-</span>' ?>
                </td>
                <td class="px-5 py-3 text-right">
                  <form method="POST" action="<?= url('/admin/plan/update') ?>" class="inline-flex gap-1.5 items-center justify-end">
                    <?= \App\Helpers\Csrf::field() ?>
                    <input type="hidden" name="user_id" value="<?= (int) $usr['user_id'] ?>">
                    <select name="plan_id" class="text-xs rounded-md border border-gray-200 py-1 px-2 bg-white text-gray-700 focus:outline-none">
                      <?php foreach ($allPlans as $pl): ?>
                        <option value="<?= (int) $pl['id'] ?>" <?= $usr['plan_id'] === $pl['id'] ? 'selected' : '' ?>><?= htmlspecialchars($pl['name']) ?></option>
                      <?php endforeach; ?>
                    </select>
                    <button type="submit" class="bg-gray-900 hover:bg-black text-white text-xs font-medium px-2.5 py-1 rounded-md transition-colors">
                      Simpan
                    </button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- TAB 4: AUDIT LOG -->
  <div id="tab-audit" class="admin-tab-content hidden space-y-4">
    <div>
      <h2 class="text-sm font-semibold text-gray-900">Audit Log Activity</h2>
      <p class="text-xs text-gray-500">Catatan riwayat tindakan penting oleh Admin &amp; Sistem.</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-xs overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-xs text-left">
          <thead class="bg-gray-50 text-gray-500 font-semibold border-b border-gray-200">
            <tr>
              <th class="px-5 py-3 font-semibold">Waktu</th>
              <th class="px-5 py-3 font-semibold">Admin</th>
              <th class="px-5 py-3 font-semibold">Action</th>
              <th class="px-5 py-3 font-semibold">Target</th>
              <th class="px-5 py-3 font-semibold">Metadata</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 text-gray-700">
            <?php if (empty($auditLogs)): ?>
              <tr><td colspan="5" class="px-5 py-6 text-center text-gray-400">Belum ada aktivitas audit log.</td></tr>
            <?php endif; ?>
            <?php foreach ($auditLogs as $log): ?>
              <tr class="hover:bg-gray-50/60 transition-colors">
                <td class="px-5 py-3 text-gray-500"><?= htmlspecialchars($log['created_at']) ?></td>
                <td class="px-5 py-3 font-medium text-gray-900"><?= htmlspecialchars($log['admin_name'] ?? 'System') ?></td>
                <td class="px-5 py-3"><span class="font-medium text-purple-700 bg-purple-50 px-2 py-0.5 rounded text-xs border border-purple-100"><?= htmlspecialchars($log['action']) ?></span></td>
                <td class="px-5 py-3 text-gray-600"><?= htmlspecialchars(($log['target_type'] ?? '') . '#' . ($log['target_id'] ?? '')) ?></td>
                <td class="px-5 py-3 text-gray-500 break-all max-w-xs"><?= htmlspecialchars($log['metadata'] ?? '-') ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>

<script>
function switchAdminTab(tabName, btn) {
    document.querySelectorAll('.admin-tab-content').forEach(function(el) {
        el.classList.add('hidden');
    });

    document.querySelectorAll('.admin-tab-btn').forEach(function(b) {
        b.classList.remove('border-purple-600', 'text-purple-600', 'font-semibold');
        b.classList.add('border-transparent', 'text-gray-500', 'font-medium');
    });

    var target = document.getElementById('tab-' + tabName);
    if (target) {
        target.classList.remove('hidden');
    }

    btn.classList.remove('border-transparent', 'text-gray-500', 'font-medium');
    btn.classList.add('border-purple-600', 'text-purple-600', 'font-semibold');
}

function filterUserTable() {
    var input = document.getElementById('user-search-input').value.toLowerCase();
    var rows = document.querySelectorAll('#user-table .user-row');
    rows.forEach(function(row) {
        var name = row.querySelector('.user-name').innerText.toLowerCase();
        var email = row.querySelector('.user-email').innerText.toLowerCase();
        if (name.includes(input) || email.includes(input)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
