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
          $badge = match ($s['status']) {
              'WORKING' => 'bg-green-100 text-green-700',
              'SCAN_QR', 'SCAN_QR_CODE', 'STARTING', 'CREATED' => 'bg-yellow-100 text-yellow-700',
              'FAILED', 'LOGGED_OUT' => 'bg-red-100 text-red-700',
              default => 'bg-gray-100 text-gray-600',
          };
        ?>
        <tr>
          <td class="px-4 py-3 font-medium"><?= htmlspecialchars($s['name']) ?></td>
          <td class="px-4 py-3 text-gray-500"><?= htmlspecialchars($s['phone_number'] ?? '-') ?></td>
          <td class="px-4 py-3"><span class="px-2 py-1 rounded-full text-xs <?= $badge ?>"><?= htmlspecialchars($s['status']) ?></span></td>
          <td class="px-4 py-3 text-gray-500"><?= htmlspecialchars($s['created_at']) ?></td>
          <td class="px-4 py-3 text-right"><a href="<?= url('/sessions/' . (int) $s['id']) ?>" class="text-purple-600 hover:underline">Kelola</a></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
