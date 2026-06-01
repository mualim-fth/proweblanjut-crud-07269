<?php
// 1. Mengatur header HTTP (Sesuai instruksi RTM)
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

// 2. Memanggil koneksi database
require_once '../config/koneksi.php';

// 3. Menerima data JSON dari body request (misal dari Postman)
$data = json_decode(file_get_contents("php://input"));

// 4. Memastikan input data tidak ada yang kosong
if (!empty($data->id_barang) && !empty($data->nama_barang) && isset($data->jumlah) && isset($data->harga) && !empty($data->tanggal_masuk)) {
    
    try {
        // 5. Menyiapkan kueri menggunakan Prepared Statement
        $query = "INSERT INTO barang (id_barang, nama_barang, jumlah, harga, tanggal_masuk) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        
        // 6. Mengeksekusi kueri dengan data dari JSON
        if ($stmt->execute([$data->id_barang, $data->nama_barang, $data->jumlah, $data->harga, $data->tanggal_masuk])) {
            // Respons Sukses (201 Created)
            http_response_code(201); 
            echo json_encode(["status" => "sukses", "pesan" => "Data barang berhasil ditambahkan."]);
        } else {
            // Respons Gagal Eksekusi (503 Service Unavailable)
            http_response_code(503); 
            echo json_encode(["status" => "gagal", "pesan" => "Gagal menambahkan data barang."]);
        }
    } catch (PDOException $e) {
        // Respons Error Database (Misal: id_barang duplikat)
        http_response_code(500);
        echo json_encode(["status" => "gagal", "pesan" => "Error database: " . $e->getMessage()]);
    }
    
} else {
    // Respons Gagal karena input tidak lengkap (400 Bad Request)
    http_response_code(400);
    echo json_encode(["status" => "gagal", "pesan" => "Data tidak lengkap. Pastikan id_barang, nama_barang, jumlah, harga, dan tanggal_masuk terisi."]);
}
?>