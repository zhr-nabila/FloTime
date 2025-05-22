

<link rel="stylesheet" href="css/pomodoro.css">
link
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
