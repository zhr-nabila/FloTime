<?php
include 'koneksi.php';
session_start();

$id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

mysqli_query($conn, "UPDATE notifications SET is_read = 1 WHERE id = $id AND user_id = $user_id");
?>
