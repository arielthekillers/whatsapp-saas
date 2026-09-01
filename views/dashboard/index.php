<?php $title = 'Dashboard'; require __DIR__ . '/../layouts/header.php'; require __DIR__ . '/../layouts/nav.php'; ?>
<div class="max-w-5xl mx-auto px-4 py-8">
  <h1 class="text-xl font-semibold mb-6">Halo, <?= htmlspecialchars($user['name']) ?> 👋</h1>

  <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
    <div class="bg-white rounded-xl shadow p-5">
      <p class="text-sm text-gray-500">Total Session</p>
      <p class="text-2xl font-semibold"><?= count($sessions) ?></p>
    </div>
    <div class="bg-white rounded-xl shadow p-5">
      <p class="text-sm text-gray-500">Session Aktif</p>
      <p class="text-2xl font-semibold"><?= count(array_filter($sessions, static fn ($s) => $s['status'] === 'WORKING')) ?></p>
    </div>
    <div class="bg-white rounded-xl shadow p-5">
      <p class="text-sm text-gray-500">Plan</p>
      <p class="text-2xl font-semibold text-gray-300">Belum ada (Phase 4)</p>
    </div>
  </div>

  <div class="flex items-center justify-between mb-4">
    <h2 class="font-medium">WhatsApp Sessions</h2>
    <a href="<?= url('/sessions/create') ?>" class="bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white text-sm px-4 py-2 rounded-lg shadow-md shadow-purple-500/10 transition-all">+ Session Baru</a>
  </div>

  <?php require __DIR__ . '/../sessions/_table.php'; ?>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
