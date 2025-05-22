<?php
session_start();
include "koneksi.php";

// Ambil data
$email = $_POST['email'];
$password = $_POST['password'];

$query = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

if(mysqli_num_rows($result) > 0){
    $_SESSION['email'] = $data['email'];
    $_SESSION['status'] = "login";$_SESSION['user_id'] = $data['id']; // <- Asumsinya nama kolomnya "id"
    $_SESSION['user_id'] = $user_id;

    if (isset($_POST['remember'])) {
        setcookie("email", $email, time() + (86400 * 7), "/"); // simpan 7 hari
    }

    header("Location: dashboard.php");
} else {
    header("Location: login.php?wrong=true");
}
?>
