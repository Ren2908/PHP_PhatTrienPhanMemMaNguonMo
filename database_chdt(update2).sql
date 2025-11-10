-- ===============================
-- Database: CuaHangDienTu
-- ===============================

DROP DATABASE IF EXISTS CuaHangDienTu;
CREATE DATABASE CuaHangDienTu;
USE CuaHangDienTu;

-- ===============================
-- Bảng nhà cung cấp
-- ===============================
CREATE TABLE nha_cung_cap (
    Ma_nha_cung_cap VARCHAR(10) PRIMARY KEY,
    Ten_nha_cung_cap VARCHAR(100) NOT NULL,
    Dia_chi VARCHAR(200),
    Dien_thoai VARCHAR(20),
    Email VARCHAR(100)
);

INSERT INTO nha_cung_cap VALUES
('NCC001', 'Samsung VN', 'Hà Nội', '0241234567', 'contact@samsung.vn'),
('NCC002', 'Apple VN', 'TP HCM', '0287654321', 'contact@apple.vn'),
('NCC003', 'Sony VN', 'Đà Nẵng', '0236123456', 'contact@sony.vn');

-- ===============================
-- Bảng loại sản phẩm
-- ===============================
CREATE TABLE loai_san_pham (
    Ma_loai VARCHAR(10) PRIMARY KEY,
    Ten_loai VARCHAR(100) NOT NULL
);

INSERT INTO loai_san_pham VALUES
('L001', 'Điện thoại'),
('L002', 'Laptop'),
('L003', 'Tai nghe'),
('L004', 'Tivi');

-- ===============================
-- Bảng sản phẩm (đã thêm cột Hinh_anh)
-- ===============================
CREATE TABLE san_pham (
    Ma_san_pham VARCHAR(10) PRIMARY KEY,
    Ten_san_pham VARCHAR(100) NOT NULL,
    Ma_loai VARCHAR(10),
    So_luong INT DEFAULT 0,
    Don_gia DECIMAL(10,2) NOT NULL,
    Ma_nha_cung_cap VARCHAR(10),
    Mo_ta TEXT,
    Hinh_anh VARCHAR(255),
    FOREIGN KEY (Ma_loai) REFERENCES loai_san_pham(Ma_loai),
    FOREIGN KEY (Ma_nha_cung_cap) REFERENCES nha_cung_cap(Ma_nha_cung_cap)
);

INSERT INTO san_pham VALUES
('SP1','Samsung Galaxy S23','L001',50,25000000,'NCC001','Điện thoại cao cấp Samsung','test.jpg'),
('SP2','iPhone 15','L001',30,30000000,'NCC002','Điện thoại Apple mới nhất','test.jpg'),
('SP3','Sony WH-1000XM5','L003',40,7000000,'NCC003','Tai nghe chống ồn Sony','test.jpg'),
('SP4','MacBook Pro 16','L002',20,55000000,'NCC002','Laptop cao cấp Apple','test.jpg'),
('SP5','Sony Bravia 55inch','L004',10,22000000,'NCC003','Tivi 4K Sony','test.jpg');

-- ===============================
-- Bảng khách hàng
-- ===============================
CREATE TABLE khach_hang (
    Ma_khach_hang VARCHAR(10) PRIMARY KEY,
    Ten_khach_hang VARCHAR(100) NOT NULL,
    Phai TINYINT(1),
    Dia_chi VARCHAR(200),
    Dien_thoai VARCHAR(20),
    Email VARCHAR(100)
);

INSERT INTO khach_hang VALUES
('KH001','Nguyễn Văn A',1,'Hà Nội','0912345678','a.nguyen@gmail.com'),
('KH002','Trần Thị B',0,'TP HCM','0987654321','b.tran@yahoo.com'),
('KH003','Lê Văn C',1,'Đà Nẵng','0932123456','c.le@gmail.com');

-- ===============================
-- Bảng nhân viên
-- ===============================
CREATE TABLE nhan_vien (
    Ma_nhan_vien VARCHAR(10) PRIMARY KEY,
    Ten_nhan_vien VARCHAR(100) NOT NULL,
    Phai TINYINT(1),
    Dia_chi VARCHAR(200),
    Dien_thoai VARCHAR(20),
    Chuc_vu VARCHAR(50)
);

INSERT INTO nhan_vien VALUES
('NV001','Phạm Văn D',1,'Hà Nội','0911222333','Quản lý'),
('NV002','Hoàng Thị E',0,'TP HCM','0988112233','Nhân viên bán hàng');

-- ===============================
-- Bảng hóa đơn
-- ===============================
CREATE TABLE hoa_don (
    Ma_hoa_don VARCHAR(10) PRIMARY KEY,
    Ma_khach_hang VARCHAR(10),
    Ma_nhan_vien VARCHAR(10),
    Ngay_lap DATE NOT NULL,
    Tong_tien DECIMAL(12,2) NOT NULL,
    FOREIGN KEY (Ma_khach_hang) REFERENCES khach_hang(Ma_khach_hang),
    FOREIGN KEY (Ma_nhan_vien) REFERENCES nhan_vien(Ma_nhan_vien)
);

INSERT INTO hoa_don VALUES
('HD001','KH001','NV001','2025-11-01',32000000),
('HD002','KH002','NV002','2025-11-02',30000000);

-- ===============================
-- Bảng chi tiết hóa đơn
-- ===============================
CREATE TABLE chi_tiet_hoa_don (
    Ma_hoa_don VARCHAR(10),
    Ma_san_pham VARCHAR(10),
    So_luong INT NOT NULL,
    Don_gia DECIMAL(10,2) NOT NULL,
    PRIMARY KEY (Ma_hoa_don, Ma_san_pham),
    FOREIGN KEY (Ma_hoa_don) REFERENCES hoa_don(Ma_hoa_don),
    FOREIGN KEY (Ma_san_pham) REFERENCES san_pham(Ma_san_pham)
);

INSERT INTO chi_tiet_hoa_don VALUES
('HD001','SP1',1,25000000),
('HD001','SP3',1,7000000),
('HD002','SP2',1,30000000);
