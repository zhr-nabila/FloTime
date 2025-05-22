<?php
session_start();
header('Content-Type: application/json');

// Debug sementara:
file_put_contents('log.txt', json_encode($_SESSION) . PHP_EOL, FILE_APPEND);

include 'koneksi.php';

$email = $_SESSION['email'] ?? null;

if (!$email) {
    echo json_encode(['status' => 'error', 'message' => 'Session email kosong']);
    exit;
}

$query = mysqli_query($conn, "DELETE FROM users WHERE email = '$email'");

if ($query) {
    session_destroy();
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus akun']);
}
