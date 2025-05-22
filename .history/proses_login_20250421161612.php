<?php
session_start();
include "koneksi.php";

$email = $_POST['email'];
$password = $_POST['password'];

// Query dengan prepared statement
$stmt = mysqli_prepare($koneksi, "SELECT id, email, password FROM users WHERE email = ?");
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($user = mysqli_fetch_assoc($result)) {
    if (password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['status'] = "login";
        header("Location: dashboard.php");
        exit();
    }
}

header("Location: login.php?wrong=true");
exit();
?>