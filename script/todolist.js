document.addEventListener("DOMContentLoaded", () => {
  const taskInput = document.getElementById("taskInput");
  const addTaskBtn = document.getElementById("addTaskBtn");
  const taskList = document.getElementById("taskList");
  const saveListBtn = document.getElementById("saveListBtn");
  const savedListsContainer = document.getElementById("savedListsContainer");
  const todoTitle = document.getElementById("todoTitle");

  let currentTasks = [];
  let savedLists = JSON.parse(localStorage.getItem("savedLists")) || [];

  // Render all saved lists on load
  renderSavedLists();

  // Add Task
  addTaskBtn.addEventListener("click", addTask);
  taskInput.addEventListener("keypress", (e) => {
    if (e.key === "Enter") addTask();
  });

  function addTask() {
    const taskText = taskInput.value.trim();
    if (!taskText) return;

    currentTasks.push({ id: Date.now(), text: taskText, completed: false });
    renderCurrentTasks();
    taskInput.value = "";
  }

  // Render current tasks (before saving)
  function renderCurrentTasks() {
    taskList.innerHTML = "";
    if (currentTasks.length === 0) {
      taskList.innerHTML = `<li class="list-group-item text-muted">No tasks added</li>`;
      return;
    }

    currentTasks.forEach((task) => {
      const li = document.createElement("li");
      li.className = "list-group-item d-flex justify-content-between align-items-center";

      const leftDiv = document.createElement("div");
      leftDiv.className = "d-flex align-items-center gap-2";

      const checkbox = document.createElement("input");
      checkbox.type = "checkbox";
      checkbox.checked = task.completed;
      checkbox.addEventListener("change", () => {
        task.completed = !task.completed;
        renderCurrentTasks();
      });

      const span = document.createElement("span");
      span.textContent = task.text;
      if (task.completed) span.style.textDecoration = "line-through";

      leftDiv.appendChild(checkbox);
      leftDiv.appendChild(span);

      const delBtn = document.createElement("button");
      delBtn.className = "btn btn-sm btn-outline-danger";
      delBtn.textContent = "Remove";
      delBtn.addEventListener("click", () => {
        currentTasks = currentTasks.filter((t) => t.id !== task.id);
        renderCurrentTasks();
      });

      li.appendChild(leftDiv);
      li.appendChild(delBtn);

      taskList.appendChild(li);
    });
  }

  // Save list
  saveListBtn.addEventListener("click", () => {
    const title = todoTitle.value.trim() || "Untitled List";
    if (currentTasks.length === 0) return;

    const newList = { id: Date.now(), title, tasks: currentTasks };
    savedLists.push(newList);
    localStorage.setItem("savedLists", JSON.stringify(savedLists));

    currentTasks = [];
    todoTitle.value = "";
    renderCurrentTasks();
    renderSavedLists();
  });

  // Render saved lists
  function renderSavedLists() {
    savedListsContainer.innerHTML = "";
    if (savedLists.length === 0) {
      savedListsContainer.innerHTML = `<p class="text-muted">No saved lists</p>`;
      return;
    }

    savedLists.forEach((list) => {
      const col = document.createElement("div");
      col.className = "col-md-4";

      const card = document.createElement("div");
      card.className = "card shadow-sm";

      const body = document.createElement("div");
      body.className = "card-body";

      const h5 = document.createElement("h5");
      h5.className = "card-title";
      h5.textContent = list.title;

      const ul = document.createElement("ul");
      ul.className = "list-group list-group-flush mb-3";
      list.tasks.forEach((task) => {
        const li = document.createElement("li");
        li.className = "list-group-item";
        li.textContent = task.text;
        if (task.completed) li.style.textDecoration = "line-through";
        ul.appendChild(li);
      });

      const delBtn = document.createElement("button");
      delBtn.className = "btn btn-sm btn-danger";
      delBtn.textContent = "Delete List";
      delBtn.addEventListener("click", () => {
        savedLists = savedLists.filter((l) => l.id !== list.id);
        localStorage.setItem("savedLists", JSON.stringify(savedLists));
        renderSavedLists();
      });

      body.appendChild(h5);
      body.appendChild(ul);
      body.appendChild(delBtn);
      card.appendChild(body);
      col.appendChild(card);
      savedListsContainer.appendChild(col);
    });
  }
});
