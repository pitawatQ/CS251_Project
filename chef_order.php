<?php /* chef_order.php */ ?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>คำสั่งอาหาร</title>
  <link href="https://fonts.googleapis.com/css2?family=Sarabun&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/chef_order.css">
</head>
<body>
  <div class="top-bar">
  <div class="home-button">
    <img src="pics/Home_icon.png" style="width: 30px; height: 30px; margin-right: 8px;">
    <p style="font-weight: bold;">หน้าหลัก</p>
  </div>
    <div class="profile-box">
      <img src="../pics/male.png" alt="Profile">
      <div class="profile-info">
        <div class="profile-name">ภัทร</div>
        <div class="profile-id">ID: ST-119</div>
      </div>
    </div>
  </div>

  <div class="container">
    <h2>🍽️ คำสั่งอาหาร</h2>
    <div class="info">
  <select id="filter">
    <option>ทั้งหมด</option>
    <option>เสร็จ</option>
    <option>ยกเลิก</option>
  </select>
</div>

    <table>
      <thead>
        <tr>
          <th>หมายเลขออเดอร์</th>
          <th>โต๊ะ</th>
          <th>รายการ</th>
          <th>สถานะ</th>
          <th>เวลา</th>
          <th>เปลี่ยนสถานะ</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>ORD20250401-013</td>
          <td>3</td>
          <td>
            แกงเขียวหวานไก่<br><span class="sub-item">ราดข้าว</span><br>
            พะแนงหมู<br><span class="sub-item">ราดข้าว</span>
          </td>
          <td><span class="status done">เสร็จ</span></td>
          <td>12:00</td>
          <td>
            <select>
              <option>เสร็จ</option>
              <option>ยกเลิก</option>
            </select>
          </td>
        </tr>
        <tr>
          <td>ORD20250401-014</td>
          <td>5</td>
          <td>
            ส้มตำไทย<br><span class="sub-item">เผ็ดน้อย</span><br>
            ลาบหมู<br><span class="sub-item">ไม่ใส่ข้าวคั่ว</span>
          </td>
          <td><span class="status done">เสร็จ</span></td>
          <td>12:15</td>
          <td>
            <select>
              <option>เสร็จ</option>
              <option>ยกเลิก</option>
            </select>
          </td>
        </tr>
        <tr>
          <td>ORD20250401-015</td>
          <td>8</td>
          <td>
            ข้าวผัดหมู<br><span class="sub-item">ไม่ใส่ต้นหอม</span>
          </td>
          <td><span class="status cancel">ยกเลิก</span></td>
          <td>12:25</td>
          <td>
            <select>
              <option>ยกเลิก</option>
              <option>เสร็จ</option>
            </select>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

  <div class="exit-button">
  <img src="pics/Exit_door.png">
</div>
</body>
</html>
