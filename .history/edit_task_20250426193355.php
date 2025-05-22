<?php
session_start();
include 'koneksi.php';

// Pastikan ada parameter id
if (!isset($_GET['id'])) {
    header("Location: todo.php");
    exit;
}

$id = intval($_GET['id']);
$query = mysqli_query($conn, "SELECT * FROM todos WHERE id = $id");

if (!$query || mysqli_num_rows($query) == 0) {
    header("Location: todo.php");
    exit;
}

$row = mysqli_fetch_assoc($query);

// Kalau form dikirim
if (isset($_POST['update_task'])) {
    $title = htmlspecialchars($_POST['title']);
    $deadline = $_POST['deadline'];

    mysqli_query($coon, "UPDATE todos SET title='$title', deadline='$deadline' WHERE id=$id");

    header("Location: todo.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Tugas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Optional: Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .edit-form-container {
            background: linear-gradient(to right, var(--bs-pink-200), var(--bs-pink-100));
            padding: 30px;
            border-radius: 1.5rem;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
        }
        .btn-gradient {
            background: linear-gradient(135deg, #f093fb, #f5576c);
            color: white;
            border: none;
            transition: 0.3s ease;
        }
        .btn-gradient:hover {
            background: linear-gradient(135deg, #f5576c, #f093fb);
            transform: scale(1.03);
        }
    </style>
</head>
<body>

<div class="edit-form-container">
    <h2 class="text-center mb-4 fw-bold">Edit Tugas</h2>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label fw-semibold">Judul Tugas</label>
            <input type="text" name="title" class="form-control form-control-lg rounded-3 shadow-sm" value="<?= htmlspecialchars($row['title']) ?>" required>
        </div>
        <div class="mb-4">
            <label class="form-label fw-semibold">Deadline</label>
            <input type="date" name="deadline" class="form-control form-control-lg rounded-3 shadow-sm" value="<?= $row['deadline'] ?>" required>
        </div>
        <div class="d-flex gap-2">
            <button type="submit" name="update_task" class="btn btn-gradient w-100 rounded-3 d-flex align-items-center justify-content-center gap-2 py-2">
                <i class="bi bi-save2"></i> Simpan Perubahan
            </button>
            <a href="todo.php" class="btn btn-outline-secondary w-50 rounded-3 d-flex align-items-center justify-content-center gap-2 py-2">
                <i class="bi bi-arrow-left-circle"></i> Batal
            </a>
        </div>
    </form>
</div>

</body>
</html>
