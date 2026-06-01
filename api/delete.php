<?php
// 1. Mengatur header HTTP
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE, POST");

// 2. Memanggil koneksi database
require_once '../config/koneksi.php';

// 3. Menerima data JSON dari request
$data = json_decode(file_get_contents("php://input"));

// 4. Memastikan ID tidak kosong
if (!empty($data->id_barang)) {
    
    try {
        // 5. Menyiapkan kueri DELETE menggunakan Prepared Statement
        $query = "DELETE FROM barang WHERE id_barang = ?";
        $stmt = $conn->prepare($query);
        
        // 6. Mengeksekusi kueri
        if ($stmt->execute([$data->id_barang])) {
            http_response_code(200);
            echo json_encode(["status" => "sukses", "pesan" => "Data barang berhasil dihapus."]);
        } else {
            http_response_code(503);
            echo json_encode(["status" => "gagal", "pesan" => "Gagal menghapus data barang."]);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["status" => "gagal", "pesan" => "Error database: " . $e->getMessage()]);
    }
    
} else {
    http_response_code(400);
    echo json_encode(["status" => "gagal", "pesan" => "id_barang tidak boleh kosong."]);
}
?>