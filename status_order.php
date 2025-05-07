<?php /* status_order.php */ ?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>รายการอาหารที่พร้อมเสิร์ฟ</title>
  <link href="https://fonts.googleapis.com/css2?family=Sarabun&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/status_order.css">
</head>
<body>
  <div class="top-bar">
  <div class="top-bar">
  <div class="home-button">
    <img src="pics/Home_icon.png" style="width: 30px; height: 30px; margin-right: 8px;">
    <p style="font-weight: bold;">หน้าหลัก</p>
  </div>
    <div class="profile-box">
      <img src="../pics/Profile_girl.png" alt="Profile">
      <div class="profile-info">
        <div class="profile-name">เกษร</div>
        <div class="profile-id">ID: ST-943</div>
      </div>
    </div>
  </div>

  <div class="container">
    <h2>🍽️ รายการอาหารที่พร้อมเสิร์ฟ</h2>

    <div class="alert-box">⚠️ มีออเดอร์พร้อมเสิร์ฟที่ยังไม่ได้เสิร์ฟ โต๊ะ 1</div>

    <div class="info">
      <select id="filter">
        <option>ทั้งหมด</option>
        <option>เสร็จ ยังไม่เสิร์ฟ</option>
        <option>เสิร์ฟแล้ว</option>
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
          <th>ใช้เวลาเตรียม</th>
          <th>เวลารวมจนเสิร์ฟ</th>
          <th>ดำเนินการ</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>ORD20250401-013</td>
          <td>1</td>
          <td>แกงเขียวหวานไก่</td>
          <td><span class="badge served">เสิร์ฟแล้ว</span></td>
          <td>15 นาที</td>
          <td>20 นาที</td>
          <td><button class="action served">เสิร์ฟแล้ว</button></td>
        </tr>
        <tr>
          <td>ORD20250401-014</td>
          <td>5</td>
          <td>
            ส้มตำไทย<br><span class="sub-item">เผ็ดน้อย</span><br>
            ลาบหมู<br><span class="sub-item">ไม่ใส่ข้าวคั่ว</span>
          </td>
          <td><span class="badge preparing">เสร็จ ยังไม่เสิร์ฟ</span></td>
          <td>15 นาที</td>
          <td>30 นาที</td>
          <td>
            <button class="action served">เสิร์ฟแล้ว</button>
            <button class="action cancel">ยกเลิก</button>
          </td>
        </tr>
        <tr>
          <td>ORD20250401-015</td>
          <td>8</td>
          <td>
            ข้าวผัดหมู<br><span class="sub-item">ไม่ใส่ต้นหอม</span>
          </td>
          <td><span class="badge cancel">ยกเลิก</span></td>
          <td>20 นาที</td>
          <td>-</td>
          <td></td>
        </tr>
      </tbody>
    </table>
  </div>

  <div class="exit-button">
  <img src="pics/Exit_door.png">
</div>
</body>
</html>
