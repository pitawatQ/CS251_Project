USE restaurant_db;

-- TableList
INSERT INTO TableList VALUES
(1,0),(2,0),(3,1),(4,0),(5,2),(6,0),(7,0);

-- Category
INSERT INTO Category (CName) VALUES
('อาหารจานเดียว'),('กับข้าว'),('เครื่องดื่ม'),('ของหวาน');

-- Menu
INSERT INTO Menu (CategoryID, Name, Price, Status, MenuDes, Picture) VALUES
(1, 'ข้าวผัดกุ้ง', 65.00, 1, 'ข้าวผัดหอมกุ้งสด', 'img/menu/fried_rice_shrimp.jpg'),
(1, 'ข้าวมันไก่', 55.00, 1, 'ข้าวมันไก่นุ่มๆ น้ำจิ้มสูตรพิเศษ', 'img/menu/chicken_rice.jpg'),
(1, 'ข้าวหมูแดง', 60.00, 1, 'ข้าวหมูแดงราดน้ำฉ่ำ', 'img/menu/red_pork_rice.jpg'),
(2, 'ต้มยำกุ้ง', 80.00, 1, 'ต้มยำกุ้งน้ำข้นรสแซ่บ', 'img/menu/tomyum_goong.jpg'),
(2, 'ผัดไทย', 55.00, 1, 'ผัดไทยต้นตำรับ', 'img/menu/padthai.jpg'),
(2, 'แกงเขียวหวานไก่', 75.00, 1, 'แกงเขียวหวานรสเข้มข้น', 'img/menu/green_curry.jpg'),
(3, 'ชาเย็น', 25.00, 1, 'ชาไทยเย็นหวานมัน', 'img/menu/iced_tea.jpg'),
(3, 'โอเลี้ยง', 20.00, 1, 'กาแฟดำเย็น', 'img/menu/iced_black_coffee.jpg'),
(4, 'บัวลอยไข่หวาน', 40.00, 1, 'บัวลอยน้ำขิงไข่หวาน', 'img/menu/bua_loy.jpg');

-- Employee
INSERT INTO Employee (FName, LName, Password, Role, StartDate, Phone, Email) VALUES
('สมชาย', 'ใจดี', '1234', 'admin', '2024-01-05', '0912345678', 'somchai@demo.com'),
('อารี', 'ศรีสุข', '1234', 'staff', '2024-02-01', '0897654321', 'aree@demo.com');

-- Orders
INSERT INTO Orders (EmployeeID, TableNo, OrderTime, Status) VALUES
(1,3,NOW(),2),
(2,5,NOW(),2);

-- OrderDetail
INSERT INTO OrderDetail VALUES
(1, 1, 2, 65.00, 1, 130.00, 'ไม่ใส่ต้นหอม'),
(1, 7, 1, 25.00, 2, 25.00, ''),
(2, 4, 1, 80.00, 1, 80.00, ''),
(2, 5, 1, 55.00, 2, 55.00, 'เพิ่มถั่วงอก');

-- Supplier
INSERT INTO Supplier (Sname, Phone, Email) VALUES
('สมศักดิ์ ผักสด', '0812345670', 'freshveg@mock.com'),
('รุ่งเรือง ตลาดปลา', '0823456789', 'fishmart@mock.com');

-- Stock
INSERT INTO Stock VALUES
(1001, 1, 'ข้าวสาร', 100.00, 'กิโลกรัม', '2024-05-01', '2024-11-01', '2024-05-10'),
(1002, 2, 'กุ้งสด', 20.00, 'กิโลกรัม', '2024-05-05', '2024-05-20', '2024-05-12'),
(1003, 1, 'หมูแดง', 30.00, 'กิโลกรัม', '2024-05-08', '2024-05-28', '2024-05-12'),
(1004, 1, 'ผักบุ้ง', 15.00, 'กิโลกรัม', '2024-05-12', '2024-05-22', '2024-05-12');

-- IngredientUsage
INSERT INTO IngredientUsage VALUES
(1,1001,0.20,0.05), -- ข้าวผัดกุ้งใช้ข้าวสาร 0.2kg
(1,1002,0.10,0.02), -- ข้าวผัดกุ้งใช้กุ้งสด 0.1kg
(3,1003,0.15,0.01), -- ข้าวหมูแดงใช้หมูแดง 0.15kg
(5,1004,0.10,0.01); -- ผัดไทยใช้ผักบุ้ง 0.1kg

-- Payment
INSERT INTO Payment (OrderID, PaymentMethod, TotalPaid, PaymentDate, InvoiceNo, TotalDiscount, EmployeeID, Vat) VALUES
(1, 'เงินสด', 155.00, NOW(), 1001, 5.00, 1, 7.00),
(2, 'QR PromptPay', 135.00, NOW(), 1002, 0.00, 2, 9.00);

-- Promotion
INSERT INTO Promotion VALUES
(500001, 'โปรต้มยำ+ชาเย็น', 95.00, 'ต้มยำกุ้ง+ชาเย็น ราคาพิเศษ', 'img/menu/promo_tomyumtea.jpg'),
(500002, 'ข้าวผัดกุ้ง+บัวลอย', 99.00, 'ข้าวผัดกุ้ง+บัวลอยไข่หวาน', 'img/menu/promo_friedricebualoy.jpg');

-- PromotionMenu
INSERT INTO PromotionMenu VALUES
(500001,4), -- ต้มยำกุ้ง
(500001,7), -- ชาเย็น
(500002,1), -- ข้าวผัดกุ้ง
(500002,9); -- บัวลอยไข่หวาน

-- Attendance
INSERT INTO Attendance VALUES
