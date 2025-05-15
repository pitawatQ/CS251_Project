<?php
date_default_timezone_set('Asia/Bangkok'); // หรือโซนเวลาที่ใช้จริง
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
    <title>Daily Report</title>
    <link rel="stylesheet" type="text/css" href="css/daily_report.css">
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
    <h1 class="title">📊 สรุปภาพรวมประจำวัน</h1>
    <div class="report-grid">

        <?php
        $today = date('Y-m-d');

        $sql = "
            SELECT c.CName AS CategoryName, COUNT(*) AS TotalItems, SUM(od.TotalPrice) AS TotalPrice
            FROM OrderDetail od
            JOIN Menu m ON od.MenuID = m.MenuID
            JOIN Category c ON m.CategoryID = c.CategoryID
            JOIN Orders o ON od.OrderID = o.OrderID
            WHERE DATE(o.OrderTime) = ? AND o.Status != 5
            GROUP BY c.CategoryID
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $today);
        $stmt->execute();
        $result = $stmt->get_result();

        $categoryData = [];
        while ($row = $result->fetch_assoc()) {
            $categoryData[] = $row;
        }
        ?>

        <div class="report-box">
            <h2>🍴 สรุปการสั่งอาหาร</h2>
            <?php
            $totalItems = 0;
            $totalBaht = 0;

            foreach ($categoryData as $cat) {
                echo "<p>{$cat['CategoryName']}: {$cat['TotalItems']} รายการ (฿" . number_format($cat['TotalPrice'], 2) . ")</p>";
                $totalItems += $cat['TotalItems'];
                $totalBaht += $cat['TotalPrice'];
            }
            ?>
            <p><strong>รวมทั้งหมด: <?= $totalItems ?> รายการ (฿<?= number_format($totalBaht, 2) ?>)</strong></p>
        </div>

        <div class="report-box">
            <h2>💰 รายได้สุทธิ</h2>
            <?php
            $vat = $totalBaht * 0.07;
            $net = $totalBaht + $vat;
            ?>
            <p>ก่อน VAT: ฿<?= number_format($totalBaht, 2) ?></p>
            <p>ภาษีมูลค่าเพิ่ม 7%: ฿<?= number_format($vat, 2) ?></p>
            <p><strong class="highlight">ยอดสุทธิ: ฿<?= number_format($net, 2) ?></strong></p>
        </div>


        <div class="report-box">
            <h2>👨‍🍳 สถานะคำสั่งอาหาร</h2>
            <?php
            $statusQuery = $conn->query("
                SELECT Status, COUNT(*) as Total FROM Orders
                WHERE DATE(OrderTime) = '$today'
                GROUP BY Status
            ");
            $cooking = $served = $cancel = 0;
            while ($row = $statusQuery->fetch_assoc()) {
                switch ($row['Status']) {
                    case 2: $cooking = $row['Total']; break;
                    case 6: $served = $row['Total']; break;
                    case 5: $cancel = $row['Total']; break;
                }
            }
            ?>
            <p>กำลังปรุง: <span class="status cooking"><?= $cooking ?></span></p>
            <p>เสิร์ฟแล้ว: <span class="status served"><?= $served ?></span></p>
            <p>ยกเลิก: <span class="status cancled"><?= $cancel ?></span></p>
        </div>

        <div class="report-box">
            <h2>⏰ การเข้าออกงานพนักงาน</h2>
            <?php
            $attQuery = $conn->query("
                SELECT e.FName, a.EmployeeID, a.ClockInTime, a.ClockOutTime
                FROM Attendance a
                LEFT JOIN Employee e ON e.EmployeeID = a.EmployeeID
                WHERE DATE(a.WorkDate) = '$today'
                ORDER BY a.ClockInTime ASC
            ");
            $found = false;
            while ($row = $attQuery->fetch_assoc()):
                $found = true;
                $clockin = $row['ClockInTime'] ?: '-';
                $clockout = $row['ClockOutTime'] ?: '-';
                $fname = $row['FName'] ?: "ID: ".$row['EmployeeID'];
                $status = ($row['ClockOutTime'] && $row['ClockOutTime'] !== "00:00:00") ? 'ออกงานแล้ว' : 'กำลังทำงาน';
                $statusClass = ($status === 'ออกงานแล้ว') ? 'off-work' : 'working';
            ?>
                <p>
                    <?= htmlspecialchars($fname) ?> - เข้า: <?= $clockin ?> / ออก: <?= $clockout ?>
                    <span class="status <?= $statusClass ?>"><?= $status ?></span>
                </p>
            <?php endwhile;
            if (!$found) {
                echo "<p style='color:gray'>ยังไม่มีพนักงานเข้างานวันนี้</p>";
            }
            ?>

        </div>
            <div class="report-box">
    <h2>🥦 วัตถุดิบที่ใช้วันนี้</h2>
    <table style="width:100%">
        <thead>
            <tr>
                <th style="text-align:left">วัตถุดิบ</th>
                <th style="text-align:right">ใช้ไป (หน่วย)</th>
            </tr>
        </thead>
        <tbody>
        <?php
        // คิวรีหายอดใช้วัตถุดิบของวันนี้
        $sql = "
            SELECT s.IngredientName, SUM(iu.QuantityUsed * od.MenuQuantity) AS UsedQty, s.Unit
            FROM Orders o
            JOIN OrderDetail od ON o.OrderID = od.OrderID
            JOIN IngredientUsage iu ON od.MenuID = iu.MenuID
            JOIN Stock s ON iu.IngredientID = s.IngredientID
            WHERE DATE(o.OrderTime) = ?
              AND o.Status >= 3
            GROUP BY s.IngredientName, s.Unit
            ORDER BY UsedQty DESC
        ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $today);
        $stmt->execute();
        $result = $stmt->get_result();
        $found = false;
        while ($row = $result->fetch_assoc()):
            if ($row['UsedQty'] > 0) {
                $found = true;
        ?>
            <tr>
                <td><?= htmlspecialchars($row['IngredientName']) ?></td>
                <td style="text-align:right"><?= number_format($row['UsedQty'], 2) . ' ' . htmlspecialchars($row['Unit']) ?></td>
            </tr>
        <?php
            }
        endwhile;
        if (!$found) {
            echo "<tr><td colspan='2' style='color:gray'>- ไม่มีการใช้วัตถุดิบวันนี้ -</td></tr>";
        }
        ?>
        </tbody>
    </table>
</div>

        <div class="report-box">
            <h2>📦 สถานะสต็อกสินค้า</h2>
            <?php
            $stockQuery = $conn->query("
                SELECT IngredientName, Quantity
                FROM Stock
                ORDER BY Quantity ASC
                LIMIT 3
            ");
            while ($stock = $stockQuery->fetch_assoc()) {
                $qty = floatval($stock['Quantity']);
                $status = $qty < 5 ? 'เหลือน้อย' : ($qty < 15 ? 'ใกล้หมด' : 'ปกติ');
                echo "<p>{$stock['IngredientName']}: {$status}</p>";
            }
            ?>
        </div>
    </div>
</div>

<div class="exit-button" onclick="location.href='login.php'">
    <img src="img/picture/Exit_door.png" alt="Exit">
</div>
</body>
</html>
