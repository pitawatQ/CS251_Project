<?php /* payment.php */ ?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>ใบสรุปรายการอาหาร</title>
  <link href="https://fonts.googleapis.com/css2?family=Sarabun&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/payment.css">
</head>
<body>
<div class="top-bar">
  <div class="home-button">
    <img src="pics/Home_icon.png" style="width: 30px; height: 30px; margin-right: 8px;">
    <p style="font-weight: bold;">หน้าหลัก</p>
  </div>
  <div class="profile-box">
    <img src="pics/Profile_girl.png" style="width: 45px; height: 45px; margin-right: 8px;">
    <div class="profile-info">
      <div class="profile-name">ฐิตารีย์</div>
      <div class="profile-id">ID: ST-789</div>
    </div>
  </div>
</div>

<div class="container">
  <h2>📑 ใบสรุปรายการอาหาร</h2>
  <div class="info">
    หมายเลขออเดอร์: <b>ORD20250407-003</b><br>
    โต๊ะที่: <b>3</b><br>
    แคชเชียร์: ฐิตารีย์ ธารานิจ<br>
    วันที่: 10/04/68 เวลา 12:45
  </div>

  <table>
    <tr>
      <th>รายการอาหาร</th>
      <th>จำนวน</th>
      <th>ราคา/หน่วย (บาท)</th>
      <th>ราคารวม (บาท)</th>
    </tr>
    <tr>
      <td>แกงเขียวหวานไก่<br><small>ราดข้าว</small></td>
      <td>1</td>
      <td>80</td>
      <td>80.00</td>
    </tr>
    <tr>
      <td>พะแนงหมู<br><small>ราดข้าว</small></td>
      <td>1</td>
      <td>70</td>
      <td>70.00</td>
    </tr>
    <tr>
      <td>ชุดข้าวแช่พร้อมเครื่องเคียง + น้ำมะพร้าว</td>
      <td>2</td>
      <td>143</td>
      <td>286.00</td>
    </tr>
    <tr>
      <td>สงกรานต์คูลเซ็ต (น้ำแข็งใส + น้ำผลไม้)<br><span class="discount">ลด 10%</span></td>
      <td>2</td>
      <td>59</td>
      <td>118.00</td>
    </tr>
  </table>

  <div class="summary">
    ยอดรวมอาหาร 6 รายการ: 554.00<br>
    ภาษีมูลค่าเพิ่ม 7%: 38.78<br>
    ทิปพนักงาน: 40.00<br>
    <span class="total">ยอดสุทธิที่ต้องชำระ: 632.78</span>
  </div>

  <div class="button-row">
    <div class="pay-button">💵 ชำระเงินสด</div>
    <div class="pay-button">📱 ชำระผ่าน QR Code</div>
    <div class="pay-button">💳 ชำระด้วยบัตรเครดิต</div>
  </div>
</div>

<div class="exit-button">
  <img src="pics/Exit_door.png">
</div>
</body>
</html>
