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

include 'config/koneksi.php';

$id = $_GET['id'];

// Ambil nama gambar sebelum dihapus
$stmt = $conn->prepare("SELECT gambar FROM barang WHERE id_barang = ?");
$stmt->execute([$id]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

// Hapus file gambar dari folder uploads jika ada
if (!empty($data['gambar']) && file_exists('uploads/' . $data['gambar'])) {
    unlink('uploads/' . $data['gambar']);
}

// === PREPARED STATEMENT UNTUK DELETE ===
$stmt = $conn->prepare("DELETE FROM barang WHERE id_barang = ?");
$stmt->execute([$id]);

header("Location: index.php");
exit();
?>