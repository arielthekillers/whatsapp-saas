<?php $title = 'Billing'; require __DIR__ . '/../layouts/header.php'; require __DIR__ . '/../layouts/nav.php'; ?>
<style>
  .active-duration-btn {
    background-color: #7C3AED;
    color: white !important;
    box-shadow: 0 4px 12px rgba(124, 58, 237, 0.25);
  }
  .active-duration-btn .badge-discount {
    background-color: rgba(255, 255, 255, 0.2) !important;
    color: white !important;
  }
  .duration-btn:not(.active-duration-btn) {
    color: #4B5563;
  }
  .duration-btn:not(.active-duration-btn):hover {
    color: #111827;
    background-color: #F3F4F6;
  }
</style>

<div class="max-w-5xl mx-auto px-4 py-8">
  <!-- Centered Header -->
  <div class="text-center mb-8">
    <h1 class="text-2xl font-bold tracking-tight text-gray-900 font-display">Paket &amp; Langganan</h1>
    <p class="text-gray-500 text-sm mt-1 max-w-2xl mx-auto">Upgrade paket Anda untuk meningkatkan kuota pengiriman pesan dan batas sesi WhatsApp.</p>
  </div>
  
  <!-- Sleek Segmented Switcher Control -->
  <div class="flex justify-center mb-10">
    <div class="inline-flex bg-white p-1.5 rounded-2xl border border-gray-200 shadow-sm gap-1.5">
      <button type="button" onclick="selectDuration(1, 0.00, this)" class="duration-btn active-duration-btn text-xs font-bold px-4 py-2.5 rounded-xl transition-all focus:outline-none flex items-center gap-2">
        1 Bulan
      </button>
      <button type="button" onclick="selectDuration(3, 0.05, this)" class="duration-btn text-xs font-bold px-4 py-2.5 rounded-xl transition-all focus:outline-none flex items-center gap-2">
        <span>3 Bulan</span>
        <span class="badge-discount bg-red-50 text-red-600 text-[10px] font-extrabold px-1.5 py-0.5 rounded-md transition-all">-5%</span>
      </button>
      <button type="button" onclick="selectDuration(6, 0.10, this)" class="duration-btn text-xs font-bold px-4 py-2.5 rounded-xl transition-all focus:outline-none flex items-center gap-2">
        <span>6 Bulan</span>
        <span class="badge-discount bg-red-50 text-red-600 text-[10px] font-extrabold px-1.5 py-0.5 rounded-md transition-all">-10%</span>
      </button>
      <button type="button" onclick="selectDuration(12, 0.20, this)" class="duration-btn text-xs font-bold px-4 py-2.5 rounded-xl transition-all focus:outline-none flex items-center gap-2">
        <span>1 Tahun</span>
        <span class="badge-discount bg-red-50 text-red-600 text-[10px] font-extrabold px-1.5 py-0.5 rounded-md transition-all">-20%</span>
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

  <!-- Grid Paket -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
    <?php foreach ($allPlans as $p): ?>
      <?php 
        $isActive = ($activeSub && (int)$activeSub['plan_id'] === (int)$p['id']);
        $isPopular = ($p['name'] === 'PRO');
        $borderClass = $isActive 
          ? 'border-2 border-purple-500 shadow-lg relative' 
          : ($isPopular ? 'border-2 border-blue-500 shadow-sm relative' : 'border border-gray-200 shadow-sm');
      ?>
      <div class="bg-white rounded-2xl p-6 flex flex-col justify-between plan-card <?= $borderClass ?>" data-base-price="<?= (float)$p['price'] ?>">
        <?php if ($isActive): ?>
          <span class="absolute -top-3 left-1/2 transform -translate-x-1/2 bg-purple-500 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">Aktif</span>
        <?php elseif ($isPopular): ?>
          <span class="absolute -top-3 left-1/2 transform -translate-x-1/2 bg-blue-500 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">Terpopuler</span>
        <?php endif; ?>

        <div>
          <h3 class="text-xl font-bold text-gray-900 font-display mb-1"><?= htmlspecialchars($p['name']) ?></h3>
          <p class="text-xs text-gray-400 mb-4"><?= htmlspecialchars($p['description'] ?? '') ?></p>
          
          <div class="mb-4 bg-gray-50 rounded-xl p-3 border border-gray-100">
            <span class="text-3xl font-extrabold text-gray-900 font-display price-display">Rp <?= number_format((float)$p['price'], 0, ',', '.') ?></span>
            <span class="text-gray-400 text-xs">/ bln</span>
            <p class="text-xs text-purple-600 font-bold mt-1.5 hidden total-display"></p>
          </div>

          <ul class="space-y-3 text-sm text-gray-600 mb-6">
            <li class="flex items-center gap-2 font-semibold text-purple-700 bg-purple-50 p-2 rounded-lg border border-purple-100">
              <svg class="w-4 h-4 text-purple-600 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
              </svg>
              <span><strong><?= (int)$p['session_limit'] ?></strong> WhatsApp Session</span>
            </li>
            <li class="flex items-center gap-2">
              <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
              </svg>
              <span><strong><?= number_format((float)$p['message_limit']) ?></strong> Pesan / bulan</span>
            </li>
            <li class="flex items-center gap-2">
              <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
              </svg>
              <span>Rate Limit <strong><?= (int)$p['rate_limit_per_minute'] ?></strong> req/menit</span>
            </li>
            <li class="flex items-center gap-2">
              <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
              </svg>
              <span>API Key &amp; Webhook Instant</span>
            </li>
            <?php if ($p['name'] === 'PRO' || $p['name'] === 'BUSINESS'): ?>
              <li class="flex items-center gap-2">
                <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                <span>Kirim Gambar &amp; Dokumen (Media)</span>
              </li>
              <li class="flex items-center gap-2">
                <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                <span>Priority Server Processing</span>
              </li>
            <?php endif; ?>
            <?php if ($p['name'] === 'BUSINESS'): ?>
              <li class="flex items-center gap-2">
                <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                <span>Kirim Audio, Video &amp; Location</span>
              </li>
              <li class="flex items-center gap-2">
                <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                <span>Custom Device Label &amp; Priority Support</span>
              </li>
            <?php endif; ?>
          </ul>
        </div>

        <div>
          <?php if ($isActive): ?>
            <button disabled class="w-full bg-gray-100 text-gray-400 font-bold py-2.5 rounded-xl cursor-not-allowed text-sm">Paket Anda Saat Ini</button>
          <?php else: ?>
            <button type="button" onclick="openCheckoutModal(<?= (int)$p['id'] ?>, '<?= htmlspecialchars($p['name'], ENT_QUOTES) ?>', <?= (float)$p['price'] ?>)" class="w-full bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white font-bold py-2.5 rounded-xl text-sm transition-all hover:scale-[1.01] shadow-lg shadow-purple-500/10">Pilih Paket</button>
          <?php endif; ?>
        </div>
      </div>
    <?php endforeach; ?>
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
        // Update hidden input jika ada
        var input = card.querySelector('.duration-input');
        if (input) {
            input.value = months;
        }

        // Hitung harga terdiskon
        var basePrice = parseFloat(card.getAttribute('data-base-price'));
        if (!basePrice) return;

        var rawTotal = basePrice * months;
        var total = rawTotal * (1 - discount);

        var priceDisplay = card.querySelector('.price-display');
        var totalDisplay = card.querySelector('.total-display');

        // Tampilkan harga bulanan terhitung
        priceDisplay.innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(total / months));
        
        if (months > 1) {
            totalDisplay.innerText = 'Total: Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(total)) + ' (' + (months === 12 ? '1 Tahun' : months + ' Bulan') + ')';
            totalDisplay.classList.remove('hidden');
        } else {
            totalDisplay.classList.add('hidden');
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
