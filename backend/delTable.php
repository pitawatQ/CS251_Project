<?php
include 'db_connect.php';

$tableNo = $_POST['TableID'] ?? null;

if ($tableNo) {
    $check = $conn->prepare("SELECT COUNT(*) FROM Orders WHERE TableNo = ?");
    $check->bind_param("i", $tableNo);
    $check->execute();
    $check->bind_result($orderCount);
    $check->fetch();
    $check->close();

    if ($orderCount == 0) {
        $stmt = $conn->prepare("DELETE FROM tablelist WHERE TableNo = ?");
        $stmt->bind_param("i", $tableNo);
        $stmt->execute();
    }
}

header("Location: ../table_status.php");
exit();
