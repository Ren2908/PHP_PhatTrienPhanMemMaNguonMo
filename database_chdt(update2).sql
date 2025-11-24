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
    So_luong INT DEFAULT 0,
    Don_gia DECIMAL(50,2) NOT NULL,
    Ma_nha_cung_cap VARCHAR(50),
    Mo_ta TEXT,
    Thong_so_ky_thuat TEXT,
    Thoi_diem_ra_mat DATETIME,
    Tinh_trang TEXT,
    Hinh_anh VARCHAR(255),
    Ngay_tao DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (Ma_loai) REFERENCES loai_san_pham(Ma_loai)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (Ma_nha_cung_cap) REFERENCES nha_cung_cap(Ma_nha_cung_cap)
        ON DELETE CASCADE ON UPDATE CASCADE
);

INSERT INTO san_pham VALUES
('SP1','Samsung Galaxy S23','L1',50,25000000,'NCC1','Điện thoại cao cấp Samsung','Màn hình: Dynamic AMOLED 2X, 120Hz, HDR10+, 1200 nits (HBM), 1750 nits (tối đa), 6.1 inches, Full HD+ (1080 x 2340 pixels), tỉ lệ 20:9, Corning Gorilla Glass Victus 2, Always-on display\nHệ điều hành: Android 13, được lên Android 14, One UI 6.1\nCamera sau: 50 MP f/1.8 24mm (góc rộng) Dual Pixel PDAF OIS, 10 MP f/2.4 70mm (telephoto) PDAF OIS zoom quang 3x, 12 MP f/2.2 13mm 120° (góc siêu rộng) Dual Pixel PDAF Super Steady video, Quay phim: 8K@24/30fps, 4K@30/60fps, 1080p@30/60/240fps, 720p@960fps, HDR10+, stereo sound, gyro-EIS, Camera trước: 12 MP f/2.2 26mm (góc rộng) Dual Pixel PDAF, Dual video call, Auto-HDR, HDR10+, Quay phim: 4K@30/60fps, 1080p@30fps\nCPU: Qualcomm SM8550-AC Snapdragon 8 Gen 2 (4 nm), 8 nhân (1x3.36 GHz, 2x2.8 GHz, 2x2.8 GHz, 3x2.0 GHz)\nGPU: Adreno 740\nRAM: 8GB\nBộ nhớ trong: 128GB (UFS 3.1), 256-512GB (UFS 4.0)\nThẻ SIM: 2 SIM, Nano SIM/eSIM\nDung lượng pin: Li-Ion 3900 mAh\nSạc nhanh 25W, 50% trong 30 phút (quảng cáo), Sạc không dây 15W, Sạc ngược không dây 4.5W\nThiết kế: Khung nhôm bọc thép, 2 mặt kính Gorilla Glass Victus 2, Chống nước/bụi IP68','2023-2-2', 'Mới','Samsung Galaxy S23.webp',NOW()),
('SP2','iPhone 15','L1',30,30000000,'NCC2','Điện thoại Apple mới nhất','Màn hình: Super Retina XDR OLED, HDR10, Dolby Vision, 1000 nits (HBM), 2000 nits (tối đa), 6.1 inches, 1179 x 2556 pixels, tỷ lệ 19.5:9, Mật độ điểm ảnh ~461 ppi, Ceramic Shield glass\nHệ điều hành: iOS 17\nCamera sau: 48 MP f/1.6 26mm (góc rộng) dual pixel PDAF sensor-shift OIS, 12 MP f/2.4 13mm 120° (góc siêu rộng), Quay phim: 4K@24/25/30/60fps, 1080p@25/30/60/120/240fps, HDR, Dolby Vision HDR (up to 60fps), Cinematic mode (4K@30fps), stereo sound, Camera trước: 12 MP f/1.9 23mm (góc rộng) PDAFSL 3D (độ sâu/sinh trắc học), HDR, Cinematic mode (4K@30fps), Quay phim: 4K@24/25/30/60fps, 1080p@25/30/60/120fps, gyro-EIS\nCPU: Apple A16 Bionic (4 nm), 6 nhân (2x3.46 GHz, 4x2.02 GHz)\nGPU: Apple GPU (5 lõi đồ họa)\nRAM: 6GB\nBộ nhớ trong: 128-512GB NVMe\nThẻ SIM: Nano SIM và eSIM (Quốc tế), Chỉ eSIM (bản Mỹ), 2 SIM, Nano SIM (Trung Quốc)\nDung lượng pin: Li-Ion x mAh\nSạc nhanh 50% trong 30 phút (QC), Sạc không dây MagSafe 15W, Sạc không dây Qi 7.5W\nThiết kế: Khung nhôm vuông vức, Kính sau Corning-made, Kính trước Ceramic Shield, Thiết kế màn hình Dynamic Island, Kháng nước bụi IP68','2023-9-13','Mới','iPhone 15.jpg',NOW()),
('SP3','Sony WH-1000XM5','L3',40,7000000,'NCC3','Tai nghe chống ồn Sony','Kích thước: Dài 27.62 cm - Rộng 21.67 cm - Cao 7.59 cm\nTrọng lượng: 250 g\nCông nghệ âm thanh: SENSE ENGINE, Bộ xử lý tích hợp V1, DSEE Extreme (tăng cường âm thanh kỹ thuật số), Hi-Res Audio, codec SBC, Active Noise Cancelling, Chống ồn HD QN1, codec LDAC, Ambient Sound, codec AAC, 360 Reality Audio, Adaptiv, Micro: Có\nCổng kết nối: 3.5mm\nThời lượng sử dụng pin: Tắt chống ồn ANC dùng 40 giờ, Bật chống ồn ANC dùng 30 giờ\nPhương thức điều khiển: Cảm ứng chạm\nTính năng khác: Tự động dừng nhạc khi bắt đầu trò chuyện, Điều khiển bằng giọng nói Google Alexa','2022-5-12','Mới','Sony WH-1000XM5.png',NOW()),
('SP4','MacBook Pro 16inch','L2',20,55000000,'NCC2','Laptop cao cấp Apple','Loại card đồ họa: 20 lõi, Neural Engine 16 lõi\nDung lượng RAM: 24GB\nỔ cứng: 512GB\nKích thước màn hình: 16.2 inches\nCông nghệ màn hình: Màn hình Liquid Retina XDR, XDR (Extreme Dynamic Range), Độ sáng XDR 1000 nit toàn màn hình và đỉnh 1600 nit (HDR), 1 tỷ màu, Dải màu rộng P3, True Tone, Hỗ trợ tối đa 2 màn hình ngoài\nPin: Li-Po 100Wh, Xem video trực tuyến 24 giờ, Duyệt web không dây 17 giờ\nHệ điều hành: macOS\nĐộ phân giải màn hình: 3456 x 2234 pixels\nLoại CPU: Apple M4 Pro 14 lõi (10 lõi hiệu năng, 4 lõi tiết kiệm điện)\nCổng giao tiếp: Khe SDXC, HDMI, Jack 3.5mm, MagSafe 3, 3 cổng Thunderbolt 5 (USB-C: Sạc, DisplayPort, Thunderbolt 5 120Gb/s, Thunderbolt 4 40Gb/s, USB 4 40Gb/s)', '2023-10-31','Cũ','MacBook Pro 16.jpg',NOW()),
('SP5','Sony Bravia 3 55inch','L4',50,22000000,'NCC3','Tivi 4K Sony','Độ phân giải: 4K Ultra HD\nHệ điều hành: Google TV\nBộ xử lý: 4K HDR Processor X1\nTrợ lý ảo: Google Assistant hỗ trợ tiếng Việt\nĐiều khiển giọng nói: Không cần remote\nCông nghệ âm thanh: Dolby Atmos\nChiếu màn hình: Chromecast built-in, AirPlay 2\nCông nghệ hình ảnh: 4K X-Reality PRO','2024-30-10','Mới','Sony Bravia 55inch.webp',NOW()),
('SP6','Xiaomi 13 Pro','L1',60,19000000,'NCC4','Điện thoại chụp ảnh đẹp', 'Màn hình: LTPO AMOLED, 1 tỷ màu, 120Hz, Dolby Vision, HDR10+, 1200 nits (HBM), 1900 nits (tối đa)\n6.73 inches, 2K (1440 x 3200 pixels), tỷ lệ 20:9, Corning Gorilla Glass Victus\nHệ điều hành: Android 13, MIUI 14, lên được Android 14, HyperOS\nCamera sau: 50.3 MP f/1.9 (góc rộng, 1.0", Dual Pixel PDAF, Laser AF, OIS), 50 MP (tele, 3.2x zoom quang học, PDAF), 50 MP f/2.2 115˚ (siêu rộng, PDAF)\nQuay 8K@24fps (HDR), 4K@30/60fps (HDR10+), 1080p@30/120/240/960fps, 720p@1920fps, gyro-EIS\nCamera trước: 32 MP f/2.5 (góc rộng)\nQuay 1080p@30/60fps, 720p@120fps, HDR10+\nChipset: Qualcomm Snapdragon 8 Gen 2 (4 nm)\nCPU: 8 nhân (1x3.2 GHz + 2x2.8 GHz + 2x2.8 GHz + 3x2.0 GHz)\nGPU: Adreno 740\nRAM: 8GB hoặc 12GB, LPDDR5X\nBộ nhớ trong: 128GB (UFS 3.1), 256GB/512GB (UFS 4.0)\nSIM: 2 Nano-SIM\nPin: Li-Po 4820 mAh\nSạc: Có dây 120W (100% trong 19 phút), không dây 50W, ngược không dây 10W, PD3.0, QC4\nThiết kế: Khung kim loại, mặt lưng gốm hoặc silicon polymer (da), kính cong Gorilla Glass Victus, chuẩn IP68', '2022-12-11', 'Cũ', 'Xiaomi 13 Pro.jpg',NOW()),
('SP7','Oppo Find X7','L1',45,25000000,'NCC5','Flagship Oppo hiệu năng cao','Màn hình: LTPO AMOLED, 1 tỷ màu, 120Hz, Dolby Vision, HDR10+, 1600 nits (typ), 2300 nits (HBM), 4500 nits (tối đa\n6.78 inches, 1.5K (1264 x 2780 pixels), ~450 ppi\nHệ điều hành: Android 14, ColorOS 15, lên được Android 15\nCamera sau: 50 MP f/1.6 (góc rộng, PDAF, OIS), 64 MP f/2.6 (tele tiềm vọng 3x zoom quang, PDAF, OIS), 50 MP f/2.0 119˚ (siêu rộng, PDAF)\nQuay phim: 4K@30/60fps, 1080p@30/60/240fps, gyro-EIS, HDR, 10-bit video\nCamera trước: 32 MP f/2.4 (góc rộng, PDAF, Panorama)\nQuay phim: 4K@30fps, 1080p@30fps, gyro-EIS\nChipset: MediaTek Dimensity 9300\nCPU: 8 nhân (1x3.25 GHz + 3x2.85 GHz + 4x2.0 GHz)\nGPU: Immortalis-G720 MC12\nRAM: 12GB hoặc 16GB, LPDDR5X\nBộ nhớ trong: 256GB / 512GB / 1TB, UFS 4.0\nSIM: 2 Nano-SIM\nPin: Li-Po 5000 mAh\nSạc: Có dây 100W (100% trong 26 phút)\nThiết kế: Khung nhôm bo cong, mặt lưng da hoặc kính, kính trước Gorilla Glass Victus 2, màn hình cong, cảm biến vân tay dưới màn hình, kháng nước bụi IP65','2024-1-8','Cũ','Oppo Find X7.webp',NOW()),
('SP8','Dell XPS 13 9350','L2',25,32000000,'NCC9','Laptop mỏng nhẹ, pin tốt','Loại card đồ họa: Intel HD Graphics\nDung lượng RAM: 8GB, DDR3 1600 MHz SDRAM\nỔ cứng: SSD 256 GB\nMàn hình: 13.3 inches, InfinityEdge, độ phân giải 3200 x 1800 pixels\nCPU: Intel Core i7-6500U (up to 3.10 GHz, 4 MB cache)\nPin: 4-cell Li-Ion\nHệ điều hành: Windows 10\nCổng giao tiếp: USB','2024-12-30','Mới','Dell XPS 13.jpg',NOW()),
('SP9','Asus ROG Strix G16 G614 2025','L2',15,45000000,'NCC7','Laptop gaming mạnh mẽ','CPU: AMD Ryzen 9 8940HX (16 nhân 32 luồng, cơ bản 2.5GHz, boost tối đa 5.3GHz, 64MB cache)\nRAM: 16GB DDR5-5600MHz (hỗ trợ nâng cấp tối đa 64GB)\nỔ cứng: 1TB PCIe NVMe M.2 SSD Gen 4\nMàn hình: 16 inch, 2.5K (2560 x 1600), IPS chống chói, 165Hz, tỷ lệ 16:10, 400 nits, 100% sRGB, 3ms, DC dimming, NVIDIA G-Sync, NanoEdge, giảm ánh sáng xanh\nCard đồ họa: NVIDIA GeForce RTX 5060 8GB GDDR7\nPin: 90Wh\nTrọng lượng: 2.5 kg\nKết nối không dây: Wi-Fi 6E, Bluetooth 5.3','2025-5-7','Mới','Asus ROG Strix.jpg',NOW()),
('SP50','LG OLED 65inch','L4',12,37000000,'NCC6','Tivi OLED hiển thị cực nét','Kích thước màn hình: 65 inch\nLoại màn hình: OLED\nĐộ phân giải: 4K\nTần số quét: 120Hz\nCông nghệ hình ảnh: HLG, HDR10, Dolby Vision, AI Picture Pro, FilmMaker Mode, 10 chế độ hình ảnh, 4K Expression Enhancer, OLED Color, HDR Expression Enhancer, a9 AI Super Upscaling 4K, Pixel Dimming, AI Brightness\nCông nghệ âm thanh: WISA ready, Dolby Atmos, LG Sound Sync, AI Acoustic Tuning, WOW Orchestra, AI Voice Remastering, TV Sound Mode Share, Clear Voice Pro, a9 AI Sound Pro (Virtual 9.1.2 Up-mix)\nHệ điều hành: WebOS\nTiện ích nổi bật: Tìm kiếm giọng nói tiếng Việt, điều khiển giọng nói không cần remote, chiếu hình từ điện thoại lên TV, điều khiển qua ứng dụng','2025-3-6','Mới','LG OLED 65inch.jpg',NOW());

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
('HD4', 'SP50', 2, 37000000.00),
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
('admin', '$2y$10$lcuJSpEabJBwA0knmE0NCO6D.TQHDXIfUPUAs/nyiTbO7/pzTFXTi', 'Q1', 'NV691b0e11465b5', NULL, 1, NOW()),
('kh', '$2y$50$CorM3VxE6wGssOdNoHfPjOWbbhDbo79/ttSLYzyDD0wLdugf8.ms6', 'Q3', NULL, 'KH691b06a2', 1, NOW()),
('nv', '$2y$50$r5ioJ6pQfOFkTMys3a3TaudE4cx06SMa0GUbrmqhbs/PnP.dt.2OS', 'Q2', 'NV691b067a', NULL, 1, NOW());



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
