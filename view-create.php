<?php
require_once 'database.php';
require_once 'gudang.php';

// Koneksi ke database
$database = new Database();
$db = $database->getConnection();

// Membuat objek gudang
$gudang = new Gudang($db);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $gudang->name = $_POST['name'];
    $gudang->location = $_POST['location'];
    $gudang->capacity = $_POST['capacity'];
    $gudang->status = $_POST['status'];
    $gudang->opening_hour = $_POST['opening_hour'];
    $gudang->closing_hour = $_POST['closing_hour'];
    
    if ($gudang->create()) {
        header("Location: index.php");
        exit;
    } else {
        echo "<div class='alert alert-danger'>Gagal menambah gudang. Silakan coba lagi.</div>";
    }
}

// Start output buffering to capture the content
ob_start();
?>

<div class="container mt-5">
    <h1 class="text-center mb-4">Tambah Gudang Baru</h1>

    <form action="view-create.php" method="POST" class="shadow p-4 rounded bg-light">
        <div class="mb-3">
            <label for="name" class="form-label">Nama Gudang:</label>
            <input type="text" class="form-control" name="name" id="name" placeholder="Masukkan nama gudang" required>
        </div>
        <div class="mb-3">
            <label for="location" class="form-label">Lokasi:</label>
            <input type="text" class="form-control" name="location" id="location" placeholder="Masukkan lokasi gudang" required>
        </div>
        <div class="mb-3">
            <label for="capacity" class="form-label">Kapasitas:</label>
            <input type="number" class="form-control" name="capacity" id="capacity" placeholder="Masukkan kapasitas" required>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Status:</label>
            <select class="form-select" name="status" id="status" required>
                <option value="aktif">Aktif</option>
                <option value="tidak_aktif">Tidak Aktif</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="opening_hour" class="form-label">Jam Buka:</label>
            <input type="time" class="form-control" name="opening_hour" id="opening_hour">
        </div>
        <div class="mb-3">
            <label for="closing_hour" class="form-label">Jam Tutup:</label>
            <input type="time" class="form-control" name="closing_hour" id="closing_hour">
        </div>
        <button type="submit" class="btn btn-success w-100">Tambah Gudang</button>
    </form>

    <div class="text-center mt-4">
        <a href="index.php" class="btn btn-secondary">Kembali ke Daftar Gudang</a>
    </div>
</div>

<?php
$content = ob_get_clean();

// Include the layout template and pass the content
include 'layout.php';
?>
