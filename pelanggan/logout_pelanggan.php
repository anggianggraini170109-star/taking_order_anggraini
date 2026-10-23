<?php
session_start();
unset($_SESSION['pelanggan_id'], $_SESSION['pelanggan_nama'], $_SESSION['pelanggan_meja'], $_SESSION['keranjang']);
session_destroy();
header("Location: ../index.php");