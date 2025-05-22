<?php
session_start();
require 'koneksi.php';

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$email = $_SESSION['email'];
$search = $_GET['search'] ?? '';

// Tambah task
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

// Centang task
if (isset($_GET['done'])) {
    $task_id = $_GET['done'];
    $conn->query("UPDATE todos SET status='completed' WHERE id=$task_id AND user_id=$user_id");
    header("Location: todo.php");
    exit();
}

// Hapus task
if (isset($_GET['delete'])) {
    $task_id = $_GET['delete'];
    $conn->query("DELETE FROM todos WHERE id=$task_id AND user_id=$user_id");
    header("Location: todo.php");
    exit();
}

// Ambil data tugas
$searchSql = !empty($search) ? "AND title LIKE '%$search%'" : "";
$sql = "SELECT * FROM todos WHERE user_id = $user_id $searchSql ORDER BY deadline ASC";
$result = $conn->query($sql);

if (!$result) {
    die("Query gagal: " . $conn->error);
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
                    <button type="submit" class="btn"><i class="bi bi-search"></i></button>
                </form>
            </div>
            <div class="notifications">
                <i class="bi bi-bell"></i>
                <div class="date"><?= date('l, d F Y') ?></div>
            </div>
        </div>

        <div class="container mt-4">
            <h3 class="mb-3 fw-bold">Todo List</h3>
            <form method="POST" class="mb-4 px-3 py-4 rounded-4 shadow bg-white border border-light-subtle premium-form">
                <div class="row align-items-end g-3">
                    <div class="col-md-5">
                        <label class="form-label fw-semibold">Judul Tugas</label>
                        <input type="text" name="title" class="form-control form-control-sm rounded-5 shadow-sm" placeholder="Tulis tugasmu di sini..." required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Deadline</label>
                        <input type="date" name="deadline" class="form-control form-control-sm rounded-5 shadow-sm" required>
                    </div>
                    <div class="col-md-3 d-grid">
                        <button type="submit" name="add_task" class="btn btn-gradient shadow fw-semibold py-2 rounded-5">+ Tambah Tugas</button>
                    </div>
                </div>
            </form>


            <?php if ($result->num_rows > 0): ?>
                <div class="table-responsive shadow-sm rounded-4 overflow-hidden">
                    <table class="table table-hover align-middle mb-0 premium-table">
                        <thead class="table-light text-center">
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
                                    <td class="text-center">
                                        <?php
                                        if ($row['status'] == 'completed') {
                                            echo "<span class='badge rounded-pill text-bg-success px-3 py-2'>Selesai</span>";
                                        } elseif (date('Y-m-d') > $row['deadline']) {
                                            echo "<span class='badge rounded-pill text-bg-danger px-3 py-2'>Terlambat</span>";
                                        } else {
                                            echo "<span class='badge rounded-pill text-bg-warning text-dark px-3 py-2'>Belum Selesai</span>";
                                        }
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($row['status'] != 'completed'): ?>
                                            <a href="?done=<?= $row['id'] ?>" class="btn btn-outline-success btn-sm me-1">✔️</a>
                                        <?php endif; ?>
                                        <a href="edit_task.php?id=<?= $row['id'] ?>" class="btn btn-outline-warning btn-sm me-1">✏️</a>
                                        <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Hapus tugas ini?')" class="btn btn-outline-danger btn-sm">🗑️</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-muted mt-4 text-center">✨ Tidak ada tugas ditemukan. Buat yang baru, yuk!</p>
            <?php endif; ?>

        </div>
    </div>

</body>

</html>