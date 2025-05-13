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

$profile = $result->fetch_assoc(); // ข้อมูลพนักงาน
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>จัดการสต็อกวัตถุดิบ</title>
    <link rel="stylesheet" href="css/supimport.css">
</head>
<body>
      <div class="top-bar">
    <div class="home-button" onclick="location.href='staff_dashboard.php'">
      <img src="pics/Home_icon.png">
      <p>หน้าหลัก</p>
    </div>
    <div class="profile-box">
      <img src="img/picture/Profile_guy.png" alt="Profile Picture">
      <div class="profile-label">
        <p class="profile-name"><?php echo htmlspecialchars($profile['FName']); ?></p>
        <p class="profile-id">ID: <?php echo htmlspecialchars($profile['EmployeeID']); ?></p>
      </div>
    </div>
  </div>
    <div class="container"> 
        <h2 class="import-header">📥 นำเข้าวัตถุดิบจาก <strong>Supplier</strong></h2>
            <div class="minicontainer">
            <form action="backend/addIngredient.php" method="POST" class="import-form">
            <div class="form-row">
                <input type="text" name="ingredientName" placeholder="ชื่อวัตถุดิบ" required>
                <input type="text" name="quantity" placeholder="จำนวน (เช่น 10 กก.)" required>
                <input type="date" name="expiryDate" placeholder="วันหมดอายุ" required>
            </div>
            <div class="form-row">
                <input type="text" name="supplierName" placeholder="ชื่อ Supplier" required>
                <input type="text" name="phone" placeholder="เบอร์" required>
                <input type="text" name="email" placeholder="อีเมล" required>
            </div>
            <div class="form-action">
                <button type="submit" class="btn-confirm">ยืนยันการนำเข้า</button>
            </div>
            </form>
            </div>
    </div>
</body>
</html>
