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

if (!isset($_GET['id'])) {
    header("Location: employee_list.php");
    exit();
}

$targetID = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM Employee WHERE EmployeeID = ?");
$stmt->bind_param("i", $targetID);
$stmt->execute();
$result = $stmt->get_result();
$employee = $result->fetch_assoc();

if (!$employee) {
    echo "<p>ไม่พบข้อมูลพนักงาน</p>";
    exit();
}

$check = $conn->prepare("SELECT * FROM Attendance WHERE EmployeeID = ? AND WorkDate = CURDATE() AND ClockOutTime IS NULL");
$check->bind_param("i", $targetID);
$check->execute();
$statusText = "ไม่ได้ทำงานขณะนี้";
$statusClass = "status-inactive";
if ($check->get_result()->num_rows > 0) {
    $statusText = "ทำงานอยู่";
    $statusClass = "status-working";
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>โปรไฟล์พนักงาน</title>
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
    <span class="section-badge">จัดการโปรไฟล์</span>
  </div>

  <div class="profile-card">
    <div class="profile-left">
      <div class="profile-img">
        <img src="img/picture/Profile_guy.png" alt="Profile Picture">
      </div>
    </div>

    <div class="profile-right">
      <!-- Form แก้ไข -->
      <form class="profile-info" id="editForm" action="backend/update_employee.php" method="POST" style="display: none;">
        <input type="hidden" name="EmployeeID" value="<?= $employee['EmployeeID'] ?>">
        <div class="info-row"><strong>ชื่อ:</strong> <input type="text" name="FName" value="<?= htmlspecialchars($employee['FName']) ?>"></div>
        <div class="info-row"><strong>นามสกุล:</strong> <input type="text" name="LName" value="<?= htmlspecialchars($employee['LName']) ?>"></div>
        <div class="info-row"><strong>ตำแหน่ง:</strong> <input type="text" name="Role" value="<?= htmlspecialchars($employee['Role']) ?>"></div>
        <div class="info-row"><strong>เบอร์โทร:</strong> <input type="text" name="Phone" value="<?= htmlspecialchars($employee['Phone']) ?>"></div>
        <div class="info-row"><strong>อีเมล:</strong> <input type="email" name="Email" value="<?= htmlspecialchars($employee['Email']) ?>"></div>

        <div class="actions actions-inline">
          <button type="submit" class="btn-small">ยืนยัน</button>
          <button type="button" class="btn-small btn-delete" onclick="cancelEdit()">ยกเลิก</button>
        </div>
      </form>

      <!-- โหมดแสดงผล -->
      <div class="profile-info" id="viewMode">
        <div class="info-row"><strong>ชื่อ-นามสกุล:</strong> <?= htmlspecialchars($employee['FName'] . ' ' . $employee['LName']) ?></div>
        <div class="info-row"><strong>รหัสพนักงาน:</strong> <?= htmlspecialchars($employee['EmployeeID']) ?></div>
        <div class="info-row"><strong>ตำแหน่ง:</strong> <span class="badge"><?= htmlspecialchars($employee['Role']) ?></span></div>
        <div class="info-row"><strong>สถานะ:</strong> <span class="<?= $statusClass ?>"><?= $statusText ?></span></div>
        <div class="info-row"><strong>เริ่มงานเมื่อ:</strong> <?= htmlspecialchars($employee['StartDate']) ?></div>
        <div class="info-row"><strong>เบอร์โทร:</strong> <?= htmlspecialchars($employee['Phone'] ?? '-') ?></div>
        <div class="info-row"><strong>อีเมล:</strong> <?= htmlspecialchars($employee['Email'] ?? '-') ?></div>
      </div>

      <!-- ปุ่มสำหรับโหมดแสดง -->
      <div class="actions actions-inline" id="viewButtons">
        <a href="#" class="btn-small" onclick="enableEdit()">แก้ไข</a>
        <a href="backend/delEmployee.php?id=<?= $employee['EmployeeID'] ?>" class="btn-small btn-delete" onclick="return confirm('ลบพนักงานนี้หรือไม่?')">ลบ</a>

      </div>
    </div>
  </div>
</div>

<script>
function enableEdit() {
  document.getElementById('viewMode').style.display = 'none';
  document.getElementById('viewButtons').style.display = 'none';

  document.getElementById('editForm').style.display = 'flex';
}

function cancelEdit() {
  document.getElementById('editForm').style.display = 'none';
  document.getElementById('viewMode').style.display = 'flex';
  document.getElementById('viewButtons').style.display = 'flex';
}
</script>

</body>
</html>
