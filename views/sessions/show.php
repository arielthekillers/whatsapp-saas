<?php $title = 'Session: ' . $session['name']; require __DIR__ . '/../layouts/header.php'; require __DIR__ . '/../layouts/nav.php'; ?>
<div class="max-w-md mx-auto px-4 py-8">
  <a href="<?= url('/sessions') ?>" class="text-sm text-gray-500 hover:underline">&larr; Kembali</a>
  <h1 class="text-xl font-semibold mt-2 mb-6"><?= htmlspecialchars($session['name']) ?></h1>

  <div class="bg-white rounded-xl shadow p-6 text-center" id="session-card" data-id="<?= (int) $session['id'] ?>">
    <p class="text-sm text-gray-500 mb-1">Status</p>
    <p class="text-lg font-semibold mb-1" id="status-text"><?= htmlspecialchars($session['status']) ?></p>
    <p class="text-xs text-purple-600 font-bold mb-4" id="phone-text"><?= htmlspecialchars($session['phone_number'] ?? '') ?></p>

    <div id="qr-container" class="mb-4 min-h-[240px] flex items-center justify-center">
      <?php if (!empty($session['qr_code'])): ?>
        <img src="<?= htmlspecialchars($session['qr_code']) ?>" class="mx-auto rounded-lg border" width="240" height="240" alt="QR Code">
      <?php elseif ($session['status'] === 'WORKING'): ?>
        <div class="py-6 text-center"><span class="text-4xl">✅</span><p class="text-green-600 font-bold mt-2">✓ Terhubung</p></div>
      <?php else: ?>
        <p class="text-gray-400 text-sm">Menunggu QR Code...</p>
      <?php endif; ?>
    </div>

    <div class="flex gap-2 justify-center flex-wrap pt-2">
      <?php if (in_array($session['status'], ['STOPPED', 'LOGGED_OUT', 'FAILED'], true)): ?>
        <form method="POST" action="<?= url('/sessions/' . (int) $session['id'] . '/start') ?>">
          <?= \App\Helpers\Csrf::field() ?>
          <button type="submit" class="text-sm px-4 py-2.5 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-bold transition-all shadow-md shadow-purple-500/20 flex items-center gap-1.5">
            <span>▶ Mulai Sesi (Scan QR)</span>
          </button>
        </form>
        <form method="POST" action="<?= url('/sessions/' . (int) $session['id'] . '/delete') ?>" onsubmit="return confirm('Apakah Anda yakin ingin menghapus sesi ini?')">
          <?= \App\Helpers\Csrf::field() ?>
          <button type="submit" class="text-sm px-4 py-2.5 rounded-xl bg-red-50 text-red-600 hover:bg-red-100 font-bold transition-all border border-red-200">
            Hapus Sesi
          </button>
        </form>
      <?php else: ?>
        <form method="POST" action="<?= url('/sessions/' . (int) $session['id'] . '/stop') ?>">
          <?= \App\Helpers\Csrf::field() ?>
          <button type="submit" class="text-sm px-3 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 font-semibold">Stop</button>
        </form>
        <form method="POST" action="<?= url('/sessions/' . (int) $session['id'] . '/logout') ?>">
          <?= \App\Helpers\Csrf::field() ?>
          <button type="submit" class="text-sm px-3 py-2 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 font-semibold">Logout</button>
        </form>
      <?php endif; ?>
    </div>
  </div>
</div>

<script>
(function () {
  var card = document.getElementById('session-card');
  var id = card.getAttribute('data-id');
  var statusText = document.getElementById('status-text');
  var phoneText = document.getElementById('phone-text');
  var qrContainer = document.getElementById('qr-container');
  var redirected = false;

  function poll() {
    fetch('<?= url('/sessions') ?>/' + id + '/status')
      .then(function (res) { return res.json(); })
      .then(function (json) {
        if (json.success) {
          statusText.textContent = json.data.status;
          if (json.data.phone) {
            phoneText.textContent = json.data.phone;
          }
          if (json.data.qr) {
            qrContainer.innerHTML = '<img src="' + json.data.qr + '" class="mx-auto rounded-lg border shadow-sm" width="240" height="240" alt="QR Code">';
          } else if (json.data.status === 'WORKING') {
            qrContainer.innerHTML = '<div class="py-6 text-center"><span class="text-4xl">✅</span><p class="text-green-600 font-bold mt-2">WhatsApp Terhubung!</p><p class="text-xs text-gray-400 mt-1">Mengalihkan ke daftar sesi...</p></div>';
            if (!redirected) {
              redirected = true;
              setTimeout(function () {
                window.location.href = '<?= url('/sessions') ?>';
              }, 1500);
            }
          } else if (json.data.status === 'STOPPED' || json.data.status === 'LOGGED_OUT' || json.data.status === 'FAILED') {
            qrContainer.innerHTML = '<div class="py-4 text-center"><p class="text-gray-500 text-sm">Sesi dalam kondisi terhenti / terputus.<br>Klik tombol <strong>▶ Mulai Sesi</strong> di bawah untuk memunculkan QR Code baru.</p></div>';
          }
          
          if (json.data.status !== 'WORKING' && json.data.status !== 'STOPPED' && json.data.status !== 'LOGGED_OUT') {
            setTimeout(poll, 3000);
          }
        } else {
          if (json.error && json.error.message) {
            qrContainer.innerHTML = '<div class="p-4 bg-red-50 text-red-600 rounded-xl border border-red-100 text-xs text-left leading-relaxed"><strong>Koneksi WAHA:</strong><br>' + json.error.message + '</div>';
          }
          setTimeout(poll, 6000);
        }
      })
      .catch(function (err) {
        setTimeout(poll, 6000);
      });
  }

  var initStatus = statusText.textContent.trim();
  if (initStatus !== 'WORKING' && initStatus !== 'STOPPED' && initStatus !== 'LOGGED_OUT') {
    poll();
  }
})();
</script>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
