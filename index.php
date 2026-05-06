<!DOCTYPE html>
<html lang="sk">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Taskly — Tvoje úlohy</title>
  <link rel="stylesheet" href="style.css" />
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet"/>
</head>
<body class="page-todo">

  <nav class="navbar">
    <div class="logo">Taskly<span class="dot">.</span></div>
    <div class="nav-links">
      <a href="login.php" class="btn-ghost">Prihlásiť sa</a>
      <a href="register.php" class="btn-primary">Registrácia</a>
    </div>
  </nav>

  <main class="todo-main">

    <aside class="sidebar">
      <div class="user-card">
        <div class="avatar">JN</div>
        <div>
          <div class="user-name">Ján Novák</div>
          <div class="user-email">jan@taskly.sk</div>
        </div>
      </div>

      <nav class="sidebar-nav">
        <a href="#" class="sidebar-link active">
          <span class="icon">◈</span> Všetky úlohy
        </a>
        <a href="#" class="sidebar-link">
          <span class="icon">◉</span> Dnes
        </a>
        <a href="#" class="sidebar-link">
          <span class="icon">◎</span> Dôležité
        </a>
        <a href="#" class="sidebar-link">
          <span class="icon">○</span> Dokončené
        </a>
      </nav>

      <div class="sidebar-footer">
        <div class="progress-label">
          <span>Dnešný pokrok</span>
          <span class="prog-count">3 / 7</span>
        </div>
        <div class="progress-bar">
          <div class="progress-fill" style="width: 43%"></div>
        </div>
      </div>
    </aside>

    <section class="todo-section">
      <header class="todo-header">
        <div>
          <h1 class="todo-title">Dobrý deň, Ján <span class="wave">👋</span></h1>
          <p class="todo-sub">Máš <strong>4 úlohy</strong> na dnes. Poďme na to.</p>
        </div>
        <button class="btn-primary" onclick="openModal()">+ Nová úloha</button>
      </header>

      <div class="filter-bar">
        <button class="filter-btn active">Všetky</button>
        <button class="filter-btn">Aktívne</button>
        <button class="filter-btn">Dokončené</button>
      </div>

      <ul class="task-list" id="taskList">
        <li class="task-item priority-high">
          <button class="check-btn" onclick="toggleTask(this)">✓</button>
          <div class="task-body">
            <span class="task-name">Dokončiť prezentáciu pre klienta</span>
            <div class="task-meta">
              <span class="tag tag-work">Práca</span>
              <span class="due">⏰ Dnes 14:00</span>
            </div>
          </div>
          <button class="delete-btn" onclick="deleteTask(this)">✕</button>
        </li>
        <li class="task-item done">
          <button class="check-btn checked" onclick="toggleTask(this)">✓</button>
          <div class="task-body">
            <span class="task-name">Ranná káva a plánovanie dňa</span>
            <div class="task-meta">
              <span class="tag tag-personal">Osobné</span>
              <span class="due">⏰ Ráno</span>
            </div>
          </div>
          <button class="delete-btn" onclick="deleteTask(this)">✕</button>
        </li>
        <li class="task-item priority-medium">
          <button class="check-btn" onclick="toggleTask(this)">✓</button>
          <div class="task-body">
            <span class="task-name">Odpovedať na emaily</span>
            <div class="task-meta">
              <span class="tag tag-work">Práca</span>
              <span class="due">⏰ Dnes 16:00</span>
            </div>
          </div>
          <button class="delete-btn" onclick="deleteTask(this)">✕</button>
        </li>
        <li class="task-item">
          <button class="check-btn" onclick="toggleTask(this)">✓</button>
          <div class="task-body">
            <span class="task-name">Nakúpiť potraviny</span>
            <div class="task-meta">
              <span class="tag tag-personal">Osobné</span>
              <span class="due">⏰ Večer</span>
            </div>
          </div>
          <button class="delete-btn" onclick="deleteTask(this)">✕</button>
        </li>
        <li class="task-item done">
          <button class="check-btn checked" onclick="toggleTask(this)">✓</button>
          <div class="task-body">
            <span class="task-name">Zavolať lekárovi kvôli termínu</span>
            <div class="task-meta">
              <span class="tag tag-health">Zdravie</span>
              <span class="due">⏰ Dopoludnia</span>
            </div>
          </div>
          <button class="delete-btn" onclick="deleteTask(this)">✕</button>
        </li>
      </ul>
    </section>
  </main>

  <!-- Modal -->
  <div class="modal-overlay" id="modalOverlay" onclick="closeModalOutside(event)">
    <div class="modal">
      <h2 class="modal-title">Nová úloha</h2>
      <input type="text" class="modal-input" id="taskInput" placeholder="Názov úlohy..." />
      <div class="modal-row">
        <select class="modal-select" id="taskTag">
          <option value="tag-work">Práca</option>
          <option value="tag-personal">Osobné</option>
          <option value="tag-health">Zdravie</option>
        </select>
        <input type="time" class="modal-input" id="taskTime" />
      </div>
      <div class="modal-actions">
        <button class="btn-ghost" onclick="closeModal()">Zrušiť</button>
        <button class="btn-primary" onclick="addTask()">Pridať úlohu</button>
      </div>
    </div>
  </div>

  <script>
    function toggleTask(btn) {
      const item = btn.closest('.task-item');
      item.classList.toggle('done');
      btn.classList.toggle('checked');
    }
    function deleteTask(btn) {
      btn.closest('.task-item').remove();
    }
    function openModal() {
      document.getElementById('modalOverlay').classList.add('active');
    }
    function closeModal() {
      document.getElementById('modalOverlay').classList.remove('active');
    }
    function closeModalOutside(e) {
      if (e.target === document.getElementById('modalOverlay')) closeModal();
    }
    function addTask() {
      const name = document.getElementById('taskInput').value.trim();
      const tag = document.getElementById('taskTag').value;
      const time = document.getElementById('taskTime').value;
      if (!name) return;
      const tagLabels = { 'tag-work': 'Práca', 'tag-personal': 'Osobné', 'tag-health': 'Zdravie' };
      const li = document.createElement('li');
      li.className = 'task-item';
      li.innerHTML = `
        <button class="check-btn" onclick="toggleTask(this)">✓</button>
        <div class="task-body">
          <span class="task-name">${name}</span>
          <div class="task-meta">
            <span class="tag ${tag}">${tagLabels[tag]}</span>
            ${time ? `<span class="due">⏰ ${time}</span>` : ''}
          </div>
        </div>
        <button class="delete-btn" onclick="deleteTask(this)">✕</button>
      `;
      document.getElementById('taskList').prepend(li);
      document.getElementById('taskInput').value = '';
      closeModal();
    }

    document.querySelectorAll('.filter-btn').forEach(btn => {
      btn.addEventListener('click', function() {
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
      });
    });
    document.querySelectorAll('.sidebar-link').forEach(link => {
      link.addEventListener('click', function(e) {
        e.preventDefault();
        document.querySelectorAll('.sidebar-link').forEach(l => l.classList.remove('active'));
        this.classList.add('active');
      });
    });
  </script>
</body>
</html>