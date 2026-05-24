<?php

class BarangModel {
    private $conn;

    // Menerima koneksi database dari luar
    public function __construct($db) {
        $this->conn = $db;
    }

    // 1. Mengambil semua data
    public function getAll() {
        $stmt = $this->conn->query("SELECT * FROM barang ORDER BY tanggal_masuk DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 2. Mengambil satu data berdasarkan ID
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM barang WHERE id_barang = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }~

    // 3. Menyimpan data baru
    public function save($data) {
        $stmt = $this->conn->prepare("INSERT INTO barang (id_barang, nama_barang, jumlah, harga, tanggal_masuk, gambar) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['id_barang'],
            $data['nama_barang'],
            $data['jumlah'],
            $data['harga'],
            $data['tanggal_masuk'],
            $data['gambar']
        ]);
    }

    // 4. Mengubah data
    public function update($id, $data) {
        $stmt = $this->conn->prepare("UPDATE barang SET nama_barang = ?, jumlah = ?, harga = ?, tanggal_masuk = ?, gambar = ? WHERE id_barang = ?");
        return $stmt->execute([
            $data['nama_barang'],
            $data['jumlah'],
            $data['harga'],
            $data['tanggal_masuk'],
            $data['gambar'],
            $id
        ]);
    }

    // 5. Menghapus data
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM barang WHERE id_barang = ?");
        return $stmt->execute([$id]);
    }
}
?>