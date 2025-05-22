<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php");
    exit();
}
$email = $_SESSION['email'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css"> <!-- Link ke CSS -->
</head>

<body>

    <!-- Sidebar -->
    <?php
    $activePage = 'dashboard';
    include 'includes/sidebar.php';
    ?>

    <div class="main-content">
        <!-- Topbar -->
        <?php
        $pageTitle = "Dashboard";
        include 'includes/topbar.php';
        ?>

        <div class="card card-welcome mb-4">
            <h2 class="fw-bold mb-3">Selamat Datang, <?= explode('@', $email)[0] ?>!</h2>
            <p>Email Anda: <strong><?= htmlspecialchars($email) ?></strong></p>
            <p>Pilih menu <strong>Todo List</strong> di sidebar untuk mulai mengelola tugas-tugasmu.</p>
        </div>
    </div>

</body>

</html>