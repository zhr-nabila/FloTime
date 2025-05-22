<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Custom Pomodoro Timer with Music Albums</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      text-align: center;
      background-color: #2c3e50;
      color: white;
      padding: 20px;
    }
    .timer {
      font-size: 48px;
      margin: 20px 0;
    }
    .controls input {
      width: 50px;
      margin: 0 5px;
    }
    .albums {
      margin-top: 40px;
    }
    .album {
      margin: 10px 0;
      background-color: #34495e;
      padding: 15px;
      border-radius: 10px;
    }
    audio {
      width: 100%;
      margin-top: 10px;
    }
    .hidden {
      display: none;
    }
  </style>
</head>
<body>
  <h1>Pomodoro Timer</h1>
  <div>
    <label>Pomodoro: <input type="number" id="pomodoroInput" value="25" /></label>
    <label>Short Break: <input type="number" id="shortBreakInput" value="5" /></label>
    <label>Long Break: <input type="number" id="longBreakInput" value="15" /></label>
  </div>
  <div class="timer" id="timer">25:00</div>
  <div>
    <button onclick="startTimer('pomodoro')">Start Pomodoro</button>
    <button onclick="startTimer('shortBreak')">Start Short Break</button>
    <button onclick="startTimer('longBreak')">Start Long Break</button>
    <button onclick="resetTimer()">Reset</button>
  </div>

  <h2>Music Albums</h2>
  <div>
    <button onclick="toggleAlbums()">🎵 Music</button>
  </div>
  <div id="albumContainer" class="albums hidden">
    <div class="album">
      <h3>LoFi Focus</h3>
      <audio controls src="music/lofi1.mp3"></audio>
      <audio controls src="music/lofi2.mp3"></audio>
    </div>
    <div class="album">
      <h3>Instrumental Chill</h3>
      <audio controls src="music/chill1.mp3"></audio>
      <audio controls src="music/chill2.mp3"></audio>
    </div>
  </div>

  <audio id="alertSound" src="sounds/alert.mp3"></audio>

  <script>
    let timerInterval;
    let currentTime = 0;

    function startTimer(type) {
      clearInterval(timerInterval);

      const times = {
        pomodoro: parseInt(document.getElementById('pomodoroInput').value) * 60,
        shortBreak: parseInt(document.getElementById('shortBreakInput').value) * 60,
        longBreak: parseInt(document.getElementById('longBreakInput').value) * 60
      };

      currentTime = times[type];
      updateDisplay();

      timerInterval = setInterval(() => {
        currentTime--;
        updateDisplay();
        if (currentTime <= 0) {
          clearInterval(timerInterval);
          document.getElementById('alertSound').play();
        }
      }, 1000);
    }

    function updateDisplay() {
      const minutes = Math.floor(currentTime / 60).toString().padStart(2, '0');
      const seconds = (currentTime % 60).toString().padStart(2, '0');
      document.getElementById('timer').textContent = `${minutes}:${seconds}`;
    }

    function resetTimer() {
      clearInterval(timerInterval);
      document.getElementById('timer').textContent = '00:00';
    }

    function toggleAlbums() {
      const albumDiv = document.getElementById('albumContainer');
      albumDiv.classList.toggle('hidden');
    }
  </script>
</body>
</html>
