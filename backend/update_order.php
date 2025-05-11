<?php
session_start();
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['EmployeeID'])) {
    $orderID = intval($_POST['OrderID']);
    $action = $_POST['action'] ?? '';
    $updatedBy = $_SESSION['EmployeeID'];
    $redirectPage = $_POST['redirect'] ?? 'status_order.php';
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

                // อัปเดตสถานะ + พนักงานที่ทำรายการ
                $stmt = $conn->prepare("UPDATE Orders SET Status = ?, EmployeeID = ? WHERE OrderID = ?");
                $stmt->bind_param("iii", $newStatus, $updatedBy, $orderID);
                $stmt->execute();
            }

        } elseif ($action === 'cancel') {
            // ยกเลิก: อัปเดตเป็นสถานะ 0
            $newStatus = 0;
            $stmt = $conn->prepare("UPDATE Orders SET Status = ?, EmployeeID = ? WHERE OrderID = ?");
            $stmt->bind_param("iii", $newStatus, $updatedBy, $orderID);
            $stmt->execute();

            // ลบคำสั่งนี้ออกจาก Orders และ OrderDetail หากยกเลิกเกิน 5 นาที
            $stmt = $conn->prepare("
                DELETE o, od FROM Orders o
                JOIN OrderDetail od ON o.OrderID = od.OrderID
                WHERE o.OrderID = ? AND o.Status = 0 AND TIMESTAMPDIFF(MINUTE, o.OrderTime, NOW()) >= 5
            ");
            $stmt->bind_param("i", $orderID);
            $stmt->execute();
        }
    }
}

header("Location: ../" . $redirectPage);

exit();
