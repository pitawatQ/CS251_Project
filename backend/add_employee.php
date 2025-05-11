<?php
require 'db_connect.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
    $data = json_decode(file_get_contents("php://input"), true); 

    if (isset($data['EmployeeID']) && isset($data['FName']) && isset($data['LName']) && isset($data['Password']) && isset($data['Role']) && isset($data['StartDate']) && isset($data['Phone']) && isset($data['Email'])) {
        $query = "INSERT INTO employee (EmployeeID, FName, LName, Password, Role, StartDate, Phone, Email) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("isssssss", $data['EmployeeID'], $data['FName'], $data['LName'], $data['Password'], $data['Role'], $data['StartDate'], $data['Phone'], $data['Email']);

        if ($stmt->execute()) { 
            echo json_encode(["status" => "success", "message" => "เพิ่มพนักงานสำเร็จ"]);
        } else {
            echo json_encode(["status" => "error", "message" => "เกิดข้อผิดพลาดในการเพิ่มข้อมูล"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "กรุณาระบุข้อมูลให้ครบถ้วน"]);
    }
}
?>

