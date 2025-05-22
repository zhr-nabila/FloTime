<?php
session_start();
require 'koneksi.php';

// Cek apakah user sudah login
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$email = $_SESSION['email'];
$search = $_GET['search'] ?? '';

// Tambah Task
if (isset($_POST['add_task'])) {
    $title = $_POST['title'];
    $deadline = $_POST['deadline'];
    $created_at = date('Y-m-d H:i:s');

    $stmt = $conn->prepare("INSERT INTO todos (user_id, title, status, deadline, created_at) VALUES (?, ?, 'pending', ?, ?)");
    $stmt->bind_param("isss", $user_id, $title, $deadline, $created_at);
    $stmt->execute();
    $stmt->close();

    header("Location: todo.php");
    exit(); 
}

// Centang Task Selesai
if (isset($_GET['done'])) {
    $task_id = $_GET['done'];
    $conn->query("UPDATE todos SET status='done' WHERE id=$task_id AND user_id=$user_id");
    header("Location: todo.php");
    exit();
}

// Hapus Task
if (isset($_GET['delete'])) {
    $task_id = $_GET['delete'];
    $conn->query("DELETE FROM todos WHERE id=$task_id AND user_id=$user_id");
    header("Location: todo.php");
    exit();
}

if (!empty($search)) {
    $searchLike = "%$search%";
    $stmt = $conn->prepare("SELECT * FROM todos WHERE user_id = ? AND title LIKE ? ORDER BY deadline ASC");
    $stmt->bind_param("is", $user_id, $searchLike);
} else {
    $stmt = $conn->prepare("SELECT * FROM todos WHERE user_id = ? ORDER BY deadline ASC");
    $stmt->bind_param("i", $user_id);
}

// Check if the statement was prepared successfully
if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}

if (!$stmt->execute()) {
    die("Eksekusi gagal: " . $stmt->error);
}

$result = $stmt->get_result();

// Check if we have any results
if ($result === false) {
    die("Gagal ambil hasil: " . $stmt->error);
}
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

<div class="main-content">
    <div class="header">
        <div class="title">Todo List</div>
        <div class="search-bar">
            <form method="GET" action="todo.php" class="d-flex">
                <input type="text" name="search" class="form-control" placeholder="Search task..." value="<?= htmlspecialchars($search) ?>">
                <button type="submit" class="btn btn-outline-secondary ms-2"><i class="bi bi-search"></i></button>
            </form>
        </div>

        <div class="notifications">
            <i class="bi bi-bell"></i>
            <div class="date"><?= date('l, d F Y') ?></div>
        </div>
    </div>

    <div class="container mt-4">
        <h3 class="mb-3 fw-bold">Todo List</h3>
        <form method="POST" class="mb-3 row g-2">
            <div class="col-md-5">
                <input type="text" name="title" class="form-control" placeholder="Judul tugas" required>
            </div>
            <div class="col-md-3">
                <input type="date" name="deadline" class="form-control" required>
            </div>
            <div class="col-md-2">
                <button type="submit" name="add_task" class="btn btn-primary w-100">Tambah</button>
            </div>
        </form>

        <?php if ($result->num_rows > 0): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Deadline</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['title']) ?></td>
                            <td><?= htmlspecialchars($row['deadline']) ?></td>
                            <td>
                                <?php
                                if ($row['status'] == 'done') {
                                    echo "<span class='badge bg-success'>Selesai</span>";
                                } elseif (date('Y-m-d') > $row['deadline']) {
                                    echo "<span class='badge bg-danger'>Terlambat</span>";
                                } else {
                                    echo "<span class='badge bg-warning text-dark'>Belum Selesai</span>";
                                }
                                ?>
                            </td>
                            <td>
                                <?php if ($row['status'] != 'done'): ?>
                                    <a href="?done=<?= $row['id'] ?>" class="btn btn-success btn-sm">Centang</a>
                                <?php endif; ?>
                                <a href="edit_task.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Hapus tugas ini?')" class="btn btn-danger btn-sm">Hapus</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-muted">Tidak ada tugas ditemukan.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
