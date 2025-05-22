<?php
include 'koneksi.php';
session_start();
$user_id = $_SESSION['user_id'];
mysqli_query($conn, "UPDATE notifications SET is_read = 1 WHERE user_id = $user_id");
