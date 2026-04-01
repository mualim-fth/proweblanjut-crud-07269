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
$conn->query("DELETE FROM barang WHERE id_barang='$id'");

header("Location: index.php");
?>