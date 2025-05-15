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
    <title>จัดการสต็อกวัตถุดิบ</title>
    <link rel="stylesheet" href="css/stock.css">
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
            <p class="profile-id">ID: <?= htmlspecialchars($profile['EmployeeID']) ?></p>
        </div>
    </div>
</div>
<div class="container">

    <div class="header-bar">
        <h1>📦 จัดการสต็อกวัตถุดิบ</h1>
        <div class="toolbar-right">
            <button class="btn-green" onclick="location.href='empimport.php'"><span class="btn-icon"></span> เพิ่มวัตถุดิบ</button>
            <button class="btn-gray" onclick="location.href='supimport.php'"><span class="btn-icon"></span> นำเข้าจาก Supplier</button>
        </div>
    </div>
    <div class="toolbar">
        <div class="toolbar-left">
            <input type="text" placeholder="ค้นหาวัตถุดิบ..." class="search-input" />
            <select class="status-filter">
                <option>ทั้งหมด</option>
                <option>ใกล้หมด</option>
                <option>หมดสต็อก</option>
                <option>ปกติ</option>
            </select>
        </div>
    </div>
    <div class="table-wrapper">
    <table>
        <thead>
            <tr>
                <th>ชื่อวัตถุดิบ</th>
                <th>คงเหลือ</th>
                <th>วันหมดอายุ</th>
                <th>วันที่นำเข้า</th>
                <th>สถานะ</th>
                <th>ตัวเลือก</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $today = date('Y-m-d');
        $q = $conn->query("SELECT DISTINCT IngredientName FROM stock ORDER BY IngredientName ASC");
        while ($namerow = $q->fetch_assoc()) {
            $name = $namerow['IngredientName'];

            // 2. ดึงล็อตที่ใกล้หมดอายุที่สุดที่เหลือ > 0
            $batch_sql = "
                SELECT *
                FROM stock
                WHERE IngredientName = ?
                AND Quantity > 0
                ORDER BY ExpirationDate ASC
                LIMIT 1
            ";
            $batch_stmt = $conn->prepare($batch_sql);
            $batch_stmt->bind_param("s", $name);
            $batch_stmt->execute();
            $batch_res = $batch_stmt->get_result();
            $batch = $batch_res->fetch_assoc();
            $batch_stmt->close();

            // ถ้าไม่มีล็อตเหลือเลย ให้ดึงล็อตหมดอายุถัดไป (โชว์ล็อตหมดอายุเร็วสุด)
            if (!$batch) {
                $batch_sql2 = "
                    SELECT *
                    FROM stock
                    WHERE IngredientName = ?
                    ORDER BY ExpirationDate ASC
                    LIMIT 1
                ";
                $batch_stmt2 = $conn->prepare($batch_sql2);
                $batch_stmt2->bind_param("s", $name);
                $batch_stmt2->execute();
                $batch_res2 = $batch_stmt2->get_result();
                $batch = $batch_res2->fetch_assoc();
                $batch_stmt2->close();
            }

            // 3. คำนวณยอดรวม "คงเหลือจริง" ของวัตถุดิบนี้
            $sum_sql = "SELECT SUM(Quantity) as TotalImported, MIN(Unit) as Unit FROM stock WHERE IngredientName = ?";
            $sum_stmt = $conn->prepare($sum_sql);
            $sum_stmt->bind_param("s", $name);
            $sum_stmt->execute();
            $sum_stmt->bind_result($totalImported, $unit);
            $sum_stmt->fetch();
            $sum_stmt->close();

            // 4. ยอดที่ใช้ไปทั้งหมด
            $get_ingredient_id = $conn->prepare("SELECT IngredientID FROM stock WHERE IngredientName = ? LIMIT 1");
            $get_ingredient_id->bind_param("s", $name);
            $get_ingredient_id->execute();
            $get_ingredient_id->bind_result($ingredientID);
            $get_ingredient_id->fetch();
            $get_ingredient_id->close();

            $usedQty = 0;
            if ($ingredientID) {
                $use_sql = "
                    SELECT SUM(iu.QuantityUsed * od.MenuQuantity) as UsedQty
                    FROM IngredientUsage iu
                    JOIN OrderDetail od ON iu.MenuID = od.MenuID
                    JOIN Orders o ON od.OrderID = o.OrderID
                    WHERE iu.IngredientID = ?
                      AND o.Status >= 3
                ";
                $use_stmt = $conn->prepare($use_sql);
                $use_stmt->bind_param("i", $ingredientID);
                $use_stmt->execute();
                $use_stmt->bind_result($usedQty);
                $use_stmt->fetch();
                $use_stmt->close();
            }

            $totalQty = floatval($totalImported) - floatval($usedQty);
            if ($totalQty < 0) $totalQty = 0;

            // Status
            if ($totalQty == 0) {
                $status = 'หมดสต็อก';
                $statusClass = 'out';
            } elseif ($totalQty <= 3) {
                $status = 'ใกล้หมด';
                $statusClass = 'low';
            } else {
                $status = 'ปกติ';
                $statusClass = 'normal';
            }

            // Show info from the chosen batch
            $expire = isset($batch['ExpirationDate']) ? $batch['ExpirationDate'] : null;
            $import = isset($batch['ImportDate']) ? $batch['ImportDate'] : "-";
            $ingredientID = isset($batch['IngredientID']) ? $batch['IngredientID'] : null;

            // 1. เมนูที่ใช้วัตถุดิบนี้
            $menus = [];
            if ($ingredientID) {
                $menu_sql = "
                    SELECT m.Name 
                    FROM ingredientusage iu
                    JOIN menu m ON iu.MenuID = m.MenuID
                    WHERE iu.IngredientID = ?
                ";
                $menu_stmt = $conn->prepare($menu_sql);
                $menu_stmt->bind_param("i", $ingredientID);
                $menu_stmt->execute();
                $menu_result = $menu_stmt->get_result();
                while ($menu_row = $menu_result->fetch_assoc()) {
                    $menus[] = $menu_row['Name'];
                }
                $menu_stmt->close();
            }

            // 2. ผู้นำเข้าล่าสุด (ตาม LastUpdate)
            $supplierName = "พนักงาน";
            if ($ingredientID) {
                $supplier_sql = "
                    SELECT s.SupplierID, sup.Sname 
                    FROM stock s
                    LEFT JOIN supplier sup ON s.SupplierID = sup.SupplierID
                    WHERE s.IngredientID = ?
                    ORDER BY s.LastUpdate DESC
                    LIMIT 1
                ";
                $sup_stmt = $conn->prepare($supplier_sql);
                $sup_stmt->bind_param("i", $ingredientID);
                $sup_stmt->execute();
                $sup_result = $sup_stmt->get_result();
                $sup_row = $sup_result->fetch_assoc();
                $supplierName = $sup_row && $sup_row['SupplierID'] ? $sup_row['Sname'] : "พนักงาน";
                $sup_stmt->close();
            }

            // 3. เวลาล่าสุด
            $lastUpdate = "-";
            if ($ingredientID) {
                $update_sql = "
                    SELECT LastUpdate
                    FROM stock
                    WHERE IngredientID = ?
                    ORDER BY LastUpdate DESC
                    LIMIT 1
                ";
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->bind_param("i", $ingredientID);
                $update_stmt->execute();
                $update_result = $update_stmt->get_result();
                $lastUpdateRow = $update_result->fetch_assoc();
                $lastUpdate = $lastUpdateRow && isset($lastUpdateRow['LastUpdate']) ? $lastUpdateRow['LastUpdate'] : "-";
                $update_stmt->close();
            }

            echo "<tr>";
            echo "<td>{$name}</td>";
            echo "<td>" . number_format($totalQty, 2) . " {$unit}</td>";

            // เช็คหมดอายุ
            if (!$expire) {
                $expireText = "-";
            } elseif ($expire < $today) {
                $expireText = '<span style="color:red">หมดอายุ</span>';
            } else {
                $expireText = $expire;
            }
            echo "<td>{$expireText}</td>";

            echo "<td>{$import}</td>";
            echo "<td><span class='status {$statusClass}'>{$status}</span></td>";
            echo "<td>
                <button class='action-btn view' 
                    data-id='{$ingredientID}'
                    data-name='{$name}'
                    data-qty='{$totalQty}'
                    data-unit='{$unit}'
                    data-expire='{$expire}'
                    data-import='{$import}'
                    data-update='{$lastUpdate}'
                    data-menu='" . htmlspecialchars(implode(',', $menus)) . "'
                    data-by='{$supplierName}'>รายละเอียด</button>
                <button class='action-btn delete'>ลบ</button>
            </td>";
            echo "</tr>";
        }
        ?>
        </tbody>
    </table>
    </div>
    <!-- ... (Alert box และ Popup ต่อเหมือนเดิม) ... -->
</div>
<script src="backend/stock.js"></script>
</body>
</html>
