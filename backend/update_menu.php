<?php
session_start();
include 'db_connect.php';
include 'auth.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("วิธีการไม่ถูกต้อง");
}

$menuID      = intval($_POST['MenuID']);
$name        = trim($_POST['Name']);
$price       = floatval($_POST['Price']);
$menuDes     = trim($_POST['MenuDes']);
$status      = isset($_POST['Status']) ? intval($_POST['Status']) : 1;
$categoryID  = $_POST['CategoryID'];
$newCategory = trim($_POST['NewCategory'] ?? '');
$oldPicture  = $_POST['OldPicture'] ?? null;
$picturePath = $oldPicture; // default to old path

// =============== จัดการหมวดหมู่ใหม่ ===============
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

// =============== จัดการรูปภาพ (ถ้ามีอัปโหลดใหม่) ===============
if (isset($_FILES['Picture']) && $_FILES['Picture']['error'] === UPLOAD_ERR_OK) {
    $ext = strtolower(pathinfo($_FILES['Picture']['name'], PATHINFO_EXTENSION));
    $newName = 'menu_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
    $uploadDir = '../img/menu/';
    $uploadPath = $uploadDir . $newName;

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    if (move_uploaded_file($_FILES['Picture']['tmp_name'], $uploadPath)) {
        $picturePath = '/CS251_project/img/menu/' . $newName;

    }
}

// =============== อัปเดตเมนูหลัก ===============
$stmt = $conn->prepare("
    UPDATE Menu
    SET Name=?, Price=?, MenuDes=?, Status=?, CategoryID=?, Picture=?
    WHERE MenuID=?
");
$stmt->bind_param("sdsissi", $name, $price, $menuDes, $status, $categoryID, $picturePath, $menuID);
if (!$stmt->execute()) {
    die("เกิดข้อผิดพลาดในการอัปเดตเมนู: " . $stmt->error);
}

// =============== อัปเดตวัตถุดิบ ===============
$stmt = $conn->prepare("DELETE FROM IngredientUsage WHERE MenuID=?");
$stmt->bind_param("i", $menuID);
$stmt->execute();

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
    if (!$stmt) {
        die("เกิดข้อผิดพลาดในการ prepare INSERT: " . $conn->error);
    }
    $stmt->bind_param("iidd", $menuID, $ingID, $qty, $errorRate);
    $stmt->execute();
}

// =============== เสร็จสิ้น ===============
header("Location: ../menuDetail.php?id=" . $menuID);
exit();
