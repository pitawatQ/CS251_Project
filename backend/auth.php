<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}



$timeout = 8 * 60 * 60;  // 8 ชั่วโมง

if (!isset($_SESSION['EmployeeID'])) {
    header("Location: ../frontend/login.php");
    exit();
}


if (isset($_SESSION['LAST_ACTIVITY'])) {
    if (time() - $_SESSION['LAST_ACTIVITY'] > $timeout) {
        session_unset();
        session_destroy();
        header("Location: ../frontend/login.php?timeout=1");
        exit();
    }
}

$_SESSION['LAST_ACTIVITY'] = time();



function checkRole($allowedRoles = []) {
    if (!in_array($_SESSION['Role'], $allowedRoles)) {
        echo "<h3 style='color:red;'>คุณไม่มีสิทธิ์เข้าถึงหน้านี้</h3>";
        exit();
    }
}
?>
