<?php
session_start();
include 'backend/db_connect.php';
include 'backend/auth.php';

if (!isset($_SESSION['EmployeeID'])) {
    header("Location: login.php");
    exit();
}

$employeeID = $_SESSION['EmployeeID'];

$stmt = $conn->prepare("SELECT FName, EmployeeID FROM Employee WHERE EmployeeID = ?");
$stmt->bind_param("i", $employeeID);
$stmt->execute();
$result = $stmt->get_result();
$profile = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>เพิ่มพนักงาน</title>
    <link rel="stylesheet" href="css/employeeProfile.css">
</head>
<body>
<div class="top-bar">
  <div class="home-button" onclick="location.href='admin_dashboard.php'">
    <img src="pics/Home_icon.png">
    <p>หน้าหลัก</p>
  </div>
  <div class="profile-box">
    <img src="img/picture/Profile_guy.png" alt="Profile Picture">
    <div class="profile-label">
      <p class="profile-name"><?= htmlspecialchars($profile['FName']) ?></p>
      <p class="profile-id">ID: <?= htmlspecialchars($profile['EmployeeID']) ?></p>
    </div>
  </div>
</div>

<div class="container">
  <div class="profile-header">
    <span class="section-badge">เพิ่มพนักงานใหม่</span>
  </div>

  <div class="profile-card">
    <div class="profile-left">
      <div class="profile-img">
        <img src="img/picture/Profile_guy.png" alt="Profile Picture">
      </div>
    </div>

    <div class="profile-right">
      <!-- Form เพิ่มพนักงาน -->
      <!-- Form เพิ่มพนักงาน -->
      <form class="profile-info" action="backend/addEmployee.php" method="POST">
        <!-- ลบช่องรหัสพนักงานออก -->
        
        <div class="info-row"><strong>ชื่อ:</strong> <input type="text" name="FName" required></div>
        <div class="info-row"><strong>นามสกุล:</strong> <input type="text" name="LName" required></div>
        <div class="info-row"><strong>รหัสผ่าน:</strong> <input type="password" name="Password" required></div>
        
        <div class="info-row"><strong>ตำแหน่ง:</strong>
          <select name="Role" required>
            <option value="">-- เลือกตำแหน่ง --</option>
            <option value="admin">admin</option>
            <option value="manager">manager</option>
            <option value="staff">staff</option>
          </select>
        </div>

        <div class="info-row"><strong>เริ่มงานเมื่อ:</strong> <input type="date" name="StartDate" required></div>
        <div class="info-row"><strong>เบอร์โทร:</strong> <input type="text" name="Phone" required></div>
        <div class="info-row"><strong>อีเมล:</strong> <input type="email" name="Email" required></div>

        <div class="actions actions-inline">
          <button type="submit" class="btn-small">✅ เพิ่มพนักงาน</button>
          <button type="button" class="btn-small btn-delete" onclick="location.href='employee_list.php'">❌ ยกเลิก</button>
        </div>
      </form>

    </div>
  </div>
</div>
</body>
</html>
