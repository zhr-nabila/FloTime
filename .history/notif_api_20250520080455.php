<?php
// notif_api.php
include 'db_conn.php'; // ganti sesuai nama koneksi DB-mu
session_start();

$user_id = $_SESSION['user_id']; // pastikan session ini aktif
$notifications = [];
$unread = 0;

// Ambil semua tugas user yang statusnya belum dan deadline sudah lewat
$sql = "SELECT id, title, deadline FROM tasks 
        WHERE user_id = ? AND status != 'selesai' AND deadline < CURDATE()";
$stmt = $conn->prepare($sql);
$stmt->execute([$user_id]);
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($data as $row) {
  $notifications[] = [
    "id" => $row['id'],
    "message" => "Tugas '{$row['title']}' sudah melewati deadline!",
    "is_read" => 0
  ];
  $unread++;
}

// Kirim data dalam format JSON ke JavaScript
header('Content-Type: application/json');
echo json_encode([
  "unread" => $unread,
  "notifications" => $notifications
]);
