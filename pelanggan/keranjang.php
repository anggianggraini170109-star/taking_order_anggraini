<?php
require_once '../config/koneksi.php';
if (!isset($_SESSION['pelanggan_id'])) { header("Location: " . asset('index.php')); exit; }
$judul = "Keranjang - Green Haven Cafe";
include '../includes/header.php';
include '../includes/navbar.php';

$keranjang = $_SESSION['keranjang'] ?? [];
$total = 0;
?>

<div class="max-w-4xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-extrabold text-ocean-dark mb-1">Keranjang Pesanan</h1>
    <p class="text-sm text-ink/60 mb-6">Periksa kembali pesanan Anda.</p>

    <?php if (empty($keranjang)): ?>
        <div class="bg-white border border-sky rounded-xl p-10 text-center">
            <i data-lucide="shopping-cart" class="w-12 h-12 mx-auto mb-3 text-ink/30"></i>
            <p class="text-ink font-semibold">Keranjang Anda masih kosong.</p>
            <a href="<?= asset('pelanggan/menu.php'); ?>" class="inline-flex items-center gap-1 text-latte-dark font-semibold mt-2 hover:underline">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Lihat menu
            </a>
        </div>
    <?php else: ?>
        <div class="bg-white rounded-xl border border-sky/70 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-ocean text-white">
                        <tr>
                            <th class="text-left px-4 py-3 font-semibold">Menu</th>
                            <th class="text-right px-4 py-3 font-semibold">Harga</th>
                            <th class="text-center px-4 py-3 font-semibold">Jumlah</th>
                            <th class="text-right px-4 py-3 font-semibold">Subtotal</th>
                            <th class="text-center px-4 py-3 font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-sky/50">
                        <?php foreach ($keranjang as $mid => $jml):
                            $q = mysqli_query($koneksi, "SELECT * FROM menu WHERE id = " . (int)$mid);
                            $m = mysqli_fetch_assoc($q);
                            if (!$m) continue;
                            $sub = $m['harga'] * $jml;
                            $total += $sub;
                        ?>
                        <tr class="hover:bg-cream/70">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <img src="<?= htmlspecialchars(menuImage($m['gambar'])); ?>"
                                         alt="<?= htmlspecialchars($m['nama_menu']); ?>"
                                         class="w-12 h-12 rounded-md object-cover border border-sky">
                                    <span class="font-semibold text-ocean-dark"><?= htmlspecialchars($m['nama_menu']); ?></span>
                                </div>
                            </td>
                            <td class="text-right px-4 py-3"><?= rupiah($m['harga']); ?></td>
                            <td class="text-center px-4 py-3">
                                <form action="<?= asset('proses/update_keranjang.php'); ?>" method="POST" class="inline-flex items-center gap-1">
                                    <input type="hidden" name="menu_id" value="<?= (int)$mid; ?>">
                                    <input type="number" name="jumlah" value="<?= (int)$jml; ?>" min="1" max="<?= (int)$m['stok']; ?>"
                                        class="w-16 rounded-lg border border-sky bg-white px-2 py-1 text-sm text-center">
                                    <button class="p-1.5 rounded-md text-ocean hover:bg-cream" title="Update">
                                        <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </td>
                            <td class="text-right px-4 py-3 font-bold text-latte-dark"><?= rupiah($sub); ?></td>
                            <td class="text-center px-4 py-3">
                                <a href="<?= asset('proses/hapus_keranjang.php?id=' . (int)$mid); ?>"
                                   class="inline-flex items-center gap-1 text-red-600 hover:text-red-700 px-2 py-1 rounded-md">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot class="bg-cream">
                        <tr>
                            <td colspan="3" class="px-4 py-3 text-right font-semibold">Total</td>
                            <td colspan="2" class="px-4 py-3 text-right text-lg font-extrabold text-latte-dark"><?= rupiah($total); ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row justify-between gap-3 mt-6">
            <a href="<?= asset('pelanggan/menu.php'); ?>"
               class="inline-flex items-center justify-center gap-2 border border-ocean text-ocean hover:bg-ocean hover:text-white px-4 py-2.5 rounded-lg font-semibold transition">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Lanjut Pesan
            </a>
            <a href="<?= asset('pelanggan/checkout.php'); ?>"
               class="inline-flex items-center justify-center gap-2 bg-ocean hover:bg-ocean-dark text-white px-5 py-2.5 rounded-lg font-semibold transition">
                Checkout <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>