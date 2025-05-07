<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>เมนูแนะนำ</title>
  <link href="https://fonts.googleapis.com/css2?family=Sarabun&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/recommended.css">
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
          <img src="pics/cook.png" alt="เมนูแนะนำ"> สั่งอาหาร ▼
        </button>
        <div class="lang-dropdown">
          <button>
            <img src="pics/globe.png" class="lang-icon"> ภาษาไทย ▼
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

    <!-- Header Recommend -->
    <div class="recommend-header">
      <h1>เมนูแนะนำ <img src="pics/cir-star.png" width="28"></h1>
      <div class="subtext">“จานแนะนำจากเชฟ เสิร์ฟความอร่อยถึงโต๊ะคุณ”</div>
      <div class="menu-dropdown">
        <button><img src="pics/icon.png" width="20"> เมนูเดี่ยว ▼</button>
      </div>
    </div>

    <!-- Recommend List -->
    <div class="recommend-list">
      <button class="arrow-btn"><img src="pics/left.png"></button>

      <div class="rec-card">
        <img src="pics/massaman.png" alt="แกงมัสมั่นไก่">
        <div class="rec-detail">
          <h3>แกงมัสมั่นไก่ ❤️❤️❤️❤️❤️</h3>
          <p>หอม เข้มข้น เนื้ออุ่นนุ่มละลายในปาก</p>
        </div>
      </div>

      <div class="rec-card">
        <img src="pics/padthai.png" alt="ผัดไทยกุ้งสด">
        <div class="rec-detail">
          <h3>ผัดไทยกุ้งสด ❤️❤️❤️❤️❤️</h3>
          <p>เส้นเหนียวนุ่ม กุ้งเด้ง เต็มคำ</p>
        </div>
      </div>

      <div class="rec-card">
        <img src="pics/kha.png" alt="ต้มข่าไก่">
        <div class="rec-detail">
          <h3>ต้มข่าไก่ ❤️❤️❤️❤️❤️</h3>
          <p>ซุปกะทิหอมมัน รสละมุนชวนซด</p>
        </div>
      </div>

      <button class="arrow-btn"><img src="pics/right.png"></button>
    </div>

    <div class="start-btn">
      <button>เริ่มสั่งอาหาร</button>
    </div>
  </div>
</body>
</html>
