<?php
// =========================================================
// config/koneksi.php — Green Haven Cafe
// =========================================================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_cafe";

$koneksi = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
mysqli_set_charset($koneksi, "utf8mb4");

date_default_timezone_set('Asia/Jakarta');

// =========================================================
// BASE URL — sesuaikan dengan folder project Anda
// =========================================================
define('BASE_URL', 'http://localhost/taking_order_anggraini/');

// Konstanta folder upload
define('UPLOAD_DIR', __DIR__ . '/../uploads/menu/');
define('UPLOAD_URL', BASE_URL . 'uploads/menu/');

if (!is_dir(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0775, true);
}

/**
 * Bangun URL asset absolut
 */
function asset($path = '') {
    return BASE_URL . ltrim($path, '/');
}

/**
 * Format Rupiah
 */
function rupiah($angka) {
    return "Rp " . number_format((float)$angka, 0, ',', '.');
}

/**
 * Generate kode order unik
 */
function generateKodeOrder() {
    return "GH-" . date("YmdHis") . "-" . rand(100, 999);
}

/**
 * Buat slug
 */
function slugify($text) {
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    return trim($text, '-');
}

/**
 * URL gambar menu dengan fallback placeholder
 */
function menuImage($filename) {
    if (!empty($filename) && file_exists(UPLOAD_DIR . $filename)) {
        return UPLOAD_URL . $filename;
    }
    // Placeholder netral — tanpa warna mencolok
    return 'data:image/svg+xml;utf8,' . rawurlencode(
        '<svg xmlns="http://www.w3.org/2000/svg" width="400" height="300">'
        . '<rect width="100%" height="100%" fill="#FFF9EB"/>'
        . '<line x1="160" y1="120" x2="240" y2="120" stroke="#C38B58" stroke-width="2"/>'
        . '<line x1="160" y1="140" x2="240" y2="140" stroke="#C38B58" stroke-width="2"/>'
        . '<text x="50%" y="180" font-family="Arial" font-size="16" fill="#1B438E" '
        . 'text-anchor="middle">Tidak ada foto</text></svg>'
    );
}