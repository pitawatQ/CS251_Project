<?php
include 'db_connect.php';
$order_id = intval($_GET['order_id'] ?? 0);

$sql = "SELECT Status FROM Orders WHERE OrderID = $order_id";
$result = mysqli_query($conn, $sql);
$status = 0;
if ($row = mysqli_fetch_assoc($result)) {
    $status = (int)$row['Status'];
}

// แปลงรหัส status เป็นข้อความ (แก้ให้ตรงกับระบบคุณ)
switch ($status) {
    case 2: $statusText = 'รอดำเนินการ'; break;
    case 3: $statusText = 'กำลังทำ'; break;
    case 4: $statusText = 'รอเสิร์ฟ'; break;
    case 5: $statusText = 'เสิร์ฟแล้ว'; break;
    case 6: $statusText = 'จ่ายเงินแล้ว'; break;
    case 0: $statusText = 'ยกเลิก'; break;
    default: $statusText = 'ไม่ทราบสถานะ';
}
echo json_encode(['status' => $status, 'statusText' => $statusText]);
