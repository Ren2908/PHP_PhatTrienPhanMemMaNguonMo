<?php
session_start();
include('includes/ket_noi.php');

// 1. Kiểm tra đăng nhập
if (!isset($_SESSION['ma_khach_hang'])) {
    echo "Chưa đăng nhập";
    exit;
}

// 2. Kiểm tra có chọn sản phẩm không
if (!isset($_POST['chon_san_pham']) || empty($_POST['chon_san_pham'])) {
    echo "Vui lòng chọn ít nhất 1 sản phẩm!";
    exit;
}

$ma_khach_hang = $_SESSION['ma_khach_hang'];
$danh_sach_san_pham = $_POST['chon_san_pham'];

// 3. Tạo mã hóa đơn tự động
$ma_hoa_don = "HD" . (time() % 100000);

// 4. Lấy thông tin sản phẩm trong giỏ hàng
$ds_maSP = "'" . implode("','", $danh_sach_san_pham) . "'";

$query_SP = "
    SELECT g.ma_san_pham,s.ten_san_pham, g.so_luong, s.Don_gia, s.So_luong AS So_luong_kho
    FROM gio_hang g
    JOIN san_pham s ON g.ma_san_pham = s.Ma_san_pham
    WHERE g.ma_khach_hang = '$ma_khach_hang' 
      AND g.ma_san_pham IN ($ds_maSP)
";

$resultSP = mysqli_query($conn, $query_SP);

$chi_tiet = [];
$tong_tien = 0;

// 5. Kiểm tra số lượng kho
while ($row = mysqli_fetch_assoc($resultSP)) {

    if ($row['so_luong'] > $row['So_luong_kho']) {
    echo "Sản phẩm " . $row['ten_san_pham'] . " không đủ với yêu cầu mua sắm của bạn vui lòng liên hệ chủ cửa hàng";

        exit;
    }

    $subtotal = $row['Don_gia'] * $row['so_luong'];
    $tong_tien += $subtotal;
    $chi_tiet[] = $row;
}

// 6. Thêm vào bảng hóa đơn
$query_HD = "
    INSERT INTO hoa_don (Ma_hoa_don, Ma_khach_hang, Ngay_tao, Tong_tien, Trang_thai, Loai_don_hang)
    VALUES ('$ma_hoa_don', '$ma_khach_hang', NOW(), '$tong_tien', 0, 1)
";

if (!mysqli_query($conn, $query_HD)) {
    echo "Lỗi tạo hóa đơn: " . mysqli_error($conn);
    exit;
}

// 7. Vòng lặp thêm chi tiết hóa đơn – trừ kho – xóa giỏ
foreach ($chi_tiet as $item) {

    $maSP = $item['ma_san_pham'];
    $soLuong = $item['so_luong'];
    $donGia = $item['Don_gia'];

    // Thêm chi tiết hóa đơn
    $query_CT = "
        INSERT INTO chi_tiet_hoa_don (Ma_hoa_don, Ma_san_pham, So_luong, Don_gia)
        VALUES ('$ma_hoa_don', '$maSP', '$soLuong', '$donGia')
    ";
    mysqli_query($conn, $query_CT);

    // Trừ kho
    $query_TruKho = "
        UPDATE san_pham SET So_luong = So_luong - $soLuong 
        WHERE Ma_san_pham = '$maSP'
    ";
    mysqli_query($conn, $query_TruKho);

    // Xóa khỏi giỏ hàng
    $query_Xoa = "
        DELETE FROM gio_hang
        WHERE ma_khach_hang = '$ma_khach_hang' AND ma_san_pham = '$maSP'
    ";
    mysqli_query($conn, $query_Xoa);
}



echo "ĐẶT HÀNG THÀNH CÔNG !.
     Mã hóa đơn của bạn là : $ma_hoa_don.
     Vui lòng kiểm tra trong giò hàng để xem thông tin chi tiết
";

?>