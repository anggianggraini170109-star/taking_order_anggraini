<?php
require_once '../config/koneksi.php';
if (!isset($_SESSION['pelanggan_id']) || !isset($_SESSION['last_order_id'])) {
    header("Location: " . asset('index.php')); exit;
}
$oid = (int)$_SESSION['last_order_id'];
$q_order = mysqli_query($koneksi, "SELECT o.*, p.nama, p.no_hp FROM orders o
                                   JOIN pelanggan p ON o.pelanggan_id = p.id
                                   WHERE o.id = $oid");
$order = mysqli_fetch_assoc($q_order);
if (!$order) { header("Location: " . asset('index.php')); exit; }

$q_detail = mysqli_query($koneksi, "SELECT * FROM order_detail WHERE order_id = $oid");
$detail = []; while ($d = mysqli_fetch_assoc($q_detail)) $detail[] = $d;

$judul = "Struk Pesanan - Green Haven Cafe";
include '../includes/header.php';
?>

<div class="min-h-screen bg-cream py-10 px-4">
    <div class="max-w-md mx-auto">

        <div class="text-center mb-5">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-emerald-100 text-emerald-600 mb-2">
                <i data-lucide="check-circle-2" class="w-7 h-7"></i>
            </div>
            <h1 class="text-xl font-extrabold text-ocean-dark">Pesanan Berhasil</h1>
            <p class="text-sm text-ink/60">Tunjukkan struk ini ke kasir / pelayan</p>
        </div>

        <div class="bg-white rounded-xl shadow-md overflow-hidden border border-sky/70">
            <!-- Header dengan logo -->
            <div class="bg-ocean text-white p-5">
                <div class="flex items-center gap-3">
                    <img src="<?= asset('assets/logo-brand.png'); ?>" alt="Green Haven"
                         class="h-12 w-12 rounded-md bg-white object-contain p-1">
                    <div>
                        <h2 class="font-bold tracking-wide">GREEN HAVEN CAFE</h2>
                        <p class="text-xs text-sky">Jl. Contoh Alamat No. 123, Kota Anda</p>
                        <p class="text-xs text-sky">Telp: 021-1234567</p>
                    </div>
                </div>
            </div>

            <div class="p-5 text-sm space-y-3">
                <div class="grid grid-cols-2 gap-y-1 text-ink">
                    <span class="text-ink/60">Kode Order</span><span class="font-semibold text-right"><?= htmlspecialchars($order['kode_order']); ?></span>
                    <span class="text-ink/60">Tanggal</span><span class="text-right"><?= date('d/m/Y H:i', strtotime($order['waktu_order'])); ?></span>
                    <span class="text-ink/60">Nama</span><span class="text-right"><?= htmlspecialchars($order['nama']); ?></span>
                    <span class="text-ink/60">No. Meja</span><span class="text-right"><?= htmlspecialchars($order['no_meja']); ?></span>
                    <?php if (!empty($order['no_hp'])): ?>
                    <span class="text-ink/60">No. HP</span><span class="text-right"><?= htmlspecialchars($order['no_hp']); ?></span>
                    <?php endif; ?>
                </div>

                <hr class="border-dashed border-sky">

                <div class="space-y-1">
                    <?php foreach ($detail as $d): ?>
                    <div class="flex justify-between text-ink">
                        <span><?= htmlspecialchars($d['nama_menu']); ?> <span class="text-ink/50 text-xs">x<?= (int)$d['jumlah']; ?></span></span>
                        <span class="font-semibold"><?= rupiah($d['subtotal']); ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>

                <hr class="border-dashed border-sky">

                <div class="flex justify-between text-base font-extrabold">
                    <span class="text-ocean-dark">TOTAL</span>
                    <span class="text-latte-dark"><?= rupiah($order['total_harga']); ?></span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-ink/60">Status</span>
                    <span class="font-semibold capitalize"><?= $order['status']; ?></span>
                </div>

                <?php if (!empty($order['catatan'])): ?>
                <hr class="border-dashed border-sky">
                <div>
                    <p class="text-ink/60 text-xs mb-1">Catatan:</p>
                    <p class="italic text-ink"><?= nl2br(htmlspecialchars($order['catatan'])); ?></p>
                </div>
                <?php endif; ?>
            </div>

            <div class="bg-cream border-t border-sky/60 text-center text-xs text-ink/60 p-3">
                Terima kasih atas kunjungan Anda &mdash; Selamat menikmati
            </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-2 mt-5">
            <a href="<?= asset('pelanggan/menu.php'); ?>"
               class="flex-1 inline-flex items-center justify-center gap-2 border border-ocean text-ocean hover:bg-ocean hover:text-white py-2.5 rounded-lg font-semibold transition">
                <i data-lucide="plus-circle" class="w-4 h-4"></i> Order Lagi
            </a>
            <a href="<?= asset('pelanggan/logout_pelanggan.php'); ?>"
               class="flex-1 inline-flex items-center justify-center gap-2 bg-ocean hover:bg-ocean-dark text-white py-2.5 rounded-lg font-semibold transition">
                <i data-lucide="log-out" class="w-4 h-4"></i> Selesai
            </a>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>