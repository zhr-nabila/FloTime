<?php
session_start();
require 'koneksi.php';

// Cek login
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$email = $_SESSION['email'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pomodoro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .timer-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 40px;
            background: var(--c10);
            border-radius: 20px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            text-align: center;
        }

        .timer {
            font-size: 72px;
            font-weight: bold;
            color: var(--c7);
        }

        .controls button {
            margin: 10px 5px;
        }

        .mode-buttons .btn {
            margin: 5px;
        }

        .active-mode {
            border: 2px solid var(--c7);
            background: var(--c8);
            color: white !important;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<?php
$activePage = 'pomodoro';
include 'includes/sidebar.php';
?>

<div class="main-content">
    <!-- Topbar -->
    <?php
    $pageTitle = "Pomodoro Timer";
    include 'includes/topbar.php';
    ?>

    <div class="container">
        <div class="timer-container">
            <h2 class="mb-4">Pomodoro Timer</h2>

            <div class="mode-buttons mb-3">
                <button class="btn btn-outline-danger rounded-pill fw-semibold" onclick="setMode('pomodoro')">Pomodoro</button>
                <button class="btn btn-outline-primary rounded-pill fw-semibold" onclick="setMode('short')">Istirahat Pendek</button>
                <button class="btn btn-outline-success rounded-pill fw-semibold" onclick="setMode('long')">Istirahat Panjang</button>
            </div>

            <div class="timer" id="timer">25:00</div>

            <div class="controls mt-4">
                <button class="btn btn-success rounded-5 fw-semibold" onclick="startTimer()">Mulai</button>
                <button class="btn btn-warning rounded-5 fw-semibold" onclick="pauseTimer()">Jeda</button>
                <button class="btn btn-danger rounded-5 fw-semibold" onclick="resetTimer()">Reset</button>
            </div>
        </div>
    </div>
</div>

<script>
    
</script>

</body>
</html>
