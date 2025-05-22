<?php
if (!isset($_SESSION)) session_start();
include_once 'koneksi.php';

$email = $_SESSION['email'] ?? '';

// Ambil data user
$query = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
$user = mysqli_fetch_assoc($query);

// Ambil nama dan foto
$name = $user['name'] ?? '';
$avatar = $user['photo'] ?? null; // GUNAKAN 'photo' bukan 'avatar'

// Ambil inisial dari nama jika ada, kalau tidak dari email
$display_name = $name ?: $email;
$initial = strtoupper(substr($display_name, 0, 1));
?>

<div class="sidebar d-flex flex-column">
    <div class="px-3 d-flex align-items-center mb-4">
        <div class="user-avatar">
            <?php if ($avatar && file_exists("uploads/$avatar")): ?>
                <img src="uploads/<?= htmlspecialchars($avatar) ?>" alt="Avatar">
            <?php else: ?>
                <?= $initial ?>
            <?php endif; ?>
        </div>
        <div class="ms-3">
            <div class="fw-semibold"><?= htmlspecialchars($name ?: 'User') ?></div>
            <div class="small text-white-50"><?= htmlspecialchars($email) ?></div>
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
