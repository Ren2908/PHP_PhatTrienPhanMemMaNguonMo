<?php
// --- Xoá nhà cung cấp ---
if (isset($_GET['Ma_nha_cung_cap'])) {
    $id = $_GET['Ma_nha_cung_cap'];
    // Lấy tên ảnh của sản phẩm
    $query_image = "SELECT Hinh_anh FROM san_pham WHERE Ma_nha_cung_cap = '$id'";
    $result_query_image = mysqli_query($conn, $query_image);
    while ($row_image = mysqli_fetch_assoc($result_query_image)) {
        $file_name = $row_image['Hinh_anh'];
        $path = __DIR__ . "/../_images/" . $file_name;
        if (file_exists($path) && !empty($file_name)) {
            unlink($path); // xóa file
        }
    }

    // Xóa nhà cung cấp
    $delete_product_cat = "DELETE FROM nha_cung_cap WHERE Ma_nha_cung_cap='$id'";
    if (mysqli_query($conn, $delete_product_cat)) {
        echo '<div id="alert-box" class="alert alert-success"
          style="position:fixed; top:20px; right:20px; z-index:9999;">
          </div>
          <script>
              setTimeout(function() {
                  document.getElementById("alert-box").remove();
                  window.location.href = "index_admin.php?page=list_product_brand";
              });
          </script>';
    } else {
        echo "Lỗi xóa nhà cung cấp: " . mysqli_error($conn);
    }
}

// --- Tìm dữ liệu nhà cung cấp ---
$search_result = isset($_GET['search']) ? trim($_GET['search']) : '';
$where_search = '';
if ($search_result != '') {
    $where_search = "WHERE Ten_nha_cung_cap LIKE '%$search_result%'";
}

// --- Phân trang ---
$rows_per_page = 5;
$current_page = isset($_GET['page_num']) ? (int)$_GET['page_num'] : 1;
$start = ($current_page - 1) * $rows_per_page;

// Lấy dữ liệu để hiển thị
$query_to_show = "
SELECT * FROM nha_cung_cap
$where_search
ORDER BY CAST(SUBSTRING(Ma_nha_cung_cap, 4) AS UNSIGNED) ASC
LIMIT $start, $rows_per_page
";
$result_to_show = mysqli_query($conn, $query_to_show);

// Lấy tổng số dòng để tính tổng số trang
$query_count = "SELECT * FROM nha_cung_cap $where_search";
$result_count = mysqli_query($conn, $query_count);
$total_rows = mysqli_num_rows($result_count);
$total_pages = ceil($total_rows / $rows_per_page);

?>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center flex-wrap">
        <h6 class="m-0 font-weight-bold text-primary">Danh sách nhà cung cấp</h6>
        <form action="index_admin.php" method="get">
            <input type="hidden" name="page" value="list_product_brand">
            <!-- Input tìm kiếm -->
            <div class="input-group mb-3" style="max-width: 400px; margin: 0 auto;">
                <input type="text" name="search" class="form-control" placeholder="Tìm tên nhà cung cấp..." value="<?php echo trim($search_result); ?>">
                <button class="btn btn-primary" type="submit">Tìm</button>
            </div>
        </form>
        <a href="index_admin.php?page=add_product_brand" class="btn btn-success">Thêm nhà cung cấp</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Mã nhà cung cấp</th>
                        <th>Tên nhà cung cấp</th>
                        <th>Địa chỉ</th>
                        <th>Điện thoại</th>
                        <th>Email</th>
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
                            <td><?php echo $row['Ma_nha_cung_cap']; ?></td>
                            <td><?php echo $row['Ten_nha_cung_cap']; ?></td>
                            <td><?php echo $row['Dia_chi']; ?></td>
                            <td><?php echo $row['Dien_thoai']; ?></td>
                            <td><?php echo $row['Email']; ?></td>
                            <td>
                                <a href="index_admin.php?page=edit_product_brand&id=<?php echo $row['Ma_nha_cung_cap']; ?>" class="btn btn-sm btn-warning">Sửa</a>
                                <a href="index_admin.php?page=list_product_brand&Ma_nha_cung_cap=<?php echo $row['Ma_nha_cung_cap']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc muốn xoá nhà cung cấp này?')">Xoá</a>
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
                        $prev_link = "index_admin.php?page=list_product_brand&page_num=$prev_page";
                        if ($search_result != '') $prev_link .= "&search=" . $search_result;
                        ?>
                        <a class="page-link" href="<?php echo $prev_link; ?>" tabindex="-1">Trước</a>
                    </li>

                    <!-- Các số trang -->
                    <?php
                    for ($p = 1; $p <= $total_pages; $p++) {
                        $link = "index_admin.php?page=list_product_brand&page_num=$p";
                        if ($search_result != '') $link .= "&search=" . $search_result;

                        $active = ($p == $current_page) ? 'active' : '';
                        echo '<li class="page-item ' . $active . '"><a class="page-link" href="' . $link . '">' . $p . '</a></li>';
                    }
                    ?>

                    <!-- Nút Next -->
                    <li class="page-item <?php if ($current_page >= $total_pages) echo 'disabled'; ?>">
                        <?php
                        $next_page = $current_page + 1;
                        $next_link = "index_admin.php?page=list_product_brand&page_num=$next_page";
                        if ($search_result != '') $next_link .= "&search=" . $search_result;
                        ?>
                        <a class="page-link" href="<?php echo $next_link; ?>">Sau</a>
                    </li>

                </ul>
            </nav>

        </div>
    </div>
</div>