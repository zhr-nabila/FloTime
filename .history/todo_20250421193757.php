<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php");
    exit();
}
$email = $_SESSION['email'];
require_once 'koneksi.php'; // untuk koneksi ke database

$userId = $_SESSION['user_id']; // Pastikan ini diset saat login
$todos = mysqli_query($conn, "SELECT * FROM todos WHERE user_id = '$userId' ORDER BY deadline ASC");
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

<?php include 'includes/sidebar.php'; ?>

<div class="main-content">
    <?php include 'includes/topbar.php'; ?>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="fw-bold">Todo List</h3>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="bi bi-plus-circle me-1"></i> Tambah Tugas
            </button>
        </div>

        <form method="GET" class="mb-3">
            <input type="text" name="search" class="form-control" placeholder="Cari tugas...">
        </form>

        <?php while ($row = mysqli_fetch_assoc($todos)) :
            $isLate = (strtotime($row['deadline']) < time() && $row['status'] != 'selesai');
        ?>
            <div class="card mb-2 p-3 shadow-sm <?= $isLate ? 'border-danger' : '' ?>">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="mb-1"><?= htmlspecialchars($row['title']) ?></h5>
                        <small class="text-muted">Deadline: <?= date('d M Y', strtotime($row['deadline'])) ?></small>
                        <?php if ($isLate): ?>
                            <span class="badge bg-danger ms-2">Terlambat</span>
                        <?php endif; ?>
                    </div>
                    <div class="btn-group">
                        <form method="POST" action="task_action.php">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <input type="hidden" name="action" value="toggle">
                            <button class="btn btn-outline-success btn-sm <?= $row['status'] == 'selesai' ? 'active' : '' ?>">
                                <i class="bi bi-check-circle"></i>
                            </button>
                        </form>
                        <a href="edit_task.php?id=<?= $row['id'] ?>" class="btn btn-outline-warning btn-sm">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <a href="delete_task.php?id=<?= $row['id'] ?>" onclick="return confirm('Yakin ingin menghapus?')" class="btn btn-outline-danger btn-sm">
                            <i class="bi bi-trash"></i>
                        </a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="task_action.php">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Tugas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="action" value="add">
                    <div class="mb-3">
                        <label>Judul Tugas</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Deadline</label>
                        <input type="date" name="deadline" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="submit">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
