<?php
session_start();
require 'koneksi.php'; // atau sesuaikan file koneksi kamu

$user_id = $_SESSION['user_id'] ?? 1;

$sql = "SELECT id, message, is_read FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT 5";
$stmt = $conn->prepare($sql);
$stmt->execute([$user_id]);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($result);
