<?php
session_start();
include 'koneksi.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User tidak login']);
    exit;
}

$user_id = $_SESSION['user_id'];

// Start transaction
mysqli_begin_transaction($conn);

try {
    // 1. Delete all user's todos first
    $deleteTodos = mysqli_query($conn, "DELETE FROM todos WHERE user_id = '$user_id'");
    if (!$deleteTodos) {
        throw new Exception("Gagal menghapus data todos");
    }
    
    // 2. Delete user account
    $deleteUser = mysqli_query($conn, "DELETE FROM users WHERE id = '$user_id'");
    if (!$deleteUser) {
        throw new Exception("Gagal menghapus akun");
    }
    
    // Commit transaction if both queries succeed
    mysqli_commit($conn);
    
    // Destroy session
    session_unset();
    session_destroy();
    
    echo json_encode(['status' => 'success']);
    exit;
    
} catch (Exception $e) {
    // Rollback transaction on error
    mysqli_rollback($conn);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    exit;
}