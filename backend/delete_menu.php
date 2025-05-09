<?php
require_once 'db_connect.php'; // เชื่อมต่อฐานข้อมูล
header('Content-Type: application/json');

// รับข้อมูล JSON
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['MenuID'])) {
    echo json_encode(['message' => 'Missing MenuID']);
    exit;
}

$menuID = $data['MenuID'];

try {
    // ปิด foreign key check ชั่วคราว ถ้ามี constraint (ขึ้นอยู่กับโครงสร้างฐานข้อมูล)
    $conn->query("SET FOREIGN_KEY_CHECKS = 0");

    // ลบข้อมูลจาก Ingredient_Usage ก่อน
    $stmt1 = $conn->prepare("DELETE FROM Ingredient_Usage WHERE MenuID = ?");
    $stmt1->bind_param("i", $menuID);
    $stmt1->execute();

    // ลบข้อมูลจาก Menu
    $stmt2 = $conn->prepare("DELETE FROM Menu WHERE MenuID = ?");
    $stmt2->bind_param("i", $menuID);

    if ($stmt2->execute()) {
        echo json_encode(['message' => 'Menu deleted successfully']);
    } else {
        echo json_encode(['message' => 'Failed to delete menu']);
    }

    $stmt1->close();
    $stmt2->close();
    $conn->query("SET FOREIGN_KEY_CHECKS = 1");
    $conn->close();

} catch (Exception $e) {
    echo json_encode(['message' => 'Error: ' . $e->getMessage()]);
}

