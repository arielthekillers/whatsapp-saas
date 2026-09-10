<?php
$title = "Kirim & Riwayat Pesan";
require __DIR__ . '/../layouts/header.php';
require __DIR__ . '/../layouts/nav.php';

$flashSuccess = $_SESSION['flash_success'] ?? null;
$flashError = $_SESSION['flash_error'] ?? null;
unset($_SESSION['flash_success'], $_SESSION['flash_error']);
?>

<div>
  <!-- Top Header Banner (Consistent with other modules) -->
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
    <div>
      <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight font-display">Pesan &amp; Riwayat Logs</h1>
      <p class="text-sm text-gray-500 mt-1">Kirim pesan WhatsApp langsung dan pantau log riwayat pesan per sesi dengan mudah.</p>
    </div>

    <!-- Active Sessions Badge -->
    <div class="flex items-center gap-3">
      <div class="px-3.5 py-2 bg-white border border-gray-200 rounded-xl text-xs font-semibold text-gray-700 flex items-center gap-2 shadow-xs">
        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
        <span><?= count($sessions) ?> Sesi WhatsApp Terdaftar</span>
      </div>
    </div>
  </div>

  <?php if ($flashSuccess): ?>
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs rounded-xl flex items-center gap-3 shadow-xs font-medium">
      <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
      </svg>
      <span><?= htmlspecialchars($flashSuccess) ?></span>
    </div>
  <?php endif; ?>

  <?php if ($flashError): ?>
    <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 text-xs rounded-xl flex items-center gap-3 shadow-xs font-medium">
      <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
      </svg>
      <span><?= htmlspecialchars($flashError) ?></span>
    </div>
  <?php endif; ?>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- LEFT SIDE: Form Kirim Pesan -->
    <div class="lg:col-span-1 space-y-6">
      <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-xs">
        <div class="pb-4 mb-5 border-b border-gray-100">
          <h2 class="text-base font-bold text-gray-900 font-display">Kirim Pesan Instan</h2>
          <p class="text-xs text-gray-500 mt-0.5">Pilih sesi &amp; jenis pesan</p>
        </div>

        <?php if (empty($sessions)): ?>
          <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl text-center">
            <p class="text-xs font-semibold text-amber-800 mb-2">Belum ada sesi WhatsApp aktif</p>
            <a href="<?= url('/sessions/create') ?>" class="inline-flex items-center gap-1.5 text-xs font-bold text-purple-700 hover:underline">
              + Buat Sesi Baru
            </a>
          </div>
        <?php else: ?>
          <form method="POST" action="<?= url('/messages/send') ?>" class="space-y-4">
            <?= \App\Helpers\Csrf::field() ?>

            <!-- Session Selector -->
            <div>
              <label class="block text-[11px] font-bold text-gray-600 uppercase tracking-wider mb-1.5">Pilih Sesi WhatsApp</label>
              <select name="session_id" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-xs font-medium text-gray-800 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600 transition-all">
                <?php foreach ($sessions as $s): ?>
                  <option value="<?= (int)$s['id'] ?>">
                    <?= htmlspecialchars($s['name']) ?> (<?= htmlspecialchars($s['phone_number'] ?? 'Tidak terhubung') ?>) - [<?= strtoupper($s['status']) ?>]
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <!-- Recipient Number -->
            <div>
              <label class="block text-[11px] font-bold text-gray-600 uppercase tracking-wider mb-1.5">Nomor Penerima</label>
              <input type="text" name="recipient" required placeholder="Contoh: 628123456789" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-xs font-medium text-gray-800 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600 transition-all" />
              <p class="text-[10px] text-gray-400 mt-1">Gunakan format internasional tanpa '+'.</p>
            </div>

            <!-- Message Type Selector -->
            <div>
              <label class="block text-[11px] font-bold text-gray-600 uppercase tracking-wider mb-1.5">Jenis Pesan</label>
              <select name="message_type" id="messageTypeSelect" required onchange="toggleMediaFields()" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-xs font-medium text-gray-800 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600 transition-all">
                <option value="text">Pesan Teks (Text)</option>
                <option value="image">Pesan Gambar (Image)</option>
                <option value="video">Pesan Video (Video)</option>
                <option value="file">Pesan Dokumen (File/PDF)</option>
              </select>
            </div>

            <!-- Media URL (Hidden by default, shown for image/video/file) -->
            <div id="mediaUrlGroup" class="hidden">
              <label class="block text-[11px] font-bold text-gray-600 uppercase tracking-wider mb-1.5">URL Media / File</label>
              <input type="url" name="media_url" id="mediaUrlInput" placeholder="https://example.com/file.jpg" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-xs font-medium text-gray-800 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600 transition-all" />
              <p class="text-[10px] text-gray-400 mt-1">URL publik file yang dapat diunduh.</p>
            </div>

            <!-- Message Body / Caption -->
            <div>
              <label id="messageTextLabel" class="block text-[11px] font-bold text-gray-600 uppercase tracking-wider mb-1.5">Isi Pesan</label>
              <textarea name="message_text" rows="4" placeholder="Tulis pesan Anda di sini..." class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-xs font-medium text-gray-800 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600 transition-all resize-none"></textarea>
            </div>

            <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs py-2.5 px-4 rounded-xl shadow-xs transition-all flex items-center justify-center gap-2">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
              </svg>
              <span>Kirim Pesan</span>
            </button>
          </form>
        <?php endif; ?>
      </div>
    </div>

    <!-- RIGHT SIDE: Log / Daftar Pesan -->
    <div class="lg:col-span-2 space-y-6">
      <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-xs">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 mb-5 border-b border-gray-100">
          <div>
            <h2 class="text-base font-bold text-gray-900 font-display">Riwayat Logs Pesan</h2>
            <p class="text-xs text-gray-500 mt-0.5">Total <?= count($messages) ?> log pesan ditemukan</p>
          </div>

          <!-- Multi-session Filter Form -->
          <form method="GET" action="<?= url('/messages') ?>" class="flex flex-wrap items-center gap-2">
            <!-- Filter Session -->
            <select name="session_id" onchange="this.form.submit()" class="bg-gray-50 border border-gray-200 rounded-xl px-2.5 py-1.5 text-xs font-medium text-gray-700 focus:outline-none focus:border-purple-600">
              <option value="">Semua Sesi</option>
              <?php foreach ($sessions as $s): ?>
                <option value="<?= (int)$s['id'] ?>" <?= $selectedSessionId === (int)$s['id'] ? 'selected' : '' ?>>
                  <?= htmlspecialchars($s['name']) ?>
                </option>
              <?php endforeach; ?>
            </select>

            <!-- Filter Type -->
            <select name="type" onchange="this.form.submit()" class="bg-gray-50 border border-gray-200 rounded-xl px-2.5 py-1.5 text-xs font-medium text-gray-700 focus:outline-none focus:border-purple-600">
              <option value="">Semua Jenis</option>
              <option value="text" <?= $selectedType === 'text' ? 'selected' : '' ?>>Text</option>
              <option value="image" <?= $selectedType === 'image' ? 'selected' : '' ?>>Image</option>
              <option value="video" <?= $selectedType === 'video' ? 'selected' : '' ?>>Video</option>
              <option value="file" <?= $selectedType === 'file' ? 'selected' : '' ?>>File</option>
            </select>

            <!-- Search Query -->
            <div class="relative">
              <input type="text" name="q" value="<?= htmlspecialchars($searchQuery) ?>" placeholder="Cari nomor/isi..." class="bg-gray-50 border border-gray-200 rounded-xl pl-2.5 pr-7 py-1.5 text-xs font-medium text-gray-700 focus:outline-none focus:border-purple-600 w-32 sm:w-40" />
              <button type="submit" class="absolute right-2 top-2 text-gray-400 hover:text-purple-600">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
              </button>
            </div>

            <?php if ($selectedSessionId || $selectedType || $searchQuery): ?>
              <a href="<?= url('/messages') ?>" class="text-xs font-semibold text-rose-500 hover:underline px-1" title="Reset filter">Reset</a>
            <?php endif; ?>
          </form>
        </div>

        <!-- Table of Messages -->
        <?php if (empty($messages)): ?>
          <div class="text-center py-12">
            <div class="w-10 h-10 rounded-xl bg-gray-50 border border-gray-200 text-gray-400 flex items-center justify-center mx-auto mb-3">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
              </svg>
            </div>
            <p class="text-xs font-bold text-gray-700">Belum ada riwayat pesan</p>
            <p class="text-[11px] text-gray-400 mt-0.5">Pesan yang dikirim melalui API atau form akan muncul di sini.</p>
          </div>
        <?php else: ?>
          <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
              <thead>
                <tr class="border-b border-gray-100 text-[10px] uppercase font-bold tracking-wider text-gray-400 bg-gray-50/50">
                  <th class="py-3 px-3">Sesi</th>
                  <th class="py-3 px-3">Penerima</th>
                  <th class="py-3 px-3">Jenis</th>
                  <th class="py-3 px-3">Isi Pesan / Media</th>
                  <th class="py-3 px-3 text-center">Status</th>
                  <th class="py-3 px-3 text-right">Waktu</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-100 text-xs">
                <?php foreach ($messages as $msg): ?>
                  <?php 
                    $payload = is_string($msg['payload']) ? json_decode($msg['payload'], true) : $msg['payload'];
                    $text = $payload['text'] ?? $payload['caption'] ?? '-';
                    $fileUrl = $payload['fileUrl'] ?? $payload['url'] ?? null;
                    
                    $typeLabel = match(strtolower($msg['message_type'] ?? 'text')) {
                      'image' => 'Image',
                      'video' => 'Video',
                      'file'  => 'File',
                      default => 'Text',
                    };

                    $statusBadge = match(strtolower($msg['status'] ?? 'sent')) {
                      'delivered' => '<span class="px-2 py-0.5 bg-blue-50 text-blue-700 border border-blue-100 rounded-full font-semibold text-[10px]">Delivered</span>',
                      'read'      => '<span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-full font-semibold text-[10px]">Read</span>',
                      'failed'    => '<span class="px-2 py-0.5 bg-rose-50 text-rose-700 border border-rose-100 rounded-full font-semibold text-[10px]">Failed</span>',
                      default     => '<span class="px-2 py-0.5 bg-gray-50 text-gray-700 border border-gray-200 rounded-full font-semibold text-[10px]">Sent</span>',
                    };
                  ?>
                  <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="py-3 px-3 font-semibold text-gray-900 whitespace-nowrap">
                      <span class="inline-flex items-center gap-1.5 bg-gray-100 px-2 py-0.5 rounded-md text-[11px] font-semibold text-gray-700 border border-gray-200">
                        <?= htmlspecialchars($msg['session_name'] ?? 'Session #' . $msg['session_id']) ?>
                      </span>
                    </td>
                    <td class="py-3 px-3 font-semibold text-gray-800 whitespace-nowrap">
                      <?= htmlspecialchars($msg['recipient'] ?? '-') ?>
                    </td>
                    <td class="py-3 px-3 whitespace-nowrap">
                      <span class="px-2 py-0.5 bg-gray-100 text-gray-700 rounded-md font-medium text-[10px]">
                        <?= $typeLabel ?>
                      </span>
                    </td>
                    <td class="py-3 px-3 text-gray-600 max-w-xs truncate">
                      <p class="truncate" title="<?= htmlspecialchars((string)$text) ?>"><?= htmlspecialchars((string)$text) ?></p>
                      <?php if ($fileUrl): ?>
                        <a href="<?= htmlspecialchars($fileUrl) ?>" target="_blank" class="text-[10px] text-purple-600 hover:underline flex items-center gap-1 mt-0.5">
                          <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                          <span>Buka Media</span>
                        </a>
                      <?php endif; ?>
                    </td>
                    <td class="py-3 px-3 text-center whitespace-nowrap">
                      <?= $statusBadge ?>
                    </td>
                    <td class="py-3 px-3 text-right text-gray-400 text-[10px] whitespace-nowrap">
                      <?= htmlspecialchars(date('d/m/Y H:i', strtotime($msg['created_at'] ?? 'now'))) ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<script>
function toggleMediaFields() {
  const type = document.getElementById('messageTypeSelect').value;
  const mediaGroup = document.getElementById('mediaUrlGroup');
  const mediaInput = document.getElementById('mediaUrlInput');
  const textLabel = document.getElementById('messageTextLabel');

  if (type === 'text') {
    mediaGroup.classList.add('hidden');
    mediaInput.removeAttribute('required');
    textLabel.textContent = 'Isi Pesan';
  } else {
    mediaGroup.classList.remove('hidden');
    mediaInput.setAttribute('required', 'required');
    textLabel.textContent = 'Caption / Keterangan (Opsional)';
  }
}
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
