<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách điện thoại theo nhà cung cấp</title>
</head>

<body>
    <?php
$page_title = 'Danh sách điện thoại';
include('includes/header.php');
include('includes/ket_noi.php'); 

// ========== LỌC THEO NHÀ CUNG CẤP ==========
$ma_nha_cung_cap = isset($_GET['ma_nha_cung_cap']) ? $conn->real_escape_string($_GET['ma_nha_cung_cap']) : '';

// ========== TÌM KIẾM ==========
$tu_khoa = isset($_GET['tu_khoa']) ? $conn->real_escape_string(trim($_GET['tu_khoa'])) : '';

// ========== PHÂN TRANG ==========
$so_san_pham_tren_trang = 8;
$trang_hien_tai = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($trang_hien_tai < 1) $trang_hien_tai = 1;
$offset = ($trang_hien_tai - 1) * $so_san_pham_tren_trang;

// ========== XÂY DỰNG ĐIỀU KIỆN ==========
$dieu_kien = [];
if ($ma_nha_cung_cap !== "") {
    $dieu_kien[] = "Ma_nha_cung_cap = '$ma_nha_cung_cap'";
}
if ($tu_khoa !== "") {
    $dieu_kien[] = "Ten_san_pham LIKE '%$tu_khoa%'";
}
$chuoi_dieu_kien = "";
if (!empty($dieu_kien)) {
    $chuoi_dieu_kien = "WHERE " . implode(" AND ", $dieu_kien);
}

// ========== ĐẾM TỔNG SẢN PHẨM ==========
$sqlDem = "SELECT COUNT(*) as total FROM san_pham $chuoi_dieu_kien";
$resultDem = $conn->query($sqlDem);
$so_luong_san_pham = $resultDem->fetch_assoc()['total'];
$so_trang = ceil($so_luong_san_pham / $so_san_pham_tren_trang);

// ========== LẤY DANH SÁCH SẢN PHẨM ==========
$sql = "SELECT Ma_san_pham, Ten_san_pham, So_luong, Don_gia, Mo_ta, Hinh_anh
        FROM san_pham $chuoi_dieu_kien
        LIMIT $offset, $so_san_pham_tren_trang";
$result = $conn->query($sql);

// ========== SẢN PHẨM BÁN CHẠY ==========
$sqlBanChay = "
    SELECT sp.Ma_san_pham, sp.Ten_san_pham, sp.Hinh_anh, sp.Don_gia, SUM(ct.So_luong) AS Tong_da_ban
    FROM san_pham sp
    JOIN chi_tiet_hoa_don ct ON sp.Ma_san_pham = ct.Ma_san_pham
    GROUP BY sp.Ma_san_pham, sp.Ten_san_pham, sp.Hinh_anh, sp.Don_gia
    ORDER BY Tong_da_ban DESC
    LIMIT 10
";
$resultBanChay = $conn->query($sqlBanChay);
?>

    <!-- Sản phẩm bán chạy -->
    <div id="best-seller" class="best-seller-box">
        <h2>Sản phẩm bán chạy</h2>
        <?php
    if ($resultBanChay && $resultBanChay->num_rows > 0) {
        echo '<div class="product-list">';
        while ($rowBC = $resultBanChay->fetch_assoc()) {
            echo '<div class="product-item">';
            echo '<a href="chi_tiet_san_pham.php?ma_san_pham=' . urlencode($rowBC['Ma_san_pham']) . '">';
            echo '<img src="../admin/_images/' . htmlspecialchars($rowBC['Hinh_anh']) . '" alt="' . htmlspecialchars($rowBC['Ten_san_pham']) . '"><br>';
            echo '<strong>' . htmlspecialchars($rowBC['Ten_san_pham']) . '</strong>';
            echo '</a><br>';
            echo '<span>Giá: ' . number_format($rowBC['Don_gia'], 0, ',', '.') . ' VND</span><br>';
            echo '<span>Đã bán: ' . htmlspecialchars($rowBC['Tong_da_ban']) . '</span><br>';
            echo '<button class="btn btn-primary btn-sm" onclick="themVaoGio(\'' . $rowBC['Ma_san_pham'] . '\')">Thêm vào giỏ hàng</button>';
            echo '</div>';
        }
        echo '</div>';
    } else {
        echo '<p>Chưa có sản phẩm nào cả.</p>';
    }
    ?>
    </div>
    <!-- Form tìm kiếm theo nhà cung cấp -->
    <div class="d-flex justify-content-end mb-3">
        <form method="get" action="" class="d-flex gap-2" style="max-width: 500px;">
            <input type="hidden" name="ma_nha_cung_cap" value="<?php echo htmlspecialchars($ma_nha_cung_cap); ?>">
            <input type="text" name="tu_khoa" class="form-control form-control-sm" placeholder="Tìm kiếm..."
                value="<?php echo htmlspecialchars($tu_khoa); ?>">
            <button type="submit" class="btn btn-primary btn-lg">🔍</button>
        </form>
    </div>
    <div id="container">
        <div id="main-layout">
            <div id="sidebar">
                <?php include('includes/lefter.php'); ?>
            </div>

            <div id="main-content">

                <div id="product-list">
                    <?php
                if ($result && $result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo '<div class="product-item">';
                        echo '<a href="chi_tiet_san_pham.php?ma_san_pham=' . urlencode($row['Ma_san_pham']) . '">';
                        echo '<img src="../admin/_images/' . htmlspecialchars($row['Hinh_anh']) . '" alt="' . htmlspecialchars($row['Ten_san_pham']) . '"><br>';
                        echo '<strong>' . htmlspecialchars($row['Ten_san_pham']) . '</strong>';
                        echo '</a><br>';
                        echo '<span>Giá: ' . number_format($row['Don_gia'], 0, ',', '.') . ' VND</span><br>';
                        echo '<span>Số lượng: ' . htmlspecialchars($row['So_luong']) . '</span><br>';
                        echo '<p>' . htmlspecialchars(substr($row['Mo_ta'], 0, 60)) . '...</p>';
                        echo '<button class="btn btn-primary btn-sm" onclick="themVaoGio(\'' . $row['Ma_san_pham'] . '\')">Thêm vào giỏ hàng</button>';
                        echo '</div>';
                    }
                } else {
                    echo "<p>Hiện chưa có sản phẩm nào.</p>";
                }
                ?>
                </div>

                <!-- PHÂN TRANG -->
                <div class="pagination">
                    <?php
                $tham_so = "";
                if ($ma_nha_cung_cap !== "") $tham_so .= "&ma_nha_cung_cap=" . urlencode($ma_nha_cung_cap);
                if ($tu_khoa !== "") $tham_so .= "&tu_khoa=" . urlencode($tu_khoa);

                if ($trang_hien_tai > 1) {
                    echo "<a href='" . $_SERVER['PHP_SELF'] . "?page=" . ($trang_hien_tai - 1) . $tham_so . "' class='page-btn'>« Back</a> ";
                }

                for ($i = 1; $i <= $so_trang; $i++) {
                    if ($i == $trang_hien_tai) {
                        echo "<span class='page-current'>Trang $i</span> ";
                    } else {
                        echo "<a href='" . $_SERVER['PHP_SELF'] . "?page=$i" . $tham_so . "' class='page-link'>Trang $i</a> ";
                    }
                }

                if ($trang_hien_tai < $so_trang) {
                    echo "<a href='" . $_SERVER['PHP_SELF'] . "?page=" . ($trang_hien_tai + 1) . $tham_so . "' class='page-btn'>Next »</a>";
                }
                ?>
                </div>

            </div>
        </div>
    </div>

    <?php include('includes/footer.html'); ?>
    <script src="./java/gio_hang.js"></script>
</body>

</html>