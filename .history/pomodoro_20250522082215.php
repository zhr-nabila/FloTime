<?php
$pageTitle = 'Pomodoro';
$activePage = 'pomodoro';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pomodoro</title>
  <link rel="stylesheet" href="css/pomodoro.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="include/sidebar.css">
  <link rel="stylesheet" href="include/topbar.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>
   <!-- sidebar -->
    <?php
    $activePage = 'todo';
    include 'includes/sidebar.php';
    ?>

    <div class="main-content">
        <!-- topbar -->
        <?php
        $pageTitle = "Todo List";
        include 'includes/topbar.php';
        ?>
  <?php
include 'include/topbar.php';
include 'include/sidebar.php';
?>

<link rel="stylesheet" href="css/pomodoro.css">
<div class="pomodoro-container">
  <div class="pomodoro-box" id="pomodoroBox">
    <div class="mode-buttons">
      <button class="mode-btn active" data-mode="pomodoro">Pomodoro</button>
      <button class="mode-btn" data-mode="short">Short Break</button>
      <button class="mode-btn" data-mode="long">Long Break</button>
    </div>
    <div class="timer" id="timer">25:00</div>
    <div class="controls">
      <button id="startBtn">Start</button>
      <button id="resetBtn">Reset</button>
      <button id="musicBtn">🎵 Music</button>
    </div>
  </div>
</div>

<audio id="musicPlayer" loop>
  <source src="music/lofi1.mp3" type="audio/mp3">
  Your browser does not support the audio element.
</audio>

<script src="js/pomodoro.js"></script>

  <script src="js/pomodoro.js"></script>
</body>
</html>
