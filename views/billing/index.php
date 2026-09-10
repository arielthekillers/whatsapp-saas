<?php $title = 'Billing'; require __DIR__ . '/../layouts/header.php'; require __DIR__ . '/../layouts/nav.php'; ?>
<style>
  .active-duration-btn {
    background-color: #7C3AED;
    color: white !important;
    box-shadow: 0 4px 12px rgba(124, 58, 237, 0.25);
  }
  .active-duration-btn .badge-discount {
    background-color: #EF4444 !important;
    color: white !important;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
  }
  .duration-btn:not(.active-duration-btn) {
    color: #4B5563;
  }
  .duration-btn:not(.active-duration-btn):hover {
    color: #111827;
    background-color: #F3F4F6;
  }
</style>

<div>
  <!-- Header Section -->
  <div class="mb-8">
    <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight font-display">Paket &amp; Langganan</h1>
    <p class="text-sm text-gray-500 mt-1">Upgrade paket Anda untuk meningkatkan kuota pengiriman pesan dan batas sesi WhatsApp.</p>
  </div>
  
  <!-- Sleek Segmented Switcher Control (Equal Width & Overlapping Top Badge) -->
  <div class="flex justify-center mb-10 w-full max-w-xl mx-auto px-2 pt-2">
    <div class="grid grid-cols-4 bg-white p-1.5 rounded-2xl border border-gray-200 shadow-sm w-full gap-1">
      <!-- 1 Bulan -->
      <button type="button" onclick="selectDuration(1, 0.00, this)" class="duration-btn active-duration-btn text-xs font-bold py-2.5 rounded-xl transition-all focus:outline-none flex items-center justify-center relative">
        <span>1 Bulan</span>
      </button>

      <!-- 3 Bulan -->
      <button type="button" onclick="selectDuration(3, 0.05, this)" class="duration-btn text-xs font-bold py-2.5 rounded-xl transition-all focus:outline-none flex items-center justify-center relative">
        <span class="badge-discount absolute -top-4 z-10 bg-red-500 text-white text-[9px] font-black px-2 py-0.5 rounded-full shadow-md tracking-tight transition-all border-2 border-white">-5%</span>
        <span>3 Bulan</span>
      </button>

      <!-- 6 Bulan -->
      <button type="button" onclick="selectDuration(6, 0.10, this)" class="duration-btn text-xs font-bold py-2.5 rounded-xl transition-all focus:outline-none flex items-center justify-center relative">
        <span class="badge-discount absolute -top-4 z-10 bg-red-500 text-white text-[9px] font-black px-2 py-0.5 rounded-full shadow-md tracking-tight transition-all border-2 border-white">-10%</span>
        <span>6 Bulan</span>
      </button>

      <!-- 1 Tahun -->
      <button type="button" onclick="selectDuration(12, 0.20, this)" class="duration-btn text-xs font-bold py-2.5 rounded-xl transition-all focus:outline-none flex items-center justify-center relative">
        <span class="badge-discount absolute -top-4 z-10 bg-red-500 text-white text-[9px] font-black px-2 py-0.5 rounded-full shadow-md tracking-tight transition-all border-2 border-white">-20%</span>
        <span>1 Tahun</span>
      </button>
    </div>
  </div>

  <?php if (!empty($success)): ?>
    <div class="mb-6 rounded-lg bg-green-50 border border-green-200 p-4 text-sm text-green-700 font-medium">
      <?= htmlspecialchars($success) ?>
    </div>
  <?php endif; ?>

  <?php if (!empty($error)): ?>
    <div class="mb-6 rounded-lg bg-red-50 border border-red-200 p-4 text-sm text-red-700">
      <?= htmlspecialchars($error) ?>
    </div>
  <?php endif; ?>

  <!-- Grid & Carousel Slider Paket -->
  <div class="max-w-5xl mx-auto mb-12">
    <div class="flex md:grid md:grid-cols-3 gap-5 justify-start md:justify-center items-stretch overflow-x-auto snap-x snap-mandatory pt-3 pb-6 -mx-4 px-4 md:mx-0 md:px-0 scroll-smooth no-scrollbar" style="overflow-y: visible;">
    <?php foreach ($allPlans as $p): ?>
      <?php 
        $isActive = ($activeSub && (int)$activeSub['plan_id'] === (int)$p['id']);
        $pName = strtoupper(trim($p['name']));
        $isPopular = ($pName === 'PRO');

        // Palet Warna Premium SaaS per Paket:
        $theme = match($pName) {
            'LITE' => [
                'cardBorder'   => 'border: 1px solid #E5E7EB;',
                'headerBg'     => 'background: linear-gradient(135deg, #059669 0%, #10B981 100%);',
                'badgeBg'      => 'background: #047857; color: white;',
                'priceBg'      => 'background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.25); color: white;',
                'sessionBox'   => 'background: #ECFDF5; border: 1px solid #A7F3D0; color: #047857;',
                'sessionIcon'  => 'text-emerald-600',
                'btnClass'     => 'background: linear-gradient(135deg, #059669 0%, #10B981 100%); color: white; box-shadow: 0 8px 20px -4px rgba(16,185,129,0.4);',
            ],
            'BUSINESS' => [
                'cardBorder'   => 'border: 1px solid #E5E7EB;',
                'headerBg'     => 'background: linear-gradient(135deg, #D97706 0%, #F59E0B 100%);',
                'badgeBg'      => 'background: #B45309; color: white;',
                'priceBg'      => 'background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.25); color: white;',
                'sessionBox'   => 'background: #FFFBEB; border: 1px solid #FDE68A; color: #B45309;',
                'sessionIcon'  => 'text-amber-600',
                'btnClass'     => 'background: linear-gradient(135deg, #D97706 0%, #F59E0B 100%); color: white; box-shadow: 0 8px 20px -4px rgba(245,158,11,0.4);',
            ],
            default => [ // PRO / Default (Hero Card)
                'cardBorder'   => 'border: 2px solid #7C3AED;',
                'headerBg'     => 'background: linear-gradient(135deg, #6D28D9 0%, #8B5CF6 100%);',
                'badgeBg'      => 'background: rgba(255,255,255,0.25); color: white; border: 1px solid rgba(255,255,255,0.3);',
                'priceBg'      => 'background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.25); color: white;',
                'sessionBox'   => 'background: #F3E8FF; border: 1px solid #DDD6FE; color: #6D28D9;',
                'sessionIcon'  => 'text-purple-600',
                'btnClass'     => 'background: linear-gradient(135deg, #6D28D9 0%, #8B5CF6 100%); color: white; box-shadow: 0 8px 20px -4px rgba(124,58,237,0.5);',
            ]
        };

        // Deskripsi Paket Sangat Menjual & Konsisten 2 Baris:
        $sellingDescriptions = [
            'LITE'       => 'Solusi hemat & handal untuk testing API, notifikasi otomatis, dan integrasi skala awal.',
            'PRO'        => 'Pilihan paling favorit untuk sistem informasi, e-commerce, bot otomatis, & aplikasi bisnis.',
            'BUSINESS'   => 'Solusi paling powerful untuk broadcast skala besar, multi-nomor WA, & infrastruktur enterprise.',
        ];
        $planDesc = $sellingDescriptions[$pName] ?? htmlspecialchars($p['description'] ?? '');
      ?>
      <div class="bg-white rounded-3xl flex flex-col justify-between plan-card overflow-hidden transition-transform duration-300 hover:-translate-y-2 relative group hover:shadow-2xl shrink-0 snap-center md:snap-align-none" style="min-width: 270px; max-width: 320px; width: 80vw; <?= $theme['cardBorder'] ?> <?= $shadowStyle ?>" data-base-price="<?= (float)$p['price'] ?>">
        
        <div>
          <!-- Header Card Berwarna Kustom & Gradien Mewah -->
          <div class="p-6 text-white relative flex flex-col justify-between min-h-[220px]" style="<?= $theme['headerBg'] ?>">
            <div>
              <div class="flex items-center justify-between gap-2 mb-2">
                <h3 class="text-2xl font-black font-display tracking-tight text-white"><?= htmlspecialchars($p['name']) ?></h3>
                <?php if ($isActive): ?>
                  <span class="text-[9px] font-extrabold px-2.5 py-0.5 rounded-full uppercase tracking-wider shadow-xs" style="<?= $theme['badgeBg'] ?>">
                    Aktif
                  </span>
                <?php elseif ($isPopular): ?>
                  <span class="text-[9px] font-extrabold px-2.5 py-0.5 rounded-full uppercase tracking-wider shadow-xs" style="<?= $theme['badgeBg'] ?>">
                    Terpopuler
                  </span>
                <?php endif; ?>
              </div>

              <p class="text-xs text-white/90 leading-relaxed font-medium mb-4 h-10 overflow-hidden text-ellipsis line-clamp-2"><?= $planDesc ?></p>
            </div>
            
            <div class="rounded-2xl p-4 backdrop-blur-md transition-all" style="<?= $theme['priceBg'] ?>">
              <!-- Element Harga Asli Satuan Tergaris (Strikethrough) -->
              <div class="flex items-center justify-between gap-1 mb-1.5 original-price-container hidden overflow-hidden">
                <span class="text-[9px] text-white/70 line-through font-semibold original-price-display">Rp 0/bln</span>
                <span class="text-[8px] font-black px-1.5 py-0.5 rounded bg-red-500 text-white leading-none shadow-2xs discount-badge-text">-0%</span>
              </div>
              <div class="flex items-baseline gap-1">
                <span class="text-3xl font-black font-display tracking-tight price-display">Rp <?= number_format((float)$p['price'], 0, ',', '.') ?></span>
                <span class="text-xs text-white/80 font-semibold">/ bulan</span>
              </div>
              <div class="mt-1.5 hidden total-display">
                <div class="text-[11px] font-bold text-white/90 total-price-text">Rp 0 / 3 bln</div>
              </div>
            </div>
          </div>

          <!-- Fitur List dengan Visual Bersih -->
          <div class="p-6 sm:p-7 space-y-4">
            <div class="flex items-center gap-3 font-bold p-3 rounded-2xl text-xs" style="<?= $theme['sessionBox'] ?>">
              <svg class="w-5 h-5 <?= $theme['sessionIcon'] ?> flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
              </svg>
              <span><strong><?= (int)$p['session_limit'] ?></strong> WhatsApp Session</span>
            </div>

            <ul class="space-y-3.5 text-xs text-gray-600 pt-1">
              <li class="flex items-center gap-2.5">
                <div class="w-5 h-5 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                  <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                </div>
                <span><strong><?= number_format((float)$p['message_limit']) ?></strong> Pesan / bulan</span>
              </li>
              <li class="flex items-center gap-2.5">
                <div class="w-5 h-5 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                  <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                </div>
                <span>Rate Limit <strong><?= (int)$p['rate_limit_per_minute'] ?></strong> req/menit</span>
              </li>
              <li class="flex items-center gap-2.5">
                <div class="w-5 h-5 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                  <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                </div>
                <span>API Key &amp; Webhook Instant</span>
              </li>
              <?php if ($pName === 'PRO' || $pName === 'BUSINESS'): ?>
                <li class="flex items-center gap-2.5">
                  <div class="w-5 h-5 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                  </div>
                  <span>Kirim Gambar &amp; Dokumen (Media)</span>
                </li>
                <li class="flex items-center gap-2.5">
                  <div class="w-5 h-5 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                  </div>
                  <span>Priority Server Processing</span>
                </li>
              <?php endif; ?>
              <?php if ($pName === 'BUSINESS'): ?>
                <li class="flex items-center gap-2.5">
                  <div class="w-5 h-5 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                  </div>
                  <span>Kirim Audio, Video &amp; Location</span>
                </li>
                <li class="flex items-center gap-2.5">
                  <div class="w-5 h-5 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                  </div>
                  <span>Custom Device Label &amp; Priority Support</span>
                </li>
              <?php endif; ?>
            </ul>
          </div>
        </div>

        <div class="px-6 pb-7 sm:px-7">
          <?php if ($isActive): ?>
            <button disabled class="w-full bg-gray-100 text-gray-400 font-bold py-3 rounded-2xl cursor-not-allowed text-xs">Paket Anda Saat Ini</button>
          <?php else: ?>
            <button type="button" onclick="openCheckoutModal(<?= (int)$p['id'] ?>, '<?= htmlspecialchars($p['name'], ENT_QUOTES) ?>', <?= (float)$p['price'] ?>)" class="w-full font-black py-3.5 rounded-2xl text-xs tracking-wider uppercase transition-all duration-300 hover:scale-[1.02] active:scale-[0.98]" style="<?= $theme['btnClass'] ?>">
              Pilih Paket <?= htmlspecialchars($p['name']) ?> →
            </button>
          <?php endif; ?>
        </div>
      </div>
    <?php endforeach; ?>
    </div>
  </div>

  <!-- Modal Konfirmasi Pemesanan -->
  <div id="checkout-modal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
      <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-xs transition-opacity" onclick="closeCheckoutModal()"></div>
      <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
      <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100">
        <div class="bg-white p-6 space-y-5">
          <div class="flex items-center justify-between border-b border-gray-100 pb-4">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center font-bold text-lg">
                🛒
              </div>
              <div>
                <h3 class="text-lg font-bold text-gray-900 font-display">Konfirmasi Pemesanan</h3>
                <p class="text-xs text-gray-500">Periksa rincian paket sebelum membuat tagihan invoice.</p>
              </div>
            </div>
            <button type="button" onclick="closeCheckoutModal()" class="text-gray-400 hover:text-gray-600 p-1.5 rounded-lg hover:bg-gray-100 transition-colors">
              ✕
            </button>
          </div>

          <div class="bg-purple-50/50 rounded-xl p-4 border border-purple-100 space-y-2 text-sm">
            <div class="flex justify-between items-center">
              <span class="text-gray-500">Paket Diminta:</span>
              <span id="modal-plan-name" class="font-extrabold text-purple-700 font-display">-</span>
            </div>
            <div class="flex justify-between items-center">
              <span class="text-gray-500">Durasi Langganan:</span>
              <span id="modal-duration" class="font-semibold text-gray-800">1 Bulan</span>
            </div>
            <div class="flex justify-between items-center text-xs text-gray-500">
              <span>Harga Dasar:</span>
              <span id="modal-base-price">Rp 0 / bln</span>
            </div>
            <div id="modal-discount-row" class="flex justify-between items-center text-xs text-green-600 hidden">
              <span>Potongan Diskon:</span>
              <span id="modal-discount-val" class="font-bold">- Rp 0</span>
            </div>
            <div class="border-t border-purple-200/60 pt-2.5 mt-2 flex justify-between items-baseline">
              <span class="font-bold text-gray-900">Total Pembayaran:</span>
              <span id="modal-total-price" class="text-2xl font-black text-purple-700 font-display">Rp 0</span>
            </div>
          </div>

          <p class="text-xs text-gray-500 bg-gray-50 p-3 rounded-xl border border-gray-100 leading-relaxed">
            💡 Invoice pembayaran dengan status <strong>pending</strong> hanya akan dibuat setelah Anda menekan tombol konfirmasi di bawah.
          </p>

          <form id="checkout-confirm-form" method="POST" action="<?= url('/billing/checkout') ?>">
            <?= \App\Helpers\Csrf::field() ?>
            <input type="hidden" name="plan_id" id="modal-input-plan-id" value="">
            <input type="hidden" name="duration_months" id="modal-input-duration" value="1">
            
            <div class="flex items-center justify-end gap-3 pt-2">
              <button type="button" onclick="closeCheckoutModal()" class="px-4 py-2.5 rounded-xl border border-gray-300 text-gray-700 font-bold text-xs hover:bg-gray-50 transition-colors">
                Batal
              </button>
              <button type="submit" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white font-bold text-xs transition-all shadow-md shadow-purple-500/20">
                Konfirmasi &amp; Lanjutkan Pembayaran →
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Riwayat Pembayaran -->
  <h2 class="text-lg font-bold text-gray-800 mb-4 font-display">Riwayat Transaksi</h2>
  <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden overflow-x-auto">
    <table class="w-full text-sm">
      <thead class="bg-gray-50 text-left text-gray-500">
        <tr>
          <th class="px-6 py-3 font-semibold">Nomor Invoice</th>
          <th class="px-6 py-3 font-semibold">Paket</th>
          <th class="px-6 py-3 font-semibold">Total Bayar</th>
          <th class="px-6 py-3 font-semibold">Status</th>
          <th class="px-6 py-3 font-semibold">Tanggal</th>
          <th class="px-6 py-3"></th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100">
        <?php if (empty($payments)): ?>
          <tr>
            <td colspan="6" class="px-6 py-8 text-center text-gray-400">Belum ada transaksi pembayaran.</td>
          </tr>
        <?php endif; ?>
        <?php foreach ($payments as $pay): ?>
          <tr class="hover:bg-gray-50/50 transition-colors">
            <td class="px-6 py-4 font-semibold text-gray-700"><?= htmlspecialchars($pay['external_id']) ?></td>
            <td class="px-6 py-4 text-gray-600"><?= htmlspecialchars($pay['plan_name'] ?? 'Wapify Plan') ?></td>
            <td class="px-6 py-4 font-medium text-gray-900">Rp <?= number_format((float)$pay['amount'], 0, ',', '.') ?></td>
            <td class="px-6 py-4">
              <?php
                $badge = match($pay['status']) {
                  'paid' => 'bg-green-50 text-green-700 border border-green-200',
                  'pending' => 'bg-yellow-50 text-yellow-700 border border-yellow-200',
                  'verifying' => 'bg-blue-50 text-blue-700 border border-blue-200',
                  'cancelled' => 'bg-gray-100 text-gray-600 border border-gray-200',
                  'expired', 'failed' => 'bg-red-50 text-red-700 border border-red-200',
                  default => 'bg-gray-50 text-gray-600'
                };
              ?>
              <span class="px-2 py-0.5 rounded-full text-xs font-semibold <?= $badge ?>"><?= htmlspecialchars($pay['status']) ?></span>
            </td>
            <td class="px-6 py-4 text-gray-500 text-xs"><?= htmlspecialchars($pay['created_at']) ?></td>
            <td class="px-6 py-4 text-right">
              <?php if ($pay['status'] === 'pending'): ?>
                <a href="<?= url('/billing/pay/' . $pay['external_id']) ?>" class="text-purple-600 hover:text-purple-800 font-bold transition-colors">Bayar</a>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
var currentSelectedDuration = 1;
var currentSelectedDiscount = 0.00;

function selectDuration(months, discount, button) {
    currentSelectedDuration = months;
    currentSelectedDiscount = discount;

    // 1. Update tombol active
    var buttons = document.querySelectorAll('.duration-btn');
    buttons.forEach(btn => btn.classList.remove('active-duration-btn'));
    button.classList.add('active-duration-btn');

    // 2. Update harga dan input di semua kartu plan
    var cards = document.querySelectorAll('.plan-card');
    cards.forEach(card => {
        var input = card.querySelector('.duration-input');
        if (input) {
            input.value = months;
        }

        var basePrice = parseFloat(card.getAttribute('data-base-price'));
        if (!basePrice) return;

        var rawTotal = basePrice * months;
        var total = rawTotal * (1 - discount);
        var savings = rawTotal - total;

        var priceDisplay = card.querySelector('.price-display');
        var totalDisplay = card.querySelector('.total-display');
        var origContainer = card.querySelector('.original-price-container');
        var origDisplay = card.querySelector('.original-price-display');
        var discountBadge = card.querySelector('.discount-badge-text');

        // Tampilkan harga bulanan terhitung
        priceDisplay.innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(total / months));
        
        if (months > 1 && discount > 0) {
            // Tampilkan harga satuan asli tergaris tengah (Option A)
            if (origDisplay) {
                origDisplay.innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(basePrice) + '/bln';
            }
            if (discountBadge) {
                discountBadge.innerText = '-' + Math.round(discount * 100) + '%';
            }
            if (origContainer) {
                origContainer.classList.remove('hidden');
            }
            
            var totalTxtEl = card.querySelector('.total-price-text');
            var durText = months === 12 ? '1 thn' : months + ' bln';
            
            if (totalTxtEl) {
                totalTxtEl.innerText = 'Total: Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(total)) + ' / ' + durText;
            }
            if (totalDisplay) {
                totalDisplay.classList.remove('hidden');
            }
        } else {
            if (origContainer) {
                origContainer.classList.add('hidden');
            }
            if (totalDisplay) {
                totalDisplay.classList.add('hidden');
            }
        }
    });
}

function openCheckoutModal(planId, planName, basePrice) {
    document.getElementById('modal-input-plan-id').value = planId;
    document.getElementById('modal-input-duration').value = currentSelectedDuration;
    
    document.getElementById('modal-plan-name').innerText = planName;
    
    var durationText = currentSelectedDuration === 12 ? '1 Tahun' : currentSelectedDuration + ' Bulan';
    if (currentSelectedDiscount > 0) {
        durationText += ' (Diskon ' + (Math.round(currentSelectedDiscount * 100)) + '%)';
    }
    document.getElementById('modal-duration').innerText = durationText;
    
    document.getElementById('modal-base-price').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(basePrice) + ' / bln';
    
    var rawTotal = basePrice * currentSelectedDuration;
    var total = rawTotal * (1 - currentSelectedDiscount);
    var discountVal = rawTotal - total;
    
    var discountRow = document.getElementById('modal-discount-row');
    if (discountVal > 0) {
        discountRow.classList.remove('hidden');
        document.getElementById('modal-discount-val').innerText = '- Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(discountVal));
    } else {
        discountRow.classList.add('hidden');
    }
    
    document.getElementById('modal-total-price').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(total));
    
    document.getElementById('checkout-modal').classList.remove('hidden');
}

function closeCheckoutModal() {
    document.getElementById('checkout-modal').classList.add('hidden');
}
</script>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
