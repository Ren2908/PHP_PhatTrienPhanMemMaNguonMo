<?php
session_start();
?>
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

// ===========================================
// PHÂN TRANG
// ===========================================
$rowsPerPage = 8;

$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($currentPage < 1) $currentPage = 1;

$offset = ($currentPage - 1) * $rowsPerPage;

// ===========================================
// XỬ LÝ TÌM KIẾM
// ===========================================
$tukhoa = isset($_POST['tukhoa']) ? trim($_POST['tukhoa']) : "";

if ($tukhoa !== "") {

    // Đếm tổng số sản phẩm tìm được
    $sqlCount = "
        SELECT COUNT(*) AS total 
        FROM san_pham 
        WHERE Ten_san_pham LIKE '%$tukhoa%'
    ";
    $resultCount = $conn->query($sqlCount);
    $numRows = $resultCount->fetch_assoc()['total'];

    $maxPage = ceil($numRows / $rowsPerPage);

    // Lấy sản phẩm theo từ khóa
    $sql = "
        SELECT Ma_san_pham, Ten_san_pham, So_luong, Don_gia, Mo_ta, Hinh_anh
        FROM san_pham
        WHERE Ten_san_pham LIKE '%$tukhoa%'
        LIMIT $offset, $rowsPerPage
    ";

} else {

    // Không tìm kiếm → phân trang bình thường
    $sqlCount = "SELECT COUNT(*) AS total FROM san_pham";
    $resultCount = $conn->query($sqlCount);
    $numRows = $resultCount->fetch_assoc()['total'];

    $maxPage = ceil($numRows / $rowsPerPage);

    $sql = "
        SELECT Ma_san_pham, Ten_san_pham, So_luong, Don_gia, Mo_ta, Hinh_anh
        FROM san_pham
        LIMIT $offset, $rowsPerPage
    ";
}

$result = $conn->query($sql);

// ===========================================
// LẤY DANH SÁCH SẢN PHẨM BÁN CHẠY
// ===========================================
$sqlBanChay = "
SELECT sp.Ma_san_pham, sp.Ten_san_pham, sp.Hinh_anh, sp.Don_gia, 
SUM(ct.So_luong) AS Tong_da_ban
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
            echo '<img src="../admin/_images/' . htmlspecialchars($rowBC['Hinh_anh']) . '" alt=""><br>';
            echo '<strong>' . htmlspecialchars($rowBC['Ten_san_pham']) . '</strong>';
            echo '</a><br>';
            echo '<span>Giá: ' . number_format($rowBC['Don_gia']) . ' VND</span><br>';
            echo '<span>Đã bán: ' . htmlspecialchars($rowBC['Tong_da_ban']) . '</span><br>';
            echo '<button class="btn btn-primary btn-sm" onclick="themVaoGio(\'' . $rowBC['Ma_san_pham'] . '\')">Thêm vào giỏ hàng</button>';
            echo '</div>';
        }
        echo '</div>';
    } else {
        echo '<p>Không có sản phẩm nào.</p>';
    }
    ?>
    </div>
    <!-- Form tìm kiếm -->
    <div class="d-flex justify-content-end mb-6">
        <form method="post" action="" class="d-flex gap-2" style="max-width: 500px;">
            <input type="text" name="tukhoa" class="form-control form-control-sm" placeholder="Iphone 17..." required>
            <button type="submit" name="tim" class="btn btn-primary btn-lg">
                🔍
            </button>
        </form>
    </div>


    <!-- Layout -->
    <div id="container">
        <div id="main-layout">
            <div id="sidebar">
                <?php include('includes/lefter.php'); ?>
            </div>

            <div id="main-content">
                <div id="product-list">
                    <?php
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="product-item">';
                        echo '<a href="chi_tiet_san_pham.php?ma_san_pham=' . urlencode($row['Ma_san_pham']) . '">';
                        echo '<img src="../admin/_images/' . htmlspecialchars($row['Hinh_anh']) . '"><br>';
                        echo '<strong>' . htmlspecialchars($row['Ten_san_pham']) . '</strong>';
                        echo '</a><br>';
                        echo '<span>Giá: ' . number_format($row['Don_gia']) . ' VND</span><br>';

                        $so_luong = (int)$row['So_luong'];
                        echo $so_luong < 10 
                            ? "<span style='color:red;'>Số lượng: $so_luong</span><br>"
                            : "<span>Số lượng: $so_luong</span><br>";

                        echo '<p>' . htmlspecialchars(substr($row['Mo_ta'], 0, 60)) . '...</p>';
                        echo '<button class="btn btn-primary" onclick="themVaoGio(\'' . $row['Ma_san_pham'] . '\')">Thêm vào giỏ hàng</button>';
                        echo '</div>';
                    }
                } else {
                    echo "<p>Không tìm thấy sản phẩm nào.</p>";
                }
                ?>
                </div>

                <!-- PHÂN TRANG -->
                <div class="pagination">
                    <?php
                $param = $tukhoa != "" ? "&tukhoa=$tukhoa" : "";

                if ($currentPage > 1) {
                    echo "<a href='?page=" . ($currentPage - 1) . "$param' class='page-btn'>« Back</a>";
                }

                for ($i = 1; $i <= $maxPage; $i++) {
                    if ($i == $currentPage) {
                        echo "<span class='page-current'>Trang $i</span>";
                    } else {
                        echo "<a href='?page=$i$param' class='page-link'>Trang $i</a>";
                    }
                }

                if ($currentPage < $maxPage) {
                    echo "<a href='?page=" . ($currentPage + 1) . "$param' class='page-btn'>Next »</a>";
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