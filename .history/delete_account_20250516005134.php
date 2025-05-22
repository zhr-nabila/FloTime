<?php
session_start();
require 'koneksi.php';

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_delete'])) {
    $user_id = $_SESSION['user_id'];
    
    // Mulai transaction
    $conn->begin_transaction();
    
    try {
        // 1. Hapus semua todo user
        $stmt1 = $conn->prepare("DELETE FROM todos WHERE user_id = ?");
        $stmt1->bind_param("i", $user_id);
        $stmt1->execute();
        
        // 2. Hapus user
        $stmt2 = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt2->bind_param("i", $user_id);
        $stmt2->execute();
        
        $conn->commit();
        
        // Hapus session dan redirect
        session_unset();
        session_destroy();
        header("Location: login.php?account_deleted=1");
        exit();
        
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['error'] = "Gagal menghapus akun: " . $e->getMessage();
        header("Location: todo.php");
        exit();
    }
}

header("Location: todo.php");
exit();