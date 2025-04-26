<?php
session_start();
include 'db_connect.php';

$EmployeeID = $_POST['ID'];
$password = $_POST['password'];

$stmt = $conn->prepare("SELECT * FROM Employee WHERE EmployeeID = ?");
$stmt->bind_param("i", $EmployeeID);  // i = integer
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();

    if ($password == $row['Password']) {
        $_SESSION['EmployeeID'] = $row['EmployeeID'];
        $_SESSION['FName'] = $row['FName'];
        $_SESSION['Role'] = $row['Role'];

        switch ($_SESSION['Role']) {
            case 'staff':
                header("Location: ../frontend/");
                exit();
            case 'admin':
                header("Location: ../frontend/");
                exit();
            case 'manager':
                header("Location: ../frontend/");
                exit();
            default:
                echo "ไม่พบสิทธิ์การใช้งานที่กำหนด";
        }

    } else {
        echo "รหัสผ่านไม่ถูกต้อง";
    }
} else {
    echo "ไม่พบผู้ใช้นี้";
}

$conn->close();
?>
