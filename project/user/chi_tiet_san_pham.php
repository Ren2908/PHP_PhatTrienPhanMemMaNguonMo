<?php
$page_title = 'Chi tiết sản phẩm';
include('includes/header.php');
include('includes/ket_noi.php');

if (isset($_GET['ma_san_pham'])) {
    $id = $_GET['ma_san_pham'];

    $sql = "SELECT * FROM san_pham WHERE Ma_san_pham = '$id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
      
        $maLoai = $row['Ma_loai'] ?? null; 
        
       
        if ($maLoai) {
            $sqlCungLoai = "SELECT Ma_san_pham, Ten_san_pham, Hinh_anh, Don_gia, So_luong 
                            FROM san_pham 
                            WHERE Ma_loai = '$maLoai' AND Ma_san_pham != '$id'
                            LIMIT 8";
        } else {
            $sqlCungLoai = "SELECT Ma_san_pham, Ten_san_pham, Hinh_anh, Don_gia, So_luong 
                            FROM san_pham 
                            WHERE Ma_san_pham != '$id'
                            ORDER BY RAND()
                            LIMIT 8";
        }
        $resultCungLoai = $conn->query($sqlCungLoai);
    } 
}
?>

<div id="container">
    <div id="main-layout">
        <div id="sidebar">
            <?php include('includes/lefter.php'); ?>
        </div>

        <div id="main-content">
            <div id="product-detail">
                <div class="detail-container">
                    <div class="detail-image">
                        <img src="images/<?php echo $row['Hinh_anh']; ?>"
                            alt="<?php echo $row['Ten_san_pham']; ?>">
                    </div>

                    <div class="detail-info">
                        <h1 class="product-title"><?php echo $row['Ten_san_pham']; ?></h1>

                        <div class="info-item">
                            <span class="info-label">Giá:</span>
                            <span class="info-value price"><?php echo number_format($row['Don_gia'], 0, ',', '.'); ?>
                                VND</span>
                        </div>

                        <div class="info-item">
                            <span class="info-label">Số lượng:</span>
                            <span class="info-value stock"><?php echo $row['So_luong']; ?></span>
                        </div>

                        <div class="info-item description-section">
                            <h3>Mô tả chi tiết:</h3>
                            <p class="description-text"><?php echo nl2br($row['Mo_ta']); ?></p>
                        </div>

                        <div class="action-buttons">
                            <a href="index.php" class="btn back-btn">← Quay lại danh sách</a>

                            <!-- Nút thêm vào giỏ hàng -->
                            <button type="button" class="btn btn-primary"
                                onclick="themVaoGio('<?php echo $row['Ma_san_pham']; ?>')">
                                Thêm vào giỏ hàng
                            </button>
                        </div>

                    </div>
                </div>
            </div>

            <!-- SẢN PHẨM CÙNG LOẠI -->
            <div class="best-seller-box" style="margin-top: 40px;">
                <h2>Sản phẩm tương tự</h2>
                <?php
                if ($resultCungLoai && $resultCungLoai->num_rows > 0) {
                    echo '<div class="product-list">';
                    while ($rowCL = $resultCungLoai->fetch_assoc()) {
                        echo '<div class="product-item">';
                        echo '<a href="chi_tiet_san_pham.php?ma_san_pham=' . urlencode($rowCL['Ma_san_pham']) . '">';
                        echo '<img src="images/' . htmlspecialchars($rowCL['Hinh_anh']) . '" alt="' . htmlspecialchars($rowCL['Ten_san_pham']) . '"><br>';
                        echo '<strong>' . htmlspecialchars($rowCL['Ten_san_pham']) . '</strong>';
                        echo '</a><br>';
                        echo '<span>Giá: ' . number_format($rowCL['Don_gia'], 0, ',', '.') . ' VND</span><br>';
                        echo '<span>Còn lại: ' . htmlspecialchars($rowCL['So_luong']) . '</span><br>';
                        echo '<button type="button" class="btn btn-primary" onclick="themVaoGio(\'' . $rowCL['Ma_san_pham'] . '\')">Thêm vào giỏ hàng</button>';
                        echo '</div>';
                    }
                    echo '</div>';
                } else {
                    echo '<p style="text-align:center; color:#fff;">Không có sản phẩm cùng loại.</p>';
                }
                ?>
            </div>
        </div>
    </div>
</div>

<?php
include('includes/footer.html');
?>
<script src="./js/gio_hang.js"></script>