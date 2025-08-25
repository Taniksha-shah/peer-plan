document.addEventListener("DOMContentLoaded", () => {
  const timerDisplay = document.getElementById("timer");
  const timerTypeSelect = document.getElementById("timerType");
  const sessionCountInput = document.getElementById("sessionCount");
  const sessionLabel = document.getElementById("sessionLabel");
  const progressDisplay = document.getElementById("progressDisplay");

  const startBtn = document.getElementById("startBtn");
  const stopBtn = document.getElementById("stopBtn");
  const resetBtn = document.getElementById("resetBtn");

  let studyMinutes = 25;
  let breakMinutes = 5;
  let sessions = parseInt(sessionCountInput.value);
  let currentSession = 1;
  let completedSessions = 0;

  let isStudyTime = true;
  let timer;
  let timeLeft = studyMinutes * 60;

  // Format time
  function formatTime(seconds) {
    const m = Math.floor(seconds / 60);
    const s = seconds % 60;
    return `${m.toString().padStart(2, "0")}:${s.toString().padStart(2, "0")}`;
  }

  // Update Timer Display
  function updateDisplay() {
    timerDisplay.textContent = formatTime(timeLeft);
    sessionLabel.textContent = isStudyTime
      ? `📖 Study Session ${currentSession} of ${sessions}`
      : `☕ Break Time (${breakMinutes} min)`;
    progressDisplay.textContent = `🎯 Sessions Completed: ${completedSessions}`;
  }

  // Start Timer
  function startTimer() {
    if (timer) return; // avoid multiple intervals
    timer = setInterval(() => {
      timeLeft--;
      updateDisplay();

      if (timeLeft <= 0) {
        clearInterval(timer);
        timer = null;

        if (isStudyTime) {
          completedSessions++;
          if (currentSession < sessions) {
            isStudyTime = false;
            timeLeft = breakMinutes * 60;
            updateDisplay();
            startTimer();
          } else {
            sessionLabel.textContent = "🎉 All Sessions Completed!";
          }
        } else {
          isStudyTime = true;
          currentSession++;
          timeLeft = studyMinutes * 60;
          updateDisplay();
          startTimer();
        }
      }
    }, 1000);
  }

  // Pause Timer
  function pauseTimer() {
    clearInterval(timer);
    timer = null;
  }

  // Reset Timer
  function resetTimer() {
    clearInterval(timer);
    timer = null;
    const [study, brk] = timerTypeSelect.value.split("-").map(Number);
    studyMinutes = study;
    breakMinutes = brk;
    sessions = parseInt(sessionCountInput.value);
    currentSession = 1;
    completedSessions = 0;
    isStudyTime = true;
    timeLeft = studyMinutes * 60;
    updateDisplay();
  }

  // Change settings
  timerTypeSelect.addEventListener("change", resetTimer);
  sessionCountInput.addEventListener("change", resetTimer);

  // Button actions
  startBtn.addEventListener("click", startTimer);
  stopBtn.addEventListener("click", pauseTimer);
  resetBtn.addEventListener("click", resetTimer);

  // Initial setup
  resetTimer();
});