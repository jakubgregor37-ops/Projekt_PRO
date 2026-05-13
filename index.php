<?php
// ============================================================
// index.php — Hlavná stránka (READ + spracovanie akcií)
// ============================================================
// Tu sa deje:
// - Kontrola či je user prihlásený (session)
// - Spracovanie POST akcií: add / toggle / delete / edit
// - Načítanie úloh z DB pre prihláseného usera
// - Vykreslenie HTML so skutočnými dátami
// ============================================================

session_start();
require_once 'db.php';

// --- Auth guard: ak nie je prihlásený, pošli ho na login ---
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id  = $_SESSION['user_id'];
$username = $_SESSION['username'];
$msg      = '';

// ============================================================
// Spracovanie POST akcií (add / toggle / delete / edit)
// Všetky akcie sú na tomto istom súbore, rozlíšené podľa
// skrytého poľa "action" vo formulári.
// ============================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $action = $_POST['action'] ?? '';

    // --- PRIDAŤ ÚLOHU (CREATE) ---
    if ($action === 'add') {
        $title = trim($_POST['title'] ?? '');
        if (!empty($title)) {
            $stmt = mysqli_prepare($conn,
                'INSERT INTO tasks (user_id, title, status) VALUES (?, ?, "pending")'
            );
            mysqli_stmt_bind_param($stmt, 'is', $user_id, $title);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }

    // --- PREPNÚŤ STATUS (UPDATE: pending ↔ done) ---
    if ($action === 'toggle') {
        $task_id = (int)($_POST['task_id'] ?? 0);
        // WHERE user_id = ? zabezpečí že user nemôže meniť cudzie úlohy
        $stmt = mysqli_prepare($conn,
            'UPDATE tasks SET status = IF(status="pending","done","pending")
             WHERE id = ? AND user_id = ?'
        );
        mysqli_stmt_bind_param($stmt, 'ii', $task_id, $user_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    // --- VYMAZAŤ ÚLOHU (DELETE) ---
    if ($action === 'delete') {
        $task_id = (int)($_POST['task_id'] ?? 0);
        $stmt = mysqli_prepare($conn,
            'DELETE FROM tasks WHERE id = ? AND user_id = ?'
        );
        mysqli_stmt_bind_param($stmt, 'ii', $task_id, $user_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    // --- UPRAVIŤ NÁZOV ÚLOHY (UPDATE) ---
    if ($action === 'edit') {
        $task_id = (int)($_POST['task_id'] ?? 0);
        $title   = trim($_POST['title'] ?? '');
        if (!empty($title)) {
            $stmt = mysqli_prepare($conn,
                'UPDATE tasks SET title = ? WHERE id = ? AND user_id = ?'
            );
            mysqli_stmt_bind_param($stmt, 'sii', $title, $task_id, $user_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }

    // Po každej akcii presmeruj (PRG pattern — zamedzí dvojitému odoslaniu)
    header('Location: index.php');
    exit;
}

// ============================================================
// Načítaj úlohy z DB (READ)
// Len úlohy prihláseného usera, zoradené od najnovších
// ============================================================
$stmt   = mysqli_prepare($conn,
    'SELECT id, title, status, created_at FROM tasks WHERE user_id = ? ORDER BY created_at DESC'
);
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$tasks  = mysqli_fetch_all($result, MYSQLI_ASSOC);  // Všetky riadky ako pole
mysqli_stmt_close($stmt);

// Štatistiky pre progress bar
$total = count($tasks);
$done  = count(array_filter($tasks, fn($t) => $t['status'] === 'done'));
$pct   = $total > 0 ? round($done / $total * 100) : 0;
?>
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
      <!-- Odhlásenie: jednoduchý GET link -->
      <a href="logout.php" class="btn-ghost">Odhlásiť sa</a>
    </div>
  </nav>

  <main class="todo-main">
    <aside class="sidebar">
      <div class="user-card">
        <!-- Avatar: prvé písmeno mena -->
        <div class="avatar"><?= strtoupper(substr($username, 0, 2)) ?></div>
        <div>
          <div class="user-name"><?= htmlspecialchars($username) ?></div>
          <div class="user-email"><?= $done ?>/<?= $total ?> úloh hotových</div>
        </div>
      </div>

      <div class="sidebar-footer" style="margin-top:1rem;">
        <div class="progress-label">
          <span>Pokrok</span>
          <span class="prog-count"><?= $done ?> / <?= $total ?></span>
        </div>
        <div class="progress-bar">
          <div class="progress-fill" style="width: <?= $pct ?>%"></div>
        </div>
      </div>
    </aside>

    <section class="todo-section">
      <header class="todo-header">
        <div>
          <h1 class="todo-title">Ahoj, <?= htmlspecialchars($username) ?> 👋</h1>
          <p class="todo-sub">
            Máš <strong><?= $total - $done ?> aktívnych</strong> úloh.
          </p>
        </div>
        <!-- Tlačidlo otvorí formulár nižšie (jednoduchý toggle) -->
        <button class="btn-primary" onclick="toggleAddForm()">+ Nová úloha</button>
      </header>

      <!-- Formulár na PRIDANIE úlohy (skrytý by default) -->
      <div id="addForm" style="display:none; background:var(--bg2); border:1px solid var(--border);
           border-radius:var(--radius); padding:1.25rem; margin-bottom:1.25rem;">
        <form method="post" action="" style="display:flex; gap:.75rem; align-items:center;">
          <input type="hidden" name="action" value="add"/>
          <input type="text" name="title" class="modal-input"
                 placeholder="Názov novej úlohy..." required
                 style="margin:0; flex:1;"/>
          <button type="submit" class="btn-primary">Pridať</button>
          <button type="button" class="btn-ghost" onclick="toggleAddForm()">Zrušiť</button>
        </form>
      </div>

      <!-- Zoznam úloh -->
      <ul class="task-list">
        <?php if (empty($tasks)): ?>
          <li style="text-align:center; color:var(--text-muted); padding:2rem;">
            Žiadne úlohy. Pridaj prvú! 🎉
          </li>
        <?php endif; ?>

        <?php foreach ($tasks as $task): ?>
          <?php $isDone = $task['status'] === 'done'; ?>
          <li class="task-item <?= $isDone ? 'done' : '' ?>" id="task-<?= $task['id'] ?>">

            <!-- CHECK BUTTON — prepne status -->
            <form method="post" action="" style="margin:0;">
              <input type="hidden" name="action"  value="toggle"/>
              <input type="hidden" name="task_id" value="<?= $task['id'] ?>"/>
              <button type="submit" class="check-btn <?= $isDone ? 'checked' : '' ?>"
                      title="<?= $isDone ? 'Označiť ako aktívnu' : 'Označiť ako hotovú' ?>">
                ✓
              </button>
            </form>

            <!-- NÁZOV ÚLOHY (kliknuteľný pre editáciu) -->
            <div class="task-body">
              <!-- Zobrazovací mód -->
              <span class="task-name" id="name-<?= $task['id'] ?>"
                    onclick="startEdit(<?= $task['id'] ?>, this)"
                    title="Klikni pre úpravu" style="cursor:pointer;">
                <?= htmlspecialchars($task['title']) ?>
              </span>

              <!-- Editovací formulár (skrytý) -->
              <form method="post" action="" id="editForm-<?= $task['id'] ?>"
                    style="display:none; margin-top:.3rem;">
                <input type="hidden" name="action"  value="edit"/>
                <input type="hidden" name="task_id" value="<?= $task['id'] ?>"/>
                <input type="text" name="title" class="modal-input"
                       value="<?= htmlspecialchars($task['title']) ?>"
                       style="margin:0 0 .4rem; font-size:.9rem;"
                       id="editInput-<?= $task['id'] ?>"/>
                <div style="display:flex; gap:.4rem;">
                  <button type="submit" class="btn-primary" style="padding:.35rem .8rem; font-size:.8rem;">Uložiť</button>
                  <button type="button" class="btn-ghost"   style="padding:.35rem .8rem; font-size:.8rem;"
                          onclick="cancelEdit(<?= $task['id'] ?>)">Zrušiť</button>
                </div>
              </form>

              <div class="task-meta">
                <span style="font-size:.75rem; color:var(--text-muted);">
                  <?= date('d.m.Y', strtotime($task['created_at'])) ?>
                </span>
                <span class="tag <?= $isDone ? 'tag-health' : 'tag-work' ?>"
                      style="font-size:.7rem;">
                  <?= $isDone ? 'Hotovo' : 'Aktívna' ?>
                </span>
              </div>
            </div>

            <!-- DELETE BUTTON -->
            <form method="post" action="" style="margin:0;">
              <input type="hidden" name="action"  value="delete"/>
              <input type="hidden" name="task_id" value="<?= $task['id'] ?>"/>
              <button type="submit" class="delete-btn" title="Vymazať"
                      onclick="return confirm('Naozaj vymazať túto úlohu?')">✕</button>
            </form>
          </li>
        <?php endforeach; ?>
      </ul>
    </section>
  </main>

  <script>
    function toggleAddForm() {
      const f = document.getElementById('addForm');
      f.style.display = f.style.display === 'none' ? 'block' : 'none';
      if (f.style.display === 'block') f.querySelector('input[name=title]').focus();
    }
    function startEdit(id, el) {
      el.style.display = 'none';
      document.getElementById('editForm-' + id).style.display = 'block';
      document.getElementById('editInput-' + id).focus();
    }
    function cancelEdit(id) {
      document.getElementById('editForm-' + id).style.display = 'none';
      document.getElementById('name-' + id).style.display = '';
    }
  </script>
</body>
</html>
