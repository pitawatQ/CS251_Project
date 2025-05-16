<?php
session_start();
include 'backend/db_connect.php';
include 'backend/auth.php';

// ตรวจสอบว่าเข้าสู่ระบบหรือยัง
if (!isset($_SESSION['EmployeeID'])) {
    header("Location: login.php");
    exit();
}

$employeeID = $_SESSION['EmployeeID'];
$stmt = $conn->prepare("SELECT FName, EmployeeID FROM Employee WHERE EmployeeID = ?");
$stmt->bind_param("i", $employeeID);
$stmt->execute();
$result = $stmt->get_result();
$profile = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>จัดการเมนูอาหาร/โปรโมชั่น</title>
    <link rel="stylesheet" href="css/menu_list.css">
</head>
<body>
<div class="top-bar">
    <div class="home-button" onclick="location.href='admin_dashboard.php'">
        <img src="pics/Home_icon.png">
        <p>หน้าหลัก</p>
    </div>
    <div class="profile-box">
        <img src="img/picture/Profile_guy.png" alt="Profile Picture">
        <div class="profile-label">
            <p class="profile-name"><?= htmlspecialchars($profile['FName']); ?></p>
            <p class="profile-id">ID: <?= htmlspecialchars($profile['EmployeeID']); ?></p>
        </div>
    </div>
</div>

<div class="container">

    <div class="header-bar">
        <h1>จัดการเมนูอาหาร/โปรโมชั่น</h1>
    </div>

    <!-- TAB Bar -->
    <div class="tabs">
        <button class="tab-btn active" onclick="showTab('menu')">เมนูอาหาร</button>
        <button class="tab-btn" onclick="showTab('promotion')">โปรโมชั่น</button>
    </div>

    <!-- ========== เมนูอาหาร TAB ========== -->
    <div id="menu-tab" class="tab-content active">
        <button class="btn-add" onclick="location.href='createMenu.php'">➕ เพิ่มเมนูอาหาร</button>
        <div class="toolbar">
            <input type="text" class="search-input" placeholder="ค้นหาชื่อเมนู..." />
            <select class="category-filter">
                <option value="">ทั้งหมด</option>
                <?php
                $categoryResult = $conn->query("SELECT CategoryID, CName FROM Category");
                while ($cat = $categoryResult->fetch_assoc()) {
                    echo "<option value=\"{$cat['CategoryID']}\">{$cat['CName']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="menu-list">
        <?php
        $sql = "
            SELECT m.MenuID, m.Name AS MenuName, m.Price, m.Status, c.CName AS CategoryName
            FROM Menu m
            LEFT JOIN Category c ON m.CategoryID = c.CategoryID
            ORDER BY m.MenuID ASC";
        $result = $conn->query($sql);

        while ($row = $result->fetch_assoc()):
            $menuName = htmlspecialchars($row['MenuName']);
            $menuID = htmlspecialchars($row['MenuID']);
            $price = number_format($row['Price'], 2);
            $categoryName = htmlspecialchars($row['CategoryName']);
            $statusText = $row['Status'] ? 'พร้อมขาย' : 'ไม่พร้อมขาย';
            $statusClass = $row['Status'] ? 'status-available' : 'status-unavailable';
        ?>
            <div class="menu-boxs">
                <div class="menu-info">
                    <div class="menu-name"><?= $menuName ?></div>
                    <div class="menu-details">ID: <?= $menuID ?> • หมวดหมู่: <?= $categoryName ?> • ราคา: ฿<?= $price ?></div>
                </div>
                <div class="menu-meta">
                    <a href="menuDetail.php?id=<?= $menuID ?>" class="btn-small">ดูรายละเอียด</a>
                    <span class="status <?= $statusClass ?>"><?= $statusText ?></span>
                    <a href="backend/delMenu.php?id=<?= $menuID ?>" class="btn-small btn-delete" onclick="return confirm('ลบเมนูนี้หรือไม่?')">ลบ</a>
                </div>
            </div>
        <?php endwhile; ?>
        </div>
    </div>

    <!-- ========== โปรโมชั่น TAB ========== -->
<div id="promotion-tab" class="tab-content">
    <button class="btn-add" onclick="location.href='createPromotion.php'">➕ เพิ่มโปรโมชั่น</button>
    <div class="menu-list"> <!-- เปลี่ยนจาก promotion-list เป็น menu-list เพื่อใช้ style เดียวกัน -->
    <?php
    // ดึงข้อมูลโปรโมชั่นจากฐานข้อมูล
    $promoSQL = "SELECT PromotionID, PromotionName, PromotionPrice, PromotionDes, Picture FROM Promotion";
    $promoResult = $conn->query($promoSQL);
    while ($promo = $promoResult->fetch_assoc()):
        $promoName = htmlspecialchars($promo['PromotionName']);
        $promoID = htmlspecialchars($promo['PromotionID']);
        $promoPrice = number_format($promo['PromotionPrice'], 2);
        $promoDes = htmlspecialchars($promo['PromotionDes']);
        // ถ้าไม่มีคอลัมน์ Picture ไม่ต้องสนใจ ถ้ามีสามารถใส่ได้แบบเมนู
    ?>
        <div class="menu-boxs">
            <div class="menu-info">
                <div class="menu-name"><?= $promoName ?></div>
                <div class="menu-details">ID: <?= $promoID ?> • ราคาโปร: ฿<?= $promoPrice ?></div>
                <div class="menu-details"><?= $promoDes ?></div>
            </div>
            <div class="menu-meta">
                <a href="promotionDetail.php?id=<?= $promoID ?>" class="btn-small">ดูรายละเอียด</a>
                <a href="backend/delPromotion.php?id=<?= $promoID ?>" class="btn-small btn-delete" onclick="return confirm('ลบโปรนี้หรือไม่?')">ลบ</a>
            </div>
        </div>
    <?php endwhile; ?>
    </div>
</div>


</div>

<script>
// TAB switch
function showTab(tab) {
    document.getElementById('menu-tab').style.display = (tab === 'menu') ? 'block' : 'none';
    document.getElementById('promotion-tab').style.display = (tab === 'promotion') ? 'block' : 'none';
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    if(tab === 'menu'){
      document.querySelectorAll('.tab-btn')[0].classList.add('active');
    }else{
      document.querySelectorAll('.tab-btn')[1].classList.add('active');
    }
}
// default: show menu
showTab('menu');
</script>
<script src="backend/searchMenu.js"></script>
</body>
</html>
