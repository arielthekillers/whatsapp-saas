<?php $title = 'Usage & Kuota'; require __DIR__ . '/../layouts/header.php'; require __DIR__ . '/../layouts/nav.php'; ?>

<div>
  <div class="mb-8">
    <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight font-display">Penggunaan &amp; Kuota</h1>
    <p class="text-sm text-gray-500 mt-1">Pantau penggunaan pesan, sesi WhatsApp, dan masa aktif langganan Anda secara real-time.</p>
  </div>

  <?php if (!$subscription): ?>
    <div class="bg-white rounded-2xl border border-gray-200 p-10 text-center space-y-3">
      <div class="text-4xl">📦</div>
      <p class="text-gray-600 font-medium">Belum ada langganan aktif.</p>
      <p class="text-sm text-gray-400">Silakan pilih paket di halaman <a href="<?= url('/billing') ?>" class="text-purple-600 font-bold hover:underline">Billing</a> untuk mulai menggunakan layanan.</p>
    </div>

  <?php else: ?>
    <?php
      $used   = (int) $subscription['messages_used'];
      $limit  = max(1, (int) $subscription['messages_limit']);
      $pct    = min(100, (int) round($used / $limit * 100));
      $remaining = $limit - $used;

      // Warna progress bar kuota pesan berdasarkan persentase
      $barColor = $pct >= 90
        ? 'from-red-500 to-red-600'
        : ($pct >= 70 ? 'from-amber-400 to-orange-500' : 'from-purple-600 to-indigo-600');

      // Peta Skema Warna Paket (Menyesuaikan dengan halaman Billing):
      // LITE = Emerald / Green
      // PRO = Purple / Indigo
      // BUSINESS = Amber / Orange
      // ENTERPRISE = Amber / Rose
      $planNameUpper = strtoupper(trim((string)$subscription['plan_name']));
      $planTheme = match($planNameUpper) {
        'LITE' => [
          'badge'       => 'bg-emerald-100/80 text-emerald-800 border border-emerald-300 font-black',
          'progressBar' => 'from-emerald-500 to-teal-500',
          'activeText'  => 'text-emerald-700',
          'headerIconBg'=> 'bg-emerald-50 text-emerald-600',
        ],
        'BUSINESS' => [
          'badge'       => 'bg-amber-100/80 text-amber-900 border border-amber-300 font-black',
          'progressBar' => 'from-amber-500 to-orange-500',
          'activeText'  => 'text-amber-700',
          'headerIconBg'=> 'bg-amber-50 text-amber-600',
        ],
        'ENTERPRISE' => [
          'badge'       => 'bg-amber-100/80 text-amber-900 border border-amber-300 font-black',
          'progressBar' => 'from-amber-500 to-rose-500',
          'activeText'  => 'text-amber-700',
          'headerIconBg'=> 'bg-amber-50 text-amber-600',
        ],
        default => [ // PRO / Default
          'badge'       => 'bg-purple-100/80 text-purple-900 border border-purple-300 font-black',
          'progressBar' => 'from-purple-600 to-indigo-600',
          'activeText'  => 'text-purple-700',
          'headerIconBg'=> 'bg-purple-50 text-purple-600',
        ],
      };
    ?>

    <!-- Top Stats Grid dengan Desain Lebih Cantik -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
      <!-- Pesan Terkirim Hari Ini -->
      <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-xs hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-2">
          <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Hari Ini</p>
          <div class="w-7 h-7 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xs">💬</div>
        </div>
        <p class="text-3xl font-black text-gray-900 font-display"><?= number_format($sentToday) ?></p>
        <p class="text-[11px] text-gray-400 mt-1">Pesan terkirim</p>
      </div>

      <!-- Total Terkirim -->
      <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-xs hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-2">
          <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Total Terkirim</p>
          <div class="w-7 h-7 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xs">🚀</div>
        </div>
        <p class="text-3xl font-black text-gray-900 font-display"><?= number_format((int)($totals['sent'] ?? 0)) ?></p>
        <p class="text-[11px] text-gray-400 mt-1">Sejak pertama pakai</p>
      </div>

      <!-- Sisa Kuota -->
      <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-xs hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-2">
          <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Sisa Kuota</p>
          <div class="w-7 h-7 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs">📊</div>
        </div>
        <p class="text-3xl font-black <?= $pct >= 90 ? 'text-red-600' : ($pct >= 70 ? 'text-amber-600' : 'text-gray-900') ?> font-display"><?= number_format($remaining) ?></p>
        <p class="text-[11px] text-gray-400 mt-1">dari <?= number_format($limit) ?> pesan</p>
      </div>

      <!-- Sesi Aktif -->
      <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-xs hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-2">
          <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">WhatsApp Session</p>
          <div class="w-7 h-7 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs">📱</div>
        </div>
        <p class="text-3xl font-black text-gray-900 font-display">
          <?= (int)($sessionStats['working'] ?? 0) ?>
          <span class="text-base font-bold text-gray-400">/ <?= (int)($sessionStats['total'] ?? 0) ?></span>
        </p>
        <p class="text-[11px] text-gray-400 mt-1">Aktif / Total</p>
      </div>
    </div>

    <!-- Kuota Pesan & Status Langganan -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
      
      <!-- Kuota Pesan -->
      <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 flex flex-col justify-between hover:border-gray-200 transition-all">
        <div>
          <div class="flex items-center justify-between mb-4">
            <h2 class="font-extrabold text-gray-900 font-display text-base tracking-tight">Kuota Pesan</h2>
            <span class="text-xs font-bold px-2.5 py-0.5 rounded-full <?= $pct >= 90 ? 'bg-red-50 text-red-600 border border-red-200' : 'bg-gray-100 text-gray-600' ?>"><?= $pct ?>% terpakai</span>
          </div>

          <!-- Progress Bar Kuota -->
          <div class="w-full bg-gray-100 rounded-full h-3 mb-4 overflow-hidden p-0.5 border border-gray-100">
            <div class="h-2 rounded-full bg-gradient-to-r <?= $barColor ?> transition-all duration-700 shadow-xs" style="width: <?= $pct ?>%"></div>
          </div>

          <div class="flex justify-between text-xs mb-2">
            <span class="font-bold text-gray-800"><?= number_format($used) ?> terpakai</span>
            <span class="text-gray-400 font-semibold"><?= number_format($limit) ?> total</span>
          </div>
        </div>

        <?php if ($pct >= 80): ?>
          <div class="mt-4 bg-red-50 border border-red-200 rounded-xl p-3 text-xs text-red-700 font-medium flex items-center gap-2">
            <svg class="w-4 h-4 flex-shrink-0 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
            <span>Kuota hampir habis! <a href="<?= url('/billing') ?>" class="underline font-bold">Upgrade paket</a> untuk terus mengirim pesan.</span>
          </div>
        <?php endif; ?>
      </div>

      <!-- Status Langganan dengan Warna Menyesuaikan Paket Billing -->
      <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 flex flex-col justify-between hover:border-gray-200 transition-all">
        <div>
          <div class="flex items-center justify-between mb-5">
            <h2 class="font-extrabold text-gray-900 font-display text-base tracking-tight">Status Langganan</h2>
            <span class="text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-wider shadow-2xs <?= $planTheme['badge'] ?>">
              <?= htmlspecialchars($subscription['plan_name']) ?>
            </span>
          </div>

          <div class="space-y-4">
            <!-- Hari Tersisa -->
            <div>
              <div class="flex justify-between text-xs font-semibold mb-2">
                <span class="text-gray-400">Masa Aktif</span>
                <span class="font-extrabold <?= $planTheme['activeText'] ?>"><?= $daysRemaining ?> hari tersisa</span>
              </div>
              <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden p-0.5 border border-gray-100">
                <div class="h-1.5 rounded-full bg-gradient-to-r <?= $planTheme['progressBar'] ?> transition-all duration-500 shadow-xs" style="width: <?= max(5, 100 - $daysPct) ?>%"></div>
              </div>
            </div>

            <div class="grid grid-cols-2 gap-3 pt-1">
              <div class="bg-gray-50/90 border border-gray-100 rounded-xl p-3.5 transition-colors hover:bg-gray-50">
                <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-1">RATE LIMIT</p>
                <p class="text-sm font-black text-gray-900 font-display"><?= (int)$subscription['rate_limit_per_minute'] ?> <span class="text-xs font-normal text-gray-500 font-sans">req/menit</span></p>
              </div>
              <div class="bg-gray-50/90 border border-gray-100 rounded-xl p-3.5 transition-colors hover:bg-gray-50">
                <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-1">BATAS SESSION</p>
                <p class="text-sm font-black text-gray-900 font-display"><?= (int)$subscription['session_limit'] ?> <span class="text-xs font-normal text-gray-500 font-sans">session</span></p>
              </div>
            </div>
          </div>
        </div>

        <div class="text-xs text-gray-400 pt-4 border-t border-gray-100 flex items-center gap-1.5 mt-4">
          <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
          Berlaku hingga <strong class="text-gray-700 font-semibold ml-0.5"><?= date('d M Y', strtotime($subscription['end_at'])) ?></strong>
        </div>
      </div>
    </div>

    <!-- Grafik Pesan 7 Hari & Breakdown Tipe -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

      <!-- Grafik Bar 7 Hari -->
      <div class="md:col-span-2 bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
        <h2 class="font-bold text-gray-800 font-display mb-5">Pesan Terkirim — 7 Hari Terakhir</h2>
        <?php
          // Isi semua 7 hari terakhir (termasuk hari yang belum ada data)
          $last7 = [];
          for ($i = 6; $i >= 0; $i--) {
            $last7[date('Y-m-d', strtotime("-$i days"))] = 0;
          }
          foreach ($dailyMessages as $row) {
            if (isset($last7[$row['day']])) {
              $last7[$row['day']] = (int)$row['total'];
            }
          }
          $maxBar = max(1, max($last7));
        ?>
        <div class="flex items-end gap-2 h-36">
          <?php foreach ($last7 as $date => $count): ?>
            <?php $barH = max(4, (int) round($count / $maxBar * 100)); ?>
            <div class="flex-1 flex flex-col items-center gap-1.5 group">
              <div class="relative w-full flex items-end justify-center" style="height: 100px;">
                <div class="w-full rounded-t-lg bg-gradient-to-t from-purple-600 to-blue-400 transition-all duration-500 group-hover:opacity-80 relative" style="height: <?= $barH ?>%;">
                  <?php if ($count > 0): ?>
                    <span class="absolute -top-5 left-1/2 -translate-x-1/2 text-[9px] font-black text-purple-700 whitespace-nowrap"><?= $count ?></span>
                  <?php endif; ?>
                </div>
              </div>
              <span class="text-[9px] text-gray-400 font-semibold"><?= date('d/m', strtotime($date)) ?></span>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Breakdown Tipe Pesan -->
      <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
        <h2 class="font-bold text-gray-800 font-display mb-5">Tipe Pesan</h2>
        <?php if (empty($messageTypes)): ?>
          <div class="flex flex-col items-center justify-center h-28 text-gray-300 gap-2">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
            <p class="text-xs">Belum ada data</p>
          </div>
        <?php else: ?>
          <?php
            $typeIcons = [
              'text' => '<svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>',
              'image' => '<svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>',
              'file' => '<svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>',
              'location' => '<svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>',
              'contact' => '<svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>',
            ];
            $totalMsgs = max(1, array_sum(array_column($messageTypes, 'total')));
          ?>
          <div class="space-y-3">
            <?php foreach ($messageTypes as $mt): ?>
              <?php
                $key = strtolower($mt['message_type']);
                $svgIcon = $typeIcons[$key] ?? '<svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>';
                $typePct = (int) round($mt['total'] / $totalMsgs * 100);
              ?>
              <div>
                <div class="flex items-center justify-between mb-1.5">
                  <span class="text-xs font-semibold text-gray-700 flex items-center gap-2">
                    <?= $svgIcon ?>
                    <span><?= ucfirst($key) ?></span>
                  </span>
                  <span class="text-xs text-gray-500 font-bold"><?= number_format($mt['total']) ?></span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-1.5 overflow-hidden">
                  <div class="h-1.5 rounded-full bg-purple-500" style="width: <?= $typePct ?>%"></div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Riwayat Pesan Terakhir -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
      <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <h2 class="font-bold text-gray-800 font-display">Riwayat Pesan Terakhir</h2>
        <span class="text-xs text-gray-400">10 terbaru</span>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead class="bg-gray-50 text-gray-500 text-left">
            <tr>
              <th class="px-6 py-3 font-semibold">Session</th>
              <th class="px-6 py-3 font-semibold">Tipe</th>
              <th class="px-6 py-3 font-semibold">Penerima</th>
              <th class="px-6 py-3 font-semibold">Status</th>
              <th class="px-6 py-3 font-semibold">Waktu</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            <?php if (empty($recentMessages)): ?>
              <tr>
                <td colspan="5" class="px-6 py-8 text-center text-gray-400">Belum ada pesan yang dikirim.</td>
              </tr>
            <?php endif; ?>
            <?php foreach ($recentMessages as $msg): ?>
              <?php
                $statusBadge = match($msg['status']) {
                  'sent'      => 'bg-blue-50 text-blue-700',
                  'delivered' => 'bg-green-50 text-green-700',
                  'read'      => 'bg-purple-50 text-purple-700',
                  'failed'    => 'bg-red-50 text-red-700',
                  default     => 'bg-gray-100 text-gray-500',
                };
              ?>
              <tr class="hover:bg-gray-50/50 transition-colors">
                <td class="px-6 py-3.5 font-medium text-gray-700"><?= htmlspecialchars($msg['session_name'] ?? '-') ?></td>
                <td class="px-6 py-3.5 text-gray-500"><?= htmlspecialchars($msg['message_type']) ?></td>
                <td class="px-6 py-3.5 font-mono text-gray-500 text-xs"><?= htmlspecialchars($msg['recipient'] ?? '-') ?></td>
                <td class="px-6 py-3.5">
                  <span class="px-2 py-0.5 rounded-full text-xs font-semibold <?= $statusBadge ?>"><?= $msg['status'] ?></span>
                </td>
                <td class="px-6 py-3.5 text-gray-400 text-xs"><?= htmlspecialchars($msg['created_at']) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

  <?php endif; ?>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
