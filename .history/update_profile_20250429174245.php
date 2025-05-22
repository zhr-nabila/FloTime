<?php
session_start();
require 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$name = $_POST['name'];
$photoName = null;

// Cek apakah upload file dilakukan
if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
    $targetDir = "uploads/";
    $photoName = time() . "_" . basename($_FILES["photo"]["name"]);
    $targetFile = $targetDir . $photoName;

    // Cek dan upload file
    if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFile)) {
        // berhasil upload
    } else {
        die("Gagal mengupload gambar.");
    }
}

// Update ke database
if ($photoName) {
    $stmt = $conn->prepare("UPDATE users SET name = ?, photo = ? WHERE id = ?");
    $stmt->bind_param("ssi", $name, $photoName, $user_id);
} else {
    $stmt = $conn->prepare("UPDATE users SET name = ? WHERE id = ?");
    $stmt->bind_param("si", $name, $user_id);
}

$stmt->execute();
$stmt->close();

header("Location: profile.php");
exit();
