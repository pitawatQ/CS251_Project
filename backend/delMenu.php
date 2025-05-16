<?php
session_start();
include 'db_connect.php';
include 'auth.php';

if (!isset($_SESSION['EmployeeID'])) {
    header("Location: ../login.php");
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("รหัสเมนูไม่ถูกต้อง");
}

$menuID = intval($_GET['id']);

try {
    $conn->begin_transaction();

    $stmt = $conn->prepare("DELETE FROM IngredientUsage WHERE MenuID = ?");
    $stmt->bind_param("i", $menuID);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM OrderDetail WHERE MenuID = ?");
    $stmt->bind_param("i", $menuID);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM Menu WHERE MenuID = ?");
    $stmt->bind_param("i", $menuID);
    $stmt->execute();
    $stmt->close();

    // ลบเมนูออกจากโปรโมชันก่อน
    $stmt = $conn->prepare("DELETE FROM PromotionMenu WHERE MenuID = ?");
    $stmt->bind_param("i", $menuID);
    $stmt->execute();
    $stmt->close();

    $conn->commit();
    header("Location: ../menu_list.php");
    exit();

} catch (mysqli_sql_exception $e) {
    $conn->rollback();
    die("เกิดข้อผิดพลาดในการลบเมนู: " . $e->getMessage());
}
?>
