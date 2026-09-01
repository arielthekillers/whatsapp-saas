<?php $title = 'Webhooks'; require __DIR__ . '/../layouts/header.php'; require __DIR__ . '/../layouts/nav.php'; ?>
<div class="max-w-4xl mx-auto px-4 py-8">
  <div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold tracking-tight text-gray-900 font-display">Webhooks</h1>
  </div>

  <?php if (!empty($newSecret)): ?>
    <div class="mb-6 rounded-lg bg-green-50 border border-green-200 p-4">
      <p class="text-sm font-semibold text-green-800 mb-1">Webhook Secret Key Baru Dibuat. Simpan secret ini sekarang — secret ini hanya ditampilkan sekali untuk keamanan:</p>
      <code class="block bg-white border rounded px-3 py-2 text-sm break-all font-mono text-green-700 select-all"><?= htmlspecialchars($newSecret) ?></code>
      <p class="text-xs text-green-600 mt-1">Gunakan key ini sebagai signature verifikasi HMAC-SHA256 pada header <code>X-Wapify-Signature</code>.</p>
    </div>
  <?php endif; ?>

  <?php if (!empty($error)): ?>
    <div class="mb-6 rounded-lg bg-red-50 border border-red-200 p-4 text-sm text-red-700">
      <?= htmlspecialchars($error) ?>
    </div>
  <?php endif; ?>

  <!-- Card Form Tambah Webhook -->
  <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 mb-8">
    <h2 class="text-lg font-bold text-gray-800 mb-4 font-display">Hubungkan Endpoint Baru</h2>
    <form method="POST" action="<?= url('/webhooks') ?>" class="space-y-4">
      <?= \App\Helpers\Csrf::field() ?>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-semibold text-gray-600 mb-1">URL Endpoint Webhook</label>
          <input type="url" name="url" required placeholder="https://aplikasi-anda.com/webhook-listener" class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500">
        </div>
        <div>
          <label class="block text-sm font-semibold text-gray-600 mb-1">Pilih WhatsApp Session</label>
          <select name="session_id" class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500">
            <option value="">Semua Sesi (Global Webhook)</option>
            <?php foreach ($sessions as $s): ?>
              <option value="<?= (int) $s['id'] ?>"><?= htmlspecialchars($s['name']) ?> (<?= htmlspecialchars($s['phone_number'] ?? 'Belum terhubung') ?>)</option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div class="flex justify-end pt-2">
        <button type="submit" class="bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white text-sm font-bold px-6 py-2.5 rounded-lg shadow-lg shadow-purple-500/10 transition-all hover:scale-[1.01]">Hubungkan Webhook</button>
      </div>
    </form>
  </div>

  <!-- Tabel Webhooks Terdaftar -->
  <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden overflow-x-auto">
    <table class="w-full text-sm">
      <thead class="bg-gray-50 text-left text-gray-500">
        <tr>
          <th class="px-6 py-3 font-semibold">Target URL Webhook</th>
          <th class="px-6 py-3 font-semibold">Tautan Sesi</th>
          <th class="px-6 py-3 font-semibold">Status</th>
          <th class="px-6 py-3 font-semibold">Dibuat</th>
          <th class="px-6 py-3"></th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100">
        <?php if (empty($webhooks)): ?>
          <tr>
            <td colspan="5" class="px-6 py-8 text-center text-gray-400">Belum ada webhook yang terhubung. Hubungkan URL callback Anda di atas.</td>
          </tr>
        <?php endif; ?>
        <?php foreach ($webhooks as $w): ?>
          <tr class="hover:bg-gray-50/50 transition-colors">
            <td class="px-6 py-4 font-mono text-xs text-gray-600 break-all"><?= htmlspecialchars($w['url']) ?></td>
            <td class="px-6 py-4 text-gray-600">
              <?= $w['session_name'] ? htmlspecialchars($w['session_name']) : '<span class="text-xs text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full">Semua Sesi</span>' ?>
            </td>
            <td class="px-6 py-4">
              <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-green-50 text-green-700">active</span>
            </td>
            <td class="px-6 py-4 text-gray-500 text-xs"><?= htmlspecialchars($w['created_at']) ?></td>
            <td class="px-6 py-4 text-right">
              <form method="POST" action="<?= url('/webhooks/' . (int) $w['id'] . '/delete') ?>" onsubmit="return confirm('Apakah Anda yakin ingin menghapus webhook ini?');">
                <?= \App\Helpers\Csrf::field() ?>
                <button type="submit" class="text-red-500 hover:text-red-700 font-semibold transition-colors hover:underline">Hapus</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
