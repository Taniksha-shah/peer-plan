document.addEventListener("DOMContentLoaded", () => {
    const taskInput = document.getElementById("taskInput");
    const addTaskBtn = document.getElementById("addTaskBtn");
    const taskList = document.getElementById("taskList");

    // Event listeners
    addTaskBtn.addEventListener("click", addTask);
    taskInput.addEventListener("keypress", (e) => {
        if (e.key === "Enter") addTask();
    });

    // Initial load of tasks from the database
    loadTasks();

    // Fetches and renders all tasks for the logged-in user from the database
    function loadTasks() {
        fetch('get_tasks.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(tasks => {
                taskList.innerHTML = "";
                if (tasks.length === 0) {
                    taskList.innerHTML = `<li class="list-group-item text-muted">No tasks yet.</li>`;
                    return;
                }
                tasks.forEach(task => renderTask(task));
            })
            .catch(error => {
                console.error('Error fetching tasks:', error);
                taskList.innerHTML = `<li class="list-group-item text-danger">Failed to load tasks.</li>`;
            });
    }

    // Sends a new task to the server to be saved in the database
    function addTask() {
        const title = taskInput.value.trim();
        if (!title) return;

        fetch('add_task.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `title=${encodeURIComponent(title)}`
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.status === 'success') {
                // Render the newly added task on the page
                renderTask({
                    id: data.id,
                    title: title,
                    is_completed: 0
                });
                taskInput.value = "";
            } else {
                alert('Error adding task: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error adding task:', error);
            alert('Failed to add task.');
        });
    }

    // Deletes a task from the database
    function deleteTask(taskId, element) {
        if (!confirm("Are you sure you want to delete this task?")) return;

        fetch('delete_task.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `id=${taskId}`
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.status === 'success') {
                element.remove();
            } else {
                alert('Error deleting task: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error deleting task:', error);
            alert('Failed to delete task.');
        });
    }

    // Toggles a task's completion status in the database
    function toggleTask(taskId, isCompleted) {
        fetch('update_task.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `id=${taskId}&is_completed=${isCompleted ? 1 : 0}`
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.status !== 'success') {
                alert('Error updating task: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error updating task:', error);
            alert('Failed to update task.');
        });
    }

    // Renders a single task element on the page
    function renderTask(task) {
        const li = document.createElement("li");
        li.className = "list-group-item d-flex justify-content-between align-items-center";

        const leftDiv = document.createElement("div");
        leftDiv.className = "d-flex align-items-center gap-2";

        const checkbox = document.createElement("input");
        checkbox.type = "checkbox";
        checkbox.checked = task.is_completed == 1;
        checkbox.addEventListener("change", () => {
            toggleTask(task.id, checkbox.checked);
            li.style.textDecoration = checkbox.checked ? "line-through" : "none";
        });

        const span = document.createElement("span");
        span.textContent = task.title;
        if (task.is_completed == 1) {
            li.style.textDecoration = "line-through";
        }

        leftDiv.appendChild(checkbox);
        leftDiv.appendChild(span);

        const delBtn = document.createElement("button");
        delBtn.className = "btn btn-sm btn-outline-danger";
        delBtn.textContent = "Remove";
        delBtn.addEventListener("click", () => deleteTask(task.id, li));

        li.appendChild(leftDiv);
        li.appendChild(delBtn);

        taskList.appendChild(li);
    }
});