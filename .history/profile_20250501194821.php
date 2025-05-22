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
                <label class="form-label">Foto Saat Ini</label>
                <img src="https://via.placeholder.com/150" alt="Foto Profil" class="photo-user" />
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="hapusFoto" />
                    <label for="hapusFoto">Hapus Foto</label>
                </div>
            </div>

            <!-- KANAN: FORM -->
            <form class="form-profile">
                <div>
                    <label for="nama" class="form-label">Nama</label>
                    <input type="text" id="nama" class="input-profile" placeholder="Masukkan nama anda" />
                </div>
                <div>
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" class="input-profile" placeholder="Masukkan email anda" />
                </div>
                <div>
                    <label for="uploadFoto" class="form-label">Upload Foto Baru</label>
                    <label class="upload-box" for="uploadFoto">
                        Klik atau seret foto ke sini
                        <input type="file" id="uploadFoto" />
                    </label>
                </div>
                <button type="submit" class="btn-primary">Simpan</button>
            </form>
        </div>
    </div>

    </div>
    </div>
</body>

</html>