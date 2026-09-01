<?php $title = 'Daftar'; require __DIR__ . '/../layouts/header.php'; ?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700&display=swap" rel="stylesheet">

<style>
  h1, .font-display {
    font-family: 'Outfit', sans-serif;
  }
  .gradient-bg {
    background: linear-gradient(135deg, #7C3AED 0%, #2563EB 100%);
  }
</style>

<div class="min-h-screen flex flex-col items-center justify-center px-4 bg-gray-50 py-12">
  <!-- Logo Section -->
  <div class="flex items-center gap-3 mb-8">
    <div class="gradient-bg p-2.5 rounded-xl shadow-md text-white">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
      </svg>
    </div>
    <span class="text-2xl font-bold tracking-tight text-gray-900 font-display">Wapify</span>
  </div>

  <div class="w-full max-w-sm bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
    <h1 class="text-2xl font-semibold mb-6 text-gray-800 text-center">Buat Akun Baru</h1>
    
    <?php if (!empty($error)): ?>
      <div class="mb-4 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-2.5"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="<?= url('/register') ?>" class="space-y-4" onsubmit="return validateForm()">
      <?= \App\Helpers\Csrf::field() ?>
      <div>
        <label class="block text-sm font-semibold text-gray-600 mb-1">Nama</label>
        <input type="text" name="name" required placeholder="Nama Anda" class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
      </div>
      
      <div>
        <label class="block text-sm font-semibold text-gray-600 mb-1">Email</label>
        <!-- HTML5 strict validation + pattern validation -->
        <input type="email" name="email" required placeholder="nama@email.com" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}" title="Masukkan format email yang valid (contoh: user@domain.com)" class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
      </div>
      
      <div>
        <label class="block text-sm font-semibold text-gray-600 mb-1">Password</label>
        <div class="relative">
          <input type="password" id="password" name="password" required minlength="8" placeholder="Minimal 8 karakter" class="w-full rounded-lg border border-gray-300 pl-3 pr-10 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
          <button type="button" onclick="togglePasswordVisibility('password', this)" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
          </button>
        </div>
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-600 mb-1">Konfirmasi Password</label>
        <div class="relative">
          <input type="password" id="password_confirm" name="password_confirm" required placeholder="Ulangi password" class="w-full rounded-lg border border-gray-300 pl-3 pr-10 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
          <button type="button" onclick="togglePasswordVisibility('password_confirm', this)" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
          </button>
        </div>
        <p id="match-error" class="hidden text-xs text-red-500 mt-1">Konfirmasi password tidak cocok.</p>
      </div>

      <button type="submit" class="w-full bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white font-bold py-2.5 px-4 rounded-lg shadow-lg shadow-purple-500/20 transition-all hover:scale-[1.01]">Daftar</button>
    </form>

    <p class="text-sm text-gray-500 mt-6 text-center">Sudah punya akun? <a href="<?= url('/login') ?>" class="text-purple-600 font-semibold hover:underline">Masuk</a></p>
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

function validateForm() {
  var password = document.getElementById('password').value;
  var passwordConfirm = document.getElementById('password_confirm').value;
  var errorMsg = document.getElementById('match-error');
  
  if (password !== passwordConfirm) {
    errorMsg.classList.remove('hidden');
    return false;
  }
  errorMsg.classList.add('hidden');
  return true;
}
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
