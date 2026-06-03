<?php
require_once '../includes/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $current = $_POST['current_password'];
    $new = $_POST['new_password'];
    $new2 = $_POST['new_password2'];

    if ($current == '' || $new == '' || $new2 == '') {
        $error = 'Wypełnij wszystkie pola.';
    } else if ($new != $new2) {
        $error = 'Nowe hasła nie są identyczne.';
    } else {
        $db = getDB();
        $stmt = $db->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if (!password_verify($current, $user['password'])) {
            $error = 'Obecne hasło jest nieprawidłowe.';
        } else {
            $hash = password_hash($new, PASSWORD_DEFAULT);
            $stmt = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->bind_param("si", $hash, $_SESSION['user_id']);
            $stmt->execute();
            $success = 'Hasło zostało zmienione pomyślnie.';
        }
        $db->close();
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8" />
  <title>Zmiana hasła — Resonance</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;600;700&family=Barlow+Condensed:wght@700;900&display=swap" rel="stylesheet" />
</head>
<body class="bg-[#0a0a0b] text-white font-['Barlow'] min-h-screen flex flex-col items-center justify-center px-4">

  <a href="../index.php" class="font-['Barlow_Condensed'] font-black text-2xl tracking-widest mb-3 hover:text-[#e8ff3d]">RESONANCE</a>
  <p class="text-white/40 text-sm mb-10">Ustawienia konta</p>

  <div class="w-full max-w-md bg-[#111113] border border-white/10 p-8">
    <h2 class="font-['Barlow_Condensed'] font-black text-2xl mb-6 uppercase tracking-wide">Zmiana hasła</h2>

    <?php if ($error != '') { ?>
      <div class="border border-red-500/30 bg-red-500/10 text-red-400 text-sm px-4 py-3 mb-6"><?php echo $error; ?></div>
    <?php } ?>
    <?php if ($success != '') { ?>
      <div class="border border-green-500/30 bg-green-500/10 text-green-400 text-sm px-4 py-3 mb-6"><?php echo $success; ?></div>
    <?php } ?>

    <form method="POST">
      <div style="margin-bottom: 18px;">
        <label style="display:block;font-size:11px;text-transform:uppercase;letter-spacing:0.15em;color:#666;font-weight:bold;margin-bottom:8px;">Obecne hasło</label>
        <input type="password" name="current_password" style="width:100%;background:#18181c;border:1px solid #333;padding:12px 16px;font-size:14px;color:white;outline:none;box-sizing:border-box;" />
      </div>
      <div style="margin-bottom: 18px;">
        <label style="display:block;font-size:11px;text-transform:uppercase;letter-spacing:0.15em;color:#666;font-weight:bold;margin-bottom:8px;">Nowe hasło</label>
        <input type="password" name="new_password" style="width:100%;background:#18181c;border:1px solid #333;padding:12px 16px;font-size:14px;color:white;outline:none;box-sizing:border-box;" />
      </div>
      <div style="margin-bottom: 24px;">
        <label style="display:block;font-size:11px;text-transform:uppercase;letter-spacing:0.15em;color:#666;font-weight:bold;margin-bottom:8px;">Powtórz nowe hasło</label>
        <input type="password" name="new_password2" style="width:100%;background:#18181c;border:1px solid #333;padding:12px 16px;font-size:14px;color:white;outline:none;box-sizing:border-box;" />
      </div>
      <button type="submit" class="w-full bg-[#e8ff3d] text-black font-black text-sm uppercase tracking-widest py-3 hover:opacity-90">
        Zmień hasło
      </button>
    </form>

    <p class="text-center text-sm text-white/40 mt-6">
      <a href="../index.php" class="text-[#e8ff3d] font-bold hover:opacity-80">← Wróć do sklepu</a>
    </p>
  </div>

</body>
</html>
