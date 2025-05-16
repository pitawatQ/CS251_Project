<?php
session_start();
include 'db_connect.php';
include 'auth.php';

if (!isset($_SESSION['EmployeeID'])) {
    header("Location: ../login.php");
    exit();
}

$fname = $_POST['FName'] ?? '';
$lname = $_POST['LName'] ?? '';
$password = $_POST['Password'] ?? '';
$role = $_POST['Role'] ?? '';
$startDate = $_POST['StartDate'] ?? date('Y-m-d');
$phone = $_POST['Phone'] ?? '';
$email = $_POST['Email'] ?? '';

// ตรวจสอบค่าที่รับมา
if (!$fname || !$lname || !$password || !$role || !$phone || !$email) {
    echo "กรุณากรอกข้อมูลให้ครบถ้วน";
    exit();
}

// เพิ่มข้อมูล (ไม่ใส่ EmployeeID เพราะ AUTO_INCREMENT แล้ว)
$stmt = $conn->prepare("
    INSERT INTO Employee (FName, LName, Password, Role, StartDate, Phone, Email)
    VALUES (?, ?, ?, ?, ?, ?, ?)
");
$stmt->bind_param("sssssss", $fname, $lname, $password, $role, $startDate, $phone, $email);

if ($stmt->execute()) {
    header("Location: ../employees.php");
    exit();
} else {
    echo "เกิดข้อผิดพลาด: " . $stmt->error;
}
?>
