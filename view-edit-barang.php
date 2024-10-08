<?php
require_once 'database.php';
require_once 'barang.php';
require_once 'gudang.php';

// Koneksi ke database
$database = new Database();
$db = $database->getConnection();

// Membuat objek barang dan gudang
$barang = new Barang($db);
$gudang = new Gudang($db);

// Ambil ID barang dari URL
if (isset($_GET['id'])) {
    $barang->id = $_GET['id'];
    // Membaca data barang
    $stmt = $barang->read();
    $num = $stmt->rowCount();

    if ($num > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $barang->name = $row['name'];
        $barang->quantity = $row['quantity'];
        $barang->gudang_id = $row['gudang_id'];
    } else {
        echo "<div class='alert alert-danger'>Barang tidak ditemukan.</div>";
        return;
    }
}    

// Mendapatkan daftar gudang untuk pemilih
$gudang_options = "";
$gudang_stmt = $gudang->read(); // Asumsikan ada metode read() untuk mengambil semua gudang
while ($gudang_row = $gudang_stmt->fetch(PDO::FETCH_ASSOC)) {
    $selected = ($gudang_row['id'] == $barang->gudang_id) ? 'selected' : '';
    $gudang_options .= "<option value='{$gudang_row['id']}' {$selected}>{$gudang_row['name']}</option>";
}

// Memproses pembaruan data barang jika form disubmit
if ($_POST) {
    // Mengupdate barang
    $barang->name = $_POST['name'];
    $barang->quantity = $_POST['quantity'];
    $barang->gudang_id = $_POST['gudang_id'];
    $barang->id = $_POST['id'];

    if ($barang->update()) {
        echo "<div class='alert alert-success'>Barang berhasil diperbarui.</div>";
    } else {
        echo "<div class='alert alert-danger'>Gagal memperbarui barang.</div>";
    }
}

// Start output buffering to capture the content
ob_start();
?>

<div class="container mt-5">
    <h1 class="text-center mb-4">Edit Barang</h1>

    <form action="" method="post" class="shadow p-4 rounded bg-light">
        <input type="hidden" name="id" value="<?php echo $barang->id; ?>" />
        <div class="mb-3">
            <label for="name" class="form-label">Nama Barang:</label>
            <input type="text" class="form-control" name="name" id="name" value="<?php echo htmlspecialchars($barang->name); ?>" required />
        </div>
        <div class="mb-3">
            <label for="quantity" class="form-label">Jumlah:</label>
            <input type="number" class="form-control" name="quantity" id="quantity" value="<?php echo htmlspecialchars($barang->quantity); ?>" required />
        </div>
        <div class="mb-3">
            <label for="gudang_id" class="form-label">Pilih Gudang:</label>
            <select class="form-control" name="gudang_id" id="gudang_id" required>
                <option value="">-- Pilih Gudang --</option>
                <?php echo $gudang_options; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-warning w-100">Simpan Perubahan</button>
    </form>

    <div class="text-center mt-4">
        <a href="index.php" class="btn btn-secondary">Kembali ke Daftar Barang</a>
    </div>
</div>

<?php
$content = ob_get_clean();
include 'layout.php';
?>
