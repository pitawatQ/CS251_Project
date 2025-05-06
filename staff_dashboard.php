<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Staff Dashboard</title>
    <link rel="stylesheet" type="text/css" href="css/staff_dashboard.css">
</head>
<body>
<div class="profile-box">
    <img src="img/picture/Profile_guy.png" alt="Profile Picture">
    <div class="profile-info">
        <p class="profile-name">พีรพล</p>
        <p class="profile-id">ID: EC-300</p>
    </div>
</div>
<div class="container">
    <div class="menu">
        <div class="menu-item" onclick="location.href='overview.php'">
            <img src="img/picture/clipboard_with_pen.png" alt="คำสั่งซื้อที่ต้องเสิร์ฟ">
            <p>คำสั่งซื้อที่ต้องเสิร์ฟ</p>
        </div>
        <div class="menu-item" onclick="location.href='sales_report.php'">
            <img src="img/picture/Blue_correct_mark.png" alt="สถานะอาหาร">
            <p>สถานะอาหาร</p>
        </div>
        <div class="menu-item" onclick="location.href='statistics.php'">
            <img src="img/picture/3D_chief.png" alt="พื้นที่ครัว">
            <p>พื้นที่ครัว</p>
        </div>
        <div class="menu-item" onclick="location.href='menu_management.php'">
            <img src="img/picture/Table_with_Chair.png" alt="จัดการโต๊ะ">
            <p>จัดการโต๊ะ</p>
        </div>
        <div class="menu-item" onclick="location.href='staff_management.php'">
            <img src="img/picture/OutOfStock_sign.png" alt="สต็อกวัตถุดิบ">
            <p>สต็อกวัตถุดิบ</p>
        </div>
        <div class="menu-item" onclick="location.href='overview.php'">
            <img src="img/picture/Box_on_trolley.png" alt="รับของ/เช็คสินค้าเข้า">
            <p>รับของ/เช็คสินค้าเข้า</p>
        </div>
        <div class="menu-item" onclick="location.href='overview.php'">
            <img src="img/picture/Time_log.png" alt="บันทึกเวลาเข้าออกงาน">
            <p>บันทึกเวลาเข้าออกงาน</p>
        </div>
        <div class="menu-item" onclick="location.href='overview.php'">
            <img src="img/picture/Notifying_bell.png" alt="แจ้งเตือน & เหตุการณ์">
            <p>แจ้งเตือน & เหตุการณ์</p>
        </div>
        <div class="menu-item" onclick="location.href='overview.php'">
            <img src="img/picture/Paper_with_pen.png" alt="ประวัติคำสั่งซื้อ">
            <p>ประวัติคำสั่งซื้อ</p>
        </div>
        <div class="menu-item" onclick="location.href='overview.php'">
            <img src="img/picture/3D_Assistant.png" alt="คู่มือ & ความช่วยเหลือ">
            <p>คู่มือ & ความช่วยเหลือ</p>
        </div>
    </div>
</div>
<div class="exit-button" onclick="location.href='login.php'">
    <img src="img/picture/Exit_door.png" alt="Exit">
</div>
</body>
</html>