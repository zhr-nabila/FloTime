let timer;
    let timeLeft = 1500; // default 25 minutes
    let isRunning = false;
    let currentMode = 'pomodoro'; // modes: pomodoro, short, long

    function updateDisplay() {
        const minutes = Math.floor(timeLeft / 60).toString().padStart(2, '0');
        const seconds = (timeLeft % 60).toString().padStart(2, '0');
        document.getElementById('timer').innerText = `${minutes}:${seconds}`;
    }

    function startTimer() {
        if (isRunning) return;
        isRunning = true;
        timer = setInterval(() => {
            if (timeLeft <= 0) {
                clearInterval(timer);
                isRunning = false;
                alert("Waktu Habis!");
                return;
            }
            timeLeft--;
            updateDisplay();
        }, 1000);
    }

    function pauseTimer() {
        clearInterval(timer);
        isRunning = false;
    }

    function resetTimer() {
        pauseTimer();
        if (currentMode === 'pomodoro') timeLeft = 1500;
        else if (currentMode === 'short') timeLeft = 300;
        else if (currentMode === 'long') timeLeft = 900;
        updateDisplay();
    }

    function setMode(mode) {
        pauseTimer();
        currentMode = mode;

        document.querySelectorAll('.mode-buttons .btn').forEach(btn => {
            btn.classList.remove('active-mode');
        });

        if (mode === 'pomodoro') {
            timeLeft = 1500;
            document.querySelectorAll('.mode-buttons .btn')[0].classList.add('active-mode');
        } else if (mode === 'short') {
            timeLeft = 300;
            document.querySelectorAll('.mode-buttons .btn')[1].classList.add('active-mode');
        } else if (mode === 'long') {
            timeLeft = 900;
            document.querySelectorAll('.mode-buttons .btn')[2].classList.add('active-mode');
        }

        updateDisplay();
    }

    // Inisialisasi pertama kali
    updateDisplay();
    setMode('pomodoro');