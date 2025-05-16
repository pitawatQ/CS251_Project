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
<div class="top-bar">
  <div class="home-button" onclick="location.href='admin_dashboard.php'">
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
    <div class="header-bar">
        <h1>จัดการพนักงาน</h1>
        <button class="btn-add" onclick="location.href='createEmployee.php'">เพิ่มพนักงาน</button>
    </div>
    <div class="toolbar">
        <input type="text" class="search-input" placeholder="ค้นหาชื่อ/รหัสพนักงาน..." />
        <select class="status-filter">
            <option>ทั้งหมด</option>
            <option>admin</option>
            <option>manager</option>
            <option>staff</option>
        </select>
    </div>

        <div class="employee-list">
        <?php
        $sql = "
            SELECT e.EmployeeID, e.FName, e.LName, e.Role, e.StartDate,
                a.ClockInTime, a.ClockOutTime
            FROM Employee e
            LEFT JOIN Attendance a 
            ON e.EmployeeID = a.EmployeeID 
            AND a.WorkDate = CURDATE()
            ORDER BY e.Role, e.StartDate";

        $result = $conn->query($sql);

        while ($row = $result->fetch_assoc()):
            $fullname = htmlspecialchars($row['FName'] . ' ' . $row['LName']);
            $employeeID = htmlspecialchars($row['EmployeeID']);
            $startDate = htmlspecialchars($row['StartDate']);
            $role = htmlspecialchars($row['Role']);

            // อายุงาน
            $start = new DateTime($row['StartDate']);
            $now = new DateTime();
            $months = ($start->diff($now)->y * 12) + $start->diff($now)->m;

            // ป้ายสี role
            $roleKey = strtolower(trim($row['Role']));
            $roleBadge = match($roleKey) {
                'admin' => 'badge-admin',
                'manager' => 'badge-manager',
                'staff' => 'badge-service',
                default => 'badge-default',
            };

            // เช็กสถานะจาก Attendance
            if (!empty($row['ClockInTime']) && empty($row['ClockOutTime'])) {
                $statusText = 'ทำงานอยู่';
                $statusClass = 'status-working';
            } else {
                $statusText = 'ไม่ได้ทำขณะนี้';
                $statusClass = 'status-inactive';
            }

        ?>
            <div class="employee-box">
                <div class="employee-info">
                    <div class="employee-name"><?= $fullname ?></div>
                    <div class="employee-details"><?= $employeeID ?> • เริ่มงาน: <?= $startDate ?> • อายุงาน: <?= $months ?> เดือน</div>
                </div>
                <div class="employee-meta">
                    <span class="badge <?php echo $roleBadge; ?>"><?php echo htmlspecialchars($row['Role']); ?></span>
                    <span class="status <?= $statusClass ?>"><?= $statusText ?></span>
                    <a href="employeeProfile.php?id=<?= $employeeID ?>" class="btn-small">ดูโปรไฟล์</a>
                    <a href="backend/delEmployee.php?id=<?= $employeeID ?>" class="btn-small btn-delete" onclick="return confirm('ลบพนักงานนี้หรือไม่?')">ลบ</a>

                </div>
            </div>
        <?php endwhile; ?>
        </div>
<script src="backend/searchEmp.js"></script>
</div>
</body>
</html>
