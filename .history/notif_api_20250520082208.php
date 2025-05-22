<?php
session_start();
header('Content-Type: application/json');

// Cek login
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        "unread" => 0,
        "notifications" => [],
        "error" => "Belum login"
    ]);
    exit;
}

require_once "koneksi.php";

$user_id = $_SESSION['user_id'];

$sql = "SELECT id, title, deadline FROM tasks 
        WHERE user_id = ? AND status != 'selesai' AND deadline < CURDATE()";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$notifications = [];

while ($row = mysqli_fetch_assoc($result)) {
    $notifications[] = [
        "id" => $row["id"],
        "message" => "Tugas '{$row["title"]}' sudah melewati deadline!",
        "is_read" => 0
    ];
}

echo json_encode([
    "unread" => count($notifications),
    "notifications" => $notifications
]);
