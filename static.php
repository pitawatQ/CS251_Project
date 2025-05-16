<?php
session_start();
include 'backend/db_connect.php';
include 'backend/auth.php';

if (!isset($_SESSION['EmployeeID'])) {
    header("Location: login.php");
    exit();
}

$type = $_GET['type'] ?? 'menu'; // 'menu' หรือ 'ingredient'

?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>สถิติร้านอาหาร</title>
    <link rel="stylesheet" href="css/menu_list.css">
</head>
<body>
<div class="top-bar">
    <div class="home-button" onclick="location.href='admin_dashboard.php'">
        <img src="pics/Home_icon.png"><p>หน้าหลัก</p>
    </div>
</div>
<div class="container">
    <div style="margin-bottom:1.5em;">
        <button onclick="window.location='static.php?type=menu'" class="tab-btn <?= $type=='menu'?'active':'' ?>">🏆 เมนูยอดนิยม</button>
        <button onclick="window.location='static.php?type=ingredient'" class="tab-btn <?= $type=='ingredient'?'active':'' ?>">🥬 วัตถุดิบใช้เยอะสุด</button>
    </div>

    <?php if ($type == 'ingredient'): ?>
        <h2>🥬 วัตถุดิบที่ใช้เยอะสุด</h2>
        <table>
            <thead>
                <tr>
                    <th>อันดับ</th>
                    <th>วัตถุดิบ</th>
                    <th>ใช้ไป (กก.)</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "
                    SELECT s.IngredientName, SUM(iu.QuantityUsed * od.MenuQuantity) as TotalUsed
                    FROM OrderDetail od
                    JOIN Orders o ON od.OrderID = o.OrderID
                    JOIN IngredientUsage iu ON od.MenuID = iu.MenuID
                    JOIN Stock s ON iu.IngredientID = s.IngredientID
                    WHERE o.Status >= 3
                    GROUP BY s.IngredientID
                    ORDER BY TotalUsed DESC
                    LIMIT 10
                ";
                $res = $conn->query($sql);
                $rank = 1;
                while($row = $res->fetch_assoc()):
                ?>
                <tr>
                    <td><?= $rank++ ?></td>
                    <td><?= htmlspecialchars($row['IngredientName']) ?></td>
                    <td><?= number_format($row['TotalUsed'],2) ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <h2>🏆 เมนูยอดนิยม</h2>
        <table>
            <thead>
                <tr>
                    <th>อันดับ</th>
                    <th>ชื่อเมนู</th>
                    <th>จำนวนที่สั่ง</th>
                    <th>ยอดขาย (บาท)</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "
                    SELECT m.MenuID, m.Name, SUM(od.MenuQuantity) as TotalSold, SUM(od.TotalPrice) as Revenue
                    FROM OrderDetail od
                    JOIN Menu m ON od.MenuID = m.MenuID
                    JOIN Orders o ON od.OrderID = o.OrderID
                    WHERE o.Status >= 3
                    GROUP BY m.MenuID
                    ORDER BY TotalSold DESC
                    LIMIT 10
                ";
                $res = $conn->query($sql);
                $rank = 1;
                while($row = $res->fetch_assoc()):
                ?>
                <tr>
                    <td><?= $rank++ ?></td>
                    <td><?= htmlspecialchars($row['Name']) ?></td>
                    <td><?= number_format($row['TotalSold']) ?></td>
                    <td><?= number_format($row['Revenue'], 2) ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
<style>
.tab-btn { padding:12px 30px; border:none; border-radius: 8px 8px 0 0; background:#f3f3f3; font-size:16px; font-weight:bold; margin-right:6px; cursor:pointer;}
.tab-btn.active { background:#28c95b; color:white; }
</style>
</body>
</html>
