<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($title ?? 'Wapify — WhatsApp API SaaS') ?></title>
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

