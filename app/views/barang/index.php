<!DOCTYPE html>
<html>
<head>
    <title>Sistem Manajemen Inventaris</title>
    <link rel="stylesheet" href="../assets/style.css">
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
                        <a href="index.php?action=logout" class="btn-logout" onclick="return confirm('Apakah Anda yakin ingin keluar?')">Logout</a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="main-content">
            <div class="page-header">
                <h2>Data Inventaris Barang</h2>
                <div style="float:right;">
                    Selamat datang, <b><?= htmlspecialchars($_SESSION["username"]); ?></b>
                </div>
                <div style="clear: both;"></div>
                <div class="breadcrumb">
                    <a href="index.php">Home</a> / <span>Data Barang</span>
                </div>
            </div>

            <div class="card">
                <a href="index.php?action=create" class="btn">Tambah Barang</a>

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
                                    <img src="../uploads/<?= htmlspecialchars($row['gambar']); ?>" alt="Gambar" style="max-height: 60px; border-radius: 4px;">
                                <?php else: ?>
                                    <span>-</span>
                                <?php endif; ?>
                            </td>
                            <td class="action">
                                <a href="index.php?action=edit&id=<?= $row['id_barang']; ?>" class="edit">Edit</a>
                                <a href="index.php?action=delete&id=<?= $row['id_barang']; ?>" class="delete" onclick="return confirm('Apakah yakin ingin menghapus data ini?')">Hapus</a>
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