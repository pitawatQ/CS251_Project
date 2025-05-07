<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "restaurant_db";

// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $username, $password, $dbname);

// ตั้งค่า charset เป็น utf8
$conn->set_charset("utf8");

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    // ซ่อนรายละเอียดใน production จริง
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

