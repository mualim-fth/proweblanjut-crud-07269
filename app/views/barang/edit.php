<!DOCTYPE html>
<html>
<head>
    <title>Edit Barang</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <div class="sidebar-top">
                <h2>SiInventaris</h2>
                <ul>
                    <li><a href="index.php" class="active">Data Barang</a></li>
                </ul>
            </div>
        </div>

        <div class="main-content">
            <div class="page-header">
                <h2>Edit Barang</h2>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3>Form Edit Barang</h3>
                    <a href="index.php" class="btn-secondary">Kembali</a>
                </div>

                <div class="card-body">
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-error">
                            <ul>
                                <?php foreach ($errors as $error): ?>
                                    <li><?= htmlspecialchars($error); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form action="index.php?action=update&id=<?= htmlspecialchars($data['id_barang']); ?>" method="POST" enctype="multipart/form-data" class="form-vertical" novalidate>
                        <div class="form-row">
                            <div class="form-group">
                                <label>ID Barang</label>
                                <input type="text" name="id_barang" value="<?= htmlspecialchars($data['id_barang']); ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label>Nama Barang</label>
                                <input type="text" name="nama_barang" value="<?= htmlspecialchars($data['nama_barang']); ?>" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Jumlah</label>
                                <input type="number" name="jumlah" value="<?= htmlspecialchars($data['jumlah']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Harga</label>
                                <input type="number" name="harga" value="<?= htmlspecialchars($data['harga']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Tanggal Masuk</label>
                                <input type="date" name="tanggal_masuk" value="<?= htmlspecialchars($data['tanggal_masuk']); ?>" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Gambar Barang</label>
                                <?php if (!empty($data['gambar'])): ?>
                                    <div style="margin-bottom: 8px;">
                                        <img src="../uploads/<?= htmlspecialchars($data['gambar']); ?>" alt="Gambar saat ini" style="max-height: 120px; border-radius: 6px;">
                                    </div>
                                <?php endif; ?>
                                <input type="file" name="gambar" accept="image/jpg, image/jpeg, image/png">
                                <small>Format: JPG/PNG, maks. 2 MB (kosongkan jika tidak ingin mengganti)</small>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn-primary">Update Barang</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>