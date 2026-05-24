<?php
session_start();

// Proteksi Autentikasi (Sesuai fungsi sebelumnya)
if (!isset($_SESSION["username"]) && isset($_COOKIE["user_login"])) {
    $_SESSION["username"] = $_COOKIE["user_login"];
}

if (!isset($_SESSION["username"])) {
    // Arahkan kembali ke file login asli di root folder
    header("Location: ../login.php");
    exit();
}

// Panggil konfigurasi dan controller
require_once '../config/koneksi.php';
require_once '../app/controllers/BarangController.php';

// Inisiasi Controller
$controller = new BarangController($conn);

// Baca parameter action dari URL (default ke 'index' jika kosong)
$action = $_GET['action'] ?? 'index';

// Router sederhana (Switch Case)
switch ($action) {
    case 'create':
        $controller->create();
        break;
    case 'store':
        $controller->store();
        break;
    case 'edit':
        $id = $_GET['id'] ?? '';
        $controller->edit($id);
        break;
    case 'update':
        $id = $_GET['id'] ?? '';
        $controller->update($id);
        break;
    case 'delete':
        $id = $_GET['id'] ?? '';
        $controller->destroy($id);
        break;
    case 'logout':
        header("Location: ../logout.php");
        exit();
    default:
        $controller->index();
        break;
}
?>