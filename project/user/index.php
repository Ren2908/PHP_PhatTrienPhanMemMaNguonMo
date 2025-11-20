<?php
session_start();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
$page_title = 'Danh sách điện thoại';
include('includes/header.php');
include('includes/ket_noi.php'); 

// ========== PHÂN TRANG ==========
$rowsPerPage = 8; // Số sản phẩm mỗi trang

if (!isset($_GET['page'])) {
    $_GET['page'] = 1;
}
$currentPage = $_GET['page'];

$offset = ($currentPage - 1) * $rowsPerPage;

// DANH SÁCH SẢN PHẨM CÓ PHÂN TRANG
$sql = "SELECT Ma_san_pham, Ten_san_pham, So_luong, Don_gia, Mo_ta, Hinh_anh 
        FROM san_pham 
        LIMIT $offset, $rowsPerPage";
$result = $conn->query($sql);

// Đếm tổng số sản phẩm để tính số trang
$sqlCount = "SELECT COUNT(*) as total FROM san_pham";
$resultCount = $conn->query($sqlCount);
$rowCount = $resultCount->fetch_assoc();
$numRows = $rowCount['total'];

// Tính tổng số trang
$maxPage = ceil($numRows / $rowsPerPage);

// TRUY VẤN LẤY SẢN PHẨM BÁN CHẠY (TOP 10)
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
        <h2> Sản phẩm bán chạy</h2>
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
            echo '<p>Chưa có sản phẩm nào cả .</p>';
        }
        ?>
    </div>

    <div id="container">
        <div id="main-layout">
            <div id="sidebar">
                <?php include('includes/lefter.php'); ?>
            </div>

            <div id="main-content">
                <div id="product-list">
                    <!-- Danh sách sản phẩm -->
                    <?php
                    if ($result && $result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo '<div class="product-item">';
                            echo '<a href="chi_tiet_san_pham.php?ma_san_pham=' . urlencode($row['Ma_san_pham']) . '">';
                            echo '<img src="../admin/_images/' . htmlspecialchars($row['Hinh_anh']) . '" alt="' . htmlspecialchars($row['Ten_san_pham']) . '"><br>';
                            echo '<strong>' . htmlspecialchars($row['Ten_san_pham']) . '</strong>';
                            echo '</a><br>';
                            echo '<span>Giá: ' . number_format($row['Don_gia'], 0, ',', '.') . ' VND</span><br>';
                            $so_luong = (int)$row['So_luong'];
                            if ($so_luong < 10) {
                                echo '<span style="color:red;">Số lượng: ' . $so_luong . '</span><br>';
                            } else {
                                echo '<span>Số lượng: ' . $so_luong . '</span><br>';
                            }
                            echo '<p>' . htmlspecialchars(substr($row['Mo_ta'], 0, 60)) . '...</p>';
                            echo '<button onclick="themVaoGio(\'' . $row['Ma_san_pham'] . '\')" class="btn btn-primary">Thêm vào giỏ hàng</button>';                            echo '</div>';
                        }
                    } else {
                        echo '<p>Hiện chưa có sản phẩm điện thoại nào.</p>';
                    }
                    ?>
                </div>

                <!-- PHÂN TRANG -->
                <div class="pagination">
                    <?php
                    // Nút Back
                    if ($currentPage > 1) {
                        echo "<a href='" . $_SERVER['PHP_SELF'] . "?page=" . ($currentPage - 1) . "' class='page-btn'>« Back</a> ";
                    }

                    // Hiển thị các trang
                    for ($i = 1; $i <= $maxPage; $i++) {
                        if ($i == $currentPage) {
                            echo "<span class='page-current'>Trang $i</span> ";
                        } else {
                            echo "<a href='" . $_SERVER['PHP_SELF'] . "?page=$i' class='page-link'>Trang $i</a> ";
                        }
                    }

                    // Nút Next
                    if ($currentPage < $maxPage) {
                        echo "<a href='" . $_SERVER['PHP_SELF'] . "?page=" . ($currentPage + 1) . "' class='page-btn'>Next »</a>";
                    }
                    ?>
                </div>


            </div>
        </div>
    </div>

    <?php
    include('includes/footer.html');
    ?>
</body>

<script src="./java/gio_hang.js"></script>

</html>