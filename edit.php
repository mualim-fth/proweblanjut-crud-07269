<?php
include "config/koneksi.php";

$id = $_GET['id'];

$data = $conn->query("SELECT * FROM barang WHERE id_barang = '$id'")->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_barang     = $_POST['id_barang'];
    $nama_barang   = $_POST['nama_barang'];
    $jumlah        = $_POST['jumlah'];
    $harga         = $_POST['harga'];
    $tanggal_masuk = $_POST['tanggal_masuk'];

    $conn->query("UPDATE barang SET 
                    nama_barang   = '$nama_barang', 
                    jumlah        = '$jumlah', 
                    harga         = '$harga', 
                    tanggal_masuk = '$tanggal_masuk' 
                WHERE id_barang = '$id_barang'");

    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    <div class="container">
        <div class="sidebar">
            <h2 class="logo">InveNTariS</h2>
            <ul>
                <li><a href="#">Dashboard</a></li>
                <li><a href="index.php" class="active">Data Barang</a></li>
            </ul>
        </div>

        <div class="main-content">
            <div class="page-header">
                <h2>Edit Barang</h2>
                <div class="breadcrumb">
                    <a href="index.php">Home</a> /
                    <a href="index.php">Data Barang</a> /
                    <span>Edit Barang</span>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3>Form Edit Barang</h3>
                    <a href="index.php" class="btn-secondary">Kembali</a>
                </div>

                <div class="card-body">
                    <form method="POST" class="form-vertical">
                        <div class="form-row">
                            <div class="form-group">
                                <label>ID Barang</label>
                                <input type="text" name="id_barang" value="<?= $data['id_barang']; ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label>Nama Barang</label>
                                <input type="text" name="nama_barang" value="<?= $data['nama_barang']; ?>" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Jumlah</label>
                                <input type="number" name="jumlah" value="<?= $data['jumlah']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Harga</label>
                                <input type="number" name="harga" value="<?= $data['harga']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Tanggal Masuk</label>
                                <input type="date" name="tanggal_masuk" value="<?= $data['tanggal_masuk']; ?>" required>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="reset" class="btn-secondary">Reset</button>
                            <button type="submit" class="btn-primary">Update Barang</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
