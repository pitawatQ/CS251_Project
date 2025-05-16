<?php
session_start();
include 'db_connect.php';
include 'auth.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("วิธีการไม่ถูกต้อง");
}

$name        = trim($_POST['Name']);
$price       = floatval($_POST['Price']);
$menuDes     = trim($_POST['MenuDes']);
$status      = isset($_POST['Status']) ? intval($_POST['Status']) : 1;
$categoryID  = $_POST['CategoryID'];
$newCategory = trim($_POST['NewCategory'] ?? '');
$picturePath = '';
// ดึง MenuID ที่มากที่สุด แล้วบวก 1
$res = $conn->query("SELECT MAX(MenuID) AS MaxID FROM Menu");
$row = $res->fetch_assoc();
$nextMenuID = $row['MaxID'] ? intval($row['MaxID']) + 1 : 1;


// ==================== จัดการหมวดหมู่ใหม่ ====================
if ($categoryID === '__new') {
    if ($newCategory === '') {
        die("กรุณากรอกชื่อหมวดหมู่ใหม่");
    }

    $stmt = $conn->prepare("SELECT CategoryID FROM Category WHERE CName = ?");
    $stmt->bind_param("s", $newCategory);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();

    if ($res) {
        $categoryID = $res['CategoryID'];
    } else {
        $stmt = $conn->prepare("INSERT INTO Category (CName) VALUES (?)");
        $stmt->bind_param("s", $newCategory);
        $stmt->execute();
        $categoryID = $stmt->insert_id;
    }
} else {
    $categoryID = intval($categoryID);
}

// ==================== อัปโหลดรูปภาพ ====================
if (isset($_FILES['Picture']) && $_FILES['Picture']['error'] === 0) {
    $uploadDir = '../img/menu/';
    $filename = uniqid('menu_') . "_" . basename($_FILES['Picture']['name']);
    $targetPath = $uploadDir . $filename;

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    if (move_uploaded_file($_FILES['Picture']['tmp_name'], $targetPath)) {
        $picturePath = '/CS251_project/img/menu/' . $filename;
    } else {
        die("อัปโหลดรูปภาพล้มเหลว");
    }
}

// ==================== เพิ่มเมนูใหม่ ====================
$stmt = $conn->prepare("INSERT INTO Menu (Name, Price, Status, MenuDes, CategoryID, Picture)
VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sdssis", $name, $price, $status, $menuDes, $categoryID, $picturePath);



if (!$stmt->execute()) {
    die("เกิดข้อผิดพลาดในการเพิ่มเมนู: " . $stmt->error);
}
$menuID = $stmt->insert_id;

// ==================== เพิ่มวัตถุดิบ ====================
$ingredientIDs = $_POST['IngredientIDs'] ?? [];
$quantities    = $_POST['QuantityUsed'] ?? [];
$errorRates    = $_POST['ErrorRateUsed'] ?? [];

if (count($ingredientIDs) !== count($quantities) || count($quantities) !== count($errorRates)) {
    die("ข้อมูลวัตถุดิบไม่ครบถ้วน");
}

for ($i = 0; $i < count($ingredientIDs); $i++) {
    $ingID      = intval($ingredientIDs[$i]);
    $qty        = floatval($quantities[$i]);
    $errorInput = floatval($errorRates[$i]);
    $errorRate  = $errorInput / 100.0;

    if ($qty <= 0) continue;

    $stmt = $conn->prepare("
        INSERT INTO IngredientUsage (MenuID, IngredientID, QuantityUsed, ErrorRateUsed)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->bind_param("iidd", $menuID, $ingID, $qty, $errorRate);
    $stmt->execute();
}

// ==================== เสร็จสิ้น ====================
header("Location: ../menuDetail.php?id=" . $menuID);
exit();
