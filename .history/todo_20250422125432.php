<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php");
    exit();
}
$email = $_SESSION['email'];
require 'koneksi.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Todo List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar d-flex flex-column">
    <div class="px-4 d-flex align-items-center mb-4">
        <div class="user-avatar"><?= strtoupper(substr($email, 0, 1)) ?></div>
        <div class="ms-2">
            <div class="fw-semibold">User Dashboard</div>
            <div class="small text-white-50"><?= htmlspecialchars($email) ?></div>
        </div>
    </div>
    <nav class="nav flex-column px-3">
        <a class="nav-link" href="dashboard.php"><i class="bi bi-house-door"></i> Dashboard</a>
        <a class="nav-link active" href="todo.php"><i class="bi bi-list-task"></i> Todo List</a>
        <a class="nav-link" href="profile.php"><i class="bi bi-person"></i> Edit Profil</a>
        <a class="nav-link logout" href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </nav>
</div>


    <!-- Main Content -->
    <div class="main-content">
        <!-- Topbar -->
        <div class="header">
    <div class="title">Todo List</div>
    <div class="search-bar">
        <input type="text" placeholder="Search task...">
        <i class="bi bi-search"></i>
    </div>
    <div class="notifications">
        <i class="bi bi-bell"></i>
        <div class="date"><?= date('l, d F Y') ?></div>
    </div>
</div>


        <!-- Todo List Section -->
        <div class="container py-5">
    <div class="card shadow rounded-4 border-0">
        <div class="card-body p-5">
            <h2 class="mb-4 fw-bold text-primary">📋 Todo List</h2>

            <!-- Form Tambah Task -->
            <form action="add_task.php" method="POST" class="row g-3 mb-4">
                <div class="col-md-5">
                    <input type="text" name="title" placeholder="Tugas baru" class="form-control form-control-lg rounded-3" required>
                </div>
                <div class="col-md-4">
                    <input type="date" name="deadline" class="form-control form-control-lg rounded-3" required>
                </div>
                <div class="col-md-3 d-grid">
                    <button class="btn btn-success btn-lg rounded-3">+ Tambah</button>
                </div>
            </form>

            <!-- Form Pencarian -->
            <form method="GET" class="mb-3">
                <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="🔍 Cari task..." class="form-control form-control-lg rounded-3">
            </form>

            <!-- Daftar Task -->
            <ul class="list-group list-group-flush">
                <?php while($row = $result->fetch_assoc()): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center py-3 px-4 border-bottom">
                    <div class="d-flex align-items-center gap-3">
                        <form action="update_status.php" method="POST" class="me-2">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <input type="checkbox" name="status" onchange="this.form.submit()" <?= $row['status'] == 'done' ? 'checked' : '' ?>>
                        </form>
                        <div>
                            <strong class="fs-5 <?= $row['status'] == 'done' ? 'text-decoration-line-through text-muted' : '' ?>">
                                <?= htmlspecialchars($row['title']) ?>
                            </strong><br>
                            <small class="text-muted">Deadline: <?= $row['deadline'] ?>
                                <?php if ($row['status'] == 'pending' && date('Y-m-d') > $row['deadline']): ?>
                                    <span class="badge bg-danger ms-2">Terlambat</span>
                                <?php endif; ?>
                            </small>
                        </div>
                    </div>
                    <div class="btn-group">
                        <a href="edit_task.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="delete_task.php?id=<?= $row['id'] ?>" onclick="return confirm('Yakin ingin menghapus task ini?')" class="btn btn-danger btn-sm">Hapus</a>
                    </div>
                </li>
                <?php endwhile; ?>
            </ul>

            

        </div>
    </div>
</div>


</body>
</html>
