<?php
require_once '../config/koneksi.php';
if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit; }

$id = (int)($_GET['id'] ?? 0);
$q  = mysqli_query($koneksi, "SELECT gambar FROM menu WHERE id = $id");
if ($row = mysqli_fetch_assoc($q)) {
    if (!empty($row['gambar']) && file_exists(UPLOAD_DIR . $row['gambar'])) {
        unlink(UPLOAD_DIR . $row['gambar']);
    }
}
mysqli_query($koneksi, "DELETE FROM menu WHERE id = $id");
$_SESSION['pesan'] = "Menu berhasil dihapus.";
header("Location: menu.php");