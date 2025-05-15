<?php
session_start();
include 'backend/db_connect.php';
include 'backend/auth.php';

if (!isset($_SESSION['EmployeeID'])) {
    header("Location: login.php");
    exit();
}

$error = '';
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $promoName = trim($_POST['PromotionName']);
    $promoPrice = floatval($_POST['PromotionPrice']);
    $promoDes = trim($_POST['PromotionDes']);
    $picturePath = "";

    // ======= รูปภาพแบบใหม่ ป้องกันชื่อซ้ำ =======
    if (isset($_FILES['Picture']) && $_FILES['Picture']['error'] === 0) {
    // Path จริงในเซิร์ฟเวอร์ (relative to backend/)
    $uploadDir ='img/promotion/';  
    $ext = strtolower(pathinfo($_FILES['Picture']['name'], PATHINFO_EXTENSION));
    $filename = 'promotion_' . date('Ymd_His') . '_' . uniqid() . '.' . $ext;
    $targetPath = $uploadDir . $filename;

    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
    if (move_uploaded_file($_FILES['Picture']['tmp_name'], $targetPath)) {
        // Path สำหรับเก็บใน database (web path)
        $picturePath = '/CS251_project/img/promotion/' . $filename;
    } else {
        $error = "อัปโหลดรูปภาพล้มเหลว!";
    }
}


    // เพิ่มโปรโมชั่น
    $stmt = $conn->prepare("INSERT INTO Promotion (PromotionName, PromotionPrice, PromotionDes, Picture) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sdss", $promoName, $promoPrice, $promoDes, $picturePath);
    if ($stmt->execute()) {
        $promotionID = $stmt->insert_id;

        // === เพิ่มเมนูเข้าโปรโมชัน ===
        $menuIDs = $_POST['MenuIDs'] ?? [];
        foreach ($menuIDs as $menuID) {
            $menuID = intval($menuID);
            $stmt2 = $conn->prepare("INSERT INTO PromotionMenu (PromotionID, MenuID) VALUES (?, ?)");
            $stmt2->bind_param("ii", $promotionID, $menuID);
            $stmt2->execute();
            $stmt2->close();
        }

        header("Location: menu_list.php");
        exit();
    } else {
        $error = "เพิ่มโปรโมชั่นไม่สำเร็จ: " . $stmt->error;
    }
}

// ดึงเมนูทั้งหมด
$menuList = $conn->query("SELECT MenuID, Name FROM Menu ORDER BY Name");
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>เพิ่มโปรโมชั่น</title>
    <link rel="stylesheet" href="css/menu_list.css">
</head>
<body>
<!-- ... (code PHP ด้านบนเหมือนเดิม) ... -->
<div class="container">
    <div class="header-bar"><h1>➕ เพิ่มโปรโมชั่น</h1></div>
    <?php if (!empty($error)) echo '<div class="error">'.$error.'</div>'; ?>
    <form action="" method="POST" enctype="multipart/form-data" class="menu-box" style="max-width:480px;margin:auto">
        <div>
            <label>ชื่อโปรโมชั่น</label>
            <input type="text" name="PromotionName" required>
        </div>
        <div>
            <label>ราคาโปร</label>
            <input type="number" name="PromotionPrice" step="0.01" required>
        </div>
        <div>
            <label>รายละเอียด</label>
            <input type="text" name="PromotionDes">
        </div>
        <div>
            <label>รูปภาพ (ถ้ามี)</label>
            <input type="file" name="Picture" accept="image/*">
        </div>
        <div style="margin:1em 0;">
            <label>เลือกเมนูที่อยู่ในโปรนี้</label>
            <input type="text" id="menuSearch" placeholder="ค้นหาเมนู..." style="width:95%;margin-bottom:8px;padding:6px;border:1px solid #ccc;border-radius:6px;">
            <label style="display:block;margin-bottom:6px;">
                <input type="checkbox" id="selectAllMenu"> <strong>เลือก/ยกเลิกทั้งหมด</strong>
            </label>
            <div id="menuListWrap" style="max-height:150px;overflow-y:auto;border:1px solid #ddd;padding:6px;border-radius:8px;">
                <?php while($menu = $menuList->fetch_assoc()): ?>
                    <label class="menu-checkbox" style="display:block">
                        <input type="checkbox" name="MenuIDs[]" value="<?= $menu['MenuID'] ?>">
                        <?= htmlspecialchars($menu['Name']) ?>
                    </label>
                <?php endwhile; ?>
            </div>
        </div>
        <div class="actions" style="margin-top:1em;justify-content:flex-end">
            <button type="submit" class="btn-small">✅ บันทึก</button>
            <a href="menu_list.php" class="btn-small btn-delete">❌ ยกเลิก</a>
        </div>
    </form>
</div>

<script>
document.getElementById('menuSearch').addEventListener('input', function() {
    const search = this.value.toLowerCase();
    document.querySelectorAll('.menu-checkbox').forEach(label => {
        label.style.display = label.textContent.toLowerCase().includes(search) ? 'block' : 'none';
    });
});
document.getElementById('selectAllMenu').addEventListener('change', function() {
    const checked = this.checked;
    document.querySelectorAll('.menu-checkbox input[type="checkbox"]').forEach(box => {
        box.checked = checked;
    });
});
</script>

</body>
</html>
