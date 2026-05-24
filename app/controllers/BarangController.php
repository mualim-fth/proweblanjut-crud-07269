<?php
require_once '../app/models/BarangModel.php';

class BarangController {
    private $model;

    public function __construct($db) {
        $this->model = new BarangModel($db);
    }

    // 1. Menampilkan Halaman Utama (Tabel)
    public function index() {
        $barang = $this->model->getAll();
        require '../app/views/barang/index.php';
    }

    // 2. Menampilkan Form Tambah
    public function create() {
        require '../app/views/barang/create.php';
    }

    // 3. Memproses Data Tambah
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $old = $_POST;
            $errors = [];
            
            $data = [
                'id_barang'     => trim($_POST['id_barang']),
                'nama_barang'   => trim($_POST['nama_barang']),
                'jumlah'        => $_POST['jumlah'],
                'harga'         => $_POST['harga'],
                'tanggal_masuk' => $_POST['tanggal_masuk'],
                'gambar'        => null
            ];

            if (empty($data['id_barang'])) $errors[] = "ID Barang tidak boleh kosong.";
            if (empty($data['nama_barang'])) $errors[] = "Nama Barang tidak boleh kosong.";
            if (!is_numeric($data['jumlah']) || $data['jumlah'] < 0) $errors[] = "Jumlah harus berupa angka positif.";
            if (!is_numeric($data['harga']) || $data['harga'] < 0) $errors[] = "Harga harus berupa angka positif.";
            if (empty($data['tanggal_masuk'])) $errors[] = "Tanggal Masuk tidak boleh kosong.";

            if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
                $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
                $max_size = 2 * 1024 * 1024;
                
                if (!in_array($_FILES['gambar']['type'], $allowed_types)) {
                    $errors[] = "Format gambar harus JPG atau PNG.";
                } elseif ($_FILES['gambar']['size'] > $max_size) {
                    $errors[] = "Ukuran gambar maksimal 2 MB.";
                } else {
                    $nama_file = uniqid() . '_' . basename($_FILES['gambar']['name']);
                    // Path upload diubah menjadi ../uploads/ karena dijalankan dari public/index.php
                    if (move_uploaded_file($_FILES['gambar']['tmp_name'], '../uploads/' . $nama_file)) {
                        $data['gambar'] = $nama_file;
                    } else {
                        $errors[] = "Gagal mengupload gambar.";
                    }
                }
            }

            if (empty($errors)) {
                $this->model->save($data);
                header("Location: index.php");
                exit();
            } else {
                require '../app/views/barang/create.php';
            }
        }
    }

    // 4. Menampilkan Form Edit
    public function edit($id) {
        $data = $this->model->getById($id);
        if (!$data) {
            header("Location: index.php");
            exit();
        }
        require '../app/views/barang/edit.php';
    }

    // 5. Memproses Data Edit
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $existing_data = $this->model->getById($id);
            $errors = [];
            
            $data = [
                'nama_barang'   => trim($_POST['nama_barang']),
                'jumlah'        => $_POST['jumlah'],
                'harga'         => $_POST['harga'],
                'tanggal_masuk' => $_POST['tanggal_masuk'],
                'gambar'        => $existing_data['gambar'] // Default gambar lama
            ];

            if (empty($data['nama_barang'])) $errors[] = "Nama Barang tidak boleh kosong.";
            if (!is_numeric($data['jumlah']) || $data['jumlah'] < 0) $errors[] = "Jumlah harus angka.";
            if (!is_numeric($data['harga']) || $data['harga'] < 0) $errors[] = "Harga harus angka.";
            if (empty($data['tanggal_masuk'])) $errors[] = "Tanggal Masuk tidak boleh kosong.";

            if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
                $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
                if (!in_array($_FILES['gambar']['type'], $allowed_types)) {
                    $errors[] = "Format gambar harus JPG atau PNG.";
                } else {
                    if (!empty($existing_data['gambar']) && file_exists('../uploads/' . $existing_data['gambar'])) {
                        unlink('../uploads/' . $existing_data['gambar']);
                    }
                    $nama_file = uniqid() . '_' . basename($_FILES['gambar']['name']);
                    if (move_uploaded_file($_FILES['gambar']['tmp_name'], '../uploads/' . $nama_file)) {
                        $data['gambar'] = $nama_file;
                    }
                }
            }

            if (empty($errors)) {
                $this->model->update($id, $data);
                header("Location: index.php");
                exit();
            } else {
                // Supaya saat error, data form tidak hilang
                $data['id_barang'] = $id;
                require '../app/views/barang/edit.php';
            }
        }
    }

    // 6. Menghapus Data
    public function destroy($id) {
        $existing_data = $this->model->getById($id);
        if ($existing_data) {
            // Hapus gambar fisik
            if (!empty($existing_data['gambar']) && file_exists('../uploads/' . $existing_data['gambar'])) {
                unlink('../uploads/' . $existing_data['gambar']);
            }
            // Hapus dari database
            $this->model->delete($id);
        }
        header("Location: index.php");
        exit();
    }
}
?>