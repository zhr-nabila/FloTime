<?php
session_start();
include "koneksi.php";

// Debug: Tampilkan input
var_dump($_POST);
echo "<br>";

$email = $_POST['email'];
$password = $_POST['password'];

// Debug: Tampilkan query
$query = "SELECT id, email, password FROM users WHERE email = '$email'";
echo "Query: $query <br>";
$result = mysqli_query($koneksi, $query);

if (!$result) {
    die("Query error: " . mysqli_error($koneksi));
}

$user = mysqli_fetch_assoc($result);

// Debug: Tampilkan data user
echo "Data user dari DB: ";
var_dump($user);
echo "<br>";

if ($user) {
    // Debug: Bandingkan password
    echo "Password input: $password <br>";
    echo "Password DB: " . $user['password'] . "<br>";
    echo "Password verify result: " . password_verify($password, $user['password']) . "<br>";
    
    if (password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['status'] = "login";
        header("Location: dashboard.php");
        exit();
    }
}

// Jika gagal
header("Location: login.php?wrong=true");
exit();
?>