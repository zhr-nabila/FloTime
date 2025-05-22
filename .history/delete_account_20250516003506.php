<?php
session_start();
require 'koneksi.php';

// Cek apakah user sudah login
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Jika konfirmasi diterima
if (isset($_GET['confirm']) && $_GET['confirm'] === 'true') {
    // Mulai transaction
    $conn->begin_transaction();
    
    try {
        // 1. Hapus semua todo milik user
        $stmt1 = $conn->prepare("DELETE FROM todos WHERE user_id = ?");
        $stmt1->bind_param("i", $user_id);
        $stmt1->execute();
        
        // 2. Hapus user dari database
        $stmt2 = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt2->bind_param("i", $user_id);
        $stmt2->execute();
        
        // Commit transaction jika semua query berhasil
        $conn->commit();
        
        // Hapus session dan redirect ke halaman login
        session_unset();
        session_destroy();
        header("Location: login.php?account_deleted=1");
        exit();
        
    } catch (Exception $e) {
        // Rollback jika terjadi error
        $conn->rollback();
        $_SESSION['error'] = "Gagal menghapus akun: " . $e->getMessage();
        header("Location: settings.php");
        exit();
    }
}

// Tampilkan halaman konfirmasi
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hapus Akun</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'includes/sidebar.php'; ?>
    
    <div class="main-content">
        <?php include 'includes/topbar.php'; ?>
        
        <div class="container mt-4">
            <div class="card shadow border-danger" style="max-width: 600px; margin: 0 auto;">
                <div class="card-header bg-danger text-white">
                    <h4 class="mb-0"><i class="bi bi-exclamation-triangle-fill"></i> Hapus Akun</h4>
                </div>
                <div class="card-body text-center">
                    <div class="alert alert-danger">
                        <h5 class="alert-heading">Apakah Anda yakin?</h5>
                        <p>Semua data Anda akan dihapus secara permanen dan tidak dapat dikembalikan.</p>
                    </div>
                    
                    <div class="d-flex justify-content-center gap-3">
                        <a href="settings.php" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Batal
                        </a>
                        <a href="delete_account.php?confirm=true" class="btn btn-danger">
                            <i class="bi bi-trash-fill"></i> Ya, Hapus Akun
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>