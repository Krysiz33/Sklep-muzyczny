<?php
require_once '../includes/config.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current = $_POST['current_password'];
    $new     = $_POST['new_password'];
    $new2    = $_POST['new_password2'];

    if ($current === '' || $new === '' || $new2 === '') {
        $error = 'Wypełnij wszystkie pola.';
    } elseif ($new !== $new2) {
        $error = 'Nowe hasła nie są identyczne.';
    } else {
        $db   = getDB();
        $stmt = $db->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $user   = $result->fetch_assoc();

        if (!password_verify($current, $user['password'])) {
            $error = 'Obecne hasło jest nieprawidłowe.';
        } else {
            $hash = password_hash($new, PASSWORD_DEFAULT);
            $stmt = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->bind_param("si", $hash, $_SESSION['user_id']);
            $stmt->execute();
            $success = 'Hasło zostało zmienione.';
        }

        $db->close();
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Zmiana hasła</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<h2>Zmiana hasła</h2>

<?php if ($error !== '') { ?>
    <p class="alert-error"><?php echo $error; ?></p>
<?php } ?>

<?php if ($success !== '') { ?>
    <p class="alert-success"><?php echo $success; ?></p>
<?php } ?>

<form method="POST">
    <label>Obecne hasło</label><br>
    <input type="password" name="current_password"><br><br>

    <label>Nowe hasło</label><br>
    <input type="password" name="new_password"><br><br>

    <label>Powtórz nowe hasło</label><br>
    <input type="password" name="new_password2"><br><br>

    <button type="submit">Zmień hasło</button>
</form>

<p><a href="../index.php">← Wróć do sklepu</a></p>

</body>
</html>
