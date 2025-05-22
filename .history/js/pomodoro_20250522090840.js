document.addEventListener('DOMContentLoaded', function() {
    // DOM Elements
    const timerDisplay = document.getElementById('timer');
    const startBtn = document.getElementById('startBtn');
    const pauseBtn = document.getElementById('pauseBtn');
    const resetBtn = document.getElementById('resetBtn');
    const modeOptions = document.querySelectorAll('.mode-option');
    const pomodoroTimeInput = document.getElementById('pomodoroTime');
    const shortBreakTimeInput = document.getElementById('shortBreakTime');
    const longBreakTimeInput = document.getElementById('longBreakTime');
    const saveSettingsBtn = document.getElementById('saveSettings');
    const musicToggle = document.getElementById('musicToggle');
    const audioPlayer = document.getElementById('audioPlayer');
    const timerSound = document.getElementById('timerSound');
    const albums = document.querySelectorAll('.album');

    // Timer Variables
    let timer;
    let timeLeft = 25 * 60; // 25 minutes in seconds
    let isRunning = false;
    let currentMode = 'pomodoro';
    let settings = {
        pomodoro: 25,
        shortBreak: 5,
        longBreak: 15
    };

    // Music Variables
    let isMusicPlaying = false;
    const musicTracks = {
        Lofi Beats: [
            { title: 'Chill Vibes', src: 'music/Dream Drifter.mp3' },
            { title: 'Coffee Shop', src: 'music/Dream Drifter (1).mp3' },
            { title: 'Rainy Day', src: 'music/Dreaming in Soft Tones.mp3' }
        ],
        nature: [
            { title: 'Forest Sounds', src: 'music/Coffee Stains and Daydreams (1).mp3' },
            { title: 'Ocean Waves', src: 'music/Coffee Stains and Daydreams.mp3' },
            { title: 'Rainfall', src: 'music/Candlelit Daydreams.mp3' }
        ],
        classical: [
            { title: 'Mozart', src: 'music/Velvet Reverie (1).mp3' },
            { title: 'Beethoven', src: 'music/Velvet Reverie (2).mp3' },
            { title: 'Bach', src: 'music/Velvet Reverie (3).mp3' }
        ],
        whiteNoise: [
            { title: 'White Noise', src: 'music/Under Neon Skies.mp3' },
            { title: 'Pink Noise', src: 'music/Under Neon Skies (1).mp3' },
            { title: 'Brown Noise', src: 'music/study at night.mp3' }
        ]
    };
    let currentAlbum = 'lofi';
    let currentTrackIndex = 0;

    // Initialize Timer
    function init() {
        loadSettings();
        updateTimerDisplay();
        setupEventListeners();
    }

    // Load settings from localStorage
    function loadSettings() {
        const savedSettings = localStorage.getItem('pomodoroSettings');
        if (savedSettings) {
            settings = JSON.parse(savedSettings);
            pomodoroTimeInput.value = settings.pomodoro;
            shortBreakTimeInput.value = settings.shortBreak;
            longBreakTimeInput.value = settings.longBreak;
        }
    }

    // Save settings to localStorage
    function saveSettings() {
        settings = {
            pomodoro: parseInt(pomodoroTimeInput.value),
            shortBreak: parseInt(shortBreakTimeInput.value),
            longBreak: parseInt(longBreakTimeInput.value)
        };
        localStorage.setItem('pomodoroSettings', JSON.stringify(settings));
        
        // Show confirmation
        const btnText = saveSettingsBtn.innerHTML;
        saveSettingsBtn.innerHTML = '<i class="bi bi-check2"></i> Saved!';
        saveSettingsBtn.style.backgroundColor = '#4CAF50';
        
        setTimeout(() => {
            saveSettingsBtn.innerHTML = btnText;
            saveSettingsBtn.style.backgroundColor = '';
        }, 2000);
        
        // Update timer if current mode is changed
        if (currentMode === 'pomodoro') {
            timeLeft = settings.pomodoro * 60;
        } else if (currentMode === 'shortBreak') {
            timeLeft = settings.shortBreak * 60;
        } else if (currentMode === 'longBreak') {
            timeLeft = settings.longBreak * 60;
        }
        
        updateTimerDisplay();
    }

    // Update timer display
    function updateTimerDisplay() {
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        timerDisplay.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    }

    // Start timer
    function startTimer() {
        if (!isRunning) {
            isRunning = true;
            startBtn.disabled = true;
            pauseBtn.disabled = false;
            
            timer = setInterval(() => {
                timeLeft--;
                updateTimerDisplay();
                
                if (timeLeft <= 0) {
                    clearInterval(timer);
                    timerComplete();
                }
            }, 1000);
        }
    }

    // Pause timer
    function pauseTimer() {
        if (isRunning) {
            clearInterval(timer);
            isRunning = false;
            startBtn.disabled = false;
            pauseBtn.disabled = true;
        }
    }

    // Reset timer
    function resetTimer() {
        pauseTimer();
        
        if (currentMode === 'pomodoro') {
            timeLeft = settings.pomodoro * 60;
        } else if (currentMode === 'shortBreak') {
            timeLeft = settings.shortBreak * 60;
        } else if (currentMode === 'longBreak') {
            timeLeft = settings.longBreak * 60;
        }
        
        updateTimerDisplay();
    }

    // Timer completed
    function timerComplete() {
        isRunning = false;
        startBtn.disabled = false;
        pauseBtn.disabled = true;
        
        // Play completion sound
        timerSound.play();
        
        // Show notification
        if (Notification.permission === 'granted') {
            new Notification('Pomodoro Timer', {
                body: `${currentMode === 'pomodoro' ? 'Time for a break!' : 'Time to get back to work!'}`,
                icon: 'icon.png'
            });
        }
        
        // Switch mode automatically
        if (currentMode === 'pomodoro') {
            // After pomodoro, alternate between short and long breaks
            const lastBreak = localStorage.getItem('lastBreak') || 'shortBreak';
            const nextBreak = lastBreak === 'shortBreak' ? 'longBreak' : 'shortBreak';
            
            switchMode(nextBreak);
            localStorage.setItem('lastBreak', nextBreak);
        } else {
            // After break, go back to pomodoro
            switchMode('pomodoro');
        }
    }

    // Switch timer mode
    function switchMode(mode) {
        currentMode = mode;
        
        // Update active mode button
        modeOptions.forEach(option => {
            option.classList.remove('active');
            if (option.dataset.mode === mode) {
                option.classList.add('active');
            }
        });
        
        // Update timer box color based on mode
        const timerBox = document.querySelector('.timer-box');
        timerBox.style.background = 
            mode === 'pomodoro' ? 'linear-gradient(135deg, var(--c7), var(--c8))' :
            mode === 'shortBreak' ? 'linear-gradient(135deg, #4CAF50, #8BC34A)' :
            'linear-gradient(135deg, #2196F3, #03A9F4)';
        
        // Reset timer with new mode time
        resetTimer();
    }

    // Toggle music
    function toggleMusic() {
        if (isMusicPlaying) {
            audioPlayer.pause();
            musicToggle.innerHTML = '<i class="bi bi-music-player"></i> Play Music';
        } else {
            playCurrentTrack();
            musicToggle.innerHTML = '<i class="bi bi-pause"></i> Pause Music';
        }
        isMusicPlaying = !isMusicPlaying;
    }

    // Play current track
    function playCurrentTrack() {
        const tracks = musicTracks[currentAlbum];
        if (tracks && tracks.length > 0) {
            audioPlayer.src = tracks[currentTrackIndex].src;
            audioPlayer.play()
                .catch(e => console.log('Autoplay prevented:', e));
            
            // When track ends, play next one
            audioPlayer.onended = () => {
                currentTrackIndex = (currentTrackIndex + 1) % tracks.length;
                playCurrentTrack();
            };
        }
    }

    // Change album
    function selectAlbum(album) {
        currentAlbum = album;
        currentTrackIndex = 0;
        
        // Update active album
        albums.forEach(a => {
            a.classList.remove('active');
            if (a.dataset.album === album) {
                a.classList.add('active');
            }
        });
        
        // If music is playing, switch to new album
        if (isMusicPlaying) {
            playCurrentTrack();
        }
    }

    // Set up event listeners
    function setupEventListeners() {
        // Timer controls
        startBtn.addEventListener('click', startTimer);
        pauseBtn.addEventListener('click', pauseTimer);
        resetBtn.addEventListener('click', resetTimer);
        
        // Mode selection
        modeOptions.forEach(option => {
            option.addEventListener('click', () => {
                switchMode(option.dataset.mode);
            });
        });
        
        // Settings
        saveSettingsBtn.addEventListener('click', saveSettings);
        
        // Music controls
        musicToggle.addEventListener('click', toggleMusic);
        
        // Album selection
        albums.forEach(album => {
            album.addEventListener('click', () => {
                selectAlbum(album.dataset.album);
            });
        });
        
        // Request notification permission
        if (Notification.permission !== 'granted' && Notification.permission !== 'denied') {
            Notification.requestPermission();
        }
    }

    // Initialize the app
    init();
});