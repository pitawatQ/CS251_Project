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
// ดึงบันทึกของวันนี้
$todayStmt = $conn->prepare("SELECT ClockInTime, ClockOutTime FROM Attendance WHERE EmployeeID = ? AND WorkDate = CURDATE()");
$todayStmt->bind_param("i", $employeeID);
$todayStmt->execute();
$todayResult = $todayStmt->get_result();
$todayLog = $todayResult->fetch_assoc();

// ดึงประวัติล่าสุด 7 วัน (ยกเว้นวันนี้)
$historyStmt = $conn->prepare("SELECT WorkDate, ClockInTime, ClockOutTime FROM Attendance WHERE EmployeeID = ? AND WorkDate != CURDATE() ORDER BY WorkDate DESC LIMIT 7");
$historyStmt->bind_param("i", $employeeID);
$historyStmt->execute();
$historyResult = $historyStmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Worktime Log</title>
    <link rel="stylesheet" type="text/css" href="css/worktime_log.css">
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
    <h1 class="title">บันทึกเวลาเข้า-ออกงาน</h1>
    <div class="status-box">
        <?php
        $isCheckedIn = $todayLog && $todayLog['ClockInTime'] && !$todayLog['ClockOutTime'];
        ?>

        <p>สถานะปัจจุบัน: 
        <span class="status-text" style="color:<?= $isCheckedIn ? 'green' : 'red' ?>">
            <?= $isCheckedIn ? 'กำลังทำงาน' : 'ออกงาน' ?>
        </span>
        </p>

        <?php
        $isCheckedOut = $todayLog && $todayLog['ClockOutTime'];
        ?>

        <?php if ($isCheckedOut): ?>
            <button class="check-in-button disabled" type="button" disabled style="background-color: gray; cursor: not-allowed;">
                ออกงานแล้ว
            </button>
        <?php else: ?>
            <form method="POST" action="backend/<?= $isCheckedIn ? 'checkout.php' : 'checkin.php' ?>">
                <button class="check-in-button" type="submit" style="background-color: <?= $isCheckedIn ? '#e67e22' : '#28c95b' ?>;">
                    <?= $isCheckedIn ? 'ออกงาน' : 'เข้างาน' ?>
                </button>
            </form>
        <?php endif; ?>


        <h2 class="history-title">ประวัติเวลาเข้าออก</h2>
        <div class="history-log">
            <?php
            if ($todayLog && $todayLog['ClockInTime']) {
                echo "<div class='history-item'><p>📅 วันนี้ ⏰ {$todayLog['ClockInTime']} - เข้างาน</p></div>";
                if ($todayLog['ClockOutTime']) {
                    echo "<div class='history-item'><p>📅 วันนี้ ⏰ {$todayLog['ClockOutTime']} - ออกงาน</p></div>";
                }
            }
            while ($row = $historyResult->fetch_assoc()) {
                echo "<div class='history-item'><p>📅 {$row['WorkDate']} ⏰ {$row['ClockInTime']} - เข้างาน</p></div>";
                if ($row['ClockOutTime']) {
                    echo "<div class='history-item'><p>📅 {$row['WorkDate']} ⏰ {$row['ClockOutTime']} - ออกงาน</p></div>";
                }
            }
            ?>
        </div>
    </div>
    </div>
    
</div>

<div class="exit-button" onclick="location.href='login.php'">
    <img src="img/picture/Exit_door.png" alt="Exit">
</div>
</body>
</html>