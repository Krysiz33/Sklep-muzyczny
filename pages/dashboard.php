<?php
require_once '../includes/config.php';
requireRole(array('admin', 'manager', 'dostawca'));

$db = getDB();


if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'add') {
    $name = $_POST['name'];
    $category_id = $_POST['category_id'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $image = '';

    if ($_FILES['image']['name'] !== '') {
        $filename = time() . '_' . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], '../uploads/' . $filename);
        $image = $filename;
    }

    $stmt = $db->prepare("INSERT INTO products (name, category_id, price, stock, image) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sidis", $name, $category_id, $price, $stock, $image);
    $stmt->execute();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'delete') {
    $id = $_POST['id'];
    $stmt = $db->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'change_role') {
    if ($_SESSION['user_role'] === 'admin') {
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

if ($sort !== 'name' && $sort !== 'price') {
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
$roles = $db->query("SELECT * FROM roles");
?>
<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: sans-serif;
        }

        #header {
            padding: 10px 20px;
            border-bottom: 1px solid #ccc;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        #layout {
            display: flex;
            height: calc(100vh - 41px);
        }

        #sidebar {
            width: 150px;
            border-right: 1px solid #ccc;
            padding-top: 10px;
        }

        #sidebar a {
            display: block;
            padding: 8px 15px;
            text-decoration: none;
            color: #333;
        }

        #sidebar a:hover {
            background: #eee;
        }

        #sidebar a.active {
            background: #eee;
            font-weight: bold;
        }

        #content {
            padding: 20px;
            flex: 1;
            overflow-y: auto;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 6px 10px;
            font-size: 0.9rem;
        }

        th {
            background: #f5f5f5;
        }

        input,
        select {
            padding: 4px 6px;
            border: 1px solid #ccc;
            margin-right: 4px;
            font-size: 0.9rem;
        }

        button {
            padding: 4px 10px;
            cursor: pointer;
        }

        hr {
            margin: 15px 0;
            border: none;
            border-top: 1px solid #ddd;
        }
    </style>
</head>

<body>

    <div id="header">
        <strong>Resonance Dashboard</strong>
        <span>
            <?php echo $_SESSION['user_name']; ?> (<?php echo $_SESSION['user_role']; ?>)
            | <a href="../index.php">Strona główna</a>
            | <a href="logout.php">Wyloguj</a>
        </span>
    </div>

    <div id="layout">

        <div id="sidebar">
            <?php if ($section === 'uzytkownicy') { ?>
                <a href="dashboard.php?section=uzytkownicy" class="active">Użytkownicy</a>
            <?php } else { ?>
                <a href="dashboard.php?section=uzytkownicy">Użytkownicy</a>
            <?php } ?>

            <?php if ($section === 'klienci') { ?>
                <a href="dashboard.php?section=klienci" class="active">Klienci</a>
            <?php } else { ?>
                <a href="dashboard.php?section=klienci">Klienci</a>
            <?php } ?>

            <?php if ($section === 'produkty') { ?>
                <a href="dashboard.php?section=produkty" class="active">Produkty</a>
            <?php } else { ?>
                <a href="dashboard.php?section=produkty">Produkty</a>
            <?php } ?>

            <?php if ($section === 'platnosci') { ?>
                <a href="dashboard.php?section=platnosci" class="active">Płatności</a>
            <?php } else { ?>
                <a href="dashboard.php?section=platnosci">Płatności</a>
            <?php } ?>
        </div>

        <div id="content">

            <?php if ($section === 'produkty') { ?>

                <h3>Dodaj produkt</h3>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="add">
                    Nazwa: <input type="text" name="name" required>
                    Kategoria: <select name="category_id">
                        <?php while ($cat = $categories->fetch_assoc()) { ?>
                            <option value="<?php echo $cat['id']; ?>"><?php echo $cat['name']; ?></option>
                        <?php } ?>
                    </select>
                    Cena: <input type="number" name="price" step="0.01" required>
                    Stan: <input type="number" name="stock" value="0">
                    Zdjęcie: <input type="file" name="image" accept="image/*">
                    <button type="submit">Dodaj</button>
                </form>

                <hr>

                <h3>Lista produktów</h3>
                Sortuj:
                <a href="?section=produkty&sort=name">Nazwa</a> |
                <a href="?section=produkty&sort=price">Cena</a> |
                <a href="?section=produkty&sort=id">Domyślnie</a>

                <table>
                    <tr>
                        <th>ID</th>
                        <th>Zdjęcie</th>
                        <th>Nazwa</th>
                        <th>Kategoria</th>
                        <th>Cena</th>
                        <th>Stan</th>
                        <th>Akcja</th>
                    </tr>
                    <?php while ($p = $products->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $p['id']; ?></td>
                            <td>
                                <?php if ($p['image'] !== '') { ?>
                                    <img src="../uploads/<?php echo $p['image']; ?>" width="50">
                                <?php } else { ?>
                                    brak
                                <?php } ?>
                            </td>
                            <td><?php echo $p['name']; ?></td>
                            <td><?php echo $p['category_name']; ?></td>
                            <td><?php echo $p['price']; ?> zł</td>
                            <td><?php echo $p['stock']; ?></td>
                            <td>
                                <form method="POST">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo $p['id']; ?>">
                                    <button type="submit" onclick="return confirm('Usunąć?')">Usuń</button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </table>

            <?php } elseif ($section === 'uzytkownicy') { ?>

                <h3>Użytkownicy</h3>

                <?php if ($_SESSION['user_role'] !== 'admin') { ?>
                    <p>Tylko admin może zarządzać użytkownikami.</p>
                <?php } else { ?>

                    <table>
                        <tr>
                            <th>ID</th>
                            <th>Nazwa</th>
                            <th>Email</th>
                            <th>Rola</th>
                            <th>Data rejestracji</th>
                            <th>Zmień rolę</th>
                        </tr>
                        <?php while ($u = $users->fetch_assoc()) { ?>
                            <tr>
                                <td><?php echo $u['id']; ?></td>
                                <td><?php echo $u['name']; ?></td>
                                <td><?php echo $u['email']; ?></td>
                                <td><?php echo $u['role_name']; ?></td>
                                <td><?php echo $u['created_at']; ?></td>
                                <td>
                                    <form method="POST">
                                        <input type="hidden" name="action" value="change_role">
                                        <input type="hidden" name="user_id" value="<?php echo $u['id']; ?>">
                                        <select name="role_id">
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
                                        <button type="submit">Zapisz</button>
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                    </table>

                <?php } ?>

            <?php } elseif ($section === 'klienci') { ?>
                <h3>Klienci</h3>


            <?php } elseif ($section === 'platnosci') { ?>
                <h3>Płatności</h3>


            <?php } ?>

        </div>
    </div>

</body>

</html>