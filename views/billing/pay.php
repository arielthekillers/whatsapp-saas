<?php $title = 'Pembayaran QRIS'; require __DIR__ . '/../layouts/header.php'; ?>

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
          Menunggu Pembayaran QRIS
        </div>
        <h1 class="text-xl font-extrabold font-display">Instruksi &amp; Konfirmasi Pembayaran QRIS</h1>
      </div>
      <div>
        <a href="<?= url('/billing') ?>" class="bg-white/20 hover:bg-white/30 text-white font-semibold text-xs px-4 py-2 rounded-xl backdrop-blur-md border border-white/20 transition-all inline-flex items-center gap-1">
          ← Kembali ke Billing
        </a>
      </div>
    </div>

    <?php if (!empty($_SESSION['flash_billing_error'])): ?>
      <div class="rounded-xl bg-red-50 border border-red-200 p-4 text-xs font-semibold text-red-700">
        <?= htmlspecialchars($_SESSION['flash_billing_error']) ?>
        <?php unset($_SESSION['flash_billing_error']); ?>
      </div>
    <?php endif; ?>

    <!-- 2-Column Responsive Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

      <!-- LEFT COLUMN: Invoice Summary & Official QRIS Box (7 Cols) -->
      <div class="lg:col-span-7 space-y-6">
        
        <!-- Merged Card: Invoice & QRIS Details -->
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
                <span class="text-gray-500">Paket Yang Dipilih</span>
                <span class="font-extrabold text-purple-700 bg-purple-100/70 border border-purple-200 px-2.5 py-0.5 rounded text-xs"><?= htmlspecialchars($payment['plan_name']) ?></span>
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
              <div class="border-t border-gray-200 pt-2.5 mt-2 flex justify-between items-baseline">
                <span class="text-sm font-bold text-gray-800">Total Nominal Pembayaran</span>
                <span class="text-2xl font-black text-purple-700 font-display">Rp <?= number_format((float)$payment['amount'], 0, ',', '.') ?></span>
              </div>
            </div>
          </div>

          <!-- Official QRIS Display Card -->
          <div>
            <div class="flex items-center justify-between mb-3">
              <h2 class="text-xs font-extrabold uppercase tracking-wider text-purple-600 flex items-center gap-1.5">
                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                <span>Kode Pembayaran QRIS Resmi</span>
              </h2>
              <span class="text-[10px] bg-red-100 text-red-700 font-bold px-2 py-0.5 rounded-full uppercase tracking-wider border border-red-200">
                GPN • QRIS
              </span>
            </div>

            <div class="rounded-2xl border-2 border-purple-200 bg-gradient-to-b from-purple-50/60 to-white p-5 space-y-4">
              
              <!-- Merchant Info Header -->
              <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white p-3.5 rounded-xl border border-purple-100 shadow-xs">
                <div>
                  <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider block">Merchant Terdaftar</span>
                  <div class="flex items-center gap-1.5">
                    <span class="text-base font-black text-gray-900 font-display"><?= htmlspecialchars($qrisMerchantName) ?></span>
                    <span class="inline-flex items-center justify-center w-4 h-4 rounded-full bg-emerald-500 text-white text-[10px] font-bold" title="Verified Merchant">✓</span>
                  </div>
                </div>
                <div class="text-left sm:text-right">
                  <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider block">NMID</span>
                  <div class="flex items-center gap-1.5 sm:justify-end">
                    <span id="qris-nmid" class="text-xs font-mono font-bold text-gray-800"><?= htmlspecialchars($qrisNmid) ?></span>
                    <button type="button" onclick="copyText('qris-nmid', this)" class="copy-btn text-[10px] text-purple-600 hover:text-purple-800 font-bold bg-purple-50 hover:bg-purple-100 px-2 py-0.5 rounded border border-purple-200 transition-all">
                      Salin
                    </button>
                  </div>
                </div>
              </div>

              <!-- QRIS Code Image Showcase -->
              <div class="text-center py-2">
                <div class="inline-block p-3 bg-white rounded-2xl border-2 border-gray-200 shadow-md max-w-[320px] w-full">
                  <img src="<?= url($qrisImagePath) ?>" alt="QRIS <?= htmlspecialchars($qrisMerchantName) ?>" class="w-full h-auto rounded-xl object-contain mx-auto">
                </div>
                
                <div class="mt-3 flex items-center justify-center gap-3">
                  <a href="<?= url($qrisImagePath) ?>" download="QRIS-<?= htmlspecialchars($payment['external_id']) ?>.jpeg" class="inline-flex items-center gap-1.5 bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs px-4 py-2 rounded-xl transition-all shadow-sm">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    <span>Unduh Gambar QRIS</span>
                  </a>
                  <a href="<?= url($qrisImagePath) ?>" target="_blank" class="inline-flex items-center gap-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs px-3.5 py-2 rounded-xl transition-all border border-gray-200">
                    <span>Perbesar</span>
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                  </a>
                </div>
              </div>

              <!-- Supported Apps Chips -->
              <div class="pt-2 border-t border-purple-100">
                <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider block mb-2 text-center">
                  Bisa dibayar dari semua m-Banking &amp; E-Wallet:
                </span>
                <div class="flex flex-wrap justify-center gap-1.5 text-[11px] font-semibold text-gray-600">
                  <span class="bg-white border border-gray-200 px-2.5 py-1 rounded-lg shadow-2xs">BCA</span>
                  <span class="bg-white border border-gray-200 px-2.5 py-1 rounded-lg shadow-2xs">Mandiri</span>
                  <span class="bg-white border border-gray-200 px-2.5 py-1 rounded-lg shadow-2xs">BRI</span>
                  <span class="bg-white border border-gray-200 px-2.5 py-1 rounded-lg shadow-2xs">BNI</span>
                  <span class="bg-white border border-gray-200 px-2.5 py-1 rounded-lg shadow-2xs">BSI</span>
                  <span class="bg-white border border-gray-200 px-2.5 py-1 rounded-lg shadow-2xs">GoPay</span>
                  <span class="bg-white border border-gray-200 px-2.5 py-1 rounded-lg shadow-2xs">OVO</span>
                  <span class="bg-white border border-gray-200 px-2.5 py-1 rounded-lg shadow-2xs">DANA</span>
                  <span class="bg-white border border-gray-200 px-2.5 py-1 rounded-lg shadow-2xs">ShopeePay</span>
                  <span class="bg-white border border-gray-200 px-2.5 py-1 rounded-lg shadow-2xs">LinkAja</span>
                </div>
              </div>

            </div>
          </div>

          <!-- Important Warning -->
          <div class="bg-amber-50 border border-amber-200 rounded-xl p-3.5 flex gap-3 text-xs text-amber-800">
            <span class="text-lg">⚠️</span>
            <div>
              <strong class="font-bold block text-amber-900 mb-0.5">Bayar Tepat Sesuai Nominal!</strong>
              <p>Masukkan nominal tepat <strong class="font-extrabold">Rp <?= number_format((float)$payment['amount'], 0, ',', '.') ?></strong> dan pastikan nama penerima adalah <strong><?= htmlspecialchars($qrisMerchantName) ?></strong>.</p>
            </div>
          </div>

        </div>

        <!-- 3-Step Horizontal Pill Steps -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4">
          <div class="grid grid-cols-3 gap-2 text-center text-xs">
            <div class="p-2.5 bg-purple-50 rounded-xl border border-purple-100">
              <span class="w-5 h-5 rounded-full bg-purple-600 text-white font-bold inline-flex items-center justify-center text-[10px] mb-1">1</span>
              <p class="font-bold text-gray-800">Scan QRIS</p>
              <p class="text-[10px] text-gray-500 mt-0.5">Lewat m-Banking / E-Wallet</p>
            </div>
            <div class="p-2.5 bg-purple-50 rounded-xl border border-purple-100">
              <span class="w-5 h-5 rounded-full bg-purple-600 text-white font-bold inline-flex items-center justify-center text-[10px] mb-1">2</span>
              <p class="font-bold text-gray-800">Isi Form</p>
              <p class="text-[10px] text-gray-500 mt-0.5">Konfirmasi pembayaran</p>
            </div>
            <div class="p-2.5 bg-purple-50 rounded-xl border border-purple-100">
              <span class="w-5 h-5 rounded-full bg-purple-600 text-white font-bold inline-flex items-center justify-center text-[10px] mb-1">3</span>
              <p class="font-bold text-gray-800">Paket Aktif</p>
              <p class="text-[10px] text-gray-500 mt-0.5">Diverifikasi oleh tim</p>
            </div>
          </div>
        </div>

      </div>

      <!-- RIGHT COLUMN: Confirmation Form (5 Cols) -->
      <div class="lg:col-span-5 space-y-6">
        
        <!-- Confirmation Form Card -->
        <div class="bg-white rounded-2xl border-2 border-purple-200 shadow-md p-6">
          <div class="mb-4 pb-3 border-b border-gray-100">
            <h2 class="font-extrabold text-gray-900 text-base font-display">Konfirmasi Pembayaran QRIS</h2>
            <p class="text-xs text-gray-500 mt-0.5">Isi data pengirim agar pembayaran langsung diverifikasi dan paket aktif.</p>
          </div>

          <form method="POST" action="<?= url('/billing/pay/' . $payment['external_id'] . '/confirm') ?>" class="space-y-4">
            <?= \App\Helpers\Csrf::field() ?>

            <div>
              <label class="block text-xs font-bold text-gray-700 mb-1">Nama Pemilik Rekening / Akun E-Wallet <span class="text-red-500">*</span></label>
              <input type="text" name="sender_name" required placeholder="Contoh: Budi Santoso"
                class="w-full border border-gray-300 rounded-xl px-3.5 py-2.5 text-xs focus:outline-none focus:ring-2 focus:ring-purple-500 bg-gray-50 font-semibold text-gray-900">
            </div>

            <div>
              <label class="block text-xs font-bold text-gray-700 mb-1">Aplikasi / Bank yang Digunakan <span class="text-gray-400 font-normal">(opsional)</span></label>
              <select name="payment_app" class="w-full border border-gray-300 rounded-xl px-3.5 py-2.5 text-xs focus:outline-none focus:ring-2 focus:ring-purple-500 bg-gray-50 font-semibold text-gray-900">
                <option value="">-- Pilih Aplikasi / Bank Pembayar --</option>
                <option value="BCA">BCA (BCA mobile / myBCA)</option>
                <option value="Mandiri">Bank Mandiri (Livin by Mandiri)</option>
                <option value="BRI">BRI (BRImo)</option>
                <option value="BNI">BNI (BNI Mobile)</option>
                <option value="BSI">BSI Mobile</option>
                <option value="GoPay">GoPay</option>
                <option value="OVO">OVO</option>
                <option value="DANA">DANA</option>
                <option value="ShopeePay">ShopeePay</option>
                <option value="LinkAja">LinkAja</option>
                <option value="Lainnya">Bank / Dompet Digital Lainnya</option>
              </select>
            </div>

            <div>
              <label class="block text-xs font-bold text-gray-700 mb-1">Tanggal Pembayaran <span class="text-red-500">*</span></label>
              <input type="date" name="transfer_date" required value="<?= date('Y-m-d') ?>"
                class="w-full border border-gray-300 rounded-xl px-3.5 py-2.5 text-xs focus:outline-none focus:ring-2 focus:ring-purple-500 bg-gray-50 font-semibold text-gray-900">
            </div>

            <div>
              <label class="block text-xs font-bold text-gray-700 mb-1">Catatan Tambahan / Nomor Referensi (RRN) <span class="text-gray-400 font-normal">(opsional)</span></label>
              <textarea name="transfer_note" rows="2" placeholder="Nomor referensi / RRN transaksi atau keterangan lainnya..."
                class="w-full border border-gray-300 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-purple-500 bg-gray-50 resize-none text-gray-900"></textarea>
            </div>

            <button type="submit" class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-extrabold py-3.5 rounded-xl text-xs transition-all shadow-md shadow-purple-500/20 cursor-pointer">
              ✅ Kirim Konfirmasi Pembayaran
            </button>
          </form>

          <div class="mt-6 pt-4 border-t border-gray-100 text-center space-y-3">
            <?php if (!empty($bankWhatsapp)): ?>
              <?php 
                $waMsg = "Halo Admin " . $qrisMerchantName . ", saya sudah membayar tagihan QRIS untuk invoice " . $payment['external_id'] . " paket " . $payment['plan_name'] . " sebesar Rp " . number_format((float)$payment['amount'], 0, ',', '.') . ". Mohon bantu verifikasi.";
              ?>
              <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $bankWhatsapp) ?>?text=<?= urlencode($waMsg) ?>"
                 target="_blank"
                 class="inline-flex items-center gap-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 text-xs font-bold px-4 py-2.5 rounded-xl transition-all">
                <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M11.75 2C6.375 2 2 6.375 2 11.75a9.685 9.685 0 001.347 4.942L2 22l5.417-1.34A9.72 9.72 0 0011.75 21.5C17.125 21.5 21.5 17.125 21.5 11.75S17.125 2 11.75 2zm0 17.75a8 8 0 01-4.084-1.121l-.292-.174-3.22.796.821-3.139-.19-.302A8 8 0 0111.75 3.75c4.411 0 8 3.589 8 8s-3.589 8-8 8z"/></svg>
                <span>Bantuan &amp; Kirim Bukti via WhatsApp</span>
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
    btn.classList.add('bg-green-600', 'text-white');
    btn.classList.remove('bg-purple-50', 'text-purple-600');
    setTimeout(function() {
      btn.innerText = orig;
      btn.classList.remove('bg-green-600', 'text-white');
      btn.classList.add('bg-purple-50', 'text-purple-600');
    }, 2000);
  });
}
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
