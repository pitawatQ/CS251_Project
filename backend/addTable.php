<?php
include 'db_connect.php';

// ดึง TableNo ที่มากที่สุด แล้ว +1
$result = $conn->query("SELECT MAX(TableNo) AS maxNo FROM tablelist");
$row = $result->fetch_assoc();
$nextTableNo = ($row['maxNo'] ?? 0) + 1;

// เตรียมคำสั่งเพิ่มโต๊ะใหม่
$stmt = $conn->prepare("INSERT INTO tablelist (TableNo, Status) VALUES (?, 0)");
$stmt->bind_param("i", $nextTableNo);
$stmt->execute();

header("Location: ../table_status.php");
exit();
?>
