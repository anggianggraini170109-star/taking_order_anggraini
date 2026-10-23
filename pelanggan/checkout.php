<?php
require_once '../config/koneksi.php';
if (!isset($_SESSION['pelanggan_id'])) { header("Location: " . asset('index.php')); exit; }
$keranjang = $_SESSION['keranjang'] ?? [];
if (empty($keranjang)) { header("Location: " . asset('pelanggan/keranjang.php')); exit; }

$total = 0;
foreach ($keranjang as $mid => $jml) {
    $q = mysqli_query($koneksi, "SELECT harga FROM menu WHERE id = " . (int)$mid);
    $m = mysqli_fetch_assoc($q);
    if ($m) $total += $m['harga'] * $jml;
}
$judul = "Checkout - Green Haven Cafe";
include '../includes/header.php';
include '../includes/navbar.php';
?>

<div class="max-w-5xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-extrabold text-ocean-dark mb-6">Konfirmasi Pesanan</h1>

    <div class="grid lg:grid-cols-5 gap-5">
        <div class="lg:col-span-3 space-y-4">
            <div class="bg-white rounded-xl border border-sky/70 p-5 shadow-sm">
                <h2 class="font-bold text-ocean-dark mb-3 flex items-center gap-2">
                    <i data-lucide="user-round" class="w-5 h-5 text-latte"></i> Data Pelanggan
                </h2>
                <div class="grid sm:grid-cols-2 gap-3 text-sm">
                    <div>
                        <p class="text-ink/50 text-xs">Nama</p>
                        <p class="font-semibold"><?= htmlspecialchars($_SESSION['pelanggan_nama']); ?></p>
                    </div>
                    <div>
                        <p class="text-ink/50 text-xs">No. Meja</p>
                        <p class="font-semibold"><?= htmlspecialchars($_SESSION['pelanggan_meja']); ?></p>
                    </div>
                </div>
            </div>

            <form action="<?= asset('proses/simpan_order.php'); ?>" method="POST" class="space-y-4">
                <div class="bg-white rounded-xl border border-sky/70 p-5 shadow-sm">
                    <label class="font-bold text-ocean-dark mb-2 flex items-center gap-2">
                        <i data-lucide="message-square-text" class="w-5 h-5 text-latte"></i> Catatan Tambahan
                    </label>
                    <textarea name="catatan" rows="3"
                        class="w-full rounded-lg border border-sky bg-white focus:border-ocean outline-none p-3 text-sm"
                        placeholder="Contoh: tanpa gula, extra shot (opsional)"></textarea>
                </div>
                <button type="submit"
                    class="w-full inline-flex items-center justify-center gap-2 bg-ocean hover:bg-ocean-dark text-white font-bold py-3 rounded-lg transition">
                    <i data-lucide="send" class="w-5 h-5"></i> Kirim Pesanan
                </button>
            </form>
        </div>

        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl border border-sky/70 p-5 sticky top-24 shadow-sm">
                <h2 class="font-bold text-ocean-dark mb-3 flex items-center gap-2">
                    <i data-lucide="receipt" class="w-5 h-5 text-latte"></i> Rincian Pesanan
                </h2>
                <div class="space-y-2 text-sm">
                    <?php foreach ($keranjang as $mid => $jml):
                        $q = mysqli_query($koneksi, "SELECT * FROM menu WHERE id = " . (int)$mid);
                        $m = mysqli_fetch_assoc($q);
                        if (!$m) continue;
                    ?>
                    <div class="flex justify-between">
                        <span class="text-ink"><?= htmlspecialchars($m['nama_menu']); ?> <span class="text-ink/50">x<?= (int)$jml; ?></span></span>
                        <span class="font-semibold"><?= rupiah($m['harga'] * $jml); ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="mt-4 pt-4 border-t border-sky flex justify-between text-lg font-extrabold">
                    <span class="text-ocean-dark">Total</span>
                    <span class="text-latte-dark"><?= rupiah($total); ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>