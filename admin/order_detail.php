<?php
require_once '../config/koneksi.php';
if (!isset($_SESSION['admin_id'])) { header("Location: " . asset('admin/login.php')); exit; }
$id = (int)($_GET['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $st = mysqli_real_escape_string($koneksi, $_POST['status'] ?? 'pending');
    mysqli_query($koneksi, "UPDATE orders SET status='$st' WHERE id=$id");
    $_SESSION['pesan'] = "Status order berhasil diperbarui.";
    header("Location: " . asset('admin/order_detail.php?id=' . $id)); exit;
}

$judul = "Detail Order";
include '../includes/header.php';

$q = mysqli_query($koneksi, "SELECT o.*, p.nama, p.no_hp FROM orders o
                             JOIN pelanggan p ON o.pelanggan_id = p.id
                             WHERE o.id = $id");
$o = mysqli_fetch_assoc($q);
if (!$o) { header("Location: " . asset('admin/order.php')); exit; }

$qd = mysqli_query($koneksi, "SELECT * FROM order_detail WHERE order_id = $id");
?>

<nav class="sticky top-0 z-40 bg-ocean-dark text-white shadow-sm">
    <div class="px-4 lg:px-6 py-3 flex items-center justify-between">
        <a href="<?= asset('admin/dashboard.php'); ?>" class="flex items-center gap-2.5">
            <img src="<?= asset('assets/logo-brand.png'); ?>" alt="Green Haven"
                 class="h-8 w-8 rounded-md bg-white object-contain p-0.5">
            <span class="font-bold">Green Haven <span class="text-sky font-normal hidden sm:inline">Admin</span></span>
        </a>
        <div class="flex items-center gap-3 text-sm">
            <span class="hidden sm:flex items-center gap-1.5 text-sky">
                <i data-lucide="user-round" class="w-4 h-4"></i>
                <?= htmlspecialchars($_SESSION['admin_nama']); ?>
            </span>
            <a href="<?= asset('admin/logout.php'); ?>"
               class="border border-white/30 hover:bg-white/10 px-3 py-1.5 rounded-lg flex items-center gap-1.5 transition">
                <i data-lucide="log-out" class="w-4 h-4"></i> Logout
            </a>
        </div>
    </div>
</nav>

<div class="max-w-6xl mx-auto px-4 py-8">
    <a href="<?= asset('admin/order.php'); ?>" class="inline-flex items-center gap-1 text-sm text-ocean hover:underline mb-3">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali
    </a>
    <h1 class="text-2xl font-extrabold text-ocean-dark mb-1">Detail Order</h1>
    <p class="text-sm text-ink/60 mb-6">Kode: <span class="font-semibold text-ocean-dark"><?= htmlspecialchars($o['kode_order']); ?></span></p>

    <?php if (isset($_SESSION['pesan'])): ?>
        <div class="mb-4 flex items-start gap-2 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm p-3">
            <i data-lucide="check-circle" class="w-5 h-5 shrink-0 mt-0.5"></i>
            <span><?= $_SESSION['pesan']; unset($_SESSION['pesan']); ?></span>
        </div>
    <?php endif; ?>

    <div class="grid lg:grid-cols-5 gap-5">
        <div class="lg:col-span-2 space-y-4">
            <div class="bg-white rounded-xl border border-sky/70 p-5 shadow-sm">
                <h2 class="font-bold text-ocean-dark mb-3 flex items-center gap-2">
                    <i data-lucide="user-round" class="w-5 h-5 text-latte"></i> Informasi Pelanggan
                </h2>
                <dl class="grid grid-cols-3 gap-y-2 text-sm">
                    <dt class="text-ink/60">Nama</dt><dd class="col-span-2 font-semibold"><?= htmlspecialchars($o['nama']); ?></dd>
                    <dt class="text-ink/60">No. HP</dt><dd class="col-span-2"><?= htmlspecialchars($o['no_hp'] ?: '-'); ?></dd>
                    <dt class="text-ink/60">No. Meja</dt><dd class="col-span-2"><?= htmlspecialchars($o['no_meja']); ?></dd>
                    <dt class="text-ink/60">Waktu</dt><dd class="col-span-2"><?= date('d/m/Y H:i', strtotime($o['waktu_order'])); ?></dd>
                    <dt class="text-ink/60">Catatan</dt><dd class="col-span-2 italic"><?= htmlspecialchars($o['catatan'] ?: '-'); ?></dd>
                </dl>
            </div>

            <div class="bg-white rounded-xl border border-sky/70 p-5 shadow-sm">
                <h2 class="font-bold text-ocean-dark mb-3 flex items-center gap-2">
                    <i data-lucide="refresh-cw" class="w-5 h-5 text-latte"></i> Update Status
                </h2>
                <form method="POST" class="space-y-3">
                    <select name="status"
                        class="w-full rounded-lg border border-sky bg-white focus:border-ocean outline-none px-3 py-2.5 text-sm">
                        <?php foreach (['pending'=>'Pending','diproses'=>'Diproses','selesai'=>'Selesai','dibatalkan'=>'Dibatalkan'] as $val => $label): ?>
                            <option value="<?= $val; ?>" <?= $o['status'] === $val ? 'selected' : ''; ?>><?= $label; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button class="w-full inline-flex items-center justify-center gap-2 bg-ocean hover:bg-ocean-dark text-white font-semibold py-2.5 rounded-lg transition">
                        <i data-lucide="save" class="w-4 h-4"></i> Update Status
                    </button>
                </form>
            </div>
        </div>

        <div class="lg:col-span-3">
            <div class="bg-white rounded-xl border border-sky/70 overflow-hidden shadow-sm">
                <h2 class="px-5 py-4 font-bold text-ocean-dark flex items-center gap-2 border-b border-sky/60">
                    <i data-lucide="list" class="w-5 h-5 text-latte"></i> Rincian Item
                </h2>
                <table class="w-full text-sm">
                    <thead class="bg-cream text-ocean-dark">
                        <tr>
                            <th class="text-left px-4 py-3 font-semibold">Menu</th>
                            <th class="text-right px-4 py-3 font-semibold">Harga</th>
                            <th class="text-center px-4 py-3 font-semibold">Jumlah</th>
                            <th class="text-right px-4 py-3 font-semibold">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-sky/50">
                        <?php while ($d = mysqli_fetch_assoc($qd)): ?>
                        <tr>
                            <td class="px-4 py-3 font-medium"><?= htmlspecialchars($d['nama_menu']); ?></td>
                            <td class="px-4 py-3 text-right"><?= rupiah($d['harga']); ?></td>
                            <td class="px-4 py-3 text-center"><?= (int)$d['jumlah']; ?></td>
                            <td class="px-4 py-3 text-right font-semibold"><?= rupiah($d['subtotal']); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                    <tfoot class="bg-cream">
                        <tr>
                            <td colspan="3" class="px-4 py-3 text-right font-bold">Total</td>
                            <td class="px-4 py-3 text-right text-lg font-extrabold text-latte-dark"><?= rupiah($o['total_harga']); ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>