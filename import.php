<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>นำเข้าวัตถุดิบจาก Supplier</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>📥 นำเข้าวัตถุดิบจาก Supplier</h1>
    <form>
        <div class="form-group">
            <label for="ingredientName">ชื่อวัตถุดิบ:</label>
            <input type="text" id="ingredientName" name="ingredientName" placeholder="ระบุชื่อวัตถุดิบ">
        </div>
        <div class="form-group">
            <label for="supplierName">ชื่อ Supplier:</label>
            <input type="text" id="supplierName" name="supplierName" placeholder="ระบุชื่อซัพพลายเออร์">
        </div>
        <div class="form-group">
            <label for="quantity">จำนวน (เช่น 10 กก.):</label>
            <input type="text" id="quantity" name="quantity" placeholder="เช่น 10 กก.">
        </div>
        <div class="form-group">
            <label for="importDate">วันที่นำเข้า (YYYY-MM-DD):</label>
            <input type="date" id="importDate" name="importDate">
        </div>
        <div class="form-group">
            <label for="expiryDate">วันหมดอายุ (YYYY-MM-DD):</label>
            <input type="date" id="expiryDate" name="expiryDate">
        </div>
        <div class="form-group">
            <label for="importer">ชื่อผู้ดำเนินการนำเข้า:</label>
            <input type="text" id="importer" name="importer" placeholder="ระบุชื่อผู้ดำเนินการ">
        </div>
        <button type="submit" class="btn">ยืนยันการนำเข้า</button>
        <button type="button" class="btn btn-delete">ยกเลิก</button>
    </form>
</body>
</html>
