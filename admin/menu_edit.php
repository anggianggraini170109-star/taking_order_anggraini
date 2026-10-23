<?php
require_once '../config/koneksi.php';
if (!isset($_SESSION['admin_id'])) { header("Location: " . asset('admin/login.php')); exit; }
$judul = "Edit Menu";
include '../includes/header.php';

$id = (int)($_GET['id'] ?? 0);
$q  = mysqli_query($koneksi, "SELECT * FROM menu WHERE id = $id");
$m  = mysqli_fetch_assoc($q);
if (!$m) { header("Location: " . asset('admin/menu.php')); exit; }

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama  = mysqli_real_escape_string($koneksi, $_POST['nama_menu']);
    $kat   = mysqli_real_escape_string($koneksi, $_POST['kategori']);
    $harga = (float)$_POST['harga'];
    $desc  = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    $stok  = (int)$_POST['stok'];
    $stat  = $_POST['status'] === 'habis' ? 'habis' : 'tersedia';
    $feat  = isset($_POST['is_featured']) ? 1 : 0;

    $filename = $m['gambar'];

    if (!empty($_POST['hapus_gambar']) && $filename) {
        if (file_exists(UPLOAD_DIR . $filename)) unlink(UPLOAD_DIR . $filename);
        $filename = null;
    }

    if (!empty($_FILES['gambar']['name'])) {
        $ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','webp'];
        if (!in_array($ext, $allowed)) {
            $error = "Format gambar harus JPG, PNG, atau WEBP.";
        } elseif ($_FILES['gambar']['size'] > 2 * 1024 * 1024) {
            $error = "Ukuran gambar maksimal 2MB.";
        } else {
            if ($filename && file_exists(UPLOAD_DIR . $filename)) unlink(UPLOAD_DIR . $filename);
            $filename = slugify($nama) . '-' . uniqid() . '.' . $ext;
            if (!move_uploaded_file($_FILES['gambar']['tmp_name'], UPLOAD_DIR . $filename)) {
                $error = "Gagal mengunggah gambar.";
            }
        }
    }

    if (!$error) {
        $gambarSql = $filename ? "'$filename'" : "NULL";
        $sql = "UPDATE menu SET nama_menu='$nama', kategori='$kat', harga=$harga,
                deskripsi='$desc', gambar=$gambarSql, stok=$stok, status='$stat', is_featured=$feat
                WHERE id=$id";
        if (mysqli_query($koneksi, $sql)) {
            $_SESSION['pesan'] = "Menu berhasil diperbarui.";
            header("Location: " . asset('admin/menu.php')); exit;
        }
        $error = "Gagal update: " . mysqli_error($koneksi);
    }

    $q = mysqli_query($koneksi, "SELECT * FROM menu WHERE id = $id");
    $m = mysqli_fetch_assoc($q);
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
            <a href="<?= asset('admin/logout.php'); ?>"
               class="border border-white/30 hover:bg-white/10 px-3 py-1.5 rounded-lg flex items-center gap-1.5 transition">
                <i data-lucide="log-out" class="w-4 h-4"></i> Logout
            </a>
        </div>
    </div>
</nav>

<div class="max-w-5xl mx-auto px-4 py-8">
    <a href="<?= asset('admin/menu.php'); ?>" class="inline-flex items-center gap-1 text-sm text-ocean hover:underline mb-3">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali
    </a>
    <h1 class="text-2xl font-extrabold text-ocean-dark mb-1">Edit Menu</h1>
    <p class="text-sm text-ink/60 mb-6">Perbarui data produk.</p>

    <?php if ($error): ?>
        <div class="mb-4 flex items-start gap-2 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm p-3">
            <i data-lucide="alert-circle" class="w-5 h-5 shrink-0 mt-0.5"></i>
            <span><?= htmlspecialchars($error); ?></span>
        </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data"
          class="bg-white rounded-xl border border-sky/70 p-6 grid lg:grid-cols-3 gap-6 shadow-sm">

        <div class="lg:col-span-1">
            <label class="block text-sm font-semibold text-ink mb-1.5">Foto Produk</label>
            <label for="file-input"
                   class="mt-1 flex flex-col items-center justify-center gap-2 w-full aspect-square rounded-xl border-2 border-dashed border-sky hover:border-ocean bg-cream cursor-pointer transition overflow-hidden relative">
                <img id="preview"
                     src="<?= htmlspecialchars(menuImage($m['gambar'])); ?>"
                     alt="<?= htmlspecialchars($m['nama_menu']); ?>"
                     class="w-full h-full object-cover absolute inset-0">
            </label>
            <input type="file" id="file-input" name="gambar" accept="image/*" class="hidden"
                   onchange="previewImage(event)">
            <?php if (!empty($m['gambar'])): ?>
            <label class="mt-2 inline-flex items-center gap-2 text-sm text-red-600">
                <input type="checkbox" name="hapus_gambar" value="1" class="rounded border-sky">
                Hapus gambar saat ini
            </label>
            <?php endif; ?>
        </div>

        <div class="lg:col-span-2 grid sm:grid-cols-2 gap-4 text-sm">
            <div class="sm:col-span-2">
                <label class="block font-semibold text-ink mb-1.5">Nama Menu</label>
                <input type="text" name="nama_menu" required value="<?= htmlspecialchars($m['nama_menu']); ?>"
                    class="w-full rounded-lg border border-sky bg-white focus:border-ocean outline-none px-3 py-2.5">
            </div>
            <div>
                <label class="block font-semibold text-ink mb-1.5">Kategori</label>
                <input type="text" name="kategori" required value="<?= htmlspecialchars($m['kategori']); ?>"
                    class="w-full rounded-lg border border-sky bg-white focus:border-ocean outline-none px-3 py-2.5">
            </div>
            <div>
                <label class="block font-semibold text-ink mb-1.5">Harga (Rp)</label>
                <input type="number" name="harga" required min="0" step="500" value="<?= (float)$m['harga']; ?>"
                    class="w-full rounded-lg border border-sky bg-white focus:border-ocean outline-none px-3 py-2.5">
            </div>
            <div>
                <label class="block font-semibold text-ink mb-1.5">Stok</label>
                <input type="number" name="stok" required min="0" value="<?= (int)$m['stok']; ?>"
                    class="w-full rounded-lg border border-sky bg-white focus:border-ocean outline-none px-3 py-2.5">
            </div>
            <div>
                <label class="block font-semibold text-ink mb-1.5">Status</label>
                <select name="status"
                    class="w-full rounded-lg border border-sky bg-white focus:border-ocean outline-none px-3 py-2.5">
                    <option value="tersedia" <?= $m['status'] === 'tersedia' ? 'selected' : ''; ?>>Tersedia</option>
                    <option value="habis"    <?= $m['status'] === 'habis'    ? 'selected' : ''; ?>>Habis</option>
                </select>
            </div>
            <div class="sm:col-span-2">
                <label class="block font-semibold text-ink mb-1.5">Deskripsi</label>
                <textarea name="deskripsi" rows="3"
                    class="w-full rounded-lg border border-sky bg-white focus:border-ocean outline-none px-3 py-2.5"><?= htmlspecialchars($m['deskripsi']); ?></textarea>
            </div>
            <label class="sm:col-span-2 inline-flex items-center gap-2">
                <input type="checkbox" name="is_featured" <?= $m['is_featured'] ? 'checked' : ''; ?>
                       class="rounded border-sky text-ocean focus:ring-ocean">
                <span class="text-ink">Tampilkan sebagai menu favorit</span>
            </label>

            <div class="sm:col-span-2 flex gap-2 pt-2">
                <button type="submit"
                    class="inline-flex items-center gap-2 bg-ocean hover:bg-ocean-dark text-white font-semibold px-5 py-2.5 rounded-lg transition">
                    <i data-lucide="save" class="w-4 h-4"></i> Update
                </button>
                <a href="<?= asset('admin/menu.php'); ?>"
                   class="inline-flex items-center gap-2 border border-sky hover:bg-cream px-5 py-2.5 rounded-lg text-ink font-semibold transition">
                    Batal
                </a>
            </div>
        </div>
    </form>
</div>

<script>
function previewImage(e) {
    const file = e.target.files[0];
    if (!file) return;
    document.getElementById('preview').src = URL.createObjectURL(file);
}
</script>

<?php include '../includes/footer.php'; ?>