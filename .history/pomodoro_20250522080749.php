<?php
$pageTitle = 'Pomodoro';
$activePage = 'pomodoro';
include 'include/sidebar.php';
include 'include/topbar.php';
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
  <?php $activePage = 'pomodoro'; $pageTitle = 'Pomodoro'; include 'include/sidebar.php'; include 'include/topbar.php'; ?>

  <main class="container">
    <div class="timer" id="timerDisplay">25:00</div>

    <div class="modes">
      <button class="mode-btn active" onclick="setMode('pomodoro')">Pomodoro</button>
      <button class="mode-btn" onclick="setMode('short')">Short Break</button>
      <button class="mode-btn" onclick="setMode('long')">Long Break</button>
    </div>

    <div class="controls">
      <button class="start" onclick="startTimer()">Mulai</button>
      <button class="stop" onclick="stopTimer()">Berhenti</button>
      <button onclick="resetTimer()">Reset</button>
    </div>

    <div class="music">
      <label for="musicSelect">Pilih Musik:</label>
      <select id="musicSelect" onchange="changeMusic()">
        <option value="lofi1.mp3">Lofi Focus</option>
        <option value="lofi2.mp3">Calm Breeze</option>
        <option value="lofi3.mp3">Deep Chill</option>
        <option value="lofi4.mp3">Study Vibes</option>
      </select>
    </div>

    <audio id="bgMusic" loop></audio>
  </main>

  <script sr></script>
</body>
</html>
