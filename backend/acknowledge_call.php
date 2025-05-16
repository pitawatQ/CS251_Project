<?php
include 'db_connect.php';

if (isset($_POST['TableID'])) {
    $tableID = intval($_POST['TableID']);
    
    $stmt = $conn->prepare("UPDATE tablelist SET Status = 1 WHERE TableNo = ?");
    $stmt->bind_param("i", $tableID);
    $stmt->execute();
    $stmt->close();
}

header("Location: ../table_status.php");
exit();
