<?php
session_start();
include 'db_connect.php';

$ref = $_GET['ref'] ?? null;

if (!$ref) {
    echo "ไม่พบใบเสร็จ";
    exit();
}

// ดึงข้อมูลจากตาราง Payment
$stmt = $conn->prepare("
    SELECT p.*, e.FName, o.TableNo 
    FROM payment p 
    JOIN orders o ON p.OrderID = o.OrderID
    JOIN employee e ON p.EmployeeID = e.EmployeeID
    WHERE p.PaymentID = ?
");
$stmt->bind_param("i", $ref);
$stmt->execute();
$result = $stmt->get_result();
$payment = $result->fetch_assoc();

if (!$payment) {
    echo "ไม่พบข้อมูลใบเสร็จ";
    exit();
}

// ดึงรายการอาหาร
$stmt2 = $conn->prepare("
    SELECT m.Name AS MenuName, od.MenuQuntity AS Quantity, od.UnitPrice
    FROM orderdetail od
    JOIN menu m ON od.MenuID = m.MenuID
    WHERE od.OrderID = ?
");
$stmt2->bind_param("i", $payment['OrderID']);
$stmt2->execute();
$items = $stmt2->get_result();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ใบเสร็จ#<?= $payment['PaymentID'] ?></title>
    <link rel="stylesheet" href="../css/receipt.css">
</head>
<body>

<div style="text-align: center;">
    <img src="../pics/brand.png" alt="Restaurant Logo" style="max-width: 300px; margin-bottom: 10px;">
</div>
<h3>ใบเสร็จรับเงิน</h3>
<p style="font-size: 18px;">
Cashier: <?= htmlspecialchars($payment['FName']) ?><br>
Date: <?= date("d/m/Y H:i", strtotime($payment['PaymentDate'])) ?><br>
Ref: #<?= $payment['PaymentID'] ?><br>
โต๊ะ: <?= $payment['TableNo'] ?>
</p>
<hr>

<?php
$subTotal = 0;
while ($row = $items->fetch_assoc()):
    $lineTotal = $row['Quantity'] * $row['UnitPrice'];
    $subTotal += $lineTotal;
?>
<div class="line">
    <span><?= htmlspecialchars($row['MenuName']) ?> (<?= $row['Quantity'] ?>x)</span>
    <span><?= number_format($lineTotal, 2) ?></span>
</div>
<?php endwhile; ?>

<hr>
<div class="line"><strong>รวม</strong><strong><?= number_format($subTotal, 2) ?></strong></div>
<div class="line"><span>VAT 7%</span><span><?= number_format($payment['Vat'], 2) ?></span></div>
<hr>
<div class="line"><strong>สุทธิ</strong><strong><?= number_format($payment['TotalPaid'], 2) ?></strong></div>

<p class="center">ขอบคุณที่ใช้บริการ</p>

<div class="button-box print-button">
    <button class="btn" onclick="window.print()">🖨️ พิมพ์ / เซฟ PDF</button>
    <a href="../table_status.php" class="btn btn-back">กลับ</a>
</div>

</body>
</html>
