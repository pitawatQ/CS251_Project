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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Staff Dashboard</title>
    <link rel="stylesheet" type="text/css" href="css/staff_dashboard.css">
</head>
<body>
  <div class="top-bar">
    <div class="profile-box">
      <img src="img/picture/Profile_guy.png" alt="Profile Picture">
      <div class="profile-label">
        <p class="profile-name"><?php echo htmlspecialchars($profile['FName']); ?></p>
        <p class="profile-id">ID: <?php echo htmlspecialchars($profile['EmployeeID']); ?></p>
      </div>
    </div>
  </div>
<div class="container">
    <div class="menu">
    <!-- แถวบน 5 ปุ่ม -->
    <div class="menu-row">
        <div class="menu-item" onclick="location.href='readytoserve.php'">
            <img src="img/picture/clipboard_with_pen.png" alt="คำสั่งซื้อที่ต้องเสิร์ฟ">
            <p>คำสั่งซื้อที่ต้องเสิร์ฟ</p>
        </div>
        <div class="menu-item" onclick="location.href='status_order.php'">
            <img src="img/picture/Blue_correct_mark.png" alt="สถานะอาหาร">
            <p>สถานะอาหาร</p>
        </div>
        <div class="menu-item" onclick="location.href='chef_order.php'">
            <img src="img/picture/3D_chief.png" alt="พื้นที่ครัว">
            <p>พื้นที่ครัว</p>
        </div>
        <div class="menu-item" onclick="location.href='table_status.php'">
            <img src="img/picture/Table_with_Chair.png" alt="จัดการโต๊ะ">
            <p>จัดการโต๊ะ</p>
        </div>
        <div class="menu-item" onclick="location.href='stock.php'">
            <img src="img/picture/OutOfStock_sign.png" alt="สต็อกวัตถุดิบ">
            <p>สต็อกวัตถุดิบ</p>
        </div>
    </div>

    <!-- แถวล่าง 3 ปุ่ม -->
    <div class="menu-row">
        <div class="menu-item" onclick="location.href='importHistory.php'">
            <img src="img/picture/Box_on_trolley.png" alt="เช็คสินค้าเข้า">
            <p>เช็คสินค้าเข้า</p>
        </div>
        <div class="menu-item" onclick="location.href='worktime_log.php'">
            <img src="img/picture/Time_log.png" alt="บันทึกเวลาเข้าออกงาน">
            <p>บันทึกเวลาเข้าออกงาน</p>
        </div>
        <div class="menu-item" onclick="location.href='historyOrder.php'">
            <img src="img/picture/Paper_with_pen.png" alt="ประวัติคำสั่งซื้อ">
            <p>ประวัติคำสั่งซื้อ</p>
        </div>
    </div>
</div>

</div>
<div class="exit-button" onclick="confirmLogout()">
  <img src="img/picture/Exit_door.png" alt="Logout">
</div>


<script src="backend/logout.js"></script>


</body>
</html>