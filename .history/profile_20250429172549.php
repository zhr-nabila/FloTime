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
$stmt = $conn->prepare("SELECT name, email, profile_pic FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Update profil
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email_baru = $_POST['email'];
    $profile_pic = $user['profile_pic']; // Default: foto lama

    // Upload foto baru jika ada
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
        $fileTmp = $_FILES['profile_pic']['tmp_name'];
        $fileName = basename($_FILES['profile_pic']['name']);
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png'];

        if (in_array($fileExt, $allowed)) {
            $newFileName = 'user_' . $user_id . '_' . time() . '.' . $fileExt;
            $destination = 'uploads/' . $newFileName;
            if (move_uploaded_file($fileTmp, $destination)) {
                // Hapus file lama jika ada
                if (!empty($user['profile_pic']) && file_exists('uploads/' . $user['profile_pic'])) {
                    unlink('uploads/' . $user['profile_pic']);
                }
                $profile_pic = $newFileName;
            }
        }
    }

    // Update data ke DB
    $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, profile_pic = ? WHERE id = ?");
    $stmt->bind_param("sssi", $name, $email_baru, $profile_pic, $user_id);
    $stmt->execute();
    $stmt->close();

    $_SESSION['email'] = $email_baru; // Update email session
    header("Location: profile.php");
    exit();
}
?>

<?php $activePage = 'profile'; include 'header.php'; include 'sidebar.php'; include 'topbar.php'; ?>

<div class="content p-4">
    <h2>Edit Profil</h2>
    <form method="POST" enctype="multipart/form-data" class="card p-4" style="max-width: 500px;">
        <div class="mb-3">
            <label class="form-label">Nama</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="profile_pic" class="form-label">Foto Profil</label><br>
            <?php if (!empty($user['profile_pic'])): ?>
                <img src="uploads/<?= htmlspecialchars($user['profile_pic']) ?>" alt="Foto Profil" style="width: 100px; border-radius: 50%; margin-bottom: 10px;">
            <?php endif; ?>
            <input type="file" class="form-control" name="profile_pic" accept="image/*">
        </div>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </form>
</div>

<?php include 'footer.php'; ?>
