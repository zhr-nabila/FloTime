<?php
session_start();
include 'koneksi.php';

// Cek login
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

$email = $_SESSION['email'];
$activePage = 'profile'; // untuk sidebar active class

// Ambil data user
$query = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
$user = mysqli_fetch_assoc($query);

// Proses update profil
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_name = mysqli_real_escape_string($conn, $_POST['name']);
    $new_email = mysqli_real_escape_string($conn, $_POST['email']);

    $update = mysqli_query($conn, "UPDATE users SET name='$new_name', email='$new_email' WHERE email='$email'");

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
    <!-- Bisa juga tambahkan bootstrap di sini kalau kamu pakai -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <!-- sidebar -->
    <?php
    $activePage = 'todo';
    include 'includes/sidebar.php';
    ?>

    <div class="main-content">
        <!-- topbar -->
        <?php
        $pageTitle = "Todo List";
        include 'includes/topbar.php';
        ?>

        <div class="content p-4">
            <h2 class="mb-4">Edit Profil</h2>

            <form method="POST" class="form-profile">
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

                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </form>
        </div>
    

    </div>
</body>
</html>
