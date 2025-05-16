<?php
require_once 'db_connect.php';

// Debug (แนะนำ: เปิดเฉพาะตอนเทส)
error_reporting(E_ALL); ini_set('display_errors', 1);

$table_no = $_POST['table_no'] ?? null;
$orderDetailsJSON = $_POST['orderDetails'] ?? null;

// *** DEBUG ***
if (!$table_no || !$orderDetailsJSON) {
    echo json_encode(['status'=>'error','message'=>'ข้อมูลไม่ครบ']);
    exit();
}

$data = json_decode($orderDetailsJSON, true);
// รองรับทั้ง array ตรงๆ กับ {"orderDetails": array}
if (isset($data[0]) && is_array($data[0])) {
    $orderDetails = $data;
} else {
    $orderDetails = $data['orderDetails'] ?? [];
}

if (empty($orderDetails)) {
    echo json_encode(['status'=>'error','message'=>'ไม่มีรายการอาหาร']);
    exit();
}

$order_date = date("Y-m-d H:i:s");
$sql_max_id = "SELECT MAX(OrderID) as max_id FROM Orders";
$result_max_id = mysqli_query($conn, $sql_max_id);
$row_max_id = mysqli_fetch_assoc($result_max_id);
$next_order_id = $row_max_id['max_id'] ? $row_max_id['max_id'] + 1 : 500001;

$sql_employee = "SELECT EmployeeID FROM Employee LIMIT 1";
$result_employee = mysqli_query($conn, $sql_employee);
$row_employee = mysqli_fetch_assoc($result_employee);
$employee_id = $row_employee['EmployeeID'] ?? 1;

// อัพเดตสถานะโต๊ะ
$sql_update_table = "UPDATE TableList SET Status=1 WHERE TableNo = $table_no";
mysqli_query($conn, $sql_update_table);

// Insert Orders
$sql_insert_order = "INSERT INTO Orders (OrderID, EmployeeID, TableNo, OrderTime, Status) 
VALUES ($next_order_id, $employee_id, $table_no, '$order_date', 2)";
mysqli_query($conn, $sql_insert_order);

$item_no = 1;
foreach ($orderDetails as $item) {
    $type = $item['type'] ?? 'menu';
    $quantity = intval($item['quantity']);
    $note = mysqli_real_escape_string($conn, $item['note']);

    if ($type === 'promo') {
        $promo_id = intval($item['id']);
        $sql_promo_menu = "SELECT m.MenuID FROM PromotionMenu pm JOIN Menu m ON pm.MenuID = m.MenuID WHERE pm.PromotionID = $promo_id";
        $result_promo_menu = mysqli_query($conn, $sql_promo_menu);
        $menus_in_promo = [];
        while ($row = mysqli_fetch_assoc($result_promo_menu)) {
            $menus_in_promo[] = $row['MenuID'];
        }

        $sql_promo_price = "SELECT PromotionPrice FROM Promotion WHERE PromotionID = $promo_id";
        $result_promo_price = mysqli_query($conn, $sql_promo_price);
        $row_promo_price = mysqli_fetch_assoc($result_promo_price);
        $promo_price = floatval($row_promo_price['PromotionPrice']);

        $menu_count = count($menus_in_promo);
        if ($menu_count > 0) {
            $price_per_menu = $promo_price / $menu_count;
            foreach ($menus_in_promo as $menu_id) {
                $total_price = $price_per_menu * $quantity;
                $sql_insert_detail = "INSERT INTO OrderDetail (OrderID, MenuID, MenuQuantity, UnitPrice, ItemNo, TotalPrice, Description)
                    VALUES ($next_order_id, $menu_id, $quantity, $price_per_menu, $item_no, $total_price, '$note')";
                mysqli_query($conn, $sql_insert_detail);
                $item_no++;
            }
        }
    } else {
        $menu_name = $item['name'];
        $sql_menu = "SELECT MenuID, Price FROM Menu WHERE Name='".mysqli_real_escape_string($conn, $menu_name)."'";
        $result_menu = mysqli_query($conn, $sql_menu);
        $row_menu = mysqli_fetch_assoc($result_menu);
        $menu_id = $row_menu['MenuID'];
        $unit_price = $row_menu['Price'];
        $total_price = $unit_price * $quantity;
        $sql_insert_detail = "INSERT INTO OrderDetail (OrderID, MenuID, MenuQuantity, UnitPrice, ItemNo, TotalPrice, Description)
            VALUES ($next_order_id, $menu_id, $quantity, $unit_price, $item_no, $total_price, '$note')";
        mysqli_query($conn, $sql_insert_detail);
        $item_no++;
    }
}

echo json_encode(['status'=>'success','orderID'=>$next_order_id]);
mysqli_close($conn);
?>
