<?php
require_once 'includes/config.php';
?>
<!DOCTYPE html>
<html lang="pl">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Resonance — Sklep Muzyczny</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link
    href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;600;700&family=Barlow+Condensed:wght@700;900&display=swap"
    rel="stylesheet" />
  <style>
    body {
      font-family: sans-serif;
      background: #0a0a0b;
      color: white;
      margin: 0;
    }
  </style>
</head>

<body>


  <div
    style="background: #111; border-bottom: 1px solid #333; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center;">
    <a href="index.php" style="color: white; text-decoration: none; font-weight: bold; font-size: 20px;">RESONANCE</a>
    <div style="display: flex; gap: 20px;">
      <a href="#" style="color: #888; text-decoration: none; font-size: 14px;">Produkty</a>
      <a href="#" style="color: #888; text-decoration: none; font-size: 14px;">Gitary</a>
      <a href="#" style="color: #888; text-decoration: none; font-size: 14px;">Perkusja</a>
    </div>
    <div style="display: flex; gap: 15px; align-items: center;">
      <?php if (isset($_SESSION['user_id'])) { ?>
        <span style="color: #888; font-size: 14px;"><?php echo $_SESSION['user_name']; ?></span>
        <?php if ($_SESSION['user_role'] == 'admin' || $_SESSION['user_role'] == 'manager' || $_SESSION['user_role'] == 'dostawca') { ?>
          <a href="pages/dashboard.php" style="color: #888; text-decoration: none; font-size: 14px;">Dashboard</a>
        <?php } ?>
        <a href="pages/change-password.php" style="color: #888; text-decoration: none; font-size: 14px;">Zmień hasło</a>
        <a href="pages/logout.php" style="color: #888; text-decoration: none; font-size: 14px;">Wyloguj</a>
      <?php } else { ?>
        <a href="pages/login.php" style="color: #888; text-decoration: none; font-size: 14px;">Zaloguj się</a>
        <a href="pages/register.php"
          style="background: #e8ff3d; color: black; text-decoration: none; font-size: 14px; font-weight: bold; padding: 8px 16px;">Rejestracja</a>
      <?php } ?>
    </div>
  </div>


  <div style="padding: 80px 60px; min-height: 500px; background: #0a0a0b;">
    <p style="color: #e8ff3d; font-size: 12px; text-transform: uppercase; letter-spacing: 0.4em; margin-bottom: 20px;">
      Sklep Muzyczny</p>
    <h1 style="font-size: 64px; font-weight: 900; line-height: 1; margin-bottom: 20px;">
      BRZMIENIE BEZ<br><span style="color: #e8ff3d;">KOMPROMISÓW</span>
    </h1>
    <p style="color: #666; font-size: 16px; margin-bottom: 30px; max-width: 500px;">Profesjonalne instrumenty i sprzęt
      muzyczny od najlepszych producentów na świecie.</p>
    <div style="display: flex; gap: 15px;">
      <a href="#kategorie"
        style="background: #e8ff3d; color: black; text-decoration: none; font-weight: bold; padding: 14px 32px; font-size: 14px; text-transform: uppercase; letter-spacing: 0.1em;">Przeglądaj
        sklep</a>
      <a href="#"
        style="border: 1px solid #444; color: white; text-decoration: none; font-weight: bold; padding: 14px 32px; font-size: 14px; text-transform: uppercase; letter-spacing: 0.1em;">Katalog
        produktów</a>
    </div>
  </div>

  <section id="kategorie" class="px-16 py-20">
    <p class="text-[#e8ff3d] text-xs uppercase tracking-widest font-bold mb-2">Asortyment</p>
    <h2 class="font-['Barlow_Condensed'] font-black text-5xl mb-10">KATEGORIE</h2>
    <div class="grid grid-cols-4 gap-3">
      <div class="bg-[#111113] border border-white/10 p-6 cursor-pointer hover:border-[#e8ff3d] transition-colors">
        <p class="text-white font-semibold mb-1">Gitary elektryczne</p>
        <p class="text-white/40 text-sm">124 produkty</p>
      </div>
      <div class="bg-[#111113] border border-white/10 p-6 cursor-pointer hover:border-[#e8ff3d] transition-colors">
        <p class="text-white font-semibold mb-1">Gitary basowe</p>
        <p class="text-white/40 text-sm">87 produktów</p>
      </div>
      <div class="bg-[#111113] border border-white/10 p-6 cursor-pointer hover:border-[#e8ff3d] transition-colors">
        <p class="text-white font-semibold mb-1">Syntezatory</p>
        <p class="text-white/40 text-sm">63 produkty</p>
      </div>
      <div class="bg-[#111113] border border-white/10 p-6 cursor-pointer hover:border-[#e8ff3d] transition-colors">
        <p class="text-white font-semibold mb-1">Perkusja</p>
        <p class="text-white/40 text-sm">41 produktów</p>
      </div>

    </div>
  </section>


  <section style="padding: 60px; background: #111113; border-top: 1px solid #222;">
    <h2 style="font-size: 22px; font-weight: bold; margin-bottom: 25px;">Polecane produkty</h2>
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px;">
      <div style="background: #0a0a0b; border: 1px solid #222;">
        <img src="https://images.unsplash.com/photo-1510915361894-db8b60106cb1?w=480&h=480&fit=crop"
          style="width: 100%; aspect-ratio: 1; object-fit: cover;" />
        <div style="padding: 16px;">
          <p
            style="color: #e8ff3d; font-size: 11px; text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 8px;">
            Gitary elektryczne</p>
          <p style="color: white; font-weight: 600; margin-bottom: 12px;">Gibson Les Paul Standard '60s</p>
          <div style="display: flex; justify-content: space-between; align-items: center;">
            <span style="font-size: 22px; font-weight: 900;">2 899 zł</span>
            <button
              style="background: #e8ff3d; color: black; border: none; padding: 8px 16px; font-weight: bold; font-size: 12px; cursor: pointer; text-transform: uppercase;">Do
              koszyka</button>
          </div>
        </div>
      </div>
      <div style="background: #0a0a0b; border: 1px solid #222;">
        <img src="https://images.unsplash.com/photo-1551972873-b7e8754e8e26?w=480&h=480&fit=crop"
          style="width: 100%; aspect-ratio: 1; object-fit: cover;" />
        <div style="padding: 16px;">
          <p
            style="color: #e8ff3d; font-size: 11px; text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 8px;">
            Syntezatory</p>
          <p style="color: white; font-weight: 600; margin-bottom: 12px;">Moog Subsequent 37</p>
          <div style="display: flex; justify-content: space-between; align-items: center;">
            <span style="font-size: 22px; font-weight: 900;">6 299 zł</span>
            <button
              style="background: #e8ff3d; color: black; border: none; padding: 8px 16px; font-weight: bold; font-size: 12px; cursor: pointer; text-transform: uppercase;">Do
              koszyka</button>
          </div>
        </div>
      </div>
      <div style="background: #0a0a0b; border: 1px solid #222;">
        <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=480&h=480&fit=crop"
          style="width: 100%; aspect-ratio: 1; object-fit: cover;" />
        <div style="padding: 16px;">
          <p
            style="color: #e8ff3d; font-size: 11px; text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 8px;">
            Słuchawki</p>
          <p style="color: white; font-weight: 600; margin-bottom: 12px;">Audio-Technica ATH-M50x</p>
          <div style="display: flex; justify-content: space-between; align-items: center;">
            <span style="font-size: 22px; font-weight: 900;">649 zł</span>
            <button
              style="background: #e8ff3d; color: black; border: none; padding: 8px 16px; font-weight: bold; font-size: 12px; cursor: pointer; text-transform: uppercase;">Do
              koszyka</button>
          </div>
        </div>
      </div>
    </div>
  </section>


  <footer style="padding: 40px 60px; border-top: 1px solid #222;">
    <div style="display: flex; justify-content: space-between; margin-bottom: 30px;">
      <div>
        <p style="font-weight: bold; font-size: 18px; margin-bottom: 10px;">RESONANCE</p>
        <p style="color: #666; font-size: 14px;">Sklep muzyczny</p>
      </div>
      <div>
        <p style="font-weight: bold; margin-bottom: 10px; font-size: 14px;">Sklep</p>
        <div style="display: flex; flex-direction: column; gap: 8px;">
          <a href="#" style="color: #666; text-decoration: none; font-size: 13px;">Gitary elektryczne</a>
          <a href="#" style="color: #666; text-decoration: none; font-size: 13px;">Syntezatory</a>
          <a href="#" style="color: #666; text-decoration: none; font-size: 13px;">Perkusja</a>
        </div>
      </div>
      <div>
        <p style="font-weight: bold; margin-bottom: 10px; font-size: 14px;">Kontakt</p>
        <p style="color: #666; font-size: 13px;">sklep@resonance.pl</p>
      </div>
    </div>
    <div style="border-top: 1px solid #222; padding-top: 20px;">
      <p style="color: #444; font-size: 13px;">© <?php echo date('Y'); ?> Resonance Sklep Muzyczny</p>
    </div>
  </footer>

</body>

</html>