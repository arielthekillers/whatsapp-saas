<?php $title = 'Session Baru'; require __DIR__ . '/../layouts/header.php'; require __DIR__ . '/../layouts/nav.php'; ?>
<div>
  <div class="mb-6">
    <a href="<?= url('/sessions') ?>" class="inline-flex items-center text-sm font-semibold text-purple-600 hover:text-purple-700 transition-colors mb-3">&larr; Kembali ke Sessions</a>
    <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight font-display">Buat WhatsApp Session</h1>
    <p class="text-sm text-gray-500 mt-1">Tambahkan label sesi WhatsApp baru untuk menghubungkan perangkat Anda.</p>
  </div>
  <?php if (!empty($error)): ?>
    <div class="mb-4 rounded bg-red-50 text-red-700 text-sm px-4 py-2"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>
  <form method="POST" action="<?= url('/sessions') ?>" class="bg-white rounded-xl shadow p-6 space-y-4">
    <?= \App\Helpers\Csrf::field() ?>
    <div>
      <label class="block text-sm font-medium mb-1">Nama Session</label>
      <input type="text" name="name" required placeholder="mis. Marketing" class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500">
      <p class="text-xs text-gray-400 mt-1">Nama internal WAHA dibuat otomatis dan unik, tidak berdasarkan nama ini secara langsung.</p>
    </div>
    <button type="submit" class="w-full bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white font-medium py-2.5 rounded-lg shadow-lg shadow-purple-500/10 transition-all hover:scale-[1.01]">Buat &amp; Hubungkan</button>
  </form>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
