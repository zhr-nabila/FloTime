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
    $title = htmlspecialchars(trim($_POST['title']));
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

// Eksekusi dan ambil hasil
if (!$stmt->execute()) {
    die("Eksekusi gagal: " . $stmt->error);
}

$result = $stmt->get_result();
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

<!-- Sidebar and Main Content Code Here (No Changes) -->

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

</body>
</html>
