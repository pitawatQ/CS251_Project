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
  <title>อาหารที่ต้องเสิร์ฟ</title>
  <link href="https://fonts.googleapis.com/css2?family=Sarabun&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/readytoserve.css">
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
  <h2>🍽️ อาหารที่ต้องเสิร์ฟ</h2>
  <div class="table-wrapper">
    <table>
      <thead>
        <tr>
          <th>หมายเลขออเดอร์</th>
          <th>โต๊ะ</th>
          <th>รายการ</th>
          <th>สถานะ</th>
          <th>เวลา</th>
          <th>เปลี่ยนสถานะ</th>
        </tr>
      </thead>
      <tbody>
      <?php
      $sql = "SELECT o.OrderID, o.TableNo, o.OrderTime, o.Status,
                     GROUP_CONCAT(CONCAT(m.Name, '<br><span class=\"sub-item\">', od.Description, '</span>') SEPARATOR '<br>') AS MenuList
              FROM Orders o
              JOIN OrderDetail od ON o.OrderID = od.OrderID
              JOIN Menu m ON od.MenuID = m.MenuID
              WHERE o.Status IN (4, 5)
              GROUP BY o.OrderID
              ORDER BY o.OrderTime DESC";

      $result = $conn->query($sql);

      while ($row = $result->fetch_assoc()):
          $status = (int)$row['Status'];
          $statusClass = match ($status) {
              2 => 'waiting',
              3 => 'cooking',
              4 => 'serving',
              5 => 'done',
              0 => 'canceled',
              default => ''
          };
          $statusText = match ($status) {
              2 => 'รอดำเนินการ',
              3 => 'กำลังทำ',
              4 => 'รอเสิร์ฟ',
              5 => 'เสร็จ',
              0 => 'ยกเลิกแล้ว',
              default => 'ไม่ทราบ',
          };
      ?>
      <tr>
        <td><?php echo htmlspecialchars($row['OrderID']); ?></td>
        <td><?php echo htmlspecialchars($row['TableNo']); ?></td>
        <td><?php echo $row['MenuList']; ?></td>
        <td><span class="status <?php echo $statusClass; ?>"><?php echo $statusText; ?></span></td>
        <td><?php echo date('H:i', strtotime($row['OrderTime'])); ?></td>
        <td>
          <?php if ($status === 4): ?>
            <form action="backend/update_order.php" method="POST" onsubmit="return confirm('คุณแน่ใจหรือไม่ว่าต้องการเสิร์ฟออเดอร์นี้?');">
              <input type="hidden" name="OrderID" value="<?php echo $row['OrderID']; ?>">
              <input type="hidden" name="redirect" value="readytoserve.php">
              <button type="submit" name="action" value="next" class="status-btn next">ดำเนินการเสิร์ฟ</button>
            </form>
          <?php elseif ($status === 5): ?>
            <button class="status-btn done" disabled>เสร็จสิ้น</button>
          <?php endif; ?>
        </td>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>


<script src="js/auto_refresh.js"></script>
</body>
</html>
