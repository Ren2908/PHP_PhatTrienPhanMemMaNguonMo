<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách điện thoại</title>
</head>

<body>
    <?php
$page_title = 'Danh sách điện thoại';
include('includes/header.php');
include('includes/ket_noi.php'); 

// ========== LỌC THEO LOẠI ==========
$ma_loai = isset($_GET['ma_loai']) ? $_GET['ma_loai'] : '';

// ========== TÌM KIẾM ==========
$tukhoa = isset($_GET['tukhoa']) ? trim($_GET['tukhoa']) : '';

// ========== PHÂN TRANG ==========
$rowsPerPage = 8;
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($currentPage < 1) $currentPage = 1;
$offset = ($currentPage - 1) * $rowsPerPage;

// ========== TRUY VẤN DANH SÁCH SẢN PHẨM ==========
$dieu_kien = [];
if ($ma_loai !== "") {
    $dieu_kien[] = "Ma_loai = '$ma_loai'";
}
if ($tukhoa !== "") {
    $dieu_kien[] = "Ten_san_pham LIKE '%$tukhoa%'";
}

$gop_dieu_kien = "";
if (!empty($dieu_kien)) {
    $gop_dieu_kien = "WHERE " . implode(" AND ", $dieu_kien);
}

// Đếm tổng số sản phẩm
$sqlCount = "SELECT COUNT(*) as total FROM san_pham $gop_dieu_kien";
$resultCount = $conn->query($sqlCount);
$numRows = $resultCount->fetch_assoc()['total'];
$maxPage = ceil($numRows / $rowsPerPage);

// Lấy danh sách sản phẩm phân trang
$sql = "SELECT Ma_san_pham, Ten_san_pham, So_luong, Don_gia, Mo_ta, Hinh_anh
        FROM san_pham $gop_dieu_kien
        LIMIT $offset, $rowsPerPage";
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
    <!-- Form tìm kiếm theo loại -->
    <div class="d-flex justify-content-end mb-3">
        <form method="get" action="" class="d-flex gap-2" style="max-width: 500px;">
            <input type="hidden" name="ma_loai" value="<?php echo htmlspecialchars($ma_loai); ?>">
            <input type="text" name="tukhoa" class="form-control form-control-sm" placeholder="Tìm kiếm..."
                value="<?php echo htmlspecialchars($tukhoa); ?>">
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
                $param = "";
                if ($ma_loai !== "") $param .= "&ma_loai=" . urlencode($ma_loai);
                if ($tukhoa !== "") $param .= "&tukhoa=" . urlencode($tukhoa);

                if ($currentPage > 1) {
                    echo "<a href='" . $_SERVER['PHP_SELF'] . "?page=" . ($currentPage - 1) . $param . "' class='page-btn'>« Back</a> ";
                }

                for ($i = 1; $i <= $maxPage; $i++) {
                    if ($i == $currentPage) {
                        echo "<span class='page-current'>Trang $i</span> ";
                    } else {
                        echo "<a href='" . $_SERVER['PHP_SELF'] . "?page=$i" . $param . "' class='page-link'>Trang $i</a> ";
                    }
                }

                if ($currentPage < $maxPage) {
                    echo "<a href='" . $_SERVER['PHP_SELF'] . "?page=" . ($currentPage + 1) . $param . "' class='page-btn'>Next »</a>";
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