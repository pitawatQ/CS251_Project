
CREATE DATABASE IF NOT EXISTS restaurant_db;
USE restaurant_db;

-- Table: TableList (โต๊ะ)
CREATE TABLE TableList (
    TableNo INT(3) PRIMARY KEY,
    Status INT(1) NOT NULL COMMENT '0=ว่าง, 1=ไม่ว่าง, 2=เรียกพนักงาน'
);

-- Table: Category
CREATE TABLE Category (
    CategoryID INT(2) AUTO_INCREMENT PRIMARY KEY,
    CName VARCHAR(50)
);

-- Table: Menu
CREATE TABLE Menu (
    MenuID INT(3) AUTO_INCREMENT PRIMARY KEY,
    CategoryID INT(2),
    Name VARCHAR(50),
    Price DECIMAL(6,2),
    Status BOOLEAN,
    MenuDes VARCHAR(100),
    Picture VARCHAR(100),
    FOREIGN KEY (CategoryID) REFERENCES Category(CategoryID)
);

-- Table: Employee
CREATE TABLE Employee (
    EmployeeID INT(6) AUTO_INCREMENT PRIMARY KEY,
    FName VARCHAR(50),
    LName VARCHAR(50),
    Password VARCHAR(12),
    Role VARCHAR(50),
    StartDate DATE,
    Phone CHAR(10),
    Email VARCHAR(50)
);

-- Table: Orders
CREATE TABLE Orders (
    OrderID INT(6) AUTO_INCREMENT PRIMARY KEY,
    EmployeeID INT(6),
    TableNo INT(3),
    OrderTime TIMESTAMP,
    Status INT(1),
    FOREIGN KEY (EmployeeID) REFERENCES Employee(EmployeeID),
    FOREIGN KEY (TableNo) REFERENCES TableList(TableNo)
);

-- Table: OrderDetail
CREATE TABLE OrderDetail (
    OrderID INT(6),
    MenuID INT(3),
    MenuQuntity INT(2),
    UnitPrice DECIMAL(6,2),
    ItemNo INT(3),
    TotalPrice DECIMAL(6,2),
    Description VARCHAR(100),
    PRIMARY KEY (OrderID, ItemNo),
    FOREIGN KEY (OrderID) REFERENCES Orders(OrderID),
    FOREIGN KEY (MenuID) REFERENCES Menu(MenuID)
);

-- Table: Supplier
CREATE TABLE Supplier (
    SupplierID INT(6) AUTO_INCREMENT PRIMARY KEY,
    Sname VARCHAR(50),
    Phone CHAR(10),
    Email VARCHAR(50)
);

-- Table: Stock
CREATE TABLE Stock (
    IngredientID INT(6) PRIMARY KEY,
    SupplierID INT(6),
    IngredientName VARCHAR(50),
    Quantity DECIMAL(6,2),
    Unit VARCHAR(20),
    ImportDate DATE,
    Expirationdate DATE,
    LastUpdate DATE,
    FOREIGN KEY (SupplierID) REFERENCES Supplier(SupplierID)
);

-- Table: IngredientUsage
CREATE TABLE IngredientUsage (
    MenuID INT(3),
    IngredientID INT(6),
    QuantityUsed DECIMAL(6,2),
    ErrorRateUsed DECIMAL(3,2),
    PRIMARY KEY (MenuID, IngredientID),
    FOREIGN KEY (MenuID) REFERENCES Menu(MenuID),
    FOREIGN KEY (IngredientID) REFERENCES Stock(IngredientID)
);

-- Table: Payment
CREATE TABLE Payment (
    PaymentID INT(6) AUTO_INCREMENT PRIMARY KEY,
    OrderID INT(6),
    PaymentMethod VARCHAR(50),
    TotalPaid DECIMAL(6,2),
    PaymentDate TIMESTAMP,
    InvoiceNo INT(6),
    TotalDiscount DECIMAL(6,2),
    EmployeeID INT(6),
    Vat DECIMAL(6,2),
    FOREIGN KEY (OrderID) REFERENCES Orders(OrderID),
    FOREIGN KEY (EmployeeID) REFERENCES Employee(EmployeeID)
);

-- Table: Promotion
CREATE TABLE Promotion (
    PromotionID INT(6) PRIMARY KEY,
    PromotionName VARCHAR(50),
    PromotionPrice DECIMAL(6,2),
    PromotionDes VARCHAR(100),
    Picture VARCHAR(100)
);

-- Table: PromotionMenu
CREATE TABLE PromotionMenu (
    PromotionID INT(6),
    MenuID INT(3),
    PRIMARY KEY (PromotionID, MenuID),
    FOREIGN KEY (PromotionID) REFERENCES Promotion(PromotionID),
    FOREIGN KEY (MenuID) REFERENCES Menu(MenuID)
);

-- Table: Nutrition
CREATE TABLE Nutrition (
    MenuID INT(3),
    Nutrition VARCHAR(50),
    Quantity DECIMAL(6,2),
    Unit VARCHAR(20),
    PRIMARY KEY (MenuID, Nutrition),
    FOREIGN KEY (MenuID) REFERENCES Menu(MenuID)
);

-- Table: Attendance
CREATE TABLE Attendance (
    EmployeeID INT(6),
    WorkDate DATE,
    ClockInTime TIME,
    ClockOutTime TIME,
    PRIMARY KEY (EmployeeID, WorkDate),
    FOREIGN KEY (EmployeeID) REFERENCES Employee(EmployeeID)
);
