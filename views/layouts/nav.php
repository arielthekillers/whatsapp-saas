<?php
$currentPath = (string) parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);

// Helper untuk menandai tab menu yang sedang aktif secara dinamis
if (!function_exists('getMenuClass')) {
    function getMenuClass(string $targetPath, string $currentPath): string {
        $target = trim($targetPath, '/');
        $current = trim($currentPath, '/');
        
        // Bersihkan prefix subdirektori XAMPP jika ada
        if (str_starts_with($current, 'whatsapp-saas')) {
            $current = trim(substr($current, 13), '/');
        }
        if (str_starts_with($current, 'public')) {
            $current = trim(substr($current, 6), '/');
        }

        // Pengecekan kecocokan rute
        $isActive = false;
        if ($target === '' && $current === 'dashboard') {
            $isActive = true;
        } elseif ($target !== '' && (str_starts_with($current, $target) || $current === $target)) {
            $isActive = true;
        }

        return $isActive 
            ? 'flex items-center gap-3 text-sm font-bold text-purple-700 bg-purple-50 px-3 py-2 rounded-xl transition-all shadow-sm'
            : 'flex items-center gap-3 text-sm font-semibold text-gray-600 hover:text-purple-600 hover:bg-purple-50/50 px-3 py-2 rounded-xl transition-all';
    }
}

$isAdmin = false;
if (!empty($_SESSION['user_id'])) {
    $userRole = $_SESSION['user_role'] ?? null;
    if ($userRole === null) {
        $db = \App\Config\Database::connection();
        $stmt = $db->prepare('SELECT role FROM users WHERE id = :id');
        $stmt->execute([':id' => $_SESSION['user_id']]);
        $userRole = $stmt->fetchColumn() ?: 'customer';
        $_SESSION['user_role'] = $userRole;
    }
    $isAdmin = ($userRole === 'admin');
}
?>

<div class="min-h-screen flex flex-col md:flex-row">
  <!-- Left Sidebar (Desktop Only) -->
  <aside class="w-64 bg-white border-r border-gray-200 flex flex-col justify-between p-6 shrink-0 hidden md:flex sticky top-0 h-screen">
    <div class="space-y-8">
      <!-- Brand Logo -->
      <a href="<?= url('/dashboard') ?>" class="flex items-center gap-2.5 hover:opacity-95 transition-all">
        <div class="bg-gradient-to-br from-purple-600 to-blue-600 p-1.5 rounded-lg text-white shadow-md shadow-purple-500/10">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
          </svg>
        </div>
        <span class="text-xl font-bold tracking-tight text-gray-900 font-display">Wapify</span>
      </a>

      <!-- Grouped Sidebar Menus -->
      <div class="space-y-6">
        <?php if ($isAdmin): ?>
          <!-- ADMIN SIDEBAR MENUS -->
          <div>
            <p class="text-[10px] font-bold uppercase tracking-wider text-purple-600 mb-2.5 px-3 whitespace-nowrap truncate">ADMIN SYSTEM</p>
            <div class="space-y-1">
              <a href="<?= url('/admin') ?>" class="<?= getMenuClass('admin', $currentPath) ?>">
                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:18px;height:18px;">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span>Admin Dashboard</span>
              </a>
            </div>
          </div>

          <div>
            <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-2.5 px-3 whitespace-nowrap truncate">RESOURCES</p>
            <div class="space-y-1">
              <a href="<?= url('/docs') ?>" class="<?= getMenuClass('docs', $currentPath) ?>">
                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:18px;height:18px;">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span>API Docs</span>
              </a>
              <a href="<?= url('/profile') ?>" class="<?= getMenuClass('profile', $currentPath) ?>">
                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:18px;height:18px;">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span>Profile</span>
              </a>
            </div>
          </div>

        <?php else: ?>
          <!-- CUSTOMER SIDEBAR MENUS -->
          <div>
            <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-2.5 px-3">WhatsApp Service</p>
            <div class="space-y-1">
              <a href="<?= url('/dashboard') ?>" class="<?= getMenuClass('', $currentPath) ?>">
                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:18px;height:18px;">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
                <span>Dashboard</span>
              </a>
              <a href="<?= url('/sessions') ?>" class="<?= getMenuClass('sessions', $currentPath) ?>">
                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:18px;height:18px;">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
                <span>Sessions</span>
              </a>
              <a href="<?= url('/api-keys') ?>" class="<?= getMenuClass('api-keys', $currentPath) ?>">
                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:18px;height:18px;">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2h6a2 2 0 002-2V9a2 2 0 00-2-2z" />
                </svg>
                <span>API Keys</span>
              </a>
              <a href="<?= url('/webhooks') ?>" class="<?= getMenuClass('webhooks', $currentPath) ?>">
                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:18px;height:18px;">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                </svg>
                <span>Webhooks</span>
              </a>
            </div>
          </div>

          <div>
            <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-2.5 px-3">Billing &amp; Limits</p>
            <div class="space-y-1">
              <a href="<?= url('/billing') ?>" class="<?= getMenuClass('billing', $currentPath) ?>">
                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:18px;height:18px;">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
                <span>Billing</span>
              </a>
              <a href="<?= url('/usage') ?>" class="<?= getMenuClass('usage', $currentPath) ?>">
                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:18px;height:18px;">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                <span>Usage</span>
              </a>
            </div>
          </div>

          <div>
            <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-2.5 px-3">Resources</p>
            <div class="space-y-1">
              <a href="<?= url('/docs') ?>" class="<?= getMenuClass('docs', $currentPath) ?>">
                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:18px;height:18px;">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span>API Docs</span>
              </a>
              <a href="<?= url('/profile') ?>" class="<?= getMenuClass('profile', $currentPath) ?>">
                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:18px;height:18px;">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span>Profile</span>
              </a>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Bottom Logout -->
    <form method="POST" action="<?= url('/logout') ?>" class="border-t border-gray-100 pt-4">
      <?= \App\Helpers\Csrf::field() ?>
      <button type="submit" class="w-full flex items-center gap-3 text-sm font-bold text-red-500 hover:text-red-700 hover:bg-red-50 px-3 py-2.5 rounded-xl transition-all">
        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:18px;height:18px;">
          <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
        </svg>
        <span>Keluar</span>
      </button>
    </form>
  </aside>

  <!-- Mobile Header Navigation (Mobile Only) -->
  <header class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between md:hidden">
    <a href="<?= url('/dashboard') ?>" class="flex items-center gap-2.5">
      <div class="bg-gradient-to-br from-purple-600 to-blue-600 p-1.5 rounded-lg text-white shadow-md">
        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="width:18px;height:18px;"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
      </div>
      <span class="text-lg font-bold tracking-tight text-gray-900 font-display">Wapify</span>
    </a>
    <button onclick="toggleMobileMenu()" class="text-gray-600 hover:text-gray-900 focus:outline-none">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" /></svg>
    </button>
  </header>

  <!-- Mobile Dropdown Menu -->
  <div id="mobile-menu" class="hidden bg-white border-b border-gray-200 px-6 py-4 space-y-3.5 md:hidden">
    <?php if ($isAdmin): ?>
      <a href="<?= url('/admin') ?>" class="block text-sm font-bold text-purple-700">Admin Dashboard</a>
      <a href="<?= url('/docs') ?>" class="block text-sm font-semibold text-gray-600 hover:text-purple-600">API Docs</a>
      <a href="<?= url('/profile') ?>" class="block text-sm font-semibold text-gray-600 hover:text-purple-600">Profile</a>
    <?php else: ?>
      <a href="<?= url('/dashboard') ?>" class="block text-sm font-semibold text-gray-600 hover:text-purple-600">Dashboard</a>
      <a href="<?= url('/sessions') ?>" class="block text-sm font-semibold text-gray-600 hover:text-purple-600">Sessions</a>
      <a href="<?= url('/api-keys') ?>" class="block text-sm font-semibold text-gray-600 hover:text-purple-600">API Keys</a>
      <a href="<?= url('/webhooks') ?>" class="block text-sm font-semibold text-gray-600 hover:text-purple-600">Webhooks</a>
      <a href="<?= url('/billing') ?>" class="block text-sm font-semibold text-gray-600 hover:text-purple-600">Billing</a>
      <a href="<?= url('/usage') ?>" class="block text-sm font-semibold text-gray-600 hover:text-purple-600">Usage</a>
      <a href="<?= url('/docs') ?>" class="block text-sm font-semibold text-gray-600 hover:text-purple-600">API Docs</a>
      <a href="<?= url('/profile') ?>" class="block text-sm font-semibold text-gray-600 hover:text-purple-600">Profile</a>
    <?php endif; ?>
    <form method="POST" action="<?= url('/logout') ?>" class="block pt-2.5 border-t border-gray-100">
      <?= \App\Helpers\Csrf::field() ?>
      <button type="submit" class="text-sm font-bold text-red-500 hover:text-red-700">Keluar</button>
    </form>
  </div>

  <!-- Content Container -->
  <div class="flex-1 bg-gray-50 flex flex-col justify-between min-h-screen">
    <div class="p-6 md:p-10">
      <?php
        $dbAnn = \App\Config\Database::connection();
        $announcement = $dbAnn->query('SELECT * FROM announcements WHERE is_active = 1 ORDER BY id DESC LIMIT 1')->fetch(PDO::FETCH_ASSOC);
      ?>
      <?php if (!empty($announcement)): ?>
        <?php 
          $type = $announcement['type'] ?? 'info';
          $annStyle = match($type) {
            'warning' => 'background: #FEF3C7; border: 1px solid #FDE68A; color: #92400E;',
            'danger'  => 'background: #FFE4E6; border: 1px solid #FECDD3; color: #9F1239;',
            default   => 'background: #F3E8FF; border: 1px solid #D8B4FE; color: #6B21A8;'
          };
          $badgeStyle = match($type) {
            'warning' => 'background: #F59E0B; color: white;',
            'danger'  => 'background: #E11D48; color: white;',
            default   => 'background: #9333EA; color: white;'
          };
        ?>
        <div style="<?= $annStyle ?>" class="mb-6 rounded-2xl p-4 text-xs font-medium flex items-start gap-3 shadow-xs">
          <span style="<?= $badgeStyle ?>" class="w-6 h-6 rounded-lg font-bold flex items-center justify-center text-xs shrink-0 shadow-xs mt-0.5">
            📢
          </span>
          <div class="flex-1 leading-relaxed font-sans">
            <?= nl2br(htmlspecialchars($announcement['message'])) ?>
          </div>
        </div>
      <?php endif; ?>
