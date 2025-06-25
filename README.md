# study-mate
---

```markdown
# 🎓 Student Productivity & Study Platform

A comprehensive productivity platform designed specifically for students — combining **task management**, **event scheduling**, **Pomodoro-based focus sessions**, and **community interaction** to enhance productivity, collaboration, and self-discipline.

---

## 🌟 Project Aim

To empower students with an all-in-one digital workspace that helps them:
- Stay organized (To-do lists + Calendar)
- Focus better (Pomodoro Timer)
- Build connections (Threaded community discussions)
- Study together (Group video chat rooms)

---

## 🛠️ Tech Stack

| Layer        | Technology                               |
|--------------|-------------------------------------------|
| **Frontend** | HTML5, CSS3, JavaScript (optionally React.js), Bootstrap |
| **Calendar** | FullCalendar.js                          |
| **Storage**  | `localStorage` (frontend version) → MySQL (backend version) |
| **Backend**  | PHP (for full-stack version)             |
| **Real-time Chat** | Firebase Realtime DB / Firestore (future) |
| **Video Rooms** | Jitsi Meet SDK                        |

---

## 🔧 Project Phases

### ✅ **Phase 1: Frontend-Only MVP**
- [x] Dashboard Layout
- [x] To-do List with `localStorage`
- [x] Calendar using FullCalendar.js
- [x] Pomodoro Timer with 25/5 structure
- [x] Responsive design

### 🚧 **Phase 2: Backend Integration (Planned)**
- [ ] User Authentication (Register/Login)
- [ ] MySQL database integration for tasks/events
- [ ] PHP handling for form submissions
- [ ] Session handling & security

### 🎯 **Phase 3: Real-Time + Social Features (Planned)**
- [ ] Firebase-powered community threads
- [ ] Real-time chat within study groups
- [ ] Group study rooms via Jitsi Meet
- [ ] Shared Pomodoro timer across participants

---

## 📁 Folder Structure

```

student-dashboard/
├── index.html                 ← Landing/Login mockup
├── dashboard.html             ← Main overview
├── todo.html                  ← To-do list module
├── calendar.html              ← Event planner
├── pomodoro.html              ← Focus timer
│
├── /assets/
│   ├── /css/
│   │   └── style.css
│   ├── /js/
│   │   ├── todo.js
│   │   ├── calendar.js
│   │   └── pomodoro.js
│   └── /img/
│
└── README.md

```

---

## ✨ Key Features

### ✅ **To-Do List**
- Add, delete, and mark tasks as complete
- Persistent storage via `localStorage`
- Simple and focused UI

### ✅ **Calendar**
- Monthly and weekly event views
- Click-to-add event prompt
- Drag-and-drop (future)
- Stored in `localStorage` (for now)

### ✅ **Pomodoro Timer**
- 25-minute work + 5-minute break
- Start, pause, reset controls
- Optional session logs (future)

### 🔁 **Community Threads (Planned)**
- Post academic or productivity topics
- Reply in threaded conversations
- Real-time updates via Firebase

### 🎥 **Group Study Rooms (Planned)**
- Public video rooms for group focus
- Embedded Jitsi Meet sessions
- Shared Pomodoro for synced focus

---

## 🚀 Getting Started (Frontend-Only Version)

1. Clone the repository
2. Open `index.html` or `dashboard.html` in a browser
3. Try:
   - Adding tasks in the to-do section
   - Creating events in the calendar
   - Running a Pomodoro session

> ⚠️ No database or login system in this version. All data is stored locally in the browser.

---

## 🧑‍💻 Author

Developed by a student developer passionate about creating meaningful tools for learners and future collaborators.

---

## 📌 License

This project is open for educational use and learning purposes. MIT License can be added if needed.

---
```

---
