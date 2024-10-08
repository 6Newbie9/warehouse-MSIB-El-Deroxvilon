<?php
require_once 'database.php';
require_once 'gudang.php';
require_once 'barang.php'; // Sertakan kelas Barang

// Koneksi ke database
$database = new Database();
$db = $database->getConnection();

// Membuat objek gudang dan barang
$gudang = new Gudang($db);
$barang = new Barang($db);

// Membaca data gudang
$stmt_gudang = $gudang->read();
$num_gudang = $stmt_gudang->rowCount();

// Membaca data barang
$stmt_barang = $barang->read();
$num_barang = $stmt_barang->rowCount();

ob_start();
?>


<div class="container mt-5">
    <h1 class="mb-4 text-center">Daftar Gudang</h1>

    <a href="view-create.php" class="btn btn-success mb-4">
        <i class="bi bi-plus-lg"></i> Tambah Gudang
    </a>
    <a href="view-create-barang.php" class="btn btn-success mb-4">
        <i class="bi bi-plus-lg"></i> Tambah Barang
    </a>

    <?php
    // Tabel Gudang
    if ($num_gudang > 0) {
        echo "<table class='table table-hover table-bordered'>";
        echo "<thead class='table-dark'>
                <tr>
                    <th>ID</th>
                    <th>Nama Gudang</th>
                    <th>Lokasi</th>
                    <th>Kapasitas</th>
                    <th>Status</th>
                    <th>Jam Buka</th>
                    <th>Jam Tutup</th>
                    <th>Aksi</th>
                </tr>
              </thead>";
        echo "<tbody>";

        while ($row = $stmt_gudang->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            echo "<tr>";
            echo "<td>{$id}</td>";
            echo "<td>{$name}</td>";
            echo "<td>{$location}</td>";
            echo "<td>{$capacity}</td>";
            echo "<td>" . ($status === 'aktif' ? "<span class='badge bg-success'>Aktif</span>" : "<span class='badge bg-secondary'>Tidak Aktif</span>") . "</td>";
            echo "<td>{$opening_hour}</td>";
            echo "<td>{$closing_hour}</td>";
            
            // Bagian aksi untuk Edit, Toggle Status, dan Hapus
            echo "<td class='text-center'>";
            echo "<a href='view-edit.php?id={$id}' class='btn btn-sm btn-warning me-2'>
                    <i class='bi bi-pencil'></i> Edit</a> ";
            echo "<a href='toggle-status.php?id={$id}' class='btn btn-sm btn-info me-2'>" . 
                 ($status === 'aktif' ? "<i class='bi bi-toggle-off'></i> Nonaktifkan" : "<i class='bi bi-toggle-on'></i> Aktifkan") . "</a> ";
            echo "<a href='delete.php?id={$id}' class='btn btn-sm btn-danger' onclick='return confirm(\"Yakin ingin menghapus atau menonaktifkan gudang ini?\")'>
                    <i class='bi bi-trash'></i> Hapus</a>";
            echo "</td>";
            
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
    } else {
        echo "<div class='alert alert-info'>Tidak ada data gudang.</div>";
    }

    // Tabel Barang
    echo "<h2 class='mt-5 mb-4 text-center'>Daftar Barang</h2>";

    if ($num_barang > 0) {
        echo "<table class='table table-hover table-bordered'>";
        echo "<thead class='table-dark'>
                <tr>
                    <th>ID</th>
                    <th>Nama Barang</th>
                    <th>Jumlah</th>
                    <th>ID Gudang</th>
                    <th>Tanggal Dibuat</th>
                    <th>Aksi</th>
                </tr>
            </thead>";
        echo "<tbody>";

        while ($row = $stmt_barang->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            echo "<tr>";
            echo "<td>{$id}</td>";
            echo "<td>{$name}</td>";
            echo "<td>{$quantity}</td>";
            echo "<td>{$gudang_id}</td>";
            echo "<td>{$created_at}</td>";
            
            // Tambahkan tombol Edit dan Delete
            echo "<td class='text-center'>";
            echo "<a href='view-edit-barang.php?id={$id}' class='btn btn-sm btn-warning me-2'>
                    <i class='bi bi-pencil'></i> Edit</a>";
            echo "<a href='delete-barang.php?id={$id}' class='btn btn-sm btn-danger' onclick='return confirm(\"Yakin ingin menghapus barang ini?\")'>
                    <i class='bi bi-trash'></i> Hapus</a>";
            echo "</td>";
            
            echo "</tr>";
        }

        echo "</tbody>";
        echo "</table>";
    } else {
        echo "<div class='alert alert-info'>Tidak ada data barang.</div>";
    }


    // Capture the content for the layout
    $content = ob_get_clean();

    // Include the layout template and pass the content
    include 'layout.php';
?>
