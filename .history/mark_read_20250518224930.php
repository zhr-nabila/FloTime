<?php
session_start();
require 'koneksi.php';

$user_id = $_SESSION['user_id'] ?? 1;

$sql = "UPDATE notifications SET is_read = 1 WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$user_id]);
