<?php
session_start();
require 'koneksi.php';

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$email = $_SESSION['email'];

// Ambil data user
$query = $conn->query("SELECT * FROM users WHERE id = $user_id");
$user = $query->fetch_assoc();
?>

<h2>Edit Profil</h2>
<form action="update_profile.php" method="POST" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="name">Nama Lengkap:</label>
        <input type="text" class="form-control" name="name" id="name" required>
    </div>
    <div class="mb-3">
        <label for="photo">Foto Profil:</label>
        <input type="file" class="form-control" name="photo" id="photo" accept="image/*">
    </div>
    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
</form>

