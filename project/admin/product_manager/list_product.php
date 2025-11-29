<?php
// --- Xoá sản phẩm ---
if (isset($_GET['Ma_san_pham'])) {
    $id = $_GET['Ma_san_pham'];

    // Xóa chi tiết hóa đơn liên quan
    // $delete_details = "DELETE FROM chi_tiet_hoa_don WHERE Ma_san_pham='$id'";
    // mysqli_query($conn, $delete_details);

    // Lấy tên ảnh của sản phẩm
    $query_image = "SELECT Hinh_anh FROM san_pham WHERE Ma_san_pham = '$id'";
    $result_query_image = mysqli_query($conn, $query_image);
    if ($row_image = mysqli_fetch_assoc($result_query_image)) {
        $file_name = $row_image['Hinh_anh'];
        $path = __DIR__ . "/../_images/" . $file_name;
        if (!empty($file_name) && file_exists($path)) {
            unlink($path);
        }
    }

    // Xóa sản phẩm
    $delete_product = "DELETE FROM san_pham WHERE Ma_san_pham='$id'";
    if (mysqli_query($conn, $delete_product)) {
        echo '<div id="alert-box" class="alert alert-success"
          style="position:fixed; top:20px; right:20px; z-index:9999;">
          </div>
          <script>
              setTimeout(function() {
                  document.getElementById("alert-box").remove();
                  window.location.href = "index_admin.php?page=list_product";
              }, );
          </script>';
    } else {
        echo "Lỗi xóa sản phẩm: " . mysqli_error($conn);
    }
}

// --- Tìm dữ liệu sản phẩm ---
$search_result = isset($_GET['search']) ? trim($_GET['search']) : '';
$where_search = '';
if ($search_result != '') {
    $where_search = "WHERE Ten_san_pham LIKE '%$search_result%'";
}

// --- Phân trang ---
$rows_per_page = 5;
$current_page = isset($_GET['page_num']) ? (int)$_GET['page_num'] : 1;
$start = ($current_page - 1) * $rows_per_page;

// Lấy dữ liệu để hiển thị
$query_to_show = "
SELECT 
    sp.*,
    lsp.Ten_loai,
    ncc.Ten_nha_cung_cap,
    ms.Ten_mau,
    xx.Ten_xuat_xu,
    bh.Thoi_gian AS Thoi_gian_bao_hanh,
    bh.Dieu_kien AS Dieu_kien_bao_hanh
FROM san_pham sp
LEFT JOIN loai_san_pham lsp 
    ON sp.Ma_loai = lsp.Ma_loai
LEFT JOIN nha_cung_cap ncc 
    ON sp.Ma_nha_cung_cap = ncc.Ma_nha_cung_cap
LEFT JOIN mau_sac ms
    ON sp.Ma_mau = ms.Ma_mau
LEFT JOIN xuat_xu xx
    ON sp.Ma_xuat_xu = xx.Ma_xuat_xu
LEFT JOIN bao_hanh bh
    ON sp.Ma_bao_hanh = bh.Ma_bao_hanh
$where_search
ORDER BY CAST(SUBSTRING(sp.Ma_san_pham, 3) AS UNSIGNED) ASC
LIMIT $start, $rows_per_page
";

$result_to_show = mysqli_query($conn, $query_to_show);

// Lấy tổng số dòng để tính tổng số trang
$query_count = "SELECT * FROM san_pham $where_search";
$result_count = mysqli_query($conn, $query_count);
$total_rows = mysqli_num_rows($result_count);
$total_pages = ceil($total_rows / $rows_per_page);

// // phân loại sản phẩm theo loại và nhà cung cấp
// // Lấy danh sách loại sản phẩm
// $query_loai = "SELECT Ma_loai, Ten_loai FROM loai_san_pham";
// $result_loai = mysqli_query($conn, $query_loai);

// // Lấy danh sách nhà cung cấp
// $query_ncc = "SELECT Ma_nha_cung_cap, Ten_nha_cung_cap FROM nha_cung_cap";
// $result_ncc = mysqli_query($conn, $query_ncc);
// $filter_ncc = isset($_GET['filter_ncc']) ? $_GET['filter_ncc'] : '';

?>


<div class="card-header py-3 d-flex justify-content-between align-items-center flex-wrap">
    <h6 class="m-0 font-weight-bold text-primary">Danh sách sản phẩm</h6>
    <form action="index_admin.php" method="get">
        <input type="hidden" name="page" value="list_product">

        <!-- Input tìm kiếm -->
        <div class="input-group mb-3" style="max-width: 400px; margin: 0 auto;">
            <input type="text" name="search" class="form-control" placeholder="Tìm tên sản phẩm..." value="<?php echo trim($search_result); ?>">
            <button class="btn btn-primary" type="submit">Tìm</button>
        </div>


    </form>


    <a href="index_admin.php?page=add_product" class="btn btn-success">Thêm sản phẩm</a>
</div>

<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>STT</th>
                <th>Mã SP</th>
                <th>Tên sản phẩm</th>
                <th>Mã loại</th>
                <th>Mã nhà cung cấp</th>
                <th>Mã màu</th>
                <th>Mã xuất xứ</th>
                <th>Mã bảo hành</th>
                <th>Số lượng</th>
                <th>Đơn giá</th>
                <th>Mô tả</th>
                <th>Cấu hình</th>
                <th>Hình ảnh</th>
                <th>Ngày tạo</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = $start + 1;
            while ($row = mysqli_fetch_assoc($result_to_show)) {
            ?>
                <tr>
                    <td><?php echo $i; ?></td>

                    <!-- Mã SP -->
                    <td><?php echo $row['Ma_san_pham']; ?></td>

                    <!-- Tên sản phẩm -->
                    <td><?php echo $row['Ten_san_pham']; ?></td>

                    <!-- Mã loại (kèm tên loại nếu JOIN) -->
                    <td><?php echo $row['Ma_loai'] . ' (' . $row['Ten_loai'] . ')'; ?></td>

                    <!-- Mã nhà cung cấp -->
                    <td><?php echo $row['Ma_nha_cung_cap'] . ' (' . $row['Ten_nha_cung_cap'] . ')'; ?></td>

                    <!-- Mã màu -->
                    <td><?php echo $row['Ma_mau'] . ' (' . $row['Ten_mau'] . ')'; ?></td>

                    <!-- Mã xuất xứ -->
                    <td><?php echo $row['Ma_xuat_xu'] . ' (' . $row['Ten_xuat_xu'] . ')'; ?></td>

                    <!-- Mã bảo hành -->
                    <td><?php echo $row['Ma_bao_hanh'] . ' (' . $row['Thoi_gian_bao_hanh'] . ')'; ?></td>

                    <!-- Số lượng -->
                    <td><?php echo $row['So_luong']; ?></td>

                    <!-- Đơn giá -->
                    <td><?php echo number_format($row['Don_gia'], 0); ?> VNĐ</td>

                    <!-- Mô tả -->
                    <td class="description"><?php echo $row['Mo_ta']; ?></td>

                    <!-- Cấu hình -->
                    <td class="description"><?php echo $row['Cau_hinh']; ?></td>

                    <!-- Hình ảnh -->
                    <td style="text-align:center;">
                        <?php
                        $imagePath = "_images/" . $row['Hinh_anh'];
                        $fullPath = __DIR__ . "/../_images/" . $row['Hinh_anh'];

                        if (!empty($row['Hinh_anh']) && file_exists($fullPath)) {
                            echo '<img src="' . $imagePath . '" alt="Ảnh sản phẩm" 
                        style="width:80px;height:80px;object-fit:cover;border-radius:8px;">';
                        } else {
                            echo '<span style="color:gray;">Không có ảnh</span>';
                        }
                        ?>
                    </td>

                    <!-- Ngày tạo -->
                    <td><?php echo $row['Ngay_tao']; ?></td>

                    <!-- Trạng thái -->
                    <?php if ($row['Trang_thai'] == 1): ?>
                        <td class="text-success">Đang bán</td>
                    <?php else: ?>
                        <td class="text-danger">Ngừng bán</td>
                    <?php endif; ?>

                    <!-- Hành động -->
                    <td>
                        <a href="index_admin.php?page=edit_product&id=<?php echo $row['Ma_san_pham']; ?>"
                            class="btn btn-sm btn-warning">Sửa</a>

                        <a href="index_admin.php?page=list_product&Ma_san_pham=<?php echo $row['Ma_san_pham']; ?>"
                            class="btn btn-sm btn-danger"
                            onclick="return confirm('Bạn có chắc muốn xoá sản phẩm này?')">Xoá</a>
                    </td>
                </tr>
            <?php
                $i++;
            }
            ?>

        </tbody>
    </table>


    <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center">

            <!-- Nút Previous -->
            <li class="page-item <?php if ($current_page <= 1) echo 'disabled'; ?>">
                <?php
                $prev_page = $current_page - 1;
                $prev_link = "index_admin.php?page=list_product&page_num=$prev_page";
                if ($search_result != '') $prev_link .= "&search=" . $search_result;
                ?>
                <a class="page-link" href="<?php echo $prev_link; ?>" tabindex="-1">Trước</a>
            </li>

            <!-- Các số trang -->
            <?php
            for ($p = 1; $p <= $total_pages; $p++) {
                $link = "index_admin.php?page=list_product&page_num=$p";
                if ($search_result != '') $link .= "&search=" . $search_result;

                $active = ($p == $current_page) ? 'active' : '';
                echo '<li class="page-item ' . $active . '"><a class="page-link" href="' . $link . '">' . $p . '</a></li>';
            }
            ?>

            <!-- Nút Next -->
            <li class="page-item <?php if ($current_page >= $total_pages) echo 'disabled'; ?>">
                <?php
                $next_page = $current_page + 1;
                $next_link = "index_admin.php?page=list_product&page_num=$next_page";
                if ($search_result != '') $next_link .= "&search=" . $search_result;
                ?>
                <a class="page-link" href="<?php echo $next_link; ?>">Sau</a>
            </li>

        </ul>
    </nav>

</div>

</div>