<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>จัดการพนักงาน</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>👤 จัดการพนักงาน</h1>
    <div class="toolbar">
        <a href="#" class="btn">เพิ่มพนักงาน</a>
        <input type="text" placeholder="ค้นหาชื่อ/รหัสพนักงาน..." />
        <select>
            <option>ทั้งหมด</option>
            <option>ผู้จัดการร้าน</option>
            <option>เชฟ</option>
            <option>แคชเชียร์</option>
            <option>เสิร์ฟ</option>
            <option>แอดมินระบบ</option>
            <option>ดูแลโต๊ะ</option>
        </select>
    </div>
    <table>
        <thead>
            <tr>
                <th>ชื่อ-นามสกุล</th>
                <th>รหัสพนักงาน</th>
                <th>ตำแหน่ง</th>
                <th>สถานะ</th>
                <th>เริ่มงานเมื่อ</th>
                <th>อายุงาน</th>
                <th>ตัวเลือก</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>รวิศ เศวตปาล</td>
                <td>AD-213</td>
                <td>แอดมินระบบ</td>
                <td>ทำงานอยู่</td>
                <td>2020-01-10</td>
                <td>62 เดือน</td>
                <td>
                    <a href="employee_profile.php" class="btn-small">ดูโปรไฟล์</a>
                    <a href="#" class="btn-small">แก้ไข</a>
                    <a href="#" class="btn-small btn-delete">ลบ</a>
                </td>
            </tr>
            <tr>
                <td>ไตรภพ ศิระเมฆา</td>
                <td>MG-127</td>
                <td>ผู้จัดการร้าน</td>
                <td>ไม่ได้ทำขณะนี้</td>
                <td>2020-05-18</td>
                <td>58 เดือน</td>
                <td>
                    <a href="employee_profile.php" class="btn-small">ดูโปรไฟล์</a>
                    <a href="#" class="btn-small">แก้ไข</a>
                    <a href="#" class="btn-small btn-delete">ลบ</a>
                </td>
            </tr>
            <tr>
                <td>ภัทร ภิรมย์เวช</td>
                <td>ST-119</td>
                <td>เชฟ</td>
                <td>ทำงานอยู่</td>
                <td>2021-11-02</td>
                <td>41 เดือน</td>
                <td>
                    <a href="employee_profile.php" class="btn-small">ดูโปรไฟล์</a>
                    <a href="#" class="btn-small">แก้ไข</a>
                    <a href="#" class="btn-small btn-delete">ลบ</a>
                </td>
            </tr>
            <tr>
                <td>ปราชญ์ นาคินทรา</td>
                <td>ST-143</td>
                <td>ดูแลโต๊ะ</td>
                <td>ทำงานอยู่</td>
                <td>2023-03-30</td>
                <td>24 เดือน</td>
                <td>
                    <a href="employee_profile.php" class="btn-small">ดูโปรไฟล์</a>
                    <a href="#" class="btn-small">แก้ไข</a>
                    <a href="#" class="btn-small btn-delete">ลบ</a>
                </td>
            </tr>
        </tbody>
    </table>
</body>
</html>
