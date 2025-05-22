
<?php
session_start();
require 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Ambil data pengguna dari database
$stmt = $conn->prepare("SELECT name, photo FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($name, $photo);
$stmt->fetch();
$stmt->close();
?>

<!-- Tampilkan data -->
<div class="profile">
    <h2>Edit Profil</h2>
    <form method="POST" enctype="multipart/form-data" action="update_profile.php">
        <label for="name">Nama:</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($name) ?>" required>
        
        <label for="photo">Foto Profil:</label>
        <input type="file" name="photo" id="photo">
        <?php if ($photo): ?>
            <br><img src="uploads/<?= htmlspecialchars($photo) ?>" alt="Profile Photo" width="100" />
        <?php endif; ?>
        
        <button type="submit">Simpan Perubahan</button>
    </form>
</div>

