<?php
require 'db_connect.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') { 
    $data = json_decode(file_get_contents("php://input"), true); 

    if (isset($data['EmployeeID']) && is_numeric($data['EmployeeID'])) { 
        $query = "DELETE FROM employee WHERE EmployeeID = ?"; 
        $stmt = $conn->prepare($query); 
        $stmt->bind_param("i", $data['EmployeeID']); 

        if ($stmt->execute()) { 
            if ($stmt->affected_rows > 0) { 
                echo json_encode(["status" => "success", "message" => "ลบพนักงานสำเร็จ"]);
            } else {
                echo json_encode(["status" => "error", "message" => "ไม่พบข้อมูลที่ต้องการลบ"]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "เกิดข้อผิดพลาดในการลบข้อมูล"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "กรุณาระบุ EmployeeID ที่ถูกต้อง"]);
    }
}
?>


