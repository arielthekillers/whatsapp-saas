<?php $title = 'Halaman Tidak Ditemukan'; require __DIR__ . '/../layouts/header.php'; ?>
<div class="min-h-screen flex items-center justify-center">
  <div class="text-center">
    <p class="text-4xl font-bold text-gray-300">404</p>
    <p class="text-gray-500 mt-2">Halaman tidak ditemukan.</p>
    <a href="<?= url('/dashboard') ?>" class="text-purple-600 hover:underline mt-4 inline-block">Kembali ke Dashboard</a>
  </div>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
