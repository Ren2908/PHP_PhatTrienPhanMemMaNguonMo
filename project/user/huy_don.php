<?php
include('includes/ket_noi.php');

if (isset($_GET['ma'])) {
    $ma_hoa_don = $_GET['ma'];

    $sql_ct = "SELECT Ma_san_pham, So_luong FROM chi_tiet_hoa_don WHERE Ma_hoa_don = '$ma_hoa_don'";
    $result_ct = mysqli_query($conn, $sql_ct);

    if ($result_ct && mysqli_num_rows($result_ct) > 0) {
        while ($row = mysqli_fetch_assoc($result_ct)) {
            $ma_san_pham = $row['Ma_san_pham'];
            $so_luong = $row['So_luong'];

            $sql_update_kho = "UPDATE san_pham SET So_luong = So_luong + $so_luong WHERE Ma_san_pham = '$ma_san_pham'";
            mysqli_query($conn, $sql_update_kho);
        }
    }

    $sql_huy = "UPDATE hoa_don SET Trang_thai = 4 WHERE Ma_hoa_don = '$ma_hoa_don'";
    mysqli_query($conn, $sql_huy);
}

header("Location: don_hang_cua_toi.php");
exit;