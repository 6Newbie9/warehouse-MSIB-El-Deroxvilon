<?php
require_once 'database.php';
require_once 'barang.php';

// Koneksi ke database
$database = new Database();
$db = $database->getConnection();

// Membuat objek barang
$barang = new Barang($db);

// Ambil ID barang dari URL
if (isset($_GET['id'])) {
    $barang->id = $_GET['id'];

    // Menghapus barang
    if ($barang->delete()) {
        echo "<div class='alert alert-success'>Barang berhasil dihapus.</div>";
    } else {
        echo "<div class='alert alert-danger'>Gagal menghapus barang.</div>";
    }
}

// Kembali ke halaman sebelumnya (daftar barang)
header("Location: index.php");
exit;
