<?php
// --- Xoá hoá đơn ---
if (isset($_GET['Ma_hoa_don'])) {
    $id = $_GET['Ma_hoa_don'];
    //Cập nhật số lượng sản phẩm từ mã sản phẩm
    $query_CTHD = "select Ma_san_pham, So_luong from chi_tiet_hoa_don where Ma_hoa_don = '$id'";
    $query_CTHD_result = mysqli_query($conn, $query_CTHD);
    while ($row = mysqli_fetch_assoc($query_CTHD_result)) {
        $maSP = $row['Ma_san_pham'];
        $soLuong = $row['So_luong'];
        $query_update_SoLuongSP = "update san_pham set So_luong = So_luong + $soLuong where Ma_san_pham = '$maSP'";
        if (!mysqli_query($conn, $query_update_SoLuongSP)) {
            echo "Lỗi biến query_update_SoLuongSP: " . mysqli_error($conn);
        }
    }
    // Xóa hoá đơn
    $delete_product_cat = "DELETE FROM hoa_don WHERE Ma_hoa_don='$id'";
    if (mysqli_query($conn, $delete_product_cat)) {
        echo '<div id="alert-box" class="alert alert-success"
          style="position:fixed; top:20px; right:20px; z-index:9999;">
          </div>
          <script>
              setTimeout(function() {
                  document.getElementById("alert-box").remove();
                  window.location.href = "index_admin.php?page=list_bill";
              });
          </script>';
    } else {
        echo "Lỗi xóa hoá đơn: " . mysqli_error($conn);
    }
}
// Cập nhật trạng thái hoá đơn
if (isset($_POST['Ma_hoa_don']) && isset($_POST['Trang_thai'])) {

    $id = $_POST['Ma_hoa_don'];
    $status = $_POST['Trang_thai'];

    $sql = "UPDATE hoa_don SET Trang_thai = '$status' WHERE Ma_hoa_don = '$id'";

    if (mysqli_query($conn, $sql)) {

        echo '<div id="alert-box" class="alert alert-success"
                style="position:fixed; top:20px; right:20px; z-index:9999;">

              </div>

              <script>
                  setTimeout(function() {
                      document.getElementById("alert-box").remove();
                      window.location.href = "index_admin.php?page=list_bill";
                  });
              </script>';
    } else {
        echo '<div class="alert alert-danger">Lỗi: ' . mysqli_error($conn) . '</div>';
    }
}


// --- Tìm dữ liệu hoá đơn ---
$search_result = isset($_GET['search']) ? trim($_GET['search']) : '';
$where_search = '';
if ($search_result != '') {
    $where_search = "WHERE Ma_khach_hang LIKE '%$search_result%'";
}

// --- Phân trang ---
$rows_per_page = 10;
$current_page = isset($_GET['page_num']) ? (int)$_GET['page_num'] : 1;
$start = ($current_page - 1) * $rows_per_page;

// Lấy dữ liệu để hiển thị
$query_to_show = "
SELECT * FROM hoa_don
$where_search
ORDER BY Ngay_tao DESC
LIMIT $start, $rows_per_page
";
$result_to_show = mysqli_query($conn, $query_to_show);

// Lấy tổng số dòng để tính tổng số trang
$query_count = "SELECT * FROM hoa_don $where_search";
$result_count = mysqli_query($conn, $query_count);
$total_rows = mysqli_num_rows($result_count);
$total_pages = ceil($total_rows / $rows_per_page);

?>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center flex-wrap">
        <h6 class="m-0 font-weight-bold text-primary">Danh sách hoá đơn</h6>
        <form action="index_admin.php" method="get">
            <input type="hidden" name="page" value="list_bill">
            <!-- Input tìm kiếm -->
            <div class="input-group mb-3" style="max-width: 400px; margin: 0 auto;">
                <input type="text" name="search" class="form-control" placeholder="Tìm mã khách hàng..." value="<?php echo trim($search_result); ?>">
                <button class="btn btn-primary" type="submit">Tìm</button>
            </div>
        </form>
        <a href="index_admin.php?page=add_bill" class="btn btn-success">Thêm hoá đơn</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Mã hoá đơn</th>
                        <th>Mã khách hàng</th>
                        <th>Mã nhân viên</th>
                        <th>Ngày tạo</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Loại đơn hàng</th>
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
                            <td><?php echo $row['Ma_hoa_don']; ?></td>
                            <td><?php echo $row['Ma_khach_hang']; ?></td>
                            <?php if ($row['Ma_nhan_vien'] == null): ?>
                                <td class="text-success">Được đặt online</td>
                            <?php else: ?>
                                <td><?php echo $row['Ma_nhan_vien']; ?></td>
                            <?php endif; ?>
                            <td><?php echo $row['Ngay_tao']; ?></td>
                            <td><?php echo number_format($row['Tong_tien'], 0, ',', '.'); ?></td>
                            <?php
                            if ($row['Trang_thai'] == 0) {
                                echo '<td class="text-warning">Chờ xác nhận</td>';
                            } elseif ($row['Trang_thai'] == 1) {
                                echo '<td class="text-primary">Đã xác nhận</td>';
                            } elseif ($row['Trang_thai'] == 2) {
                                echo '<td class="text-info">Đã giao cho vận chuyển</td>';
                            } elseif ($row['Trang_thai'] == 3) {
                                echo '<td class="text-success">Đã hoàn thành</td>';
                            } else {
                                echo '<td class="text-danger">Đã huỷ</td>';
                            }
                            ?>
                            <?php
                            if ($row['Loai_don_hang'] == 0) {
                                echo '<td class="text-primary-emphasis">OFFLINE</td>';
                            } else {
                                echo '<td class="text-primary">ONLINE</td>';
                            }
                            ?>
                            <td>
                                <a href="index_admin.php?page=list_bill_detail&id=<?php echo $row['Ma_hoa_don']; ?>" class="btn btn-sm btn-warning">Chi tiết</a>
                                <?php if (isset($_SESSION['ma_quyen']) && $_SESSION['ma_quyen'] == 'Q1' && $row['Trang_thai'] != 3 && $row['Trang_thai'] != 4): ?>
                                    <a href="index_admin.php?page=list_bill&Ma_hoa_don=<?php echo $row['Ma_hoa_don']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc muốn xoá hoá đơn này?')">Xoá</a>
                                    <form method="POST" action="index_admin.php?page=list_bill" style="display:inline-block;">
                                        <input type="hidden" name="Ma_hoa_don" value="<?php echo $row['Ma_hoa_don']; ?>">

                                        <select name="Trang_thai" class="form-control form-control-sm"
                                            onchange="this.form.submit()">
                                            <option value="0" <?php echo $row['Trang_thai'] == 0 ? 'selected' : ''; ?>>Chờ xác nhận</option>
                                            <option value="1" <?php echo $row['Trang_thai'] == 1 ? 'selected' : ''; ?>>Đã xác nhận</option>
                                            <option value="2" <?php echo $row['Trang_thai'] == 2 ? 'selected' : ''; ?>>Đã giao cho vận chuyển</option>
                                            <option value="3" <?php echo $row['Trang_thai'] == 3 ? 'selected' : ''; ?>>Đã hoàn thành</option>
                                            <option value="4" <?php echo $row['Trang_thai'] == 4 ? 'selected' : ''; ?>>Đã huỷ</option>
                                        </select>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php
                        $i++;
                    } ?>
                </tbody>
            </table>
            <!-- Phân trang với Bootstrap -->


        </div>
        <div class="pagination-wrapper">
            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center">

                    <!-- Nút Previous -->
                    <li class="page-item <?php if ($current_page <= 1) echo 'disabled'; ?>">
                        <?php
                        $prev_page = $current_page - 1;
                        $prev_link = "index_admin.php?page=list_bill&page_num=$prev_page";
                        if ($search_result != '') $prev_link .= "&search=" . $search_result;
                        ?>
                        <a class="page-link" href="<?php echo $prev_link; ?>" tabindex="-1">Trước</a>
                    </li>

                    <!-- Các số trang -->
                    <?php
                    for ($p = 1; $p <= $total_pages; $p++) {
                        $link = "index_admin.php?page=list_bill&page_num=$p";
                        if ($search_result != '') $link .= "&search=" . $search_result;

                        $active = ($p == $current_page) ? 'active' : '';
                        echo '<li class="page-item ' . $active . '"><a class="page-link" href="' . $link . '">' . $p . '</a></li>';
                    }
                    ?>

                    <!-- Nút Next -->
                    <li class="page-item <?php if ($current_page >= $total_pages) echo 'disabled'; ?>">
                        <?php
                        $next_page = $current_page + 1;
                        $next_link = "index_admin.php?page=list_bill&page_num=$next_page";
                        if ($search_result != '') $next_link .= "&search=" . $search_result;
                        ?>
                        <a class="page-link" href="<?php echo $next_link; ?>">Sau</a>
                    </li>

                </ul>
            </nav>
        </div>
    </div>
</div>