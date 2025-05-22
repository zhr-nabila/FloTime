<?php
session_start();
require 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Ambil data user dari database
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

$name = $user['name'];
$photo = $user['photo'];

// Cek jika ada foto, maka tampilkan gambar
$photoPath = $photo ? "uploads/" . $photo : "default-avatar.jpg"; // Jika tidak ada foto, tampilkan default avatar
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profil</title>
</head>
<body>
    <h1>Edit Profil</h1>
    <form action="update_profile.php" method="POST" enctype="multipart/form-data">
        <div>
            <label for="name">Nama:</label>
            <input type="text" name="name" id="name" value="<?= htmlspecialchars($name) ?>" required>
        </div>
        <div>
            <label for="photo">Foto Profil:</label>
            <input type="file" name="photo" id="photo">
            <br>
            <!-- Menampilkan foto profil -->
            <img src="<?= $photoPath ?>" alt="Foto Profil" width="100">
        </div>
        <div>
            <button type="submit">Simpan Perubahan</button>
        </div>
    </form>
</body>
</html>
