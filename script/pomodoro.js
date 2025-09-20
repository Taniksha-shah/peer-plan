let timer;
let isRunning = false;
let sessionTime = 25 * 60; // Default session time in seconds
let breakTime = 5 * 60;    // Default break time in seconds
let longBreakTime = 15 * 60; // Long break time in seconds
let timeRemaining = sessionTime;
let currentPhase = 'session';
let sessionCount = 0;
let totalSessions = 3;

// DOM Elements - All IDs must match your HTML
const timerDisplay = document.getElementById('main-timer-display');
const sessionLabel = document.getElementById('timer-status');
const startBtn = document.getElementById('startBtn');
const stopBtn = document.getElementById('stopBtn');
const resetBtn = document.getElementById('resetBtn');

// Settings Elements
const sessionLengthInput = document.getElementById('session-length');
const shortBreakInput = document.getElementById('short-break');
const longBreakInput = document.getElementById('long-break');
const sessionCountInput = document.getElementById('sessionCount');
const saveSettingsBtn = document.getElementById('save-settings-btn');

function updateDisplay() {
    const minutes = Math.floor(timeRemaining / 60).toString().padStart(2, '0');
    const seconds = Math.floor(timeRemaining % 60).toString().padStart(2, '0');
    if (timerDisplay) {
        timerDisplay.textContent = `${minutes}:${seconds}`;
        document.title = `${minutes}:${seconds} - ${currentPhase === 'session' ? 'Study' : 'Break'}`;
    }
}

function startTimer() {
    if (isRunning) return;
    isRunning = true;
    startBtn.textContent = 'Continue';
    timer = setInterval(() => {
        timeRemaining--;
        updateDisplay();
        if (timeRemaining <= 0) {
            clearInterval(timer);
            // playAudioNotification(); // Uncomment this line if you add an audio file
            nextPhase();
        }
    }, 1000);
    saveTimerState('start');
}

function stopTimer() {
    if (!isRunning) return;
    isRunning = false;
    clearInterval(timer);
    startBtn.textContent = 'Resume';
    saveTimerState('stop');
}

function resetTimer() {
    stopTimer();
    currentPhase = 'session';
    timeRemaining = sessionTime;
    sessionCount = 0;
    startBtn.textContent = 'Start';
    sessionLabel.textContent = `Session`;
    updateDisplay();
    saveTimerState('reset');
}

function nextPhase() {
    if (currentPhase === 'session') {
        sessionCount++;
        if (sessionCount >= totalSessions) {
            currentPhase = 'longBreak';
            timeRemaining = longBreakTime;
        } else {
            currentPhase = 'shortBreak';
            timeRemaining = breakTime;
        }
        sessionLabel.textContent = currentPhase === 'longBreak' ? 'Long Break' : 'Short Break';
    } else {
        currentPhase = 'session';
        timeRemaining = sessionTime;
        sessionLabel.textContent = `Session`;
    }
    updateDisplay();
    startTimer();
}

// Function to save the timer state to session storage for synchronization
function saveTimerState(action) {
    const state = {
        action,
        remaining: timeRemaining,
        phase: currentPhase,
        sessionsDone: sessionCount,
        totalSessions: totalSessions,
        startTime: new Date().getTime(),
        pomodoroSettings: {
            sessionLength: sessionTime,
            shortBreak: breakTime,
            longBreak: longBreakTime
        }
    };
    sessionStorage.setItem('pomodoroTimerState', JSON.stringify(state));
}

// Event Listeners
if(startBtn) startBtn.addEventListener('click', startTimer);
if(stopBtn) stopBtn.addEventListener('click', stopTimer);
if(resetBtn) resetBtn.addEventListener('click', resetTimer);

// Save settings to database via AJAX
if(saveSettingsBtn) {
    saveSettingsBtn.addEventListener('click', () => {
        const data = {
            session_length: sessionLengthInput.value,
            short_break: shortBreakInput.value,
            long_break: longBreakInput.value,
            number_of_sessions: sessionCountInput.value
        };

        fetch('save_pomodoro_settings.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            if (result.status === 'success') {
                alert('Settings saved successfully!');
                sessionTime = data.session_length * 60;
                breakTime = data.short_break * 60;
                longBreakTime = data.long_break * 60;
                totalSessions = data.number_of_sessions;
                resetTimer();
            } else {
                alert('Error saving settings.');
            }
        });
    });
}

// Load settings from database on page load
document.addEventListener('DOMContentLoaded', () => {
    fetch('get_pomodoro_settings.php')
    .then(response => response.json())
    .then(data => {
        if (data) {
            sessionLengthInput.value = data.session_length;
            shortBreakInput.value = data.short_break_length;
            longBreakInput.value = data.long_break_length;
            sessionCountInput.value = data.number_of_sessions;
            
            sessionTime = data.session_length * 60;
            breakTime = data.short_break_length * 60;
            longBreakTime = data.long_break_length * 60;
            totalSessions = data.number_of_sessions;
            timeRemaining = sessionTime;
        }
        updateDisplay();
        
        const savedState = JSON.parse(sessionStorage.getItem('pomodoroTimerState'));
        if (savedState && savedState.action === 'start') {
            const elapsedTime = (new Date().getTime() - savedState.startTime) / 1000;
            timeRemaining = savedState.remaining - elapsedTime;
            currentPhase = savedState.phase;
            sessionCount = savedState.sessionsDone;
            sessionLabel.textContent = savedState.phase === 'session' ? 'Session' : 'Break';
            
            if (timeRemaining > 0) {
                startTimer();
            } else {
                nextPhase();
            }
        }
    });
});