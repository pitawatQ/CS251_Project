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
                header("Location: ../staff_dashboard.php");
                exit();
            case 'admin':
                header("Location: ../admin_dashboard.php");
                exit();
            case 'manager':
                header("Location: ../admin_dashboard.php");
                exit();
            default:
                $_SESSION['error'] = 'role';
                header("Location: ../login.php");
                exit();
         }

    } else {
        $_SESSION['error'] = 'pass';
        header("Location: ../login.php");
        exit();
    }
} else {
    $_SESSION['error'] = 'user';
    header("Location: ../login.php");
    exit();
}

$conn->close();
?>
