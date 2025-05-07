<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>จัดการโปรโมชัน</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>🎉 จัดการโปรโมชัน</h1>
    <form>
        <div class="form-group">
            <label for="promoName">ชื่อโปรโมชัน:</label>
            <input type="text" id="promoName" name="promoName" placeholder="ระบุชื่อโปรโมชัน">
        </div>
        <div class="form-group">
            <label for="promoDetail">รายละเอียด:</label>
            <textarea id="promoDetail" name="promoDetail" rows="4" placeholder="ระบุรายละเอียดโปรโมชัน"></textarea>
        </div>
        <div class="form-group">
            <label for="discountType">ประเภทส่วนลด:</label>
            <select id="discountType" name="discountType">
                <option>เปอร์เซ็นต์</option>
                <option>จำนวนเงิน</option>
            </select>
        </div>
        <div class="form-group">
            <label for="discountAmount">จำนวนส่วนลด:</label>
            <input type="number" id="discountAmount" name="discountAmount">
        </div>
        <div class="form-group">
            <label for="minSpend">ยอดขั้นต่ำที่ใช้ได้:</label>
            <input type="number" id="minSpend" name="minSpend">
        </div>
        <div class="form-group">
            <label for="startDate">วันที่เริ่มต้น:</label>
            <input type="date" id="startDate" name="startDate">
        </div>
        <div class="form-group">
            <label for="endDate">วันที่สิ้นสุด:</label>
            <input type="date" id="endDate" name="endDate">
        </div>
        <div class="form-group">
            <label for="promoImage">อัปโหลดรูปโปรโมชัน:</label>
            <input type="file" id="promoImage" name="promoImage">
        </div>
        <button type="submit" class="btn">บันทึกโปรโมชัน</button>
        <button type="button" class="btn btn-delete">ยกเลิก</button>
    </form>
</body>
</html>
