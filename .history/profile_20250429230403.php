<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

$email = $_SESSION['email'];
$activePage = 'profile';

// Ambil data user
$query = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
$user = mysqli_fetch_assoc($query);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_name = mysqli_real_escape_string($conn, $_POST['name']);
    $new_email = mysqli_real_escape_string($conn, $_POST['email']);

    // Cek jika ada file gambar diupload
    if (!empty($_FILES['photo']['name'])) {
        $photo_name = basename($_FILES['photo']['name']);
        $target_dir = "uploads/";
        $target_file = $target_dir . time() . '_' . $photo_name;

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
            $update = mysqli_query($conn, "UPDATE users SET name='$new_name', email='$new_email', photo='$target_file' WHERE email='$email'");
        } else {
            $error = "Gagal mengunggah foto.";
        }
    } else {
        $update = mysqli_query($conn, "UPDATE users SET name='$new_name', email='$new_email' WHERE email='$email'");
    }

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <?php
    $activePage = 'profile';
    include 'includes/sidebar.php';
    ?>

    <div class="main-content">
        <?php
        $pageTitle = "Edit Profile";
        include 'includes/topbar.php';
        ?>

        <div class="content p-4">
            <h2 class="mb-4">Edit Profil</h2>

            <form method="POST" enctype="multipart/form-data" class="form-profile">
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" id="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Alamat Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
                </div>

                <div class="mb-3">
                    <label for="photo" class="form-label">Foto Profil</label>
                    <input type="file" name="photo" id="photo" class="form-control">
                    <?php if (!empty($user['photo'])) : ?>
                        <img src="<?= htmlspecialchars($user['photo']) ?>" alt="Foto Profil" width="100" class="mt-2 rounded">
                    <?php endif; ?>
                </div>

                <?php if (isset($error)) : ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>

                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </form>
        </div>
    </div>
</body>
</html>
