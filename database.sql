-- Base de dados do laboratório de SQL Injection
-- Correr no phpMyAdmin (http://localhost/phpmyadmin) ou na consola MySQL.

CREATE DATABASE IF NOT EXISTS injection_lab;
USE injection_lab;

DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id       INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50)  NOT NULL,
    password VARCHAR(50)  NOT NULL
);

-- Utilizadores de teste.
-- Nota: passwords em texto simples apenas para fins didáticos.
-- Em produção usar password_hash() / password_verify().
INSERT INTO users (username, password) VALUES
    ('admin', 'admin123'),
    ('user',  'user123'),
    ('teste', '1234');
