<?php
session_start();
require 'koneksi.php';

$user_id = $_SESSION['user_id'] ?? 1;

$sql = "SELECT COUNT(*) as count FROM notifications WHERE user_id = ? AND is_read = 0";
$stmt = $conn->prepare($sql);
$stmt->execute([$user_id]);
$count = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode($count);
