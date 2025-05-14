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
    <title>ประวัติการนำเข้าวัตถุดิบ</title>
    <link rel="stylesheet" href="css/importHistory.css">
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
            <h1>📦 ประวัติการนำเข้าวัตถุดิบ</h1>

            <div class="toolbar">
            <div class="toolbar-left">
                <input type="text" placeholder="ค้นหาวัตถุดิบ..." class="search-input" />
            </div>
        </div>
        <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>รหัสวัตถุดิบ</th>
                    <th>ชื่อวัตถุดิบ</th>
                    <th>ผู้นำเข้า</th>
                    <th class="sortable" data-sort="import">วันที่นำเข้า ⬍</th>
                    <th class="sortable" data-sort="expire">วันหมดอายุ ⬍</th>
                </tr>
            </thead>
                <tbody>
                    <?php
                    $sql = "
                        SELECT 
                            s.IngredientID,
                            s.IngredientName,
                            s.ImportDate,
                            s.ExpirationDate,
                            COALESCE(sup.Sname, 'พนักงาน') AS Importer
                        FROM stock s
                        LEFT JOIN supplier sup ON s.SupplierID = sup.SupplierID
                        ORDER BY s.ImportDate DESC
                    ";


            $result = $conn->query($sql);

            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['IngredientID']) . "</td>";
                echo "<td>" . htmlspecialchars($row['IngredientName']) . "</td>";
                echo "<td>" . htmlspecialchars($row['Importer']) . "</td>";
                echo "<td>" . htmlspecialchars($row['ImportDate']) . "</td>";
                echo "<td>" . htmlspecialchars($row['ExpirationDate']) . "</td>";
                echo "</tr>";
            }
            ?>
                    </tbody>

        </table>
        
        </div>
        </div>

<script src="backend/historyImport.js"></script>
</body>

</body>
</html>
