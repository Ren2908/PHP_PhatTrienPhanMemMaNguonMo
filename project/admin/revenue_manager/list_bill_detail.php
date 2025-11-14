<?php
$id_detail = $_GET['id'];
$query_detail = "SELECT * FROM chi_tiet_hoa_don WHERE Ma_hoa_don = '$id_detail'";
$query_detail_result = mysqli_query($conn, $query_detail);
$query_detail_result_print = mysqli_fetch_assoc($query_detail_result);

// --- Tìm dữ liệu hoá đơn ---
$search_result = isset($_GET['search']) ? trim($_GET['search']) : '';
$where_search = '';
if ($search_result != '') {
    $where_search = "Ma_san_pham LIKE '%$search_result%'";
}

// --- Phân trang ---
$rows_per_page = 5;
$current_page = isset($_GET['page_num']) ? (int)$_GET['page_num'] : 1;
$start = ($current_page - 1) * $rows_per_page;

// Lấy dữ liệu để hiển thị
$query_to_show = "
SELECT 
    cthd.*, 
    sp.Ten_san_pham, 
    sp.Hinh_anh,
    ncc.Ten_nha_cung_cap
FROM chi_tiet_hoa_don cthd
LEFT JOIN san_pham sp ON cthd.Ma_san_pham = sp.Ma_san_pham
LEFT JOIN nha_cung_cap ncc ON sp.Ma_nha_cung_cap = ncc.Ma_nha_cung_cap
WHERE cthd.Ma_hoa_don = '$id_detail'"
    . ($where_search ? " AND $where_search" : "") . "
ORDER BY CAST(SUBSTRING(cthd.Ma_hoa_don, 3) AS UNSIGNED) ASC 
LIMIT $start, $rows_per_page
";

$result_to_show = mysqli_query($conn, $query_to_show);

// Lấy tổng số dòng để tính tổng số trang
$query_count = "
SELECT * FROM chi_tiet_hoa_don
WHERE Ma_hoa_don = '$id_detail'
" . ($where_search ? " AND $where_search" : "");

$result_count = mysqli_query($conn, $query_count);
$total_rows = mysqli_num_rows($result_count);
$total_pages = ceil($total_rows / $rows_per_page);

?>
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center flex-wrap">
        <h6 class="m-0 font-weight-bold text-primary">Danh sách chi tiết hoá đơn <?php echo $query_detail_result_print['Ma_hoa_don'] ?></h6>
        <form action="index_admin.php" method="get">
            <input type="hidden" name="page" value="list_bill_detail">
            <input type="hidden" name="id" value="<?php echo $id_detail; ?>">

            <!-- Input tìm kiếm -->
            <div class="input-group mb-3" style="max-width: 400px; margin: 0 auto;">
                <input type="text" name="search" class="form-control" placeholder="Tìm mã sản phẩm..." value="<?php echo trim($search_result); ?>">
                <button class="btn btn-primary" type="submit">Tìm</button>
            </div>
        </form>
        <a href="index_admin.php?page=list_bill" class="btn btn-secondary">Quay lại danh sách hoá đơn</a>

    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Mã sản phẩm</th>
                        <th>Tên sản phẩm</th>
                        <th>Nhà cung cấp</th>
                        <th>Hình ảnh</th>
                        <th>Số lượng</th>
                        <th>Đơn giá</th>
                        <th>Tổng đơn giá</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = $start + 1;
                    while ($row = mysqli_fetch_assoc($result_to_show)) {
                    ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo $row['Ma_san_pham']; ?></td>
                            <td><?php echo $row['Ten_san_pham']; ?></td>
                            <td><?php echo $row['Ten_nha_cung_cap']; ?></td>
                            <td style="text-align:center;">
                                <?php
                                $imagePath = "_images/" . $row['Hinh_anh'];
                                $fullPath = __DIR__ . "/../_images/" . $row['Hinh_anh'];
                                if (!empty($row['Hinh_anh']) && file_exists($fullPath)) {
                                    echo '<img src="' . $imagePath . '" alt="Ảnh sản phẩm" style="width:80px;height:80px;object-fit:cover;border-radius:8px;">';
                                } else {
                                    echo '<span style="color:gray;">Không có ảnh</span>';
                                }
                                ?>
                            </td>
                            <td><?php echo $row['So_luong']; ?></td>
                            <td><?php echo number_format($row['Don_gia'], 0, ',', '.'); ?></td>
                            <td><?php echo number_format($row['So_luong'] * $row['Don_gia'], 0, '.', ',') ?></td>
                        </tr>
                    <?php
                        $i++;
                    } ?>
                </tbody>
            </table>
            <!-- Phân trang với Bootstrap -->
            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center">

                    <!-- Nút Previous -->
                    <li class="page-item <?php if ($current_page <= 1) echo 'disabled'; ?>">
                        <?php
                        $prev_page = $current_page - 1;
                        $prev_link = "index_admin.php?page=list_bill_detail&id=$id_detail&page_num=$prev_page";
                        if ($search_result != '') $prev_link .= "&search=" . $search_result;
                        ?>
                        <a class="page-link" href="<?php echo $prev_link; ?>" tabindex="-1">Trước</a>
                    </li>

                    <!-- Các số trang -->
                    <?php
                    for ($p = 1; $p <= $total_pages; $p++) {
                        $link = "index_admin.php?page=list_bill_detail&id=$id_detail&page_num=$p";
                        if ($search_result != '') $link .= "&search=" . $search_result;

                        $active = ($p == $current_page) ? 'active' : '';
                        echo '<li class="page-item ' . $active . '"><a class="page-link" href="' . $link . '">' . $p . '</a></li>';
                    }
                    ?>

                    <!-- Nút Next -->
                    <li class="page-item <?php if ($current_page >= $total_pages) echo 'disabled'; ?>">
                        <?php
                        $next_page = $current_page + 1;
                        $next_link = "index_admin.php?page=list_bill_detail&id=$id_detail&page_num=$next_page";
                        if ($search_result != '') $next_link .= "&search=" . $search_result;
                        ?>
                        <a class="page-link" href="<?php echo $next_link; ?>">Sau</a>
                    </li>

                </ul>
            </nav>

        </div>
    </div>
</div>