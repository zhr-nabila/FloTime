<?php
session_start();
include 'koneksi.php';

// Cek login
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

$email = $_SESSION['email'];
$activePage = 'profile'; // untuk sidebar active class

// Ambil data user
$query = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
$user = mysqli_fetch_assoc($query);

// Proses update profil
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_name = mysqli_real_escape_string($conn, $_POST['name']);
    $new_email = mysqli_real_escape_string($conn, $_POST['email']);

    $update = mysqli_query($conn, "UPDATE users SET name='$new_name', email='$new_email' WHERE email='$email'");

    if ($update) {
        $_SESSION['email'] = $new_email;
        header("Location: profile.php");
        exit;
    } else {
        $error = "Gagal memperbarui profil.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profil</title>
    <link rel="stylesheet" href="css/profile.css">
    <link rel="stylesheet" href="css/style.css">
    <!-- Bisa juga tambahkan bootstrap di sini kalau kamu pakai -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- sidebar -->
    <?php
    $activePage = 'todo';
    include 'includes/sidebar.php';
    ?>

    <div class="main-content">
        <!-- topbar -->
        <?php
        $pageTitle = "Todo List";
        include 'includes/topbar.php';
        ?>

    
</body>
</html>
