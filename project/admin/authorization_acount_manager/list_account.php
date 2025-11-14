<?php
// --- khoá tài khoản ---
if (isset($_GET['Ten_dang_nhap'])) {
    $id = $_GET['Ten_dang_nhap'];
    $query_check_status = "select Trang_thai from tai_khoan where Ten_dang_nhap = '$id'";
    $query_check_status_result = mysqli_query($conn, $query_check_status);
    $status = mysqli_fetch_assoc($query_check_status_result);

    if ($status['Trang_thai'] == 1) {
        // khoá tài khoản
        $query = "update tai_khoan set Trang_thai = 0 WHERE Ten_dang_nhap='$id'";
        if (mysqli_query($conn, $query)) {
            echo '<div id="alert-box" class="alert alert-success"
          style="position:fixed; top:20px; right:20px; z-index:9999;">
          </div>
          <script>
              setTimeout(function() {
                  document.getElementById("alert-box").remove();
                  window.location.href = "index_admin.php?page=list_account";
              });
          </script>';
        } else {
            echo "Lỗi khoá tài khoản: " . mysqli_error($conn);
        }
    } else {
        // mở tài khoản
        $query = "update tai_khoan set Trang_thai = 1 WHERE Ten_dang_nhap='$id'";
        if (mysqli_query($conn, $query)) {
            echo '<div id="alert-box" class="alert alert-success"
          style="position:fixed; top:20px; right:20px; z-index:9999;">
          </div>
          <script>
              setTimeout(function() {
                  document.getElementById("alert-box").remove();
                  window.location.href = "index_admin.php?page=list_account";
              });
          </script>';
        } else {
            echo "Lỗi mở tài khoản: " . mysqli_error($conn);
        }
    }
}

// --- Tìm dữ liệu tài khoản ---
$search_result = isset($_GET['search']) ? trim($_GET['search']) : '';
$where_search = '';
if ($search_result != '') {
    $where_search = "WHERE Ten_dang_nhap LIKE '%$search_result%'";
}

// --- Phân trang ---
$rows_per_page = 5;
$current_page = isset($_GET['page_num']) ? (int)$_GET['page_num'] : 1;
$start = ($current_page - 1) * $rows_per_page;

// Lấy dữ liệu để hiển thị
$query_to_show = "
SELECT tk.Ten_dang_nhap,
       tk.Mat_khau,
       tk.Ma_quyen,
       q.Ten_quyen,
       tk.Ma_nhan_vien,
       nv.Ten_nhan_vien,
       tk.Ma_khach_hang,
       kh.Ten_khach_hang,
       tk.Trang_thai,
       tk.Ngay_tao
FROM tai_khoan tk
LEFT JOIN nhan_vien nv ON tk.Ma_nhan_vien = nv.Ma_nhan_vien
LEFT JOIN khach_hang kh ON tk.Ma_khach_hang = kh.Ma_khach_hang
LEFT JOIN quyen q ON tk.Ma_quyen = q.Ma_quyen
$where_search
ORDER BY tk.Ngay_tao ASC
LIMIT $start, $rows_per_page
";

$result_to_show = mysqli_query($conn, $query_to_show);

// Lấy tổng số dòng để tính tổng số trang
$query_count = "SELECT * FROM tai_khoan $where_search";
$result_count = mysqli_query($conn, $query_count);
$total_rows = mysqli_num_rows($result_count);
$total_pages = ceil($total_rows / $rows_per_page);

?>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center flex-wrap">
        <h6 class="m-0 font-weight-bold text-primary">Danh sách tài khoản</h6>
        <form action="index_admin.php" method="get">
            <input type="hidden" name="page" value="list_account">
            <!-- Input tìm kiếm -->
            <div class="input-group mb-3" style="max-width: 400px; margin: 0 auto;">
                <input type="text" name="search" class="form-control" placeholder="Tìm tên đăng nhập..." value="<?php echo trim($search_result); ?>">
                <button class="btn btn-primary" type="submit">Tìm</button>
            </div>
        </form>
        <div class="row">
            <div class="col">
                <a href="index_admin.php?page=add_account_customer" class="btn btn-success">Thêm tài khoản khách hàng</a>
                <a href="index_admin.php?page=add_account_employee" class="btn btn-success">Thêm tài khoản nhân viên</a>
            </div>
        </div>

    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Tên đăng nhập</th>
                        <th>Mật khẩu</th>
                        <th>Mã quyền</th>
                        <th>Mã người dùng</th>
                        <th>Trạng thái</th>
                        <th>Ngày tạo</th>
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
                            <td><?php echo $row['Ten_dang_nhap']; ?></td>
                            <td class="description"><?php echo $row['Mat_khau']; ?></td>
                            <td><?php echo $row['Ma_quyen'] . " (" . $row['Ten_quyen'] . ")"; ?></td>

                            <td>
                                <?php
                                if ($row['Ma_nhan_vien'] !== NULL) {
                                    echo $row['Ma_nhan_vien'] . " (" . $row['Ten_nhan_vien'] . ")";
                                } else {
                                    echo $row['Ma_khach_hang'] . " (" . $row['Ten_khach_hang'] . ")";
                                }
                                ?>
                            </td>

                            <td>
                                <?php if ($row['Trang_thai'] == 1): ?>
                                    <p class='text-success'>Kích hoạt</p>
                                <?php else: ?>
                                    <p class='text-danger'>Bị khoá</p>
                                <?php endif; ?>
                            </td>
                            <td><?php echo $row['Ngay_tao']; ?></td>
                            <td>
                                <a href="index_admin.php?page=edit_account&id=<?php echo $row['Ten_dang_nhap']; ?>" class="btn btn-sm btn-warning">Sửa</a>
                                <?php if ($row['Trang_thai'] == 1): ?>
                                    <a href="index_admin.php?page=list_account&Ten_dang_nhap=<?php echo $row['Ten_dang_nhap']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc muốn khoá tài khoản này?')">Khoá</a>
                                <?php else: ?>
                                    <a href="index_admin.php?page=list_account&Ten_dang_nhap=<?php echo $row['Ten_dang_nhap']; ?>" class="btn btn-sm btn-success" onclick="return confirm('Bạn có chắc muốn mở tài khoản này?')">Mở</a>
                                <?php endif; ?>
                            </td>
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
                        $prev_link = "index_admin.php?page=list_account&page_num=$prev_page";
                        if ($search_result != '') $prev_link .= "&search=" . $search_result;
                        ?>
                        <a class="page-link" href="<?php echo $prev_link; ?>" tabindex="-1">Trước</a>
                    </li>

                    <!-- Các số trang -->
                    <?php
                    for ($p = 1; $p <= $total_pages; $p++) {
                        $link = "index_admin.php?page=list_account&page_num=$p";
                        if ($search_result != '') $link .= "&search=" . $search_result;

                        $active = ($p == $current_page) ? 'active' : '';
                        echo '<li class="page-item ' . $active . '"><a class="page-link" href="' . $link . '">' . $p . '</a></li>';
                    }
                    ?>

                    <!-- Nút Next -->
                    <li class="page-item <?php if ($current_page >= $total_pages) echo 'disabled'; ?>">
                        <?php
                        $next_page = $current_page + 1;
                        $next_link = "index_admin.php?page=list_account&page_num=$next_page";
                        if ($search_result != '') $next_link .= "&search=" . $search_result;
                        ?>
                        <a class="page-link" href="<?php echo $next_link; ?>">Sau</a>
                    </li>

                </ul>
            </nav>

        </div>
    </div>
</div>