<?php
session_start();
include('koneksi.php');

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $task_id = $_GET['id'];
    $query = "SELECT * FROM todos WHERE id = '$task_id' AND user_id = '" . $_SESSION['id'] . "'";
    $result = mysqli_query($koneksi, $query);
    $task = mysqli_fetch_assoc($result);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $deadline = $_POST['deadline'];
    $status = $_POST['status'];

    $update_query = "UPDATE todos SET title = '$title', deadline = '$deadline', status = '$status' WHERE id = '$task_id'";
    mysqli_query($koneksi, $update_query);
    header("Location: todo.php");
}
?>

<form method="POST">
    <input type="text" name="title" value="<?= $task['title'] ?>" required>
    <input type="date" name="deadline" value="<?= $task['deadline'] ?>" required>
    <select name="status">
        <option value="pending" <?= $task['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
        <option value="completed" <?= $task['status'] == 'completed' ? 'selected' : '' ?>>Completed</option>
    </select>
    <button type="submit">Update Task</button>
</form>
