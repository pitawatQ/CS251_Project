<?php
require 'db_connect.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'GET') { 
    $query = "SELECT EmployeeID, FName, LName, Role, StartDate, Phone, Email FROM employee ORDER BY Role, StartDate";
    $stmt = $conn->prepare($query); 
    $stmt->execute(); 
    
    $result = $stmt->get_result(); 
    $employees = [];

    while ($row = $result->fetch_assoc()) { 
        $employees[] = $row;
    }

    echo json_encode(["status" => "success", "data" => $employees]); 
}
?>
