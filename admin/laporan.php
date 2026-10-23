<?php
require_once '../config/koneksi.php';
if (!isset($_SESSION['admin_id'])) { header("Location: " . asset('admin/login.php')); exit; }
$judul = "Laporan Order";
include '../includes/header.php';

$tgl_awal  = $_GET['tgl_awal']  ?? date('Y-m-01');
$tgl_akhir = $_GET['tgl_akhir'] ?? date('Y-m-d');
$ta = mysqli_real_escape_string($koneksi, $tgl_awal);
$tk = mysqli_real_escape_string($koneksi, $tgl_akhir);

$q = mysqli_query($koneksi, "SELECT o.*, p.nama FROM orders o
                             JOIN pelanggan p ON o.pelanggan_id = p.id
                             WHERE DATE(o.waktu_order) BETWEEN '$ta' AND '$tk'
                             ORDER BY o.waktu_order DESC");

$total = 0; $data = [];
while ($row = mysqli_fetch_assoc($q)) {
    $data[] = $row;
    if ($row['status'] === 'selesai') $total += $row['total_harga'];
}
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

<div class="flex min-h-[calc(100vh-56px)]">
    <aside class="hidden md:flex flex-col w-60 bg-ocean text-white p-3 gap-1">
        <?php $current = basename($_SERVER['PHP_SELF']); ?>
        <?php
        $items = [
            ['dashboard.php', 'layout-dashboard', 'Dashboard'],
            ['menu.php',      'coffee',           'Kelola Menu'],
            ['order.php',     'receipt',          'Data Order'],
            ['laporan.php',   'file-text',        'Laporan'],
        ];
        foreach ($items as [$href, $icon, $label]):
        ?>
            <a href="<?= asset('admin/' . $href); ?>"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition
                      <?= $current === $href ? 'bg-white/15 text-white' : 'text-sky hover:bg-white/10 hover:text-white'; ?>">
                <i data-lucide="<?= $icon; ?>" class="w-4 h-4"></i>
                <?= $label; ?>
            </a>
        <?php endforeach; ?>
    </aside>

    <main class="flex-1 p-6 lg:p-8">
        <h1 class="text-2xl font-extrabold text-ocean-dark mb-1">Laporan Order</h1>
        <p class="text-sm text-ink/60 mb-5">Rekap transaksi berdasarkan periode.</p>

        <form method="GET" class="bg-white rounded-xl border border-sky/70 p-4 mb-5 flex flex-wrap items-end gap-3 text-sm shadow-sm">
            <div>
                <label class="block text-ink/70 font-semibold mb-1">Tanggal Awal</label>
                <input type="date" name="tgl_awal" value="<?= htmlspecialchars($tgl_awal); ?>"
                    class="rounded-lg border border-sky bg-white focus:border-ocean outline-none px-3 py-2">
            </div>
            <div>
                <label class="block text-ink/70 font-semibold mb-1">Tanggal Akhir</label>
                <input type="date" name="tgl_akhir" value="<?= htmlspecialchars($tgl_akhir); ?>"
                    class="rounded-lg border border-sky bg-white focus:border-ocean outline-none px-3 py-2">
            </div>
            <button class="inline-flex items-center gap-2 bg-ocean hover:bg-ocean-dark text-white font-semibold px-4 py-2 rounded-lg transition">
                <i data-lucide="search" class="w-4 h-4"></i> Filter
            </button>
            <a href="<?= asset('admin/cetak_laporan.php?tgl_awal=' . $tgl_awal . '&tgl_akhir=' . $tgl_akhir); ?>" target="_blank"
               class="inline-flex items-center gap-2 border border-ocean text-ocean hover:bg-ocean hover:text-white font-semibold px-4 py-2 rounded-lg transition">
                <i data-lucide="printer" class="w-4 h-4"></i> Cetak
            </a>
        </form>

        <div class="bg-white rounded-xl border border-sky/70 p-4 mb-5 flex items-center gap-3 shadow-sm">
            <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-latte/20 text-latte-dark">
                <i data-lucide="wallet" class="w-5 h-5"></i>
            </span>
            <div>
                <p class="text-xs text-ink/60">Total Pendapatan (Order Selesai)</p>
                <p class="text-lg font-extrabold text-latte-dark"><?= rupiah($total); ?></p>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-sky/70 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-ocean text-white">
                        <tr>
                            <th class="text-left px-4 py-3 font-semibold">No</th>
                            <th class="text-left px-4 py-3 font-semibold">Kode</th>
                            <th class="text-left px-4 py-3 font-semibold">Nama</th>
                            <th class="text-center px-4 py-3 font-semibold">Meja</th>
                            <th class="text-right px-4 py-3 font-semibold">Total</th>
                            <th class="text-center px-4 py-3 font-semibold">Status</th>
                            <th class="text-center px-4 py-3 font-semibold">Waktu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-sky/50">
                        <?php $no = 1; foreach ($data as $o): ?>
                        <tr class="hover:bg-cream/70">
                            <td class="px-4 py-3"><?= $no++; ?></td>
                            <td class="px-4 py-3"><code class="text-xs font-semibold"><?= htmlspecialchars($o['kode_order']); ?></code></td>
                            <td class="px-4 py-3"><?= htmlspecialchars($o['nama']); ?></td>
                            <td class="px-4 py-3 text-center"><?= htmlspecialchars($o['no_meja']); ?></td>
                            <td class="px-4 py-3 text-right font-semibold"><?= rupiah($o['total_harga']); ?></td>
                            <td class="px-4 py-3 text-center capitalize"><?= htmlspecialchars($o['status']); ?></td>
                            <td class="px-4 py-3 text-center text-ink/60"><?= date('d/m/Y H:i', strtotime($o['waktu_order'])); ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($data)): ?>
                            <tr><td colspan="7" class="px-4 py-8 text-center text-ink/50">Tidak ada data pada periode ini.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<?php include '../includes/footer.php'; ?>