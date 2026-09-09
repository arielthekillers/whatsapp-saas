<?php
use App\Config\Env;

$appUrl = rtrim((string) Env::get('APP_URL', 'https://wapify.biz.id'), '/');
$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?: '';
$canonicalUrl = htmlspecialchars($appUrl . $currentPath);
$metaTitle = htmlspecialchars($title ?? 'Wapify — Platform WhatsApp API SaaS & Broadcast Terpercaya');
$metaDesc  = htmlspecialchars($description ?? 'Kirim pesan, broadcast, dan kelola WhatsApp API dengan cepat, aman, dan terjangkau menggunakan infrastruktur WAHA REST API.');
$ogImage   = htmlspecialchars($appUrl . '/og-image.jpg');
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $metaTitle ?></title>

<!-- Primary SEO Meta Tags -->
<meta name="description" content="<?= $metaDesc ?>">
<meta name="keywords" content="whatsapp api, waha api, whatsapp saas, broadcast whatsapp, whatsapp gateway indonesia, api whatsapp murah, bot whatsapp">
<meta name="author" content="Wapify">
<meta name="robots" content="index, follow">
<link rel="canonical" href="<?= $canonicalUrl ?>">

<!-- Favicon Icons -->
<link rel="shortcut icon" href="<?= url('/favicon.ico') ?>" type="image/x-icon">
<link rel="icon" href="<?= url('/favicon.ico') ?>" type="image/x-icon">
<link rel="icon" type="image/png" href="<?= url('/favicon.png') ?>">
<link rel="icon" type="image/png" sizes="32x32" href="<?= url('/favicon-32x32.png') ?>">
<link rel="apple-touch-icon" sizes="180x180" href="<?= url('/apple-touch-icon.png') ?>">

<!-- Open Graph / Facebook / WhatsApp / Telegram -->
<meta property="og:type" content="website">
<meta property="og:title" content="<?= $metaTitle ?>">
<meta property="og:description" content="<?= $metaDesc ?>">
<meta property="og:url" content="<?= $canonicalUrl ?>">
<meta property="og:site_name" content="Wapify">
<meta property="og:image" content="<?= $ogImage ?>">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:locale" content="id_ID">

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?= $metaTitle ?>">
<meta name="twitter:description" content="<?= $metaDesc ?>">
<meta name="twitter:image" content="<?= $ogImage ?>">

<!-- JSON-LD Structured Data for Google -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "SoftwareApplication",
  "name": "Wapify",
  "operatingSystem": "All",
  "applicationCategory": "BusinessApplication",
  "offers": {
    "@type": "Offer",
    "price": "29000",
    "priceCurrency": "IDR"
  },
  "description": "Layanan WhatsApp API SaaS berbasis WAHA REST API terjangkau untuk pengiriman pesan, broadcast, dan integrasi webhook."
}
</script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
<!-- Load Outfit & Inter fonts globally -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
  body {
    font-family: 'Inter', sans-serif;
  }
  h1, h2, h3, h4, .font-display {
    font-family: 'Outfit', sans-serif;
  }
  @keyframes toastSlideIn {
    from { transform: translateY(-100%); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
  }
  .animate-toast {
    animation: toastSlideIn 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
  }
</style>
</head>
<body class="bg-gray-50 text-gray-800 min-h-screen relative">
<!-- Toast Container -->
<div id="toast-container" class="fixed top-5 right-5 z-50 flex flex-col space-y-3 max-w-sm pointer-events-none"></div>

