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
  <link rel="stylesheet" href="style.css">
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

  <script>
    let timer;
    let time = 25 * 60;
    let mode = 'pomodoro';
    const display = document.getElementById('timerDisplay');
    const music = document.getElementById('bgMusic');

    function setMode(newMode) {
      mode = newMode;
      document.querySelectorAll('.mode-btn').forEach(btn => btn.classList.remove('active'));
      document.querySelector(`.mode-btn[onclick*="${newMode}"]`).classList.add('active');
      resetTimer();
    }

    function startTimer() {
      if (timer) return;
      timer = setInterval(() => {
        if (time <= 0) {
          clearInterval(timer);
          timer = null;
          music.play();
          time = getDefaultTime();
          startTimer();
        } else {
          time--;
          updateDisplay();
        }
      }, 1000);
      music.play();
    }

    function stopTimer() {
      clearInterval(timer);
      timer = null;
      music.pause();
    }

    function resetTimer() {
      stopTimer();
      time = getDefaultTime();
      updateDisplay();
      music.currentTime = 0;
    }

    function getDefaultTime() {
      if (mode === 'pomodoro') return 25 * 60;
      if (mode === 'short') return 5 * 60;
      return 15 * 60;
    }

    function updateDisplay() {
      const minutes = Math.floor(time / 60);
      const seconds = time % 60;
      display.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
    }

    function changeMusic() {
      const selected = document.getElementById('musicSelect').value;
      music.src = `music/${selected}`;
      music.play();
    }

    // Inisialisasi
    changeMusic();
    updateDisplay();
  </script>
</body>
</html>
