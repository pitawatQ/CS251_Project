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
    <title>Admin Dashboard</title>
    <link rel="stylesheet" type="text/css" href="css/admin_dashboard.css">
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
        <div class="menu-item" onclick="location.href='daily_report.php'">
            <img src="img/picture/Pie_chart.png" alt="ภาพรวมร้านวันนี้">
            <p>ภาพรวมร้านวันนี้</p>
        </div>
        <div class="menu-item" onclick="location.href='weekly_report.php'">
            <img src="img/picture/Report_clipboard.png" alt="ยอดขาย & รายงาน">
            <p>ยอดขาย & รายงาน</p>
        </div>
        <div class="menu-item" onclick="location.href='statistics.php'">
            <img src="img/picture/Magnifying_glass_with_report.png" alt="สถิติ">
            <p>สถิติ</p>
        </div>
        <div class="menu-item" onclick="location.href='menu_list.php'">
            <img src="img/picture/Tomato_Stew.png" alt="จัดการเมนูอาหาร">
            <p>จัดการเมนูอาหาร</p>
        </div>
        <div class="menu-item" onclick="location.href='employees.php'">
            <img src="img/picture/3D_guy.png" alt="จัดการพนักงาน">
            <p>จัดการพนักงาน</p>
        </div>
        <div class="menu-item" onclick="location.href='worktime_loga.php'">
            <img src="img/picture/Time_log.png" alt="บันทึกเวลาเข้าออกงาน">
            <p>บันทึกเวลาเข้าออกงาน</p>
        </div>

    </div>
</div>
<div class="exit-button" onclick="location.href='login.php'">
    <img src="img/picture/Exit_door.png" alt="Exit">
</div>
</body>
</html>