<?php
session_start();
include 'koneksi.php';

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$id = $_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM todos WHERE id = $id");
$task = mysqli_fetch_assoc($query);

if (!$task) {
    echo "Tugas tidak ditemukan.";
    exit;
}

// Update tugas kalau form disubmit
if (isset($_POST['update_task'])) {
    $title = $_POST['title'];
    $deadline = $_POST['deadline'];

    $update = mysqli_query($conn, "UPDATE todos SET title='$title', deadline='$deadline' WHERE id = $id");

    if ($update) {
        header('Location: index.php');
        exit;
    } else {
        echo "Gagal mengupdate tugas.";
    }
}
?>

<!-- Form edit tugas -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Tugas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="min-height: 100vh;">
    <div class="container p-4 bg-white rounded-4 shadow" style="max-width: 600px;">
        <h3 class="mb-4 fw-bold text-center">Edit Tugas</h3>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label fw-semibold">Judul Tugas</label>
                <input type="text" name="title" value="<?= htmlspecialchars($task['title']) ?>" class="form-control form-control-lg rounded-3 shadow-sm" required>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Deadline</label>
                <input type="date" name="deadline" value="<?= htmlspecialchars($task['deadline']) ?>" class="form-control form-control-lg rounded-3 shadow-sm" required>
            </div>
            <div class="d-grid">
                <button type="submit" name="update_task" class="btn btn-gradient shadow fw-bold py-3 rounded-3">Simpan Perubahan</button>
                <a href="index.php" class="btn btn-secondary mt-3 rounded-3 py-2">Batal</a>
            </div>
        </form>
    </div>
</body>
</html>
