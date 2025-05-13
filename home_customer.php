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
    <div class="top-bar">
      <div class="logo-box">
        <img src="pics/brand.png" alt="โลโก้ร้าน">
      </div>
      <div class="nav-buttons">
        <button class="nav-btn">
          <img src="pics/home_icon.png" alt="หน้าหลัก"> หน้าหลัก
        </button>
        <button class="nav-btn active">
          <img src="pics/cook.png" alt="เมนู"> สั่งอาหาร
        </button>
        <div class="table-dropdown">
          <button id="table-btn">เลือกหมายเลขโต๊ะ ▼</button>
          <div class="dropdown-content">
            <a href="#" class="table-select">โต๊ะ 1</a>
            <a href="#" class="table-select">โต๊ะ 2</a>
            <a href="#" class="table-select">โต๊ะ 3</a>
            <a href="#" class="table-select">โต๊ะ 4</a>
            <a href="#" class="table-select">โต๊ะ 5</a>
          </div>
        </div>
      </div>
    </div>

    <div class="gradient-bar"></div>

    <div class="content-wrapper">
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
           <a href="promotionpage.php" class="tag promo"><img src="pics/gr-star.png" width="20"> โปรโมชั่น </a>
        </div>
        <div class="menu-grid">
          <div class="menu-card">
            <img src="pics/gr-curry.png" class="menu-img" data-img="pics/gr-curry.png">
            <div class="menu-name">แกงเขียวหวานไก่</div>
            <div class="menu-price">฿ 70</div>
            <button class="select-btn">เลือก</button>
          </div>
          <div class="menu-card">
            <img src="pics/panang.png" class="menu-img" data-img="pics/panang.png">
            <div class="menu-name">พะแนงหมู</div>
            <div class="menu-price">฿ 80</div>
            <button class="select-btn">เลือก</button>
          </div>
          <div class="menu-card">
            <img src="pics/tyk.png" class="menu-img" data-img="pics/tyk.png">
            <div class="menu-name">ต้มยำกุ้งน้ำข้น</div>
            <div class="menu-price">฿ 90</div>
            <button class="select-btn">เลือก</button>
          </div>
          <div class="menu-card">
            <img src="pics/fried.png" class="menu-img" data-img="pics/fried.png">
            <div class="menu-name">ข้าวผัดกุ้ง</div>
            <div class="menu-price">฿ 60</div>
            <button class="select-btn">เลือก</button>
          </div>
          <div class="menu-card">
            <img src="pics/crab.png" class="menu-img" data-img="pics/crab.png">
            <div class="menu-name">ปูผัดผงกะหรี่</div>
            <div class="menu-price">฿ 80</div>
            <button class="select-btn">เลือก</button>
          </div>
        </div>
      </div>

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
    /// ฟังก์ชันคำนวณราคารวม (คงเดิม)
    function calculateTotal() {
      let total = 0;
      document.querySelectorAll('.cart-item').forEach(item => {
        const price = parseFloat(item.getAttribute('data-price'));
        const quantity = parseInt(item.querySelector('.quantity').textContent);
        total += price * quantity;
      });
      document.getElementById('total-amount').textContent = `฿ ${total}`;
      return total;
    }

    let selectedMenuItems = {}; // Object เก็บรายการเมนูที่เลือกไว้ { itemName: { price: number, img: string, quantity: number, note: string } }

    // ฟังก์ชันอัปเดตการแสดงผลตะกร้า
    function updateCartDisplay() {
      const cartItemsContainer = document.getElementById('cart-items');
      const emptyCartMessage = document.getElementById('empty-cart');
      const cartTotalSection = document.getElementById('cart-total');
      const submitOrderButton = document.getElementById('submit-order');
      const addMoreButton = document.getElementById('add-more-items-btn');

      cartItemsContainer.innerHTML = '';
      let hasItems = false;

      for (const itemName in selectedMenuItems) {
        if (selectedMenuItems.hasOwnProperty(itemName) && selectedMenuItems[itemName].quantity > 0) {
          hasItems = true;
          const item = selectedMenuItems[itemName];
          const quantity = item.quantity;
          const note = item.note || ''; // กำหนดค่าเริ่มต้นเป็น '' หากยังไม่มีโน้ต

          const listItem = document.createElement('li');
          listItem.classList.add('cart-item');
          listItem.setAttribute('data-price', item.price);
          listItem.setAttribute('data-name', itemName);

          let description = "";
          switch (itemName) {
            case "แกงเขียวหวานไก่": description = "แกงเขียวหวานเข้มข้น หอมใบโหระพา"; break;
            case "พะแนงหมู": description = "พะแนงหมูรสกลมกล่อม เครื่องแกงเข้มข้น"; break;
            case "ต้มยำกุ้งน้ำข้น": description = "ต้มยำกุ้งน้ำข้นรสแซ่บ หอมสมุนไพรสด"; break;
            case "ข้าวผัดกุ้ง": description = "ข้าวผัดหอมกระทะ พร้อมกุ้งตัวโต"; break;
            case "ปูผัดผงกะหรี่": description = "ปูเนื้อแน่น ผัดกับผงกะหรี่รสเข้ม"; break;
            default: description = "";
          }

          listItem.innerHTML = `
            <div class="cart-item-header">
              <img src="${item.img}" class="cart-item-img">
              <div class="cart-item-details">
                <div class="cart-item-name">${itemName}</div>
                <div class="menu-description">${description}</div>
                <div class="cart-item-price">฿ ${item.price.toFixed(2)}</div>
              </div>
            </div>
            <div class="cart-item-controls">
              <div class="quantity-controls">
                <button class="quantity-btn decrease-btn">-</button>
                <span class="quantity">${quantity}</span>
                <button class="quantity-btn increase-btn">+</button>
              </div>
              <textarea class="special-note-cart" placeholder="พิมพ์รายละเอียดเพิ่มเตืม เช่นไม่เอาผัก">${note}</textarea>
              <button class="remove-btn">✕</button>
            </div>
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
        if (addMoreButton) {
          addMoreButton.remove();
        }
      }

      // อัปเดตสถานะปุ่ม "เลือก" ในเมนู
      document.querySelectorAll('.select-btn').forEach(btn => {
        const menuItem = btn.closest('.menu-card');
        const itemName = menuItem.querySelector('.menu-name').textContent;
        if (selectedMenuItems[itemName] && selectedMenuItems[itemName].quantity > 0) {
          btn.textContent = 'เพิ่มแล้ว';
          btn.disabled = true;
        } else {
          btn.textContent = 'เลือก';
          btn.disabled = false;
        }
      });
    }

    // เพิ่มเมนูลงตะกร้า (เมื่อคลิก "เลือก")
    document.querySelectorAll('.select-btn').forEach(button => {
      button.addEventListener('click', function() {
        const menuItem = this.closest('.menu-card');
        const itemName = menuItem.querySelector('.menu-name').textContent;
        const itemPriceText = menuItem.querySelector('.menu-price').textContent;
        const itemPrice = parseFloat(itemPriceText.replace('฿ ', ''));
        const itemImg = menuItem.querySelector('.menu-img').getAttribute('data-img');

        if (selectedMenuItems[itemName]) {
          selectedMenuItems[itemName].quantity++;
        } else {
          selectedMenuItems[itemName] = {
            price: itemPrice,
            img: itemImg,
            quantity: 1,
            note: '' // เริ่มต้นด้วยโน้ตว่าง
          };
        }
        updateCartDisplay();
      });
    });

    // การทำงานเมื่อมีการเปลี่ยนแปลงใน textarea หมายเหตุในตะกร้า
    document.addEventListener('change', function(event) {
      if (event.target.classList.contains('special-note-cart')) {
        const cartItem = event.target.closest('.cart-item');
        const itemName = cartItem.getAttribute('data-name');
        if (selectedMenuItems[itemName]) {
          selectedMenuItems[itemName].note = event.target.value.trim();
        }
      }
    });

    // การทำงานเมื่อคลิกปุ่ม "ใส่ตะกร้า" (เปลี่ยนเป็น "ยืนยันรายการ" และซ่อน controls)
    document.getElementById('submit-order').addEventListener('click', function() {
      const cartItemsContainer = document.getElementById('cart-items');
      const addMoreButton = document.getElementById('add-more-items-btn');

      if (this.textContent === 'ใส่ตะกร้า' && Object.keys(selectedMenuItems).length > 0) {
        this.textContent = 'ยืนยันรายการ';
        // ซ่อนปุ่มเพิ่ม/ลด/ลบ (และ textarea)
        cartItemsContainer.querySelectorAll('.cart-item').forEach(item => {
          const quantityControls = item.querySelector('.quantity-controls');
          const removeButton = item.querySelector('.remove-btn');
          const noteInput = item.querySelector('.special-note-cart');
          if (quantityControls) quantityControls.style.display = 'none';
          if (removeButton) removeButton.style.display = 'none';
          if (noteInput) noteInput.style.display = 'none';
        });

        // แสดงปุ่ม "+ เพิ่มรายการ"
        if (!addMoreButton) {
          const newAddMoreButton = document.createElement('button');
          newAddMoreButton.id = 'add-more-items-btn';
          newAddMoreButton.classList.add('add-more-items-btn');
          newAddMoreButton.textContent = '+ เพิ่มรายการ';
          document.querySelector('.cart-box').appendChild(newAddMoreButton);

          newAddMoreButton.addEventListener('click', function() {
            // แสดงปุ่มควบคุมในตะกร้าอีกครั้ง (และ textarea)
            cartItemsContainer.querySelectorAll('.cart-item').forEach(item => {
              const quantityControls = item.querySelector('.quantity-controls');
              const removeButton = item.querySelector('.remove-btn');
              const noteInput = item.querySelector('.special-note-cart');
              if (quantityControls) quantityControls.style.display = 'flex';
              if (removeButton) removeButton.style.display = 'block';
              if (noteInput) noteInput.style.display = 'block';
            });
            document.getElementById('submit-order').textContent = 'ใส่ตะกร้า';
            newAddMoreButton.remove();
          });
        } else if (addMoreButton.style.display === 'none') {
          addMoreButton.style.display = 'block';
        }
      } else if (this.textContent === 'ยืนยันรายการ') {
        // โค้ดสำหรับการยืนยันรายการสั่งซื้อ (ต้องส่งข้อมูลหมายเหตุด้วย)
        const tableStatus = document.getElementById('table-status').textContent;
        const orderDetails = [];
        for (const itemName in selectedMenuItems) {
          if (selectedMenuItems[itemName].quantity > 0) {
            orderDetails.push({
              name: itemName,
              quantity: selectedMenuItems[itemName].quantity,
              note: selectedMenuItems[itemName].note
            });
          }
        }

        if (tableStatus === 'ยังไม่ได้เลือกโต๊ะ') {
          alert('กรุณาเลือกหมายเลขโต๊ะก่อนสั่งอาหาร');
          return;
        }

        if (orderDetails.length === 0) {
          alert('กรุณาเลือกเมนูอาหารก่อนสั่ง');
          return;
        }

        const total = calculateTotal();
        const confirmOrder = confirm(`ยืนยันการสั่งอาหารที่ ${tableStatus}\nรายการ: ${JSON.stringify(orderDetails, null, 2)}\nรวมทั้งสิ้น: ฿ ${total}`);

        if (confirmOrder) {
          alert('สั่งอาหารเรียบร้อยแล้ว');

          document.getElementById('cart-items').innerHTML = '';
          document.getElementById('empty-cart').style.display = 'block';
          document.getElementById('cart-total').style.display = 'none';
          this.style.display = 'none';
          if (addMoreButton) {
            addMoreButton.remove();
          }
          document.querySelectorAll('.select-btn').forEach(btn => {
            btn.textContent = 'เลือก';
            btn.disabled = false;
          });
          selectedMenuItems = {};
          cartItemsAdded = false;
        }
      }
    });

    // เพิ่ม event delegation สำหรับปุ่มเพิ่ม/ลดจำนวน และลบรายการ (ปรับให้คงค่าหมายเหตุ)
    document.addEventListener('click', function(event) {
      const target = event.target;
      if (target.classList.contains('increase-btn') || target.classList.contains('decrease-btn')) {
        const cartItem = target.closest('.cart-item');
        const itemName = cartItem.getAttribute('data-name');
        if (selectedMenuItems[itemName]) {
          if (target.classList.contains('increase-btn')) {
            selectedMenuItems[itemName].quantity++;
          } else if (target.classList.contains('decrease-btn') && selectedMenuItems[itemName].quantity > 1) {
            selectedMenuItems[itemName].quantity--;
          }
          updateCartDisplay();
        }
      }

      if (target.classList.contains('remove-btn')) {
        const cartItem = target.closest('.cart-item');
        const itemNameToRemove = cartItem.getAttribute('data-name');
        delete selectedMenuItems[itemNameToRemove];
        updateCartDisplay();
      }
    });

    // ระบบเลือกหมายเลขโต๊ะ (คงเดิม)
    document.addEventListener('click', function(event) {
      if (event.target.classList.contains('table-select')) {
        event.preventDefault();
        const tableNumber = event.target.textContent;
        document.getElementById('table-status').textContent = tableNumber;
        document.getElementById('table-btn').textContent = tableNumber;
        event.target.closest('.dropdown-content').style.display = 'none';
      }
    });

    // แสดง/ซ่อนเมนูเลือกโต๊ะ (คงเดิม)
    document.getElementById('table-btn').addEventListener('click', function() {
      const dropdown = this.nextElementSibling;
      dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
    });

    // ซ่อนเมนูเลือกโต๊ะเมื่อคลิกที่อื่น (คงเดิม)
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
  </script>
</body>
</html>
