<?php
session_start();
include 'db_connect.php';
include 'auth.php';

// ตรวจสอบว่าเข้าสู่ระบบหรือยัง
if (!isset($_SESSION['EmployeeID'])) {
    header("Location: ../login.php");
    exit();
}

// ตรวจสอบว่ามีการส่ง ID มาหรือไม่
if (!isset($_GET['id'])) {
    echo "ไม่พบรหัสพนักงานที่ต้องการลบ";
    exit();
}

$targetID = intval($_GET['id']);

// ป้องกันไม่ให้ลบตัวเอง
if ($targetID == $_SESSION['EmployeeID']) {
    echo "ไม่สามารถลบบัญชีของตัวเองได้";
    exit();
}

// ตรวจสอบว่าพนักงานนั้นมีอยู่จริงหรือไม่
$check = $conn->prepare("SELECT EmployeeID FROM Employee WHERE EmployeeID = ?");
$check->bind_param("i", $targetID);
$check->execute();
$check->store_result();

if ($check->num_rows === 0) {
    echo "ไม่พบพนักงานที่ต้องการลบ";
    exit();
}

// ลบพนักงาน
$stmt = $conn->prepare("DELETE FROM Employee WHERE EmployeeID = ?");
$stmt->bind_param("i", $targetID);

if ($stmt->execute()) {
    header("Location: ../employees.php");
    exit();
} else {
    echo "เกิดข้อผิดพลาดในการลบ: " . $stmt->error;
}
?>
