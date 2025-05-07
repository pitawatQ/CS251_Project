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
    <title>จัดการพนักงาน</title>
    <link rel="stylesheet" href="css/employees.css">
</head>
<body>
<div class="profile-box">
    <img src="img/picture/Profile_guy.png" alt="Profile Picture">
    <div class="profile-info">
    <p class="profile-name"><?php echo htmlspecialchars($profile['FName']); ?></p>
    <p class="profile-id">ID: <?php echo htmlspecialchars($profile['EmployeeID']); ?></p>
    </div>
</div>
<div class="container">
    <div class="header-bar">
        <h1>จัดการพนักงาน</h1>
        <button class="btn-add">+ เพิ่มพนักงาน</button>
    </div>
    <div class="toolbar">
        <input type="text" placeholder="ค้นหาชื่อ/รหัสพนักงาน..." />
        <select>
            <option>ทั้งหมด</option>
            <option>ผู้จัดการร้าน</option>
            <option>เชฟ</option>
            <option>แคชเชียร์</option>
            <option>เสิร์ฟ</option>
            <option>แอดมินระบบ</option>
            <option>ดูแลโต๊ะ</option>
        </select>
    </div>
    <div class="employee-list">
        <div class="employee-box">
            <div class="employee-info">
                <div class="employee-name">รวิศ เศวตปาล</div>
                <div class="employee-details">AD-213 • เริ่มงาน: 2020-01-10 • อายุงาน: 62 เดือน</div>
            </div>
            <div class="employee-meta">
                <span class="badge role">แอดมินระบบ</span>
                <span class="status status-working">ทำงานอยู่</span>
                <a href="#" class="btn-small">ดูโปรไฟล์</a>
                <a href="#" class="btn-small">แก้ไข</a>
                <a href="#" class="btn-small btn-delete">ลบ</a>
            </div>
        </div>

        <div class="employee-box">
            <div class="employee-info">
                <div class="employee-name">ไตรภพ ศิระเมฆา</div>
                <div class="employee-details">MG-127 • เริ่มงาน: 2020-05-18 • อายุงาน: 58 เดือน</div>
            </div>
            <div class="employee-meta">
                <span class="badge role">ผู้จัดการร้าน</span>
                <span class="status status-inactive">ไม่ได้ทำขณะนี้</span>
                <a href="#" class="btn-small">ดูโปรไฟล์</a>
                <a href="#" class="btn-small">แก้ไข</a>
                <a href="#" class="btn-small btn-delete">ลบ</a>
            </div>
        </div>
    </div>
</div>
<div class="exit-button" onclick="location.href='login.php'">
    <img src="img/picture/Exit_door.png" alt="Exit">
</div>
</body>
</html>
