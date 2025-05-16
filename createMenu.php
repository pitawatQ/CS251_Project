<?php
session_start();
include 'backend/db_connect.php';
include 'backend/auth.php';

if (!isset($_SESSION['EmployeeID'])) {
    header("Location: login.php");
    exit();
}

$employeeID = $_SESSION['EmployeeID'];
$stmt = $conn->prepare("SELECT FName FROM Employee WHERE EmployeeID=?");
$stmt->bind_param("i", $employeeID);
$stmt->execute();
$profile = $stmt->get_result()->fetch_assoc();

// ดึงหมวดหมู่ทั้งหมด
$categoryList = $conn->query("SELECT CategoryID,CName FROM Category ORDER BY CName");

// ดึงวัตถุดิบแบบไม่ซ้ำ
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
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>เพิ่มเมนูอาหาร</title>
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
    <h1>➕ เพิ่มเมนูอาหารใหม่</h1>
  </div>

  <form class="menu-box" action="backend/save_menu.php" method="POST" enctype="multipart/form-data" style="display:flex;flex-direction:column">
    <div class="menu-info">
        <div class="menu-info-row" style="display: flex; gap: 20px;">
            <!-- ข้อมูลเมนูฝั่งซ้าย -->
            <div class="menu-text" style="flex: 1;">
            <p>รูปภาพเมนู: <input type="file" name="Picture" accept="image/*" required></p>
            <div class="menu-name">
                <input type="text" name="Name" placeholder="ชื่อเมนู" required>
            </div>
            <div class="menu-details">
                <p>ราคา: <input type="number" name="Price" step="0.01" required></p>
                <p>
                หมวดหมู่:
                <select name="CategoryID" id="catSelect" required>
                    <option value="">-- เลือก/เพิ่มหมวดหมู่ --</option>
                    <?php foreach ($categoryList as $c): ?>
                    <option value="<?=$c['CategoryID']?>"><?=htmlspecialchars($c['CName'])?></option>
                    <?php endforeach; ?>
                    <option value="__new">+ สร้างใหม่...</option>
                </select>
                <input type="text" id="newCat" name="NewCategory" placeholder="พิมพ์ชื่อหมวดใหม่" style="display:none">
                </p>
                <p>รายละเอียด: <input type="text" name="MenuDes" placeholder="คำอธิบายเพิ่มเติม"></p>
                <p>
                สถานะ:
                <select name="Status">
                    <option value="1" selected>พร้อมขาย</option>
                    <option value="0">ไม่พร้อมขาย</option>
                </select>
                </p>
            </div>
            </div>
        </div>
        </div>


    <div class="ingredient-section">
      <h3 style="margin-top:1em">🧾 วัตถุดิบที่ใช้</h3>
      <div class="ingredient-scroll">
        <table>
          <thead>
            <tr>
              <th>วัตถุดิบ</th><th>จำนวน (กก.)</th><th>อัตราสูญเสีย (%)</th><th>เพิ่ม/ลบ</th>
            </tr>
          </thead>
          <tbody id="ingBody">
            <tr>
              <td>
                <select name="IngredientIDs[]" required>
                  <?php foreach ($stockList as $st): ?>
                    <option value="<?=$st['IngredientID']?>"><?=htmlspecialchars($st['IngredientName'])?></option>
                  <?php endforeach; ?>
                </select>
              </td>
              <td><input type="number" name="QuantityUsed[]" step="0.01" required></td>
              <td><input type="number" name="ErrorRateUsed[]" step="0.01" min="0" max="100" required></td>
              <td><button type="button" onclick="removeRow(this)">–</button></td>
            </tr>
          </tbody>
        </table>
        <button type="button" onclick="addRow()" style="margin-top:8px">+ เพิ่มวัตถุดิบ</button>
      </div>
    </div>

    <div class="actions" style="justify-content:flex-end;margin-top:1em">
      <button type="submit" class="btn-small">✅ บันทึกเมนู</button>
      <a href="menu_list.php" class="btn-small btn-delete">❌ ยกเลิก</a>
    </div>
  </form>
</div>

<script>
function addRow(){
  const tbody = document.getElementById('ingBody'),
        selectHTML = tbody.querySelector('select').outerHTML;
  const tr = document.createElement('tr');
  tr.innerHTML = `
    <td>${selectHTML}</td>
    <td><input type="number" name="QuantityUsed[]" step="0.01" required></td>
    <td><input type="number" name="ErrorRateUsed[]" step="0.01" min="0" max="100" required></td>
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

</script>
</body>
</html>
