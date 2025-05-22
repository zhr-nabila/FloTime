<?php
session_start();
include('koneksi.php');

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['id'];
$search = isset($_GET['search']) ? mysqli_real_escape_string($koneksi, $_GET['search']) : '';

$query = "SELECT * FROM todos WHERE user_id = '$user_id' AND title LIKE '%$search%'";
$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Todo List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'includes/sidebar.php'; ?>
    <div class="main-content">
        <?php include 'includes/topbar.php'; ?>
        <div class="container mt-5">
            <h2>Todo List</h2>
            <form method="POST" action="todo.php">
                <input type="text" name="title" placeholder="Add new task" required>
                <input type="date" name="deadline" required>
                <button type="submit">Add Task</button>
            </form>

            <div class="task-list">
                <?php while ($task = mysqli_fetch_assoc($result)): ?>
                    <div class="task">
                        <div class="task-info">
                            <span class="task-title"><?= $task['title'] ?></span>
                            <span class="task-deadline"><?= $task['deadline'] ?></span>
                            <?php if ($task['status'] == 'completed'): ?>
                                <span class="badge bg-success">Selesai</span>
                            <?php elseif ($task['deadline'] < date('Y-m-d')): ?>
                                <span class="badge bg-danger">Terlambat</span>
                            <?php endif; ?>
                        </div>
                        <div class="task-actions">
                            <a href="edit_task.php?id=<?= $task['id'] ?>">Edit</a>
                            <a href="todo.php?action=complete&id=<?= $task['id'] ?>">Complete</a>
                            <a href="todo.php?action=delete&id=<?= $task['id'] ?>">Delete</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
</body>
</html>
