<?php
require_once '../includes/config.php';
requireRole(array('admin', 'manager', 'dostawca'));

$db = getDB();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'add') {
  $name = $_POST['name'];
  $category_id = $_POST['category_id'];
  $price = $_POST['price'];
  $stock = $_POST['stock'];
  $image = '';

  if ($_FILES['image']['name'] != '') {
    $filename = time() . '_' . $_FILES['image']['name'];
    move_uploaded_file($_FILES['image']['tmp_name'], '../uploads/' . $filename);
    $image = $filename;
  }

  $stmt = $db->prepare("INSERT INTO products (name, category_id, price, stock, image) VALUES (?, ?, ?, ?, ?)");
  $stmt->bind_param("sidis", $name, $category_id, $price, $stock, $image);
  $stmt->execute();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'delete') {
  $id = $_POST['id'];
  $stmt = $db->prepare("DELETE FROM products WHERE id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'change_role') {
  if ($_SESSION['user_role'] == 'admin') {
    $user_id = $_POST['user_id'];
    $role_id = $_POST['role_id'];
    $stmt = $db->prepare("UPDATE users SET role_id = ? WHERE id = ?");
    $stmt->bind_param("ii", $role_id, $user_id);
    $stmt->execute();
  }
}

if (isset($_GET['sort'])) {
  $sort = $_GET['sort'];
} else {
  $sort = 'id';
}
if ($sort != 'name' && $sort != 'price') {
  $sort = 'id';
}

if (isset($_GET['section'])) {
  $section = $_GET['section'];
} else {
  $section = 'produkty';
}

$products = $db->query("SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.$sort ASC");
$categories = $db->query("SELECT * FROM categories");
$users = $db->query("SELECT u.id, u.name, u.email, u.created_at, r.name AS role_name, r.id AS role_id FROM users u JOIN roles r ON u.role_id = r.id ORDER BY u.id ASC");
?>
<!DOCTYPE html>
<html lang="pl">

<head>
  <meta charset="UTF-8" />
  <title>Panel admina — Resonance</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link
    href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;600;700&family=Barlow+Condensed:wght@700;900&display=swap"
    rel="stylesheet" />
</head>

<body class="bg-[#0a0a0b] text-white font-['Barlow']">


  <header
    class="fixed top-0 left-0 right-0 z-50 h-14 bg-[#111113] border-b border-white/10 flex items-center px-6 gap-4">
    <a href="../index.php"
      class="font-['Barlow_Condensed'] font-black text-lg tracking-widest hover:text-[#e8ff3d]">RESONANCE</a>
    <span class="text-white/20">|</span>
    <span class="text-white/40 text-xs uppercase tracking-widest font-bold">Panel administracyjny</span>
    <div class="flex-1"></div>
    <div class="flex items-center gap-3">
      <div class="w-8 h-8 bg-[#e8ff3d] flex items-center justify-center text-black text-xs font-black">
        <?php echo strtoupper(substr($_SESSION['user_name'], 0, 2)); ?>
      </div>
      <span class="text-white text-sm"><?php echo $_SESSION['user_name']; ?></span>
    </div>
  </header>

  <div class="flex pt-14 min-h-screen">


    <aside class="fixed top-14 left-0 bottom-0 w-52 bg-[#111113] border-r border-white/10 flex flex-col z-40">
      <nav class="flex-1 p-3 pt-6">
        <?php if ($section == 'uzytkownicy') { ?>
          <a href="dashboard.php?section=uzytkownicy"
            class="flex items-center gap-3 px-3 py-2.5 mb-1 text-sm font-semibold text-[#e8ff3d] bg-[#e8ff3d]/10 border-l-2 border-[#e8ff3d]">Użytkownicy</a>
        <?php } else { ?>
          <a href="dashboard.php?section=uzytkownicy"
            class="flex items-center gap-3 px-3 py-2.5 mb-1 text-sm font-semibold text-white/40 hover:text-white border-l-2 border-transparent hover:bg-white/5">Użytkownicy</a>
        <?php } ?>
        <?php if ($section == 'klienci') { ?>
          <a href="dashboard.php?section=klienci"
            class="flex items-center gap-3 px-3 py-2.5 mb-1 text-sm font-semibold text-[#e8ff3d] bg-[#e8ff3d]/10 border-l-2 border-[#e8ff3d]">Klienci</a>
        <?php } else { ?>
          <a href="dashboard.php?section=klienci"
            class="flex items-center gap-3 px-3 py-2.5 mb-1 text-sm font-semibold text-white/40 hover:text-white border-l-2 border-transparent hover:bg-white/5">Klienci</a>
        <?php } ?>
        <?php if ($section == 'produkty') { ?>
          <a href="dashboard.php?section=produkty"
            class="flex items-center gap-3 px-3 py-2.5 mb-1 text-sm font-semibold text-[#e8ff3d] bg-[#e8ff3d]/10 border-l-2 border-[#e8ff3d]">Produkty</a>
        <?php } else { ?>
          <a href="dashboard.php?section=produkty"
            class="flex items-center gap-3 px-3 py-2.5 mb-1 text-sm font-semibold text-white/40 hover:text-white border-l-2 border-transparent hover:bg-white/5">Produkty</a>
        <?php } ?>
        <?php if ($section == 'platnosci') { ?>
          <a href="dashboard.php?section=platnosci"
            class="flex items-center gap-3 px-3 py-2.5 mb-1 text-sm font-semibold text-[#e8ff3d] bg-[#e8ff3d]/10 border-l-2 border-[#e8ff3d]">Płatności</a>
        <?php } else { ?>
          <a href="dashboard.php?section=platnosci"
            class="flex items-center gap-3 px-3 py-2.5 mb-1 text-sm font-semibold text-white/40 hover:text-white border-l-2 border-transparent hover:bg-white/5">Płatności</a>
        <?php } ?>
      </nav>
      <div class="p-3 border-t border-white/10">
        <a href="logout.php"
          class="flex items-center gap-3 px-3 py-2.5 text-sm text-white/40 hover:text-white border-l-2 border-transparent">Wyloguj
          się</a>
      </div>
    </aside>

    <main class="ml-52 flex-1 p-8">

      <?php if ($section == 'produkty') { ?>


        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:30px;">
          <h1 style="font-size:30px;font-weight:900;letter-spacing:0.03em;">Produkty</h1>
          <button onclick="document.getElementById('addForm').classList.toggle('hidden')"
            class="bg-[#e8ff3d] text-black font-black text-sm uppercase tracking-widest px-5 py-2.5 hover:opacity-90">
            + Dodaj produkt
          </button>
        </div>

        <div id="addForm" class="hidden bg-[#111113] border border-white/10 p-6 mb-8">
          <h3 style="margin-bottom:16px;font-weight:bold;font-size:15px;">Nowy produkt</h3>
          <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="add" />
            Nazwa: <input type="text" name="name" required
              style="background:#0a0a0b;border:1px solid #333;color:white;padding:6px 10px;margin-right:8px;" />
            Kategoria:
            <select name="category_id"
              style="background:#0a0a0b;border:1px solid #333;color:white;padding:6px 10px;margin-right:8px;">
              <?php while ($cat = $categories->fetch_assoc()) { ?>
                <option value="<?php echo $cat['id']; ?>"><?php echo $cat['name']; ?></option>
              <?php } ?>
            </select>
            Cena (zł): <input type="number" name="price" step="0.01" required
              style="background:#0a0a0b;border:1px solid #333;color:white;padding:6px 10px;margin-right:8px;width:100px;" />
            Stan: <input type="number" name="stock" value="0"
              style="background:#0a0a0b;border:1px solid #333;color:white;padding:6px 10px;margin-right:8px;width:70px;" />
            Zdjęcie: <input type="file" name="image" accept="image/*" style="color:white;margin-right:8px;" />
            <button type="submit"
              style="background:#e8ff3d;color:black;font-weight:bold;padding:7px 20px;border:none;cursor:pointer;margin-top:10px;">Dodaj</button>
          </form>
        </div>

        <div style="margin-bottom:12px;font-size:13px;color:#666;">
          Sortuj:
          <a href="?section=produkty&sort=name"
            style="color:<?php echo $sort == 'name' ? '#e8ff3d' : '#666'; ?>;text-decoration:none;margin:0 6px;">Nazwa</a> |
          <a href="?section=produkty&sort=price"
            style="color:<?php echo $sort == 'price' ? '#e8ff3d' : '#666'; ?>;text-decoration:none;margin:0 6px;">Cena</a> |
          <a href="?section=produkty&sort=id"
            style="color:<?php echo $sort == 'id' ? '#e8ff3d' : '#666'; ?>;text-decoration:none;margin:0 6px;">Domyślnie</a>
        </div>

        <table style="width:100%;border-collapse:collapse;background:#111113;border:1px solid #222;">
          <thead>
            <tr style="border-bottom:1px solid #333;">
              <th
                style="text-align:left;padding:10px 15px;font-size:11px;color:#666;text-transform:uppercase;letter-spacing:0.1em;">
                ID</th>
              <th
                style="text-align:left;padding:10px 15px;font-size:11px;color:#666;text-transform:uppercase;letter-spacing:0.1em;">
                Zdjęcie</th>
              <th
                style="text-align:left;padding:10px 15px;font-size:11px;color:#666;text-transform:uppercase;letter-spacing:0.1em;">
                Nazwa</th>
              <th
                style="text-align:left;padding:10px 15px;font-size:11px;color:#666;text-transform:uppercase;letter-spacing:0.1em;">
                Kategoria</th>
              <th
                style="text-align:left;padding:10px 15px;font-size:11px;color:#666;text-transform:uppercase;letter-spacing:0.1em;">
                Cena</th>
              <th
                style="text-align:left;padding:10px 15px;font-size:11px;color:#666;text-transform:uppercase;letter-spacing:0.1em;">
                Stan</th>
              <th
                style="text-align:left;padding:10px 15px;font-size:11px;color:#666;text-transform:uppercase;letter-spacing:0.1em;">
                Akcja</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($p = $products->fetch_assoc()) { ?>
              <tr style="border-bottom:1px solid #1a1a1a;">
                <td style="padding:10px 15px;color:#666;font-size:13px;"><?php echo $p['id']; ?></td>
                <td style="padding:10px 15px;">
                  <?php if ($p['image'] != '') { ?>
                    <img src="../uploads/<?php echo $p['image']; ?>" style="width:40px;height:40px;object-fit:cover;" />
                  <?php } else { ?>
                    <div style="width:40px;height:40px;background:#1a1a1a;border:1px solid #333;"></div>
                  <?php } ?>
                </td>
                <td style="padding:10px 15px;color:white;font-size:14px;font-weight:600;"><?php echo $p['name']; ?></td>
                <td style="padding:10px 15px;color:#888;font-size:13px;"><?php echo $p['category_name']; ?></td>
                <td style="padding:10px 15px;color:white;font-size:14px;font-weight:bold;"><?php echo $p['price']; ?> zł
                </td>
                <td style="padding:10px 15px;color:#888;font-size:13px;"><?php echo $p['stock']; ?> szt.</td>
                <td style="padding:10px 15px;">
                  <form method="POST" style="display:inline">
                    <input type="hidden" name="action" value="delete" />
                    <input type="hidden" name="id" value="<?php echo $p['id']; ?>" />
                    <button type="submit" onclick="return confirm('Na pewno usunąć?')"
                      style="background:transparent;border:1px solid #444;color:#888;padding:4px 12px;cursor:pointer;font-size:13px;">Usuń</button>
                  </form>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>

      <?php } else if ($section == 'uzytkownicy') { ?>


          <div class="mb-8">
            <h1 class="font-['Barlow_Condensed'] font-black text-4xl">Użytkownicy</h1>
            <p class="text-white/40 text-sm mt-1">Zarządzaj kontami i rolami użytkowników</p>
          </div>

        <?php if ($_SESSION['user_role'] != 'admin') { ?>
            <div class="border border-red-500/30 bg-red-500/10 text-red-400 px-5 py-4">Tylko administrator może zarządzać
              użytkownikami.</div>
        <?php } else { ?>

            <div class="bg-[#111113] border border-white/10">
              <div class="px-5 py-4 border-b border-white/10">
                <h2 class="text-white font-semibold">Lista użytkowników</h2>
              </div>
              <table class="w-full">
                <thead>
                  <tr class="border-b border-white/10 text-white/40 text-xs uppercase tracking-widest">
                    <th class="text-left px-5 py-3 font-bold">ID</th>
                    <th class="text-left px-5 py-3 font-bold">Użytkownik</th>
                    <th class="text-left px-5 py-3 font-bold">Email</th>
                    <th class="text-left px-5 py-3 font-bold">Rola</th>
                    <th class="text-left px-5 py-3 font-bold">Data rejestracji</th>
                    <th class="text-left px-5 py-3 font-bold">Zapisz</th>
                  </tr>
                </thead>
                <tbody>
                <?php while ($u = $users->fetch_assoc()) { ?>
                    <tr class="border-b border-white/[0.04] hover:bg-white/[0.02]">
                      <td class="px-5 py-4 text-white/40 text-sm"><?php echo $u['id']; ?></td>
                      <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                          <div
                            class="w-8 h-8 bg-[#18181c] border border-white/10 flex items-center justify-center text-xs font-black text-white/50">
                          <?php echo strtoupper(substr($u['name'], 0, 2)); ?>
                          </div>
                          <span class="text-white text-sm font-semibold"><?php echo $u['name']; ?></span>
                        </div>
                      </td>
                      <td class="px-5 py-4 text-white/50 text-sm"><?php echo $u['email']; ?></td>
                      <td class="px-5 py-4">
                        <form method="POST">
                          <input type="hidden" name="action" value="change_role" />
                          <input type="hidden" name="user_id" value="<?php echo $u['id']; ?>" />
                          <select name="role_id"
                            class="bg-[#0a0a0b] border border-white/10 px-3 py-1.5 text-sm text-white outline-none focus:border-[#e8ff3d] cursor-pointer">
                            <?php
                            $roles_fresh = $db->query("SELECT * FROM roles");
                            while ($r = $roles_fresh->fetch_assoc()) {
                              if ($r['id'] == $u['role_id']) {
                                echo '<option value="' . $r['id'] . '" selected>' . $r['name'] . '</option>';
                              } else {
                                echo '<option value="' . $r['id'] . '">' . $r['name'] . '</option>';
                              }
                            }
                            ?>
                          </select>
                      </td>
                      <td class="px-5 py-4 text-white/40 text-sm"><?php echo $u['created_at']; ?></td>
                      <td class="px-5 py-4">
                        <button type="submit"
                          class="bg-[#e8ff3d]/10 border border-[#e8ff3d]/25 text-[#e8ff3d] px-4 py-1.5 text-sm font-bold hover:bg-[#e8ff3d] hover:text-black transition-colors">Zapisz</button>
                        </form>
                      </td>
                    </tr>
                <?php } ?>
                </tbody>
              </table>
            </div>

        <?php } ?>

      <?php } else if ($section == 'klienci') { ?>
            <h1 style="font-size:28px;font-weight:900;margin-bottom:10px;">Klienci</h1>
            <p style="color:#666;">Sekcja w budowie.</p>

      <?php } else if ($section == 'platnosci') { ?>
              <h1 style="font-size:28px;font-weight:900;margin-bottom:10px;">Płatności</h1>
              <p style="color:#666;">Sekcja w budowie.</p>

      <?php } ?>

    </main>
  </div>

</body>

</html>