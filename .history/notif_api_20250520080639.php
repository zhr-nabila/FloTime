<?php
// notif_api.php
session_start();
header('Content-Type: application/json');

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
  echo json_encode([
    "unread" => 0,
    "notifications" => [],
    "error" => "User not logged in"
  ]);
  exit;
}

require_once "db_conn.php"; // pastikan file koneksi database ini ada dan benar

$user_id = $_SESSION['user_id'];

try {
  // Ambil tugas yang statusnya belum selesai dan deadline sudah lewat
  $sql = "SELECT id, title, deadline FROM tasks 
          WHERE user_id = ? AND status != 'selesai' AND deadline < CURDATE()";
  $stmt = $conn->prepare($sql);
  $stmt->execute([$user_id]);
  $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

  $notifications = [];
  foreach ($results as $row) {
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

} catch (PDOException $e) {
  echo json_encode([
    "unread" => 0,
    "notifications" => [],
    "error" => "Database error: " . $e->getMessage()
  ]);
}
