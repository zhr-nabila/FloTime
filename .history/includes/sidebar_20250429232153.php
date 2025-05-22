<?php
if (!isset($email)) {
    session_start();
    $email = $_SESSION['email'];
}

include_once 'koneksi.php';
$get_user = mysqli_query($conn, "SELECT name, photo FROM users WHERE email = '$email'");
$data_user = mysqli_fetch_assoc($get_user);

$display_name = $data_user['name'] ?? 'User Dashboard';
$first_letter = strtoupper(substr($display_name, 0, 1));
$photo = $data_user['photo'];
?>

<div class="px-2 d-flex align-items-center mb-4">
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
