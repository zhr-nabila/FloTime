<div class="sidebar">
    <!-- Menu-menu utama di atas -->
    <nav class="nav flex-column px-3">
        <a class="nav-link <?= $activePage == 'dashboard' ? 'active' : '' ?>" href="dashboard.php">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        <a class="nav-link <?= $activePage == 'todo' ? 'active' : '' ?>" href="todo.php">
            <i class="bi bi-list-check"></i> Todo List
        </a>
        <a class="nav-link <?= $activePage == 'profile' ? 'active' : '' ?>" href="profile.php">
            <i class="bi bi-person"></i> Profil
        </a>
        
        <!-- Menu di bagian bawah -->
        <div class="sidebar-bottom">
            <a class="nav-link delete-account" href="delete_account.php">
                <i class="bi bi-trash-fill"></i> Hapus Akun
            </a>
            <a class="nav-link logout" href="logout.php">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
        </div>
    </nav>
</div>