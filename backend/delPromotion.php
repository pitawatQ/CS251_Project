<?php
session_start();
include 'db_connect.php';
include 'auth.php';

if (!isset($_SESSION['EmployeeID'])) {
    header("Location: ../login.php");
    exit();
}
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("รหัสโปรโมชั่นไม่ถูกต้อง");
}

$promoID = intval($_GET['id']);

try {
    $conn->begin_transaction();

    // 1. ลบความสัมพันธ์ PromotionMenu ก่อน (FK)
    $stmt = $conn->prepare("DELETE FROM PromotionMenu WHERE PromotionID = ?");
    $stmt->bind_param("i", $promoID);
    $stmt->execute();
    $stmt->close();

    // 2. ลบ Promotion จริง
    $stmt = $conn->prepare("DELETE FROM Promotion WHERE PromotionID = ?");
    $stmt->bind_param("i", $promoID);
    $stmt->execute();
    $stmt->close();

    $conn->commit();
    header("Location: ../menu_list.php"); // กลับไปหน้าเมนู/โปรโมชัน
    exit();

} catch (mysqli_sql_exception $e) {
    $conn->rollback();
    die("เกิดข้อผิดพลาดในการลบโปรโมชั่น: " . $e->getMessage());
}
?>
