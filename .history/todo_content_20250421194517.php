<?php
// File: todo.php
session_start();
include 'koneksi.php'; // koneksi ke database

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$email = $_SESSION['email'];
$userId = $_SESSION['user_id']; // Pastikan user_id ada di session
$search = $_GET['search'] ?? '';

$sql = "SELECT * FROM todos WHERE user_id = ? AND title LIKE ? ORDER BY deadline ASC";
$stmt = $conn->prepare($sql);
$like = "%$search%";
$stmt->bind_param("is", $user_id, $like);
$stmt->execute();
$result = $stmt->get_result();
?>

<?php include 'includes/sidebar.php'; ?>
<?php include 'includes/topbar.php'; ?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

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

<?php include 'includes/footer.php'; ?>