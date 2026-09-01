<?php $title = 'Profil Akun'; require __DIR__ . '/../layouts/header.php'; require __DIR__ . '/../layouts/nav.php'; ?>
<div class="max-w-xl mx-auto px-4 py-8">
  <div class="mb-6">
    <h1 class="text-2xl font-bold tracking-tight text-gray-900 font-display">Profil Akun</h1>
    <p class="text-gray-500 text-sm mt-1">Kelola data profil dan keamanan kata sandi akun Anda.</p>
  </div>

  <?php if (!empty($success)): ?>
    <div class="mb-6 rounded-lg bg-green-50 border border-green-200 p-4 text-sm text-green-700 font-medium">
      <?= htmlspecialchars($success) ?>
    </div>
  <?php endif; ?>

  <?php if (!empty($error)): ?>
    <div class="mb-6 rounded-lg bg-red-50 border border-red-200 p-4 text-sm text-red-700 font-medium">
      <?= htmlspecialchars($error) ?>
    </div>
  <?php endif; ?>

  <!-- Card Detail Akun -->
  <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 mb-6 space-y-4">
    <h2 class="text-lg font-bold text-gray-800 font-display">Informasi Pengguna</h2>
    <div class="grid grid-cols-1 gap-4">
      <div>
        <label class="block text-xs font-bold uppercase tracking-wider text-gray-400 mb-1">Nama Lengkap</label>
        <div class="bg-gray-50 text-gray-800 rounded-lg px-3 py-2 border border-gray-200 font-medium">
          <?= htmlspecialchars($user['name']) ?>
        </div>
      </div>
      <div>
        <label class="block text-xs font-bold uppercase tracking-wider text-gray-400 mb-1">Alamat Email</label>
        <div class="bg-gray-50 text-gray-800 rounded-lg px-3 py-2 border border-gray-200 font-mono text-sm">
          <?= htmlspecialchars($user['email']) ?>
        </div>
      </div>
    </div>
  </div>

  <!-- Card Ubah Password -->
  <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
    <h2 class="text-lg font-bold text-gray-800 font-display mb-4">Perbarui Password</h2>
    <form method="POST" action="<?= url('/profile/password') ?>" class="space-y-4" onsubmit="return validatePasswordForm()">
      <?= \App\Helpers\Csrf::field() ?>
      
      <div>
        <label class="block text-sm font-semibold text-gray-600 mb-1">Password Saat Ini</label>
        <div class="relative">
          <input type="password" id="current_password" name="current_password" required placeholder="••••••••" class="w-full rounded-lg border border-gray-300 pl-3 pr-10 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500">
          <button type="button" onclick="togglePasswordVisibility('current_password', this)" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
          </button>
        </div>
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-600 mb-1">Password Baru</label>
        <div class="relative">
          <input type="password" id="new_password" name="new_password" required minlength="8" placeholder="Minimal 8 karakter" class="w-full rounded-lg border border-gray-300 pl-3 pr-10 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500">
          <button type="button" onclick="togglePasswordVisibility('new_password', this)" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
          </button>
        </div>
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-600 mb-1">Konfirmasi Password Baru</label>
        <div class="relative">
          <input type="password" id="confirm_password" name="confirm_password" required placeholder="Ulangi password baru" class="w-full rounded-lg border border-gray-300 pl-3 pr-10 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500">
          <button type="button" onclick="togglePasswordVisibility('confirm_password', this)" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
          </button>
        </div>
        <p id="password-match-error" class="hidden text-xs text-red-500 mt-1">Konfirmasi password baru tidak cocok.</p>
      </div>

      <div class="flex justify-end pt-2">
        <button type="submit" class="bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white text-sm font-bold px-6 py-2.5 rounded-lg shadow-lg shadow-purple-500/10 transition-all hover:scale-[1.01]">Perbarui Password</button>
      </div>
    </form>
  </div>
</div>

<script>
function togglePasswordVisibility(inputId, button) {
  var input = document.getElementById(inputId);
  var svg = button.querySelector('svg');
  if (input.type === "password") {
    input.type = "text";
    svg.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />';
  } else {
    input.type = "password";
    svg.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
  }
}

function validatePasswordForm() {
  var newPassword = document.getElementById('new_password').value;
  var confirmPassword = document.getElementById('confirm_password').value;
  var errorMsg = document.getElementById('password-match-error');
  
  if (newPassword !== confirmPassword) {
    errorMsg.classList.remove('hidden');
    return false;
  }
  errorMsg.classList.add('hidden');
  return true;
}
</script>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
