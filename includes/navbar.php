<?php
$cartCount = isset($_SESSION['keranjang']) ? array_sum($_SESSION['keranjang']) : 0;
?>
<nav class="sticky top-0 z-40 bg-ocean text-white shadow-sm">
    <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between gap-3">
        <a href="<?= asset('index.php'); ?>" class="flex items-center gap-2.5">
            <img src="<?= asset('assets/logo-brand.png'); ?>"
                 alt="Green Haven Cafe"
                 class="h-9 w-9 rounded-md bg-white object-contain p-0.5">
            <span class="font-bold text-lg tracking-tight">Green Haven</span>
        </a>

        <?php if (isset($_SESSION['pelanggan_id'])): ?>
        <div class="flex items-center gap-2 text-sm">
            <span class="hidden sm:flex items-center gap-1.5 text-sky">
                <i data-lucide="user-round" class="w-4 h-4"></i>
                <?= htmlspecialchars($_SESSION['pelanggan_nama']); ?>
                <span class="mx-1 text-white/30">|</span>
                <i data-lucide="armchair" class="w-4 h-4"></i>
                Meja <?= htmlspecialchars($_SESSION['pelanggan_meja']); ?>
            </span>
            <a href="<?= asset('pelanggan/keranjang.php'); ?>"
               class="relative inline-flex items-center gap-1.5 bg-latte hover:bg-latte-dark transition px-3 py-1.5 rounded-lg font-semibold">
                <i data-lucide="shopping-cart" class="w-4 h-4"></i>
                <span class="hidden xs:inline">Keranjang</span>
                <?php if ($cartCount > 0): ?>
                    <span class="absolute -top-1.5 -right-1.5 bg-red-600 text-white text-[10px] font-bold w-5 h-5 rounded-full flex items-center justify-center">
                        <?= $cartCount; ?>
                    </span>
                <?php endif; ?>
            </a>
            <a href="<?= asset('pelanggan/logout_pelanggan.php'); ?>"
               class="inline-flex items-center gap-1.5 border border-white/30 hover:bg-white/10 px-3 py-1.5 rounded-lg transition">
                <i data-lucide="log-out" class="w-4 h-4"></i>
                <span class="hidden sm:inline">Selesai</span>
            </a>
        </div>
        <?php endif; ?>
    </div>
</nav>