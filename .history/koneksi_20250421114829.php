<?php
$host = "localhost";      // atau bisa pakai 127.0.0.1
$user = "root";           // default user XAMPP
$pass = "";               // default password kosong (kecuali kamu ubah sendiri)
$db   = "todolist";       // nama database kamu

$koneksi = mysqli_connect($host, $user, $pass, $db);

// Cek koneksi
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
