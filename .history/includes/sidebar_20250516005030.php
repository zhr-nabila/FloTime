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
    <div class="px-4 d-flex align-items-center mb-4">
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
        
       <!-- Tambahkan ini di bagian bawah sidebar -->
<div class="mt-auto">
    <!-- Tombol trigger modal -->
    <a class="nav-link text-danger delete-account" href="#" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
        <i class="bi bi-trash-fill"></i> Hapus Akun
    </a>
    <a class="nav-link logout" href="logout.php">
        <i class="bi bi-box-arrow-right"></i> Logout
    </a>
</div>

<!-- Modal Structure -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteAccountModalLabel">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> Hapus Akun
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">
                <div class="alert alert-danger mb-0">
                    <p class="fw-bold mb-2">⚠️ PERINGATAN: Tindakan ini permanen!</p>
                    <p class="mb-0">Semua data termasuk task dan profil akan dihapus dan tidak dapat dikembalikan.</p>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i> Batal
                </button>
                <form action="delete_account.php" method="POST">
                    <button type="submit" name="confirm_delete" class="btn btn-danger rounded-pill px-4">
                        <i class="bi bi-trash-fill me-1"></i> Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>