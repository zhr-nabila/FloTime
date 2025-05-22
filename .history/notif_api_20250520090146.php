<?php
session_start();
header('Content-Type: application/json');

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

$sql = "SELECT id, title, deadline FROM todos 
        WHERE user_id = ? AND status != 'completed' AND deadline < CURDATE()";

$stmt = mysqli_prepare($conn, $sql);
if (!$stmt) {
    echo json_encode([
        "unread" => 0,
        "notifications" => [],
        "error" => "Query error: " . mysqli_error($conn)
    ]);
    exit;
}

mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
if (!$result) {
    echo json_encode([
        "unread" => 0,
        "notifications" => [],
        "error" => "Gagal mengambil data: " . mysqli_error($conn)
    ]);
    exit;
}

$notifications = [];
while ($row = mysqli_fetch_assoc($result)) {
    $notifications[] = [
        "id" => $row["id"],
        "message" => "Tugas '{$row["title"]}' sudah melewati deadline!"
    ];
}

echo json_encode([
    "unread" => count($notifications),
    "notifications" => $notifications
]);
exit;
