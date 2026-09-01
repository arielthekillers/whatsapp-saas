<?php $title = 'Admin Panel'; require __DIR__ . '/../layouts/header.php'; require __DIR__ . '/../layouts/nav.php'; ?>
<div class="max-w-7xl mx-auto px-4 py-8 space-y-8">
  
  <!-- Header Banner -->
  <div class="bg-purple-700 rounded-2xl p-6 text-white shadow-lg flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
      <h1 class="text-2xl font-bold font-display">System Administration</h1>
      <p class="text-purple-100 text-sm mt-1">Pantau seluruh pengguna, statistik pendapatan, server WAHA, antrean job, dan audit log.</p>
    </div>
    <div class="flex items-center gap-3 flex-wrap">
      <a href="<?= url('/admin/export-payments') ?>" class="bg-white text-purple-700 font-bold text-xs px-4 py-2 rounded-lg transition-all hover:bg-purple-50 shadow-sm inline-flex items-center gap-1.5">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        Export Transaksi CSV
      </a>
      <a href="<?= url('/admin') ?>" class="bg-white/20 hover:bg-white/30 text-white font-semibold text-xs px-4 py-2 rounded-lg transition-all inline-flex items-center gap-1.5">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
        Refresh
      </a>
    </div>
  </div>

  <?php if (!empty($success)): ?>
    <div class="rounded-xl bg-green-50 border border-green-200 p-4 text-sm text-green-800 font-semibold flex items-center gap-2">
      <span>✅</span>
      <span><?= htmlspecialchars($success) ?></span>
    </div>
  <?php endif; ?>

  <?php if (!empty($error)): ?>
    <div class="rounded-xl bg-red-50 border border-red-200 p-4 text-sm text-red-800 font-semibold flex items-center gap-2">
      <span>❌</span>
      <span><?= htmlspecialchars($error) ?></span>
    </div>
  <?php endif; ?>

  <!-- Stats Grid -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
    <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm flex items-center justify-between">
      <div>
        <p class="text-xs font-bold uppercase tracking-wider text-gray-400">Total Customer</p>
        <h3 class="text-2xl font-bold text-gray-900 font-display mt-1"><?= number_format($totalUsers) ?></h3>
      </div>
      <div class="p-3 bg-purple-50 text-purple-600 rounded-xl">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
        </svg>
      </div>
    </div>

    <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm flex items-center justify-between">
      <div>
        <p class="text-xs font-bold uppercase tracking-wider text-gray-400">WhatsApp Sessions</p>
        <h3 class="text-2xl font-bold text-gray-900 font-display mt-1"><?= number_format($totalSessions) ?></h3>
      </div>
      <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
        </svg>
      </div>
    </div>

    <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm flex items-center justify-between">
      <div>
        <p class="text-xs font-bold uppercase tracking-wider text-gray-400">Total Pendapatan</p>
        <h3 class="text-2xl font-bold text-gray-900 font-display mt-1">Rp <?= number_format($totalRevenue, 0, ',', '.') ?></h3>
      </div>
      <div class="p-3 bg-green-50 text-green-600 rounded-xl">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
      </div>
    </div>

    <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm flex items-center justify-between">
      <div>
        <p class="text-xs font-bold uppercase tracking-wider text-gray-400">Menunggu Verifikasi</p>
        <h3 class="text-2xl font-bold <?= $pendingPayments > 0 ? 'text-amber-600' : 'text-gray-900' ?> font-display mt-1"><?= number_format($pendingPayments) ?></h3>
      </div>
      <div class="p-3 bg-yellow-50 text-yellow-600 rounded-xl">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
        </svg>
      </div>
    </div>
  </div>

  <!-- System Health & Job Queue Cards -->
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- WAHA Health Check -->
    <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm space-y-3">
      <div class="flex items-center justify-between">
        <h3 class="font-bold text-gray-900 text-sm font-display flex items-center gap-2">
          <span>⚡ Status Server WAHA API</span>
        </h3>
        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold <?= $wahaStatus['status'] === 'ONLINE' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
          <?= $wahaStatus['status'] ?>
        </span>
      </div>
      <div class="text-xs space-y-1 text-gray-600 bg-gray-50 p-3 rounded-lg border border-gray-200">
        <p><strong>Base URL:</strong> <code class="font-mono text-purple-700"><?= htmlspecialchars($wahaStatus['url']) ?></code></p>
        <p><strong>Latency Ping:</strong> <?= $wahaStatus['latency'] ?> ms</p>
      </div>
    </div>

    <!-- Job Queue Stats -->
    <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm space-y-3">
      <div class="flex items-center justify-between">
        <h3 class="font-bold text-gray-900 text-sm font-display flex items-center gap-2">
          <span>⚙️ Background Job Queue</span>
        </h3>
        <?php if ($jobStats['failed'] > 0): ?>
          <form method="POST" action="<?= url('/admin/jobs/retry-failed') ?>">
            <?= \App\Helpers\Csrf::field() ?>
            <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white text-[11px] font-bold px-3 py-1 rounded-lg transition-all shadow-sm">
              Retry <?= $jobStats['failed'] ?> Job Gagal
            </button>
          </form>
        <?php endif; ?>
      </div>
      <div class="grid grid-cols-3 gap-2 text-center text-xs">
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-2">
          <span class="text-gray-400 block text-[10px] uppercase font-bold">Pending</span>
          <span class="font-bold text-yellow-800 text-base"><?= number_format($jobStats['pending']) ?></span>
        </div>
        <div class="bg-green-50 border border-green-200 rounded-lg p-2">
          <span class="text-gray-400 block text-[10px] uppercase font-bold">Completed</span>
          <span class="font-bold text-green-800 text-base"><?= number_format($jobStats['completed']) ?></span>
        </div>
        <div class="bg-red-50 border border-red-200 rounded-lg p-2">
          <span class="text-gray-400 block text-[10px] uppercase font-bold">Failed</span>
          <span class="font-bold text-red-800 text-base"><?= number_format($jobStats['failed']) ?></span>
        </div>
      </div>
    </div>
  </div>

  <!-- Pengumuman Sistem Banner Broadcast -->
  <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm space-y-4">
    <div class="flex items-center justify-between">
      <div>
        <h3 class="font-bold text-gray-900 text-sm font-display">📢 Broadcast Pengumuman Sistem</h3>
        <p class="text-xs text-gray-500">Pesan ini akan tampil di bagian atas dashboard seluruh pelanggan.</p>
      </div>
      <?php if (!empty($activeAnnouncement)): ?>
        <form method="POST" action="<?= url('/admin/announcement/delete') ?>">
          <?= \App\Helpers\Csrf::field() ?>
          <button type="submit" class="text-red-600 hover:text-red-800 text-xs font-bold border border-red-300 px-3 py-1 rounded-lg hover:bg-red-50">
            Hapus Broadcast
          </button>
        </form>
      <?php endif; ?>
    </div>

    <form method="POST" action="<?= url('/admin/announcement') ?>" class="flex flex-col sm:flex-row gap-3 items-end">
      <?= \App\Helpers\Csrf::field() ?>
      <div class="flex-1 w-full">
        <label class="block text-xs font-bold text-gray-600 mb-1">Pesan Pengumuman</label>
        <input type="text" name="message" value="<?= htmlspecialchars($activeAnnouncement['message'] ?? '') ?>" placeholder="mis. Maintenance server WAHA tanggal 5 September jam 02:00 WIB" class="w-full text-xs rounded-lg border-gray-300 px-3 py-2 focus:ring-purple-500">
      </div>
      <div class="w-full sm:w-36">
        <label class="block text-xs font-bold text-gray-600 mb-1">Tipe Banner</label>
        <select name="type" class="w-full text-xs rounded-lg border-gray-300 px-3 py-2 bg-gray-50 font-semibold focus:ring-purple-500">
          <option value="info" <?= ($activeAnnouncement['type'] ?? '') === 'info' ? 'selected' : '' ?>>Info (Ungu)</option>
          <option value="warning" <?= ($activeAnnouncement['type'] ?? '') === 'warning' ? 'selected' : '' ?>>Warning (Kuning)</option>
          <option value="danger" <?= ($activeAnnouncement['type'] ?? '') === 'danger' ? 'selected' : '' ?>>Bahaya (Merah)</option>
        </select>
      </div>
      <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold px-4 py-2 rounded-lg shadow-sm whitespace-nowrap">
        Publikasikan
      </button>
    </form>
  </div>

  <!-- Verifikasi Pembayaran Transfer Bank -->
  <div class="space-y-4">
    <div class="flex items-center justify-between">
      <h2 class="text-lg font-bold text-gray-900 font-display flex items-center gap-2">
        <span>Verifikasi Pembayaran Transfer Bank</span>
        <?php if (!empty($pendingList)): ?>
          <span class="bg-amber-100 text-amber-800 text-xs font-bold px-2.5 py-0.5 rounded-full"><?= count($pendingList) ?> Perlu Verifikasi</span>
        <?php endif; ?>
      </h2>
    </div>

    <?php if (!empty($pendingList)): ?>
      <div class="space-y-3">
        <?php foreach ($pendingList as $pmt): ?>
          <?php
            $parts  = explode(':', $pmt['provider'] ?? '');
            $months = isset($parts[1]) ? (int)$parts[1] : 1;
            $label  = match($months) { 3 => '3 Bulan', 6 => '6 Bulan', 12 => '1 Tahun', default => '1 Bulan' };
            $isVerifying = ($pmt['status'] === 'verifying');
          ?>
          <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-start gap-4 flex-1">
              <div class="w-10 h-10 rounded-full bg-purple-600 text-white font-bold flex items-center justify-center text-sm shrink-0 shadow-sm">
                <?= strtoupper(substr($pmt['user_name'], 0, 1)) ?>
              </div>
              <div class="space-y-1">
                <div class="flex items-center gap-2 flex-wrap">
                  <span class="text-xs font-bold <?= $isVerifying ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800' ?> px-2.5 py-0.5 rounded-full">
                    <?= $isVerifying ? '🔍 Dikonfirmasi Pelanggan' : '⏳ Menunggu Transfer' ?>
                  </span>
                  <span class="text-xs text-gray-500 font-mono">ID: <?= htmlspecialchars($pmt['external_id']) ?></span>
                </div>
                <p class="font-bold text-gray-900 text-sm"><?= htmlspecialchars($pmt['user_name']) ?> <span class="font-normal text-gray-500 text-xs">(<?= htmlspecialchars($pmt['user_email']) ?>)</span></p>
                <p class="text-xs text-gray-600">
                  Paket: <strong class="text-purple-700 bg-purple-50 px-2 py-0.5 rounded text-xs border border-purple-100"><?= htmlspecialchars($pmt['plan_name']) ?></strong> — <?= $label ?> • Order: <?= htmlspecialchars($pmt['created_at']) ?>
                </p>
                <?php if ($pmt['transfer_note']): ?>
                  <div class="mt-2 bg-gray-50 border border-gray-200 rounded-lg p-2 text-xs text-gray-700">
                    <strong>Catatan Transfer:</strong> <?= htmlspecialchars($pmt['transfer_note']) ?>
                  </div>
                <?php endif; ?>
              </div>
            </div>

            <div class="flex items-center gap-4 shrink-0 border-t md:border-t-0 pt-3 md:pt-0">
              <div class="text-left md:text-right">
                <span class="text-[10px] text-gray-400 font-bold uppercase block">Total Nominal</span>
                <span class="text-lg font-bold text-purple-700 font-display">Rp <?= number_format((float)$pmt['amount'], 0, ',', '.') ?></span>
              </div>
              <div class="flex items-center gap-2">
                <form method="POST" action="<?= url('/admin/payment/approve') ?>" onsubmit="return confirm('Setujui pembayaran ini dan aktifkan paket?')">
                  <?= \App\Helpers\Csrf::field() ?>
                  <input type="hidden" name="payment_id" value="<?= (int)$pmt['id'] ?>">
                  <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold px-4 py-2 rounded-lg transition-all shadow-sm">
                    Setujui Pembayaran
                  </button>
                </form>

                <form method="POST" action="<?= url('/admin/payment/reject') ?>" onsubmit="var r=prompt('Alasan penolakan:','Transfer tidak sesuai');if(!r)return false;this.querySelector('[name=reason]').value=r;return true;">
                  <?= \App\Helpers\Csrf::field() ?>
                  <input type="hidden" name="payment_id" value="<?= (int)$pmt['id'] ?>">
                  <input type="hidden" name="reason" value="">
                  <button type="submit" class="bg-white hover:bg-red-50 text-red-600 text-xs font-bold px-3.5 py-2 rounded-lg border border-red-300 transition-all">
                    Tolak
                  </button>
                </form>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="bg-green-50 border border-green-200 rounded-xl p-4 text-sm text-green-800 flex items-center gap-2">
        <span>✅</span>
        <span>Tidak ada pembayaran yang menunggu verifikasi saat ini.</span>
      </div>
    <?php endif; ?>
  </div>

  <!-- User Management, Status Control & Plan Override -->
  <div class="space-y-4">
    <h2 class="text-lg font-bold text-gray-900 font-display">Daftar Pengguna &amp; Kontrol Akses</h2>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden overflow-x-auto">
      <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 text-gray-500 uppercase text-[11px] font-bold">
          <tr>
            <th class="px-6 py-3">Pelanggan</th>
            <th class="px-6 py-3">Status User</th>
            <th class="px-6 py-3">Paket Aktif</th>
            <th class="px-6 py-3">Pemakaian Kuota</th>
            <th class="px-6 py-3">Masa Berlaku</th>
            <th class="px-6 py-3 text-right">Ubah Paket Manual</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          <?php foreach ($usersList as $usr): ?>
            <tr class="hover:bg-gray-50 transition-colors">
              <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                  <div class="w-8 h-8 rounded-full bg-purple-600 text-white font-bold flex items-center justify-center text-xs shrink-0">
                    <?= strtoupper(substr($usr['name'], 0, 1)) ?>
                  </div>
                  <div>
                    <p class="font-bold text-gray-900 text-sm"><?= htmlspecialchars($usr['name']) ?></p>
                    <p class="text-xs text-gray-500 font-mono"><?= htmlspecialchars($usr['email']) ?></p>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4">
                <form method="POST" action="<?= url('/admin/user/status') ?>" class="inline-block">
                  <?= \App\Helpers\Csrf::field() ?>
                  <input type="hidden" name="user_id" value="<?= (int) $usr['user_id'] ?>">
                  <select name="status" onchange="if(confirm('Ubah status user ini?')) this.form.submit();" class="text-xs font-bold rounded-lg border-gray-300 py-1 px-2 <?= $usr['user_status'] === 'active' ? 'bg-green-50 text-green-800' : 'bg-red-50 text-red-800' ?>">
                    <option value="active" <?= $usr['user_status'] === 'active' ? 'selected' : '' ?>>active</option>
                    <option value="suspended" <?= $usr['user_status'] === 'suspended' ? 'selected' : '' ?>>suspended</option>
                    <option value="banned" <?= $usr['user_status'] === 'banned' ? 'selected' : '' ?>>banned</option>
                  </select>
                </form>
              </td>
              <td class="px-6 py-4">
                <span class="font-bold text-xs text-purple-700 bg-purple-50 border border-purple-100 px-2.5 py-0.5 rounded-full">
                  <?= htmlspecialchars($usr['plan_name'] ?? 'Tidak Aktif') ?>
                </span>
              </td>
              <td class="px-6 py-4 text-gray-600 font-medium text-xs">
                <?php if ($usr['plan_id']): ?>
                  <?= number_format((float)$usr['messages_used']) ?> / <?= number_format((float)$usr['messages_limit']) ?>
                <?php else: ?>
                  <span class="text-gray-400">-</span>
                <?php endif; ?>
              </td>
              <td class="px-6 py-4 text-xs font-medium text-gray-600">
                <?= $usr['end_at'] ? htmlspecialchars($usr['end_at']) : '<span class="text-gray-400">-</span>' ?>
              </td>
              <td class="px-6 py-4 text-right">
                <form method="POST" action="<?= url('/admin/plan/update') ?>" class="inline-flex gap-2 items-center justify-end">
                  <?= \App\Helpers\Csrf::field() ?>
                  <input type="hidden" name="user_id" value="<?= (int) $usr['user_id'] ?>">
                  <select name="plan_id" class="text-xs rounded-lg border-gray-300 py-1.5 px-2 bg-gray-50 font-semibold text-gray-700 focus:ring-purple-500">
                    <?php foreach ($allPlans as $pl): ?>
                      <option value="<?= (int) $pl['id'] ?>" <?= $usr['plan_id'] === $pl['id'] ? 'selected' : '' ?>><?= htmlspecialchars($pl['name']) ?></option>
                    <?php endforeach; ?>
                  </select>
                  <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold px-3 py-1.5 rounded-lg transition-all shadow-sm">
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

  <!-- Audit Logs Trail -->
  <div class="space-y-4">
    <h2 class="text-lg font-bold text-gray-900 font-display">📝 Audit Log Tindakan Admin</h2>
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden overflow-x-auto">
      <table class="w-full text-xs text-left">
        <thead class="bg-gray-50 text-gray-500 uppercase font-bold">
          <tr>
            <th class="px-4 py-3">Waktu</th>
            <th class="px-4 py-3">Admin</th>
            <th class="px-4 py-3">Tindakan (Action)</th>
            <th class="px-4 py-3">Target</th>
            <th class="px-4 py-3">Detail Metadata</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          <?php if (empty($auditLogs)): ?>
            <tr><td colspan="5" class="px-4 py-4 text-center text-gray-400">Belum ada aktivitas audit log.</td></tr>
          <?php endif; ?>
          <?php foreach ($auditLogs as $log): ?>
            <tr class="hover:bg-gray-50">
              <td class="px-4 py-3 text-gray-500 font-mono"><?= htmlspecialchars($log['created_at']) ?></td>
              <td class="px-4 py-3 font-bold text-gray-800"><?= htmlspecialchars($log['admin_name'] ?? 'System') ?></td>
              <td class="px-4 py-3"><span class="font-bold text-purple-700 bg-purple-50 px-2 py-0.5 rounded border border-purple-100"><?= htmlspecialchars($log['action']) ?></span></td>
              <td class="px-4 py-3 text-gray-600"><?= htmlspecialchars(($log['target_type'] ?? '') . '#' . ($log['target_id'] ?? '')) ?></td>
              <td class="px-4 py-3 text-gray-500 font-mono break-all"><?= htmlspecialchars($log['metadata'] ?? '-') ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
