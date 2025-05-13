<?php
session_start();
include 'db_connect.php'; 

if (!isset($_GET['table'])) {
    header("Location: ../table_status.php");
    exit();
}

$tableNo = intval($_GET['table']);

// ดึง Order ล่าสุดของโต๊ะนี้ที่ยังไม่จ่ายเงิน
$sql = "SELECT OrderID FROM Orders WHERE TableNo = ? AND PaymentStatus = 0 ORDER BY OrderTime DESC LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $tableNo);
$stmt->execute();
$stmt->bind_result($orderID);
$stmt->fetch();
$stmt->close();

if ($orderID) {
    // ถ้าพบออเดอร์ ส่งไป payment.php
    header("Location: ../payment.php?table={$tableNo}&order={$orderID}");
} else {
    // ไม่พบออเดอร์
    $_SESSION['error'] = "ไม่พบออเดอร์ที่ยังไม่จ่ายของโต๊ะนี้";
    header("Location: ../table_status.php");
}
exit();
?>
