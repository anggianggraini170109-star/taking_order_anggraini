<?php
require_once '../config/koneksi.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header("Location: ../index.php"); exit; }

$nama    = trim($_POST['nama'] ?? '');
$no_meja = trim($_POST['no_meja'] ?? '');
$no_hp   = trim($_POST['no_hp'] ?? '');

if ($nama === '' || $no_meja === '') {
    $_SESSION['error'] = "Nama dan nomor meja wajib diisi.";
    header("Location: ../index.php"); exit;
}

$nama    = mysqli_real_escape_string($koneksi, $nama);
$no_meja = mysqli_real_escape_string($koneksi, $no_meja);
$no_hp   = mysqli_real_escape_string($koneksi, $no_hp);

$q = "INSERT INTO pelanggan (nama, no_meja, no_hp) VALUES ('$nama','$no_meja','$no_hp')";
if (mysqli_query($koneksi, $q)) {
    $_SESSION['pelanggan_id']   = mysqli_insert_id($koneksi);
    $_SESSION['pelanggan_nama'] = $nama;
    $_SESSION['pelanggan_meja'] = $no_meja;
    $_SESSION['keranjang']      = [];
    header("Location: ../pelanggan/menu.php"); exit;
}
$_SESSION['error'] = "Terjadi kesalahan: " . mysqli_error($koneksi);
header("Location: ../index.php");