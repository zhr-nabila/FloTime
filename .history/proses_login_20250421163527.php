<?php
session_start();
include "koneksi.php";

// Ambil data dari form
$username = $_POST['email'];
$password = $_POST['password'];

// Cek ke database
$query = mysqli_query($koneksi, "SELECT * FROM users WHERE email='$username' AND password='$password'");
$cek = mysqli_num_rows($query);

// Jika cocok
if ($cek > 0) {
    $_SESSION['username'] = $username;
    $_SESSION['status'] = "login";
    header("Location: dashboard.php");
} else {
    // Balik ke login dengan notifikasi gagal
    header("Location: login.php?wrong=true");
}
?>
