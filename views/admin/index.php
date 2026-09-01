<?php $title = 'Admin Panel'; require __DIR__ . '/../layouts/header.php'; require __DIR__ . '/../layouts/nav.php'; ?>
<div class="max-w-7xl mx-auto px-4 py-8 space-y-8">
  
  <!-- Header Banner -->
  <div class="relative overflow-hidden bg-gradient-to-r from-purple-700 via-indigo-700 to-blue-600 rounded-3xl p-8 text-white shadow-2xl shadow-purple-500/10">
    <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
    <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
      <div>
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 backdrop-blur-md text-purple-100 text-xs font-semibold mb-3 border border-white/10">
          <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
          System Administration &amp; Monitoring
        </div>
        <h1 class="text-3xl font-extrabold tracking-tight font-display">Dashboard Admin Wapify</h1>
        <p class="text-purple-100 text-sm mt-1 max-w-xl">Pantau statistik pengguna, verifikasi pembayaran manual transfer bank, dan kelola alokasi paket pelanggan secara real-time.</p>
      </div>
      <div class="flex items-center gap-3">
        <a href="<?= url('/admin') ?>" class="bg-white/10 hover:bg-white/20 text-white font-semibold text-xs px-4 py-2.5 rounded-xl backdrop-blur-md border border-white/20 transition-all flex items-center gap-2">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
          Refresh Data
        </a>
      </div>
    </div>
  </div>

  <?php if (!empty($success)): ?>
    <div class="rounded-2xl bg-emerald-50 border border-emerald-200 p-4 text-sm text-emerald-800 font-semibold flex items-center gap-3 shadow-sm animate-toast">
      <span class="text-lg">✅</span>
      <span><?= htmlspecialchars($success) ?></span>
    </div>
  <?php endif; ?>

  <?php if (!empty($error)): ?>
    <div class="rounded-2xl bg-rose-50 border border-rose-200 p-4 text-sm text-rose-800 font-semibold flex items-center gap-3 shadow-sm animate-toast">
      <span class="text-lg">❌</span>
      <span><?= htmlspecialchars($error) ?></span>
    </div>
  <?php endif; ?>

  <!-- Stats Grid -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
    <!-- Stat 1: Total Users -->
    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-xl shadow-gray-200/50 hover:shadow-2xl transition-all duration-300 group">
      <div class="flex items-center justify-between">
        <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Total Customer</span>
        <div class="p-3 bg-purple-50 text-purple-600 rounded-2xl group-hover:scale-110 transition-transform">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
          </svg>
        </div>
      </div>
      <h3 class="text-3xl font-extrabold text-gray-900 font-display mt-3"><?= number_format($totalUsers) ?></h3>
      <p class="text-xs text-gray-400 mt-1 flex items-center gap-1"><span class="text-emerald-500 font-bold">✓</span> Pengguna terdaftar</p>
    </div>

    <!-- Stat 2: Sessions -->
    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-xl shadow-gray-200/50 hover:shadow-2xl transition-all duration-300 group">
      <div class="flex items-center justify-between">
        <span class="text-xs font-bold uppercase tracking-wider text-gray-400">WhatsApp Sessions</span>
        <div class="p-3 bg-blue-50 text-blue-600 rounded-2xl group-hover:scale-110 transition-transform">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
          </svg>
        </div>
      </div>
      <h3 class="text-3xl font-extrabold text-gray-900 font-display mt-3"><?= number_format($totalSessions) ?></h3>
      <p class="text-xs text-gray-400 mt-1 flex items-center gap-1"><span class="text-blue-500 font-bold">⚡</span> Sesi WA aktif</p>
    </div>

    <!-- Stat 3: Pendapatan -->
    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-xl shadow-gray-200/50 hover:shadow-2xl transition-all duration-300 group">
      <div class="flex items-center justify-between">
        <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Total Pendapatan</span>
        <div class="p-3 bg-emerald-50 text-emerald-600 rounded-2xl group-hover:scale-110 transition-transform">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
      </div>
      <h3 class="text-2xl font-extrabold text-gray-900 font-display mt-3">Rp <?= number_format($totalRevenue, 0, ',', '.') ?></h3>
      <p class="text-xs text-gray-400 mt-1 flex items-center gap-1"><span class="text-emerald-500 font-bold">💰</span> Transaksi terverifikasi</p>
    </div>

    <!-- Stat 4: Pending Payments -->
    <div class="bg-white rounded-2xl p-6 border-2 <?= $pendingPayments > 0 ? 'border-amber-300 shadow-amber-500/10' : 'border-gray-100' ?> shadow-xl shadow-gray-200/50 hover:shadow-2xl transition-all duration-300 group">
      <div class="flex items-center justify-between">
        <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Menunggu Verifikasi</span>
        <div class="p-3 <?= $pendingPayments > 0 ? 'bg-amber-100 text-amber-700 animate-bounce' : 'bg-gray-50 text-gray-400' ?> rounded-2xl group-hover:scale-110 transition-transform">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
          </svg>
        </div>
      </div>
      <h3 class="text-3xl font-extrabold <?= $pendingPayments > 0 ? 'text-amber-600' : 'text-gray-900' ?> font-display mt-3"><?= number_format($pendingPayments) ?></h3>
      <p class="text-xs text-amber-600 font-semibold mt-1">Pembayaran pending</p>
    </div>
  </div>

  <!-- ===== PENDING PAYMENT APPROVALS ===== -->
  <div class="bg-white rounded-3xl border border-gray-100 shadow-xl shadow-gray-200/50 p-6 md:p-8">
    <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100">
      <div>
        <h2 class="text-xl font-extrabold text-gray-900 font-display flex items-center gap-2">
          <span>Verifikasi Pembayaran Transfer Bank</span>
          <?php if (!empty($pendingList)): ?>
            <span class="bg-amber-100 text-amber-800 text-xs font-extrabold px-3 py-1 rounded-full animate-pulse"><?= count($pendingList) ?> Perlu Tindakan</span>
          <?php endif; ?>
        </h2>
        <p class="text-gray-400 text-xs mt-0.5">Setujui konfirmasi pembayaran untuk langsung mengaktifkan masa berlaku paket pelanggan.</p>
      </div>
    </div>

    <?php if (!empty($pendingList)): ?>
      <div class="space-y-4">
        <?php foreach ($pendingList as $pmt): ?>
          <?php
            $parts  = explode(':', $pmt['provider'] ?? '');
            $months = isset($parts[1]) ? (int)$parts[1] : 1;
            $label  = match($months) { 3 => '3 Bulan (-5%)', 6 => '6 Bulan (-10%)', 12 => '1 Tahun (-20%)', default => '1 Bulan' };
            $isVerifying = ($pmt['status'] === 'verifying');
          ?>
          <div class="rounded-2xl border-2 <?= $isVerifying ? 'border-blue-200 bg-blue-50/20' : 'border-amber-200 bg-amber-50/20' ?> p-5 transition-all hover:shadow-md">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
              <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center font-extrabold text-white text-lg shrink-0 <?= $isVerifying ? 'bg-gradient-to-br from-blue-500 to-indigo-600' : 'bg-gradient-to-br from-amber-500 to-orange-600' ?>">
                  <?= strtoupper(substr($pmt['user_name'], 0, 1)) ?>
                </div>
                <div>
                  <div class="flex items-center gap-2 mb-1 flex-wrap">
                    <span class="text-xs font-bold <?= $isVerifying ? 'bg-blue-100 text-blue-800' : 'bg-amber-100 text-amber-800' ?> px-2.5 py-0.5 rounded-full flex items-center gap-1">
                      <span class="w-1.5 h-1.5 rounded-full <?= $isVerifying ? 'bg-blue-600 animate-ping' : 'bg-amber-600 animate-ping' ?>"></span>
                      <?= $isVerifying ? 'Dikonfirmasi Pelanggan' : 'Menunggu Transfer' ?>
                    </span>
                    <span class="text-xs text-gray-400 font-mono bg-gray-100 px-2 py-0.5 rounded-md">ID: <?= htmlspecialchars($pmt['external_id']) ?></span>
                  </div>
                  <p class="font-bold text-gray-900 text-base"><?= htmlspecialchars($pmt['user_name']) ?> <span class="font-normal text-gray-400 text-xs">(<?= htmlspecialchars($pmt['user_email']) ?>)</span></p>
                  <p class="text-sm text-gray-600 mt-1">
                    Membeli Paket <strong class="text-purple-700 font-bold bg-purple-50 px-2 py-0.5 rounded-md border border-purple-100"><?= htmlspecialchars($pmt['plan_name']) ?></strong> — <?= $label ?>
                  </p>
                  <?php if ($pmt['transfer_note']): ?>
                    <div class="mt-2.5 bg-white border border-blue-200 rounded-xl p-3 text-xs text-blue-900 shadow-sm">
                      <strong class="font-bold text-blue-700">Catatan Transfer Pelanggan:</strong>
                      <p class="mt-0.5 font-mono text-gray-700 break-all"><?= htmlspecialchars($pmt['transfer_note']) ?></p>
                    </div>
                  <?php endif; ?>
                  <p class="text-[11px] text-gray-400 mt-2">Waktu Order: <?= htmlspecialchars($pmt['created_at']) ?></p>
                </div>
              </div>

              <div class="flex items-center gap-4 shrink-0 border-t md:border-t-0 pt-3 md:pt-0">
                <div class="text-right">
                  <span class="text-xs text-gray-400 block font-medium">Total Nominal</span>
                  <span class="text-xl font-extrabold text-purple-700 font-display">Rp <?= number_format((float)$pmt['amount'], 0, ',', '.') ?></span>
                </div>
                <div class="flex gap-2">
                  <!-- Approve -->
                  <form method="POST" action="<?= url('/admin/payment/approve') ?>" onsubmit="return confirm('Setujui pembayaran ini dan aktifkan paket?')">
                    <?= \App\Helpers\Csrf::field() ?>
                    <input type="hidden" name="payment_id" value="<?= (int)$pmt['id'] ?>">
                    <button type="submit" class="bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white text-xs font-extrabold px-4 py-2.5 rounded-xl shadow-md shadow-emerald-500/20 transition-all hover:scale-[1.03]">
                      ✅ Setujui
                    </button>
                  </form>
                  <!-- Reject -->
                  <form method="POST" action="<?= url('/admin/payment/reject') ?>" onsubmit="var r=prompt('Alasan penolakan:','Transfer tidak sesuai');if(!r)return false;this.querySelector('[name=reason]').value=r;return true;">
                    <?= \App\Helpers\Csrf::field() ?>
                    <input type="hidden" name="payment_id" value="<?= (int)$pmt['id'] ?>">
                    <input type="hidden" name="reason" value="">
                    <button type="submit" class="bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-extrabold px-4 py-2.5 rounded-xl border border-rose-200 transition-all">
                      ❌ Tolak
                    </button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="bg-emerald-50/50 border border-emerald-200/60 rounded-2xl p-6 text-sm text-emerald-800 flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-lg font-bold shrink-0">✓</div>
        <div>
          <p class="font-bold">Semua Pembayaran Berhasil Divalidasi</p>
          <p class="text-xs text-emerald-600 mt-0.5">Tidak ada konfirmasi pembayaran manual yang tertunda saat ini.</p>
        </div>
      </div>
    <?php endif; ?>
  </div>

  <!-- User Management & Plan Override Section -->
  <div class="bg-white rounded-3xl border border-gray-100 shadow-xl shadow-gray-200/50 p-6 md:p-8">
    <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100">
      <div>
        <h2 class="text-xl font-extrabold text-gray-900 font-display">Daftar Pengguna &amp; Override Paket</h2>
        <p class="text-gray-400 text-xs mt-0.5">Kelola status keanggotaan pengguna dan sesuaikan alokasi paket secara manual bila diperlukan.</p>
      </div>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 text-gray-400 uppercase text-[11px] font-bold tracking-wider">
          <tr>
            <th class="px-6 py-4 rounded-l-xl">Pelanggan</th>
            <th class="px-6 py-4">Status User</th>
            <th class="px-6 py-4">Paket Aktif</th>
            <th class="px-6 py-4">Pemakaian Kuota</th>
            <th class="px-6 py-4">Masa Berlaku</th>
            <th class="px-6 py-4 rounded-r-xl text-right">Ubah Paket Manual</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          <?php foreach ($usersList as $usr): ?>
            <tr class="hover:bg-gray-50/60 transition-colors">
              <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                  <div class="w-9 h-9 rounded-xl bg-purple-100 text-purple-700 font-bold flex items-center justify-center text-sm shrink-0">
                    <?= strtoupper(substr($usr['name'], 0, 1)) ?>
                  </div>
                  <div>
                    <p class="font-bold text-gray-900 text-sm"><?= htmlspecialchars($usr['name']) ?></p>
                    <p class="text-xs text-gray-400 font-mono"><?= htmlspecialchars($usr['email']) ?></p>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4">
                <?php
                  $isUserActive = ($usr['user_status'] === 'active');
                ?>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold <?= $isUserActive ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' ?>">
                  <span class="w-1.5 h-1.5 rounded-full <?= $isUserActive ? 'bg-emerald-500' : 'bg-rose-500' ?>"></span>
                  <?= htmlspecialchars($usr['user_status']) ?>
                </span>
              </td>
              <td class="px-6 py-4">
                <span class="inline-block font-extrabold text-xs text-purple-700 bg-purple-50 border border-purple-100 px-3 py-1 rounded-full">
                  <?= htmlspecialchars($usr['plan_name'] ?? 'Tidak Aktif') ?>
                </span>
              </td>
              <td class="px-6 py-4">
                <?php if ($usr['plan_id']): ?>
                  <?php 
                    $used = (float) $usr['messages_used'];
                    $limit = (float) $usr['messages_limit'];
                    $pct = $limit > 0 ? min(100, round(($used / $limit) * 100)) : 0;
                  ?>
                  <div class="space-y-1 max-w-[140px]">
                    <div class="flex justify-between text-xs font-semibold text-gray-700">
                      <span><?= number_format($used) ?></span>
                      <span class="text-gray-400">/ <?= number_format($limit) ?></span>
                    </div>
                    <div class="w-full bg-gray-100 h-1.5 rounded-full overflow-hidden">
                      <div class="bg-gradient-to-r from-purple-500 to-blue-500 h-full rounded-full" style="width: <?= $pct ?>%"></div>
                    </div>
                  </div>
                <?php else: ?>
                  <span class="text-gray-400 text-xs">-</span>
                <?php endif; ?>
              </td>
              <td class="px-6 py-4 text-xs font-medium text-gray-600">
                <?= $usr['end_at'] ? htmlspecialchars($usr['end_at']) : '<span class="text-gray-400">-</span>' ?>
              </td>
              <td class="px-6 py-4 text-right">
                <form method="POST" action="<?= url('/admin/plan/update') ?>" class="inline-flex gap-2 items-center justify-end">
                  <?= \App\Helpers\Csrf::field() ?>
                  <input type="hidden" name="user_id" value="<?= (int) $usr['user_id'] ?>">
                  <select name="plan_id" class="text-xs rounded-xl border-gray-300 py-1.5 px-2.5 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-gray-50 font-semibold text-gray-700">
                    <?php foreach ($allPlans as $pl): ?>
                      <option value="<?= (int) $pl['id'] ?>" <?= $usr['plan_id'] === $pl['id'] ? 'selected' : '' ?>><?= htmlspecialchars($pl['name']) ?></option>
                    <?php endforeach; ?>
                  </select>
                  <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold px-3 py-1.5 rounded-xl shadow transition-all hover:scale-105">
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
<?php require __DIR__ . '/../layouts/footer.php'; ?>
