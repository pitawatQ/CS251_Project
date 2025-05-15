<?php
session_start();
include 'backend/db_connect.php';
include 'backend/auth.php';

if (!isset($_SESSION['EmployeeID'])) {
    header("Location: login.php");
    exit();
}
$employeeID = $_SESSION['EmployeeID'];

// ดึงชื่อผู้ใช้
$stmt = $conn->prepare("SELECT FName FROM Employee WHERE EmployeeID=?");
$stmt->bind_param("i", $employeeID);
$stmt->execute();
$profile = $stmt->get_result()->fetch_assoc();

// ตรวจสอบ id โปรโมชั่น
if (!isset($_GET['id'])) {
    header("Location: menuPromotion.php");
    exit();
}
$promoID = intval($_GET['id']);

// ดึงข้อมูลโปรโมชั่น
$stmt = $conn->prepare("
  SELECT PromotionID, PromotionName, PromotionPrice, PromotionDes, Picture
  FROM Promotion
  WHERE PromotionID=?
");
$stmt->bind_param("i", $promoID);
$stmt->execute();
$promotion = $stmt->get_result()->fetch_assoc();
if (!$promotion) die("ไม่พบโปรโมชั่น");

// ดึงเมนูที่อยู่ในโปรโมชั่นนี้
$stmt = $conn->prepare("
  SELECT m.MenuID, m.Name, m.Price
  FROM PromotionMenu pm
  JOIN Menu m ON pm.MenuID = m.MenuID
  WHERE pm.PromotionID = ?
");
$stmt->bind_param("i", $promoID);
$stmt->execute();
$menus = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>รายละเอียดโปรโมชั่น</title>
  <link rel="stylesheet" href="css/menu_list.css">
</head>
<body>
  <div class="top-bar">
    <div class="home-button" onclick="location.href='admin_dashboard.php'">
      <img src="pics/Home_icon.png"><p>หน้าหลัก</p>
    </div>
    <div class="profile-box">
      <img src="img/picture/Profile_guy.png">
      <div class="profile-label">
        <p class="profile-name"><?=htmlspecialchars($profile['FName'])?></p>
      </div>
    </div>
  </div>

  <div class="container">
    <div class="header-bar">
      <h1>🔥 รายละเอียดโปรโมชั่น</h1>
    </div>
    <div class="menu-box" id="viewMode">
      <div class="menu-info-row">
        <div class="menu-text">
          <div class="menu-name"><?=htmlspecialchars($promotion['PromotionName'])?></div>
          <p>รหัสโปรโมชั่น: <?= $promotion['PromotionID'] ?></p>
          <p>ราคาโปร: ฿<?= number_format($promotion['PromotionPrice'],2) ?></p>
          <p>รายละเอียด: <?= htmlspecialchars($promotion['PromotionDes'] ?: '-') ?></p>
        </div>
        <div class="menu-image-wrapper">
          <?php if (!empty($promotion['Picture'])): ?>
            <img src="<?=htmlspecialchars($promotion['Picture'])?>" class="menu-image" alt="Promotion Image">
          <?php endif; ?>
        </div>
      </div>
      <div class="ingredient-section">
        <h3>🍱 เมนูที่อยู่ในโปรนี้</h3>
        <div class="ingredient-scroll">
          <table>
            <thead>
              <tr>
                <th>ชื่อเมนู</th>
                <th>ราคาปกติ</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($menus)): ?>
                <tr><td colspan="2" style="text-align:center">ไม่มีเมนูในโปรโมชั่นนี้</td></tr>
              <?php else: foreach ($menus as $m): ?>
                <tr>
                  <td><?=htmlspecialchars($m['Name'])?></td>
                  <td>฿<?=number_format($m['Price'],2)?></td>
                </tr>
              <?php endforeach; endif; ?>
            </tbody>
          </table>
        </div>
      </div>
      <div class="actions" style="justify-content:flex-end">
        <a href="backend/delPromotion.php?id=<?= $promoID ?>" class="btn-small btn-delete" onclick="return confirm('ลบโปรนี้หรือไม่?')">ลบ</a>
        <a href="menu_list.php" class="btn-small btn-back">← กลับ</a>
      </div>
    </div>
  </div>
</body>
</html>
