<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>หน้าเมนูแนะนำ</title>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/porridge_menu.css">
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
                <button class="nav-btn" id="call-staff-btn">
                    <img src="img\picture\Notifying_bell.png" alt="เรียกพนักงาน"> เรียกพนักงาน
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
                    <a href="porridge_menu.php" class="menu-category-item" data-category="ข้าวต้ม">
                        <img src="pics/porridge.png" alt="ข้าวต้ม">
                        <div class="label">ข้าวต้ม</div>
                    </a>
                    <a href="single_menu.php" class="menu-category-item" data-category="อาหารจานเดียว">
                        <img src="pics/single.png" alt="อาหารจานเดียว">
                        <div class="label">อาหารจานเดียว</div>
                    </a>
                    <a href="tomyum_menu.php" class="menu-category-item" data-category="ต้มยำ">
                        <img src="pics/tomyum.png" alt="ต้มยำ">
                        <div class="label">ต้มยำ</div>
                    </a>
                    <a href="friedfood_menu.php" class="menu-category-item" data-category="ของทอด">
                        <img src="pics/friedfood.png" alt="ของทอด">
                        <div class="label">ของทอด</div>
                    </a>
                    <a href="bev_menu.php" class="menu-category-item active" data-category="เครื่องดื่ม">
                        <img src="pics/bev.png" alt="เครื่องดื่ม">
                        <div class="label">เครื่องดื่ม</div>
                    </a>
                </div>
                <div class="gradient-line"></div>
                <div class="tags">
                    <div class="tag rec">⭐ เครื่องดื่ม</div>
                    <a href="home_customer.php" class="tag popular">⭐ เมนูยอดนิยม</a>
                    <a href="promotionpage.php" class="tag promo">⭐ โปรโมชั่น </a>
                </div>
                <div class="menu-grid">
                    <div class="menu-card" data-item-id="1" data-category="ชาเย็น">
                        <img src="img/menu/tea.jpg" class="menu-img" data-img="img/menu/tea.jpg">
                        <div class="menu-name">ชาเย็น</div>
                        <div class="menu-price">฿ 35</div>
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
        // ฟังก์ชันคำนวณราคารวม
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
                    const note = item.note || '';

                    const listItem = document.createElement('li');
                    listItem.classList.add('cart-item');
                    listItem.setAttribute('data-price', item.price);
                    listItem.setAttribute('data-name', itemName);

                    let description = "";
                    switch (itemName) {
                        case "ข้าวต้มหมู": description = "ข้าวต้มหมูร้อนๆ"; break;
                        case "ข้าวผัดปู": description = "ข้าวผัดปูหอมอร่อย"; break;
                        case "ต้มยำกุ้ง": description = "ต้มยำกุ้งรสจัดจ้าน"; break;
                        case "ไก่ทอด": description = "ไก่ทอดกรอบนอกนุ่มใน"; break;
                        case "ชาเย็น": description = "ชาเย็นหวานชื่นใจ"; break;
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

        // การทำงานเมื่อคลิกปุ่ม "ใส่ตะกร้า" (เปลี่ยนเป็น "ยืนยันรายการ" และส่งออร์เดอร์)
           document.getElementById('submit-order').addEventListener('click', function() {
             const cartItemsContainer = document.getElementById('cart-items');
             const cartTotalSection = document.getElementById('cart-total');
             const emptyCartMessage = document.getElementById('empty-cart');
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
        // เตรียมข้อมูลสำหรับส่งไปยัง backend
        const tableStatusElement = document.getElementById('table-status');
        const tableNumberText = tableStatusElement.textContent;
        let tableNumber = null;
        if (tableNumberText.startsWith('โต๊ะ ')) {
            tableNumber = tableNumberText.split(' ')[1];
        } else {
            alert('กรุณาเลือกหมายเลขโต๊ะก่อนสั่งอาหาร');
            return;
        }

        const orderDetails = [];
        for (const itemName in selectedMenuItems) {
            if (selectedMenuItems[itemName].quantity > 0) {
                orderDetails.push({
                    name: itemName,
                    quantity: selectedMenuItems[itemName].quantity,
                    note: selectedMenuItems[itemName].note || ''
                });
            }
        }

        if (orderDetails.length === 0) {
            alert('กรุณาเลือกเมนูอาหารก่อนสั่ง');
            return;
        }

        const total = calculateTotal();
        const confirmOrder = confirm(`ยืนยันการสั่งอาหารที่ ${tableNumberText}\nรายการ: ${orderDetails.map(item => `${item.name} x${item.quantity}`).join(', ')}\nรวมทั้งสิ้น: ฿ ${total}`);

        if (confirmOrder) {
            // สร้าง FormData object
            const formData = new FormData();
            formData.append('table_no', tableNumber);
            formData.append('orderDetails', JSON.stringify({ orderDetails: orderDetails }));
            
            // แสดง Loading message
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

            // ส่งข้อมูลไปยัง backend
            fetch('backend/insert_order.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json()) // เปลี่ยนจาก text เป็น json
            .then(data => {
                // ลบ Loading message
                document.body.removeChild(document.getElementById('loading-message'));
                
                console.log("Server response:", data); // เพิ่ม log เพื่อตรวจสอบการตอบกลับ
                
                if (data.status === 'success') {
                    // แสดงหน้าติดตามสถานะอาการแทนตะกร้า โดยส่ง OrderID ที่ได้จาก server
                    showOrderStatus(tableNumber, data.orderID);
                } else {
                    alert('เกิดข้อผิดพลาดในการสั่งอาหาร: ' + data.message);
                    console.error('Error inserting order:', data.message);
                }
            })
            .catch(error => {
                // ลบ Loading message
                if (document.getElementById('loading-message')) {
                    document.body.removeChild(document.getElementById('loading-message'));
                }
                
                console.error('Fetch error:', error);
                alert('เกิดข้อผิดพลาดในการเชื่อมต่อ');
            });
          }
       }
    });

             // ฟังก์ชันสำหรับแสดงหน้าติดตามสถานะคำสั่งซื้อ
             function showOrderStatus(tableNumber, orderID) {
             // สร้างเวลาปัจจุบัน
                const now = new Date();
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                const currentTime = `${hours}:${minutes}`;
    
               // ล้างเนื้อหาในตะกร้า
                const cartBox = document.querySelector('.cart-box');
                const cartItems = document.getElementById('cart-items');
                const emptyCart = document.getElementById('empty-cart');
                const cartTotal = document.getElementById('cart-total');
                const submitButton = document.getElementById('submit-order');
                const addMoreButton = document.getElementById('add-more-items-btn');
    
                // ซ่อนส่วนประกอบเดิมของตะกร้า
                cartItems.style.display = 'none';
                emptyCart.style.display = 'none';
                cartTotal.style.display = 'none';
                submitButton.style.display = 'none';
                if (addMoreButton) {
                  addMoreButton.style.display = 'none';
              }
    
        // สร้าง HTML สำหรับแสดงหน้าติดตามสถานะ
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

            <div class="status-item-wrapper">
                <div class="status-item">
                    <img src="pics/od2.png" class="status-icon" alt="กำลังปรุงอาหาร">
                    <div>
                        <strong>กำลังปรุงอาหาร</strong>
                        <p class="status-description">เชฟกำลังปรุงอาหารของคุณ</p>
                    </div>
                </div>
            </div>

            <div class="status-item-wrapper">
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
    
               // แทรก HTML ลงในตะกร้า
         const statusTrackingDiv = document.createElement('div');
         statusTrackingDiv.id = 'status-tracking-container';
         statusTrackingDiv.innerHTML = statusHTML;
         cartBox.appendChild(statusTrackingDiv);
    
        // เพิ่ม Event Listener สำหรับปุ่ม "สั่งเมนูใหม่"
         document.getElementById('order-new-btn').addEventListener('click', function() {
            window.location.href = 'home_customer.php';
        });
    
       // ล้างข้อมูลเมนูที่เลือก
         selectedMenuItems = {};
    
    // รีเซ็ตปุ่ม "เลือก" ในเมนูทั้งหมด
      document.querySelectorAll('.select-btn').forEach(btn => {
        btn.textContent = 'เลือก';
        btn.disabled = false;
     });
   }

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

            // การทำงานเมื่อคลิกปุ่ม "เรียกพนักงาน"
            if (target.id === 'call-staff-btn') {
                const tableStatusElement = document.getElementById('table-status');
                const currentTableText = tableStatusElement.textContent;

                if (currentTableText === 'ยังไม่ได้เลือกโต๊ะ') {
                    alert('กรุณาเลือกหมายเลขโต๊ะก่อนเรียกพนักงาน');
                    return;
                }

                // ดึงหมายเลขโต๊ะ (สมมติว่าข้อความคือ "โต๊ะ X")
                const tableNumber = currentTableText.split(' ')[1];

                // ส่งคำขอไปยังเซิร์ฟเวอร์เพื่ออัปเดตสถานะโต๊ะ
                fetch('backend/call_staff.php', { // สร้างไฟล์ call_staff.php เพื่อจัดการการอัปเดต
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `table_no=${tableNumber}`
                })
                .then(response => response.text())
                .then(data => {
                    if (data === 'success') {
                        alert(`เรียกพนักงานไปยังโต๊ะ ${tableNumber} แล้ว`);
                        // อาจจะมีการเปลี่ยนแปลง UI เพิ่มเติม เช่น เปลี่ยนสีปุ่ม หรือแสดงข้อความสถานะ
                    } else {
                        alert('เกิดข้อผิดพลาดในการเรียกพนักงาน โปรดลองอีกครั้ง');
                        console.error('Error calling staff:', data);
                    }
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    alert('เกิดข้อผิดพลาดในการเชื่อมต่อ');
                });
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