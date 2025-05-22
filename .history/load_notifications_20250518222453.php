<?php
session_start();
include 'koneksi.php';

$email = $_SESSION['email'] ?? null;

if (!$email) {
    echo json_encode([]);
    exit;
}

$query = mysqli_query($conn, "SELECT * FROM notifications WHERE email = '$email' AND is_read = 0 ORDER BY created_at DESC LIMIT 5");

$data = [];
while ($row = mysqli_fetch_assoc($query)) {
    $data[] = [
        'id' => $row['id'],
        'text' => $row['message']
    ];
}

echo json_encode($data);
?>
