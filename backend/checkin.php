<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['EmployeeID'])) {
    header("Location: ../login.php");
    exit();
}

$employeeID = $_SESSION['EmployeeID'];

// บันทึกเวลาเข้างาน ถ้ายังไม่มีบันทึกในวันนี้
$stmt = $conn->prepare("SELECT * FROM Attendance WHERE EmployeeID = ? AND WorkDate = CURDATE()");
$stmt->bind_param("i", $employeeID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // ถ้ายังไม่มี record → ให้ insert
    $insert = $conn->prepare("INSERT INTO Attendance (EmployeeID, WorkDate, ClockInTime) VALUES (?, CURDATE(), CURTIME())");
    $insert->bind_param("i", $employeeID);
    $insert->execute();
}

$redirect = isset($_POST['redirect']) && $_POST['redirect'] ? $_POST['redirect'] : '../worktime_log.php';
if (strpos($redirect, '/') !== 0 && strpos($redirect, 'http') !== 0) {
    $redirect = '../' . $redirect;
}
header("Location: $redirect");
exit();

?>
