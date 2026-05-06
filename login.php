<!DOCTYPE html>
<html lang="sk">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Taskly — Prihlásenie</title>
  <link rel="stylesheet" href="style.css" />
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet"/>
</head>
<body class="page-auth">

  <nav class="navbar">
    <a href="index.php" class="logo">Taskly<span class="dot">.</span></a>
    <div class="nav-links">
      <a href="register.php" class="btn-ghost">Registrácia</a>
    </div>
  </nav>

  <div class="auth-layout">
    <div class="auth-deco">
      <div class="deco-text">
        <h2>Vitaj späť<span class="dot">.</span></h2>
        <p>Tvoje úlohy čakajú.<br/>Poďme na ne spoločne.</p>
      </div>
      <div class="deco-shapes">
        <div class="shape s1"></div>
        <div class="shape s2"></div>
        <div class="shape s3"></div>
      </div>
      <div class="floating-task ft1">✓ Prezentácia odoslaná</div>
      <div class="floating-task ft2">◈ 3 úlohy na dnes</div>
      <div class="floating-task ft3">○ Nakúpiť</div>
    </div>

    <div class="auth-card">
      <h1 class="auth-title">Prihlásiť sa</h1>
      <p class="auth-sub">Nemáš účet? <a href="register.php">Registruj sa zadarmo</a></p>

      <form class="auth-form" onsubmit="handleLogin(event)">
        <div class="form-group">
          <label>E-mail</label>
          <input type="email" placeholder="tvoj@email.sk" required />
        </div>
        <div class="form-group">
          <label>Heslo</label>
          <div class="input-wrapper">
            <input type="password" id="loginPwd" placeholder="••••••••" required />
            <button type="button" class="toggle-pwd" onclick="togglePwd('loginPwd', this)">👁</button>
          </div>
        </div>
        <div class="form-extras">
          <label class="checkbox-label">
            <input type="checkbox" /> Zapamätať ma
          </label>
          <a href="#" class="forgot">Zabudnuté heslo?</a>
        </div>
        <button type="submit" class="btn-primary btn-full">Prihlásiť sa</button>
      </form>

     
  </div>

  <script>
    function handleLogin(e) {
      e.preventDefault();
      window.location.href = 'index.html';
    }
    function togglePwd(id, btn) {
      const input = document.getElementById(id);
      input.type = input.type === 'password' ? 'text' : 'password';
      btn.textContent = input.type === 'password' ? '👁' : '🙈';
    }
  </script>
</body>
</html>