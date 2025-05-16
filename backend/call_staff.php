<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['table_no'])) {
    $tableNo = $_POST['table_no'];
    $status = 2; // สถานะ "เรียกพนักงาน"

    $sql = "UPDATE TableList SET Status = ? WHERE TableNo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $status, $tableNo);

    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error: ' . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo 'invalid request';
}
?>