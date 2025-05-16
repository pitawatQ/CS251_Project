<?php
require_once 'db_connect.php';

// ตรวจสอบว่ามีการเชื่อมต่อฐานข้อมูลสำเร็จหรือไม่ (จาก db_connect.php)
if (!$conn) {
    // ส่ง JSON response กลับไปบอกว่าไม่สำเร็จ
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
    exit();
}

// รับข้อมูลที่ส่งมาจาก JavaScript
$table_no = isset($_POST['table_no']) ? $_POST['table_no'] : null;
$orderDetailsJSON = isset($_POST['orderDetails']) ? $_POST['orderDetails'] : null;

// บันทึก log เพื่อตรวจสอบ
file_put_contents('debug_log.txt', "Table: $table_no, Order: $orderDetailsJSON\n", FILE_APPEND);

// ตรวจสอบว่ามีหมายเลขโต๊ะและข้อมูลคำสั่งซื้อหรือไม่
if ($table_no === null || $orderDetailsJSON === null) {
    echo json_encode(['status' => 'error', 'message' => 'Missing required data (table number or order details)']);
    exit();
}

// แปลง JSON string เป็น PHP array
$data = json_decode($orderDetailsJSON, true);

if (!$data || !isset($data['orderDetails']) || empty($data['orderDetails'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid order data format']);
    exit();
}

$orderDetails = $data['orderDetails'];

// เริ่ม transaction
mysqli_begin_transaction($conn);

try {
    // สร้างคำสั่งใหม่ในตาราง 'Orders'
    $order_date = date("Y-m-d H:i:s");
    
    // สร้าง OrderID ใหม่ (ตามรูปแบบของฐานข้อมูล)
    $sql_max_id = "SELECT MAX(OrderID) as max_id FROM Orders";
    $result_max_id = mysqli_query($conn, $sql_max_id);
    $row_max_id = mysqli_fetch_assoc($result_max_id);
    $next_order_id = $row_max_id['max_id'] ? $row_max_id['max_id'] + 1 : 500001;
    
    // หา EmployeeID (ใช้ ID แรกสำหรับทดสอบ)
    $sql_employee = "SELECT EmployeeID FROM Employee LIMIT 1";
    $result_employee = mysqli_query($conn, $sql_employee);
    $employee_id = 0001; // ค่าเริ่มต้น
    if (mysqli_num_rows($result_employee) > 0) {
        $row_employee = mysqli_fetch_assoc($result_employee);
        $employee_id = $row_employee['EmployeeID'];
    }
    
    // อัปเดตสถานะโต๊ะเป็นไม่ว่าง
    $sql_update_table = "UPDATE TableList SET Status = 1 WHERE TableNo = $table_no";
    mysqli_query($conn, $sql_update_table);
    
    // สร้างคำสั่งซื้อใหม่ (Status = 1 หมายถึงกำลังดำเนินการ)
    $sql_insert_order = "INSERT INTO Orders (OrderID, EmployeeID, TableNo, OrderTime, Status) 
                         VALUES ($next_order_id, $employee_id, $table_no, '$order_date', 1)";
    
    if (mysqli_query($conn, $sql_insert_order)) {
        $item_no = 1;
        
        // วนลูปเพื่อเพิ่มรายละเอียดคำสั่งซื้อ
        foreach ($orderDetails as $item) {
            $menu_name = isset($item['name']) ? $item['name'] : null;
            $quantity = isset($item['quantity']) ? intval($item['quantity']) : 0;
            $note = isset($item['note']) ? mysqli_real_escape_string($conn, $item['note']) : '';
            
            if ($menu_name === null || $quantity <= 0) {
                throw new Exception("Invalid menu item details: $menu_name, qty: $quantity");
            }
            
            // ดึง MenuID และราคาจากชื่อเมนู
            $sql_select_menu = "SELECT MenuID, Price FROM Menu WHERE Name = '" . mysqli_real_escape_string($conn, $menu_name) . "'";
            $result_menu = mysqli_query($conn, $sql_select_menu);
            
            if (mysqli_num_rows($result_menu) > 0) {
                $row_menu = mysqli_fetch_assoc($result_menu);
                $menu_id = $row_menu['MenuID'];
                $unit_price = $row_menu['Price'];
                $total_price = $unit_price * $quantity;
                
                $sql_insert_detail = "INSERT INTO OrderDetail (OrderID, MenuID, MenuQuntity, UnitPrice, ItemNo, TotalPrice, Description) 
                                     VALUES ($next_order_id, $menu_id, $quantity, $unit_price, $item_no, $total_price, '$note')";
                
                if (!mysqli_query($conn, $sql_insert_detail)) {
                    throw new Exception("Error inserting order detail: " . mysqli_error($conn));
                }
                
                $item_no++;
            } else {
                throw new Exception("Menu '$menu_name' not found in database.");
            }
        }
        
        // ถ้าทุกอย่างเรียบร้อย, commit transaction
        mysqli_commit($conn);
        
        // ส่ง JSON response ที่มี status และ orderID กลับไป
        echo json_encode(['status' => 'success', 'orderID' => $next_order_id]);
    } else {
        throw new Exception("Error creating order: " . mysqli_error($conn));
    }
} catch (Exception $e) {
    // ถ้าเกิดข้อผิดพลาด, rollback transaction
    mysqli_rollback($conn);
    
    // ส่ง JSON response กลับไปบอกว่าไม่สำเร็จ พร้อมข้อความผิดพลาด
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    
    // บันทึก log ข้อผิดพลาด
    file_put_contents('error_log.txt', date('Y-m-d H:i:s') . ": " . $e->getMessage() . "\n", FILE_APPEND);
}

// ปิดการเชื่อมต่อฐานข้อมูล
mysqli_close($conn);
?>