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

$errors = [];
$old = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $old = $_POST;

    $id_barang     = trim($_POST['id_barang']);
    $nama_barang   = trim($_POST['nama_barang']);
    $jumlah        = $_POST['jumlah'];
    $harga         = $_POST['harga'];
    $tanggal_masuk = $_POST['tanggal_masuk'];

    // === VALIDASI SERVER-SIDE ===
    if (empty($id_barang))   $errors[] = "ID Barang tidak boleh kosong.";
    if (empty($nama_barang)) $errors[] = "Nama Barang tidak boleh kosong.";
    if (!is_numeric($jumlah) || $jumlah < 0) $errors[] = "Jumlah harus berupa angka.";
    if (!is_numeric($harga)  || $harga < 0)  $errors[] = "Harga harus berupa angka.";
    if (empty($tanggal_masuk)) $errors[] = "Tanggal Masuk tidak boleh kosong.";

    // === VALIDASI & UPLOAD GAMBAR ===
    $nama_file_gambar = null;

    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
        $max_size      = 2 * 1024 * 1024; // 2 MB

        $file_type = $_FILES['gambar']['type'];
        $file_size = $_FILES['gambar']['size'];
        $file_tmp  = $_FILES['gambar']['tmp_name'];
        $file_name = $_FILES['gambar']['name'];

        if (!in_array($file_type, $allowed_types)) {
            $errors[] = "Format gambar harus JPG atau PNG.";
        } elseif ($file_size > $max_size) {
            $errors[] = "Ukuran gambar maksimal 2 MB.";
        } else {
            $nama_file_gambar = uniqid() . '_' . basename($file_name);
            $upload_path = 'uploads/' . $nama_file_gambar;

            if (!move_uploaded_file($file_tmp, $upload_path)) {
                $errors[] = "Gagal mengupload gambar.";
                $nama_file_gambar = null;
            }
        }
    }

    // === SIMPAN KE DATABASE (hanya jika tidak ada error) ===
    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO barang (id_barang, nama_barang, jumlah, harga, tanggal_masuk, gambar) 
                                VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$id_barang, $nama_barang, $jumlah, $harga, $tanggal_masuk, $nama_file_gambar]);

        header("Location: index.php");
        exit();
    }
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
                <h2>Tambah Barang</h2>
                <div class="breadcrumb">
                    <a href="index.php">Home</a> /
                    <a href="index.php">Data Barang</a> /
                    <span>Tambah Barang</span>
                </div>
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

                    <form method="POST" enctype="multipart/form-data" class="form-vertical" novalidate>
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