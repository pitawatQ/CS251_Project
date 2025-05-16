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

// เช็ค id เมนู
if (!isset($_GET['id'])) {
    header("Location: menu_list.php");
    exit();
}
$menuID = intval($_GET['id']);

// ดึงข้อมูลเมนู
$stmt = $conn->prepare("
  SELECT m.MenuID, m.Name, m.Price, m.Status, m.MenuDes, m.CategoryID, m.Picture, c.CName
  FROM Menu m
  LEFT JOIN Category c ON m.CategoryID=c.CategoryID
  WHERE m.MenuID=?
");
$stmt->bind_param("i", $menuID);
$stmt->execute();
$menu = $stmt->get_result()->fetch_assoc();
if (!$menu) die("ไม่พบเมนู");

// ดึงวัตถุดิบที่ใช้
$stmt = $conn->prepare("
  SELECT i.IngredientID, s.IngredientName, i.QuantityUsed
  FROM IngredientUsage i
  JOIN Stock s ON i.IngredientID=s.IngredientID
  WHERE i.MenuID=?
");
$stmt->bind_param("i", $menuID);
$stmt->execute();
$ingredients = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// ดึงหมวดหมู่
$categoryList = $conn->query("SELECT CategoryID,CName FROM Category ORDER BY CName");

// ดึงวัตถุดิบแบบไม่ซ้ำชื่อ (แสดงเฉพาะวัตถุดิบที่เหลือ และวันหมดอายุน้อยสุด)
$stockList = $conn->query("
  SELECT s1.IngredientID, s1.IngredientName
  FROM Stock s1
  JOIN (
    SELECT IngredientName, MIN(ExpirationDate) AS EarliestDate
    FROM Stock
    WHERE Quantity > 0
    GROUP BY IngredientName
  ) s2 ON s1.IngredientName = s2.IngredientName AND s1.ExpirationDate = s2.EarliestDate
  GROUP BY s1.IngredientName
  ORDER BY s1.IngredientName
");

$statusText  = $menu['Status'] ? 'พร้อมขาย' : 'ไม่พร้อมขาย';
$statusClass = $menu['Status'] ? 'status-available' : 'status-unavailable';
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>รายละเอียดเมนู</title>
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
      <h1>🍽 รายละเอียดเมนู</h1>
    </div>

    <!-- VIEW MODE -->
    <div class="menu-box" id="viewMode">
      <div class="menu-info-row">
        <div class="menu-text">
          <div class="menu-name"><?=htmlspecialchars($menu['Name'])?></div>
          <p>รหัส: <?=$menu['MenuID']?></p>
          <p>ราคา: ฿<?=number_format($menu['Price'],2)?></p>
          <p>หมวดหมู่: <?=htmlspecialchars($menu['CName'] ?: '-')?></p>
          <p>รายละเอียด: <?=htmlspecialchars($menu['MenuDes'] ?: '-')?></p>
          <p>สถานะ: <span class="<?=$statusClass?>"><?=$statusText?></span></p>
        </div>
        <div class="menu-image-wrapper">
          <?php if (!empty($menu['Picture'])): ?>
            <img src="<?=htmlspecialchars($menu['Picture'])?>" class="menu-image" alt="Menu Image">
          <?php endif; ?>
        </div>
      </div>
      <div class="ingredient-section">
        <h3>🧾 วัตถุดิบที่ใช้</h3>
        <div class="ingredient-scroll">
          <table>
            <thead><tr><th>วัตถุดิบ</th><th>จำนวน (กก.)</th></tr></thead>
            <tbody>
              <?php if (empty($ingredients)): ?>
                <tr><td colspan="2" style="text-align:center">ไม่มี</td></tr>
              <?php else: foreach ($ingredients as $it): ?>
                <tr>
                  <td><?=htmlspecialchars($it['IngredientName'])?></td>
                  <td><?=number_format($it['QuantityUsed'],2)?> กก.</td>
                </tr>
              <?php endforeach; endif; ?>
            </tbody>
          </table>
        </div>
      </div>
      <div class="actions" id="viewButtons" style="justify-content:flex-end">
        <button class="btn-small" onclick="enableEdit()">✏️ แก้ไข</button>
        <a href="backend/delMenu.php?id=<?= $menuID ?>" class="btn-small btn-delete" onclick="return confirm('ลบเมนูนี้หรือไม่?')">ลบ</a>
        <a href="menu_list.php" class="btn-small btn-back">← กลับ</a>
      </div>
    </div>

    <!-- EDIT MODE -->
    <form id="editForm" class="menu-box" action="backend/update_menu.php" method="POST" enctype="multipart/form-data" style="display:none;flex-direction:column">
      <input type="hidden" name="MenuID" value="<?=$menu['MenuID']?>">
      <input type="hidden" name="OldPicture" value="<?=htmlspecialchars($menu['Picture'])?>">
      <div class="menu-info-row">
        <div class="menu-text">
          <p>เปลี่ยนรูปภาพ: <input type="file" name="Picture" accept="image/*"></p>
          <div class="menu-name">
            <input type="text" name="Name" value="<?=htmlspecialchars($menu['Name'])?>" required>
          </div>
          <div class="menu-details">
            <p>ราคา: <input type="number" name="Price" value="<?=$menu['Price']?>" step="0.01" required></p>
            <p>
              หมวดหมู่:
              <select name="CategoryID" id="catSelect" required>
                <option value="">-- เลือก/เพิ่มหมวดหมู่ --</option>
                <?php foreach ($categoryList as $c): ?>
                  <option value="<?=$c['CategoryID']?>" <?= $c['CategoryID'] == $menu['CategoryID'] ? 'selected' : '' ?>>
                    <?=htmlspecialchars($c['CName'])?>
                  </option>
                <?php endforeach; ?>
                <option value="__new">+ สร้างใหม่...</option>
              </select>
              <input type="text" id="newCat" name="NewCategory" placeholder="พิมพ์ชื่อหมวดใหม่" style="display:none">
            </p>
            <p>รายละเอียด: <input type="text" name="MenuDes" value="<?=htmlspecialchars($menu['MenuDes'])?>"></p>
            <p>
              สถานะ:
              <select name="Status">
                <option value="1" <?=$menu['Status'] ? 'selected' : ''?>>พร้อมขาย</option>
                <option value="0" <?=$menu['Status'] ? '' : 'selected'?>>ไม่พร้อมขาย</option>
              </select>
            </p>
          </div>
        </div>
        <div class="menu-image-wrapper">
          <?php if (!empty($menu['Picture'])): ?>
            <img src="<?=htmlspecialchars($menu['Picture'])?>" class="menu-image" alt="Menu Image">
          <?php endif; ?>
        </div>
      </div>

      <div class="ingredient-section">
        <h3 style="margin-top:1em">🧾 แก้ไขวัตถุดิบที่ใช้</h3>
        <div class="ingredient-scroll">
          <table>
            <thead>
            <tr>
                <th>วัตถุดิบ</th>
                <th>จำนวน (กก.)</th>
                <th>อัตราสูญเสีย (%)</th>
                <th>เพิ่ม/ลบ</th>
            </tr>
            </thead>
            <tbody id="ingBody">
              <?php foreach ($ingredients as $it): ?>
              <tr>
                <td>
                  <select name="IngredientIDs[]" required>
                    <?php foreach ($stockList as $st): ?>
                      <option value="<?=$st['IngredientID']?>" <?= $st['IngredientID'] == $it['IngredientID'] ? 'selected' : '' ?>>
                        <?=htmlspecialchars($st['IngredientName'])?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </td>
                <td><input type="number" name="QuantityUsed[]" value="<?=$it['QuantityUsed']?>" step="0.01" required></td>
                <td><input type="number" name="ErrorRateUsed[]" value="<?= isset($it['ErrorRateUsed']) ? $it['ErrorRateUsed'] * 100 : 0 ?>" step="0.01" min="0" max="100" required></td>
                <td><button type="button" onclick="removeRow(this)">–</button></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
          <button type="button" onclick="addRow()" style="margin-top:8px">+ เพิ่มวัตถุดิบ</button>
        </div>
      </div>

      <div class="actions" style="justify-content:flex-end;margin-top:1em">
        <button type="submit" class="btn-small">✅ บันทึก</button>
        <button type="button" class="btn-small btn-delete" onclick="cancelEdit()">❌ ยกเลิก</button>
      </div>
    </form>
  </div>


<script>
function enableEdit(){
  document.getElementById('viewMode').style.display='none';
  document.getElementById('editForm').style.display='flex';
}
function cancelEdit(){
  document.getElementById('editForm').style.display='none';
  document.getElementById('viewMode').style.display='flex';
}
function addRow(){
  const tbody = document.getElementById('ingBody'),
        selectHTML = tbody.querySelector('select').outerHTML;
  const tr = document.createElement('tr');
  tr.innerHTML = `
    <td>${selectHTML}</td>
    <td><input type="number" name="QuantityUsed[]" step="0.01" required></td>
    <td><button type="button" onclick="removeRow(this)">–</button></td>`;
  tbody.appendChild(tr);
}
function removeRow(btn){
  btn.closest('tr').remove();
}
document.getElementById('catSelect').onchange = e => {
  document.getElementById('newCat').style.display =
    e.target.value === '__new' ? 'inline-block' : 'none';
};
</script>
</body>
</html>
