<!DOCTYPE html>
<html>
<head>
    <title>Tambah Barang</title>
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
                <h2>Tambah Barang</h2>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3>Form Tambah Barang</h3>
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

                    <form action="index.php?action=store" method="POST" enctype="multipart/form-data" class="form-vertical" novalidate>
                        <div class="form-row">
                            <div class="form-group">
                                <label>ID Barang</label>
                                <input type="text" name="id_barang" value="<?= htmlspecialchars($old['id_barang'] ?? ''); ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Nama Barang</label>
                                <input type="text" name="nama_barang" value="<?= htmlspecialchars($old['nama_barang'] ?? ''); ?>" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Jumlah</label>
                                <input type="number" name="jumlah" value="<?= htmlspecialchars($old['jumlah'] ?? ''); ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Harga</label>
                                <input type="number" name="harga" value="<?= htmlspecialchars($old['harga'] ?? ''); ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Tanggal Masuk</label>
                                <input type="date" name="tanggal_masuk" value="<?= htmlspecialchars($old['tanggal_masuk'] ?? ''); ?>" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Gambar Barang</label>
                                <input type="file" name="gambar" accept="image/jpg, image/jpeg, image/png">
                                <small>Format: JPG/PNG, maks. 2 MB (opsional)</small>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="reset" class="btn-secondary">Reset</button>
                            <button type="submit" class="btn-primary">Simpan Barang</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>