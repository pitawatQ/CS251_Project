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
        <p class="profile-name"><?php echo htmlspecialchars($profile['FName']); ?></p>
        <p class="profile-id">ID: <?php echo htmlspecialchars($profile['EmployeeID']); ?></p>
      </div>
    </div>
  </div>
    <div class="container">
        
       <div class="header-bar">
            <h1>📦 จัดการสต็อกวัตถุดิบ</h1>
                <div class="toolbar-right">
                    <button class="btn-green" onclick="location.href='empimport.php'">
                        <span class="btn-icon"></span> เพิ่มวัตถุดิบ
                    </button>
                    <button class="btn-gray" onclick="location.href='supimport.php'">
                        <span class="btn-icon"></span> นำเข้าจาก Supplier
                    </button>
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
                    <th>รหัสวัตถุดิบ</th>
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
                    $sql = "
                        SELECT 
                            s.IngredientID,
                            s.IngredientName,
                            s.Unit,
                            s.ExpirationDate,
                            s.ImportDate,
                            summary.TotalQty
                        FROM stock s
                        JOIN (
                            SELECT IngredientName, SUM(Quantity) AS TotalQty
                            FROM stock
                            GROUP BY IngredientName
                        ) summary ON s.IngredientName = summary.IngredientName
                        WHERE s.ExpirationDate = (
                            SELECT 
                                IFNULL(
                                    -- ถ้ามีรายการเหลืออยู่
                                    MAX(s2.ExpirationDate),
                                    -- ถ้าหมดทุกชิ้น ให้เอาหมดอายุช้าสุดจากทั้งหมด
                                    (SELECT MAX(s3.ExpirationDate) 
                                    FROM stock s3 
                                    WHERE s3.IngredientName = s.IngredientName)
                                )
                            FROM stock s2
                            WHERE s2.IngredientName = s.IngredientName AND s2.Quantity > 0
                        )
                        GROUP BY s.IngredientName
                    ";

            $result = $conn->query($sql);

            while ($row = $result->fetch_assoc()) {
                $ingredientID = $row['IngredientID'];
                $name = $row['IngredientName'];
                $totalQty = floatval($row['TotalQty']);
                $unit = $row['Unit'];
                $expire = $row['ExpirationDate'];
                $import = $row['ImportDate'];

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
                // 1. เมนูที่ใช้วัตถุดิบนี้
                $menus = [];
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

                // 2. ผู้นำเข้า
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
                $supplierName = $sup_row['SupplierID'] ? $sup_row['Sname'] : "พนักงาน";
                $sup_stmt->close();

                // 3. เวลาล่าสุด
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
                $lastUpdate = $update_result->fetch_assoc()['LastUpdate'];
                $update_stmt->close();

                echo "<tr>";
                echo "<td>{$ingredientID}</td>";
                echo "<td>{$name}</td>";
                echo "<td>{$totalQty} {$unit}</td>";
                echo "<td>{$expire}</td>";
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
                        data-menu='" . implode(',', $menus) . "'
                        data-by='{$supplierName}'>รายละเอียด</button>
                    <button class='action-btn delete'>ลบ</button>
                </td>";
                echo "</tr>";
            }

                    ?>
                    </tbody>

        </table>
        
        </div>
        <div class="alert-box">
            <span id="alert-text">ไม่มีแจ้งเตือน</span>
            <button id="next-alert-btn">ถัดไป</button>
            </div>
            <?php
            $alert_sql = "
            SELECT IngredientName, SUM(Quantity) AS TotalQty
            FROM stock
            GROUP BY IngredientName
            HAVING TotalQty <= 3
            ";

            $alert_result = $conn->query($alert_sql);
            $alerts = [];

            while ($row = $alert_result->fetch_assoc()) {
                $alerts[] = "❗ {$row['IngredientName']} เหลือ {$row['TotalQty']} หน่วย กรุณาตรวจสอบ";
            }
                ?>
            <script>
            const alerts = <?php echo json_encode($alerts); ?>;
            </script>
        </div>
        <div class="popup-overlay" id="popup" style="display: none;">
        <div class="popup-content">
            <button class="popup-close" onclick="document.getElementById('popup').style.display='none'">×</button>
            <h2 class="popup-title">รายละเอียดวัตถุดิบ: <span id="popup-name"></span></h2>
            <table class="detail-table">
            <tr><th>รหัส</th><td id="popup-id">IG-001</td></tr>
            <tr><th>คงเหลือ</th><td id="popup-qty">2 กก.</td></tr>
            <tr><th>วันหมดอายุ</th><td id="popup-expire">2025-04-09</td></tr>
            <tr><th>วันที่นำเข้า</th><td id="popup-import">2025-04-01</td></tr>
            <tr><th>อัปเดตล่าสุด</th><td id="popup-update">2025-04-06 10:30</td></tr>
            <tr><th>ใช้ในเมนู</th><td><ul id="popup-menu"><li>ยำผักกาดหอม</li><li>ข้าวผัดสุขภาพ</li></ul></td></tr>
            <tr><th>ผู้นำเข้า</th><td id="popup-by">เกวลิน ธนเศรษฐ์ชัย</td></tr>

            </table>
        </div>
        </div>


    <script src="backend/stock.js"></script>
</body>

</body>
</html>
