<?php
require_once 'config/koneksi.php';
$judul = "Selamat Datang - Green Haven Cafe";
include 'includes/header.php';
?>

<div class="min-h-screen bg-ocean-dark">
    <div class="max-w-6xl mx-auto px-4 py-12 lg:py-20">
        <div class="grid lg:grid-cols-2 gap-10 items-center">

            <!-- Branding kiri -->
            <div class="text-white">
                <div class="flex items-center gap-3 mb-8">
                    <img src="<?= asset('assets/logo-brand.png'); ?>"
                         alt="Green Haven Cafe"
                         class="h-16 w-16 rounded-xl bg-white object-contain p-1.5 shadow">
                    <div>
                        <p class="text-xs uppercase tracking-widest text-sky font-semibold">Since 2026</p>
                        <h2 class="text-2xl font-bold">Green Haven Cafe</h2>
                    </div>
                </div>

                <h1 class="text-3xl md:text-4xl font-extrabold leading-tight">
                    Pesan langsung dari meja Anda.
                </h1>
                <p class="text-sky mt-4 max-w-md">
                    Tanpa antre, tanpa aplikasi. Pilih menu, kirim pesanan, dan nikmati hidangan di meja Anda.
                </p>

                <ul class="mt-8 space-y-2.5 text-sm text-sky">
                    <li class="flex items-center gap-2">
                        <i data-lucide="check" class="w-4 h-4 text-latte"></i>
                        Bahan segar setiap hari
                    </li>
                    <li class="flex items-center gap-2">
                        <i data-lucide="check" class="w-4 h-4 text-latte"></i>
                        Konfirmasi pesanan cepat
                    </li>
                    <li class="flex items-center gap-2">
                        <i data-lucide="check" class="w-4 h-4 text-latte"></i>
                        Struk digital otomatis
                    </li>
                </ul>
            </div>

            <!-- Form kanan -->
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <h2 class="text-xl font-bold text-ocean-dark mb-1">Mulai Pesanan</h2>
                <p class="text-sm text-ink/60 mb-6">Isi data diri Anda terlebih dahulu.</p>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="mb-4 flex items-start gap-2 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm p-3">
                        <i data-lucide="alert-circle" class="w-5 h-5 shrink-0 mt-0.5"></i>
                        <span><?= $_SESSION['error']; unset($_SESSION['error']); ?></span>
                    </div>
                <?php endif; ?>

                <form action="<?= asset('proses/simpan_pelanggan.php'); ?>" method="POST" class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-ink mb-1.5">Nama Lengkap</label>
                        <div class="relative">
                            <i data-lucide="user" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-ink/40"></i>
                            <input type="text" name="nama" required placeholder="Masukkan nama Anda"
                                class="w-full pl-9 pr-3 py-2.5 rounded-lg border border-sky bg-white focus:border-ocean outline-none transition">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-ink mb-1.5">Nomor Meja</label>
                        <div class="relative">
                            <i data-lucide="armchair" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-ink/40"></i>
                            <input type="text" name="no_meja" required placeholder="Contoh: A01"
                                class="w-full pl-9 pr-3 py-2.5 rounded-lg border border-sky bg-white focus:border-ocean outline-none transition">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-ink mb-1.5">
                            No. HP <span class="font-normal text-ink/40">(opsional)</span>
                        </label>
                        <div class="relative">
                            <i data-lucide="phone" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-ink/40"></i>
                            <input type="text" name="no_hp" placeholder="08xxxxxxxxxx"
                                class="w-full pl-9 pr-3 py-2.5 rounded-lg border border-sky bg-white focus:border-ocean outline-none transition">
                        </div>
                    </div>
                    <button type="submit"
                        class="w-full bg-ocean hover:bg-ocean-dark text-white font-semibold py-3 rounded-lg transition flex items-center justify-center gap-2">
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        Mulai Pesan
                    </button>
                </form>

                <p class="text-center mt-5 pt-4 border-t border-sky/60 text-sm">
                    <a href="<?= asset('admin/login.php'); ?>" class="text-ink/60 hover:text-ocean inline-flex items-center gap-1.5">
                        <i data-lucide="shield" class="w-4 h-4"></i>
                        Login Admin
                    </a>
                </p>
            </div>

        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>