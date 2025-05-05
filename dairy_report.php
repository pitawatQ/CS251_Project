<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Daily Report</title>
    <link rel="stylesheet" type="text/css" href="css/dairy_report.css">
</head>
<body>

<div class="container">
    <h1 class="title">📊 สรุปภาพรวมประจำวัน</h1>
    <div class="report-grid">
        <div class="report-box">
            <h2>🍴 สรุปการสั่งอาหาร</h2>
            <p>เมนูปกติ: 48 รายการ (฿6,240)</p>
            <p>โปรโมชั่น: 17 รายการ (฿1,865)</p>
            <p>เครื่องดื่ม: 25 รายการ (฿1,120)</p>
            <p>ของหวาน: 11 รายการ (฿495)</p>
            <p><strong>รวมทั้งหมด: 101 รายการ (฿9,720)</strong></p>
        </div>
        <div class="report-box">
            <h2>💰 รายได้สุทธิ</h2>
            <p>ก่อน VAT: ฿9,720</p>
            <p>ส่วนลดโปรโมชั่น: -฿850</p>
            <p>ภาษีมูลค่าเพิ่ม 7%: ฿635.90</p>
            <p>ทิปจากลูกค้า: ฿300</p>
            <p><strong class="highlight">ยอดสุทธิ: ฿9,205.00</strong></p>
        </div>
        <div class="report-box">
            <h2>👨‍🍳 สถานะคำสั่งอาหาร</h2>
            <p>กำลังปรุง: <span class="status cooking">7</span></p>
            <p>เสิร์ฟแล้ว: <span class="status served">92</span></p>
            <p>ยกเลิก: <span class="status cancled">2</span></p>
        </div>
        <div class="report-box">
            <h2>⏰ การเข้าออกงานพนักงาน</h2>
            <p>ฐิตารีย์ - เข้า: 08:00 / ออก: 17:30 <span class="status off-work">ออกงานแล้ว</span></p>
            <p>ปรางค์ - เข้า: 09:30 / ออก: - <span class="status working">กำลังทำงาน</span></p>
            <p>ไตรภพ - เข้า: 11:00 / ออก: - <span class="status working">กำลังทำงาน</span></p>
        </div>
        <div class="report-box">
            <h2>📌 เหตุการณ์เด่นวันนี้</h2>
            <p>มีออเดอร์ใหม่ 6 รายการช่วง 12:00-13:00</p>
            <p>น้ำแข็งใกล้หมด</p>
            <p>เมนูขายดี: ข้าวผัด (15 ชุด)</p>
        </div>
        <div class="report-box">
            <h2>📦 สถานะสต็อกสินค้า</h2>
            <p>ข้าวสาร: ปกติ</p>
            <p>น้ำแข็ง: ใกล้หมด</p>
            <p>ไอศกรีมมะพร้าว: เหลือน้อย</p>
        </div>
    </div>
</div>
<div class="profile-box">
    <img src="img/picture/Profile_guy.png" alt="Profile Picture">
    <div class="profile-info">
        <p class="profile-name">ชนภัทร์</p>
        <p class="profile-id">ID: KM-008</p>
    </div>
</div>
<div class="home-button" onclick="location.href='index.php'">
    <img src="img/picture/Home_icon.png" alt="Home Icon">
    <p>หน้าหลัก</p>
</div>
<div class="exit-button" onclick="location.href='login.php'">
    <img src="img/picture/Exit_door.png" alt="Exit">
</div>
</body>
</html>