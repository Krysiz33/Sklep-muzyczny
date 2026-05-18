-- phpMyAdmin SQL Dump
-- Host: localhost
-- Generation Time: 18 Maj 2026

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE DATABASE IF NOT EXISTS music_store CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE music_store;

-- --------------------------------------------------------
-- Tabela: roles
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS roles (
    id   INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE
);

INSERT INTO roles (id, name) VALUES
(1, 'admin'),
(2, 'manager'),
(3, 'dostawca'),
(4, 'klient');

-- --------------------------------------------------------
-- Tabela: users
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS users (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(100) NOT NULL,
    email      VARCHAR(150) NOT NULL UNIQUE,
    password   VARCHAR(255) NOT NULL,
    role_id    INT DEFAULT 4,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(id)
);

-- Hasla zostana ustawione przez create_admin.php
-- admin@admin.pl    / admin123  (role: admin)
-- test@test.pl      / test123   (role: klient)
-- dostawca@test.pl  / test123   (role: dostawca)

INSERT INTO users (id, name, email, password, role_id) VALUES
(1, 'Admin',    'admin@admin.pl',   'ZMIEN_PRZEZ_CREATE_ADMIN', 1),
(2, 'Test',     'test@test.pl',     'ZMIEN_PRZEZ_CREATE_ADMIN', 4),
(3, 'Dostawca', 'dostawca@test.pl', 'ZMIEN_PRZEZ_CREATE_ADMIN', 3);

-- --------------------------------------------------------
-- Tabela: categories
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS categories (
    id   INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

INSERT INTO categories (id, name) VALUES
(1, 'Gitary'),
(2, 'Basy'),
(3, 'Perkusja'),
(4, 'Klawiszowce'),
(5, 'Akcesoria');

-- --------------------------------------------------------
-- Tabela: products
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS products (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(200) NOT NULL,
    category_id INT,
    price       DECIMAL(10,2) NOT NULL,
    stock       INT DEFAULT 0,
    image       VARCHAR(255),
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

COMMIT;
