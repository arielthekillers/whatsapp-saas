<?php $title = 'Sessions'; require __DIR__ . '/../layouts/header.php'; require __DIR__ . '/../layouts/nav.php'; ?>
<div class="max-w-5xl mx-auto px-4 py-8">
  <div class="flex items-center justify-between mb-4">
    <h1 class="text-xl font-semibold">WhatsApp Sessions</h1>
    <a href="<?= url('/sessions/create') ?>" class="bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white text-sm px-4 py-2 rounded-lg shadow-md shadow-purple-500/10 transition-all">+ Session Baru</a>
  </div>
  <?php require __DIR__ . '/_table.php'; ?>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
