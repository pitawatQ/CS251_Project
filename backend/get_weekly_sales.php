<?php
include 'db_connect.php';

$sales = array_fill(0, 7, 0); // จันทร์ = 0 ... อาทิตย์ = 6

$sql = "
    SELECT DAYOFWEEK(PaymentDate) AS weekday, SUM(TotalPaid) AS total
    FROM Payment
    WHERE YEARWEEK(PaymentDate, 1) = YEARWEEK(CURDATE(), 1)
    GROUP BY weekday
";

$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    $dayIndex = ($row['weekday'] + 5) % 7; // จัดให้ จันทร์ = 0 ... อาทิตย์ = 6
    $sales[$dayIndex] = (float)$row['total'];
}

header('Content-Type: application/json');
echo json_encode($sales);
