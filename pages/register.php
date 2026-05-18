<?php
require_once '../includes/config.php';

if (isLoggedIn()) {
    header("Location: ../index.php");
    exit;
}

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name      = $_POST['name'];
    $email     = $_POST['email'];
    $password  = $_POST['password'];
    $password2 = $_POST['password2'];

    if ($name === '' || $email === '' || $password === '' || $password2 === '') {
        $error = 'Wypełnij wszystkie pola.';
    } elseif ($password !== $password2) {
        $error = 'Hasła nie są identyczne.';
    } else {
        $db   = getDB();
        $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = 'Konto z tym emailem już istnieje.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $db->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $email, $hash);
            $stmt->execute();
            $success = 'Konto zostało utworzone! Możesz się teraz zalogować.';
        }

        $db->close();
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Rejestracja</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<h2>Rejestracja</h2>

<?php if ($error !== '') { ?>
    <p class="alert-error"><?php echo $error; ?></p>
<?php } ?>

<?php if ($success !== '') { ?>
    <p class="alert-success"><?php echo $success; ?></p>
<?php } ?>

<?php if ($success === '') { ?>
<form method="POST">
    <label>Imię i nazwisko</label><br>
    <input type="text" name="name"><br><br>

    <label>Email</label><br>
    <input type="email" name="email"><br><br>

    <label>Hasło</label><br>
    <input type="password" name="password"><br><br>

    <label>Powtórz hasło</label><br>
    <input type="password" name="password2"><br><br>

    <button type="submit">Utwórz konto</button>
</form>
<?php } ?>

<p>Masz już konto? <a href="login.php">Zaloguj się</a></p>

</body>
</html>
