<?php
if (!isset($conn)) {
    include '../koneksi.php'; // pastikan koneksi tersedia
}
if (!isset($_SESSION)) {
    session_start();
}
$email = $_SESSION['email'] ?? null;

// Cegah error jika belum login
if (!$email) {
    header("Location: login.php");
    exit;
}

// Ambil data user dari database kalau belum ada
if (!isset($user) || !is_array($user)) {
    $result = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
    $user = mysqli_fetch_assoc($result);
}
?>


<div class="sidebar d-flex flex-column">
    <div class="px-4 d-flex align-items-center mb-4">
        <?php if ($user['photo']) : ?>
            <img src="uploads/<?= htmlspecialchars($user['photo']) ?>" alt="Avatar" class="rounded-circle" width="45" height="45">
        <?php else : ?>
            <div class="user-avatar">
                <?= strtoupper(substr($user['name'] ?: $user['email'], 0, 1)) ?>
            </div>
        <?php endif; ?>
        <div class="ms-2">
            <div class="fw-semibold"><?= htmlspecialchars($user['name'] ?: 'User Dashboard') ?></div>
            <div class="small text-white-50"><?= htmlspecialchars($user['email']) ?></div>
        </div>
    </div>
    <nav class="nav flex-column px-3">
        <a class="nav-link <?= ($activePage == 'dashboard') ? 'active' : '' ?>" href="dashboard.php">
            <i class="bi bi-house-door"></i> Dashboard
        </a>
        <a class="nav-link <?= ($activePage == 'todo') ? 'active' : '' ?>" href="todo.php">
            <i class="bi bi-list-task"></i> Todo List
        </a>
        <a class="nav-link <?= ($activePage == 'profile') ? 'active' : '' ?>" href="profile.php">
            <i class="bi bi-person"></i> Edit Profil
        </a>
        <a class="nav-link logout" href="logout.php">
            <i class="bi bi-box-arrow-right"></i> Logout
        </a>
    </nav>
</div>
