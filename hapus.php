<?php
session_start();

// cek apakah user sudah login
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

include 'config/koneksi.php';

$id = $_GET['id'];
$conn->query("DELETE FROM barang WHERE id_barang='$id'");

header("Location: index.php");
?>
