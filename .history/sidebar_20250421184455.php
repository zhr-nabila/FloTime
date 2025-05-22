<div class="sidebar d-flex flex-column">
    <div class="px-4 d-flex align-items-center mb-4">
        <div class="user-avatar"><?= strtoupper(substr($email, 0, 1)) ?></div>
        <div class="ms-2">
            <div class="fw-semibold">User Dashboard</div>
            <div class="small text-white-50"><?= htmlspecialchars($email) ?></div>
        </div>
    </div>
    <nav class="nav flex-column px-3">
        <a class="nav-link active" href="dashboard.php"><i class="bi bi-house-door"></i> Dashboard</a>
        <a class="nav-link" href="todo.php"><i class="bi bi-list-task"></i> Todo List</a>
        <a class="nav-link" href="profile.php"><i class="bi bi-person"></i> Edit Profil</a>
        <a class="nav-link logout" href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </nav>
</div>
