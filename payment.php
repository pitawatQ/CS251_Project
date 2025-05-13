<?php /*payment.php*/
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
$tableNo = $_GET['table'] ?? null;
if (!$tableNo) {
    echo "ไม่พบหมายเลขโต๊ะ";
    exit();
}

// เตรียมคำสั่ง SQL ดึงรายการอาหารทั้งหมดของโต๊ะนั้น (หลายออเดอร์รวมกัน)
$sql = "
SELECT m.Name AS MenuName, od.MenuQuntity AS Quantity, od.UnitPrice, od.TotalPrice, o.OrderID
FROM Orders o
JOIN OrderDetail od ON o.OrderID = od.OrderID
JOIN Menu m ON od.MenuID = m.MenuID
WHERE o.TableNo = ? AND o.Status != 6
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $tableNo);
$stmt->execute();
$result = $stmt->get_result();

// สรุปรายการ
$items = [];
$totalQty = 0;
$totalPrice = 0;

while ($row = $result->fetch_assoc()) {
    $name = $row['MenuName'];
    $qty = (int)$row['Quantity'];
    $price = (float)$row['UnitPrice'];
    $discount = 0; // ไม่มีฟิลด์ Discount ในตาราง

    $subtotal = $qty * $price;

    $items[] = [
        'name' => $name,
        'qty' => $qty,
        'price' => $price,
        'discount' => $discount,
        'total' => round($subtotal, 2)
    ];

    $totalQty += $qty;
    $totalPrice += $subtotal;
}

$vat = round($totalPrice * 0.07, 2);
$grandTotal = round($totalPrice + $vat, 2);

// ✅ ทำหลังจากคำนวณเสร็จ
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pay_method'])) {
    $method = $_POST['pay_method'];
    $now = date("Y-m-d H:i:s");

    $orderIDs = [];
    $stmt = $conn->prepare("SELECT OrderID FROM Orders WHERE TableNo = ? AND Status != 6");
    $stmt->bind_param("i", $tableNo);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $orderIDs[] = $row['OrderID'];
    }
    $stmt->close();

    foreach ($orderIDs as $orderID) {
        $stmt = $conn->prepare("UPDATE Orders SET Status = 6 WHERE OrderID = ?");
        $stmt->bind_param("i", $orderID);
        $stmt->execute();
        $stmt->close();

        $invoiceNo = rand(700000, 799999);
        $discount = 0;

        $stmt = $conn->prepare("INSERT INTO Payment (OrderID, PaymentMethod, TotalPaid, PaymentDate, InvoiceNo, TotalDiscount, EmployeeID, Vat)
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isdsiidi", $orderID, $method, $grandTotal, $now, $invoiceNo, $discount, $employeeID, $vat);
        $stmt->execute();
        $stmt->close();
    }
    $updateTable = $conn->prepare("UPDATE tablelist SET Status = 0 WHERE TableNo = ?");
    $updateTable->bind_param("i", $tableNo);
    $updateTable->execute();
    $updateTable->close();
    header("Location: table_status.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>ใบสรุปรายการอาหาร</title>
  <link href="https://fonts.googleapis.com/css2?family=Sarabun&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/payment.css">
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
  <h2>📁 ใบสรุปรายการอาหาร</h2>
  <div class="info">
    โต๊ะที่: <b><?= htmlspecialchars($tableNo) ?></b><br>
    แคชเชียร์: <?= htmlspecialchars($profile['FName']) ?><br>
    วันที่: <?= date("d/m/Y H:i") ?>
  </div>

  <table>
    <tr>
      <th>รายการอาหาร</th>
      <th>จำนวน</th>
      <th>ราคา/ หน่วย</th>
      <th>รวม</th>
    </tr>
    <?php foreach ($items as $item): ?>
    <tr>
      <td><?= htmlspecialchars($item['name']) ?></td>
      <td><?= $item['qty'] ?></td>
      <td><?= number_format($item['price'], 2) ?></td>
      <td><?= number_format($item['total'], 2) ?></td>
    </tr>
    <?php endforeach; ?>
  </table>

  <div class="summary">
    ยอดรวมอาหาร <?= $totalQty ?> รายการ: <?= number_format($totalPrice, 2) ?><br>
    ภาษีมูลค่าเพิ่ม 7%: <?= number_format($vat, 2) ?><br>
    <br> <!-- เพิ่มบรรทัดว่าง -->
    <span class="total">ยอดสุทธิที่ต้องชำระ: <?= number_format($grandTotal, 2) ?></span>
  </div>


  <div class="button-row">
    <form method="POST">
      <button type="submit" name="pay_method" value="เงินสด" class="pay-button">💵 ชำระเงินสด</button>
      <button type="submit" name="pay_method" value="QR" class="pay-button">📱 ชำระผ่าน QR</button>
    </form>
  </div>
</div>
</body>
</html>
