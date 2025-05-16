<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $conn->prepare("UPDATE Employee SET FName = ?, LName = ?, Role = ?, Phone = ?, Email = ? WHERE EmployeeID = ?");
    $stmt->bind_param("sssssi", $_POST['FName'], $_POST['LName'], $_POST['Role'], $_POST['Phone'], $_POST['Email'], $_POST['EmployeeID']);
    $stmt->execute();

    header("Location: ../employeeProfile.php?id=" . $_POST['EmployeeID']);
    exit();
}
?>
