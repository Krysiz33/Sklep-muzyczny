<?php
require_once '../includes/config.php';

if (isLoggedIn()) {
    header("Location: ../index.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = $_POST['email'];
    $password = $_POST['password'];

    if ($email === '' || $password === '') {
        $error = 'Wypełnij wszystkie pola.';
    } else {
        $db   = getDB();
        $stmt = $db->prepare("SELECT u.id, u.name, u.password, r.name AS role FROM users u JOIN roles r ON u.role_id = r.id WHERE u.email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user   = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];
            header("Location: ../index.php");
            exit;
        } else {
            $error = 'Nieprawidłowy email lub hasło.';
        }

        $db->close();
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Logowanie</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<h2>Logowanie</h2>

<?php if ($error !== '') { ?>
    <p class="alert-error"><?php echo $error; ?></p>
<?php } ?>

<form method="POST">
    <label>Email</label><br>
    <input type="email" name="email"><br><br>

    <label>Hasło</label><br>
    <input type="password" name="password"><br><br>

    <button type="submit">Zaloguj się</button>
</form>

<p>Nie masz konta? <a href="register.php">Zarejestruj się</a></p>

</body>
</html>
