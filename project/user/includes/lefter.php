<?php
include('includes/ket_noi.php'); // kết nối CSDL
?>

<div class="sidebar">
    <h2>Menu</h2>

    <!-- Phần loại sản phẩm -->
    <h3> Loại sản phẩm</h3>
    <ul>
        <?php
        $sqlLoai = "SELECT * FROM loai_san_pham";
        $resultLoai = $conn->query($sqlLoai);
        if ($resultLoai->num_rows > 0) {
            echo '<li><a href="san_pham_theo_loai.php">Tất cả</a></li>';
            while ($rowLoai = $resultLoai->fetch_assoc()) {
                echo '<li><a href="san_pham_theo_loai.php?ma_loai=' . $rowLoai['Ma_loai'] . '">';
                echo $rowLoai['Ten_loai'];
                echo '</a></li>';
            }
        } else {
            echo '<li>Chưa có loại sản phẩm nào</li>';
        }
        ?>
    </ul>

    <!-- Phần nhà cung cấp -->
    <h3> Nhà cung cấp</h3>
    <ul>
        <?php
        $sqlNCC = "SELECT * FROM nha_cung_cap";
        $resultNCC = $conn->query($sqlNCC);
        if ($resultNCC->num_rows > 0) {
            echo '<li><a href="san_pham_theo_ncc.php">Tất cả</a></li>';
            while ($rowNCC = $resultNCC->fetch_assoc()) {
                echo '<li><a href="san_pham_theo_ncc.php?ma_nha_cung_cap=' . $rowNCC['Ma_nha_cung_cap'] . '">';
                echo $rowNCC['Ten_nha_cung_cap'];
                echo '</a></li>';
            }
        } else {
            echo '<li>Chưa có nhà cung cấp nào</li>';
        }
        ?>
    </ul>
</div>