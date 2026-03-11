<?php
include "config/koneksi.php";

$barang = $conn->query("SELECT * FROM barang")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sistem Manajemen Inventaris</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    <div class="container">
        <div class="sidebar">
            <h2>InveNTariS</h2>
            <ul>
                <li><a href="#">Dashboard</a></li>
                <li><a href="index.php" class="active">Data Barang</a></li>
            </ul>
        </div>

        <div class="main-content">
            <div class="page-header">
                <h2>Data Inventaris Barang</h2>
                <div class="breadcrumb">
                    <a href="index.php">Home</a> /
                    <span>Data Barang</span>
                </div>
            </div>

            <div class="card">
                <a href="tambah.php" class="btn">Tambah Barang</a>

                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>ID Barang</th>
                            <th>Nama Barang</th>
                            <th>Jumlah</th>
                            <th>Harga</th>
                            <th>Tanggal Masuk</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        foreach($barang as $row): 
                        ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $row['id_barang']; ?></td>
                            <td><?= $row['nama_barang']; ?></td>
                            <td><?= $row['jumlah']; ?></td>
                            <td>Rp. <?= number_format($row['harga'], 0, ',', '.'); ?></td>
                            <td><?= $row['tanggal_masuk']; ?></td>
                            <td class="action">
                                <a href="edit.php?id=<?= $row['id_barang']; ?>" class="edit">Edit</a>
                                <a href="hapus.php?id=<?= $row['id_barang']; ?>" class="delete" onclick="return confirm('Apakah yakin ingin menghapus data ini?')">Hapus</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
