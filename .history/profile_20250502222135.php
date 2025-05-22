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
    $photo_name = $user['photo']; // default photo lama

    // Upload foto jika ada
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, $allowed)) {
            $filename = uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['photo']['tmp_name'], 'uploads/' . $filename);
            // Hapus file lama
            if ($user['photo'] && file_exists('uploads/' . $user['photo'])) {
                unlink('uploads/' . $user['photo']);
            }
            $photo_name = $filename;
        }
    }

    // Hapus foto jika dipilih
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
        <div class="content">
            <!-- KIRI: FOTO PROFIL -->
            <div class="profile-left">
                <label class="form-label">Profil Kamu</label>
                <?php if ($user['photo']) : ?>
                    <img src="uploads/<?= htmlspecialchars($user['photo']) ?>" alt="Foto Profil" class="photo-user" />
                <?php else : ?>
                    <div class="user-avatar-lg">
                        <?= strtoupper(substr($user['name'] ?: $user['email'], 0, 1)) ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($user['photo']) : ?>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="delete_photo" id="hapusFoto" />
                        <label for="hapusFoto">Hapus Foto</label>
                    </div>
                <?php endif; ?>
            </div>

            <!-- KANAN: FORM -->
            <form method="POST" enctype="multipart/form-data" class="form-profile">
                <?php if (isset($error)) : ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>
                
                <div>
                    <label for="name" class="form-label">Nama</label>
                    <input type="text" name="name" id="name" class="input-profile" value="<?= htmlspecialchars($user['name']) ?>" required />
                </div>
                <div>
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="input-profile" value="<?= htmlspecialchars($user['email']) ?>" required />
                </div>
                <div>
                    <label for="photo" class="form-label">Upload Foto Baru</label>
                    <label class="upload-box" for="photo">
                        Klik atau seret foto ke sini
                        <input type="file" name="photo" id="photo" />
                    </label>
                </div>
                <button type="submit" class="btn-primary">Simpan</button>
            </form>
        </div>
    </div>
</body>

</html>