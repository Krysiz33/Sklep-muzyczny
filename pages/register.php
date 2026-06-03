<?php
require_once '../includes/config.php';

if (isset($_SESSION['user_id'])) {
  header("Location: ../index.php");
  exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $name = $_POST['name'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $password2 = $_POST['password2'];

  if ($name == '' || $email == '' || $password == '' || $password2 == '') {
    $error = 'Wypełnij wszystkie pola.';
  } else if ($password != $password2) {
    $error = 'Hasła nie są identyczne.';
  } else {
    $db = getDB();
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
  <meta charset="UTF-8" />
  <title>Rejestracja — Resonance</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link
    href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;600;700&family=Barlow+Condensed:wght@700;900&display=swap"
    rel="stylesheet" />
</head>

<body class="bg-[#0a0a0b] text-white font-['Barlow'] min-h-screen flex flex-col items-center justify-center px-4">

  <a href="../index.php"
    class="font-['Barlow_Condensed'] font-black text-2xl tracking-widest mb-3 hover:text-[#e8ff3d]">RESONANCE</a>
  <p class="text-white/40 text-sm mb-10">Sklep Muzyczny</p>

  <div class="w-full max-w-md bg-[#111113] border border-white/10 p-8">

    <div class="flex border-b border-white/10 mb-8">
      <a href="login.php"
        class="flex-1 text-center pb-3 text-sm font-bold uppercase tracking-widest text-white/40 hover:text-white">Logowanie</a>
      <a href="register.php"
        class="flex-1 text-center pb-3 text-sm font-bold uppercase tracking-widest text-[#e8ff3d] border-b-2 border-[#e8ff3d] -mb-px">Rejestracja</a>
    </div>

    <?php if ($error != '') { ?>
      <div class="border border-red-500/30 bg-red-500/10 text-red-400 text-sm px-4 py-3 mb-6"><?php echo $error; ?></div>
    <?php } ?>
    <?php if ($success != '') { ?>
      <div class="border border-green-500/30 bg-green-500/10 text-green-400 text-sm px-4 py-3 mb-6">
        <?php echo $success; ?></div>
    <?php } ?>

    <?php if ($success == '') { ?>
      <form method="POST">
        <div class="mb-5">
          <label class="block text-xs uppercase tracking-widest text-white/40 font-bold mb-2">Imię i nazwisko</label>
          <input type="text" name="name" placeholder="Jan Kowalski"
            class="w-full bg-[#18181c] border border-white/10 px-4 py-3 text-sm text-white placeholder:text-white/20 outline-none focus:border-[#e8ff3d]" />
        </div>
        <div class="mb-5">
          <label class="block text-xs uppercase tracking-widest text-white/40 font-bold mb-2">Adres email</label>
          <input type="email" name="email" placeholder="twoj@email.pl"
            class="w-full bg-[#18181c] border border-white/10 px-4 py-3 text-sm text-white placeholder:text-white/20 outline-none focus:border-[#e8ff3d]" />
        </div>

        <div class="mb-5">
          <label class="block text-xs uppercase tracking-widest text-white/40 font-bold mb-2">Hasło</label>
          <input type="password" name="password" placeholder="••••••••"
            class="w-full bg-[#18181c] border border-white/10 px-4 py-3 text-sm text-white placeholder:text-white/20 outline-none focus:border-[#e8ff3d]" />
        </div>
        <div class="mb-6">
          <label class="block text-xs uppercase tracking-widest text-white/40 font-bold mb-2">Powtórz hasło</label>
          <input type="password" name="password2" placeholder="••••••••"
            class="w-full bg-[#18181c] border border-white/10 px-4 py-3 text-sm text-white placeholder:text-white/20 outline-none focus:border-[#e8ff3d]" />
        </div>
        <button type="submit"
          style="width:100%;background:#e8ff3d;color:black;font-weight:900;font-size:13px;text-transform:uppercase;letter-spacing:0.15em;padding:14px;border:none;cursor:pointer;">
          Utwórz konto
        </button>
      </form>
    <?php } ?>

    <p class="text-center text-sm text-white/40 mt-6">
      Masz już konto? <a href="login.php" class="text-[#e8ff3d] font-bold hover:opacity-80">Zaloguj się</a>
    </p>
  </div>

</body>

</html>