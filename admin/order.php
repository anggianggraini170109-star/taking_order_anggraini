<?php
require_once '../config/koneksi.php';
if (!isset($_SESSION['admin_id'])) { header("Location: " . asset('admin/login.php')); exit; }
$judul = "Data Order";
include '../includes/header.php';

$filter = $_GET['status'] ?? '';
$where  = "";
if ($filter !== '') {
    $f = mysqli_real_escape_string($koneksi, $filter);
    $where = "WHERE o.status = '$f'";
}
$q = mysqli_query($koneksi, "SELECT o.*, p.nama FROM orders o
                             JOIN pelanggan p ON o.pelanggan_id = p.id
                             $where ORDER BY o.waktu_order DESC");

$statusList = [
    ''           => ['Semua', 'bg-ocean text-white border-ocean', 'bg-white text-ink border-sky hover:border-ocean'],
    'pending'    => ['Pending', 'bg-latte text-white border-latte', 'bg-white text-ink border-sky hover:border-latte'],
    'diproses'   => ['Diproses', 'bg-ocean text-white border-ocean', 'bg-white text-ink border-sky hover:border-ocean'],
    'selesai'    => ['Selesai', 'bg-emerald-600 text-white border-emerald-600', 'bg-white text-ink border-sky hover:border-emerald-600'],
    'dibatalkan' => ['Dibatalkan', 'bg-red-600 text-white border-red-600', 'bg-white text-ink border-sky hover:border-red-600'],
];
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
        <h1 class="text-2xl font-extrabold text-ocean-dark mb-1">Data Order</h1>
        <p class="text-sm text-ink/60 mb-5">Kelola status pesanan pelanggan.</p>

        <div class="flex flex-wrap gap-2 mb-5">
            <?php foreach ($statusList as $key => [$label, $active, $inactive]): ?>
                <a href="<?= asset('admin/order.php' . ($key ? '?status=' . $key : '')); ?>"
                   class="px-4 py-1.5 rounded-full text-sm font-semibold border transition
                          <?= $filter === $key ? $active : $inactive; ?>">
                    <?= $label; ?>
                </a>
            <?php endforeach; ?>
        </div>

        <div class="bg-white rounded-xl border border-sky/70 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-ocean text-white">
                        <tr>
                            <th class="text-left px-4 py-3 font-semibold">Kode</th>
                            <th class="text-left px-4 py-3 font-semibold">Nama</th>
                            <th class="text-center px-4 py-3 font-semibold">Meja</th>
                            <th class="text-right px-4 py-3 font-semibold">Total</th>
                            <th class="text-center px-4 py-3 font-semibold">Status</th>
                            <th class="text-center px-4 py-3 font-semibold">Waktu</th>
                            <th class="text-center px-4 py-3 font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-sky/50">
                        <?php while ($o = mysqli_fetch_assoc($q)):
                            $badge = match ($o['status']) {
                                'pending'    => 'bg-latte/20 text-latte-dark',
                                'diproses'   => 'bg-ocean/10 text-ocean',
                                'selesai'    => 'bg-emerald-100 text-emerald-700',
                                'dibatalkan' => 'bg-red-100 text-red-700',
                                default      => 'bg-sky/40 text-ocean',
                            };
                        ?>
                        <tr class="hover:bg-cream/70">
                            <td class="px-4 py-3"><code class="text-xs font-semibold text-ink"><?= htmlspecialchars($o['kode_order']); ?></code></td>
                            <td class="px-4 py-3"><?= htmlspecialchars($o['nama']); ?></td>
                            <td class="px-4 py-3 text-center"><?= htmlspecialchars($o['no_meja']); ?></td>
                            <td class="px-4 py-3 text-right font-semibold"><?= rupiah($o['total_harga']); ?></td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-block px-2 py-0.5 rounded-full text-xs font-semibold capitalize <?= $badge; ?>"><?= $o['status']; ?></span>
                            </td>
                            <td class="px-4 py-3 text-center text-ink/60"><?= date('d/m/Y H:i', strtotime($o['waktu_order'])); ?></td>
                            <td class="px-4 py-3 text-center">
                                <a href="<?= asset('admin/order_detail.php?id=' . (int)$o['id']); ?>"
                                   class="inline-flex items-center gap-1 text-ocean hover:text-ocean-dark font-semibold">
                                    <i data-lucide="eye" class="w-4 h-4"></i> Detail
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<?php include '../includes/footer.php'; ?>