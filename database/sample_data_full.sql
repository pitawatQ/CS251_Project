
USE restaurant_db;

-- TableList
INSERT INTO TableList (TableNo, Status) VALUES
(1, 0), (2, 1), (3, 0), (4, 2), (5, 0);

-- Category
INSERT INTO Category (CategoryID, CName) VALUES
(1, 'ข้าวต้ม'), (2, 'อาหารจานเดียว'), (3, 'ต้มยำ'), (4, 'ของทอด'), (5, 'เครื่องดื่ม');

-- Menu
INSERT INTO Menu (MenuID, CategoryID, Name, Price, Status, MenuDes, Picture) VALUES
(101, 2, 'ข้าวผัดปู', 80.00, TRUE, 'ข้าวผัดปูเนื้อแน่น', '/img/menu/friedricecrab.jpg'),
(102, 3, 'ต้มยำกุ้ง', 120.00, TRUE, 'ต้มยำกุ้งรสแซ่บ', '/img/menu/tomyumkung.jpg'),
(103, 4, 'ไก่ทอด', 60.00, TRUE, 'ไก่ทอดกรอบอร่อย', '/img/menu/friedchicken.jpg'),
(104, 5, 'ชาเย็น', 35.00, TRUE, 'ชาเย็นหอมหวาน', '/img/menu/tea.jpg'),
(105, 1, 'ข้าวต้มหมู', 50.00, TRUE, 'ข้าวต้มหมูร้อนๆ', '/img/menu/riceporridge.jpg');

-- Employee
INSERT INTO Employee (EmployeeID, FName, LName, Password, Role, StartDate, Phone, Email) VALUES
(100001, 'พีรพล', 'สุขุมาลพันธ์', 'pass1234', 'admin', '2024-01-01', '0951111111', 'pee@example.com'),
(100002, 'รัตนากร', 'วังคีรี', 'pass1234', 'manager', '2024-02-01', '0952222222', 'rat@example.com'),
(100003, 'ศิวกิจ ', 'ภูสุนาทัน', 'pass1234', 'staff', '2024-03-01', '0953333333', 'siw@example.com'),
(100004, 'อริยา ', 'ตั้งโรจนกุล', 'pass1234', 'admin', '2023-12-01', '0954444444', 'ari@example.com'),
(100005, 'พิทวัส ', 'พิรักษา', 'pass1234', 'staff', '2024-01-15', '0955555555', 'pit@example.com'),
(100006, 'ปวีร์  ', 'สีดามาตร', 'pass1234', 'staff', '2024-01-15', '0966666666', 'paw@example.com'),
(0001, 'สมชาย ', 'ใจงาม', '1234', 'admin', '2024-01-15', '0977777777', 'som@example.com'),
(0002, 'สมศรี ', 'ใจดี', '1234', 'manager', '2024-01-15', '0988888888', 'soms@example.com'),
(0003, 'สมปอง ', 'ใจเย็น', '1234', 'staff', '2024-01-15', '0999999999', 'somp@example.com');

-- Orders
INSERT INTO Orders (OrderID, EmployeeID, TableNo, OrderTime, Status) VALUES
(500001, 100001, 1, '2024-04-24 12:00:00', 3),
(500002, 100002, 2, '2024-04-24 12:10:00', 2),
(500003, 100003, 3, '2024-04-24 12:20:00', 4),
(500004, 100004, 4, '2024-04-24 12:30:00', 5),
(500005, 100005, 5, '2024-04-24 12:40:00', 6);

-- OrderDetail
INSERT INTO OrderDetail (OrderID, MenuID, MenuQuntity, UnitPrice, ItemNo, TotalPrice, Description) VALUES
(500001, 101, 2, 80.00, 1, 160.00, 'ไม่เผ็ด'),
(500001, 104, 1, 35.00, 2, 35.00, ''),
(500002, 102, 1, 120.00, 1, 120.00, ''),
(500003, 103, 3, 60.00, 1, 180.00, 'กรอบๆ'),
(500004, 105, 2, 50.00, 1, 100.00, 'ไม่ใส่ผัก');

-- Supplier
INSERT INTO Supplier (SupplierID, Sname, Phone, Email) VALUES
(400001, 'ร้านลุงพี', '0911111111', 'lungpee@example.com'),
(400002, 'ร้านป้าแดง', '0922222222', 'padang@example.com'),
(400003, 'ฟาร์มสด', '0933333333', 'farmfresh@example.com'),
(400004, 'ตลาดไท', '0944444444', 'taladthai@example.com'),
(400005, 'โกลบอลฟู้ด', '0955555555', 'globalfood@example.com');

-- Stock
INSERT INTO Stock (IngredientID, SupplierID, IngredientName, Quantity, Unit, ImportDate, Expirationdate, LastUpdate) VALUES
(300001, 400001, 'หมูเนื้อแดง', 150.00, 'กรัม', '2024-04-20', '2024-05-01', '2024-04-20'),
(300002, 400002, 'กุ้งสด', 100.00, 'กรัม', '2024-04-21', '2024-04-28', '2024-04-21'),
(300003, 400003, 'ปูม้า', 80.00, 'กรัม', '2024-04-19', '2024-04-29', '2024-04-19'),
(300004, 400004, 'ไก่', 200.00, 'กรัม', '2024-04-18', '2024-04-30', '2024-04-18'),
(300005, 400005, 'ชาไทย', 50.00, 'กรัม', '2024-04-17', '2025-04-17', '2024-04-17');

-- IngredientUsage
INSERT INTO IngredientUsage (MenuID, IngredientID, QuantityUsed, ErrorRateUsed) VALUES
(101, 300003, 50.00, 0.05),
(102, 300002, 60.00, 0.04),
(103, 300004, 70.00, 0.03),
(104, 300005, 30.00, 0.02),
(105, 300001, 80.00, 0.06);

-- Payment
INSERT INTO Payment (PaymentID, OrderID, PaymentMethod, TotalPaid, PaymentDate, InvoiceNo, TotalDiscount, EmployeeID, Vat) VALUES
(600001, 500001, 'เงินสด', 195.00, '2024-04-24 13:00:00', 700001, 5.00, 100001, 12.00),
(600002, 500002, 'บัตรเครดิต', 120.00, '2024-04-24 13:10:00', 700002, 0.00, 100002, 8.00),
(600003, 500003, 'โอนเงิน', 180.00, '2024-04-24 13:20:00', 700003, 10.00, 100003, 9.00),
(600004, 500004, 'เงินสด', 100.00, '2024-04-24 13:30:00', 700004, 0.00, 100004, 7.00),
(600005, 500005, 'บัตรเครดิต', 250.00, '2024-04-24 13:40:00', 700005, 15.00, 100005, 14.00);

-- Promotion
INSERT INTO Promotion (PromotionID, PromotionName, PromotionPrice, PromotionDes, Picture) VALUES
(800001, 'โปรสงกรานต์', 60.00, 'สดชื่นรับสงกรานต์', '/img/promotion/1.jpg');



-- PromotionMenu
INSERT INTO PromotionMenu (PromotionID, MenuID) VALUES
(800001, 101);

-- Nutrition
INSERT INTO Nutrition (MenuID, Nutrition, Quantity, Unit) VALUES
(101, 'โปรตีน', 10.5, 'กรัม'),
(102, 'ไขมัน', 5.2, 'กรัม'),
(103, 'คาร์โบไฮเดรต', 20.0, 'กรัม'),
(104, 'น้ำตาล', 15.0, 'กรัม'),
(105, 'โปรตีน', 8.0, 'กรัม');

-- Attendance
INSERT INTO Attendance (EmployeeID, WorkDate, ClockInTime, ClockOutTime) VALUES
(100001, '2024-04-24', '08:00:00', '17:00:00'),
(100002, '2024-04-24', '09:00:00', '18:00:00'),
(100003, '2024-04-24', '07:30:00', '16:30:00'),
(100004, '2024-04-24', '08:15:00', '17:15:00'),
(100005, '2024-04-24', '09:30:00', '18:30:00');
