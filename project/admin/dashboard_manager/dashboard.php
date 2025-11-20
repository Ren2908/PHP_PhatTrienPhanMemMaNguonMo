<?php
// Doanh thu theo giờ/ngày/tháng (start)
// Doanh thu theo GIỜ (24 giờ trong ngày hôm nay)
$sql_doanhThu_gio = "
SELECT 
    HOUR(hd.Ngay_tao) AS gio,
    SUM(hd.Tong_tien) AS doanh_thu
FROM hoa_don AS hd
WHERE hd.Trang_thai = 3   
    AND DATE(hd.Ngay_tao) = CURDATE()
GROUP BY HOUR(hd.Ngay_tao)
ORDER BY gio ASC
";
$result_doanhThu_gio = mysqli_query($conn, $sql_doanhThu_gio);

// Khởi tạo mảng 24 giờ với giá trị 0
$data_doanhThu_gio = array_fill(0, 24, 0);
while ($row = mysqli_fetch_assoc($result_doanhThu_gio)) {
    $data_doanhThu_gio[(int)$row['gio']] = (float)$row['doanh_thu'];
}
$labels_doanhThu_gio = range(0, 23); // [0, 1, 2, ..., 23]

// Doanh thu theo NGÀY (các ngày trong tháng hiện tại)
$sql_doanhThu_ngay = "
SELECT 
    DAY(hd.Ngay_tao) AS ngay,
    SUM(hd.Tong_tien) AS doanh_thu
FROM hoa_don AS hd
WHERE hd.Trang_thai = 3   
    AND MONTH(hd.Ngay_tao) = MONTH(CURDATE())
    AND YEAR(hd.Ngay_tao) = YEAR(CURDATE())
GROUP BY DAY(hd.Ngay_tao)
ORDER BY ngay ASC
";
$result_doanhThu_ngay = mysqli_query($conn, $sql_doanhThu_ngay);

// Lấy số ngày trong tháng hiện tại
$soNgayTrongThang = date('t');
$data_doanhThu_ngay = array_fill(0, $soNgayTrongThang, 0);
while ($row = mysqli_fetch_assoc($result_doanhThu_ngay)) {
    $data_doanhThu_ngay[(int)$row['ngay'] - 1] = (float)$row['doanh_thu'];
}
$labels_doanhThu_ngay = range(1, $soNgayTrongThang); // [1, 2, 3, ..., 30/31]

// Doanh thu theo THÁNG (12 tháng trong năm hiện tại)
$sql_doanhThu_thang = "
SELECT 
    MONTH(hd.Ngay_tao) AS thang,
    SUM(hd.Tong_tien) AS doanh_thu
FROM hoa_don AS hd
WHERE hd.Trang_thai = 3   
    AND YEAR(hd.Ngay_tao) = YEAR(CURDATE())
GROUP BY MONTH(hd.Ngay_tao)
ORDER BY thang ASC
";
$result_doanhThu_thang = mysqli_query($conn, $sql_doanhThu_thang);

// Khởi tạo mảng 12 tháng với giá trị 0
$data_doanhThu_thang = array_fill(0, 12, 0);
while ($row = mysqli_fetch_assoc($result_doanhThu_thang)) {
    $data_doanhThu_thang[(int)$row['thang'] - 1] = (float)$row['doanh_thu'];
}
$labels_doanhThu_thang = ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'T8', 'T9', 'T10', 'T11', 'T12'];
// Doanh thu theo giờ/ngày/tháng (end)

//Top 5 nhà cung cấp bán ít/nhiều nhất (start)
// TOP 5 bán nhiều nhất
$sql_topNhieu_nhaCungCapSeller = "
SELECT 
    ncc.Ten_nha_cung_cap,
    SUM(ct.So_luong) AS tong_so_luong
FROM chi_tiet_hoa_don AS ct
JOIN san_pham AS sp 
    ON ct.Ma_san_pham = sp.Ma_san_pham
JOIN nha_cung_cap AS ncc
    ON sp.Ma_nha_cung_cap = ncc.Ma_nha_cung_cap
JOIN hoa_don AS hd
    ON ct.Ma_hoa_don = hd.Ma_hoa_don
WHERE hd.Trang_thai = 3   -- chỉ tính đơn hoàn thành
GROUP BY ncc.Ma_nha_cung_cap, ncc.Ten_nha_cung_cap
ORDER BY tong_so_luong DESC
LIMIT 5;


";
$result_topNhieu_nhaCungCapSeller = mysqli_query($conn, $sql_topNhieu_nhaCungCapSeller);

$labels_topNhieu_nhaCungCapSeller = [];
$data_topNhieu_nhaCungCapSeller = [];

while ($row = mysqli_fetch_assoc($result_topNhieu_nhaCungCapSeller)) {
    $labels_topNhieu_nhaCungCapSeller[] = $row['Ten_nha_cung_cap'];
    $data_topNhieu_nhaCungCapSeller[] = (int)$row['tong_so_luong'];
}

// TOP 5 bán ít nhất
$sql_topIt_nhaCungCapSeller = "
SELECT 
    ncc.Ten_nha_cung_cap,
    SUM(ct.So_luong) AS tong_so_luong
FROM chi_tiet_hoa_don AS ct
JOIN san_pham AS sp 
    ON ct.Ma_san_pham = sp.Ma_san_pham
JOIN nha_cung_cap AS ncc
    ON sp.Ma_nha_cung_cap = ncc.Ma_nha_cung_cap
JOIN hoa_don AS hd
    ON ct.Ma_hoa_don = hd.Ma_hoa_don
WHERE hd.Trang_thai = 3   -- chỉ tính đơn hoàn thành
GROUP BY ncc.Ma_nha_cung_cap, ncc.Ten_nha_cung_cap
ORDER BY tong_so_luong ASC
LIMIT 5;


";
$result_topIt_nhaCungCapSeller = mysqli_query($conn, $sql_topIt_nhaCungCapSeller);

$labels_topIt_nhaCungCapSeller = [];
$data_topIt_nhaCungCapSeller = [];

while ($row = mysqli_fetch_assoc($result_topIt_nhaCungCapSeller)) {
    $labels_topIt_nhaCungCapSeller[] = $row['Ten_nha_cung_cap'];
    $data_topIt_nhaCungCapSeller[] = (int)$row['tong_so_luong'];
}
//Top 5 nhà cung cấp bán ít/nhiều nhất (end)

//Top 5 sản phẩm tồn kho ít/nhiều nhất (start)
// TOP 5 tồn kho nhiều nhất
$sql_topNhieu_sanPham = "
    SELECT Ten_san_pham, So_luong
    FROM san_pham
    ORDER BY So_luong DESC
    LIMIT 5
";
$result_topNhieu_sanPham = mysqli_query($conn, $sql_topNhieu_sanPham);

$labels_topNhieu_sanPham = [];
$data_topNhieu_sanPham = [];

while ($row = mysqli_fetch_assoc($result_topNhieu_sanPham)) {
    $labels_topNhieu_sanPham[] = $row['Ten_san_pham'];
    $data_topNhieu_sanPham[] = (int)$row['So_luong'];
}

// TOP 5 tồn kho ít nhất
$sql_topIt_sanPham = "
    SELECT Ten_san_pham, So_luong
    FROM san_pham
    ORDER BY So_luong ASC
    LIMIT 5
";
$result_topIt_sanPham = mysqli_query($conn, $sql_topIt_sanPham);

$labels_topIt_sanPham = [];
$data_topIt_sanPham = [];

while ($row = mysqli_fetch_assoc($result_topIt_sanPham)) {
    $labels_topIt_sanPham[] = $row['Ten_san_pham'];
    $data_topIt_sanPham[] = (int)$row['So_luong'];
}
//Top 5 sản phẩm tồn kho ít/nhiều nhất (end)

//Top 5 sản phẩm bán ít/nhiều nhất (start)
// TOP 5 bán nhiều nhất
$sql_topNhieu_sanPhamSeller = "
    SELECT 
        sp.Ten_san_pham,
        SUM(cthd.So_luong) AS tong_ban
    FROM chi_tiet_hoa_don AS cthd
    JOIN san_pham AS sp 
        ON cthd.Ma_san_pham = sp.Ma_san_pham
    JOIN hoa_don AS hd 
        ON cthd.Ma_hoa_don = hd.Ma_hoa_don
    WHERE hd.Trang_thai = 3  
    GROUP BY sp.Ma_san_pham, sp.Ten_san_pham
    ORDER BY tong_ban DESC
    LIMIT 5
";
$result_topNhieu_sanPhamSeller = mysqli_query($conn, $sql_topNhieu_sanPhamSeller);

$labels_topNhieu_sanPhamSeller = [];
$data_topNhieu_sanPhamSeller = [];

while ($row = mysqli_fetch_assoc($result_topNhieu_sanPhamSeller)) {
    $labels_topNhieu_sanPhamSeller[] = $row['Ten_san_pham'];
    $data_topNhieu_sanPhamSeller[] = (int)$row['tong_ban'];
}

// TOP 5 bán ít nhất
$sql_topIt_sanPhamSeller = "
    SELECT 
        sp.Ten_san_pham,
        SUM(cthd.So_luong) AS tong_ban
    FROM chi_tiet_hoa_don AS cthd
    JOIN san_pham AS sp 
        ON cthd.Ma_san_pham = sp.Ma_san_pham
    JOIN hoa_don AS hd 
        ON cthd.Ma_hoa_don = hd.Ma_hoa_don
    WHERE hd.Trang_thai = 3  
    GROUP BY sp.Ma_san_pham, sp.Ten_san_pham
    ORDER BY tong_ban ASC
    LIMIT 5
";
$result_topIt_sanPhamSeller = mysqli_query($conn, $sql_topIt_sanPhamSeller);

$labels_topIt_sanPhamSeller = [];
$data_topIt_sanPhamSeller = [];

while ($row = mysqli_fetch_assoc($result_topIt_sanPhamSeller)) {
    $labels_topIt_sanPhamSeller[] = $row['Ten_san_pham'];
    $data_topIt_sanPhamSeller[] = (int)$row['tong_ban'];
}
//Top 5 sản phẩm bán ít/nhiều nhất (end)

//Tổng tiền theo nền tảng (start)
$sql_sanphamOnOff_Tien = "
    SELECT 
        Loai_don_hang,
        SUM(Tong_tien) AS tong_tien
    FROM hoa_don
    WHERE Trang_thai = 3
    GROUP BY Loai_don_hang
";

$result_sanphamOnOff_Tien = mysqli_query($conn, $sql_sanphamOnOff_Tien);

$labels_sanphamOnOff_Tien = [];
$data_sanphamOnOff_Tien = [];

while ($row = mysqli_fetch_assoc($result_sanphamOnOff_Tien)) {
    $labels_sanphamOnOff_Tien[] = ($row['Loai_don_hang'] == 0 ? 'Offline' : 'Online');
    $data_sanphamOnOff_Tien[] = (int)$row['tong_tien'];
}
//Tổng tiền theo nền tảng (end)

//Tổng hoá đơn theo nền tảng (start)
$sql_sanphamOnOff_hoaDon = "
    SELECT 
        Loai_don_hang,
        COUNT(*) AS so_hoa_don
    FROM hoa_don
    WHERE Trang_thai = 3
    GROUP BY Loai_don_hang
";
$result_sanphamOnOff_hoaDon = mysqli_query($conn, $sql_sanphamOnOff_hoaDon);
$labels_sanphamOnOff_hoaDon = [];
$data_sanphamOnOff_hoaDon = [];
while ($row = mysqli_fetch_assoc($result_sanphamOnOff_hoaDon)) {
    $labels_sanphamOnOff_hoaDon[] = ($row['Loai_don_hang'] == 0 ? 'Offline' : 'Online');
    $data_sanphamOnOff_hoaDon[] = (int)$row['so_hoa_don'];
}
//Tổng hoá đơn theo nền tảng (end)

// Chờ xác nhận (0)
$q0 = mysqli_query($conn, "SELECT COUNT(*) AS so_don FROM hoa_don WHERE Trang_thai = 0");
$don_0 = (int) mysqli_fetch_assoc($q0)['so_don'];

// Đã giao vận chuyển (2)
$q2 = mysqli_query($conn, "SELECT COUNT(*) AS so_don FROM hoa_don WHERE Trang_thai = 2");
$don_2 = (int) mysqli_fetch_assoc($q2)['so_don'];

// Tổng số tài khoản nhân viên (Ma_nhan_vien NOT NULL)
$sql_nv = "SELECT COUNT(*) AS total_nv FROM tai_khoan WHERE Ma_nhan_vien IS NOT NULL";
$result_nv = mysqli_query($conn, $sql_nv);
$total_nv = (int)mysqli_fetch_assoc($result_nv)['total_nv'];

// Tổng số tài khoản khách hàng (Ma_khach_hang NOT NULL)
$sql_kh = "SELECT COUNT(*) AS total_kh FROM tai_khoan WHERE Ma_khach_hang IS NOT NULL";
$result_kh = mysqli_query($conn, $sql_kh);
$total_kh = (int)mysqli_fetch_assoc($result_kh)['total_kh'];

//Top 5 loại sản phẩm bán ít/nhiều nhất (start)
// TOP 5 bán nhiều nhất
$sql_topNhieu_loaiSanPhamSeller = "
    SELECT 
    lsp.Ten_loai,
    SUM(ct.So_luong) AS tong_so_luong
FROM chi_tiet_hoa_don AS ct
JOIN san_pham AS sp ON ct.Ma_san_pham = sp.Ma_san_pham
JOIN loai_san_pham AS lsp ON sp.Ma_loai = lsp.Ma_loai
JOIN hoa_don AS hd ON ct.Ma_hoa_don = hd.Ma_hoa_don
WHERE hd.Trang_thai = 3
GROUP BY lsp.Ma_loai, lsp.Ten_loai
ORDER BY tong_so_luong DESC
LIMIT 5;

";
$result_topNhieu_loaiSanPhamSeller = mysqli_query($conn, $sql_topNhieu_loaiSanPhamSeller);

$labels_topNhieu_loaiSanPhamSeller = [];
$data_topNhieu_loaiSanPhamSeller = [];

while ($row = mysqli_fetch_assoc($result_topNhieu_loaiSanPhamSeller)) {
    $labels_topNhieu_loaiSanPhamSeller[] = $row['Ten_loai'];
    $data_topNhieu_loaiSanPhamSeller[] = (int)$row['tong_so_luong'];
}

// TOP 5 bán ít nhất
$sql_topIt_loaiSanPhamSeller = "
   SELECT 
    lsp.Ten_loai,
    SUM(ct.So_luong) AS tong_so_luong
FROM chi_tiet_hoa_don AS ct
JOIN san_pham AS sp ON ct.Ma_san_pham = sp.Ma_san_pham
JOIN loai_san_pham AS lsp ON sp.Ma_loai = lsp.Ma_loai
JOIN hoa_don AS hd ON ct.Ma_hoa_don = hd.Ma_hoa_don
WHERE hd.Trang_thai = 3
GROUP BY lsp.Ma_loai, lsp.Ten_loai
ORDER BY tong_so_luong ASC
LIMIT 5;

";
$result_topIt_loaiSanPhamSeller = mysqli_query($conn, $sql_topIt_loaiSanPhamSeller);

$labels_topIt_loaiSanPhamSeller = [];
$data_topIt_loaiSanPhamSeller = [];

while ($row = mysqli_fetch_assoc($result_topIt_loaiSanPhamSeller)) {
    $labels_topIt_loaiSanPhamSeller[] = $row['Ten_loai'];
    $data_topIt_loaiSanPhamSeller[] = (int)$row['tong_so_luong'];
}
//Top 5 loại sản phẩm bán ít/nhiều nhất (end)


$result_tong_hd = mysqli_query($conn, "SELECT COUNT(*) AS tong_hoa_don FROM hoa_don WHERE Trang_thai = 3");
$row_tong_hd = mysqli_fetch_assoc($result_tong_hd);
$tong_hoa_don = (int)$row_tong_hd['tong_hoa_don'];

$result_tong_tien_hd = mysqli_query($conn, "SELECT SUM(Tong_tien) AS tong_tien_hoan_thanh FROM hoa_don WHERE Trang_thai = 3");
$row_tong_tien_hd = mysqli_fetch_assoc($result_tong_tien_hd);
$tong_tien_hd = (float)$row_tong_tien_hd['tong_tien_hoan_thanh'];

// Tổng số tài khoản
$sql_total = "SELECT COUNT(*) AS total FROM tai_khoan";
$result_total = mysqli_query($conn, $sql_total);
$total_accounts = (int)mysqli_fetch_assoc($result_total)['total'];
// Tổng sản phẩm
$sql_tong_san_pham = "SELECT SUM(So_luong) AS tong_so_luong FROM san_pham";
$result_tong_san_pham = mysqli_query($conn, $sql_tong_san_pham);
$total_products = mysqli_fetch_assoc($result_tong_san_pham)['tong_so_luong'];


?>

<div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Chờ xác nhận
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo $don_0; ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-hourglass-start fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Đang giao hàng
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo $don_2; ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-shipping-fast fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Tài khoản nhân viên -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Tài khoản nhân viên
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo $total_nv; ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-tie fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tài khoản khách hàng -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Tài khoản khách hàng
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo $total_kh; ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Tổng hóa đơn
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo $tong_hoa_don; ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-file-invoice fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Tổng doanh thu
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo number_format($tong_tien_hd, 0, ',', '.') . " VND";
                            ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Tổng tài khoản -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Tổng tài khoản
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo $total_accounts; ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Tổng sản phẩm -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Tổng sản phẩm
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo number_format($total_products, 0, ',', '.'); ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-box fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-12 col-lg-12">
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary" id="chartTitle_doanhThu">
                    Doanh thu theo giờ (Hôm nay)
                </h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle btn btn-sm btn-outline-primary" href="#" role="button"
                        id="dropdownTime_doanhThu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-calendar-alt"></i> <span id="timeLabel_doanhThu">Theo giờ</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                        aria-labelledby="dropdownTime_doanhThu">
                        <div class="dropdown-header">Chọn thời gian:</div>
                        <a class="dropdown-item" href="#" onclick="changeTimeFilter_doanhThu('gio'); return false;">
                            <i class="fas fa-clock"></i> Theo giờ (Hôm nay)
                        </a>
                        <a class="dropdown-item" href="#" onclick="changeTimeFilter_doanhThu('ngay'); return false;">
                            <i class="fas fa-calendar-day"></i> Theo ngày (Tháng này)
                        </a>
                        <a class="dropdown-item" href="#" onclick="changeTimeFilter_doanhThu('thang'); return false;">
                            <i class="fas fa-calendar"></i> Theo tháng (Năm nay)
                        </a>
                    </div>
                </div>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <canvas id="myChart_doanhThu" width="1000" height="200"></canvas>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xl-6 col-lg-7">
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary" id="chartTitle_sanPham">
                    5 sản phẩm có trong kho nhiều nhất
                </h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink_sanPham"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                        aria-labelledby="dropdownMenuLink_sanPham">
                        <div class="dropdown-header">Thay đổi sắp xếp:</div>
                        <a class="dropdown-item" href="#" onclick="toggleChartSanPham('nhieu'); return false;">
                            <i class="fas fa-sort-amount-down"></i> Nhiều nhất
                        </a>
                        <a class="dropdown-item" href="#" onclick="toggleChartSanPham('it'); return false;">
                            <i class="fas fa-sort-amount-up"></i> Ít nhất
                        </a>
                    </div>
                </div>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <canvas id="myChart_sanPham" width="600" height="200"></canvas>
            </div>
        </div>
    </div>
    <div class="col-xl-6 col-lg-7">
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary" id="chartTitle_sanPhamSeller">
                    5 sản phẩm bán nhiều nhất
                </h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink_sanPhamSeller"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                        aria-labelledby="dropdownMenuLink_sanPhamSeller">
                        <div class="dropdown-header">Thay đổi sắp xếp:</div>
                        <a class="dropdown-item" href="#" onclick="toggleChartSeller('nhieu'); return false;">
                            <i class="fas fa-sort-amount-down"></i> Nhiều nhất
                        </a>
                        <a class="dropdown-item" href="#" onclick="toggleChartSeller('it'); return false;">
                            <i class="fas fa-sort-amount-up"></i> Ít nhất
                        </a>
                    </div>
                </div>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <canvas id="myChart_sanPhamSeller" width="600" height="200"></canvas>
            </div>
        </div>
    </div>

    <div class="col-xl-6 col-lg-7">
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary" id="chartTitle_loaiSanPhamSeller">
                    5 loại sản phẩm bán nhiều nhất
                </h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink_loaiSanPhamSeller"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                        aria-labelledby="dropdownMenuLink_loaiSanPhamSeller">
                        <div class="dropdown-header">Thay đổi sắp xếp:</div>
                        <a class="dropdown-item" href="#" onclick="toggleChartLoaiSanPhamSeller('nhieu'); return false;">
                            <i class="fas fa-sort-amount-down"></i> Nhiều nhất
                        </a>
                        <a class="dropdown-item" href="#" onclick="toggleChartLoaiSanPhamSeller('it'); return false;">
                            <i class="fas fa-sort-amount-up"></i> Ít nhất
                        </a>
                    </div>
                </div>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <canvas id="myChart_loaiSanPhamSeller" width="600" height="200"></canvas>
            </div>
        </div>
    </div>
    <div class="col-xl-6 col-lg-7">
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary" id="chartTitle_nhaCungCapSeller">
                    5 nhà cung cấp bán nhiều nhất
                </h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink_nhaCungCapSeller"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                        aria-labelledby="dropdownMenuLink_nhaCungCapSeller">
                        <div class="dropdown-header">Thay đổi sắp xếp:</div>
                        <a class="dropdown-item" href="#" onclick="toggleChartnhaCungCapSeller('nhieu'); return false;">
                            <i class="fas fa-sort-amount-down"></i> Nhiều nhất
                        </a>
                        <a class="dropdown-item" href="#" onclick="toggleChartnhaCungCapSeller('it'); return false;">
                            <i class="fas fa-sort-amount-up"></i> Ít nhất
                        </a>
                    </div>
                </div>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <canvas id="myChart_nhaCungCapSeller" width="600" height="200"></canvas>
            </div>
        </div>
    </div>
    <div class="col-xl-6 col-lg-7">
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary" id="chartTitle_sanphamOnOff_Tien">
                    Tổng Tiền theo nền tảng
                </h6>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <canvas id="myChart_sanphamOnOff_Tien" width="600" height="200"></canvas>
            </div>
        </div>
    </div>
    <div class="col-xl-6 col-lg-7">
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary" id="chartTitle_sanphamOnOff_hoaDon_Tien">
                    Tổng hoá đơn theo nền tảng
                </h6>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <canvas id="myChart_sanphamOnOff_hoaDon" width="600" height="200"></canvas>
            </div>
        </div>
    </div>
</div>


<!-- Top 5 sản phẩm tồn kho ít/nhiều nhất (start) -->
<script>
    // Dữ liệu từ PHP - Chart Tồn Kho
    const dataSource_sanPham = {
        nhieu: {
            labels: <?php echo json_encode($labels_topNhieu_sanPham); ?>,
            data: <?php echo json_encode($data_topNhieu_sanPham); ?>,
            title: '5 sản phẩm có trong kho nhiều nhất'
        },
        it: {
            labels: <?php echo json_encode($labels_topIt_sanPham); ?>,
            data: <?php echo json_encode($data_topIt_sanPham); ?>,
            title: '5 sản phẩm có trong kho ít nhất'
        }
    };

    // Khởi tạo chart Tồn Kho
    const ctx_sanPham = document.getElementById('myChart_sanPham').getContext('2d');
    const myChart_sanPham = new Chart(ctx_sanPham, {
        type: 'bar',
        data: {
            labels: dataSource_sanPham.nhieu.labels,
            datasets: [{
                label: 'Số lượng tồn kho',
                data: dataSource_sanPham.nhieu.data,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            animation: {
                duration: 750
            }
        }
    });

    // Hàm toggle chart Tồn Kho
    function toggleChartSanPham(type) {
        const source = dataSource_sanPham[type];

        // Update dữ liệu chart
        myChart_sanPham.data.labels = source.labels;
        myChart_sanPham.data.datasets[0].data = source.data;

        // Update tiêu đề
        document.getElementById('chartTitle_sanPham').textContent = source.title;

        // Đổi màu theo loại
        if (type === 'it') {
            myChart_sanPham.data.datasets[0].backgroundColor = 'rgba(255, 99, 132, 0.2)';
            myChart_sanPham.data.datasets[0].borderColor = 'rgba(255, 99, 132, 1)';
        } else {
            myChart_sanPham.data.datasets[0].backgroundColor = 'rgba(75, 192, 192, 0.2)';
            myChart_sanPham.data.datasets[0].borderColor = 'rgba(75, 192, 192, 1)';
        }
        myChart_sanPham.update();
    }
</script>
<!-- Top 5 sản phẩm tồn kho ít/nhiều nhất (end) -->

<!-- Top 5 sản phẩm bán ít/nhiều nhất (start) -->
<script>
    // Dữ liệu từ PHP - Chart Bán Hàng
    const dataSource_sanPhamSeller = {
        nhieu: {
            labels: <?php echo json_encode($labels_topNhieu_sanPhamSeller); ?>,
            data: <?php echo json_encode($data_topNhieu_sanPhamSeller); ?>,
            title: '5 sản phẩm bán nhiều nhất'
        },
        it: {
            labels: <?php echo json_encode($labels_topIt_sanPhamSeller); ?>,
            data: <?php echo json_encode($data_topIt_sanPhamSeller); ?>,
            title: '5 sản phẩm bán ít nhất'
        }
    };

    // Khởi tạo chart Bán Hàng
    const ctx_sanPhamSeller = document.getElementById('myChart_sanPhamSeller').getContext('2d');
    const myChart_sanPhamSeller = new Chart(ctx_sanPhamSeller, {
        type: 'bar',
        data: {
            labels: dataSource_sanPhamSeller.nhieu.labels,
            datasets: [{
                label: 'Số lượng đã bán',
                data: dataSource_sanPhamSeller.nhieu.data,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            animation: {
                duration: 750
            }
        }
    });

    // Hàm toggle chart Bán Hàng
    function toggleChartSeller(type) {
        const source = dataSource_sanPhamSeller[type];

        // Update dữ liệu chart
        myChart_sanPhamSeller.data.labels = source.labels;
        myChart_sanPhamSeller.data.datasets[0].data = source.data;

        // Update tiêu đề
        document.getElementById('chartTitle_sanPhamSeller').textContent = source.title;

        // Đổi màu theo loại
        if (type === 'it') {
            myChart_sanPhamSeller.data.datasets[0].backgroundColor = 'rgba(255, 99, 132, 0.2)';
            myChart_sanPhamSeller.data.datasets[0].borderColor = 'rgba(255, 99, 132, 1)';
        } else {
            myChart_sanPhamSeller.data.datasets[0].backgroundColor = 'rgba(75, 192, 192, 0.2)';
            myChart_sanPhamSeller.data.datasets[0].borderColor = 'rgba(75, 192, 192, 1)';
        }
        myChart_sanPhamSeller.update();
    }
</script>
<!-- Top 5 sản phẩm bán ít/nhiều nhất (end) -->

<!-- Tổng tiền theo nền tảng (start) -->
<script>
    const labels_sanphamOnOff_Tien = <?php echo json_encode($labels_sanphamOnOff_Tien); ?>;
    const data_sanphamOnOff_Tien = <?php echo json_encode($data_sanphamOnOff_Tien); ?>;
    const ctx_sanphamOnOff_Tien = document.getElementById('myChart_sanphamOnOff_Tien').getContext('2d');
    const myChart_sanphamOnOff_Tien = new Chart(ctx_sanphamOnOff_Tien, {
        type: 'bar',
        data: {
            labels: labels_sanphamOnOff_Tien,
            datasets: [{
                label: 'Tổng tiền',
                data: data_sanphamOnOff_Tien,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true

                }
            },
            animation: {
                duration: 750
            }
        }
    });
</script>
<!-- Tổng tiền theo nền tảng (end) -->

<!-- Tổng hoá đơn theo nền tảng (start) -->
<script>
    const labels_sanphamOnOff_hoaDon = <?php echo json_encode($labels_sanphamOnOff_hoaDon); ?>;
    const data_sanphamOnOff_hoaDon = <?php echo json_encode($data_sanphamOnOff_hoaDon); ?>;

    const ctx_sanphamOnOff_hoaDon = document.getElementById('myChart_sanphamOnOff_hoaDon').getContext('2d');
    const myChart_sanphamOnOff_hoaDon = new Chart(ctx_sanphamOnOff_hoaDon, {
        type: 'bar',
        data: {
            labels: labels_sanphamOnOff_hoaDon,
            datasets: [{
                label: 'Số lượng hóa đơn',
                data: data_sanphamOnOff_hoaDon,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1 // bắt trục Y nhảy 1 đơn vị
                    }
                }
            },
            animation: {
                duration: 750
            }
        }
    });
</script>
<!-- Tổng hoá đơn theo nền tảng (end) -->

<!-- Top 5 loại sản phẩm bán ít/nhiều nhất (start) -->
<script>
    // Dữ liệu từ PHP - Chart Bán Hàng
    const dataSource_loaiSanPhamSeller = {
        nhieu: {
            labels: <?php echo json_encode($labels_topNhieu_loaiSanPhamSeller); ?>,
            data: <?php echo json_encode($data_topNhieu_loaiSanPhamSeller); ?>,
            title: '5 loại sản phẩm bán nhiều nhất'
        },
        it: {
            labels: <?php echo json_encode($labels_topIt_loaiSanPhamSeller); ?>,
            data: <?php echo json_encode($data_topIt_loaiSanPhamSeller); ?>,
            title: '5 loại sản phẩm bán ít nhất'
        }
    };

    // Khởi tạo chart Bán Hàng
    const ctx_loaiSanPhamSeller = document.getElementById('myChart_loaiSanPhamSeller').getContext('2d');
    const myChart_loaiSanPhamSeller = new Chart(ctx_loaiSanPhamSeller, {
        type: 'bar',
        data: {
            labels: dataSource_loaiSanPhamSeller.nhieu.labels,
            datasets: [{
                label: 'Số lượng đã bán',
                data: dataSource_loaiSanPhamSeller.nhieu.data,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            animation: {
                duration: 750
            }
        }
    });

    // Hàm toggle chart Bán Hàng
    function toggleChartLoaiSanPhamSeller(type) {
        const source = dataSource_loaiSanPhamSeller[type];

        // Update dữ liệu chart
        myChart_loaiSanPhamSeller.data.labels = source.labels;
        myChart_loaiSanPhamSeller.data.datasets[0].data = source.data;

        // Update tiêu đề
        document.getElementById('chartTitle_loaiSanPhamSeller').textContent = source.title;

        // Đổi màu theo loại
        if (type === 'it') {
            myChart_loaiSanPhamSeller.data.datasets[0].backgroundColor = 'rgba(255, 99, 132, 0.2)';
            myChart_loaiSanPhamSeller.data.datasets[0].borderColor = 'rgba(255, 99, 132, 1)';
        } else {
            myChart_loaiSanPhamSeller.data.datasets[0].backgroundColor = 'rgba(75, 192, 192, 0.2)';
            myChart_loaiSanPhamSeller.data.datasets[0].borderColor = 'rgba(75, 192, 192, 1)';
        }
        myChart_loaiSanPhamSeller.update();
    }
</script>
<!-- Top 5 loại sản phẩm bán ít/nhiều nhất (end) -->

<!-- Top 5 nhà cung cấp bán ít/nhiều nhất (start) -->
<script>
    // Dữ liệu từ PHP - Chart Bán Hàng
    const dataSource_nhaCungCapSeller = {
        nhieu: {
            labels: <?php echo json_encode($labels_topNhieu_nhaCungCapSeller); ?>,
            data: <?php echo json_encode($data_topNhieu_nhaCungCapSeller); ?>,
            title: '5 nhà cung cấp bán nhiều nhất'
        },
        it: {
            labels: <?php echo json_encode($labels_topIt_nhaCungCapSeller); ?>,
            data: <?php echo json_encode($data_topIt_nhaCungCapSeller); ?>,
            title: '5 nhà cung cấp bán ít nhất'
        }
    };

    // Khởi tạo chart Bán Hàng
    const ctx_nhaCungCapSeller = document.getElementById('myChart_nhaCungCapSeller').getContext('2d');
    const myChart_nhaCungCapSeller = new Chart(ctx_nhaCungCapSeller, {
        type: 'bar',
        data: {
            labels: dataSource_nhaCungCapSeller.nhieu.labels,
            datasets: [{
                label: 'Số lượng đã bán',
                data: dataSource_nhaCungCapSeller.nhieu.data,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            animation: {
                duration: 750
            }
        }
    });

    // Hàm toggle chart Bán Hàng
    function toggleChartnhaCungCapSeller(type) {
        const source = dataSource_nhaCungCapSeller[type];

        // Update dữ liệu chart
        myChart_nhaCungCapSeller.data.labels = source.labels;
        myChart_nhaCungCapSeller.data.datasets[0].data = source.data;

        // Update tiêu đề
        document.getElementById('chartTitle_nhaCungCapSeller').textContent = source.title;

        // Đổi màu theo loại
        if (type === 'it') {
            myChart_nhaCungCapSeller.data.datasets[0].backgroundColor = 'rgba(255, 99, 132, 0.2)';
            myChart_nhaCungCapSeller.data.datasets[0].borderColor = 'rgba(255, 99, 132, 1)';
        } else {
            myChart_nhaCungCapSeller.data.datasets[0].backgroundColor = 'rgba(75, 192, 192, 0.2)';
            myChart_nhaCungCapSeller.data.datasets[0].borderColor = 'rgba(75, 192, 192, 1)';
        }
        myChart_nhaCungCapSeller.update();
    }
</script>
<!-- Top 5 nhà cung cấp bán ít/nhiều nhất (end) -->
<!-- Doanh thu theo giờ/ngày/tháng (start) -->
<script>
    // Dữ liệu từ PHP - Doanh thu theo thời gian
    const dataSource_doanhThu = {
        gio: {
            labels: <?php echo json_encode($labels_doanhThu_gio); ?>,
            data: <?php echo json_encode($data_doanhThu_gio); ?>,
            title: 'Doanh thu theo giờ (Hôm nay)',
            labelText: 'Theo giờ',
            xAxisLabel: 'Giờ'
        },
        ngay: {
            labels: <?php echo json_encode($labels_doanhThu_ngay); ?>,
            data: <?php echo json_encode($data_doanhThu_ngay); ?>,
            title: 'Doanh thu theo ngày (Tháng <?php echo date("m/Y"); ?>)',
            labelText: 'Theo ngày',
            xAxisLabel: 'Ngày'
        },
        thang: {
            labels: <?php echo json_encode($labels_doanhThu_thang); ?>,
            data: <?php echo json_encode($data_doanhThu_thang); ?>,
            title: 'Doanh thu theo tháng (Năm <?php echo date("Y"); ?>)',
            labelText: 'Theo tháng',
            xAxisLabel: 'Tháng'
        }
    };

    // Biến lưu trạng thái hiện tại
    let currentTimeFilter_doanhThu = 'gio';

    // Khởi tạo chart
    const ctx_doanhThu = document.getElementById('myChart_doanhThu').getContext('2d');
    const myChart_doanhThu = new Chart(ctx_doanhThu, {
        type: 'line',
        data: {
            labels: dataSource_doanhThu.gio.labels,
            datasets: [{
                label: 'Doanh thu (VNĐ)',
                data: dataSource_doanhThu.gio.data,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2,
                tension: 0.4,
                fill: true,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString('vi-VN') + ' đ';
                        }
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: dataSource_doanhThu.gio.xAxisLabel
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Doanh thu: ' + context.parsed.y.toLocaleString('vi-VN') + ' đ';
                        }
                    }
                },
                legend: {
                    display: true
                }
            },
            animation: {
                duration: 750
            }
        }
    });

    // Hàm thay đổi bộ lọc thời gian
    function changeTimeFilter_doanhThu(timeType) {
        currentTimeFilter_doanhThu = timeType;

        const source = dataSource_doanhThu[timeType];

        // Update dữ liệu chart
        myChart_doanhThu.data.labels = source.labels;
        myChart_doanhThu.data.datasets[0].data = source.data;

        // Update tiêu đề
        document.getElementById('chartTitle_doanhThu').textContent = source.title;

        // Update label thời gian
        document.getElementById('timeLabel_doanhThu').textContent = source.labelText;

        // Update label trục X
        myChart_doanhThu.options.scales.x.title.text = source.xAxisLabel;

        myChart_doanhThu.update();
    }
</script>
<!-- Doanh thu theo giờ/ngày/tháng (end) -->