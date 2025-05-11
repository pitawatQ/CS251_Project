<?php
require 'db_connect.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'PUT') { 
    $data = json_decode(file_get_contents("php://input"), true); 

    if (isset($data['EmployeeID']) && is_numeric($data['EmployeeID'])) { 
        $query = "UPDATE employee SET FName = ?, LName = ?, Password = ?, Role = ?, StartDate = ?, Phone = ?, Email = ? WHERE EmployeeID = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssssi", $data['FName'], $data['LName'], $data['Password'], $data['Role'], $data['StartDate'], $data['Phone'], $data['Email'], $data['EmployeeID']);

        if ($stmt->execute()) { 
            if ($stmt->affected_rows > 0) { 
                echo json_encode(["status" => "success", "message" => "แก้ไขข้อมูลพนักงานสำเร็จ"]);
            } else {
                echo json_encode(["status" => "error", "message" => "ไม่พบข้อมูลที่ต้องการแก้ไข"]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "เกิดข้อผิดพลาดในการอัปเดตข้อมูล"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "กรุณาระบุ EmployeeID ที่ถูกต้อง"]);
    }
}
?>

