<div class="bg-white rounded-xl shadow overflow-hidden overflow-x-auto">
  <table class="w-full text-sm">
    <thead class="bg-gray-50 text-left text-gray-500">
      <tr>
        <th class="px-4 py-3">Nama</th>
        <th class="px-4 py-3">Nomor</th>
        <th class="px-4 py-3">Status</th>
        <th class="px-4 py-3">Dibuat</th>
        <th class="px-4 py-3"></th>
      </tr>
    </thead>
    <tbody class="divide-y">
      <?php if (empty($sessions)): ?>
        <tr><td colspan="5" class="px-4 py-6 text-center text-gray-400">Belum ada session. <a href="<?= url('/sessions/create') ?>" class="text-purple-600 hover:underline">Buat sekarang</a>.</td></tr>
      <?php endif; ?>
      <?php foreach ($sessions as $s): ?>
        <?php
          $badgeClass = match ($s['status']) {
              'WORKING' => 'bg-green-100 text-green-700 border-green-200',
              'SCAN_QR', 'SCAN_QR_CODE', 'STARTING', 'CREATED' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
              'FAILED', 'LOGGED_OUT', 'STOPPED' => 'bg-red-100 text-red-700 border-red-200',
              default => 'bg-gray-100 text-gray-600 border-gray-200',
          };
          $pingColor = match ($s['status']) {
              'WORKING' => 'bg-green-500',
              'SCAN_QR', 'SCAN_QR_CODE', 'STARTING', 'CREATED' => 'bg-yellow-500',
              default => 'bg-red-500',
          };
        ?>
        <tr>
          <td class="px-4 py-3 font-medium"><?= htmlspecialchars($s['name']) ?></td>
          <td class="px-4 py-3 text-gray-500"><?= htmlspecialchars($s['phone_number'] ?? '-') ?></td>
          <td class="px-4 py-3">
            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border <?= $badgeClass ?>">
              <span class="relative flex h-2 w-2 mr-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full <?= $pingColor ?> opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 <?= $pingColor ?>"></span>
              </span>
              <?= htmlspecialchars($s['status']) ?>
            </span>
          </td>
          <td class="px-4 py-3 text-gray-500"><?= htmlspecialchars($s['created_at']) ?></td>
          <td class="px-4 py-3 text-right"><a href="<?= url('/sessions/' . (int) $s['id']) ?>" class="text-purple-600 hover:underline">Kelola</a></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
