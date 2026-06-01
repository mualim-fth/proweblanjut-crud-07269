<?php
// 1. Mengatur header HTTP
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT, POST");

// 2. Memanggil koneksi database
require_once '../config/koneksi.php';

// 3. Menerima data JSON dari request
$data = json_decode(file_get_contents("php://input"));

// 4. Memastikan ID dan data lainnya tidak kosong
if (!empty($data->id_barang) && !empty($data->nama_barang) && isset($data->jumlah) && isset($data->harga) && !empty($data->tanggal_masuk)) {
    
    try {
        // 5. Menyiapkan kueri UPDATE menggunakan Prepared Statement
        $query = "UPDATE barang SET nama_barang = ?, jumlah = ?, harga = ?, tanggal_masuk = ? WHERE id_barang = ?";
        $stmt = $conn->prepare($query);
        
        // 6. Mengeksekusi kueri
        if ($stmt->execute([$data->nama_barang, $data->jumlah, $data->harga, $data->tanggal_masuk, $data->id_barang])) {
            http_response_code(200); // 200 OK
            echo json_encode(["status" => "sukses", "pesan" => "Data barang berhasil diperbarui."]);
        } else {
            http_response_code(503); // 503 Service Unavailable
            echo json_encode(["status" => "gagal", "pesan" => "Gagal memperbarui data barang."]);
        }
    } catch (PDOException $e) {
        http_response_code(500); // 500 Internal Server Error
        echo json_encode(["status" => "gagal", "pesan" => "Error database: " . $e->getMessage()]);
    }
    
} else {
    http_response_code(400); // 400 Bad Request
    echo json_encode(["status" => "gagal", "pesan" => "Data tidak lengkap. Pastikan ID dan semua field terisi."]);
}
?>