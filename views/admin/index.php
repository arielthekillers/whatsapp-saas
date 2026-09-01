<?php $title = 'Admin Panel'; require __DIR__ . '/../layouts/header.php'; require __DIR__ . '/../layouts/nav.php'; ?>
<div class="max-w-6xl mx-auto px-4 py-8">
  <div class="mb-8">
    <h1 class="text-2xl font-bold tracking-tight text-gray-900 font-display">System Administration</h1>
    <p class="text-gray-500 text-sm mt-1">Pantau seluruh pengguna, statistik pendapatan, antrean job worker, dan kelola langganan customer.</p>
  </div>

  <?php if (!empty($success)): ?>
    <div class="mb-6 rounded-lg bg-green-50 border border-green-200 p-4 text-sm text-green-700 font-medium">
      <?= htmlspecialchars($success) ?>
    </div>
  <?php endif; ?>

  <?php if (!empty($error)): ?>
    <div class="mb-6 rounded-lg bg-red-50 border border-red-200 p-4 text-sm text-red-700">
      <?= htmlspecialchars($error) ?>
    </div>
  <?php endif; ?>

  <!-- Stats Grid -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
    <!-- Stat 1: Total Users -->
    <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm flex items-center justify-between">
      <div>
        <p class="text-xs font-bold uppercase tracking-wider text-gray-400">Total Customer</p>
        <h3 class="text-2xl font-black text-gray-800 font-display mt-1"><?= number_format($totalUsers) ?></h3>
      </div>
      <div class="p-3 bg-purple-50 text-purple-600 rounded-xl">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
        </svg>
      </div>
    </div>

    <!-- Stat 2: Sessions -->
    <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm flex items-center justify-between">
      <div>
        <p class="text-xs font-bold uppercase tracking-wider text-gray-400">WhatsApp Sessions</p>
        <h3 class="text-2xl font-black text-gray-800 font-display mt-1"><?= number_format($totalSessions) ?></h3>
      </div>
      <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
        </svg>
      </div>
    </div>

    <!-- Stat 3: Pendapatan -->
    <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm flex items-center justify-between">
      <div>
        <p class="text-xs font-bold uppercase tracking-wider text-gray-400">Total Pendapatan</p>
        <h3 class="text-2xl font-black text-gray-800 font-display mt-1">Rp <?= number_format($totalRevenue, 0, ',', '.') ?></h3>
      </div>
      <div class="p-3 bg-green-50 text-green-600 rounded-xl">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
      </div>
    </div>

    <!-- Stat 4: Pending Payments -->
    <div class="bg-white rounded-2xl p-6 border-2 <?= $pendingPayments > 0 ? 'border-yellow-300 shadow-yellow-100' : 'border-gray-200' ?> shadow-sm flex items-center justify-between">
      <div>
        <p class="text-xs font-bold uppercase tracking-wider text-gray-400">Menunggu Verifikasi</p>
        <h3 class="text-2xl font-black <?= $pendingPayments > 0 ? 'text-yellow-600' : 'text-gray-800' ?> font-display mt-1"><?= number_format($pendingPayments) ?></h3>
        <p class="text-xs text-gray-400 mt-0.5">Pembayaran pending</p>
      </div>
      <div class="p-3 <?= $pendingPayments > 0 ? 'bg-yellow-50 text-yellow-600' : 'bg-gray-50 text-gray-400' ?> rounded-xl">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
        </svg>
      </div>
    </div>
  </div>

  <!-- ===== PENDING PAYMENT APPROVALS ===== -->
  <?php if (!empty($pendingList)): ?>
  <div class="mb-8">
    <div class="flex items-center gap-2 mb-4">
      <h2 class="text-lg font-bold text-gray-800 font-display">Pembayaran Menunggu Verifikasi</h2>
      <span class="bg-yellow-100 text-yellow-700 text-xs font-extrabold px-2.5 py-0.5 rounded-full"><?= count($pendingList) ?> pending</span>
    </div>
    <div class="space-y-3">
      <?php foreach ($pendingList as $pmt): ?>
        <?php
          $parts  = explode(':', $pmt['provider'] ?? '');
          $months = isset($parts[1]) ? (int)$parts[1] : 1;
          $label  = match($months) { 3 => '3 Bulan', 6 => '6 Bulan', 12 => '1 Tahun', default => '1 Bulan' };
          $isVerifying = ($pmt['status'] === 'verifying');
        ?>
        <div class="bg-white rounded-2xl border-2 <?= $isVerifying ? 'border-blue-200' : 'border-yellow-100' ?> shadow-sm p-5">
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <!-- Info -->
            <div class="flex-1">
              <div class="flex items-center gap-2 mb-1">
                <span class="text-xs font-bold <?= $isVerifying ? 'bg-blue-50 text-blue-700' : 'bg-yellow-50 text-yellow-700' ?> px-2 py-0.5 rounded-full">
                  <?= $isVerifying ? '🔍 Dikonfirmasi User' : '⏳ Menunggu Konfirmasi' ?>
                </span>
                <span class="text-xs text-gray-400 font-mono"><?= htmlspecialchars($pmt['external_id']) ?></span>
              </div>
              <p class="font-bold text-gray-800"><?= htmlspecialchars($pmt['user_name']) ?> <span class="font-normal text-gray-400 text-sm">(<?= htmlspecialchars($pmt['user_email']) ?>)</span></p>
              <p class="text-sm text-gray-500 mt-0.5">
                Paket <strong class="text-gray-800"><?= htmlspecialchars($pmt['plan_name']) ?></strong> — <?= $label ?> — 
                <span class="font-bold text-purple-600">Rp <?= number_format((float)$pmt['amount'], 0, ',', '.') ?></span>
              </p>
              <?php if ($pmt['transfer_note']): ?>
                <div class="mt-2 bg-blue-50 border border-blue-100 rounded-lg px-3 py-1.5 text-xs text-blue-700">
                  <strong>Bukti transfer:</strong> <?= htmlspecialchars($pmt['transfer_note']) ?>
                </div>
              <?php endif; ?>
              <p class="text-[11px] text-gray-400 mt-1">Dibuat: <?= htmlspecialchars($pmt['created_at']) ?></p>
            </div>
            <!-- Actions -->
            <div class="flex gap-2 flex-shrink-0">
              <!-- Approve -->
              <form method="POST" action="<?= url('/admin/payment/approve') ?>" onsubmit="return confirm('Setujui pembayaran ini dan aktifkan paket?')">
                <?= \App\Helpers\Csrf::field() ?>
                <input type="hidden" name="payment_id" value="<?= (int)$pmt['id'] ?>">
                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white text-xs font-bold px-4 py-2 rounded-xl transition-all hover:scale-105">
                  ✅ Setujui
                </button>
              </form>
              <!-- Reject -->
              <form method="POST" action="<?= url('/admin/payment/reject') ?>" onsubmit="var r=prompt('Alasan penolakan:','Transfer tidak sesuai');if(!r)return false;this.querySelector('[name=reason]').value=r;return true;">
                <?= \App\Helpers\Csrf::field() ?>
                <input type="hidden" name="payment_id" value="<?= (int)$pmt['id'] ?>">
                <input type="hidden" name="reason" value="">
                <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-600 text-xs font-bold px-4 py-2 rounded-xl border border-red-200 transition-all">
                  ❌ Tolak
                </button>
              </form>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
  <?php else: ?>
  <div class="mb-8 bg-green-50 border border-green-200 rounded-2xl p-4 text-sm text-green-700 flex items-center gap-2">
    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
    Tidak ada pembayaran yang menunggu verifikasi saat ini.
  </div>
  <?php endif; ?>

  <!-- User Management Section -->
  <h2 class="text-lg font-bold text-gray-800 mb-4 font-display">Daftar Pengguna &amp; Override Paket</h2>
  <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden overflow-x-auto">
    <table class="w-full text-sm">
      <thead class="bg-gray-50 text-left text-gray-500">
        <tr>
          <th class="px-6 py-3 font-semibold">User</th>
          <th class="px-6 py-3 font-semibold">Status User</th>
          <th class="px-6 py-3 font-semibold">Paket Aktif</th>
          <th class="px-6 py-3 font-semibold">Sisa Kuota Pesan</th>
          <th class="px-6 py-3 font-semibold">Berlaku Sampai</th>
          <th class="px-6 py-3">Ubah Paket Manual</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100">
        <?php foreach ($usersList as $usr): ?>
          <tr class="hover:bg-gray-50/50 transition-colors">
            <td class="px-6 py-4">
              <p class="font-bold text-gray-800"><?= htmlspecialchars($usr['name']) ?></p>
              <p class="text-xs text-gray-500 font-mono"><?= htmlspecialchars($usr['email']) ?></p>
            </td>
            <td class="px-6 py-4">
              <?php
                $statusColor = match($usr['user_status']) {
                  'active' => 'bg-green-50 text-green-700',
                  default => 'bg-red-50 text-red-700'
                };
              ?>
              <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold <?= $statusColor ?>"><?= htmlspecialchars($usr['user_status']) ?></span>
            </td>
            <td class="px-6 py-4">
              <span class="font-semibold text-purple-700 bg-purple-50 px-2 py-0.5 rounded-full text-xs"><?= htmlspecialchars($usr['plan_name'] ?? 'Tidak Aktif') ?></span>
            </td>
            <td class="px-6 py-4 text-gray-600 font-medium">
              <?php if ($usr['plan_id']): ?>
                <?= number_format((float)$usr['messages_used']) ?> / <?= number_format((float)$usr['messages_limit']) ?>
              <?php else: ?>
                -
              <?php endif; ?>
            </td>
            <td class="px-6 py-4 text-xs text-gray-500">
              <?= $usr['end_at'] ? htmlspecialchars($usr['end_at']) : '-' ?>
            </td>
            <td class="px-6 py-4">
              <form method="POST" action="<?= url('/admin/plan/update') ?>" class="flex gap-2 items-center">
                <?= \App\Helpers\Csrf::field() ?>
                <input type="hidden" name="user_id" value="<?= (int) $usr['user_id'] ?>">
                <select name="plan_id" class="text-xs rounded border-gray-300 py-1 focus:ring-purple-500">
                  <?php foreach ($allPlans as $pl): ?>
                    <option value="<?= (int) $pl['id'] ?>" <?= $usr['plan_id'] === $pl['id'] ? 'selected' : '' ?>><?= htmlspecialchars($pl['name']) ?></option>
                  <?php endforeach; ?>
                </select>
                <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white text-[11px] font-bold px-2.5 py-1.5 rounded transition-all">Simpan</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
