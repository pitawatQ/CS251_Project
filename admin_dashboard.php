<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" type="text/css" href="css/admin_dashboard.css">
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
            <img src="img/picture/Pie_chart.png" alt="ภาพรวมร้านวันนี้">
            <p>ภาพรวมร้านวันนี้</p>
        </div>
        <div class="menu-item" onclick="location.href='sales_report.php'">
            <img src="img/picture/Report_clipboard.png" alt="ยอดขาย & รายงาน">
            <p>ยอดขาย & รายงาน</p>
        </div>
        <div class="menu-item" onclick="location.href='statistics.php'">
            <img src="img/picture/Magnifying_glass_with_report.png" alt="สถิติ">
            <p>สถิติ</p>
        </div>
        <div class="menu-item" onclick="location.href='menu_management.php'">
            <img src="img/picture/Tomato_Stew.png" alt="จัดการเมนูอาหาร">
            <p>จัดการเมนูอาหาร</p>
        </div>
        <div class="menu-item" onclick="location.href='staff_management.php'">
            <img src="img/picture/3D_guy.png" alt="จัดการพนักงาน">
            <p>จัดการพนักงาน</p>
        </div>
    </div>
</div>
<div class="exit-button" onclick="location.href='login.php'">
    <img src="img/picture/Exit_door.png" alt="Exit">
</div>
</body>
</html>