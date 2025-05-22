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

// Proses update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_name = mysqli_real_escape_string($conn, $_POST['name']);
    $new_email = mysqli_real_escape_string($conn, $_POST['email']);
    $photo_name = $user['photo'];

    // Upload foto jika ada
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, $allowed)) {
            $filename = uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['photo']['tmp_name'], 'uploads/' . $filename);
            if ($user['photo'] && file_exists('uploads/' . $user['photo'])) {
                unlink('uploads/' . $user['photo']);
            }
            $photo_name = $filename;
        }
    }

    // Hapus foto jika diminta
    if (isset($_POST['delete_photo']) && $user['photo']) {
        if (file_exists('uploads/' . $user['photo'])) {
            unlink('uploads/' . $user['photo']);
        }
        $photo_name = null;
    }

    $update = mysqli_query($conn, "UPDATE users SET name='$new_name', email='$new_email', photo=" . ($photo_name ? "'$photo_name'" : "NULL") . " WHERE email='$email'");

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
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Profil</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/profile.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

<?php include 'includes/sidebar.php'; ?>

<div class="main-content">
    <?php
    $pageTitle = "Edit Profil";
    include 'includes/topbar.php';
    ?>
    
    <div class="container py-5">
        <div class="card shadow-lg p-4">
            <h3 class="mb-4 text-center">Edit Profil</h3>
            <form method="POST" enctype="multipart/form-data">

                <div class="mb-3 text-center">
                    <?php if ($user['photo']) : ?>
                        <img src="uploads/<?= htmlspecialchars($user['photo']) ?>" alt="Foto Profil" class="rounded-circle shadow" width="150" height="150" style="object-fit: cover;">
                    <?php else : ?>
                        <div class="rounded-circle bg-secondary d-inline-flex align-items-center justify-content-center text-white fs-1" style="width: 150px; height: 150px;">
                            <?= strtoupper(substr($user['name'] ?: $user['email'], 0, 1)) ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label for="photo" class="form-label">Unggah Foto Baru</label>
                    <input type="file" name="photo" id="photo" class="form-control">
                </div>

                <?php if ($user['photo']) : ?>
                    <div class="mb-3 form-check">
                        <input class="form-check-input" type="checkbox" name="delete_photo" id="delete_photo">
                        <label class="form-check-label" for="delete_photo">Hapus Foto Profil</label>
                    </div>
                <?php endif; ?>

                <div class="mb-3">
                    <label for="name" class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" id="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Alamat Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
                </div>

                <?php if (isset($error)) : ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary px-4">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>
