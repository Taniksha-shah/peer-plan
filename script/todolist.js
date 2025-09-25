document.addEventListener("DOMContentLoaded", () => {
    const todoTitleInput = document.getElementById("todoTitle");
    const taskInput = document.getElementById("taskInput");
    const addTaskBtn = document.getElementById("addTaskBtn");
    const taskList = document.getElementById("taskList");
    const saveListBtn = document.getElementById("saveListBtn");
    const savedListsContainer = document.getElementById("savedListsContainer");

    let currentTasks = [];

    // Event listeners
    addTaskBtn.addEventListener("click", addTask);
    taskInput.addEventListener("keypress", (e) => {
        if (e.key === "Enter") addTask();
    });
    saveListBtn.addEventListener("click", saveList);

    // Initial load
    loadSavedLists();

    // Adds a task to the current in-memory list
    function addTask() {
        const title = taskInput.value.trim();
        if (!title) return;

        const task = {
            title: title,
            is_completed: false
        };

        currentTasks.push(task);
        renderTask(task);
        taskInput.value = "";
    }

    // Renders a single task in the current list
    function renderTask(task) {
        const li = document.createElement("li");
        li.className = `list-group-item d-flex justify-content-between align-items-center`;

        li.innerHTML = `
            <div class="form-check d-flex align-items-center">
                <input class="form-check-input me-2" type="checkbox">
                <label class="form-check-label">${task.title}</label>
            </div>
            <button class="btn btn-sm btn-danger delete-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                    <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                </svg>
            </button>
        `;

        const deleteBtn = li.querySelector('.delete-btn');
        deleteBtn.addEventListener('click', () => {
            const index = currentTasks.findIndex(t => t.title === task.title);
            if (index > -1) {
                currentTasks.splice(index, 1);
            }
            li.remove();
        });

        taskList.appendChild(li);
    }

    // Saves the current list to the database
    function saveList() {
        const title = todoTitleInput.value.trim();
        if (!title) {
            alert("Please enter a title for your list.");
            return;
        }

        if (currentTasks.length === 0) {
            alert("Please add at least one task to save the list.");
            return;
        }

        const taskTitles = currentTasks.map(task => task.title);
        const formData = new FormData();
        formData.append('title', title);
        formData.append('tasks', JSON.stringify(taskTitles));

        fetch('../student-dashboard/save_list.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert("List saved successfully!");
                // Clear the current list
                currentTasks = [];
                taskList.innerHTML = "";
                todoTitleInput.value = "";
                // Reload saved lists to show the new one
                loadSavedLists();
            } else {
                alert("Error saving list: " + data.message);
            }
        })
        .catch(error => {
            console.error('Error saving list:', error);
            alert("Failed to save the list.");
        });
    }

    // Fetches and displays all saved lists
    function loadSavedLists() {
        fetch('../student-dashboard/get_lists.php')
            .then(response => response.json())
            .then(lists => {
                savedListsContainer.innerHTML = "";
                if (lists.length === 0) {
                    savedListsContainer.innerHTML = `<p class="text-muted">No saved lists yet.</p>`;
                    return;
                }
                lists.forEach(list => renderSavedList(list));
            })
            .catch(error => {
                console.error('Error fetching saved lists:', error);
                savedListsContainer.innerHTML = `<p class="text-danger">Failed to load saved lists.</p>`;
            });
    }

    // Renders a single saved list as a card
    function renderSavedList(list) {
        const card = document.createElement("div");
        card.className = "col-md-6";
        card.innerHTML = `
            <div class="card shadow-sm p-3">
                <h5 class="card-title fw-bold">${list.title}</h5>
                <ul class="list-group list-group-flush mt-2">
                    ${list.tasks.map(task => `
                        <li class="list-group-item d-flex align-items-center">
                            <span class="${task.is_completed == 1 ? 'text-decoration-line-through text-muted' : ''}">${task.title}</span>
                        </li>
                    `).join('')}
                </ul>
            </div>
        `;
        savedListsContainer.appendChild(card);
    }
});