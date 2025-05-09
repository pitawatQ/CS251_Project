<?php
require_once 'db_connect.php'; // เชื่อมต่อฐานข้อมูล
header('Content-Type: application/json');

// รับข้อมูล JSON ที่ส่งเข้ามา
$data = json_decode(file_get_contents("php://input"), true);

// ตรวจสอบว่าข้อมูลครบหรือไม่
if (!isset($data['MenuID'], $data['CategoryID'], $data['Name'], $data['Price'], $data['Status'], $data['Picture'])) {
    echo json_encode(['message' => 'Missing required fields']);
    exit;
}

$menuID = $data['MenuID'];
$categoryID = $data['CategoryID'];
$name = $data['Name'];
$price = $data['Price'];
$status = $data['Status'];
$picture = $data['Picture'];

try {
    // สร้าง prepared statement เพื่ออัปเดตข้อมูลเมนู
    $stmt = $conn->prepare("
        UPDATE Menu 
        SET CategoryID = ?, Name = ?, Price = ?, Status = ?, Picture = ?
        WHERE MenuID = ?
    ");
    $stmt->bind_param("issssi", $categoryID, $name, $price, $status, $picture, $menuID);

    if ($stmt->execute()) {
        echo json_encode(['message' => 'Menu updated successfully']);
    } else {
        echo json_encode(['message' => 'Failed to update menu']);
    }

    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    echo json_encode(['message' => 'Error: ' . $e->getMessage()]);
}
