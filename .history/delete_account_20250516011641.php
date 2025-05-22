<?php
session_start();
include 'koneksi.php';

$email = $_SESSION['email'] ?? '';

if (!$email) {
    echo json_encode(['status' => 'error', 'message' => 'Anda belum login.']);
    exit;
}

// Ambil data user (jika ada foto)
$query = mysqli_query($conn, "SELECT photo FROM users WHERE email = '$email'");
$user = mysqli_fetch_assoc($query);
$photo = $user['photo'] ?? null;

// Hapus foto jika ada
if ($photo && file_exists("uploads/$photo")) {
    unlink("uploads/$photo");
}

// Hapus user
$delete = mysqli_query($conn, "DELETE FROM users WHERE email = '$email'");

if ($delete) {
    session_destroy();
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus akun.']);
}
?>
