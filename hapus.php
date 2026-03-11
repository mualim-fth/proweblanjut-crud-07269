<?php
include 'config/koneksi.php';

$id = $_GET['id'];
$conn->query("DELETE FROM barang WHERE id_barang='$id'");

header("Location: index.php");
?>