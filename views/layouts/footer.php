    </div> <!-- Close p-6 md:p-10 -->
    
    <!-- Footer Credit -->
    <footer class="py-6 text-center text-xs text-gray-400 border-t border-gray-100 bg-white px-6">
      &copy; <?= date('Y') ?> Wapify By Sintesa Corporation. Hak Cipta Dilindungi.
    </footer>
  </div> <!-- Close flex-1 bg-gray-50 flex flex-col justify-between min-h-screen -->
</div> <!-- Close min-h-screen flex flex-col md:flex-row -->

<script>
function toggleMobileMenu() {
  var menu = document.getElementById('mobile-menu');
  menu.classList.toggle('hidden');
}

// Toast notification helper
function showToast(message, type = 'success') {
  var container = document.getElementById('toast-container');
  if (!container) return;

  var toast = document.createElement('div');
  var bgColor = type === 'success' ? 'bg-green-600' : (type === 'error' ? 'bg-red-600' : 'bg-blue-600');
  var icon = type === 'success' ? '✓' : (type === 'error' ? '✕' : 'ℹ');

  toast.className = `pointer-events-auto flex items-center p-4 rounded-xl text-white shadow-xl text-sm font-medium animate-toast ${bgColor}`;
  toast.innerHTML = `<span class="mr-3 text-lg font-bold">${icon}</span><span>${message}</span>`;

  container.appendChild(toast);

  setTimeout(function() {
    toast.style.opacity = '0';
    toast.style.transition = 'opacity 0.3s ease';
    setTimeout(function() { toast.remove(); }, 300);
  }, 4000);
}

// Copy to Clipboard helper
function copyToClipboard(text, buttonEl) {
  if (navigator.clipboard && window.isSecureContext) {
    navigator.clipboard.writeText(text).then(function() {
      showToast('Berhasil disalin ke clipboard!', 'success');
    }).catch(function() {
      fallbackCopy(text);
    });
  } else {
    fallbackCopy(text);
  }
}

function fallbackCopy(text) {
  var textArea = document.createElement("textarea");
  textArea.value = text;
  document.body.appendChild(textArea);
  textArea.select();
  try {
    document.execCommand('copy');
    showToast('Berhasil disalin ke clipboard!', 'success');
  } catch (err) {
    showToast('Gagal menyalin text.', 'error');
  }
  document.body.removeChild(textArea);
}

// Global submit button loading state handler
document.addEventListener('submit', function(e) {
  var form = e.target;
  var submitBtn = form.querySelector('button[type="submit"]');
  if (submitBtn && !submitBtn.disabled) {
    submitBtn.disabled = true;
    submitBtn.dataset.originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = `<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses...`;
  }
});
</script>
</body>
</html>
