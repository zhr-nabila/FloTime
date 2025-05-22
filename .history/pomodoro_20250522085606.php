<?php
session_start();
require 'koneksi.php';

// Check login
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
    <title>Pomodoro Timer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/pomodoro.css">
</head>

<body>
    <!-- sidebar -->
    <?php
    $activePage = 'pomodoro';
    include 'includes/sidebar.php';
    ?>

    <div class="main-content">
        <!-- topbar -->
        <?php
        $pageTitle = "Pomodoro";
        include 'includes/topbar.php';
        ?>

        <div class="container mt-4">
            <div class="pomodoro-container">
                <!-- Timer Display -->
                <div class="timer-box">
                    <div class="timer-display" id="timer">25:00</div>
                    <div class="timer-controls">
                        <button id="startBtn" class="timer-btn">
                            <i class="bi bi-play-fill"></i> Start
                        </button>
                        <button id="pauseBtn" class="timer-btn" disabled>
                            <i class="bi bi-pause-fill"></i> Pause
                        </button>
                        <button id="resetBtn" class="timer-btn">
                            <i class="bi bi-arrow-counterclockwise"></i> Reset
                        </button>
                    </div>
                </div>

                <!-- Mode Selection -->
                <div class="mode-selection">
                    <div class="mode-option active" data-mode="pomodoro" data-time="25">
                        <i class="bi bi-alarm"></i>
                        <span>Pomodoro</span>
                    </div>
                    <div class="mode-option" data-mode="shortBreak" data-time="5">
                        <i class="bi bi-cup-hot"></i>
                        <span>Short Break</span>
                    </div>
                    <div class="mode-option" data-mode="longBreak" data-time="15">
                        <i class="bi bi-cup-straw"></i>
                        <span>Long Break</span>
                    </div>
                </div>

                <!-- Custom Time Settings -->
                <div class="custom-settings">
                    <h5><i class="bi bi-gear"></i> Custom Timer</h5>
                    <div class="time-inputs">
                        <div class="input-group">
                            <label for="pomodoroTime">Pomodoro (min):</label>
                            <input type="number" id="pomodoroTime" min="1" max="60" value="25">
                        </div>
                        <div class="input-group">
                            <label for="shortBreakTime">Short Break (min):</label>
                            <input type="number" id="shortBreakTime" min="1" max="30" value="5">
                        </div>
                        <div class="input-group">
                            <label for="longBreakTime">Long Break (min):</label>
                            <input type="number" id="longBreakTime" min="1" max="60" value="15">
                        </div>
                    </div>
                    <button id="saveSettings" class="settings-btn">
                        <i class="bi bi-check-circle"></i> Save Settings
                    </button>
                </div>

                <!-- Music Player -->
                <div class="music-player">
                    <div class="music-header">
                        <h5><i class="bi bi-music-note-beamed"></i> Focus Music</h5>
                        <button id="musicToggle" class="music-toggle">
                            <i class="bi bi-music-player"></i> Music
                        </button>
                    </div>
                    
                    <!-- Music Albums -->
                    <div class="music-albums">
                        <!-- Lofi Album -->
                        <div class="album active" data-album="lofi">
                            <div class="album-cover" style="background-image: url('assets/f48bedeb-1b55-4b4b-93a5-074935d8a4e7.jpg')">
                                <div class="album-overlay">
                                    <i class="bi bi-play-circle"></i>
                                </div>
                            </div>
                            <div class="album-info">
                                <h6>Lofi Beats</h6>
                                <p>Chill vibes for focus</p>
                            </div>
                        </div>
                        
                        <!-- Nature Sounds -->
                        <div class="album" data-album="nature">
                            <div class="album-cover" style="background-image: url('assets/0169afb0-39d8-486b-94e7-1f0b1ac82fad.jpg')">
                                <div class="album-overlay">
                                    <i class="bi bi-play-circle"></i>
                                </div>
                            </div>
                            <div class="album-info">
                                <h6>Chill Work </h6>
                                <p>Calming natural ambiance</p>
                            </div>
                        </div>
                        
                        <!-- Classical -->
                        <div class="album" data-album="classical">
                            <div class="album-cover" style="background-image: url('https://source.unsplash.com/random/300x300/?classical,music')">
                                <div class="album-overlay">
                                    <i class="bi bi-play-circle"></i>
                                </div>
                            </div>
                            <div class="album-info">
                                <h6>Classical</h6>
                                <p>Timeless focus music</p>
                            </div>
                        </div>
                        
                        <!-- White Noise -->
                        <div class="album" data-album="whiteNoise">
                            <div class="album-cover" style="background-image: url('https://source.unsplash.com/random/300x300/?rain,noise')">
                                <div class="album-overlay">
                                    <i class="bi bi-play-circle"></i>
                                </div>
                            </div>
                            <div class="album-info">
                                <h6>White Noise</h6>
                                <p>Consistent background</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Audio Player (hidden, controlled by JS) -->
                    <audio id="audioPlayer" loop></audio>
                </div>
            </div>
        </div>
    </div>

    <!-- Audio for timer completion -->
    <audio id="timerSound" src="sounds/timer_complete.mp3" preload="auto"></audio>

    <script src="js/pomodoro.js"></script>
</body>

</html>