<?php
require_once '../config/koneksi.php';
if (!isset($_SESSION['admin_id'])) { header("Location: " . asset('admin/login.php')); exit; }
$judul = "Kelola Menu";
include '../includes/header.php';

$q = mysqli_query($koneksi, "SELECT * FROM menu ORDER BY kategori, nama_menu");
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
        <div class="flex items-center justify-between mb-6 flex-wrap gap-3">
            <div>
                <h1 class="text-2xl font-extrabold text-ocean-dark">Kelola Menu</h1>
                <p class="text-sm text-ink/60 mt-0.5">Tambah, ubah, dan kelola produk.</p>
            </div>
            <a href="<?= asset('admin/menu_tambah.php'); ?>"
               class="inline-flex items-center gap-2 bg-ocean hover:bg-ocean-dark text-white font-semibold px-4 py-2 rounded-lg transition">
                <i data-lucide="plus" class="w-4 h-4"></i> Tambah Menu
            </a>
        </div>

        <?php if (isset($_SESSION['pesan'])): ?>
            <div class="mb-4 flex items-start gap-2 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm p-3">
                <i data-lucide="check-circle" class="w-5 h-5 shrink-0 mt-0.5"></i>
                <span><?= $_SESSION['pesan']; unset($_SESSION['pesan']); ?></span>
            </div>
        <?php endif; ?>

        <div class="bg-white rounded-xl border border-sky/70 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-ocean text-white">
                        <tr>
                            <th class="text-left px-4 py-3 font-semibold">Produk</th>
                            <th class="text-left px-4 py-3 font-semibold">Kategori</th>
                            <th class="text-right px-4 py-3 font-semibold">Harga</th>
                            <th class="text-center px-4 py-3 font-semibold">Stok</th>
                            <th class="text-center px-4 py-3 font-semibold">Status</th>
                            <th class="text-center px-4 py-3 font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-sky/50">
                        <?php while ($m = mysqli_fetch_assoc($q)): ?>
                        <tr class="hover:bg-cream/70">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <img src="<?= htmlspecialchars(menuImage($m['gambar'])); ?>"
                                         alt="<?= htmlspecialchars($m['nama_menu']); ?>"
                                         class="w-12 h-12 rounded-md object-cover border border-sky">
                                    <span class="font-semibold text-ocean-dark"><?= htmlspecialchars($m['nama_menu']); ?></span>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-block bg-cream text-ocean-dark text-xs font-semibold px-2 py-0.5 rounded-full border border-sky/70">
                                    <?= htmlspecialchars($m['kategori']); ?>
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right font-semibold"><?= rupiah($m['harga']); ?></td>
                            <td class="px-4 py-3 text-center"><?= (int)$m['stok']; ?></td>
                            <td class="px-4 py-3 text-center">
                                <?php if ($m['status'] === 'tersedia'): ?>
                                    <span class="inline-block bg-emerald-100 text-emerald-700 text-xs font-semibold px-2 py-0.5 rounded-full">Tersedia</span>
                                <?php else: ?>
                                    <span class="inline-block bg-red-100 text-red-700 text-xs font-semibold px-2 py-0.5 rounded-full">Habis</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center gap-1">
                                    <a href="<?= asset('admin/menu_edit.php?id=' . (int)$m['id']); ?>"
                                       class="p-2 rounded-md text-ocean hover:bg-cream transition" title="Edit">
                                        <i data-lucide="pencil" class="w-4 h-4"></i>
                                    </a>
                                    <a href="<?= asset('admin/menu_hapus.php?id=' . (int)$m['id']); ?>"
                                       onclick="return confirm('Hapus menu ini?')"
                                       class="p-2 rounded-md text-red-600 hover:bg-red-50 transition" title="Hapus">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </a>
                                </div>
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