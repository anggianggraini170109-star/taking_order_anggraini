<?php
require_once '../config/koneksi.php';
if (!isset($_SESSION['pelanggan_id'])) { header("Location: " . asset('index.php')); exit; }
$judul = "Menu - Green Haven Cafe";
include '../includes/header.php';
include '../includes/navbar.php';

$kategori_filter = $_GET['kategori'] ?? '';
$where = "WHERE status = 'tersedia'";
if ($kategori_filter !== '') {
    $kf = mysqli_real_escape_string($koneksi, $kategori_filter);
    $where .= " AND kategori = '$kf'";
}
$query = mysqli_query($koneksi, "SELECT * FROM menu $where ORDER BY is_featured DESC, kategori, nama_menu");
$kategori_list = mysqli_query($koneksi, "SELECT DISTINCT kategori FROM menu ORDER BY kategori");
?>

<div class="max-w-6xl mx-auto px-4 py-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <div>
            <h1 class="text-2xl font-extrabold text-ocean-dark">Menu</h1>
            <p class="text-ink/60 text-sm mt-0.5">Pilih menu favorit Anda.</p>
        </div>
        <a href="<?= asset('pelanggan/keranjang.php'); ?>"
           class="inline-flex items-center gap-2 bg-latte hover:bg-latte-dark text-white px-4 py-2 rounded-lg font-semibold transition">
            <i data-lucide="shopping-cart" class="w-4 h-4"></i> Lihat Keranjang
        </a>
    </div>

    <?php if (isset($_SESSION['pesan'])): ?>
        <div class="mb-4 flex items-start gap-2 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm p-3">
            <i data-lucide="check-circle" class="w-5 h-5 shrink-0 mt-0.5"></i>
            <span><?= $_SESSION['pesan']; unset($_SESSION['pesan']); ?></span>
        </div>
    <?php endif; ?>

    <div class="flex flex-wrap gap-2 mb-6">
        <a href="<?= asset('pelanggan/menu.php'); ?>"
           class="px-4 py-1.5 rounded-full text-sm font-medium border transition
                  <?= $kategori_filter === '' ? 'bg-ocean text-white border-ocean' : 'bg-white text-ink border-sky hover:border-ocean'; ?>">
            Semua
        </a>
        <?php while ($k = mysqli_fetch_assoc($kategori_list)): ?>
            <a href="<?= asset('pelanggan/menu.php?kategori=' . urlencode($k['kategori'])); ?>"
               class="px-4 py-1.5 rounded-full text-sm font-medium border transition
                      <?= $kategori_filter === $k['kategori'] ? 'bg-ocean text-white border-ocean' : 'bg-white text-ink border-sky hover:border-ocean'; ?>">
                <?= htmlspecialchars($k['kategori']); ?>
            </a>
        <?php endwhile; ?>
    </div>

    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
        <?php if (mysqli_num_rows($query) === 0): ?>
            <div class="col-span-full bg-white border border-sky rounded-xl p-8 text-center text-ink/60">
                <i data-lucide="inbox" class="w-10 h-10 mx-auto mb-2"></i>
                Belum ada menu tersedia.
            </div>
        <?php endif; ?>

        <?php while ($m = mysqli_fetch_assoc($query)): ?>
        <article class="group bg-white rounded-xl overflow-hidden border border-sky/70 shadow-sm hover:shadow-md hover:border-ocean transition flex flex-col">
            <div class="relative aspect-[4/3] overflow-hidden bg-cream">
                <img src="<?= htmlspecialchars(menuImage($m['gambar'])); ?>"
                     alt="<?= htmlspecialchars($m['nama_menu']); ?>"
                     loading="lazy"
                     class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                <?php if ($m['is_featured']): ?>
                    <span class="absolute top-2 left-2 bg-latte text-white text-[11px] font-bold px-2 py-0.5 rounded-full flex items-center gap-1">
                        <i data-lucide="star" class="w-3 h-3"></i> Favorit
                    </span>
                <?php endif; ?>
                <span class="absolute top-2 right-2 bg-ocean text-white text-[11px] font-semibold px-2 py-0.5 rounded-full">
                    <?= htmlspecialchars($m['kategori']); ?>
                </span>
            </div>

            <div class="p-4 flex flex-col flex-grow">
                <h3 class="font-bold text-ocean-dark text-lg leading-tight"><?= htmlspecialchars($m['nama_menu']); ?></h3>
                <p class="text-sm text-ink/60 line-clamp-2 mt-1"><?= htmlspecialchars($m['deskripsi']); ?></p>

                <div class="mt-3">
                    <p class="text-latte-dark font-extrabold text-lg"><?= rupiah($m['harga']); ?></p>
                    <p class="text-xs text-ink/50 flex items-center gap-1 mt-0.5">
                        <i data-lucide="package" class="w-3 h-3"></i> Stok: <?= (int)$m['stok']; ?>
                    </p>
                </div>

                <form action="<?= asset('proses/tambah_keranjang.php'); ?>" method="POST" class="mt-3 flex gap-2">
                    <input type="hidden" name="menu_id" value="<?= (int)$m['id']; ?>">
                    <input type="number" name="jumlah" value="1" min="1" max="<?= (int)$m['stok']; ?>"
                           class="w-20 rounded-lg border border-sky bg-white focus:border-ocean outline-none px-2 py-2 text-sm">
                    <button type="submit"
                            class="flex-1 inline-flex items-center justify-center gap-1.5 bg-ocean hover:bg-ocean-dark text-white text-sm font-semibold rounded-lg py-2 transition">
                        <i data-lucide="plus-circle" class="w-4 h-4"></i> Tambah
                    </button>
                </form>
            </div>
        </article>
        <?php endwhile; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>