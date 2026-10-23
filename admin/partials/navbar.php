<?php
// admin/partials/navbar.php — Navbar admin reusable
// Path logo dari folder admin/ → ../assets/
$logoPath = '../assets/logo-brand.png';
?>
<nav class="sticky top-0 z-40 bg-ocean-dark text-white shadow-lg">
    <div class="px-4 lg:px-6 py-2.5 flex items-center justify-between">
        <a href="dashboard.php" class="flex items-center gap-2.5 font-bold group">
            <img src="<?= $logoPath; ?>"
                 alt="Green Haven Cafe"
                 class="h-9 w-auto object-contain drop-shadow group-hover:scale-105 transition-transform duration-300">
            <div class="leading-tight hidden sm:block">
                <span class="block text-sm font-extrabold tracking-wide">GREEN HAVEN</span>
                <span class="block text-[10px] text-sky/80 tracking-widest">ADMIN PANEL</span>
            </div>
        </a>
        <div class="flex items-center gap-3 text-sm">
            <span class="hidden sm:flex items-center gap-1 text-sky">
                <i data-lucide="user-round" class="w-4 h-4"></i>
                <?= htmlspecialchars($_SESSION['admin_nama']); ?>
            </span>
            <a href="logout.php" class="border border-white/30 hover:bg-white/10 px-3 py-1.5 rounded-lg flex items-center gap-1">
                <i data-lucide="log-out" class="w-4 h-4"></i> <span class="hidden sm:inline">Logout</span>
            </a>
        </div>
    </div>
</nav>