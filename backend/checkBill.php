<?php
include 'db_connect.php';

$tableNo = $_POST['TableNo'] ?? null;

if ($tableNo) {
    header("Location: ../payment_summary.php?table=$tableNo");
    exit();
}

header("Location: ../table_status.php");
exit();
?>
