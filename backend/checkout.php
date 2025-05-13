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

header("Location: ../worktime_log.php");
exit();
?>
