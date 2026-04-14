<?php
session_start();

// Jika session kosong, coba cek cookie
if (!isset($_SESSION["username"]) && isset($_COOKIE["user_login"])) {
    $_SESSION["username"] = $_COOKIE["user_login"];
}

// Jika setelah cek cookie tetap kosong, berarti belum login
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

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
        <div class="sidebar-top">
            <h2>SiInventaris</h2>
            <ul>
                <li><a href="#">Dashboard</a></li>
                <li><a href="index.php" class="active">Data Barang</a></li>
            </ul>
        </div>
        
        <div class="sidebar-bottom">
            <ul>
                <li>
                    <a href="logout.php" class="btn-logout" onclick="return confirm('Apakah Anda yakin ingin keluar?')">
                        Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>

        <div class="main-content">
            <div class="page-header">
                <h2>Data Inventaris Barang</h2>
                <!-- NAMA USER YG MASUK -->
                <div style="float:right;">
                    Selamat datang, <b><?= htmlspecialchars($_SESSION["username"]); ?></b>
                </div>

                <div style="clear: both;"></div>
                
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
                            <th>Gambar</th>
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
                            <td><?= htmlspecialchars($row['id_barang']); ?></td>
                            <td><?= htmlspecialchars($row['nama_barang']); ?></td>
                            <td><?= htmlspecialchars($row['jumlah']); ?></td>
                            <td>Rp. <?= number_format($row['harga'], 0, ',', '.'); ?></td>
                            <td><?= htmlspecialchars($row['tanggal_masuk']); ?></td>
                            <td>
                                <?php if (!empty($row['gambar'])): ?>
                                    <img src="uploads/<?= htmlspecialchars($row['gambar']); ?>" 
                                        alt="<?= htmlspecialchars($row['nama_barang']); ?>"
                                        style="max-height: 60px; border-radius: 4px;">
                                <?php else: ?>
                                    <span>-</span>
                                <?php endif; ?>
                            </td>
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