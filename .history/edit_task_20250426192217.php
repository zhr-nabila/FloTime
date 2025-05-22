<?php
// Koneksi ke database
include 'koneksi.php';

// Cek apakah ada ID yang dikirim
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Ambil data tugas berdasarkan ID
    $query = "SELECT * FROM tasks WHERE id = $id";
    $result = mysqli_query($conn, $query);
    $task = mysqli_fetch_assoc($result);

    if (!$task) {
        echo "Tugas tidak ditemukan.";
        exit;
    }
} else {
    echo "ID tidak ditemukan.";
    exit;
}

// Proses update saat form disubmit
if (isset($_POST['update_task'])) {
    $title = $_POST['title'];
    $deadline = $_POST['deadline'];

    $updateQuery = "UPDATE tasks SET title='$title', deadline='$deadline' WHERE id=$id";
    mysqli_query($conn, $updateQuery);

    header('Location: index.php'); // Balik ke halaman utama
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Tugas</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container py-5">
        <h2 class="mb-4 text-center">Edit Tugas</h2>

        <form method="POST" class="mb-4 px-3 py-4 rounded-4 shadow bg-white border border-light-subtle premium-form">
            <div class="row align-items-end g-3">
                <div class="col-md-5">
                    <label class="form-label fw-semibold">Judul Tugas</label>
                    <input type="text" name="title" value="<?= htmlspecialchars($task['title']) ?>" class="form-control form-control-lg rounded-3 shadow-sm" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Deadline</label>
                    <input type="date" name="deadline" value="<?= $task['deadline'] ?>" class="form-control form-control-lg rounded-3 shadow-sm" required>
                </div>
                <div class="col-md-3 d-grid">
                    <button type="submit" name="update_task" class="btn btn-gradient shadow fw-bold py-3 rounded-3">💾 Update</button>
                </div>
            </div>
        </form>

        <div class="text-center">
            <a href="index.php" class="btn btn-secondary rounded-3">⬅️ Kembali</a>
        </div>
    </div>
</body>
</html>
