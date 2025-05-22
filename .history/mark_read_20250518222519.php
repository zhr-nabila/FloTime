<?php
session_start();
include 'koneksi.php';

$email = $_SESSION['email'] ?? null;

if (!$email) {
    exit;
}

mysqli_query($conn, "UPDATE notifications SET is_read = 1 WHERE email = '$email'");
?>
