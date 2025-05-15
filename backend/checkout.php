<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['EmployeeID'])) {
    header("Location: ../login.php");
    exit();
}

$employeeID = $_SESSION['EmployeeID'];

// บันทึกเวลาออก
$stmt = $conn->prepare("UPDATE Attendance 
                        SET ClockOutTime = CURTIME() 
                        WHERE EmployeeID = ? AND WorkDate = CURDATE() AND ClockOutTime IS NULL");
$stmt->bind_param("i", $employeeID);
$stmt->execute();

$redirect = isset($_POST['redirect']) && $_POST['redirect'] ? $_POST['redirect'] : '../worktime_log.php';
if (strpos($redirect, '/') !== 0 && strpos($redirect, 'http') !== 0) {
    $redirect = '../' . $redirect;
}
header("Location: $redirect");
exit();

?>
