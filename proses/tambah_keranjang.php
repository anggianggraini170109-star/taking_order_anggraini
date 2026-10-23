<?php
require_once '../config/koneksi.php';
if (!isset($_SESSION['pelanggan_id'])) { header("Location: ../index.php"); exit; }

$menu_id = (int)($_POST['menu_id'] ?? 0);
$jumlah  = max(1, (int)($_POST['jumlah'] ?? 1));

$q = mysqli_query($koneksi, "SELECT * FROM menu WHERE id=$menu_id AND status='tersedia'");
$m = mysqli_fetch_assoc($q);
if (!$m) { $_SESSION['pesan'] = "Menu tidak ditemukan."; header("Location: ../pelanggan/menu.php"); exit; }

if ($jumlah > $m['stok']) $jumlah = (int)$m['stok'];
if (!isset($_SESSION['keranjang'])) $_SESSION['keranjang'] = [];

if (isset($_SESSION['keranjang'][$menu_id])) {
    $_SESSION['keranjang'][$menu_id] += $jumlah;
    if ($_SESSION['keranjang'][$menu_id] > $m['stok']) {
        $_SESSION['keranjang'][$menu_id] = (int)$m['stok'];
    }
} else {
    $_SESSION['keranjang'][$menu_id] = $jumlah;
}
$_SESSION['pesan'] = $m['nama_menu'] . " ditambahkan ke keranjang.";
header("Location: ../pelanggan/menu.php");