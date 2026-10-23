<?php
require_once '../config/koneksi.php';
if (!isset($_SESSION['admin_id'])) { header("Location: " . asset('admin/login.php')); exit; }
$judul = "Dashboard Admin";

$total_menu    = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) j FROM menu"))['j'];
$total_order   = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) j FROM orders"))['j'];
$total_pending = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) j FROM orders WHERE status='pending'"))['j'];
$pendapatan    = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COALESCE(SUM(total_harga),0) j FROM orders WHERE status='selesai'"))['j'];

$recent = mysqli_query($koneksi,
    "SELECT o.*, p.nama FROM orders o JOIN pelanggan p ON o.pelanggan_id = p.id
     ORDER BY o.waktu_order DESC LIMIT 5");

include '../includes/header.php';
?>

<!-- Top bar admin -->
<nav class="sticky top-0 z-40 bg-ocean-dark text-white shadow-sm">
    <div class="px-4 lg:px-6 py-3 flex items-center justify-between">
        <a href="<?= asset('admin/dashboard.php'); ?>" class="flex items-center gap-2.5">
            <img src="<?= asset('assets/logo-brand.png'); ?>"
                 alt="Green Haven"
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
                <i data-lucide="log-out" class="w-4 h-4"></i>
                Logout
            </a>
        </div>
    </div>
</nav>

<div class="flex min-h-[calc(100vh-56px)]">
    <!-- Sidebar -->
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

    <!-- Content -->
    <main class="flex-1 p-6 lg:p-8 bg-cream">
        <header class="mb-6">
            <h1 class="text-2xl font-extrabold text-ocean-dark">Dashboard</h1>
            <p class="text-sm text-ink/60 mt-0.5">Ringkasan aktivitas Green Haven Cafe.</p>
        </header>

        <!-- Statistik -->
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-xl p-5 border border-sky/70 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-ocean/10 text-ocean">
                        <i data-lucide="coffee" class="w-5 h-5"></i>
                    </span>
                </div>
                <p class="text-xs text-ink/60 uppercase tracking-wide">Total Menu</p>
                <p class="text-2xl font-extrabold text-ocean-dark mt-1"><?= (int)$total_menu; ?></p>
            </div>
            <div class="bg-white rounded-xl p-5 border border-sky/70 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-ocean/10 text-ocean">
                        <i data-lucide="receipt-text" class="w-5 h-5"></i>
                    </span>
                </div>
                <p class="text-xs text-ink/60 uppercase tracking-wide">Total Order</p>
                <p class="text-2xl font-extrabold text-ocean-dark mt-1"><?= (int)$total_order; ?></p>
            </div>
            <div class="bg-white rounded-xl p-5 border border-sky/70 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-latte/20 text-latte-dark">
                        <i data-lucide="clock" class="w-5 h-5"></i>
                    </span>
                </div>
                <p class="text-xs text-ink/60 uppercase tracking-wide">Order Pending</p>
                <p class="text-2xl font-extrabold text-ocean-dark mt-1"><?= (int)$total_pending; ?></p>
            </div>
            <div class="bg-white rounded-xl p-5 border border-sky/70 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-latte/20 text-latte-dark">
                        <i data-lucide="wallet" class="w-5 h-5"></i>
                    </span>
                </div>
                <p class="text-xs text-ink/60 uppercase tracking-wide">Pendapatan</p>
                <p class="text-lg font-extrabold text-latte-dark mt-1"><?= rupiah($pendapatan); ?></p>
            </div>
        </div>

        <!-- Order Terbaru -->
        <div class="bg-white rounded-xl border border-sky/70 overflow-hidden shadow-sm">
            <div class="px-5 py-4 border-b border-sky/60 flex items-center justify-between">
                <h2 class="font-bold text-ocean-dark flex items-center gap-2">
                    <i data-lucide="clock-4" class="w-5 h-5 text-latte"></i>
                    Order Terbaru
                </h2>
                <a href="<?= asset('admin/order.php'); ?>" class="text-sm text-ocean hover:text-ocean-dark flex items-center gap-1 font-medium">
                    Lihat semua <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-cream text-ocean-dark">
                        <tr>
                            <th class="text-left px-5 py-2.5 font-semibold">Kode</th>
                            <th class="text-left px-5 py-2.5 font-semibold">Pelanggan</th>
                            <th class="text-right px-5 py-2.5 font-semibold">Total</th>
                            <th class="text-center px-5 py-2.5 font-semibold">Status</th>
                            <th class="text-center px-5 py-2.5 font-semibold">Waktu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-sky/50">
                        <?php if (mysqli_num_rows($recent) === 0): ?>
                            <tr><td colspan="5" class="px-5 py-6 text-center text-ink/50">Belum ada pesanan.</td></tr>
                        <?php endif; ?>
                        <?php while ($o = mysqli_fetch_assoc($recent)):
                            $badge = match ($o['status']) {
                                'pending'    => 'bg-latte/20 text-latte-dark',
                                'diproses'   => 'bg-ocean/10 text-ocean',
                                'selesai'    => 'bg-emerald-100 text-emerald-700',
                                'dibatalkan' => 'bg-red-100 text-red-700',
                                default      => 'bg-sky/40 text-ocean',
                            };
                        ?>
                        <tr class="hover:bg-cream/70">
                            <td class="px-5 py-3"><code class="text-xs font-semibold text-ink"><?= htmlspecialchars($o['kode_order']); ?></code></td>
                            <td class="px-5 py-3"><?= htmlspecialchars($o['nama']); ?></td>
                            <td class="px-5 py-3 text-right font-semibold"><?= rupiah($o['total_harga']); ?></td>
                            <td class="px-5 py-3 text-center">
                                <span class="inline-block px-2 py-0.5 rounded-full text-xs font-semibold capitalize <?= $badge; ?>"><?= $o['status']; ?></span>
                            </td>
                            <td class="px-5 py-3 text-center text-ink/60"><?= date('d/m H:i', strtotime($o['waktu_order'])); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<?php include '../includes/footer.php'; ?>