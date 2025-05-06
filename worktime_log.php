<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Worktime Log</title>
    <link rel="stylesheet" type="text/css" href="css/worktime_log.css">
</head>
<body>

<div class="container">
    <h1 class="title">บันทึกเวลาเข้า-ออกงาน</h1>
    <div class="status-box">
        <p>สถานะปัจจุบัน: <span class="status-text">นอกเวลา</span></p>
        <button class="check-in-button">เข้างาน</button>
        <h2 class="history-title">ประวัติเวลาเข้าออก</h2>
        <div class="history-log">
            <div class="history-item">
                <p>📅 10/4/2568 ⏰ 08:00 - เข้างาน</p>
            </div>
            <div class="history-item">
                <p>📅 10/4/2568 ⏰ 17:34 - ออกงาน</p>
            </div>
            <div class="history-item">
                <p>📅 9/4/2568 ⏰ 08:02 - เข้างาน</p>
            </div>
            <div class="history-item">
                <p>📅 9/4/2568 ⏰ 17:05 - ออกงาน</p>
            </div>
    </div>
    </div>
    
</div>

<div class="profile-box">
    <img src="img/picture/Profile_girl.png" alt="Profile Picture">
    <div class="profile-info">
        <p class="profile-name">ปภาวดี</p>
        <p class="profile-id">ID: EC-074</p>
    </div>
</div>
<div class="home-button" onclick="location.href='index.php'">
    <img src="img/picture/Home_icon.png" alt="Home Icon">
    <p>หน้าหลัก</p>
</div>
<div class="exit-button" onclick="location.href='login.php'">
    <img src="img/picture/Exit_door.png" alt="Exit">
</div>
</body>
</html>