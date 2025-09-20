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
  <title>PeerPlan Calendar</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css' rel='stylesheet' />
    <link href="../style/dashboard.css" rel="stylesheet" />
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

  </div>
</div>

<!-- Layout: Sidebar + Main -->
<div class="container-fluid">
  <div class="row">
    
    <!-- Sidebar on Large Screens -->
    <aside class="col-lg-2 bg-light d-none d-lg-block p-3 min-vh-100 border-end">
      <ul class="nav flex-column">
          <li class="nav-item md-2">
            <a href="calendar.php" class="nav-link d-flex align-items-center active">
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

    </aside>

    <!-- Main Content -->
  <main class="col-lg-10 p-4">
   <!--<h1 class="month-title mb-4">July 2025</h1> --> 
<!-- Calendar Wrapper -->
  <div id="calendar"></div>
  </main>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            selectable: true,
            editable: true,
            selectMirror: true,
            
            // Fetches events from the database
            events: 'get_events.php',

            select: function(info) {
                let title = prompt("Enter event title:");
                if (title) {
                    let start = info.startStr;
                    let end = info.endStr;

                    fetch('add_event.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `title=${encodeURIComponent(title)}&start_date=${encodeURIComponent(start)}&end_date=${encodeURIComponent(end)}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            calendar.addEvent({
                                id: data.id,
                                title: title,
                                start: info.start,
                                end: info.end,
                                allDay: info.allDay
                            });
                        } else {
                            alert('Error adding event: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Failed to add event.');
                    });
                }
                calendar.unselect();
            },
            
            // Handles event drag/drop and resize
            eventDrop: function(info) {
                let event = info.event;
                let newStart = event.startStr;
                let newEnd = event.endStr;

                fetch('update_event.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `id=${event.id}&start_date=${newStart}&end_date=${newEnd}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status !== 'success') {
                        alert('Error updating event: ' + data.message);
                        info.revert(); // Revert the event to its original position
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to update event.');
                    info.revert();
                });
            },
            
            eventResize: function(info) {
                let event = info.event;
                let newStart = event.startStr;
                let newEnd = event.endStr;
                
                fetch('update_event.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `id=${event.id}&start_date=${newStart}&end_date=${newEnd}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status !== 'success') {
                        alert('Error updating event: ' + data.message);
                        info.revert();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to update event.');
                    info.revert();
                });
            },
            
            // Handles event deletion on click
            eventClick: function(info) {
                if (confirm("Are you sure you want to delete this event?")) {
                    let event = info.event;
                    
                    fetch('delete_event.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `id=${event.id}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            event.remove(); // Remove the event from the calendar
                        } else {
                            alert('Error deleting event: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Failed to delete event.');
                    });
                }
            }
        });

        calendar.render();
    });
</script>
</body>
</html>