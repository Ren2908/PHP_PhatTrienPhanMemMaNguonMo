-- ===============================
-- Database: CuaHangDienTu
-- ===============================


DROP DATABASE IF EXISTS CuaHangDienTu;
CREATE DATABASE CuaHangDienTu;
USE CuaHangDienTu;

-- Màu sắc
CREATE TABLE mau_sac (
    Ma_mau VARCHAR(50) PRIMARY KEY,
    Ten_mau VARCHAR(50) NOT NULL
);

-- Nguồn gốc xuất xứ
CREATE TABLE xuat_xu (
    Ma_xuat_xu VARCHAR(50) PRIMARY KEY,
    Ten_xuat_xu VARCHAR(100) NOT NULL
);

-- Bảo hành
CREATE TABLE bao_hanh (
    Ma_bao_hanh VARCHAR(50) PRIMARY KEY,
    Thoi_gian INT, -- tháng
    Dieu_kien TEXT
);

-- ===============================
-- Bảng nhà cung cấp
-- ===============================
CREATE TABLE nha_cung_cap (
    Ma_nha_cung_cap VARCHAR(50) PRIMARY KEY,
    Ten_nha_cung_cap VARCHAR(500) NOT NULL,
    Dia_chi VARCHAR(200),
    Dien_thoai VARCHAR(20),
    Email VARCHAR(500)
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
('NCC50', 'HP VN', 'Hà Nội', '0249988776', 'contact@hp.vn');

-- ===============================
-- Bảng loại sản phẩm
-- ===============================
CREATE TABLE loai_san_pham (
    Ma_loai VARCHAR(50) PRIMARY KEY,
    Ten_loai VARCHAR(500) NOT NULL
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
('L50', 'Phụ kiện');

-- ===============================
-- Bảng sản phẩm
-- ===============================
CREATE TABLE san_pham (
    Ma_san_pham VARCHAR(50) PRIMARY KEY,
    Ten_san_pham VARCHAR(500) NOT NULL,
    Ma_loai VARCHAR(50),
    Ma_nha_cung_cap VARCHAR(50),
    Ma_mau VARCHAR(50),
    Ma_xuat_xu VARCHAR(50),
    Ma_bao_hanh VARCHAR(50),
    So_luong INT DEFAULT 0,
    Don_gia DECIMAL(12,2) NOT NULL,
    Mo_ta TEXT,
    Cau_hinh TEXT,                 -- thông tin đặc thù gộp chung
    Hinh_anh VARCHAR(255),
    Ngay_tao DATETIME DEFAULT CURRENT_TIMESTAMP,
    Trang_thai TINYINT(1) DEFAULT 1, -- 1: đang bán, 0: ngừng bán
    FOREIGN KEY (Ma_loai) REFERENCES loai_san_pham(Ma_loai)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (Ma_nha_cung_cap) REFERENCES nha_cung_cap(Ma_nha_cung_cap)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (Ma_mau) REFERENCES mau_sac(Ma_mau)
        ON DELETE SET NULL ON UPDATE CASCADE,
    FOREIGN KEY (Ma_xuat_xu) REFERENCES xuat_xu(Ma_xuat_xu)
        ON DELETE SET NULL ON UPDATE CASCADE,
    FOREIGN KEY (Ma_bao_hanh) REFERENCES bao_hanh(Ma_bao_hanh)
        ON DELETE SET NULL ON UPDATE CASCADE   
);



INSERT INTO mau_sac VALUES
('M1', 'Đen'),
('M2', 'Trắng'),
('M3', 'Xanh'),
('M4', 'Đỏ');

INSERT INTO xuat_xu VALUES
('X1', 'Hàn Quốc'),
('X2', 'Mỹ'),
('X3', 'Nhật Bản'),
('X4', 'Trung Quốc');

INSERT INTO bao_hanh VALUES
('BH1', 12, 'Bảo hành chính hãng 1 năm'),
('BH2', 24, 'Bảo hành chính hãng 2 năm'),
('BH3', 6, 'Bảo hành 6 tháng'),
('BH4', 36, 'Bảo hành 3 năm');

INSERT INTO san_pham (
    Ma_san_pham, Ten_san_pham, Ma_loai, Ma_nha_cung_cap, Ma_mau, Ma_xuat_xu, Ma_bao_hanh,
    So_luong, Don_gia, Mo_ta, Cau_hinh, Hinh_anh, Ngay_tao, Trang_thai
) VALUES
('SP1','Samsung Galaxy S23','L1','NCC1','M1','X1','BH1',50,25000000,
 'Điện thoại cao cấp Samsung',
 'Màn hình 6.1\" AMOLED 120Hz; Snapdragon 8 Gen 2; RAM 8GB; ROM 256GB; Camera 50MP + 12MP + 10MP; Pin 3900mAh; Android 13',
 'Samsung Galaxy S23.webp',NOW(),1),

('SP2','iPhone 15','L1','NCC2','M2','X2','BH2',30,30000000,
 'Điện thoại Apple mới nhất',
 'Màn hình 6.1\" Super Retina XDR; Chip Apple A16 Bionic; RAM 6GB; ROM 128GB; Camera 48MP; Pin 3349mAh; iOS 17',
 'iPhone 15.jpg',NOW(),1),

('SP3','Sony WH-5000XM5','L3','NCC3','M3','X3','BH3',40,7000000,
 'Tai nghe chống ồn Sony',
 'Tai nghe chụp tai; Driver 30mm; Chống ồn ANC; Bluetooth 5.2; Pin 30 giờ; Sạc USB-C; Hỗ trợ LDAC',
 'Sony WH-1000XM5.png',NOW(),1),

('SP4','MacBook Pro 16','L2','NCC2','M2','X2','BH2',20,55000000,
 'Laptop cao cấp Apple',
 'Màn hình 16\" Liquid Retina XDR; Apple M2 Pro; RAM 16GB; SSD 512GB; GPU 19-core; Pin 100Wh; macOS Ventura',
 'MacBook Pro 16.jpg',NOW(),1),

('SP5','Sony Bravia 55inch','L4','NCC3','M1','X3','BH4',50,22000000,
 'Tivi 4K Sony',
 'TV 55\" 4K HDR; Công nghệ Triluminos Pro; Tần số quét 120Hz; Android TV; Dolby Vision & Atmos; 4 cổng HDMI',
 'Sony Bravia 55inch.webp',NOW(),1),

('SP6','Xiaomi 13 Pro','L1','NCC4','M4','X4','BH1',60,19000000,
 'Điện thoại chụp ảnh đẹp',
 'Màn hình 6.73\" AMOLED 120Hz; Snapdragon 8 Gen 2; RAM 12GB; ROM 256GB; Camera Leica 50MP; Pin 4820mAh; Sạc 120W',
 'Xiaomi 13 Pro.jpg',NOW(),1),

('SP7','Oppo Find X7','L1','NCC5','M1','X4','BH1',45,25000000,
 'Flagship Oppo hiệu năng cao',
 'Màn hình 6.78\" AMOLED 120Hz; MediaTek Dimensity 9300; RAM 12GB; ROM 256GB; Camera 50MP; Pin 5000mAh; Sạc 100W',
 'Oppo Find X7.webp',NOW(),1),

('SP8','Dell XPS 13','L2','NCC9','M2','X1','BH2',25,32000000,
 'Laptop mỏng nhẹ, pin tốt',
 'Màn hình 13.4\" FHD+; Intel Core i7-1355U; RAM 16GB; SSD 512GB; Intel Iris Xe; Pin 52Wh; Windows 11',
 'Dell XPS 13.jpg',NOW(),1),

('SP9','Asus ROG Strix','L2','NCC7','M3','X3','BH2',15,45000000,
 'Laptop gaming mạnh mẽ',
 'Màn hình 15.6\" QHD 165Hz; AMD Ryzen 9 7945HX; RAM 32GB; SSD 1TB; GPU RTX 4070; Bàn phím RGB; Windows 11',
 'Asus ROG Strix.jpg',NOW(),1),

('SP10','LG OLED 65inch','L4','NCC6','M1','X4','BH4',12,37000000,
 'Tivi OLED hiển thị cực nét',
 'TV OLED 65\" 4K; Công nghệ OLED evo; Tần số 120Hz; WebOS; Dolby Vision IQ; Dolby Atmos; HDMI 2.1 x 4',
 'LG OLED 65inch.jpg',NOW(),1);


-- ===============================
-- Bảng khách hàng
-- ===============================
CREATE TABLE khach_hang (
    Ma_khach_hang VARCHAR(50) PRIMARY KEY,
    Ten_khach_hang VARCHAR(500) NOT NULL,
    Phai TINYINT(1), -- 0 = nữ, 1 = nam
    Dia_chi VARCHAR(200),
    Dien_thoai VARCHAR(20),
    Email VARCHAR(500)
);

INSERT INTO khach_hang VALUES
('KH691b06a2', 'Phạm Bá Dương', 1, 'Đại học nha trang', '0389851543', 'duong.pb.64.cntt@ntu.edu.vn');


-- ===============================
-- Bảng nhân viên
-- ===============================
CREATE TABLE nhan_vien (
    Ma_nhan_vien VARCHAR(50) PRIMARY KEY,
    Ten_nhan_vien VARCHAR(500) NOT NULL,
    Phai TINYINT(1),
    Dia_chi VARCHAR(200),
    Dien_thoai VARCHAR(20),
    Chuc_vu VARCHAR(50)
);

INSERT INTO nhan_vien VALUES
('NV691b0e11465b5', 'Phạm Bá Dương(admin)', 1, 'Nha trang', '0911222333', 'Quản lý'),
('NV691b067a', 'Phạm Bá Dương(nhân viên)', 1, 'Nha Trang', '0911222334', 'Nhân viên');


-- ===============================
-- Bảng hóa đơn
-- ===============================
CREATE TABLE hoa_don (
    Ma_hoa_don VARCHAR(50) PRIMARY KEY,
    Ma_khach_hang VARCHAR(50),
    Ma_nhan_vien VARCHAR(50),
    Ngay_tao DATETIME DEFAULT CURRENT_TIMESTAMP,
    Tong_tien DECIMAL(12,2) NOT NULL,
	Trang_thai TINYINT(1) DEFAULT 0, -- 0 = Chờ xác nhận, 1 = Đã xác nhận, 2 = Đã giao cho vận chuyển, 3 = đã hoàn thành, 4 = đã huỷ 
	Loai_don_hang TINYINT(1) DEFAULT 0, -- 0 = offline, 1 = online
    FOREIGN KEY (Ma_khach_hang) REFERENCES khach_hang(Ma_khach_hang)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (Ma_nhan_vien) REFERENCES nhan_vien(Ma_nhan_vien)
        ON DELETE CASCADE ON UPDATE CASCADE
);
INSERT INTO `hoa_don` (`Ma_hoa_don`, `Ma_khach_hang`, `Ma_nhan_vien`, `Ngay_tao`, `Tong_tien`, `Trang_thai`, `Loai_don_hang`) VALUES
('HD1', 'KH691b06a2', 'NV691b067a', '2025-11-18 04:12:02', 60000000.00, 3, 0),
('HD2', 'KH691b06a2', 'NV691b067a', '2025-11-18 04:16:03', 262000000.00, 3, 0),
('HD3', 'KH691b06a2', 'NV691b067a', '2025-11-18 04:16:56', 144000000.00, 3, 0),
('HD4', 'KH691b06a2', 'NV691b067a', '2025-11-18 04:18:14', 184000000.00, 3, 0),
('HD5', 'KH691b06a2', 'NV691b067a', '2025-11-18 04:18:49', 150000000.00, 3, 0);
-- ===============================
-- Bảng chi tiết hóa đơn
-- ===============================
CREATE TABLE chi_tiet_hoa_don (
    Ma_hoa_don VARCHAR(50),
    Ma_san_pham VARCHAR(50),
    So_luong INT NOT NULL,
    Don_gia DECIMAL(50,2) NOT NULL,
    PRIMARY KEY (Ma_hoa_don, Ma_san_pham),
    FOREIGN KEY (Ma_hoa_don) REFERENCES hoa_don(Ma_hoa_don)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (Ma_san_pham) REFERENCES san_pham(Ma_san_pham)
        ON DELETE CASCADE ON UPDATE CASCADE
);
INSERT INTO `chi_tiet_hoa_don` (`Ma_hoa_don`, `Ma_san_pham`, `So_luong`, `Don_gia`) VALUES
('HD1', 'SP2', 2, 30000000.00),
('HD2', 'SP1', 2, 25000000.00),
('HD2', 'SP3', 2, 7000000.00),
('HD2', 'SP5', 2, 22000000.00),
('HD2', 'SP8', 2, 32000000.00),
('HD2', 'SP9', 2, 45000000.00),
('HD3', 'SP6', 2, 19000000.00),
('HD3', 'SP7', 2, 25000000.00),
('HD3', 'SP8', 2, 32000000.00),
('HD4', 'SP1', 2, 25000000.00),
('HD4', 'SP10', 2, 37000000.00),
('HD4', 'SP2', 2, 30000000.00),
('HD5', 'SP4', 2, 55000000.00);
CREATE TABLE quyen (
    Ma_quyen VARCHAR(50) PRIMARY KEY,
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
    Ma_quyen VARCHAR(50),             -- liên kết tới bảng quyền
    Ma_nhan_vien VARCHAR(50),         -- nếu tài khoản thuộc về nhân viên
    Ma_khach_hang VARCHAR(50),        -- nếu tài khoản thuộc về khách hàng
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
('admin', '$2y$10$BI6BJHBN.2DbpnQ/Qowo4OZ0joSeEzaOeciShgxR5qGyTVATfT5/K', 'Q1', 'NV691b0e11465b5', NULL, 1, NOW()),
('kh', '$2y$10$T5z9tHBJhn8LNCYK3iEDde1umeHIFteqRy6ApVBf9Emn44IGvNWhK', 'Q3', NULL, 'KH691b06a2', 1, NOW()),
('nv', '$2y$10$sZ7.N27TZS38gmYzyj1v5eisLSSQPotawK41wtpKbzd3vTU0CoBa6', 'Q2', 'NV691b067a', NULL, 1, NOW());



CREATE TABLE `gio_hang` (
  `id` int(11) NOT NULL,
  `ma_khach_hang` varchar(50) NOT NULL,
  `ma_san_pham` varchar(50) NOT NULL,
  `so_luong` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DELIMITER //

CREATE TRIGGER trg_after_insert_cthd
AFTER INSERT ON chi_tiet_hoa_don
FOR EACH ROW
BEGIN
    UPDATE hoa_don
    SET Tong_tien = (
        SELECT SUM(So_luong * Don_gia)
        FROM chi_tiet_hoa_don
        WHERE Ma_hoa_don = NEW.Ma_hoa_don
    )
    WHERE Ma_hoa_don = NEW.Ma_hoa_don;
END;
//

DELIMITER ;
