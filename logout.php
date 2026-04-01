<?php
session_start();

// hapus semua data session
session_unset();
session_destroy();

// hapus Cookie (set waktu ke masa lalu)
if (isset($_COOKIE['user_login'])) {
    setcookie("user_login", "", time() - 3600, "/");
}

// arahkan ke login
header("Location: login.php");
exit();
?>