<?php
session_start();
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['EmployeeID'])) {
    $orderID = intval($_POST['OrderID']);
    $action = $_POST['action'] ?? '';
    $updatedBy = $_SESSION['EmployeeID']; // พนักงานที่กดรับ

    // ดึงสถานะเดิม
    $stmt = $conn->prepare("SELECT Status FROM Orders WHERE OrderID = ?");
    $stmt->bind_param("i", $orderID);
    $stmt->execute();
    $result = $stmt->get_result();
    $current = $result->fetch_assoc();

    if ($current) {
        $currentStatus = (int)$current['Status'];
        $newStatus = $currentStatus;

        if ($action === 'next') {
            $next = [
                2 => 3,
                3 => 4,
                4 => 5
            ];
            if (isset($next[$currentStatus])) {
                $newStatus = $next[$currentStatus];
            }
        } elseif ($action === 'cancel') {
            $newStatus = 0;
        }

        // อัปเดตสถานะ + คนที่รับออเดอร์
        $stmt = $conn->prepare("UPDATE Orders SET Status = ?, EmployeeID = ? WHERE OrderID = ?");
        $stmt->bind_param("iii", $newStatus, $updatedBy, $orderID);
        $stmt->execute();
    }
}

header("Location: ../chef_order.php");
exit();
