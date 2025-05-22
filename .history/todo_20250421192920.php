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
    <?php include 'includes/sidebar.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Topbar -->
        <?php include 'includes/topbar.php'; ?>

        <!-- Todo List Section -->
        <div class="container py-5">
    <div class="card shadow rounded-4 border-0">
        <div class="card-body p-5">
            <h2 class="mb-4 fw-bold text-primary">📋 Todo List Premium</h2>

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
