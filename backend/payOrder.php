<?php
session_start();
include 'db_connect.php';

if (!isset($_POST['orderID']) || !isset($_POST['tableNo'])) {
    $_SESSION['error'] = "ข้อมูลไม่ครบ";
    header("Location: ../table_status.php");
    exit();
}

$orderID = intval($_POST['orderID']);
$tableNo = intval($_POST['tableNo']);

// อัปเดตสถานะออเดอร์เป็นจ่ายแล้ว (6)
$stmt1 = $conn->prepare("UPDATE Orders SET Status = 6 WHERE OrderID = ?");
$stmt1->bind_param("i", $orderID);
$stmt1->execute();
$stmt1->close();

// อัปเดตสถานะโต๊ะให้ว่าง
$stmt2 = $conn->prepare("UPDATE TableList SET Status = 0 WHERE TableNo = ?");
$stmt2->bind_param("i", $tableNo);
$stmt2->execute();
$stmt2->close();

$_SESSION['success'] = "ชำระเงินสำเร็จ";
header("Location: ../table_status.php");
exit();
