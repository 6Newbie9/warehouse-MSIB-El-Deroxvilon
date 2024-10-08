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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $gudang->name = $_POST['name'];
    $gudang->location = $_POST['location'];
    $gudang->capacity = $_POST['capacity'];
    $gudang->status = $_POST['status'];
    $gudang->opening_hour = $_POST['opening_hour'];
    $gudang->closing_hour = $_POST['closing_hour'];
    
    if ($gudang->update()) {
        header("Location: index.php");
        exit;
    } else {
        echo "<div class='alert alert-danger'>Gagal mengupdate gudang. Silakan coba lagi.</div>";
    }
} else {
    // Mendapatkan data gudang berdasarkan ID
    $stmt = $gudang->show($gudang->id);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    $gudang->name = $data['name'];
    $gudang->location = $data['location'];
    $gudang->capacity = $data['capacity'];
    $gudang->status = $data['status'];
    $gudang->opening_hour = $data['opening_hour'];
    $gudang->closing_hour = $data['closing_hour'];
}

// Start output buffering to capture the content
ob_start();
?>

<div class="container mt-5">
    <h1 class="text-center mb-4">Edit Gudang</h1>

    <form action="view-edit.php?id=<?php echo $gudang->id; ?>" method="POST" class="shadow p-4 rounded bg-light">
        <div class="mb-3">
            <label for="name" class="form-label">Nama Gudang:</label>
            <input type="text" class="form-control" name="name" id="name" value="<?php echo $gudang->name; ?>" required>
        </div>
        <div class="mb-3">
            <label for="location" class="form-label">Lokasi:</label>
            <input type="text" class="form-control" name="location" id="location" value="<?php echo $gudang->location; ?>" required>
        </div>
        <div class="mb-3">
            <label for="capacity" class="form-label">Kapasitas:</label>
            <input type="number" class="form-control" name="capacity" id="capacity" value="<?php echo $gudang->capacity; ?>" required>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Status:</label>
            <select class="form-select" name="status" id="status" required>
                <option value="aktif" <?php if($gudang->status == 'aktif') echo 'selected'; ?>>Aktif</option>
                <option value="tidak_aktif" <?php if($gudang->status == 'tidak_aktif') echo 'selected'; ?>>Tidak Aktif</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="opening_hour" class="form-label">Jam Buka:</label>
            <input type="time" class="form-control" name="opening_hour" id="opening_hour" value="<?php echo $gudang->opening_hour; ?>">
        </div>
        <div class="mb-3">
            <label for="closing_hour" class="form-label">Jam Tutup:</label>
            <input type="time" class="form-control" name="closing_hour" id="closing_hour" value="<?php echo $gudang->closing_hour; ?>">
        </div>
        <button type="submit" class="btn btn-warning w-100">Update Gudang</button>
    </form>

    <div class="text-center mt-4">
        <a href="index.php" class="btn btn-secondary">Kembali ke Daftar Gudang</a>
    </div>
</div>

<?php
$content = ob_get_clean();
include 'layout.php';
?>
