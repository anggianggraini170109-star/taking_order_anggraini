<?php
$current = basename($_SERVER['PHP_SELF']);
$items = [
    ['dashboard.php', 'layout-dashboard', 'Dashboard'],
    ['menu.php',      'coffee',           'Kelola Menu'],
    ['order.php',     'receipt',          'Data Order'],
    ['laporan.php',   'file-text',        'Laporan'],
];
?>
<aside class="hidden md:flex flex-col w-64 bg-ocean text-white min-h-[calc(100vh-56px)] p-4 gap-1">
    <!-- Brand mini di atas menu -->
    <div class="flex items-center gap-2 px-2 pb-3 mb-2 border-b border-white/10">
        <img src="../assets/logo-brand.png"
             alt="Green Haven"
             class="h-8 w-auto object-contain">
        <span class="text-xs font-bold tracking-widest text-sky/80">MENU UTAMA</span>
    </div>

    <?php foreach ($items as [$href, $icon, $label]): ?>
        <a href="<?= $href; ?>"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition
                  <?= $current === $href ? 'bg-white/15' : 'hover:bg-white/10 text-sky'; ?>">
            <i data-lucide="<?= $icon; ?>" class="w-4 h-4"></i>
            <?= $label; ?>
        </a>
    <?php endforeach; ?>

    <div class="mt-auto pt-4 border-t border-white/10">
        <div class="flex items-center gap-2 px-2 py-2 mb-2 text-xs text-sky/60">
            <img src="../assets/logo-brand.png" class="h-6 w-auto object-contain" alt="">
            <span>&copy; <?= date('Y'); ?></span>
        </div>
        <a href="logout.php" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium hover:bg-red-500/20 text-sky">
            <i data-lucide="log-out" class="w-4 h-4"></i> Logout
        </a>
    </div>
</aside>