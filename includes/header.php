<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!defined('BASE_URL')) require_once __DIR__ . '/../config/koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($judul) ? htmlspecialchars($judul) : 'Green Haven Cafe'; ?></title>

    <!-- Favicon dari logo brand -->
    <link rel="icon" type="image/png" href="<?= asset('assets/logo-brand.png'); ?>">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        ocean:       '#1D51A4',
                        'ocean-dark':'#1B438E',
                        sky:         '#A5D7F6',
                        latte:       '#C38B58',
                        'latte-dark':'#A66F3C',
                        cream:       '#FFF9EB',
                        ink:         '#0F1F3D',
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    },
                }
            }
        }
    </script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>

    <style>
        body { font-family: 'Inter', system-ui, sans-serif; }
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-thumb { background: #C38B58; border-radius: 8px; }
        ::-webkit-scrollbar-track { background: #FFF9EB; }

        /* Fokus ring yang konsisten */
        input:focus, select:focus, textarea:focus, button:focus-visible, a:focus-visible {
            outline: 2px solid #1D51A4;
            outline-offset: 2px;
        }
    </style>
</head>
<body class="bg-cream text-ink antialiased">