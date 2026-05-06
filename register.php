<!DOCTYPE html>
<html lang="sk">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Taskly — Registrácia</title>
  <link rel="stylesheet" href="style.css" />
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet"/>
</head>
<body class="page-auth">

  <nav class="navbar">
    <a href="index.php" class="logo">Taskly<span class="dot">.</span></a>
    <div class="nav-links">
      <a href="login.php" class="btn-ghost">Prihlásiť sa</a>
    </div>
  </nav>

  <div class="auth-layout">
    <div class="auth-deco reg-deco">
      <div class="deco-text">
        <h2>Začni dnes<span class="dot">.</span></h2>
        <p>Organizuj svoj život.<br/>Zadarmo, navždy.</p>
      </div>
      <div class="deco-shapes">
        <div class="shape s1"></div>
        <div class="shape s2"></div>
        <div class="shape s3"></div>
      </div>
      <ul class="feature-list">
        <li>✓ Neobmedzené úlohy</li>
        <li>✓ Kategórie a štítky</li>
        <li>✓ Pripomienky a termíny</li>
        <li>✓ Synchronizácia na všetkých zariadeniach</li>
      </ul>
    </div>

    <div class="auth-card">
      <h1 class="auth-title">Vytvoriť účet</h1>
      <p class="auth-sub">Už máš účet? <a href="login.php">Prihlásiť sa</a></p>

      <form class="auth-form" onsubmit="handleRegister(event)">
        <div class="form-row">
          <div class="form-group">
            <label>Meno</label>
            <input type="text" placeholder="Ján" required />
          </div>
          <div class="form-group">
            <label>Priezvisko</label>
            <input type="text" placeholder="Novák" required />
          </div>
        </div>
        <div class="form-group">
          <label>E-mail</label>
          <input type="email" placeholder="tvoj@email.sk" required />
        </div>
        <div class="form-group">
          <label>Heslo</label>
          <div class="input-wrapper">
            <input type="password" id="regPwd" placeholder="min. 8 znakov" required oninput="checkStrength(this.value)" />
            <button type="button" class="toggle-pwd" onclick="togglePwd('regPwd', this)">👁</button>
          </div>
          <div class="strength-bar">
            <div class="strength-fill" id="strengthFill"></div>
          </div>
          <span class="strength-label" id="strengthLabel"></span>
        </div>
        <div class="form-group">
      
        <button type="submit" class="btn-primary btn-full">Vytvoriť účet</button>
      </form>
    </div>
  </div>

  <script>
    function handleRegister(e) {
      e.preventDefault();
      const p1 = document.getElementById('regPwd').value;
      const p2 = document.getElementById('regPwd2').value;
      if (p1 !== p2) { alert('Heslá sa nezhodujú!'); return; }
      window.location.href = 'index.html';
    }
    function togglePwd(id, btn) {
      const input = document.getElementById(id);
      input.type = input.type === 'password' ? 'text' : 'password';
      btn.textContent = input.type === 'password' ? '👁' : '🙈';
    }
    function checkStrength(val) {
      const fill = document.getElementById('strengthFill');
      const label = document.getElementById('strengthLabel');
      let score = 0;
      if (val.length >= 8) score++;
      if (/[A-Z]/.test(val)) score++;
      if (/[0-9]/.test(val)) score++;
      if (/[^A-Za-z0-9]/.test(val)) score++;
      const levels = [
        { pct: '0%', color: 'transparent', text: '' },
        { pct: '25%', color: '#e74c3c', text: 'Slabé' },
        { pct: '50%', color: '#e67e22', text: 'Priemerné' },
        { pct: '75%', color: '#f1c40f', text: 'Dobré' },
        { pct: '100%', color: '#2ecc71', text: 'Silné' },
      ];
      fill.style.width = levels[score].pct;
      fill.style.background = levels[score].color;
      label.textContent = levels[score].text;
      label.style.color = levels[score].color;
    }
  </script>
</body>
</html>