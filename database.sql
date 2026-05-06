-- ============================================
-- Todo List App - Database Setup
-- Step 1: database.sql
-- ============================================

-- Create the database
CREATE DATABASE IF NOT EXISTS todo_app;
USE todo_app;

-- ============================================
-- TABLE: users
-- Stores user accounts
-- ============================================
CREATE TABLE IF NOT EXISTS users (
    id       INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50)  NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL          -- will store hashed passwords later
);

-- ============================================
-- TABLE: tasks
-- Stores tasks belonging to users
-- ============================================
CREATE TABLE IF NOT EXISTS tasks (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    user_id    INT          NOT NULL,
    title      VARCHAR(255) NOT NULL,
    status     ENUM('pending', 'done') NOT NULL DEFAULT 'pending',
    created_at DATETIME                NOT NULL DEFAULT CURRENT_TIMESTAMP,

    -- Link each task to a user
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ============================================
-- DUMMY DATA: users
-- Passwords are plain text for now (we'll hash them in the login step)
-- ============================================
INSERT INTO users (username, password) VALUES
    ('alice', 'alice123'),
    ('bob',   'bob123');

-- ============================================
-- DUMMY DATA: tasks
-- user_id 1 = alice, user_id 2 = bob
-- ============================================
INSERT INTO tasks (user_id, title, status) VALUES
    (1, 'Buy groceries',         'pending'),
    (1, 'Read a PHP book',       'done'),
    (1, 'Finish homework',       'pending'),
    (2, 'Clean the apartment',   'pending'),
    (2, 'Go for a morning run',  'done');
