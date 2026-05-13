# Projekt_PRO 
# Taskly — PHP Todo aplikácia

Jednoduchá Todo List aplikácia s používateľskými účtami. Postavená na čistom PHP, MySQL (mysqli) a vanilla JS — žiadne frameworky.

---

## Čo aplikácia vie

- Registrácia a prihlásenie používateľa (session)
- Každý používateľ vidí len svoje vlastné úlohy
- Pridávanie nových úloh (Create)
- Zobrazenie zoznamu úloh (Read)
- Inline editácia názvu úlohy (Update)
- Prepínanie stavu pending ↔ done (Update)
- Mazanie úloh s potvrdením (Delete)
- Progress bar ukazujúci koľko úloh je hotových
- Bezpečné hašovanie hesiel (bcrypt)

---

## Štruktúra súborov

```
todo_app/
├── database.sql    # Vytvorenie DB, tabuliek a testovacích dát
├── db.php          # Pripojenie k MySQL (mysqli)
├── login.php       # Prihlásenie + spracovanie POST formulára
├── register.php    # Registrácia + validácia + hašovanie hesla
├── logout.php      # Zničenie session + presmerovanie
├── index.php       # Hlavná stránka — všetky CRUD operácie
├── style.css       # Celý dizajn (dark theme)
└── README.md       # Tento súbor
```

---

## Inštalácia

### 1. Požiadavky

- XAMPP (Apache + MySQL + PHP 8.x)
- Webový prehliadač

### 2. Skopíruj súbory

Celý priečinok `todo_app` skopíruj do:
```
C:\xampp\htdocs\todo_app\
```

### 3. Spusti XAMPP

Otvor XAMPP Control Panel a klikni **Start** pri:
- Apache
- MySQL

### 4. Importuj databázu

Otvor `http://localhost/phpmyadmin` a:
1. Klikni na záložku **Import**
2. Vyber súbor `database.sql`
3. Klikni **Vykonať**

### 5. Otvor aplikáciu

```
http://localhost/todo_app/login.php
```

Testovacie účty:
| Meno    | Heslo    |
|---------|----------|
| alice   | alice123 |
| bob     | bob123   |

---

## Databázová štruktúra

```sql
users
  id         INT  PRIMARY KEY AUTO_INCREMENT
  username   VARCHAR(50)  UNIQUE NOT NULL
  password   VARCHAR(255) NOT NULL  -- bcrypt hash

tasks
  id         INT  PRIMARY KEY AUTO_INCREMENT
  user_id    INT  NOT NULL → users(id) ON DELETE CASCADE
  title      VARCHAR(255) NOT NULL
  status     ENUM('pending','done') DEFAULT 'pending'
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
```

Vzťah: jeden user má viacero tasks (1:N).

---

## Bezpečnosť

**Prepared statements** — ochrana proti SQL Injection:
```php
// SPRÁVNE
$stmt = mysqli_prepare($conn, 'SELECT * FROM users WHERE username = ?');
mysqli_stmt_bind_param($stmt, 's', $username);

// NEBEZPEČNÉ — nikdy takto!
$result = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");
```

**Hašovanie hesiel** — nikdy plain text v DB:
```php
// Pri registrácii
$hash = password_hash($password, PASSWORD_DEFAULT);  // bcrypt

// Pri prihlásení
password_verify($password, $hash);  // true / false
```

**htmlspecialchars()** — ochrana proti XSS pri výpise dát:
```php
echo htmlspecialchars($task['title']);  // bezpečné
echo $task['title'];                   // nebezpečné
```

**Session ochrana** — každá stránka overí prihlásenie:
```php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
```

**user_id v každom SQL dotaze** — user nemôže mazať cudzie úlohy:
```php
'DELETE FROM tasks WHERE id = ? AND user_id = ?'
//                                ^^^^^^^^^^^^
//                    Bez tohto by mohol ktokoľvek mazať čokoľvek
```

---

## Ako funguje tok aplikácie

```
register.php  →  login.php  →  index.php  →  logout.php
                                   ↑
                              (CRUD akcie)
                         add / toggle / delete / edit
                         (všetko POST na index.php)
```

Každá POST akcia je rozlíšená skrytým poľom `action` vo formulári. Po spracovaní nasleduje redirect (PRG pattern), aby sa formulár neodoslal znova pri obnovení stránky.

---

## Technológie

| Vrstva     | Technológia               |
|------------|---------------------------|
| Backend    | PHP 8.x  |
| Databáza   | MySQL + mysqli            |
| Frontend   | HTML5 + CSS3 + vanilla JS |
| Sessions   | PHP $_SESSION             |
