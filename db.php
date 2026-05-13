<?php
// ============================================================
// db.php — Pripojenie k databáze
// ============================================================
// Tento súbor je "most" medzi PHP a MySQL.
// Každý iný PHP súbor ho zahrnie cez: require_once 'db.php';
// ============================================================

define('DB_HOST', 'localhost');   // Server — XAMPP vždy localhost
define('DB_USER', 'root');        // Predvolený XAMPP používateľ
define('DB_PASS', 'root'); 
define('DB_NAME', 'todo_app');    // Názov databázy z database.sql

// Vytvor pripojenie
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Skontroluj či sa pripojenie podarilo
if (!$conn) {
    // die() zastav skript a vypíše chybu
    die('Chyba pripojenia k databáze: ' . mysqli_connect_error());
}

// Nastav kódovanie na UTF-8 (kvôli diakritic)
mysqli_set_charset($conn, 'utf8mb4');

