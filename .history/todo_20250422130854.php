<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] !== "login") {
    header("Location: login.php");
    exit();
}

include 'koneksi.php';

$email = $_SESSION['email'];
$user_id = $_SESSION['user_id']; // Ini harus diset saat login sukses

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Ambil data todos
if ($search !== '') {
    $stmt = $conn->prepare("SELECT * FROM todos WHERE user_id = ? AND title LIKE ? ORDER BY deadline ASC");
    $likeSearch = "%$search%";
    $stmt->bind_param("is", $user_id, $likeSearch);
} else {
    $stmt = $conn->prepare("SELECT * FROM todos WHERE user_id = ? ORDER BY deadline ASC");
    $stmt->bind_param("i", $user_id);
}

$stmt->execute();
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

<?php include 'includes/sidebar.php'; ?>

<div class="main-content">
    <?php include 'includes/topbar.php'; ?>

    <div class="container mt-4">
        <h2 class="mb-4 fw-bold">Todo List</h2>

        <form method="GET" class="d-flex mb-3">
            <input type="text" name="search" class="form-control me-2" placeholder="Cari tugas..." value="<?= htmlspecialchars($search) ?>">
            <button class="btn btn-primary"><i class="bi bi-search"></i></button>
        </form>

        <table class="table table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Judul</th>
                    <th>Status</th>
                    <th>Deadline</th>
                    <th>Dibuat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['title']) ?></td>
                            <td><?= htmlspecialchars($row['status']) ?></td>
                            <td><?= htmlspecialchars($row['deadline']) ?></td>
                            <td><?= htmlspecialchars($row['created_at']) ?></td>
                            <td>
                                <a href="#" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                                <a href="#" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="5" class="text-center">Tidak ada tugas.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
