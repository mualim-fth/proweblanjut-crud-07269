<?php
session_start();
include "config/koneksi.php";

// ini PROTEKSI, jika sudah login (via session atau cookie), lempar ke index.php
if (isset($_SESSION["username"])) {
    header("Location: index.php");
    exit();
} elseif (isset($_COOKIE["user_login"])) {
    $_SESSION["username"] = $_COOKIE["user_login"];
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    // Cek duplikasi username
    $cek = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $cek->execute([$username]);
    
    if ($cek->rowCount() > 0) {
        $error = "Username '$username' sudah terdaftar!";
    } else {
        // Hash password
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // Insert ke database
        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        
        if ($stmt->execute([$username, $password_hash])) {
            $success = "Registrasi Berhasil! Silakan login.";
        } else {
            $error = "Terjadi kesalahan saat mendaftar.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - SiInventaris</title>
    <link rel="stylesheet" href="assets/style_login.css">
</head>
<body>

<div class="login-wrapper">
    <div class="login-box">
        <h2>Daftar Akun</h2>
        <p>Lengkapi data untuk membuat akun baru</p>

        <?php if (isset($error)): ?>
            <div class="alert alert-error"><?= $error; ?></div>
        <?php endif; ?>

        <?php if (isset($success)): ?>
            <div class="alert alert-success">
                <?= $success; ?> <br>
                <a href="login.php">Klik untuk Login</a>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="input-group">
                <label>Username</label>
                <input type="text" name="username" placeholder="Masukkan username baru" required>
            </div>
            <div class="input-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Buat password aman" required>
            </div>
            
            <button type="submit" class="btn-auth">Daftar Sekarang</button>
            
            <div class="auth-footer">
                Sudah punya akun? <a href="login.php">Login di sini</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>