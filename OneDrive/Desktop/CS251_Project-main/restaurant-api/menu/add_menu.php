<?php
require_once 'db_connect.php'; // เชื่อมต่อฐานข้อมูล

header('Content-Type: application/json');

// รับข้อมูล JSON
$data = json_decode(file_get_contents("php://input"), true);

// ตรวจสอบข้อมูลที่จำเป็น
if (!isset($data['CategoryID'], $data['Name'], $data['Price'], $data['Status'], $data['Picture'])) {
    echo json_encode(['message' => 'Missing required fields']);
    exit;
}

$categoryID = $data['CategoryID'];
$name = $data['Name'];
$price = $data['Price'];
$status = $data['Status'];
$picture = $data['Picture'];

try {
    // ใช้ prepared statement ป้องกัน SQL Injection
    $stmt = $conn->prepare("INSERT INTO Menu (CategoryID, Name, Price, Status, Picture) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $categoryID, $name, $price, $status, $picture);

    if ($stmt->execute()) {
        echo json_encode(['message' => 'Menu added successfully']);
    } else {
        echo json_encode(['message' => 'Failed to add menu']);
    }

    $stmt->close();
    $conn->close();
} catch (Exception $e) {
    echo json_encode(['message' => 'Error: ' . $e->getMessage()]);
}

