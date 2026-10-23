<?php
require_once '../config/koneksi.php';
if (!isset($_SESSION['admin_id'])) { header("Location: " . asset('admin/login.php')); exit; }

$tgl_awal  = $_GET['tgl_awal']  ?? date('Y-m-01');
$tgl_akhir = $_GET['tgl_akhir'] ?? date('Y-m-d');
$ta = mysqli_real_escape_string($koneksi, $tgl_awal);
$tk = mysqli_real_escape_string($koneksi, $tgl_akhir);

$q = mysqli_query($koneksi, "SELECT o.*, p.nama FROM orders o
                             JOIN pelanggan p ON o.pelanggan_id = p.id
                             WHERE DATE(o.waktu_order) BETWEEN '$ta' AND '$tk'
                             ORDER BY o.waktu_order ASC");

$total = 0; $data = [];
while ($r = mysqli_fetch_assoc($q)) { $data[] = $r; if ($r['status'] === 'selesai') $total += $r['total_harga']; }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Order - Green Haven Cafe</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { padding: 0; margin: 10mm; }
            @page { size: A4; margin: 15mm; }
        }
        body { font-family: Arial, sans-serif; }
    </style>
</head>
<body class="bg-white p-8 text-ink text-sm">

    <div class="no-print mb-4 flex gap-2">
        <button onclick="window.print()" class="bg-ocean text-white px-4 py-2 rounded-lg font-semibold">Print</button>
        <button onclick="window.close()" class="border border-sky px-4 py-2 rounded-lg font-semibold">Tutup</button>
    </div>

    <!-- Header dengan logo -->
    <div class="flex items-center gap-4 border-b-2 border-ink pb-4 mb-4">
        <img src="<?= asset('assets/logo-brand.png'); ?>" alt="Logo" class="h-16 w-16 object-contain">
        <div class="flex-1">
            <h1 class="text-2xl font-extrabold tracking-wide text-ink">GREEN HAVEN CAFE</h1>
            <p class="text-ink/70 text-sm">Jl. Contoh Alamat No. 123, Kota Anda</p>
            <p class="text-ink/70 text-sm">Telp: 021-1234567 | Email: info@greenhaven.id</p>
        </div>
    </div>

    <h2 class="text-center text-lg font-bold mb-4 text-ink">LAPORAN ORDER</h2>

    <div class="mb-3 text-xs text-ink">
        <p><strong>Periode:</strong> <?= date('d/m/Y', strtotime($tgl_awal)); ?> s/d <?= date('d/m/Y', strtotime($tgl_akhir)); ?></p>
        <p><strong>Tanggal Cetak:</strong> <?= date('d/m/Y H:i'); ?></p>
    </div>

    <table class="w-full border-collapse border border-ink text-xs">
        <thead>
            <tr class="bg-cream">
                <th class="border border-ink px-2 py-1">No</th>
                <th class="border border-ink px-2 py-1">Kode</th>
                <th class="border border-ink px-2 py-1">Nama</th>
                <th class="border border-ink px-2 py-1">Meja</th>
                <th class="border border-ink px-2 py-1">Total</th>
                <th class="border border-ink px-2 py-1">Status</th>
                <th class="border border-ink px-2 py-1">Waktu</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($data)): ?>
                <tr><td colspan="7" class="border border-ink px-2 py-3 text-center">Tidak ada data.</td></tr>
            <?php else: $no = 1; foreach ($data as $o): ?>
                <tr>
                    <td class="border border-ink px-2 py-1 text-center"><?= $no++; ?></td>
                    <td class="border border-ink px-2 py-1"><?= htmlspecialchars($o['kode_order']); ?></td>
                    <td class="border border-ink px-2 py-1"><?= htmlspecialchars($o['nama']); ?></td>
                    <td class="border border-ink px-2 py-1 text-center"><?= htmlspecialchars($o['no_meja']); ?></td>
                    <td class="border border-ink px-2 py-1 text-right"><?= rupiah($o['total_harga']); ?></td>
                    <td class="border border-ink px-2 py-1 text-center capitalize"><?= htmlspecialchars($o['status']); ?></td>
                    <td class="border border-ink px-2 py-1 text-center"><?= date('d/m/Y H:i', strtotime($o['waktu_order'])); ?></td>
                </tr>
            <?php endforeach; endif; ?>
        </tbody>
    </table>

    <div class="mt-4 text-right font-bold">
        Total Pendapatan (Order Selesai): <?= rupiah($total); ?>
    </div>

    <div class="mt-12 text-right text-xs">
        <p>Kota Anda, <?= date('d/m/Y'); ?></p>
        <p>Administrator,</p>
        <p class="mt-16 border-t border-ink inline-block min-w-[180px] text-center pt-1">
            <?= htmlspecialchars($_SESSION['admin_nama']); ?>
        </p>
    </div>

    <script>window.onload = () => window.print();</script>
</body>
</html>