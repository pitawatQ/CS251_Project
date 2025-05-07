<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>หน้าเมนูลูกค้า</title>
  <link href="https://fonts.googleapis.com/css2?family=Sarabun&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/home_customer.css">
</head>
<body>
  <div class="container">
    <!-- Top Bar -->
    <div class="top-bar">
      <div class="logo-box">
        <img src="pics/brand.png" alt="โลโก้ร้าน">
      </div>
      <div class="nav-buttons">
        <button class="nav-btn">
          <img src="pics/home_icon.png" alt="หน้าหลัก"> หน้าหลัก
        </button>
        <button class="nav-btn active">
          <img src="pics/cook.png" alt="เมนู"> สั่งอาหาร ▼
        </button>
        <div class="lang-dropdown">
          <button>
            <img src="pics/bk-globe.png" class="lang-icon"> ภาษาไทย ▼
          </button>
          <div class="dropdown-content">
            <a href="#"><img src="pics/bk-globe.png" class="lang-option-icon"> ภาษาไทย ✓</a>
            <a href="#"><img src="pics/bk-globe.png" class="lang-option-icon"> English</a>
          </div>
        </div>
      </div>
    </div>

    <!-- Gradient Bar -->
    <div class="gradient-bar"></div>

    <!-- Content -->
    <div class="content-wrapper">
      <!-- เมนูฝั่งซ้าย -->
      <div class="menu-section">
        <div class="menu-heading">
          <img src="pics/flowers.png" class="menu-icon" alt="menu icon">
          <h2>เมนู | หมวดหมู่</h2>
        </div>
        <div class="category-bar">
          <div class="menu-category-item">
            <img src="pics/rec.png" alt="เมนูแนะนำ">
            <div class="label">เมนูแนะนำ</div>
          </div>
          <div class="menu-category-item">
            <img src="pics/single.png" alt="เมนูเดี่ยว">
            <div class="label">เมนูเดี่ยว</div>
          </div>
          <div class="menu-category-item">
            <img src="pics/set.png" alt="เมนูเซ็ต">
            <div class="label">เมนูเซ็ต</div>
          </div>
          <div class="menu-category-item">
            <img src="pics/noodles.png" alt="เมนูเส้น">
            <div class="label">เมนูเส้น</div>
          </div>
          <div class="menu-category-item">
            <img src="pics/bev.png" alt="เครื่องดื่ม">
            <div class="label">เครื่องดื่ม</div>
          </div>
          <div class="menu-category-item">
            <img src="pics/sweet.png" alt="ของหวาน">
            <div class="label">ของหวาน</div>
          </div>
        </div>

        <div class="gradient-line"></div>

        <div class="tags">
          <div class="tag popular"><img src="pics/wh-star.png" width="20"> เมนูยอดนิยม</div>
          <div class="tag promo"><img src="pics/gr-star.png" width="20"> โปรโมชั่น</div>
        </div>

        <div class="menu-grid">
          <div class="menu-card">
            <img src="pics/gr-curry.png" class="menu-img">
            <div class="menu-name">แกงเขียวหวานไก่</div>
            <div class="menu-price">฿ 70</div>
            <button class="select-btn">เลือก</button>
          </div>
          <div class="menu-card">
            <img src="pics/panang.png" class="menu-img">
            <div class="menu-name">พะแนงหมู</div>
            <div class="menu-price">฿ 80</div>
            <button class="select-btn">เลือก</button>
          </div>
          <div class="menu-card">
            <img src="pics/tyk.png" class="menu-img">
            <div class="menu-name">ต้มยำกุ้งน้ำข้น</div>
            <div class="menu-price">฿ 90</div>
            <button class="select-btn">เลือก</button>
          </div>
          <div class="menu-card">
            <img src="pics/fried.png" class="menu-img">
            <div class="menu-name">ข้าวผัดกุ้ง</div>
            <div class="menu-price">฿ 60</div>
            <button class="select-btn">เลือก</button>
          </div>
          <div class="menu-card">
            <img src="pics/crab.png" class="menu-img">
            <div class="menu-name">ปูผัดผงกะหรี่</div>
            <div class="menu-price">฿ 80.00</div>
            <button class="select-btn">เลือก</button>
          </div>
        </div>
      </div>

      <!-- ตะกร้าฝั่งขวา -->
      <div class="cart-box">
        <h2><img src="pics/basket.png" class="menu-icon" alt="basket"> ตะกร้า</h2>
        <div class="empty-cart">
          <img class="empty-icon" src="pics/gold.png" alt="icon">
          <p><strong>ยังไม่มีคำสั่งซื้อ</strong></p>
          <p>เมื่อคุณทำการสั่งอาหาร ระบบจะแสดงสถานะที่นี่ให้คุณติดตามแบบเรียลไทม์</p>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
