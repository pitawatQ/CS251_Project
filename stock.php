<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>จัดการสต็อกวัตถุดิบ</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>📦 จัดการสต็อกวัตถุดิบ</h1>
    <div class="toolbar">
        <input type="text" placeholder="ค้นหาวัตถุดิบ..." />
        <select>
            <option>ทั้งหมด</option>
            <option>ใกล้หมด</option>
            <option>หมดสต็อก</option>
            <option>ปกติ</option>
        </select>
    </div>
    <table>
        <thead>
            <tr>
                <th>ลำดับ</th>
                <th>รหัสวัตถุดิบ</th>
                <th>ชื่อวัตถุดิบ</th>
                <th>คงเหลือ</th>
                <th>วันหมดอายุ</th>
                <th>วันที่นำเข้า</th>
                <th>สถานะ</th>
                <th>ตัวเลือก</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>IG-001</td>
                <td>ผักกาดหอม</td>
                <td>2 กก.</td>
                <td>2025-04-09</td>
                <td>2025-04-01</td>
                <td>ใกล้หมด</td>
                <td><a href="ingredient_detail.php" class="btn-small">รายละเอียด</a></td>
            </tr>
            <tr>
                <td>2</td>
                <td>IG-002</td>
                <td>หมูสามชั้น</td>
                <td>5 กก.</td>
                <td>2025-04-10</td>
                <td>2025-04-03</td>
                <td>ปกติ</td>
                <td><a href="ingredient_detail.php" class="btn-small">รายละเอียด</a></td>
            </tr>
            <tr>
                <td>3</td>
                <td>IG-003</td>
                <td>กุ้งสด</td>
                <td>0 กก.</td>
                <td>2025-04-05</td>
                <td>2025-04-01</td>
                <td>หมดสต็อก</td>
                <td><a href="ingredient_detail.php" class="btn-small">รายละเอียด</a></td>
            </tr>
        </tbody>
    </table>
</body>
</html>
