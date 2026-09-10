<?php $title = 'Sessions'; require __DIR__ . '/../layouts/header.php'; require __DIR__ . '/../layouts/nav.php'; ?>
<div>
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
    <div>
      <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight font-display">WhatsApp Sessions</h1>
      <p class="text-sm text-gray-500 mt-1">Kelola dan pantau status koneksi sesi WhatsApp Anda secara real-time.</p>
    </div>
    <div>
      <a href="<?= url('/sessions/create') ?>" class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl shadow-md shadow-purple-500/20 transition-all">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Session Baru
      </a>
    </div>
  </div>

  <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <?php require __DIR__ . '/_table.php'; ?>
  </div>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
