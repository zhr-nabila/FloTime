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