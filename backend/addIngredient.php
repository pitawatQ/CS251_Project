<?php
include 'db_connect.php';
session_start();

$ingredientName = $_POST['ingredientName'] ?? '';
$quantityRaw = $_POST['quantity'] ?? '';
$expiryDate = $_POST['expiryDate'] ?? '';
$importDate = date("Y-m-d");
$lastUpdate = $importDate;

$quantity = floatval(preg_replace('/[^0-9.]/', '', $quantityRaw)); // ดึงเฉพาะตัวเลข
$unit = 'กิโลกรัม'; // สมมติว่าใช้หน่วยเดียว

// สร้าง IngredientID ใหม่ก่อนใช้
$result = $conn->query("SELECT MAX(IngredientID) AS maxID FROM stock");
$row = $result->fetch_assoc();
$newIngredientID = ($row['maxID'] ?? 300000) + 1;

// เช็คว่ามาจาก supimport หรือ empimport
if (isset($_POST['supplierName']) && isset($_POST['phone']) && isset($_POST['email'])) {
    // มาจาก supimport.php
    $supplierName = $_POST['supplierName'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];

    $stmt = $conn->prepare("SELECT SupplierID FROM supplier WHERE Sname = ? AND Phone = ? AND Email = ?");
    $stmt->bind_param("sss", $supplierName, $phone, $email);
    $stmt->execute();
    $stmt->bind_result($supplierID);

    if (!$stmt->fetch()) {
        // ยังไม่มี supplier นี้ → สร้างใหม่
        $stmt->close();
        $stmt = $conn->prepare("INSERT INTO supplier (Sname, Phone, Email) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $supplierName, $phone, $email);
        $stmt->execute();
        $supplierID = $stmt->insert_id;
    }
    $stmt->close();
} else {
    // มาจาก empimport.php → ไม่ระบุ supplier
    $supplierID = null;
}

// เตรียม INSERT โดยดูว่ามี supplier หรือไม่
if (is_null($supplierID)) {
    $stmt = $conn->prepare("INSERT INTO stock 
        (IngredientID, SupplierID, IngredientName, Quantity, Unit, ImportDate, ExpirationDate, LastUpdate)
        VALUES (?, NULL, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isdssss", $newIngredientID, $ingredientName, $quantity, $unit, $importDate, $expiryDate, $lastUpdate);
} else {
    $stmt = $conn->prepare("INSERT INTO stock 
        (IngredientID, SupplierID, IngredientName, Quantity, Unit, ImportDate, ExpirationDate, LastUpdate)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iisdssss", $newIngredientID, $supplierID, $ingredientName, $quantity, $unit, $importDate, $expiryDate, $lastUpdate);
}

$stmt->execute();
$stmt->close();

// กลับไปหน้าหลัก
header("Location: ../stock.php");
exit();
?>
