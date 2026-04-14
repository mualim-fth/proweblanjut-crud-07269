<?php
session_start();
include "config/koneksi.php";

// Cek jika sudah ada Session atau Cookie
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

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if (password_verify($password, trim($user["password"]))) {
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["username"] = $user["username"];

            if (isset($_POST["remember"])) {
                setcookie("user_login", $user["username"], time() + (86400 * 7), "/");
            }

            header("Location: index.php");
            exit();
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SiInventaris</title>
    <link rel="stylesheet" href="assets/style_login.css">
</head>
<body>

<div class="login-wrapper">
    <div class="login-box">
        <h2>Login</h2>
        <p>Masuk untuk mengelola inventaris</p>

        <?php if (isset($error)): ?>
            <div class="alert alert-error"><?= $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="input-group">
                <label>Username</label>
                <input type="text" name="username" placeholder="Masukkan username" required>
            </div>
            <div class="input-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Masukkan password" required>
            </div>
            <div class="input-group" style="display: flex; flex-direction: row; align-items: center; gap: 8px; margin-bottom: 15px;">
                <input type="checkbox" name="remember" id="remember" style="width: auto; margin: 0; cursor: pointer;">
                <label for="remember" style="margin: 0; font-weight: normal; cursor: pointer; line-height: 1;">Remember Me</label>
            </div>

            <button type="submit" class="btn-auth">Masuk Sekarang</button>
            
            <div class="auth-footer">
                Belum punya akun? <a href="register.php">Daftar</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>