<?php
session_start();
include 'koneksi.php';

$email = $_SESSION['email'] ?? null;

if (!$email) {
    echo json_encode(['status' => 'error', 'message' => 'User tidak ditemukan']);
    exit;
}

// Hapus user dari database
$query = mysqli_query($conn, "DELETE FROM users WHERE email = '$email'");

if ($query) {
    session_destroy();
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus akun']);
}
?>
<?php
session_start();
header('Content-Type: application/json');
include 'koneksi.php';

$email = $_SESSION['email'] ?? null;

if (!$email) {
    echo json_encode(['status' => 'error', 'message' => 'User tidak ditemukan']);
    exit;
}

$query = mysqli_query($conn, "DELETE FROM users WHERE email = '$email'");

if ($query) {
    session_destroy();
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus akun']);
}
?>
