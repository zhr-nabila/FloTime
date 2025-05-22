<?php
include 'koneksi.php';
session_start();
$user_id = $_SESSION['user_id'] ?? 1;

$notifQuery = mysqli_query($conn, "SELECT id, message, is_read FROM notifications WHERE user_id = $user_id ORDER BY created_at DESC LIMIT 10");

$notifications = [];
$unread = 0;

while ($row = mysqli_fetch_assoc($notifQuery)) {
    $notifications[] = [
        'id' => (int)$row['id'],
        'message' => $row['message'],
        'is_read' => (int)$row['is_read']
    ];
    if (!$row['is_read']) {
        $unread++;
    }
}

echo json_encode([
    'unread' => $unread,
    'notifications' => $notifications
]);
?>
