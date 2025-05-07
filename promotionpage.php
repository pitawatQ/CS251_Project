<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>โปรโมชั่น</title>
  <link href="https://fonts.googleapis.com/css2?family=Sarabun&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/promotion.css">
</head>
<body>
  <div class="container">
    <!-- Top Bar -->
    <div class="top-bar">
      <div class="logo-box">
        <img src="pics/brand.png" alt="โลโก้ร้าน">
      </div>
      <div class="nav-buttons">
        <button class="nav-btn"><img src="pics/home_icon.png"> หน้าหลัก</button>
        <button class="nav-btn active"><img src="pics/cook.png"> สั่งอาหาร</button>
        <div class="lang-dropdown">
          <button><img src="pics/bk-globe.png" class="lang-icon"> ภาษาไทย ▼</button>
          <div class="dropdown-content">
            <a href="#"><img src="pics/bk-globe.png" class="lang-option-icon"> ภาษาไทย ✓</a>
            <a href="#"><img src="pics/bk-globe.png" class="lang-option-icon"> English</a>
          </div>
        </div>
      </div>
    </div>

    <div class="gradient-bar"></div>

    <!-- Promotion Content -->
    <div class="content-wrapper">
      <!-- Left Section -->
      <div class="promo-left">
        <div class="tab-bar">
          <button class="tab active">⭐ เมนูยอดนิยม</button>
          <button class="tab">⭐ โปรโมชั่น</button>
        </div>

        <div class="banner">
          <img src="pics/banner.png" alt="Summer Deals">
        </div>

        <p class="desc">เติมความสดชื่นด้วยเมนูเย็น ๆ ราคาสุดพิเศษต้อนรับหน้าร้อนและเทศกาลสงกรานต์ 💦</p>

        <div class="promo-grid">
          <div class="promo-card">
            <img src="pics/set1.png" alt="set1">
            <div class="badge">โปรโมชั่น</div>
            <div class="info">
              <h3>ชุดข้าวแช่พร้อมเครื่องเคียง + น้ำมะพร้าว</h3>
              <div class="price-row">
                <span class="original">฿ 190</span>
                <span class="sale">฿ 143</span>
              </div>
              <button class="btn">สั่งเลย</button>
            </div>
          </div>

          <div class="promo-card">
            <img src="pics/set2.png" alt="set2">
            <div class="badge">โปรโมชั่น</div>
            <div class="info">
              <h3>เชตไอติม + ไอศกรีมลด 10%</h3>
              <div class="price-row">
                <span class="original">฿ 110</span>
                <span class="sale">฿ 99</span>
              </div>
              <button class="btn">สั่งเลย</button>
            </div>
          </div>

          <div class="promo-card">
            <img src="pics/set3.png" alt="set3">
            <div class="badge">โปรโมชั่น</div>
            <div class="info">
              <h3>สมูทตี้ชุดเย็น (น้ำเจี๊ยบใส + น้ำผลไม้)</h3>
              <div class="price-row">
                <span class="original">฿ 80</span>
                <span class="sale">฿ 59</span>
              </div>
              <button class="btn">สั่งเลย</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Cart Section -->
      <div class="cart-box">
        <h2><img src="pics/basket.png" class="menu-icon"> ตะกร้า</h2>
        <p class="table">โต๊ะ 3 <a class="add-item">+ เพิ่มรายการ</a></p>

        <div class="cart-item">
          <img src="pics/set1.png">
          <div class="cart-details">
            <span class="cart-title">ชุดข้าวแช่พร้อมเครื่องเคียง + น้ำมะพร้าว</span>
            <span class="cart-label">โปรโมชั่น</span>
            <div class="cart-price">฿ 143.00 x 2 = ฿ 286.00</div>
            <a class="edit-link">แก้ไข</a>
          </div>
        </div>

        <div class="cart-item">
          <img src="pics/set3.png">
          <div class="cart-details">
            <span class="cart-title">สมูทตี้ชุดเย็น</span>
            <span class="cart-label">โปรโมชั่น</span>
            <div class="cart-price">฿ 59.00 x 2 = ฿ 118.00</div>
            <a class="edit-link">แก้ไข</a>
          </div>
        </div>

        <div class="cart-total">
          รวม 4 รายการ <span>฿ 404.00</span>
        </div>

        <button class="confirm-btn">ยืนยันรายการ</button>
      </div>
    </div>
  </div>
</body>
</html>
