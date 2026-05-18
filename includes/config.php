<?php
session_start();

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'music_store');

function getDB() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        die("Błąd połączenia z bazą: " . $conn->connect_error);
    }
    $conn->set_charset("utf8mb4");
    return $conn;
}

function isLoggedIn() {
    if (isset($_SESSION['user_id'])) {
        return true;
    } else {
        return false;
    }
}

function requireRole($allowed_roles) {
    if (!isLoggedIn()) {
        header("Location: ../pages/login.php");
        exit;
    }

    $user_role = $_SESSION['user_role'];
    $has_access = false;

    foreach ($allowed_roles as $role) {
        if ($user_role === $role) {
            $has_access = true;
        }
    }

    if ($has_access === false) {
        die("Brak dostępu.");
    }
}
