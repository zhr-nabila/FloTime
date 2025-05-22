<?php
session_start();
require_once 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Amankan input dari SQL injection
    $username = $conn->real_escape_string($username);

    // Sesuaikan query dengan struktur tabel 'user' Anda (tanpa kolom 'id')
    $sql = "SELECT username, password FROM user WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        // Verifikasi password
        if ($password == $row['password']) { // Sementara tanpa hashing
            // Simpan username ke dalam sesi (karena tidak ada 'id')
            $_SESSION['username'] = $row['username'];
            header("Location: dashboard.php"); // Redirect ke halaman dashboard setelah login berhasil
            exit();
        } else {
            header("Location: login.php?error=1"); // Password salah
            exit();
        }
    } else {
        header("Location: login.php?error=1"); // Username tidak ditemukan
        exit();
    }

    $conn->close();
} else {
    // Jika bukan metode POST, redirect kembali ke halaman login
    header("Location: login.php");
    exit();
}
?>