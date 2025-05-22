<?php
session_start();
require 'koneksi.php';

// cek login
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$email = $_SESSION['email'];

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// tambah task
if (isset($_POST['add_task'])) {
    $title = trim($_POST['title']);
    $deadline = $_POST['deadline'];
    $created_at = date('Y-m-d H:i:s');

    $stmt = $conn->prepare("INSERT INTO todos (user_id, title, status, deadline, created_at) VALUES (?, ?, 'pending', ?, ?)");
    $stmt->bind_param("isss", $user_id, $title, $deadline, $created_at);
    $stmt->execute();
    $stmt->close();

    header("Location: todo.php");
    exit();
}

// nandain task selesai
if (isset($_GET['done'])) {
    $task_id = (int) $_GET['done'];
    $stmt = $conn->prepare("UPDATE todos SET status = 'completed' WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $task_id, $user_id);
    $stmt->execute();
    $stmt->close();

    header("Location: todo.php");
    exit();
}

// hapus task
if (isset($_GET['delete'])) {
    $task_id = (int) $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM todos WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $task_id, $user_id);
    $stmt->execute();
    $stmt->close();

    header("Location: todo.php");
    exit();
}

// ambil daftar tugas
if (!empty($search)) {
    $like_search = "%$search%";
    $stmt = $conn->prepare("SELECT * FROM todos WHERE user_id = ? AND title LIKE ? ORDER BY deadline ASC");
    $stmt->bind_param("is", $user_id, $like_search);
} else {
    $stmt = $conn->prepare("SELECT * FROM todos WHERE user_id = ? ORDER BY deadline ASC");
    $stmt->bind_param("i", $user_id);
}

$stmt->execute();
$result = $stmt->get_result();

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
    <!-- sidebar -->
    <?php
    $activePage = 'todo';
    include 'includes/sidebar.php';
    ?>

    <div class="main-content">
        <!-- topbar -->
        <?php
        $pageTitle = "Todo List";
        include 'includes/topbar.php';
        ?>


        <div class="container mt-4">
            <!-- <h3 class="mb-3 fw-bold">Ayo buat pengingat dirimu</h3> -->
            <form method="POST" class="mb-4 px-4 py-4 rounded-4 shadow border border-light-subtle premium-form">
                <div class="row align-items-end g-3">
                    <div class="col-md-5">
                        <label class="form-label fw-semibold px-3">Judul Tugas</label>
                        <input type="text" name="title" class="form-control form-control-sm rounded-5 shadow-sm" placeholder="Tulis tugasmu di sini..." required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold px-3">Deadline</label>
                        <input type="date" name="deadline" class="form-control form-control-sm rounded-5 shadow-sm" required>
                    </div>
                    <div class="col-md-3 d-grid">
                        <button type="submit" name="add_task" class="btn btn-gradient shadow fw-semibold rounded-5">+ Tambah Tugas</button>
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
                    <tr class="<?= $row['status'] == 'completed' ? 'completed-task' : '' ?>">
                        <td class="<?= $row['status'] == 'completed' ? 'text-decoration-line-through' : '' ?>">
                            <?= htmlspecialchars($row['title']) ?>
                        </td>
                        <td class="<?= $row['status'] == 'completed' ? 'text-decoration-line-through' : '' ?>">
                            <?= htmlspecialchars($row['deadline']) ?>
                        </td>
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
                            <?php if ($row['status'] == 'completed'): ?>
                                <a href="?undo=<?= $row['id'] ?>" class="btn btn-icon btn-outline-secondary btn-sm me-1" title="Batalkan status">
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                </a>
                            <?php else: ?>
                                <a href="?done=<?= $row['id'] ?>" class="btn btn-icon btn-outline-success btn-sm me-1" title="Tandai selesai">
                                    <i class="bi bi-check-circle"></i>
                                </a>
                            <?php endif; ?>
                            <a href="edit_task.php?id=<?= $row['id'] ?>" class="btn btn-icon btn-outline-warning btn-sm me-1" title="Edit tugas">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Hapus tugas ini?')" class="btn btn-icon btn-outline-danger btn-sm" title="Hapus tugas">
                                <i class="bi bi-trash3"></i>
                            </a>
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
    <script>
         document.querySelectorAll('tbody tr').forEach((row, index) => {
            row.style.opacity = '0';
            row.style.transform = 'translateY(20px)';
            row.style.transition = `all 0.5s ease ${index * 0.05}s`;

            setTimeout(() => {
                row.style.opacity = '1';
                row.style.transform = 'translateY(0)';
            }, 50);
        });
    </script>

</body>

</html>