<?php
require_once 'database.php';
require_once 'gudang.php';

// Koneksi ke database
$database = new Database();
$db = $database->getConnection();

// Membuat objek gudang
$gudang = new Gudang($db);

// Mendapatkan ID gudang dari URL
$gudang->id = isset($_GET['id']) ? $_GET['id'] : die('Error: ID tidak ditemukan.');

// Dapatkan data status gudang saat ini
$stmt = $gudang->show($gudang->id);
$data = $stmt->fetch(PDO::FETCH_ASSOC);
$gudang->status = $data['status'];

// Toggle status gudang
if ($gudang->toggleStatus()) {
    header("Location: index.php");
    exit;
} else {
    echo "Gagal memperbarui status gudang.";
}
?>
