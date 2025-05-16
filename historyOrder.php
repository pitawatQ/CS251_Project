<?php
session_start();
include 'backend/db_connect.php';
include 'backend/auth.php';

if (!isset($_SESSION['EmployeeID'])) {
    header("Location: login.php");
    exit();
}

$employeeID = $_SESSION['EmployeeID'];

$stmt = $conn->prepare("SELECT FName FROM Employee WHERE EmployeeID = ?");
$stmt->bind_param("i", $employeeID);
$stmt->execute();
$profile = $stmt->get_result()->fetch_assoc();
$stmt->close();

// ดึงประวัติการชำระเงิน
$sql = "
    SELECT 
        o.OrderID, o.TableNo, o.Status,
        p.PaymentDate, p.TotalPaid, p.PaymentID,
        GROUP_CONCAT(CONCAT(m.Name, IF(od.Description != '', CONCAT(' (', od.Description, ')'), '')) SEPARATOR '<br>') AS MenuList
    FROM Orders o
    JOIN OrderDetail od ON o.OrderID = od.OrderID
    JOIN Menu m ON od.MenuID = m.MenuID
    JOIN Payment p ON o.OrderID = p.OrderID
    WHERE o.Status = 6
    GROUP BY o.OrderID
    ORDER BY p.PaymentDate DESC
";
$orderResult = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>ประวัติการชำระเงิน</title>
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
        <p class="profile-name"><?= htmlspecialchars($profile['FName']) ?></p>
        <p class="profile-id">ID: <?= htmlspecialchars($employeeID) ?></p>
      </div>
    </div>
  </div>

  <div class="container">
    <h2>ประวัติการชำระเงิน</h2>
    <div class="table-wrapper">
      <table>
        <thead>
          <tr>
            <th>หมายเลขชำระเงิน</th>
            <th>โต๊ะ</th>
            <th>รายการ</th>
            <th>วันที่จ่าย</th>
            <th>รวมเงิน (บาท)</th>
            <th>ใบเสร็จ</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $orderResult->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['PaymentID']) ?></td>
              <td><?= htmlspecialchars($row['TableNo']) ?></td>
              <td><?= $row['MenuList'] ?></td>
              <td><?= date("d/m/Y H:i", strtotime($row['PaymentDate'])) ?></td>
              <td><?= number_format($row['TotalPaid'], 2) ?></td>
              <td>
                <a href="backend/receipt.php?ref=<?= htmlspecialchars($row['PaymentID']) ?>" class="action served" target="_blank">ดูใบเสร็จ</a>
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
