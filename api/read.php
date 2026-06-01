<?php
// 1. Mengatur header agar respons dibaca sebagai JSON
header("Content-Type: application/json; charset=UTF-8");

// 2. Memanggil koneksi database
require_once '../config/koneksi.php';

try {
    // 3. Menyiapkan query menggunakan Prepared Statement
    $stmt = $conn->prepare("SELECT * FROM barang ORDER BY tanggal_masuk DESC");
    $stmt->execute();
    
    // 4. Mengambil semua data dalam bentuk Array Assosiatif
    $barang = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // 5. Mengubah array PHP menjadi format JSON dan menampilkannya
    echo json_encode($barang);
    
} catch (PDOException $e) {
    // Menangani jika terjadi error pada database
    http_response_code(500);
    echo json_encode(["pesan" => "Terjadi kesalahan: " . $e->getMessage()]);
}
?>