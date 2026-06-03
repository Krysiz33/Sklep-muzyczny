<?php
require_once '../includes/config.php';

if (isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($email == '' || $password == '') {
        $error = 'Wypełnij wszystkie pola.';
    } else {
        $db = getDB();
        $stmt = $db->prepare("SELECT u.id, u.name, u.password, r.name AS role FROM users u JOIN roles r ON u.role_id = r.id WHERE u.email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
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
  <meta charset="UTF-8" />
  <title>Logowanie — Resonance</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;600;700&family=Barlow+Condensed:wght@700;900&display=swap" rel="stylesheet" />
</head>
<!-- TODO: przepisac na tailwind jak index -->
<body style="background: #0a0a0b; color: white; font-family: sans-serif; min-height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 20px;">

  <a href="../index.php" style="font-size: 22px; font-weight: 900; letter-spacing: 0.3em; text-decoration: none; color: white; margin-bottom: 8px;">RESONANCE</a>
  <p style="color: #666; font-size: 13px; margin-bottom: 30px;">Sklep Muzyczny</p>

  <div style="width: 100%; max-width: 420px; background: #111113; border: 1px solid rgba(255,255,255,0.1); padding: 32px;">

    <div style="display: flex; border-bottom: 1px solid #222; margin-bottom: 28px;">
      <a href="login.php" style="flex: 1; text-align: center; padding-bottom: 12px; font-size: 13px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.15em; text-decoration: none; color: #e8ff3d; border-bottom: 2px solid #e8ff3d; margin-bottom: -1px;">Logowanie</a>
      <a href="register.php" style="flex: 1; text-align: center; padding-bottom: 12px; font-size: 13px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.15em; text-decoration: none; color: #666;">Rejestracja</a>
    </div>

    <?php if ($error != '') { ?>
      <div style="background: rgba(255,0,0,0.1); border: 1px solid rgba(255,0,0,0.3); color: #f87171; padding: 12px 16px; margin-bottom: 20px; font-size: 14px;">
        <?php echo $error; ?>
      </div>
    <?php } ?>

    <form method="POST">
      <div style="margin-bottom: 18px;">
        <label style="display: block; font-size: 11px; text-transform: uppercase; letter-spacing: 0.15em; color: #666; font-weight: bold; margin-bottom: 8px;">Adres email</label>
        <input type="email" name="email" placeholder="twoj@email.pl" style="width: 100%; background: #18181c; border: 1px solid #333; padding: 12px 16px; font-size: 14px; color: white; outline: none; box-sizing: border-box;" />
      </div>
      <div style="margin-bottom: 24px;">
        <label style="display: block; font-size: 11px; text-transform: uppercase; letter-spacing: 0.15em; color: #666; font-weight: bold; margin-bottom: 8px;">Hasło</label>
        <input type="password" name="password" placeholder="••••••••" style="width: 100%; background: #18181c; border: 1px solid #333; padding: 12px 16px; font-size: 14px; color: white; outline: none; box-sizing: border-box;" />
      </div>
      <button type="submit" style="width: 100%; background: #e8ff3d; color: black; font-weight: 900; font-size: 13px; text-transform: uppercase; letter-spacing: 0.15em; padding: 14px; border: none; cursor: pointer;">
        Zaloguj się
      </button>
    </form>

    <p style="text-align: center; font-size: 13px; color: #666; margin-top: 20px;">
      Nie masz konta? <a href="register.php" style="color: #e8ff3d; font-weight: bold; text-decoration: none;">Zarejestruj się</a>
    </p>
  </div>

</body>
</html>
