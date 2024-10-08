<?php
require_once 'database.php';
require_once 'barang.php';
require_once 'gudang.php';


$database = new Database();
$db = $database->getConnection();


$gudang = new Gudang($db);
$stmt = $gudang->read(); 

$gudang_options = "";
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $gudang_options .= "<option value='{$row['id']}'>{$row['name']}</option>";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Memasukkan data barang
    $barang = new Barang($db);
    $barang->name = $_POST['name'];
    $barang->quantity = $_POST['quantity'];
    $barang->gudang_id = $_POST['gudang_id'];

    if ($barang->create()) {
        header("Location: index.php"); // Redirect ke daftar barang
        exit;
    } else {
        echo "Gagal menambahkan barang.";
    }
}
?>

<div class="container mt-5">
    <h1 class="mb-4 text-center">Tambah Barang</h1>
    <form action="view-create-barang.php" method="POST">
        <div class="mb-3">
            <label for="name" class="form-label">Nama Barang:</label>
            <input type="text" class="form-control" name="name" id="name" required>
        </div>
        <div class="mb-3">
            <label for="quantity" class="form-label">Jumlah:</label>
            <input type="number" class="form-control" name="quantity" id="quantity" required>
        </div>
        <div class="mb-3">
            <label for="gudang_id" class="form-label">Pilih Gudang:</label>
            <select class="form-control" name="gudang_id" id="gudang_id" required>
                <option value="">-- Pilih Gudang --</option>
                <?php echo $gudang_options; ?>
            </select>
        </div>
        <input type="submit" class="btn btn-success w-100" value="Tambah Barang">
    </form>
    <br>
    <a href="index.php" class="btn btn-secondary">Kembali ke Daftar Barang</a>
</div>

<?php
$content = ob_get_clean();
include 'layout.php';
?>