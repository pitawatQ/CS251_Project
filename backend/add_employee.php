<?php
require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!empty($data['FName']) && !empty($data['LName']) && !empty($data['Password']) && !empty($data['Role']) && !empty($data['StartDate']) && !empty($data['Phone']) && !empty($data['Email'])) {
        $query = "INSERT INTO employee (FName, LName, Password, Role, StartDate, Phone, Email) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssss", $data['FName'], $data['LName'], $data['Password'], $data['Role'], $data['StartDate'], $data['Phone'], $data['Email']);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "เพิ่มพนักงานสำเร็จ"]);
        } else {
            echo json_encode(["status" => "error", "message" => "เกิดข้อผิดพลาด"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "ข้อมูลไม่ครบ"]);
    }
}
?>
