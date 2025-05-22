<?php
include "koneksi.php";

session_start();

$email = $_POST['email'];
$password = $_POST['password'];

// Amankan input
$email = mysqli_real_escape_string($koneksi, $email);
$password = mysqli_real_escape_string($koneksi, $password);

// Cek data di database
$query = "SELECT * FROM users WHERE email='$email' AND password='$password'";
$result = mysqli_query($koneksi, $query);

if(mysqli_num_rows($result) > 0){
    $_SESSION['login'] = true;
    $_SESSION['email'] = $email;
    header("Location: dashboard.php"); // Ganti sesuai halaman dashboard kamu
} else {
    header("Location: login.php?wrong=1");
}
?>
