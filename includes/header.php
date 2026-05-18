<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Resonance — Sklep Muzyczny' ?></title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<nav>
    <a href="../index.php" class="logo">Resonance</a>
    <ul>
        <li><a href="../index.php">Strona główna</a></li>
        <li><a href="#">Gitary</a></li>
        <li><a href="#">Perkusja</a></li>
        <li><a href="#">Akcesoria</a></li>
    </ul>
    <div class="nav-auth">
        <?php if (isLoggedIn()): ?>
            <span>Cześć, <?= htmlspecialchars($_SESSION['user_name']) ?>!</span>
            <a href="../pages/change-password.php">Zmień hasło</a>
            <a href="../pages/logout.php">Wyloguj</a>
        <?php else: ?>
            <a href="../pages/login.php">Zaloguj się</a>
            <a href="../pages/register.php" class="btn">Rejestracja</a>
        <?php endif; ?>
    </div>
</nav>

<main>
