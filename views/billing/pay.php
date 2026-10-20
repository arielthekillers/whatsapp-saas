<?php $title = 'Pembayaran QRIS'; require __DIR__ . '/../layouts/header.php'; ?>

<div class="min-h-screen bg-gray-50/60 py-8 px-4 flex flex-col justify-center items-center">
  <div class="max-w-4xl w-full space-y-4">

    <!-- Top Navigation / Breadcrumb -->
    <div class="flex items-center justify-between px-1">
      <a href="<?= url('/billing') ?>" class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-500 hover:text-gray-900 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
        <span>Kembali ke Billing</span>
      </a>

      <div class="inline-flex items-center gap-2 bg-purple-50 text-purple-700 border border-purple-100 px-3 py-1 rounded-full text-xs font-semibold">
        <span class="w-2 h-2 rounded-full bg-purple-600 animate-pulse"></span>
        <span>Menunggu Pembayaran</span>
      </div>
    </div>

    <?php if (!empty($_SESSION['flash_billing_error'])): ?>
      <div class="rounded-2xl bg-red-50 border border-red-200 p-4 text-xs font-semibold text-red-700">
        <?= htmlspecialchars($_SESSION['flash_billing_error']) ?>
        <?php unset($_SESSION['flash_billing_error']); ?>
      </div>
    <?php endif; ?>

    <!-- Main Clean Checkout Card -->
    <div class="bg-white rounded-3xl border border-gray-200/80 shadow-xl overflow-hidden grid grid-cols-1 lg:grid-cols-12">

      <!-- LEFT COLUMN: QRIS Showcase (5 Cols) -->
      <div class="lg:col-span-5 bg-slate-50/70 p-6 sm:p-8 flex flex-col items-center justify-between border-b lg:border-b-0 lg:border-r border-gray-100 text-center space-y-5">
        
        <!-- Price Header -->
        <div class="space-y-1">
          <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">Total Tagihan</span>
          <div class="text-3xl font-black text-purple-700 font-display">
            Rp <?= number_format((float)$payment['amount'], 0, ',', '.') ?>
          </div>
        </div>

        <!-- QRIS Image Showcase -->
        <div class="w-full flex flex-col items-center">
          <div class="bg-white p-2.5 rounded-2xl border-2 border-gray-200 shadow-sm max-w-[270px] w-full">
            <img src="<?= url($qrisImagePath) ?>" alt="QRIS Pembayaran" class="w-full h-auto rounded-xl object-contain mx-auto block">
          </div>

          <div class="mt-3.5">
            <a href="<?= url($qrisImagePath) ?>" download="QRIS-<?= htmlspecialchars($payment['external_id']) ?>.jpeg" class="inline-flex items-center gap-1.5 text-xs font-bold text-purple-700 bg-white hover:bg-purple-50 px-4 py-2 rounded-xl transition-all border border-purple-200 shadow-2xs">
              <svg class="w-3.5 h-3.5 text-purple-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
              <span>Unduh QRIS</span>
            </a>
          </div>
        </div>

        <!-- Subtle Scan Hint -->
        <p class="text-[11px] text-gray-400 leading-relaxed max-w-[260px]">
          Scan kode QR di atas menggunakan aplikasi e-wallet atau mobile banking Anda.
        </p>

      </div>

      <!-- RIGHT COLUMN: Order Summary & Confirmation Form (7 Cols) -->
      <div class="lg:col-span-7 p-6 sm:p-8 flex flex-col justify-between space-y-6">
        
        <!-- Order Summary -->
        <div class="space-y-3 pb-5 border-b border-gray-100">
          <h2 class="text-xs font-extrabold uppercase tracking-wider text-gray-400">Ringkasan Pesanan</h2>
          <div class="bg-gray-50 rounded-2xl p-4 border border-gray-100 space-y-2 text-xs">
            <div class="flex justify-between items-center">
              <span class="text-gray-500">Nomor Invoice</span>
              <span class="font-bold text-gray-900 font-mono text-[11px]"><?= htmlspecialchars($payment['external_id']) ?></span>
            </div>
            <div class="flex justify-between items-center">
              <span class="text-gray-500">Paket</span>
              <span class="font-extrabold text-purple-700 bg-purple-100/70 border border-purple-200 px-2 py-0.5 rounded text-[11px]"><?= htmlspecialchars($payment['plan_name']) ?></span>
            </div>
            <?php
              $parts  = explode(':', $payment['provider']);
              $months = isset($parts[1]) ? (int)$parts[1] : 1;
              $label  = match($months) { 3 => '3 Bulan (-5%)', 6 => '6 Bulan (-10%)', 12 => '1 Tahun (-20%)', default => '1 Bulan' };
            ?>
            <div class="flex justify-between items-center">
              <span class="text-gray-500">Durasi</span>
              <span class="font-semibold text-gray-800"><?= $label ?></span>
            </div>
          </div>
        </div>

        <!-- Confirmation Form -->
        <div class="space-y-4">
          <div>
            <h3 class="text-base font-extrabold text-gray-900 font-display">Konfirmasi Pembayaran</h3>
            <p class="text-xs text-gray-500 mt-0.5">Kirim data pengirim setelah melakukan scan QRIS.</p>
          </div>

          <form method="POST" action="<?= url('/billing/pay/' . $payment['external_id'] . '/confirm') ?>" class="space-y-3.5">
            <?= \App\Helpers\Csrf::field() ?>
            <input type="hidden" name="transfer_date" value="<?= date('Y-m-d') ?>">

            <div>
              <label class="block text-xs font-bold text-gray-700 mb-1">Nama Pemilik Akun / Pengirim <span class="text-red-500">*</span></label>
              <input type="text" name="sender_name" required placeholder="Nama akun yang digunakan saat scan"
                class="w-full border border-gray-300 rounded-xl px-3.5 py-2.5 text-xs focus:outline-none focus:ring-2 focus:ring-purple-500 bg-gray-50/50 font-semibold text-gray-900">
            </div>

            <div>
              <label class="block text-xs font-bold text-gray-700 mb-1">Catatan / No. Referensi RRN <span class="text-gray-400 font-normal">(opsional)</span></label>
              <input type="text" name="transfer_note" placeholder="Contoh: DANA a.n Budi / No. Ref..."
                class="w-full border border-gray-300 rounded-xl px-3.5 py-2.5 text-xs focus:outline-none focus:ring-2 focus:ring-purple-500 bg-gray-50/50 text-gray-900">
            </div>

            <button type="submit" class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-extrabold py-3.5 rounded-xl text-xs transition-all shadow-md shadow-purple-500/20 hover:shadow-lg active:scale-[0.99] cursor-pointer">
              Konfirmasi Pembayaran →
            </button>
          </form>
        </div>

        <!-- Footer Actions -->
        <div class="pt-4 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
          <?php if (!empty($bankWhatsapp)): ?>
            <?php 
              $waMsg = "Halo Admin " . $qrisMerchantName . ", saya sudah membayar tagihan QRIS untuk invoice " . $payment['external_id'] . " paket " . $payment['plan_name'] . " sebesar Rp " . number_format((float)$payment['amount'], 0, ',', '.') . ". Mohon bantu verifikasi.";
            ?>
            <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $bankWhatsapp) ?>?text=<?= urlencode($waMsg) ?>"
               target="_blank"
               class="inline-flex items-center gap-1.5 text-emerald-700 hover:text-emerald-800 bg-emerald-50 hover:bg-emerald-100/80 border border-emerald-200/80 px-3.5 py-2 rounded-xl font-bold transition-all w-full sm:w-auto justify-center">
              <svg class="w-3.5 h-3.5 text-emerald-600" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M11.75 2C6.375 2 2 6.375 2 11.75a9.685 9.685 0 001.347 4.942L2 22l5.417-1.34A9.72 9.72 0 0011.75 21.5C17.125 21.5 21.5 17.125 21.5 11.75S17.125 2 11.75 2zm0 17.75a8 8 0 01-4.084-1.121l-.292-.174-3.22.796.821-3.139-.19-.302A8 8 0 0111.75 3.75c4.411 0 8 3.589 8 8s-3.589 8-8 8z"/></svg>
              <span>Kirim Bukti via WhatsApp</span>
            </a>
          <?php endif; ?>

          <form method="POST" action="<?= url('/billing/pay/' . $payment['external_id'] . '/cancel') ?>" onsubmit="return confirm('Batalkan tagihan invoice ini?')">
            <?= \App\Helpers\Csrf::field() ?>
            <button type="submit" class="text-gray-400 hover:text-rose-600 transition-colors underline cursor-pointer text-xs">
              Batalkan Tagihan
            </button>
          </form>
        </div>

      </div>

    </div>

  </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
