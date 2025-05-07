<?php
require_once 'db_connect.php';
header('Content-Type: application/json');

// รับค่าจาก query string
$categoryID = isset($_GET['categoryID']) ? $_GET['categoryID'] : null;
$status = isset($_GET['status']) ? $_GET['status'] : null;
$search = isset($_GET['search']) ? '%' . $_GET['search'] . '%' : null;

$sql = "SELECT * FROM Menu WHERE 1=1";
$params = [];
$types = "";

// เพิ่มเงื่อนไขตามที่ผู้ใช้ส่งเข้ามา
if ($categoryID !== null) {
    $sql .= " AND CategoryID = ?";
    $params[] = $categoryID;
    $types .= "i";
}

if ($status !== null) {
    $sql .= " AND Status = ?";
    $params[] = $status;
    $types .= "s";
}

if ($search !== null) {
    $sql .= " AND Name LIKE ?";
    $params[] = $search;
    $types .= "s";
}

// เตรียม statement และ bind ค่า
$stmt = $conn->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

$menus = [];

while ($row = $result->fetch_assoc()) {
    $menus[] = $row;
}

echo json_encode(['menus' => $menus]);

$stmt->close();
$conn->close();
