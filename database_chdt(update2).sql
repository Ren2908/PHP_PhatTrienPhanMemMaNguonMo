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
('NCC1', 'Samsung VN', 'Hà Nội', '0241234567', 'contact@samsung.vn'),
('NCC2', 'Apple VN', 'TP HCM', '0287654321', 'contact@apple.vn'),
('NCC3', 'Sony VN', 'Đà Nẵng', '0236123456', 'contact@sony.vn'),
('NCC4', 'Xiaomi VN', 'Hà Nội', '0249876543', 'contact@xiaomi.vn'),
('NCC5', 'Oppo VN', 'TP HCM', '0282233445', 'contact@oppo.vn'),
('NCC6', 'LG VN', 'Hải Phòng', '0225666777', 'contact@lg.vn'),
('NCC7', 'Asus VN', 'Đà Nẵng', '0236789123', 'contact@asus.vn'),
('NCC8', 'Acer VN', 'Cần Thơ', '0292388888', 'contact@acer.vn'),
('NCC9', 'Dell VN', 'TP HCM', '0285554444', 'contact@dell.vn'),
('NCC10', 'HP VN', 'Hà Nội', '0249988776', 'contact@hp.vn');

-- ===============================
-- Bảng loại sản phẩm
-- ===============================
CREATE TABLE loai_san_pham (
    Ma_loai VARCHAR(10) PRIMARY KEY,
    Ten_loai VARCHAR(100) NOT NULL
);

INSERT INTO loai_san_pham VALUES
('L1', 'Điện thoại'),
('L2', 'Laptop'),
('L3', 'Tai nghe'),
('L4', 'Tivi'),
('L5', 'Máy tính bảng'),
('L6', 'Đồng hồ thông minh'),
('L7', 'Loa Bluetooth'),
('L8', 'Chuột máy tính'),
('L9', 'Bàn phím'),
('L10', 'Phụ kiện');

-- ===============================
-- Bảng sản phẩm
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
    Ngay_tao DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (Ma_loai) REFERENCES loai_san_pham(Ma_loai)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (Ma_nha_cung_cap) REFERENCES nha_cung_cap(Ma_nha_cung_cap)
        ON DELETE CASCADE ON UPDATE CASCADE
);

INSERT INTO san_pham VALUES
('SP1','Samsung Galaxy S23','L1',50,25000000,'NCC1','Điện thoại cao cấp Samsung','test.jpg',NOW()),
('SP2','iPhone 15','L1',30,30000000,'NCC2','Điện thoại Apple mới nhất','test.jpg',NOW()),
('SP3','Sony WH-1000XM5','L3',40,7000000,'NCC3','Tai nghe chống ồn Sony','test.jpg',NOW()),
('SP4','MacBook Pro 16','L2',20,55000000,'NCC2','Laptop cao cấp Apple','test.jpg',NOW()),
('SP5','Sony Bravia 55inch','L4',10,22000000,'NCC3','Tivi 4K Sony','test.jpg',NOW()),
('SP6','Xiaomi 13 Pro','L1',60,19000000,'NCC4','Điện thoại chụp ảnh đẹp','test.jpg',NOW()),
('SP7','Oppo Find X7','L1',45,21000000,'NCC5','Flagship Oppo hiệu năng cao','test.jpg',NOW()),
('SP8','Dell XPS 13','L2',25,32000000,'NCC9','Laptop mỏng nhẹ, pin tốt','test.jpg',NOW()),
('SP9','Asus ROG Strix','L2',15,45000000,'NCC7','Laptop gaming mạnh mẽ','test.jpg',NOW()),
('SP10','LG OLED 65inch','L4',12,37000000,'NCC6','Tivi OLED hiển thị cực nét','test.jpg',NOW());

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
('KH1','Nguyễn Văn A',1,'Hà Nội','0912345678','a.nguyen@gmail.com'),
('KH2','Trần Thị B',0,'TP HCM','0987654321','b.tran@yahoo.com'),
('KH3','Lê Văn C',1,'Đà Nẵng','0932123456','c.le@gmail.com'),
('KH4','Phạm Minh D',1,'Hải Phòng','0977123456','d.pham@gmail.com'),
('KH5','Nguyễn Thị E',0,'Cần Thơ','0909123123','e.nguyen@gmail.com'),
('KH6','Vũ Quốc F',1,'Huế','0911333444','f.vu@gmail.com'),
('KH7','Bùi Thị G',0,'Nha Trang','0966777888','g.bui@gmail.com'),
('KH8','Phan Văn H',1,'Đồng Nai','0933444555','h.phan@gmail.com'),
('KH9','Đỗ Thị I',0,'TP HCM','0988111222','i.do@gmail.com'),
('KH10','Lý Văn K',1,'Hà Nội','0911999888','k.ly@gmail.com');

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
('NV1','Phạm Văn D',1,'Hà Nội','0911222333','Quản lý'),
('NV2','Hoàng Thị E',0,'TP HCM','0988112233','Nhân viên bán hàng'),
('NV3','Lê Văn F',1,'Đà Nẵng','0911333555','Kế toán'),
('NV4','Nguyễn Thị G',0,'Hà Nội','0922333444','Nhân viên kho'),
('NV5','Trần Văn H',1,'TP HCM','0933555666','Nhân viên bán hàng'),
('NV6','Phạm Thị I',0,'Đà Nẵng','0944777888','Lễ tân'),
('NV7','Bùi Văn K',1,'Huế','0955666777','Nhân viên giao hàng'),
('NV8','Vũ Thị L',0,'Cần Thơ','0966777889','Nhân viên CSKH'),
('NV9','Nguyễn Văn M',1,'Hải Phòng','0977888999','Quản lý chi nhánh'),
('NV10','Đoàn Thị N',0,'Nha Trang','0988999000','Nhân viên bán hàng');

-- ===============================
-- Bảng hóa đơn
-- ===============================
CREATE TABLE hoa_don (
    Ma_hoa_don VARCHAR(10) PRIMARY KEY,
    Ma_khach_hang VARCHAR(10),
    Ma_nhan_vien VARCHAR(10),
    Ngay_lap DATE NOT NULL,
    Tong_tien DECIMAL(12,2) NOT NULL,
    FOREIGN KEY (Ma_khach_hang) REFERENCES khach_hang(Ma_khach_hang)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (Ma_nhan_vien) REFERENCES nhan_vien(Ma_nhan_vien)
        ON DELETE CASCADE ON UPDATE CASCADE
);

INSERT INTO hoa_don VALUES
('HD1','KH1','NV1','2025-11-01',32000000),
('HD2','KH2','NV2','2025-11-02',30000000),
('HD3','KH3','NV3','2025-11-03',19000000),
('HD4','KH4','NV4','2025-11-04',45000000),
('HD5','KH5','NV5','2025-11-05',22000000),
('HD6','KH6','NV6','2025-11-06',55000000),
('HD7','KH7','NV7','2025-11-07',21000000),
('HD8','KH8','NV8','2025-11-08',37000000),
('HD9','KH9','NV9','2025-11-09',32000000),
('HD10','KH10','NV10','2025-11-10',7000000);

-- ===============================
-- Bảng chi tiết hóa đơn
-- ===============================
CREATE TABLE chi_tiet_hoa_don (
    Ma_hoa_don VARCHAR(10),
    Ma_san_pham VARCHAR(10),
    So_luong INT NOT NULL,
    Don_gia DECIMAL(10,2) NOT NULL,
    PRIMARY KEY (Ma_hoa_don, Ma_san_pham),
    FOREIGN KEY (Ma_hoa_don) REFERENCES hoa_don(Ma_hoa_don)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (Ma_san_pham) REFERENCES san_pham(Ma_san_pham)
        ON DELETE CASCADE ON UPDATE CASCADE
);

INSERT INTO chi_tiet_hoa_don VALUES
('HD1','SP1',1,25000000),
('HD1','SP3',1,7000000),
('HD2','SP2',1,30000000),
('HD3','SP6',1,19000000),
('HD4','SP9',1,45000000),
('HD5','SP5',1,22000000),
('HD6','SP4',1,55000000),
('HD7','SP7',1,21000000),
('HD8','SP10',1,37000000),
('HD9','SP8',1,32000000);

CREATE TABLE quyen (
    Ma_quyen VARCHAR(10) PRIMARY KEY,
    Ten_quyen VARCHAR(50) NOT NULL,
    Mo_ta VARCHAR(200)
);

INSERT INTO quyen VALUES
('Q1', 'Admin', 'Quản trị hệ thống, có toàn quyền'),
('Q2', 'NhanVien', 'Nhân viên bán hàng, quản lý hóa đơn, sản phẩm'),
('Q3', 'KhachHang', 'Khách hàng, có thể xem và mua hàng');


CREATE TABLE tai_khoan (
    Ten_dang_nhap VARCHAR(50) PRIMARY KEY,
    Mat_khau VARCHAR(255) NOT NULL,  -- lưu password đã mã hóa (bcrypt, sha256, ...)
    Ma_quyen VARCHAR(10),             -- liên kết tới bảng quyền
    Ma_nhan_vien VARCHAR(10),         -- nếu tài khoản thuộc về nhân viên
    Ma_khach_hang VARCHAR(10),        -- nếu tài khoản thuộc về khách hàng
    Trang_thai TINYINT(1) DEFAULT 1,  -- 1: hoạt động, 0: bị khóa
    Ngay_tao DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (Ma_quyen) REFERENCES quyen(Ma_quyen)
        ON DELETE SET NULL ON UPDATE CASCADE,
    FOREIGN KEY (Ma_nhan_vien) REFERENCES nhan_vien(Ma_nhan_vien)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (Ma_khach_hang) REFERENCES khach_hang(Ma_khach_hang)
        ON DELETE CASCADE ON UPDATE CASCADE
);
INSERT INTO tai_khoan VALUES
('admin', '1', 'Q1', 'NV1', NULL, 1, NOW()),
('nv_hoang', '1', 'Q2', 'NV2', NULL, 1, NOW()),
('kh_nguyen', '1', 'Q3', NULL, 'KH1', 1, NOW());

