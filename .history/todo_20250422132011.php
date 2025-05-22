<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id']; // pastikan ini diset saat login
$email = $_SESSION['email'];

$search = isset($_GET['search']) ? $_GET['search'] : '';
$sql = "SELECT * FROM todos WHERE user_id = $user_id AND title LIKE '%$search%' ORDER BY deadline ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Todo List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .label-terlambat {
            background-color: #dc3545;
            color: white;
            padding: 2px 10px;
            border-radius: 12px;
            font-size: 0.8rem;
        }
        .label-selesai {
            background-color: #28a745;
            color: white;
            padding: 2px 10px;
            border-radius: 12px;
            font-size: 0.8rem;
        }
    </style>
</head>
<body>
    <?php include 'includes/sidebar.php'; ?>
    <div class="main-content">
        <?php include 'includes/topbar.php'; ?>

        <div class="container py-4">
            <h2 class="fw-bold mb-3">Todo List</h2>

            <!-- Form Tambah -->
            <form action="tambah_task.php" method="POST" class="row g-3 mb-4">
                <div class="col-md-5">
                    <input type="text" name="title" class="form-control" placeholder="Tambah tugas..." required>
                </div>
                <div class="col-md-3">
                    <input type="date" name="deadline" class="form-control" required>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Tambah</button>
                </div>
            </form>

            <!-- Daftar Tugas -->
            <div class="list-group">
                <?php
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $isLate = strtotime($row['deadline']) < strtotime(date('Y-m-d')) && $row['status'] != 'done';
                        $isDone = $row['status'] == 'done';
                        ?>

                        <div class="list-group-item d-flex justify-content-between align-items-center <?= $isLate ? 'border-danger' : '' ?>">
                            <div>
                                <span class="fw-semibold"><?= htmlspecialchars($row['title']) ?></span>
                                <?php if ($isLate): ?>
                                    <span class="label-terlambat">Terlambat</span>
                                <?php elseif ($isDone): ?>
                                    <span class="label-selesai">Selesai</span>
                                <?php endif; ?>
                                <div class="small text-muted">Deadline: <?= htmlspecialchars($row['deadline']) ?></div>
                            </div>
                            <div class="btn-group">
                                <a href="edit_task.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                                <?php if (!$isDone): ?>
                                    <a href="centang_task.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-success"><i class="bi bi-check-circle"></i></a>
                                <?php endif; ?>
                                <a href="hapus_task.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin ingin menghapus?')"><i class="bi bi-trash"></i></a>
                            </div>
                        </div>

                    <?php
                    }
                } else {
                    echo '<div class="alert alert-info">Belum ada tugas.</div>';
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>
