<?php
include 'db_connect.php';

$tableNo = $_POST['TableNo'] ?? null;

if ($tableNo) {
    header("Location: ../payment_summary.php?table=$tableNo");
    exit();
}
header("Location: receipt.php?ref=$invoiceNo");
exit();

?>
