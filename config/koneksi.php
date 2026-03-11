<?php
$host   = "localhost";
$user   = "root";
$pass   = "";
$dbname = "db_inventaris";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}
?>