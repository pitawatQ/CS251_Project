<?php
session_start();
include 'backend/db_connect.php';
include 'backend/auth.php';

if (!isset($_SESSION['EmployeeID'])) {
    header("Location: login.php");
    exit();
}

$employeeID = $_SESSION['EmployeeID'];

// ลบ Order ที่ถูกยกเลิกเกิน 5 นาที
$conn->query("DELETE od FROM OrderDetail od
              JOIN Orders o ON od.OrderID = o.OrderID
              WHERE o.Status = 0 AND o.OrderTime < NOW() - INTERVAL 5 MINUTE");

$conn->query("DELETE FROM Orders
              WHERE Status = 0 AND OrderTime < NOW() - INTERVAL 5 MINUTE");

$stmt = $conn->prepare("SELECT FName, EmployeeID FROM Employee WHERE EmployeeID = ?");
$stmt->bind_param("i", $employeeID);
$stmt->execute();
$result = $stmt->get_result();
$profile = $result->fetch_assoc();

// Filter เงื่อนไข
$filter = $_GET['filter'] ?? '';
$where = '';
if (in_array($filter, ['0', '2', '3', '4', '5'])) {
    $where = "WHERE o.Status = $filter";
}

// Query รายการอาหาร
$orderQuery = "SELECT o.OrderID, o.TableNo, o.OrderTime, o.Status,
                      TIMESTAMPDIFF(MINUTE, o.OrderTime, NOW()) AS TotalTime,
                      GROUP_CONCAT(CONCAT(m.Name, '<br><span class=\"sub-item\">', od.Description, '</span>') SEPARATOR '<br>') AS MenuList
               FROM Orders o
               JOIN OrderDetail od ON o.OrderID = od.OrderID
               JOIN Menu m ON od.MenuID = m.MenuID
               $where
               GROUP BY o.OrderID
               ORDER BY o.OrderTime DESC";
$orderResult = $conn->query($orderQuery);
?>

<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>รายการอาหารทั้งหมด</title>
  <link href="https://fonts.googleapis.com/css2?family=Sarabun&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/status_order.css">
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
    <h2>🍽️ รายการอาหารทั้งหมด</h2>
    <div class="info">
      <form method="GET" action="">
        <select name="filter" id="filter" onchange="this.form.submit()">
          <option value="">ทั้งหมด</option>
          <option value="2" <?php if ($filter === "2") echo 'selected'; ?>>รอดำเนินการ</option>
          <option value="3" <?php if ($filter === "3") echo 'selected'; ?>>กำลังทำ</option>
          <option value="4" <?php if ($filter === "4") echo 'selected'; ?>>รอเสิร์ฟ</option>
          <option value="5" <?php if ($filter === "5") echo 'selected'; ?>>เสิร์ฟแล้ว</option>
          <option value="0" <?php if ($filter === "0") echo 'selected'; ?>>ยกเลิก</option>
        </select>
      </form>
    </div>

    <div class="table-wrapper">
      <table>
        <thead>
          <tr>
            <th>หมายเลขออเดอร์</th>
            <th>โต๊ะ</th>
            <th>รายการ</th>
            <th>สถานะ</th>
            <th>ใช้เวลาเตรียม</th>
            <th>เวลารวมจนเสิร์ฟ</th>
            <th>ดำเนินการ</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $orderResult->fetch_assoc()):
            $status = (int)$row['Status'];
            $statusText = match ($status) {
              2 => 'รอดำเนินการ',
              3 => 'กำลังทำ',
              4 => 'รอเสิร์ฟ',
              5 => 'เสิร์ฟแล้ว',
              0 => 'ยกเลิกแล้ว',
              default => 'ไม่ทราบ'
            };

            $statusClass = match ($status) {
              2 => 'waiting',
              3 => 'cooking',
              4 => 'serving',
              5 => 'done',
              0 => 'canceled',
              default => ''
            };

            $timeToCook = 15;
            $totalTime = $row['TotalTime'] ?? '-';
          ?>
          <tr>
            <td><?php echo htmlspecialchars($row['OrderID']); ?></td>
            <td><?php echo htmlspecialchars($row['TableNo']); ?></td>
            <td><?php echo $row['MenuList']; ?></td>
            <td><span class="status <?php echo $statusClass; ?>"><?php echo $statusText; ?></span></td>
            <td><?php echo $timeToCook; ?> นาที</td>
            <td><?php echo $totalTime; ?> นาที</td>
            <td>
              <?php if ($status === 0): ?>
                <button class="action cancel" style="background-color: #ccc;" disabled>ยกเลิก</button>
              <?php elseif ($status < 5): ?>
                <form action="backend/update_order.php" method="POST" onsubmit="return confirm('คุณแน่ใจว่าต้องการยกเลิกออเดอร์นี้?')">
                  <input type="hidden" name="OrderID" value="<?php echo $row['OrderID']; ?>">
                  <input type="hidden" name="redirect" value="status_order.php">
                  <button type="submit" name="action" value="cancel" class="action cancel">ยกเลิก</button>
                </form>
              <?php elseif ($status == 5): ?>
                <button class="action served" disabled>เสิร์ฟแล้ว</button>
              <?php endif; ?>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>


 <script src="backend/auto_refresh.js"></script>
</body>
</html>
