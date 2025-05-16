<?php
include 'backend/db_connect.php';

// ดึงโปรโมชัน
$sql_promo = "
SELECT p.PromotionID, p.PromotionName, p.PromotionPrice, p.PromotionDes, p.Picture
FROM Promotion p
ORDER BY p.PromotionID
";
$result_promo = mysqli_query($conn, $sql_promo);

// ดึงโต๊ะที่ว่าง
$sql_table = "SELECT TableNo FROM TableList WHERE Status = 0";
$result_table = mysqli_query($conn, $sql_table);

// เมนูในแต่ละโปร
$promoMenus = [];
$sql_menu_in_promo = "
SELECT pm.PromotionID, m.MenuID, m.Name as MenuName, m.Picture as MenuPic
FROM PromotionMenu pm
JOIN Menu m ON pm.MenuID = m.MenuID
";
$result_menu_in_promo = mysqli_query($conn, $sql_menu_in_promo);
while ($row = mysqli_fetch_assoc($result_menu_in_promo)) {
    $promoMenus[$row['PromotionID']][] = $row;
}
?>
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
  <div class="top-bar">
    <div class="logo-box">
      <img src="pics/brand.png" alt="โลโก้ร้าน">
    </div>
    <div class="nav-buttons">
      <button class="nav-btn" onclick="window.location.href='home_customer.php'">
        <img src="pics/home_icon.png" alt="หน้าหลัก"> หน้าหลัก
      </button>
      <button class="nav-btn active">
        <img src="pics/cook.png" alt="โปรโมชั่น"> โปรโมชั่น
      </button>
      <button class="nav-btn" id="call-staff-btn">
        <img src="img/picture/Notifying_bell.png" alt="เรียกพนักงาน"> เรียกพนักงาน
      </button>
      <div class="table-dropdown">
        <button id="table-btn">เลือกหมายเลขโต๊ะ ▼</button>
        <div class="dropdown-content">
          <?php while ($row = mysqli_fetch_assoc($result_table)): ?>
            <a href="#" class="table-select">โต๊ะ <?= $row['TableNo'] ?></a>
          <?php endwhile; ?>
        </div>
      </div>
    </div>
  </div>
  <div class="gradient-bar"></div>
  <div class="content-wrapper">
    <div class="menu-section">
      <div class="menu-heading">
        <img src="pics/flowers.png" class="menu-icon" alt="menu icon">
        <h2>โปรโมชั่นพิเศษ</h2>
      </div>
      <div class="gradient-line"></div>
      <div class="tags">
        <a href="home_customer.php" class="tag popular">⭐ เมนูยอดนิยม</a>
        <div class="tag promo">⭐ โปรโมชั่น</div>
      </div>
      <div class="banner">
        <img src="pics/banner.png" alt="Summer Deals">
      </div>
      <p class="desc">เลือกโปรโมชันราคาพิเศษสุดคุ้ม! ทุกชุดรวมเมนูดังต่อไปนี้</p>
      <div class="menu-grid">
        <?php while ($promo = mysqli_fetch_assoc($result_promo)): ?>
          <div class="menu-card promo-card"
            data-type="promo"
            data-id="<?= $promo['PromotionID'] ?>"
            data-name="<?= htmlspecialchars($promo['PromotionName']) ?>"
            data-price="<?= $promo['PromotionPrice'] ?>"
            data-img="<?= $promo['Picture'] ?>">
            <img src="<?= $promo['Picture'] ?>" class="menu-img" data-img="<?= $promo['Picture'] ?>">
            <div class="menu-name"><?= htmlspecialchars($promo['PromotionName']) ?></div>
            <div class="promo-desc"><?= htmlspecialchars($promo['PromotionDes']) ?></div>
            <div class="promo-menu-items">
              <ul class="promo-menu-list">
                <?php if(!empty($promoMenus[$promo['PromotionID']])): ?>
                  <?php foreach ($promoMenus[$promo['PromotionID']] as $menu): ?>
                    <li>
                      <img src="<?= $menu['MenuPic'] ?>" class="promo-menu-pic" alt="">
                      <?= htmlspecialchars($menu['MenuName']) ?>
                    </li>
                  <?php endforeach; ?>
                <?php else: ?>
                  <li><em>ไม่มีเมนูในโปรโมชัน</em></li>
                <?php endif; ?>
              </ul>
            </div>
            <div class="menu-price">฿ <?= number_format($promo['PromotionPrice'], 2) ?></div>
            <button class="select-btn">เลือก</button>
          </div>
        <?php endwhile; ?>
      </div>
    </div>
    <!-- ตะกร้ารวม -->
    <div class="cart-box">
      <h2><img src="pics/basket.png" class="menu-icon" alt="basket"> ตะกร้า</h2>
      <p id="table-status">ยังไม่ได้เลือกโต๊ะ</p>
      <div id="empty-cart" class="empty-cart">
        <img class="empty-icon" src="pics/gold.png" alt="icon">
        <p><strong>ยังไม่มีคำสั่งซื้อ</strong></p>
        <p>เมื่อคุณทำการสั่งอาหาร ระบบจะแสดงสถานะที่นี่ให้คุณติดตามแบบเรียลไทม์</p>
      </div>
      <ul id="cart-items"></ul>
      <div id="cart-total" class="total-price" style="display: none;">
        รวมทั้งสิ้น: <span id="total-amount">฿ 0</span>
      </div>
      <button id="submit-order" class="add-to-cart-btn" style="display: none;">ใส่ตะกร้า</button>
    </div>
  </div>
</div>
<script>
let selectedMenuItems = JSON.parse(localStorage.getItem('cart')) || {};
function updateCartDisplay() {
  const cartItemsContainer = document.getElementById('cart-items');
  const emptyCartMessage = document.getElementById('empty-cart');
  const cartTotalSection = document.getElementById('cart-total');
  const submitOrderButton = document.getElementById('submit-order');
  cartItemsContainer.innerHTML = '';
  let hasItems = false;
  for (const key in selectedMenuItems) {
    if (!selectedMenuItems.hasOwnProperty(key)) continue;
    const item = selectedMenuItems[key];
    if (item.quantity > 0) {
      hasItems = true;
      const listItem = document.createElement('li');
      listItem.classList.add('cart-item');
      listItem.setAttribute('data-price', item.price);
      listItem.setAttribute('data-key', key);
      listItem.innerHTML = `
        <div class="cart-item-header">
            <img src="${item.img}" class="cart-item-img">
            <div class="cart-item-details">
                <div class="cart-item-name">${item.name}</div>
                <div class="cart-item-type">${item.type === 'promo' ? '<span class="promo-label">โปรโมชัน</span>' : ''}</div>
                <div class="cart-item-price">฿ ${parseFloat(item.price).toFixed(2)}</div>
            </div>
        </div>
        <div class="cart-item-controls">
            <div class="quantity-controls">
                <button class="quantity-btn decrease-btn">-</button>
                <span class="quantity">${item.quantity}</span>
                <button class="quantity-btn increase-btn">+</button>
            </div>
            <button class="remove-btn">✕</button>
        </div>
        <textarea class="special-note-cart" placeholder="พิมพ์รายละเอียดเพิ่มเติม เช่นไม่เอาผัก">${item.note || ''}</textarea>
      `;
      cartItemsContainer.appendChild(listItem);
    }
  }
  if (hasItems) {
    emptyCartMessage.style.display = 'none';
    cartTotalSection.style.display = 'block';
    submitOrderButton.style.display = 'block';
    calculateTotal();
  } else {
    emptyCartMessage.style.display = 'block';
    cartTotalSection.style.display = 'none';
    submitOrderButton.style.display = 'none';
  }
  document.querySelectorAll('.select-btn').forEach(btn => {
    const card = btn.closest('.menu-card');
    const key = card.dataset.type + '-' + card.dataset.id;
    if (selectedMenuItems[key] && selectedMenuItems[key].quantity > 0) {
      btn.textContent = 'เพิ่มแล้ว';
      btn.disabled = true;
    } else {
      btn.textContent = 'เลือก';
      btn.disabled = false;
    }
  });
}
function calculateTotal() {
  let total = 0;
  for (const key in selectedMenuItems) {
    if (selectedMenuItems[key].quantity > 0)
      total += selectedMenuItems[key].price * selectedMenuItems[key].quantity;
  }
  document.getElementById('total-amount').textContent = `฿ ${total.toFixed(2)}`;
  return total;
}
document.addEventListener('click', function(event) {
  if (event.target.classList.contains('select-btn')) {
    const card = event.target.closest('.menu-card');
    const key = card.dataset.type + '-' + card.dataset.id;
    if (selectedMenuItems[key]) {
      selectedMenuItems[key].quantity++;
    } else {
      selectedMenuItems[key] = {
        name: card.dataset.name,
        price: parseFloat(card.dataset.price),
        img: card.dataset.img,
        type: card.dataset.type,
        id: card.dataset.id,
        quantity: 1,
        note: ''
      };
    }
    localStorage.setItem('cart', JSON.stringify(selectedMenuItems));
    updateCartDisplay();
  }
  let target = event.target;
  if (target.classList.contains('increase-btn') || target.classList.contains('decrease-btn')) {
    const cartItem = target.closest('.cart-item');
    const key = cartItem.getAttribute('data-key');
    if (selectedMenuItems[key]) {
      if (target.classList.contains('increase-btn')) {
        selectedMenuItems[key].quantity++;
      } else if (selectedMenuItems[key].quantity > 1) {
        selectedMenuItems[key].quantity--;
      }
      localStorage.setItem('cart', JSON.stringify(selectedMenuItems));
      updateCartDisplay();
    }
  }
  if (target.classList.contains('remove-btn')) {
    const cartItem = target.closest('.cart-item');
    const key = cartItem.getAttribute('data-key');
    delete selectedMenuItems[key];
    localStorage.setItem('cart', JSON.stringify(selectedMenuItems));
    updateCartDisplay();
  }
  if (event.target.classList.contains('table-select')) {
    event.preventDefault();
    const tableNumber = event.target.textContent;
    document.getElementById('table-status').textContent = tableNumber;
    document.getElementById('table-btn').textContent = tableNumber;
    event.target.closest('.dropdown-content').style.display = 'none';
  }
});
document.addEventListener('change', function(event) {
  if (event.target.classList.contains('special-note-cart')) {
    const cartItem = event.target.closest('.cart-item');
    const key = cartItem.getAttribute('data-key');
    if (selectedMenuItems[key]) {
      selectedMenuItems[key].note = event.target.value.trim();
      localStorage.setItem('cart', JSON.stringify(selectedMenuItems));
    }
  }
});
document.getElementById('submit-order').addEventListener('click', function() {
  const tableStatus = document.getElementById('table-status').textContent;
  if (tableStatus === 'ยังไม่ได้เลือกโต๊ะ') {
    alert('กรุณาเลือกหมายเลขโต๊ะก่อนสั่งอาหาร');
    return;
  }
  const orderDetails = [];
  for (const key in selectedMenuItems) {
    if (selectedMenuItems[key].quantity > 0) {
      orderDetails.push({
        type: selectedMenuItems[key].type,
        id: selectedMenuItems[key].id,
        name: selectedMenuItems[key].name,
        quantity: selectedMenuItems[key].quantity,
        note: selectedMenuItems[key].note || ''
      });
    }
  }
  if (orderDetails.length === 0) {
    alert('กรุณาเลือกเมนู/โปรโมชันก่อนสั่ง');
    return;
  }
  const total = calculateTotal();
  if (!confirm(`ยืนยันการสั่งที่ ${tableStatus}\nรายการ: ${orderDetails.map(o=>o.name+' x'+o.quantity).join(', ')}\nรวมทั้งสิ้น: ฿ ${total}`)) {
    return;
  }
  const formData = new FormData();
  formData.append('table_no', tableStatus.split(' ')[1]);
  formData.append('orderDetails', JSON.stringify(orderDetails));
  const loadingMessage = document.createElement('div');
  loadingMessage.id = 'loading-message';
  loadingMessage.style.position = 'fixed';
  loadingMessage.style.top = '50%';
  loadingMessage.style.left = '50%';
  loadingMessage.style.transform = 'translate(-50%, -50%)';
  loadingMessage.style.padding = '20px';
  loadingMessage.style.background = 'rgba(0, 0, 0, 0.7)';
  loadingMessage.style.color = 'white';
  loadingMessage.style.borderRadius = '5px';
  loadingMessage.style.zIndex = '1000';
  loadingMessage.textContent = 'กำลังดำเนินการ...';
  document.body.appendChild(loadingMessage);
  fetch('backend/insert_order.php', {
    method: 'POST',
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    document.body.removeChild(document.getElementById('loading-message'));
    if (data.status === 'success') {
      localStorage.removeItem('cart');
      localStorage.setItem('orderID', data.orderID);
      localStorage.setItem('tableNo', tableStatus.split(' ')[1]);
      showOrderStatus(tableStatus.split(' ')[1], data.orderID);
    } else {
      alert('เกิดข้อผิดพลาด: ' + data.message);
    }
  })
  .catch(error => {
    if (document.getElementById('loading-message')) document.body.removeChild(document.getElementById('loading-message'));
    alert('เกิดข้อผิดพลาดในการเชื่อมต่อ');
  });
});
document.getElementById('table-btn').addEventListener('click', function() {
  const dropdown = this.nextElementSibling;
  dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
});
window.addEventListener('click', function(event) {
  if (!event.target.matches('#table-btn')) {
    const dropdowns = document.getElementsByClassName('dropdown-content');
    for (let i = 0; i < dropdowns.length; i++) {
      if (dropdowns[i].style.display === 'block') {
        dropdowns[i].style.display = 'none';
      }
    }
  }
});
// เรียกพนักงาน
document.getElementById('call-staff-btn').addEventListener('click', function() {
  const tableStatusElement = document.getElementById('table-status');
  const currentTableText = tableStatusElement.textContent;
  if (currentTableText === 'ยังไม่ได้เลือกโต๊ะ') {
    alert('กรุณาเลือกหมายเลขโต๊ะก่อนเรียกพนักงาน');
    return;
  }
  const tableNumber = currentTableText.split(' ')[1];
  fetch('backend/call_staff.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `table_no=${tableNumber}`
  })
  .then(response => response.text())
  .then(data => {
    if (data === 'success') {
      alert(`เรียกพนักงานไปยังโต๊ะ ${tableNumber} แล้ว`);
    } else {
      alert('เกิดข้อผิดพลาดในการเรียกพนักงาน');
    }
  })
  .catch(() => alert('เกิดข้อผิดพลาดในการเชื่อมต่อ'));
});

window.addEventListener('DOMContentLoaded', updateCartDisplay);

// ====== ฟังก์ชันสถานะออเดอร์ =========
function showOrderStatus(tableNumber, orderID) {
  const now = new Date();
  const hours = String(now.getHours()).padStart(2, '0');
  const minutes = String(now.getMinutes()).padStart(2, '0');
  const currentTime = `${hours}:${minutes}`;
  const cartBox = document.querySelector('.cart-box');
  const cartItems = document.getElementById('cart-items');
  const emptyCart = document.getElementById('empty-cart');
  const cartTotal = document.getElementById('cart-total');
  const submitButton = document.getElementById('submit-order');

  cartItems.style.display = 'none';
  emptyCart.style.display = 'none';
  cartTotal.style.display = 'none';
  submitButton.style.display = 'none';

  // ลบ status เดิมถ้ามี
  const oldStatus = document.getElementById('status-tracking-container');
  if (oldStatus) oldStatus.remove();

  const statusHTML = `
    <div class="order-status-details">
      <div class="order-info">
        <h3>ติดตามสถานะคำสั่งซื้อของคุณ</h3>
        <p>หมายเลขคำสั่งซื้อ</p>
        <div class="order-id-box">${orderID}</div>
        <p>เวลาที่สั่ง: ${currentTime} น. | โต๊ะ: ${tableNumber}</p>
      </div>
      <div class="status-tracking">
        <div class="status-item-wrapper received">
          <div class="status-item">
            <img src="pics/od1.png" class="status-icon" alt="รับออเดอร์แล้ว">
            <div>
              <strong>รับออร์เดอร์แล้ว</strong>
              <p class="status-description">ระบบได้รับคำสั่งซื้อของคุณแล้ว</p>
            </div>
          </div>
        </div>
        <div class="status-item-wrapper cooking">
          <div class="status-item">
            <img src="pics/od2.png" class="status-icon" alt="กำลังปรุงอาหาร">
            <div>
              <strong>กำลังปรุงอาหาร</strong>
              <p class="status-description">เชฟกำลังปรุงอาหารของคุณ</p>
            </div>
          </div>
        </div>
        <div class="status-item-wrapper serving">
          <div class="status-item">
            <img src="pics/od3.png" class="status-icon" alt="พร้อมเสิร์ฟ">
            <div>
              <strong>พร้อมเสิร์ฟ</strong>
              <p class="status-description">อาหารกำลังนำไปเสิร์ฟที่โต๊ะแล้ว</p>
            </div>
          </div>
        </div>
      </div>
      <button id="order-new-btn" class="add-to-cart-btn">สั่งเมนูใหม่</button>
    </div>
  `;
  const statusTrackingDiv = document.createElement('div');
  statusTrackingDiv.id = 'status-tracking-container';
  statusTrackingDiv.innerHTML = statusHTML;
  cartBox.appendChild(statusTrackingDiv);

  document.getElementById('order-new-btn').addEventListener('click', function() {
    localStorage.removeItem('orderID');
    localStorage.removeItem('tableNo');
    window.location.href = 'promotionpage.php';
  });

  pollOrderStatus(orderID);
}

function updateStatusTracking(status) {
  const wrappers = document.querySelectorAll('.status-item-wrapper');
  wrappers.forEach((w, i) => {
    if (i <= status - 2) {
      w.classList.add('active');
    } else {
      w.classList.remove('active');
    }
  });
}
function pollOrderStatus(orderID) {
  setInterval(() => {
    fetch('backend/get_order_status.php?order_id=' + orderID)
      .then(response => response.json())
      .then(data => {
        if (typeof data.status !== "undefined") {
          updateStatusTracking(data.status);
        }
      })
      .catch(err => {
        console.error("Error polling order status", err);
      });
  }, 5000);
}
</script>
</body>
</html>
