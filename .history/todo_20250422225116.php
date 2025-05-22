<?php
session_start();
include "koneksi.php";

// Cek apakah user sudah login
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$email = $_SESSION['email'];
$error = "";

// Tambah Task
if (isset($_POST['add'])) {
    $task = trim($_POST['task']);
    if (!empty($task)) {
        $stmt = $conn->prepare("INSERT INTO todos (user_id, task, status) VALUES (?, ?, 0)");
        $stmt->bind_param("is", $user_id, $task);
        $stmt->execute();
    } else {
        $error = "Task tidak boleh kosong.";
    }
}

// Hapus Task
if (isset($_GET['delete'])) {
    $task_id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM todos WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $task_id, $user_id);
    $stmt->execute();
}

// Centang Task (toggle status)
if (isset($_GET['done'])) {
    $task_id = intval($_GET['done']);
    // Ambil status saat ini
    $stmt = $conn->prepare("SELECT status FROM todos WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $task_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $new_status = $row['status'] == 1 ? 0 : 1;
        $stmt = $conn->prepare("UPDATE todos SET status = ? WHERE id = ? AND user_id = ?");
        $stmt->bind_param("iii", $new_status, $task_id, $user_id);
        $stmt->execute();
    }
}

// Edit Task
if (isset($_POST['edit']) && isset($_POST['task_id'])) {
    $task_id = intval($_POST['task_id']);
    $new_task = trim($_POST['edited_task']);
    if (!empty($new_task)) {
        $stmt = $conn->prepare("UPDATE todos SET task = ? WHERE id = ? AND user_id = ?");
        $stmt->bind_param("sii", $new_task, $task_id, $user_id);
        $stmt->execute();
    } else {
        $error = "Task tidak boleh kosong saat diedit.";
    }
}

// Ambil semua task user
$stmt = $conn->prepare("SELECT * FROM todos WHERE user_id = ? ORDER BY id DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$tasks = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Todo List</title>
    <link rel="stylesheet" href="css/todo.css">
</head>
<body>
    <div class="container">
        <h1>Hai, <?= htmlspecialchars($email) ?> 👋</h1>
        <form method="POST">
            <input type="text" name="task" placeholder="Tulis tugas baru...">
            <button type="submit" name="add">Tambah</button>
        </form>
        <?php if (!empty($error)): ?>
            <p style="color: red;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <ul>
            <?php while ($row = $tasks->fetch_assoc()): ?>
                <li>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="task_id" value="<?= $row['id'] ?>">
                        <input type="text" name="edited_task" value="<?= htmlspecialchars($row['task']) ?>">
                        <button type="submit" name="edit">Edit</button>
                    </form>

                    <a href="?done=<?= $row['id'] ?>" style="margin-left: 10px; color: green;">
                        <?= $row['status'] ? '✅' : '⬜' ?>
                    </a>

                    <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Hapus tugas ini?')" style="margin-left: 10px; color: red;">
                        ❌
                    </a>
                </li>
            <?php endwhile; ?>
        </ul>
        <br>
        <a href="logout.php" onclick="return confirm('Yakin logout?')">Logout</a>
    </div>
</body>
</html>
