<?php 
$title = 'Dashboard'; 
require __DIR__ . '/../layouts/header.php'; 
require __DIR__ . '/../layouts/nav.php'; 

// Calculate Subscription & Quota stats
$hasActiveSub = !empty($activeSub);
$planName     = $hasActiveSub ? htmlspecialchars($activeSub['plan_name']) : 'Tanpa Paket';
$messagesUsed  = $hasActiveSub ? (int) $activeSub['messages_used'] : 0;
$messagesLimit = $hasActiveSub ? (int) $activeSub['messages_limit'] : 0;
$sessionLimit  = $hasActiveSub ? (int) $activeSub['session_limit'] : 0;
$rateLimit     = $hasActiveSub ? (int) $activeSub['rate_limit_per_minute'] : 0;

$msgPercent = $messagesLimit > 0 ? min(100, round(($messagesUsed / $messagesLimit) * 100, 1)) : 0;
$activeSessionCount = count(array_filter($sessions, static fn ($s) => $s['status'] === 'WORKING'));
$totalSessionCount  = count($sessions);
$sessionPercent     = $sessionLimit > 0 ? min(100, round(($totalSessionCount / $sessionLimit) * 100, 1)) : 0;

// Format expiry date
$expiryFormatted = '-';
$daysRemaining   = 0;
if ($hasActiveSub && !empty($activeSub['end_at'])) {
    $endTime = strtotime($activeSub['end_at']);
    $expiryFormatted = date('d M Y', $endTime);
    $diff = $endTime - time();
    $daysRemaining = max(0, (int) ceil($diff / (60 * 60 * 24)));
}
?>

<div>
  <!-- Header Section -->
  <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
      <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight font-display">
        Halo, <?= htmlspecialchars($user['name']) ?>
      </h1>
      <p class="text-sm text-gray-500 mt-1">
        Kelola sesi WhatsApp, pantau kuota pengiriman pesan, dan integrasikan API Anda.
      </p>
    </div>
    <div class="flex items-center gap-3">
      <a href="<?= url('/billing') ?>" class="inline-flex items-center gap-2 bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 text-sm font-medium px-4 py-2.5 rounded-xl shadow-sm transition-all">
        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
        </svg>
        Billing & Paket
      </a>
      <a href="<?= url('/sessions/create') ?>" class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl shadow-md shadow-purple-500/20 transition-all">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        Session Baru
      </a>
    </div>
  </div>

  <!-- Subscription & Metric Cards -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    
    <?php
      // Peta Skema Warna Paket (Menyesuaikan dengan Billing):
      $planNameUpper = strtoupper(trim((string)$planName));
      $dashboardPlanTheme = match($planNameUpper) {
        'LITE' => [
          'badge'       => 'bg-emerald-100/80 text-emerald-800 border border-emerald-300 font-black',
          'dot'         => 'bg-emerald-500',
          'activeText'  => 'text-emerald-700',
          'link'        => 'text-emerald-600 hover:text-emerald-700',
        ],
        'BUSINESS' => [
          'badge'       => 'bg-amber-100/80 text-amber-900 border border-amber-300 font-black',
          'dot'         => 'bg-amber-500',
          'activeText'  => 'text-amber-700',
          'link'        => 'text-amber-600 hover:text-amber-700',
        ],
        'ENTERPRISE' => [
          'badge'       => 'bg-amber-100/80 text-amber-900 border border-amber-300 font-black',
          'dot'         => 'bg-amber-500',
          'activeText'  => 'text-amber-700',
          'link'        => 'text-amber-600 hover:text-amber-700',
        ],
        default => [ // PRO / Default
          'badge'       => 'bg-purple-100/80 text-purple-900 border border-purple-300 font-black',
          'dot'         => 'bg-purple-500',
          'activeText'  => 'text-purple-700',
          'link'        => 'text-purple-600 hover:text-purple-700',
        ],
      };
    ?>

    <!-- Card 1: Subscription Info -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 flex flex-col justify-between relative hover:border-gray-200 transition-all">
      <div class="relative z-10 flex flex-col justify-between h-full">
        <div class="flex items-center justify-between mb-3">
          <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Paket Langganan</span>
          <?php if ($hasActiveSub): ?>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider shadow-2xs <?= $dashboardPlanTheme['badge'] ?>">
              <span class="w-1.5 h-1.5 rounded-full <?= $dashboardPlanTheme['dot'] ?> mr-1.5 animate-pulse"></span>
              AKTIF
            </span>
          <?php else: ?>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-600 border border-amber-200 uppercase tracking-wider">
              Belum Aktif
            </span>
          <?php endif; ?>
        </div>

        <div class="flex items-baseline gap-2 mb-2">
          <h2 class="text-2xl font-black text-gray-900 font-display tracking-tight">
            <?= $planName ?>
          </h2>
          <?php if ($hasActiveSub): ?>
            <span class="text-xs text-gray-400 font-normal font-sans">(<?= number_format($rateLimit) ?> req/min)</span>
          <?php endif; ?>
        </div>

        <?php if ($hasActiveSub): ?>
          <p class="text-xs text-gray-500">
            Berlaku hingga <strong class="font-bold text-gray-800"><?= $expiryFormatted ?></strong> 
            <span class="<?= $dashboardPlanTheme['activeText'] ?> font-bold ml-1">(<?= $daysRemaining ?> hari lagi)</span>
          </p>
        <?php else: ?>
          <p class="text-xs text-gray-500">
            Anda tidak memiliki paket aktif saat ini.
          </p>
        <?php endif; ?>
      </div>

      <div class="mt-5 pt-4 border-t border-gray-100 flex items-center justify-between text-xs">
        <?php if ($hasActiveSub): ?>
          <span class="text-gray-500">Upgrade atau ubah paket</span>
          <a href="<?= url('/billing') ?>" class="<?= $dashboardPlanTheme['link'] ?> font-extrabold inline-flex items-center gap-1">
            Kelola <span stroke-width="2">&rarr;</span>
          </a>
        <?php else: ?>
          <a href="<?= url('/billing') ?>" class="w-full text-center bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 rounded-xl transition-all shadow-sm">
            Pilih Paket Langganan
          </a>
        <?php endif; ?>
      </div>
    </div>

    <!-- Card 2: Messages Quota Progress -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 flex flex-col justify-between">
      <div>
        <div class="flex items-center justify-between mb-3">
          <span class="text-xs font-semibold uppercase tracking-wider text-gray-400">Kuota Pesan</span>
          <span class="text-xs font-bold text-purple-600"><?= $msgPercent ?>%</span>
        </div>

        <div class="flex items-baseline gap-1 mb-3">
          <span class="text-2xl font-black text-gray-900 font-display"><?= number_format($messagesUsed) ?></span>
          <span class="text-sm text-gray-400 font-medium">/ <?= number_format($messagesLimit) ?> Pesan</span>
        </div>

        <!-- Progress Bar -->
        <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden mb-2">
          <div class="bg-gradient-to-r from-purple-500 to-indigo-600 h-2.5 rounded-full transition-all duration-500" style="width: <?= $msgPercent ?>%"></div>
        </div>
      </div>

      <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between text-xs text-gray-500">
        <span>Sisa Kuota: <strong class="text-gray-700"><?= number_format(max(0, $messagesLimit - $messagesUsed)) ?></strong></span>
        <a href="<?= url('/usage') ?>" class="text-purple-600 hover:text-purple-700 font-medium">Lihat Detail</a>
      </div>
    </div>

    <!-- Card 3: Session Limit -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 flex flex-col justify-between">
      <div>
        <div class="flex items-center justify-between mb-3">
          <span class="text-xs font-semibold uppercase tracking-wider text-gray-400">Batas WhatsApp Session</span>
          <span class="text-xs font-semibold text-emerald-600"><?= $activeSessionCount ?> Aktif</span>
        </div>

        <div class="flex items-baseline gap-1 mb-3">
          <span class="text-2xl font-black text-gray-900 font-display"><?= $totalSessionCount ?></span>
          <span class="text-sm text-gray-400 font-medium">/ <?= $sessionLimit ?> Session Digunakan</span>
        </div>

        <!-- Progress Bar -->
        <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden mb-2">
          <div class="bg-gradient-to-r from-emerald-500 to-teal-600 h-2.5 rounded-full transition-all duration-500" style="width: <?= $sessionPercent ?>%"></div>
        </div>
      </div>

      <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between text-xs text-gray-500">
        <span>Sisa Slot Session: <strong class="text-gray-700"><?= max(0, $sessionLimit - $totalSessionCount) ?></strong></span>
        <a href="<?= url('/sessions') ?>" class="text-purple-600 hover:text-purple-700 font-medium">Kelola Session</a>
      </div>
    </div>

  </div>

  <!-- Developer & Quick Resource Links -->
  <div class="bg-gradient-to-r from-purple-700 to-indigo-700 rounded-2xl shadow-lg p-6 mb-8 text-white relative overflow-hidden">
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 relative z-10">
      <div>
        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-white/10 text-white border border-white/20 mb-2">
          <svg class="w-3.5 h-3.5 text-yellow-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"></path></svg>
          Integrasi Cepat
        </div>
        <h3 class="text-lg font-bold font-display">Siap Mengirim Pesan Pertama via API?</h3>
        <p class="text-sm text-purple-100 mt-1 max-w-xl">
          Gunakan API Key dan Endpoint Webhook Anda untuk mengintegrasikan WhatsApp dengan aplikasi web, sistem CRM, atau bot.
        </p>
      </div>
      <div class="flex flex-wrap items-center gap-3">
        <a href="<?= url('/api-keys') ?>" class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white text-xs font-semibold px-4 py-2.5 rounded-xl border border-white/20 transition-all backdrop-blur-xs">
          <svg class="w-4 h-4 text-purple-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" /></svg>
          API Keys
        </a>
        <a href="<?= url('/webhooks') ?>" class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white text-xs font-semibold px-4 py-2.5 rounded-xl border border-white/20 transition-all backdrop-blur-xs">
          <svg class="w-4 h-4 text-purple-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" /></svg>
          Webhooks
        </a>
        <a href="<?= url('/docs') ?>" class="inline-flex items-center gap-2 bg-white text-purple-700 hover:bg-purple-50 text-xs font-bold px-4 py-2.5 rounded-xl shadow-md transition-all">
          <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
          Dokumentasi API
        </a>
      </div>
    </div>
  </div>

  <!-- WhatsApp Sessions List Section -->
  <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <h2 class="text-lg font-bold text-gray-900 font-display">Daftar WhatsApp Session</h2>
        <p class="text-xs text-gray-500 mt-0.5">Semua sesi WhatsApp terhubung yang siap digunakan untuk kirim/terima pesan.</p>
      </div>
      <a href="<?= url('/sessions/create') ?>" class="inline-flex items-center gap-1.5 bg-purple-600 hover:bg-purple-700 text-white text-xs font-semibold px-4 py-2 rounded-lg transition-all shadow-sm">
        + Sesi Baru
      </a>
    </div>

    <?php require __DIR__ . '/../sessions/_table.php'; ?>
  </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
