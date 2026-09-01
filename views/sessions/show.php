<?php $title = 'Session: ' . $session['name']; require __DIR__ . '/../layouts/header.php'; require __DIR__ . '/../layouts/nav.php'; ?>
<div class="max-w-md mx-auto px-4 py-8">
  <a href="<?= url('/sessions') ?>" class="text-sm text-gray-500 hover:underline">&larr; Kembali</a>
  <h1 class="text-xl font-semibold mt-2 mb-6"><?= htmlspecialchars($session['name']) ?></h1>

  <div class="bg-white rounded-xl shadow p-6 text-center" id="session-card" data-id="<?= (int) $session['id'] ?>">
    <p class="text-sm text-gray-500 mb-1">Status</p>
    <p class="text-lg font-semibold mb-4" id="status-text"><?= htmlspecialchars($session['status']) ?></p>

    <div id="qr-container" class="mb-4 min-h-[240px] flex items-center justify-center">
      <?php if (!empty($session['qr_code'])): ?>
        <img src="<?= htmlspecialchars($session['qr_code']) ?>" class="mx-auto rounded-lg border" width="240" height="240" alt="QR Code">
      <?php elseif ($session['status'] === 'WORKING'): ?>
        <p class="text-green-600 font-medium">✓ Terhubung</p>
      <?php else: ?>
        <p class="text-gray-400 text-sm">Menunggu QR Code...</p>
      <?php endif; ?>
    </div>

    <div class="flex gap-2 justify-center">
      <form method="POST" action="<?= url('/sessions/' . (int) $session['id'] . '/stop') ?>">
        <?= \App\Helpers\Csrf::field() ?>
        <button type="submit" class="text-sm px-3 py-2 rounded-lg bg-gray-100 hover:bg-gray-200">Stop</button>
      </form>
      <form method="POST" action="<?= url('/sessions/' . (int) $session['id'] . '/logout') ?>">
        <?= \App\Helpers\Csrf::field() ?>
        <button type="submit" class="text-sm px-3 py-2 rounded-lg bg-red-50 text-red-600 hover:bg-red-100">Logout</button>
      </form>
    </div>
  </div>
</div>

<script>
(function () {
  var card = document.getElementById('session-card');
  var id = card.getAttribute('data-id');
  var statusText = document.getElementById('status-text');
  var qrContainer = document.getElementById('qr-container');

  function poll() {
    fetch('<?= url('/sessions') ?>/' + id + '/status')
      .then(function (res) { return res.json(); })
      .then(function (json) {
        if (json.success) {
          statusText.textContent = json.data.status;
          if (json.data.qr) {
            qrContainer.innerHTML = '<img src="' + json.data.qr + '" class="mx-auto rounded-lg border" width="240" height="240" alt="QR Code">';
          } else if (json.data.status === 'WORKING') {
            qrContainer.innerHTML = '<p class="text-green-600 font-medium">✓ Terhubung</p>';
          }
          if (json.data.status !== 'WORKING') {
            setTimeout(poll, 4000);
          }
        } else {
          setTimeout(poll, 6000);
        }
      })
      .catch(function () { setTimeout(poll, 6000); });
  }

  if (statusText.textContent.trim() !== 'WORKING') {
    setTimeout(poll, 3000);
  }
})();
</script>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
