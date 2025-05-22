<?php
session_start();
include 'koneksi.php';

// Cek login
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

$email = $_SESSION['email'];
$activePage = 'profile';

// Ambil data user
$query = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
$user = mysqli_fetch_assoc($query);

// Cek apakah pengguna baru (nama kosong)
$isNewUser = empty(trim($user['name']));
$avatarText = strtoupper(substr($user['name'] ?: $email, 0, 1));

// Proses update profil
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_name = mysqli_real_escape_string($conn, $_POST['name']);
    $new_email = mysqli_real_escape_string($conn, $_POST['email']);

    // Cek apakah ingin hapus avatar
    $removeAvatar = isset($_POST['remove_avatar']);
    $avatarPath = $removeAvatar ? '' : $user['avatar'];

    // Cek apakah ada upload foto
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        if (in_array($_FILES['avatar']['type'], $allowedTypes)) {
            $uploadDir = 'uploads/avatars/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
            $filename = uniqid() . '_' . basename($_FILES['avatar']['name']);
            $uploadPath = $uploadDir . $filename;

            if (move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadPath)) {
                $avatarPath = $uploadPath;
            }
        }
    }

    $update = mysqli_query($conn, "UPDATE users SET name='$new_name', email='$new_email', avatar='$avatarPath' WHERE email='$email'");

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
    <link rel="stylesheet" href="css/profile.css">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .avatar-preview {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: var(--c8);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
            font-weight: bold;
        }
    </style>
</head>
<body>
<?php include 'includes/sidebar.php'; ?>
<div class="main-content">
    <?php $pageTitle = "Edit Profil"; include 'includes/topbar.php'; ?>
    <div class="content p-4">
        <h2 class="mb-4">Edit Profil</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Avatar Saat Ini</label><br>
                <?php if (!empty($user['avatar'])): ?>
                    <img src="<?= $user['avatar'] ?>" alt="Avatar" class="img-thumbnail" style="width: 100px;">
                <?php else: ?>
                    <div class="avatar-preview"><?= $avatarText ?></div>
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <label for="avatar" class="form-label">Ubah Avatar</label>
                <input type="file" name="avatar" id="avatar" class="form-control">
            </div>
            <div class="form-check mb-3">
                <input type="checkbox" name="remove_avatar" id="remove_avatar" class="form-check-input">
                <label for="remove_avatar" class="form-check-label">Hapus Avatar</label>
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">Nama Lengkap</label>
                <input type="text" name="name" id="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
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
<?php
session_start();
include 'koneksi.php';

// Cek login
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

$email = $_SESSION['email'];
$activePage = 'profile';

// Ambil data user
$query = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
$user = mysqli_fetch_assoc($query);

// Cek apakah pengguna baru (nama kosong)
$isNewUser = empty(trim($user['name']));
$avatarText = strtoupper(substr($user['name'] ?: $email, 0, 1));

// Proses update profil
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_name = mysqli_real_escape_string($conn, $_POST['name']);
    $new_email = mysqli_real_escape_string($conn, $_POST['email']);

    // Cek apakah ingin hapus avatar
    $removeAvatar = isset($_POST['remove_avatar']);
    $avatarPath = $removeAvatar ? '' : $user['avatar'];

    // Cek apakah ada upload foto
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        if (in_array($_FILES['avatar']['type'], $allowedTypes)) {
            $uploadDir = 'uploads/avatars/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
            $filename = uniqid() . '_' . basename($_FILES['avatar']['name']);
            $uploadPath = $uploadDir . $filename;

            if (move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadPath)) {
                $avatarPath = $uploadPath;
            }
        }
    }

    $update = mysqli_query($conn, "UPDATE users SET name='$new_name', email='$new_email', avatar='$avatarPath' WHERE email='$email'");

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
    <link rel="stylesheet" href="css/profile.css">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .avatar-preview {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: var(--c8);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
            font-weight: bold;
        }
    </style>
</head>
<body>
<?php include 'includes/sidebar.php'; ?>
<div class="main-content">
    <?php $pageTitle = "Edit Profil"; include 'includes/topbar.php'; ?>
    <div class="content p-4">
        <h2 class="mb-4">Edit Profil</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Avatar Saat Ini</label><br>
                <?php if (!empty($user['avatar'])): ?>
                    <img src="<?= $user['avatar'] ?>" alt="Avatar" class="img-thumbnail" style="width: 100px;">
                <?php else: ?>
                    <div class="avatar-preview"><?= $avatarText ?></div>
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <label for="avatar" class="form-label">Ubah Avatar</label>
                <input type="file" name="avatar" id="avatar" class="form-control">
            </div>
            <div class="form-check mb-3">
                <input type="checkbox" name="remove_avatar" id="remove_avatar" class="form-check-input">
                <label for="remove_avatar" class="form-check-label">Hapus Avatar</label>
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">Nama Lengkap</label>
                <input type="text" name="name" id="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
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
