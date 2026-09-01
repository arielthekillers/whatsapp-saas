<?php $title = 'Instruksi Pembayaran'; require __DIR__ . '/../layouts/header.php'; ?>

<style>
  @keyframes pulse-ring {
    0% { transform: scale(1); opacity: 0.4; }
    50% { transform: scale(1.1); opacity: 0.15; }
    100% { transform: scale(1); opacity: 0.4; }
  }
  .pulse-ring { animation: pulse-ring 2s ease-in-out infinite; }
  .copy-btn:active { transform: scale(0.95); }
</style>

<div class="min-h-screen bg-gray-50 py-6 px-4">
  <div class="max-w-5xl mx-auto space-y-6">

    <!-- Top Header Banner -->
    <div class="bg-gradient-to-r from-purple-700 via-indigo-700 to-blue-600 rounded-2xl p-5 text-white shadow-lg flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <div class="inline-flex items-center gap-2 bg-white/20 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider mb-1">
          <span class="relative flex h-2 w-2">
            <span class="pulse-ring absolute inline-flex h-full w-full rounded-full bg-yellow-300 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-2 w-2 bg-yellow-400"></span>
          </span>
          Menunggu Pembayaran
        </div>
        <h1 class="text-xl font-extrabold font-display">Instruksi &amp; Konfirmasi Pembayaran</h1>
      </div>
      <div>
        <a href="<?= url('/billing') ?>" class="bg-white/20 hover:bg-white/30 text-white font-semibold text-xs px-4 py-2 rounded-xl backdrop-blur-md border border-white/20 transition-all inline-flex items-center gap-1">
          ← Kembali ke Billing
        </a>
      </div>
    </div>

    <!-- 2-Column Responsive Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

      <!-- LEFT COLUMN: Invoice & Bank Transfer Account Info (7 Cols) -->
      <div class="lg:col-span-7 space-y-6">
        
        <!-- Merged Card: Invoice & Bank Transfer Details -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-6">
          
          <!-- Invoice Details Grid -->
          <div>
            <h2 class="text-xs font-extrabold uppercase tracking-wider text-gray-400 mb-3">Ringkasan Tagihan</h2>
            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 space-y-2">
              <div class="flex justify-between text-sm">
                <span class="text-gray-500">Nomor Invoice</span>
                <span class="font-bold text-gray-900 font-mono"><?= htmlspecialchars($payment['external_id']) ?></span>
              </div>
              <div class="flex justify-between text-sm">
                <span class="text-gray-500">Paket Yang Dibeli</span>
                <span class="font-extrabold text-purple-700 bg-purple-100/70 border border-purple-200 px-2 py-0.5 rounded text-xs"><?= htmlspecialchars($payment['plan_name']) ?></span>
              </div>
              <?php
                $parts  = explode(':', $payment['provider']);
                $months = isset($parts[1]) ? (int)$parts[1] : 1;
                $label  = match($months) { 3 => '3 Bulan (-5%)', 6 => '6 Bulan (-10%)', 12 => '1 Tahun (-20%)', default => '1 Bulan' };
              ?>
              <div class="flex justify-between text-sm">
                <span class="text-gray-500">Durasi</span>
                <span class="font-semibold text-gray-800"><?= $label ?></span>
              </div>
              <div class="border-t border-gray-200 pt-2 mt-2 flex justify-between items-baseline">
                <span class="text-sm font-bold text-gray-800">Total Nominal Transfer</span>
                <span class="text-2xl font-black text-purple-700 font-display">Rp <?= number_format((float)$payment['amount'], 0, ',', '.') ?></span>
              </div>
            </div>
          </div>

          <!-- Bank Transfer Account Box -->
          <div>
            <h2 class="text-xs font-extrabold uppercase tracking-wider text-purple-600 mb-3 flex items-center gap-1.5">
              <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
              <span>Rekening Tujuan Transfer Bank</span>
            </h2>

            <div class="rounded-xl border-2 border-purple-200 bg-purple-50/40 p-4 space-y-3">
              <div class="flex items-center justify-between">
                <div>
                  <span class="text-[11px] text-gray-400 font-bold uppercase block">Bank Tujuan</span>
                  <span class="text-lg font-black text-gray-900 font-display"><?= htmlspecialchars($bankName) ?></span>
                </div>
                <div class="w-10 h-10 bg-purple-600 text-white rounded-xl flex items-center justify-center font-bold text-xs">
                  BANK
                </div>
              </div>

              <!-- Account Number with Copy -->
              <div class="bg-white rounded-xl p-3 border border-purple-200 flex items-center justify-between gap-3 shadow-xs">
                <div>
                  <span class="text-[10px] text-gray-400 font-bold uppercase block">Nomor Rekening</span>
                  <span id="acc-number" class="text-xl font-black text-gray-900 font-mono tracking-wider"><?= htmlspecialchars($bankAccount) ?></span>
                </div>
                <button onclick="copyText('acc-number', this)" class="copy-btn bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold px-3.5 py-2 rounded-lg transition-all shadow-sm">
                  Salin No. Rek
                </button>
              </div>

              <div class="flex justify-between items-center text-xs">
                <span class="text-gray-500">Atas Nama Rekening</span>
                <span class="font-extrabold text-gray-900"><?= htmlspecialchars($bankHolder) ?></span>
              </div>
            </div>
          </div>

          <!-- Transfer Warning -->
          <div class="bg-amber-50 border border-amber-200 rounded-xl p-3.5 flex gap-3 text-xs text-amber-800">
            <span class="text-lg">⚠️</span>
            <div>
              <strong class="font-bold block text-amber-900 mb-0.5">Transfer Tepat Sesuai Nominal!</strong>
              <p>Transfer tepat sebesar <strong class="font-extrabold">Rp <?= number_format((float)$payment['amount'], 0, ',', '.') ?></strong> dan tuliskan Invoice ID <code class="font-mono bg-amber-100 px-1.5 py-0.5 rounded text-amber-900 font-bold"><?= htmlspecialchars($payment['external_id']) ?></code> di catatan transfer.</p>
            </div>
          </div>

        </div>

        <!-- 3-Step Horizontal Pill Steps -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4">
          <div class="grid grid-cols-3 gap-2 text-center text-xs">
            <div class="p-2 bg-purple-50 rounded-xl border border-purple-100">
              <span class="w-5 h-5 rounded-full bg-purple-600 text-white font-bold inline-flex items-center justify-center text-[10px] mb-1">1</span>
              <p class="font-bold text-gray-800">Transfer Bank</p>
            </div>
            <div class="p-2 bg-purple-50 rounded-xl border border-purple-100">
              <span class="w-5 h-5 rounded-full bg-purple-600 text-white font-bold inline-flex items-center justify-center text-[10px] mb-1">2</span>
              <p class="font-bold text-gray-800">Isi Form</p>
            </div>
            <div class="p-2 bg-purple-50 rounded-xl border border-purple-100">
              <span class="w-5 h-5 rounded-full bg-purple-600 text-white font-bold inline-flex items-center justify-center text-[10px] mb-1">3</span>
              <p class="font-bold text-gray-800">Paket Aktif</p>
            </div>
          </div>
        </div>

      </div>

      <!-- RIGHT COLUMN: Confirmation Form (5 Cols) -->
      <div class="lg:col-span-5 space-y-6">
        
        <!-- Confirmation Form Card -->
        <div class="bg-white rounded-2xl border-2 border-purple-200 shadow-md p-6">
          <div class="mb-4 pb-3 border-b border-gray-100">
            <h2 class="font-extrabold text-gray-900 text-base font-display">Konfirmasi Transfer</h2>
            <p class="text-xs text-gray-500 mt-0.5">Isi data pengirim agar pembayaran langsung diverifikasi Admin.</p>
          </div>

          <form method="POST" action="<?= url('/billing/pay/' . $payment['external_id'] . '/confirm') ?>" class="space-y-4">
            <?= \App\Helpers\Csrf::field() ?>

            <div>
              <label class="block text-xs font-bold text-gray-700 mb-1">Nama Pengirim Transfer <span class="text-red-500">*</span></label>
              <input type="text" name="sender_name" required placeholder="Nama sesuai pemilik rekening"
                class="w-full border border-gray-300 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-purple-500 bg-gray-50 font-semibold">
            </div>

            <div>
              <label class="block text-xs font-bold text-gray-700 mb-1">Tanggal Transfer <span class="text-red-500">*</span></label>
              <input type="date" name="transfer_date" required value="<?= date('Y-m-d') ?>"
                class="w-full border border-gray-300 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-purple-500 bg-gray-50 font-semibold">
            </div>

            <div>
              <label class="block text-xs font-bold text-gray-700 mb-1">Catatan Tambahan <span class="text-gray-400 font-normal">(opsional)</span></label>
              <textarea name="transfer_note" rows="2" placeholder="Nama bank pengirim, atau berita transfer..."
                class="w-full border border-gray-300 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-purple-500 bg-gray-50 resize-none"></textarea>
            </div>

            <button type="submit" class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-extrabold py-3 rounded-xl text-xs transition-all shadow-md shadow-purple-500/20 cursor-pointer">
              ✅ Konfirmasi Pembayaran
            </button>
          </form>

          <div class="mt-6 pt-4 border-t border-gray-100 text-center space-y-3">
            <?php if ($bankWhatsapp): ?>
              <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $bankWhatsapp) ?>?text=Halo+Wapify%2C+saya+ingin+konfirmasi+pembayaran+<?= urlencode($payment['external_id']) ?>"
                 target="_blank"
                 class="inline-flex items-center gap-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 text-xs font-bold px-4 py-2 rounded-xl transition-all">
                <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M11.75 2C6.375 2 2 6.375 2 11.75a9.685 9.685 0 001.347 4.942L2 22l5.417-1.34A9.72 9.72 0 0011.75 21.5C17.125 21.5 21.5 17.125 21.5 11.75S17.125 2 11.75 2zm0 17.75a8 8 0 01-4.084-1.121l-.292-.174-3.22.796.821-3.139-.19-.302A8 8 0 0111.75 3.75c4.411 0 8 3.589 8 8s-3.589 8-8 8z"/></svg>
                <span>Bantuan WhatsApp Support</span>
              </a>
            <?php endif; ?>

            <div>
              <form method="POST" action="<?= url('/billing/pay/' . $payment['external_id'] . '/cancel') ?>" onsubmit="return confirm('Batalkan invoice ini?')">
                <?= \App\Helpers\Csrf::field() ?>
                <button type="submit" class="text-[11px] text-gray-400 hover:text-rose-600 transition-colors underline">
                  Batalkan Invoice Ini
                </button>
              </form>
            </div>
          </div>
        </div>

      </div>

    </div>

  </div>
</div>

<script>
function copyText(elementId, btn) {
  var text = document.getElementById(elementId).innerText;
  navigator.clipboard.writeText(text.replace(/\s/g, '')).then(function() {
    var orig = btn.innerText;
    btn.innerText = '✓ Disalin';
    btn.classList.add('bg-green-600');
    btn.classList.remove('bg-purple-600');
    setTimeout(function() {
      btn.innerText = orig;
      btn.classList.remove('bg-green-600');
      btn.classList.add('bg-purple-600');
    }, 2000);
  });
}
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
