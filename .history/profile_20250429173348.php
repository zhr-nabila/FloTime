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
<form method="POST" enctype="multipart/form-data" action="update_profile.php">
    <label>Email: <?= htmlspecialchars($user['email']) ?></label><br><br>

    <label>Nama:</label>
    <input type="text" name="name" value="<?= htmlspecialchars($user['name'] ?? '') ?>" required><br><br>

    <label>Ganti Foto Profil:</label>
    <input type="file" name="photo"><br><br>

    <?php if (!empty($user['photo'])): ?>
        <img src="uploads/<?= htmlspecialchars($user['photo']) ?>" alt="Foto Profil" width="100"><br><br>
    <?php endif; ?>

    <button type="submit" name="update_profile">Simpan</button>
</form>
