let timer;
  let totalSeconds;
  let currentMode = 'pomodoro';
  let durations = {
    pomodoro: 25 * 60,
    short: 5 * 60,
    long: 15 * 60
  };
  let isRunning = false;

  function updateDisplay() {
    let minutes = Math.floor(totalSeconds / 60).toString().padStart(2, '0');
    let seconds = (totalSeconds % 60).toString().padStart(2, '0');
    document.getElementById('time').textContent = `${minutes}:${seconds}`;
  }

  function setMode(mode) {
    currentMode = mode;
    totalSeconds = durations[mode];
    document.getElementById('timer-label').textContent =
      mode === 'pomodoro' ? 'Pomodoro' : mode === 'short' ? 'Short Break' : 'Long Break';
    resetTimer();
  }

  function startTimer() {
    if (isRunning) return;
    isRunning = true;
    document.getElementById("bg-music").muted = false;
    timer = setInterval(() => {
      if (totalSeconds > 0) {
        totalSeconds--;
        updateDisplay();
      } else {
        clearInterval(timer);
        isRunning = false;
        startTimer(); // repeat
      }
    }, 1000);
  }

  function pauseTimer() {
    clearInterval(timer);
    isRunning = false;
  }

  function resetTimer() {
    clearInterval(timer);
    isRunning = false;
    totalSeconds = durations[currentMode];
    updateDisplay();
  }

  function changeMusic() {
    const music = document.getElementById('music-select').value;
    const player = document.getElementById('bg-music');
    player.src = `audio/${music}`;
    player.play();
  }

  // Init
  setMode('pomodoro');