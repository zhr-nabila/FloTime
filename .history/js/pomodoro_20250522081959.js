let timer;
let isRunning = false;
let currentMode = 'pomodoro';
let timeLeft = 25 * 60;
const timerDisplay = document.getElementById("timer");
const startBtn = document.getElementById("startBtn");
const resetBtn = document.getElementById("resetBtn");
const musicBtn = document.getElementById("musicBtn");
const musicPlayer = document.getElementById("musicPlayer");
const pomodoroBox = document.getElementById("pomodoroBox");

const modeButtons = document.querySelectorAll(".mode-btn");

const modeDurations = {
  pomodoro: 25 * 60,
  short: 5 * 60,
  long: 15 * 60
};

const modeColors = {
  pomodoro: "#ff6b6b",
  short: "#4ecdc4",
  long: "#1a535c"
};

function updateDisplay() {
  const minutes = Math.floor(timeLeft / 60).toString().padStart(2, '0');
  const seconds = (timeLeft % 60).toString().padStart(2, '0');
  timerDisplay.textContent = `${minutes}:${seconds}`;
}

function switchMode(mode) {
  currentMode = mode;
  timeLeft = modeDurations[mode];
  updateDisplay();
  pomodoroBox.style.backgroundColor = modeColors[mode];

  modeButtons.forEach(btn => {
    btn.classList.remove("active");
    if (btn.dataset.mode === mode) btn.classList.add("active");
  });

  stopTimer();
}

function startTimer() {
  if (!isRunning) {
    isRunning = true;
    startBtn.textContent = "Pause";
    timer = setInterval(() => {
      if (timeLeft > 0) {
        timeLeft--;
        updateDisplay();
      } else {
        stopTimer();
      }
    }, 1000);
  } else {
    stopTimer();
  }
}

function stopTimer() {
  isRunning = false;
  startBtn.textContent = "Start";
  clearInterval(timer);
}

function resetTimer() {
  stopTimer();
  timeLeft = modeDurations[currentMode];
  updateDisplay();
}

function toggleMusic() {
  if (musicPlayer.paused) {
    musicPlayer.play();
    musicBtn.textContent = "🔇 Mute";
  } else {
    musicPlayer.pause();
    musicBtn.textContent = "🎵 Music";
  }
}

modeButtons.forEach(btn => {
  btn.addEventListener("click", () => {
    switchMode(btn.dataset.mode);
  });
});

startBtn.addEventListener("click", startTimer);
resetBtn.addEventListener("click", resetTimer);
musicBtn.addEventListener("click", toggleMusic);

updateDisplay();
