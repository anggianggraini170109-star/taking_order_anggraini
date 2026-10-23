<?php
require_once '../config/koneksi.php';
if (isset($_SESSION['admin_id'])) { header("Location: " . asset('admin/dashboard.php')); exit; }
$judul = "Login Admin - Green Haven Cafe";
include '../includes/header.php';
?>

<div class="min-h-screen bg-ocean-dark flex items-center justify-center px-4 py-10">
    <div class="max-w-md w-full">

        <!-- Branding -->
        <div class="text-center mb-8">
            <img src="<?= asset('assets/logo-brand.png'); ?>"
                 alt="Green Haven Cafe"
                 class="h-16 w-16 mx-auto rounded-xl bg-white object-contain p-1.5 shadow mb-4">
            <h1 class="text-2xl font-bold text-white">Green Haven Cafe</h1>
            <p class="text-sm text-sky mt-1">Panel Administrator</p>
        </div>

        <!-- Card form -->
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <h2 class="text-lg font-bold text-ocean-dark mb-1">Login</h2>
            <p class="text-sm text-ink/60 mb-6">Masuk untuk mengelola pesanan dan menu.</p>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="mb-4 flex items-start gap-2 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm p-3">
                    <i data-lucide="alert-circle" class="w-5 h-5 shrink-0 mt-0.5"></i>
                    <span><?= $_SESSION['error']; unset($_SESSION['error']); ?></span>
                </div>
            <?php endif; ?>

            <form action="<?= asset('admin/proses_login.php'); ?>" method="POST" class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-ink mb-1.5">Username</label>
                    <div class="relative">
                        <i data-lucide="user" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-ink/40"></i>
                        <input type="text" name="username" required autocomplete="username"
                            class="w-full pl-9 pr-3 py-2.5 rounded-lg border border-sky bg-white focus:border-ocean outline-none transition">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-ink mb-1.5">Password</label>
                    <div class="relative">
                        <i data-lucide="lock" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-ink/40"></i>
                        <input type="password" name="password" required autocomplete="current-password"
                            class="w-full pl-9 pr-3 py-2.5 rounded-lg border border-sky bg-white focus:border-ocean outline-none transition">
                    </div>
                </div>
                <button type="submit"
                    class="w-full bg-ocean hover:bg-ocean-dark text-white font-semibold py-3 rounded-lg transition flex items-center justify-center gap-2">
                    <i data-lucide="log-in" class="w-4 h-4"></i>
                    Masuk
                </button>
            </form>

            <a href="<?= asset('index.php'); ?>"
               class="mt-5 flex items-center justify-center gap-1.5 text-sm text-ink/60 hover:text-ocean transition">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Kembali ke Halaman Pelanggan
            </a>
        </div>

    </div>
</div>

<?php include '../includes/footer.php'; ?>