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
        <a class="nav-link delete-account" href="#" id="deleteAccountBtn">
            <i class="bi bi-trash"></i> Hapus Akun
        </a>

        <a class="nav-link logout" href="logout.php">
            <i class="bi bi-box-arrow-right"></i> Logout
        </a>
    </nav>
</div>

<!-- Modal Hapus Akun -->
<!-- Modal Hapus Akun -->
<div id="deleteModal">
  <div class="modal-box">
    <h4>Yakin ingin menghapus akun?</h4>
    <p>Aksi ini tidak bisa dibatalkan!</p>
    <form id="deleteAccountForm" method="POST" action="delete_account.php">
        <button type="button" class="cancel" onclick="closeModal()">Batal</button>
        <button type="submit" class="confirm">Ya, Hapus</button>
    </form>
  </div>
</div>

<script>
  const deleteBtn = document.getElementById('deleteAccountBtn');
  const modal = document.getElementById('deleteModal');
  const deleteForm = document.getElementById('deleteAccountForm');

  if (deleteBtn && modal) {
    deleteBtn.addEventListener('click', function(e) {
      e.preventDefault();
      modal.style.display = 'flex';
    });
  }

  function closeModal() {
    modal.style.display = 'none';
  }

  // Handle form submission
  deleteForm.addEventListener('submit', function(e) {
    e.preventDefault();
    
    fetch('delete_account.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: new URLSearchParams(new FormData(deleteForm))
    })
    .then(response => response.json())
    .then(result => {
      if (result.status === 'success') {
        window.location.href = 'login.php?account_deleted=1';
      } else {
        alert(result.message);
        closeModal();
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert('Terjadi kesalahan saat menghapus akun');
      closeModal();
    });
  });
</script>