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
  <title>จัดการสถานะโต๊ะ</title>
  <link href="https://fonts.googleapis.com/css2?family=Sarabun&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/table_status.css">
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

  <div class="main-container">
    <div class="header-row">
      <h2>จัดการสถานะโต๊ะ</h2>
      <form action="backend/addTable.php" method="POST" style="margin-left: auto;">
      <button type="submit" class="btn add">➕ เพิ่มโต๊ะ</button>
      </form>
    </div>
  
    <div class="table-grid">
      <?php
        $result = $conn->query("SELECT TableNo, Status FROM tablelist ORDER BY TableNo ASC");
        while ($row = $result->fetch_assoc()) {
            $tableID = $row['TableNo'];
            $statusCode = (int)$row['Status'];

            // แปลงสถานะเป็นข้อความ
            $statusText = match ($statusCode) {
                0 => 'ว่าง',
                1 => 'กำลังใช้งาน',
                2 => 'รอเช็กบิล',
                default => 'ไม่ทราบ'
            };

            $statusClass = match ($statusCode) {
                0 => 'green',
                1 => 'red',
                2 => 'blue',
                default => 'gray'
            };

            // ตรวจสอบว่าเคยมีออเดอร์ไหม
            $check = $conn->prepare("SELECT COUNT(*) FROM Orders WHERE TableNo = ?");
            $check->bind_param("i", $tableID);
            $check->execute();
            $check->bind_result($orderCount);
            $check->fetch();
            $check->close();

            echo "<div class='table-box {$statusClass}'>";
            echo "<div class='table-title'>โต๊ะ {$tableID}</div>";
            echo "<div class='status-line'>สถานะ: {$statusText}</div>";
            echo "<div class='button-group'>";
            echo "<a href='backend/checkbill.php?id={$tableID}' class='btn check'>เช็คบิล</a>";
            
            if ($orderCount == 0) {
                echo "<form action='backend/delTable.php' method='POST' onsubmit='return confirm(\"คุณแน่ใจหรือไม่ว่าต้องการลบโต๊ะนี้?\");'>";
                echo "<input type='hidden' name='TableID' value='{$tableID}'>";
                echo "<button type='submit' class='btn delete'>ลบโต๊ะ</button>";
                echo "</form>";
            }
            echo "</div></div>";
        }

      ?>
    </div>
  </div>
  </div>
  <script src="backend/auto_refresh.js"></script>
  </body>
</html>
