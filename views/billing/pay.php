<?php $title = 'Instruksi Pembayaran'; require __DIR__ . '/../layouts/header.php'; ?>

<style>
  @keyframes pulse-ring {
    0% { transform: scale(1); opacity: 0.4; }
    50% { transform: scale(1.1); opacity: 0.15; }
    100% { transform: scale(1); opacity: 0.4; }
  }
  .pulse-ring { animation: pulse-ring 2s ease-in-out infinite; }
  .copy-btn:active { transform: scale(0.95); }
  .step-connector { background: linear-gradient(180deg, #E9D5FF 0%, #BFDBFE 100%); }
</style>

<div class="min-h-screen bg-gray-50 py-8 px-4 flex items-start justify-center">
  <div class="w-full max-w-xl space-y-4">

    <!-- Header Card -->
    <div class="bg-gradient-to-r from-purple-600 to-blue-600 rounded-2xl p-6 text-white shadow-xl">
      <div class="flex items-center justify-between mb-3">
        <div class="flex items-center gap-2 bg-white/20 px-3 py-1 rounded-full text-xs font-bold tracking-wider uppercase">
          <span class="relative flex h-2 w-2">
            <span class="pulse-ring absolute inline-flex h-full w-full rounded-full bg-yellow-300 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-2 w-2 bg-yellow-400"></span>
          </span>
          Menunggu Pembayaran
        </div>
        <a href="<?= url('/billing') ?>" class="text-white/60 hover:text-white text-xs transition-colors">← Kembali</a>
      </div>
      <h1 class="text-xl font-bold font-display">Transfer Manual ke Rekening Kami</h1>
      <p class="text-purple-100 text-xs mt-1">Selesaikan pembayaran dalam 24 jam agar invoice tidak kedaluwarsa.</p>
    </div>

    <!-- Invoice Summary -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
      <h2 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-3">Ringkasan Invoice</h2>
      <div class="space-y-2">
        <div class="flex justify-between text-sm">
          <span class="text-gray-500">Nomor Invoice</span>
          <span class="font-bold text-gray-800 font-mono"><?= htmlspecialchars($payment['external_id']) ?></span>
        </div>
        <div class="flex justify-between text-sm">
          <span class="text-gray-500">Paket</span>
          <span class="font-bold text-gray-800"><?= htmlspecialchars($payment['plan_name']) ?></span>
        </div>
        <?php
          $parts  = explode(':', $payment['provider']);
          $months = isset($parts[1]) ? (int)$parts[1] : 1;
          $label  = match($months) { 3 => '3 Bulan (-5%)', 6 => '6 Bulan (-10%)', 12 => '1 Tahun (-20%)', default => '1 Bulan' };
        ?>
        <div class="flex justify-between text-sm">
          <span class="text-gray-500">Durasi</span>
          <span class="font-bold text-gray-800"><?= $label ?></span>
        </div>
        <div class="border-t border-gray-100 pt-2 mt-2 flex justify-between items-baseline">
          <span class="text-sm font-semibold text-gray-700">Total Tagihan</span>
          <span class="text-2xl font-extrabold text-purple-600 font-display">Rp <?= number_format((float)$payment['amount'], 0, ',', '.') ?></span>
        </div>
      </div>
    </div>

    <!-- Bank Transfer Info -->
    <div class="bg-white rounded-2xl border-2 border-purple-100 shadow-sm overflow-hidden">
      <div class="bg-purple-50 px-5 py-3 border-b border-purple-100 flex items-center gap-2">
        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
        <span class="font-bold text-purple-800 text-sm">Rekening Tujuan Transfer</span>
      </div>
      <div class="p-5 space-y-4">

        <!-- Bank Name -->
        <div class="flex items-center justify-between">
          <div>
            <p class="text-xs text-gray-400 font-semibold mb-0.5">Bank</p>
            <p class="text-lg font-extrabold text-gray-900 font-display"><?= htmlspecialchars($bankName) ?></p>
          </div>
          <div class="w-14 h-14 bg-purple-50 rounded-xl flex items-center justify-center">
            <svg class="w-7 h-7 text-purple-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z"/></svg>
          </div>
        </div>

        <!-- Account Number -->
        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
          <p class="text-xs text-gray-400 font-semibold mb-1">Nomor Rekening</p>
          <div class="flex items-center justify-between gap-3">
            <span id="acc-number" class="text-2xl font-extrabold text-gray-900 font-mono tracking-widest"><?= htmlspecialchars($bankAccount) ?></span>
            <button onclick="copyText('acc-number', this)" class="copy-btn flex-shrink-0 bg-purple-100 hover:bg-purple-200 text-purple-700 text-xs font-bold px-3 py-1.5 rounded-lg transition-all">
              Salin
            </button>
          </div>
        </div>

        <!-- Account Holder -->
        <div class="flex justify-between items-center text-sm">
          <span class="text-gray-500">Atas Nama</span>
          <span class="font-bold text-gray-800"><?= htmlspecialchars($bankHolder) ?></span>
        </div>

        <!-- Important: Transfer exact amount -->
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-3 flex gap-3">
          <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
          <div>
            <p class="text-xs font-bold text-amber-800">Penting!</p>
            <p class="text-xs text-amber-700 mt-0.5">Transfer tepat sebesar <strong class="font-extrabold">Rp <?= number_format((float)$payment['amount'], 0, ',', '.') ?></strong> agar verifikasi lebih cepat. Tulis nomor invoice <strong><?= htmlspecialchars($payment['external_id']) ?></strong> di kolom berita/pesan transfer.</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Steps -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
      <h2 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-4">Langkah Pembayaran</h2>
      <div class="space-y-3">
        <?php
          $steps = [
            ['num' => '1', 'title' => 'Transfer ke rekening di atas', 'desc' => 'Pastikan nominal transfer tepat dan cantumkan nomor invoice di keterangan transfer.'],
            ['num' => '2', 'title' => 'Isi form konfirmasi di bawah', 'desc' => 'Lengkapi nama pengirim, tanggal transfer, dan catatan tambahan jika ada.'],
            ['num' => '3', 'title' => 'Tunggu verifikasi tim kami', 'desc' => 'Proses verifikasi maksimal 1×24 jam kerja. Paket akan aktif otomatis setelah dikonfirmasi.'],
          ];
          foreach ($steps as $s):
        ?>
          <div class="flex gap-3">
            <div class="flex-shrink-0 w-7 h-7 rounded-full bg-purple-100 text-purple-700 flex items-center justify-center text-xs font-extrabold"><?= $s['num'] ?></div>
            <div>
              <p class="text-sm font-bold text-gray-800"><?= $s['title'] ?></p>
              <p class="text-xs text-gray-400 mt-0.5"><?= $s['desc'] ?></p>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Confirmation Form -->
    <div class="bg-white rounded-2xl border-2 border-gray-200 shadow-sm p-5">
      <h2 class="font-bold text-gray-800 font-display mb-1">Konfirmasi Transfer</h2>
      <p class="text-xs text-gray-400 mb-5">Setelah transfer, isi formulir berikut agar tim kami dapat memverifikasi pembayaran Anda.</p>

      <form method="POST" action="<?= url('/billing/pay/' . $payment['external_id'] . '/confirm') ?>" class="space-y-4">
        <?= \App\Helpers\Csrf::field() ?>

        <div>
          <label class="block text-xs font-bold text-gray-600 mb-1.5">Nama Pengirim <span class="text-red-500">*</span></label>
          <input type="text" name="sender_name" required placeholder="Nama sesuai rekening pengirim"
            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-transparent transition-all">
        </div>

        <div>
          <label class="block text-xs font-bold text-gray-600 mb-1.5">Tanggal Transfer <span class="text-red-500">*</span></label>
          <input type="date" name="transfer_date" required value="<?= date('Y-m-d') ?>"
            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-transparent transition-all">
        </div>

        <div>
          <label class="block text-xs font-bold text-gray-600 mb-1.5">Catatan Tambahan <span class="text-gray-400 font-normal">(opsional)</span></label>
          <textarea name="transfer_note" rows="2" placeholder="Bank pengirim, nominal, atau keterangan lain..."
            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-transparent transition-all resize-none"></textarea>
        </div>

        <button type="submit" class="w-full bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white font-bold py-3 rounded-xl text-sm transition-all hover:scale-[1.01] shadow-lg shadow-purple-500/10">
          ✅ Saya Sudah Transfer — Konfirmasi Sekarang
        </button>
      </form>
    </div>

    <!-- WhatsApp Bantuan -->
    <?php if ($bankWhatsapp): ?>
    <div class="text-center pb-6">
      <p class="text-xs text-gray-400 mb-2">Butuh bantuan atau punya pertanyaan?</p>
      <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $bankWhatsapp) ?>?text=Halo+Wapify%2C+saya+ingin+konfirmasi+pembayaran+<?= urlencode($payment['external_id']) ?>"
         target="_blank"
         class="inline-flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white text-xs font-bold px-5 py-2.5 rounded-full transition-all hover:scale-105 shadow-md shadow-green-500/20">
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M11.75 2C6.375 2 2 6.375 2 11.75a9.685 9.685 0 001.347 4.942L2 22l5.417-1.34A9.72 9.72 0 0011.75 21.5C17.125 21.5 21.5 17.125 21.5 11.75S17.125 2 11.75 2zm0 17.75a8 8 0 01-4.084-1.121l-.292-.174-3.22.796.821-3.139-.19-.302A8 8 0 0111.75 3.75c4.411 0 8 3.589 8 8s-3.589 8-8 8z"/></svg>
        Chat WhatsApp Support
      </a>
    </div>
    <?php endif; ?>

    <!-- Cancel Invoice -->
    <div class="text-center pb-4">
      <form method="POST" action="<?= url('/billing/pay/' . $payment['external_id'] . '/cancel') ?>" onsubmit="return confirm('Batalkan invoice ini?')">
        <?= \App\Helpers\Csrf::field() ?>
        <button type="submit" class="text-xs text-gray-400 hover:text-red-500 transition-colors underline">
          Batalkan Invoice
        </button>
      </form>
    </div>

  </div>
</div>

<script>
function copyText(elementId, btn) {
  var text = document.getElementById(elementId).innerText;
  navigator.clipboard.writeText(text.replace(/\s/g, '')).then(function() {
    var orig = btn.innerText;
    btn.innerText = '✓ Disalin';
    btn.classList.add('bg-green-100', 'text-green-700');
    btn.classList.remove('bg-purple-100', 'text-purple-700');
    setTimeout(function() {
      btn.innerText = orig;
      btn.classList.remove('bg-green-100', 'text-green-700');
      btn.classList.add('bg-purple-100', 'text-purple-700');
    }, 2000);
  });
}
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
