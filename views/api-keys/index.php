<?php $title = 'API Keys'; require __DIR__ . '/../layouts/header.php'; require __DIR__ . '/../layouts/nav.php'; ?>
<div class="max-w-3xl mx-auto px-4 py-8">
  <h1 class="text-xl font-semibold mb-6">API Keys</h1>

  <?php if (!empty($newKey)): ?>
    <div class="mb-6 rounded-lg bg-green-50 border border-green-200 p-4">
      <p class="text-sm font-medium text-green-800 mb-1">API Key baru dibuat. Simpan sekarang — kunci ini hanya ditampilkan sekali:</p>
      <code class="block bg-white border rounded px-3 py-2 text-sm break-all"><?= htmlspecialchars($newKey) ?></code>
    </div>
  <?php endif; ?>

  <form method="POST" action="<?= url('/api-keys') ?>" class="bg-white rounded-xl shadow p-6 mb-6 flex gap-3 items-end">
    <?= \App\Helpers\Csrf::field() ?>
    <div class="flex-1">
      <label class="block text-sm font-medium mb-1">Nama Key</label>
      <input type="text" name="name" placeholder="mis. Production Server" class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500">
    </div>
    <button type="submit" class="bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white text-sm px-4 py-2.5 rounded-lg shadow-md shadow-purple-500/10 transition-all hover:scale-[1.01]">Buat Key</button>
  </form>

  <div class="bg-white rounded-xl shadow overflow-hidden overflow-x-auto">
    <table class="w-full text-sm">
      <thead class="bg-gray-50 text-left text-gray-500">
        <tr>
          <th class="px-4 py-3">Nama</th>
          <th class="px-4 py-3">Prefix</th>
          <th class="px-4 py-3">Terakhir Dipakai</th>
          <th class="px-4 py-3">Status</th>
          <th class="px-4 py-3"></th>
        </tr>
      </thead>
      <tbody class="divide-y">
        <?php if (empty($keys)): ?>
          <tr><td colspan="5" class="px-4 py-6 text-center text-gray-400">Belum ada API key.</td></tr>
        <?php endif; ?>
        <?php foreach ($keys as $k): ?>
          <tr>
            <td class="px-4 py-3 font-medium"><?= htmlspecialchars($k['name']) ?></td>
            <td class="px-4 py-3"><code class="text-xs text-gray-500"><?= htmlspecialchars($k['api_key_prefix']) ?>…</code></td>
            <td class="px-4 py-3 text-gray-500"><?= htmlspecialchars($k['last_used_at'] ?? 'Belum pernah') ?></td>
            <td class="px-4 py-3">
              <?php if ($k['status'] === 'active'): ?>
                <span class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-700">active</span>
              <?php else: ?>
                <span class="px-2 py-1 rounded-full text-xs bg-red-100 text-red-700">revoked</span>
              <?php endif; ?>
            </td>
            <td class="px-4 py-3 text-right">
              <?php if ($k['status'] === 'active'): ?>
                <form method="POST" action="<?= url('/api-keys/' . (int) $k['id'] . '/revoke') ?>" onsubmit="return confirm('Cabut API key ini?');">
                  <?= \App\Helpers\Csrf::field() ?>
                  <button type="submit" class="text-red-600 hover:underline">Cabut</button>
                </form>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
