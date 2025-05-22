<?php
session_start();
include "koneksi.php";

$email = mysqli_real_escape_string($koneksi, $_POST['email']);
$password = $_POST['password'];

$stmt = mysqli_prepare($koneksi, "SELECT id, email, password FROM users WHERE email = ?");
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($user = mysqli_fetch_assoc($result)) {
    if (password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['status'] = "login";
        
        // Debug: Tampilkan session
        echo "<pre>Session: ";
        print_r($_SESSION);
        echo "</pre>";
        
        header("Location: dashboard.php");
        exit();
    }
}

// Jika gagal
header("Location: login.php?wrong=true");
exit();
?>