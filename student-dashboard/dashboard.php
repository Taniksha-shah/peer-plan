<?php
session_start();

// Redirect to login page if user is not logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>PeerPlan Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="../style/dashboard.css" rel="stylesheet" />
  <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css' rel='stylesheet' />
  <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
  <style>
    /* General card improvements */
.card {
  border: 1px solid #e0e0e0;
  border-radius: 12px;
  overflow: hidden;
  transition: transform 0.2s ease-in-out;
}

.card:hover {
  transform: translateY(-5px);
}

.card-header {
  background-color: #f8f9fa;
  border-bottom: 1px solid #e0e0e0;
  padding: 1rem 1.25rem;
  font-size: 1.1rem;
}

.card-body {
  padding: 1.5rem;
}

/* Pomodoro Timer specific styles */
#pomodoro-timer-display {
  font-family: 'Courier New', Courier, monospace;
  font-weight: bold;
}

/* To-do list specific styles */
#latest-todo-item .d-flex {
  align-items: center;
  gap: 0.5rem;
}

#latest-todo-item input[type="checkbox"] {
  transform: scale(1.2);
}

/* FullCalendar preview size */
#dashboard-calendar-preview .fc .fc-toolbar-title {
  font-size: 1em;
}

#dashboard-calendar-preview .fc .fc-toolbar-chunk:first-child .fc-button-group {
    display: none; /* Hide prev/next buttons for a cleaner look */
}

#dashboard-calendar-preview {
    max-height: 250px;
    margin-bottom: 1rem;
}
</style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-dark navbar-expand-lg" style="background-color: rgb(93, 13, 45);">
  <div class="container-fluid">
    <button class="btn btn-light d-lg-none me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarOffcanvas">
      ☰
    </button>
    <a class="navbar-brand fw-bold" href="index.html">PeerPlan</a>
    <div class="ms-auto d-flex align-items-center gap-3">
      <a class="nav-link text-white d-none d-lg-block" href="about.html">About</a>
      <a href="profile.html" class="btn btn-light d-flex align-items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
          viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
          class="user-pfp" width="32" height="32">
          <path stroke-linecap="round" stroke-linejoin="round"
            d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
        </svg>
        <span class="username"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
      </a>
    </div>
  </div>
</nav>

<!-- Offcanvas Sidebar -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarOffcanvas">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title">Navigation</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body">
    <ul class="nav flex-column">
          <li class="nav-item md-2">
            <a href="calendar.php" class="nav-link text-dark d-flex align-items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                class="me-2 side-bar-item-icon" width="20" height="20">
                <path stroke-linecap="round" stroke-linejoin="round"
                d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                </svg>
            <span>Calendar</span>
            </a>
          </li>

          <li class="nav-item mb-2">
            <a href="todo.php" class="nav-link text-dark d-flex align-items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                class="me-2 side-bar-item-icon" width="20" height="20">
                <path stroke-linecap="round" stroke-linejoin="round"
                d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                </svg>
            <span>To-do list</span>
            </a>
          </li>

          <li class="nav-item mb-2">
            <a href="pomodoro.php" class="nav-link text-dark d-flex align-items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                class="me-2 side-bar-item-icon" width="20" height="20">
                <path stroke-linecap="round" stroke-linejoin="round"
                d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            <span>Pomodoro Timer</span>
            </a>
          </li>

          <li class="nav-item">
            <a href="studyRoom.php" class="nav-link text-dark d-flex align-items-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                class="me-2 side-bar-item-icon" width="20" height="20">
                <path stroke-linecap="round" stroke-linejoin="round"
                d="m15.75 10.5 4.72-4.72a.75.75 0 0 1 1.28.53v11.38a.75.75 0 0 1-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25h-9A2.25 2.25 0 0 0 2.25 7.5v9a2.25 2.25 0 0 0 2.25 2.25Z" />
                </svg>
        <span>Study Rooms</span>
        </a>
          </li>
        </ul>

  </div>
</div>

<!-- Layout: Sidebar + Main -->
<div class="container-fluid">
  <div class="row">
    
    <!-- Sidebar on Large Screens -->
    <aside class="col-lg-2 bg-light d-none d-lg-block p-3 min-vh-100 border-end">
      <ul class="nav flex-column">
          <li class="nav-item md-2">
            <a href="calendar.php" class="nav-link text-dark d-flex align-items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                class="me-2 side-bar-item-icon" width="20" height="20">
                <path stroke-linecap="round" stroke-linejoin="round"
                d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                </svg>
            <span>Calendar</span>
            </a>
          </li>

          <li class="nav-item mb-2">
            <a href="todo.php" class="nav-link text-dark d-flex align-items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                class="me-2 side-bar-item-icon" width="20" height="20">
                <path stroke-linecap="round" stroke-linejoin="round"
                d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                </svg>
            <span>To-do list</span>
            </a>
          </li>

          <li class="nav-item mb-2">
            <a href="pomodoro.php" class="nav-link text-dark d-flex align-items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                class="me-2 side-bar-item-icon" width="20" height="20">
                <path stroke-linecap="round" stroke-linejoin="round"
                d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            <span>Pomodoro Timer</span>
            </a>
          </li>

          <li class="nav-item">
            <a href="studyroom.php" class="nav-link text-dark d-flex align-items-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                class="me-2 side-bar-item-icon" width="20" height="20">
                <path stroke-linecap="round" stroke-linejoin="round"
                d="m15.75 10.5 4.72-4.72a.75.75 0 0 1 1.28.53v11.38a.75.75 0 0 1-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25h-9A2.25 2.25 0 0 0 2.25 7.5v9a2.25 2.25 0 0 0 2.25 2.25Z" />
                </svg>
        <span>Study Rooms</span>
        </a>
          </li>
        </ul>

    </aside>
<!-- Main content-->
    <main class="col-lg-10 p-4">
    <div class="mb-4">
        <h4>Hello, <span class="Username"><?php echo htmlspecialchars($_SESSION['username']); ?></span></h4>
    </div>

    <div class="row g-4">
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm">
                <div class="card-header fw-bold">Calendar Preview</div>
                <div class="card-body d-flex flex-column">
                    <div id="dashboard-calendar-preview" class="flex-grow-1 mb-3"></div>
                    <h6 class="fw-semibold">Today's Events</h6>
                    <div id="today-events-list">
                        <p class="text-muted">No events scheduled for today.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-4">
            <div class="card h-100 text-center shadow-sm">
                <div class="card-header fw-bold">Pomodoro Timer</div>
                <div class="card-body d-flex flex-column justify-content-center">
                    <div class="session-title mb-2" id="session-status">Session</div>
                    <div class="timer mb-3 display-4 fw-bold" id="pomodoro-timer-display">25:00</div>
                    <div class="d-flex justify-content-center gap-2 mt-auto">
                        <button class="btn btn-success" id="start-pomodoro">Start</button>
                        <button class="btn btn-secondary" id="reset-pomodoro">Reset</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm">
                <div class="card-header fw-bold">Today's To-do</div>
                <div class="card-body">
                    <div id="latest-todo-item">
                        <p class="text-muted">No tasks added.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Render the calendar preview
    var calendarEl = document.getElementById('dashboard-calendar-preview');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next',
            center: 'title',
            right: ''
        },
        initialDate: new Date(),
        height: 250,
        events: {
            url: 'get_events_for_dashboard.php',
            method: 'POST',
            extraParams: {
                start: new Date().toISOString().split('T')[0],
                end: new Date().toISOString().split('T')[0]
            }
        },
        eventClick: function(info) {
            window.location.href = 'calendar.html';
        },
        dayCellDidMount: function(info) {
            info.el.addEventListener('click', function() {
                const date = info.dateStr.split('T')[0];
                fetchEventsForDate(date);
            });
        }
    });
    calendar.render();

    // 2. Function to fetch and display events for a specific date
    function fetchEventsForDate(date) {
        const eventsListContainer = document.getElementById('today-events-list');
        eventsListContainer.innerHTML = '';
        fetch(`get_events_by_date.php?date=${date}`)
        .then(response => {
            if (!response.ok) {
                // If response is not ok, throw an error to trigger catch block
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(events => {
            if (events.length > 0) {
                events.forEach(event => {
                    const eventItem = document.createElement('div');
                    eventItem.classList.add('d-flex', 'align-items-center', 'mb-2');
                    eventItem.innerHTML = `
                        <div class="bg-primary rounded-circle me-2" style="width: 10px; height: 10px;"></div>
                        <div><strong>${event.title}</strong> (${new Date(event.start_date).toLocaleTimeString()} - ${new Date(event.end_date).toLocaleTimeString()})</div>
                    `;
                    eventsListContainer.appendChild(eventItem);
                });
            } else {
                eventsListContainer.innerHTML = '<p class="text-muted">No events scheduled for this day.</p>';
            }
        })
        .catch(error => {
            console.error('Error fetching events:', error);
            eventsListContainer.innerHTML = '<p class="text-danger">Failed to load events.</p>';
        });
    }

    // Load today's events when the page loads
    const today = new Date().toISOString().split('T')[0];
    fetchEventsForDate(today);

});
</script>
<script>
// To-do List logic
    function fetchLatestTodo() {
        fetch('get_latest_todo.php')
            .then(response => response.json())
            .then(data => {
                const container = document.getElementById('latest-todo-item');
                if (data && data.task) {
                    container.innerHTML = `
                        <div class="d-flex align-items-center gap-2">
                            <input type="checkbox" disabled>
                            <span>${data.task}</span>
                        </div>
                    `;
                } else {
                    container.innerHTML = `<p class="text-muted">No tasks added.</p>`;
                }
            })
            .catch(error => {
                console.error('Error fetching latest todo:', error);
            });
    }

    // Pomodoro Timer logic
    let timerInterval;
    let isRunning = false;
    let sessionDuration = 25 * 60; // 25 minutes
    let timeRemaining = sessionDuration;

    const timerDisplay = document.getElementById('pomodoro-timer-display');
    const startButton = document.getElementById('start-pomodoro');
    const resetButton = document.getElementById('reset-pomodoro');
    const statusDisplay = document.getElementById('session-status');

    function updateTimerDisplay() {
        const minutes = Math.floor(timeRemaining / 60).toString().padStart(2, '0');
        const seconds = (timeRemaining % 60).toString().padStart(2, '0');
        timerDisplay.textContent = `${minutes}:${seconds}`;
    }

    function startTimer() {
        if (isRunning) return;
        isRunning = true;
        statusDisplay.textContent = 'Session in progress';
        timerInterval = setInterval(() => {
            timeRemaining--;
            updateTimerDisplay();
            if (timeRemaining <= 0) {
                clearInterval(timerInterval);
                isRunning = false;
                statusDisplay.textContent = 'Session complete!';
                alert('Pomodoro session complete!');
            }
        }, 1000);
    }

    function resetTimer() {
        clearInterval(timerInterval);
        isRunning = false;
        timeRemaining = sessionDuration;
        statusDisplay.textContent = 'Session';
        updateTimerDisplay();
    }

    // Event listeners
    if (startButton) startButton.addEventListener('click', startTimer);
    if (resetButton) resetButton.addEventListener('click', resetTimer);

    // Initial calls on page load
    updateTimerDisplay();
    fetchLatestTodo();
</script>
<script>
    // New Pomodoro sync logic
    function syncPomodoroTimer() {
        const dashboardTimerDisplay = document.getElementById('pomodoro-timer-display');
        const dashboardStatusDisplay = document.getElementById('session-status');
        const savedState = JSON.parse(sessionStorage.getItem('pomodoroTimerState'));

        if (savedState && savedState.action === 'start') {
            const elapsedTime = (new Date().getTime() - savedState.startTime) / 1000;
            const remaining = savedState.remaining - elapsedTime;
            
            if (remaining > 0) {
                const minutes = Math.floor(remaining / 60).toString().padStart(2, '0');
                const seconds = Math.floor(remaining % 60).toString().padStart(2, '0');
                dashboardTimerDisplay.textContent = `${minutes}:${seconds}`;
                dashboardStatusDisplay.textContent = savedState.phase === 'session' ? 'Session in Progress' : 'Break in Progress';
            } else {
                dashboardTimerDisplay.textContent = '00:00';
                dashboardStatusDisplay.textContent = 'Timer Finished';
                sessionStorage.removeItem('pomodoroTimerState');
            }
        } else {
            dashboardTimerDisplay.textContent = '25:00';
            dashboardStatusDisplay.textContent = 'Session';
        }
    }

    setInterval(syncPomodoroTimer, 1000);
    syncPomodoroTimer();
</script>
</body>
</html>
