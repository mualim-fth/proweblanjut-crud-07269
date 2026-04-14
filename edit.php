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

$id = $_GET['id'];

// === PREPARED STATEMENT UNTUK SELECT ===
$stmt = $conn->prepare("SELECT * FROM barang WHERE id_barang = ?");
$stmt->execute([$id]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_barang     = trim($_POST['id_barang']);
    $nama_barang   = trim($_POST['nama_barang']);
    $jumlah        = $_POST['jumlah'];
    $harga         = $_POST['harga'];
    $tanggal_masuk = $_POST['tanggal_masuk'];

    // === VALIDASI SERVER-SIDE ===
    if (empty($nama_barang)) $errors[] = "Nama Barang tidak boleh kosong.";
    if (!is_numeric($jumlah) || $jumlah < 0) $errors[] = "Jumlah harus berupa angka.";
    if (!is_numeric($harga)  || $harga < 0)  $errors[] = "Harga harus berupa angka.";
    if (empty($tanggal_masuk)) $errors[] = "Tanggal Masuk tidak boleh kosong.";

    // === VALIDASI & UPLOAD GAMBAR ===
    $nama_file_gambar = $data['gambar']; // default: gambar lama

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
            // Hapus gambar lama jika ada
            if (!empty($data['gambar']) && file_exists('uploads/' . $data['gambar'])) {
                unlink('uploads/' . $data['gambar']);
            }

            $nama_file_gambar = uniqid() . '_' . basename($file_name);
            $upload_path = 'uploads/' . $nama_file_gambar;

            if (!move_uploaded_file($file_tmp, $upload_path)) {
                $errors[] = "Gagal mengupload gambar.";
                $nama_file_gambar = $data['gambar']; // kembalikan ke gambar lama
            }
        }
    }

    // === UPDATE DATABASE (hanya jika tidak ada error) ===
    if (empty($errors)) {
        $stmt = $conn->prepare("UPDATE barang SET 
                                    nama_barang   = ?, 
                                    jumlah        = ?, 
                                    harga         = ?, 
                                    tanggal_masuk = ?,
                                    gambar        = ?
                                WHERE id_barang = ?");
        $stmt->execute([$nama_barang, $jumlah, $harga, $tanggal_masuk, $nama_file_gambar, $id_barang]);

        header("Location: index.php");
        exit();
    }

    // Update $data dengan nilai POST supaya form tidak kosong saat error
    $data['nama_barang']   = $nama_barang;
    $data['jumlah']        = $jumlah;
    $data['harga']         = $harga;
    $data['tanggal_masuk'] = $tanggal_masuk;
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
                                        <img src="uploads/<?= htmlspecialchars($data['gambar']); ?>" 
                                            alt="Gambar saat ini" 
                                            style="max-height: 120px; border-radius: 6px;">
                                        <p><small>Gambar saat ini. Upload baru untuk mengganti.</small></p>
                                    </div>
                                <?php endif; ?>
                                <input type="file" name="gambar" accept="image/jpg, image/jpeg, image/png">
                                <small>Format: JPG/PNG, maks. 2 MB (kosongkan jika tidak ingin mengganti)</small>
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