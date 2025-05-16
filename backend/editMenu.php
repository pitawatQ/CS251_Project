<?php
session_start();
include 'db_connect.php';
include 'auth.php';

if (!isset($_SESSION['EmployeeID'])) {
    header("Location: login.php");
    exit();
}

$menuID = $_GET['id'] ?? null;
if (!$menuID) {
    header("Location: menu_list.php");
    exit();
}

// ดึงข้อมูลเมนูจากฐานข้อมูล
$stmt = $conn->prepare("SELECT * FROM Menu WHERE MenuID = ?");
$stmt->bind_param("i", $menuID);
$stmt->execute();
$result = $stmt->get_result();
$menu = $result->fetch_assoc();

if (!$menu) {
    header("Location: menu_list.php");
    exit();
}

// ดึงหมวดหมู่เมนูจากฐานข้อมูล
$categoryResult = $conn->query("SELECT CategoryID, CName FROM Category");
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>แก้ไขเมนูอาหาร</title>
    <link rel="stylesheet" href="../css/menu_list.css">
</head>
<body>
<div class="container">
    <h1>แก้ไขเมนูอาหาร</h1>
    <form action="backend/updateMenu.php" method="post">
        <input type="hidden" name="menuID" value="<?= $menu['MenuID'] ?>">

        <label for="name">ชื่อเมนู:</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($menu['Name']) ?>" required>

        <label for="category">หมวดหมู่:</label>
        <select id="category" name="category" required>
            <?php
            while ($cat = $categoryResult->fetch_assoc()) {
                $selected = $cat['CategoryID'] == $menu['CategoryID'] ? 'selected' : '';
                echo "<option value=\"{$cat['CategoryID']}\" $selected>{$cat['CName']}</option>";
            }
            ?>
        </select>

        <label for="price">ราคา:</label>
        <input type="number" id="price" name="price" step="0.01" value="<?= $menu['Price'] ?>" required>

        <label for="status">สถานะ:</label>
        <select id="status" name="status">
            <option value="1" <?= $menu['Status'] ? 'selected' : '' ?>>พร้อมขาย</option>
            <option value="0" <?= !$menu['Status'] ? 'selected' : '' ?>>ไม่พร้อมขาย</option>
        </select>

        <button type="submit">บันทึกการเปลี่ยนแปลง</button>
    </form>
</div>
</body>
</html>
