<?php
// Layout khusus publik untuk Dokumentasi API (Top Navbar bersih & modern)
$currentPath = (string) parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
?>
<style>
  .glass-docs {
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
  }
</style>

<div class="min-h-screen flex flex-col bg-gray-50">
  <!-- Public Docs Header Navbar -->
  <header class="sticky top-0 z-40 glass-docs border-b border-gray-200/80 shadow-xs">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-4">
      <!-- Logo Brand -->
      <a href="<?= url('/') ?>" class="flex items-center gap-2 sm:gap-2.5 hover:opacity-90 transition-all shrink-0">
        <div class="bg-gradient-to-br from-purple-600 to-blue-600 p-1.5 sm:p-2 rounded-xl text-white shadow-md shadow-purple-500/10 flex items-center justify-center">
          <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
          </svg>
        </div>
        <div class="flex items-center gap-1.5">
          <span class="text-base sm:text-xl font-bold tracking-tight text-gray-900 font-display">Wapify</span>
          <span class="px-1.5 py-0.5 rounded text-[9px] sm:text-[10px] font-extrabold uppercase tracking-wider bg-purple-100 text-purple-700 border border-purple-200">Docs</span>
        </div>
      </a>

      <!-- Right Action Items -->
      <div class="flex items-center gap-2">
        <a href="<?= url('/') ?>" class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-2 rounded-xl text-purple-700 bg-purple-50 hover:bg-purple-100 border border-purple-200 transition-all shadow-xs">
          <svg class="w-4 h-4 text-purple-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
          <span>Landing Page</span>
        </a>
      </div>
    </div>
  </header>

  <!-- Docs Content Container -->
  <main class="flex-1 max-w-7xl w-full mx-auto p-4 sm:p-6 md:p-8">
