<?php
// --- Xoá sản phẩm ---
if (isset($_GET['Ma_san_pham'])) {
    $id = $_GET['Ma_san_pham'];

    // Xóa chi tiết hóa đơn liên quan
    $delete_details = "DELETE FROM chi_tiet_hoa_don WHERE Ma_san_pham='$id'";
    mysqli_query($conn, $delete_details);
    // Lấy tên ảnh của sản phẩm
    $query_image = "SELECT Hinh_anh FROM san_pham WHERE Ma_san_pham = '$id'";
    $result_query_image = mysqli_query($conn, $query_image);
    if ($row_image = mysqli_fetch_assoc($result_query_image)) {
        $file_name = $row_image['Hinh_anh'];
        $path = __DIR__ . "/../_images/" . $file_name;
        if (file_exists($path)) {
            unlink($path); // xóa file
        }
    }

    // Xóa sản phẩm
    $delete_product = "DELETE FROM san_pham WHERE Ma_san_pham='$id'";
    if (mysqli_query($conn, $delete_product)) {
        echo '<div id="alert-box" class="alert alert-success"
          style="position:fixed; top:20px; right:20px; z-index:9999;">
          Xoá thành công
          </div>
          <script>
              setTimeout(function() {
                  document.getElementById("alert-box").remove();
                  window.location.href = "index_admin.php?page=list_product";
              }, 2000);
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
    ncc.Ten_nha_cung_cap
FROM san_pham sp
LEFT JOIN loai_san_pham lsp ON sp.Ma_loai = lsp.Ma_loai
LEFT JOIN nha_cung_cap ncc ON sp.Ma_nha_cung_cap = ncc.Ma_nha_cung_cap
$where_search
LIMIT $start, $rows_per_page
";
$result_to_show = mysqli_query($conn, $query_to_show);

// Lấy tổng số dòng để tính tổng số trang
$query_count = "SELECT * FROM san_pham $where_search";
$result_count = mysqli_query($conn, $query_count);
$total_rows = mysqli_num_rows($result_count);
$total_pages = ceil($total_rows / $rows_per_page);

// phân loại sản phẩm theo loại và nhà cung cấp
// Lấy danh sách loại sản phẩm
$query_loai = "SELECT Ma_loai, Ten_loai FROM loai_san_pham";
$result_loai = mysqli_query($conn, $query_loai);

// Lấy danh sách nhà cung cấp
$query_ncc = "SELECT Ma_nha_cung_cap, Ten_nha_cung_cap FROM nha_cung_cap";
$result_ncc = mysqli_query($conn, $query_ncc);
$filter_ncc = isset($_GET['filter_ncc']) ? $_GET['filter_ncc'] : '';

?>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center flex-wrap">
        <h6 class="m-0 font-weight-bold text-primary">Danh sách sản phẩm</h6>
        <form action="index_admin.php" method="get">
            <input type="hidden" name="page" value="list_product">

            <!-- Input tìm kiếm -->
            <div class="input-group mb-3" style="max-width: 400px; margin: 0 auto;">
                <input type="text" name="search" class="form-control" placeholder="Tìm tên sản phẩm..." value="<?php echo trim($search_result); ?>">
                <button class="btn btn-primary" type="submit">Tìm</button>
            </div>

            <!-- Dropdown lọc -->
            <div class="d-flex justify-content-center" style="gap: 10px; max-width: 500px; margin: 0 auto;">
                <!-- <select class="form-select" name="filter_ncc" style="min-width: 200px;">
                    <option value="">-- Chọn nhà cung cấp --</option>
                    <?php while ($row = mysqli_fetch_assoc($result_ncc)) {
                        $selected = ($row['Ma_nha_cung_cap'] == $filter_ncc) ? 'selected' : '';
                    ?>
                        <option value="<?php echo $row['Ma_nha_cung_cap']; ?>" <?php echo $selected; ?>>
                            <?php echo $row['Ten_nha_cung_cap']; ?>
                        </option>
                    <?php } ?>
                </select> -->

                <!-- <select class="form-select" name="filter_loai" style="min-width: 200px;">
                    <option selected>Lọc theo loại sản phẩm</option>
                    <option value="1">One</option>
                    <option value="2">Two</option>
                    <option value="3">Three</option>
                </select> -->
            </div>
        </form>


        <a href="index_admin.php?page=add_product" class="btn btn-success">Thêm sản phẩm</a>
    </div>


    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Mã SP</th>
                        <th>Tên SP</th>
                        <th>Mã loại</th>
                        <th>Số lượng</th>
                        <th>Đơn giá</th>
                        <th>Mã NCC</th>
                        <th>Mô tả</th>
                        <th>Hình ảnh</th>
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
                            <td><?php echo $row['Ma_san_pham']; ?></td>
                            <td><?php echo $row['Ten_san_pham']; ?></td>
                            <td><?php echo $row['Ma_loai'] . '(' . $row['Ten_loai'] . ')'; ?></td>
                            <td><?php echo $row['So_luong']; ?></td>
                            <td><?php echo number_format($row['Don_gia'], 0); ?> VNĐ</td>
                            <td><?php echo $row['Ma_nha_cung_cap'] . '(' . $row['Ten_nha_cung_cap'] . ')'; ?></td>
                            <td class="text-start" style="word-break: break-word; max-width: 200px;">
                                <?php echo $row['Mo_ta']; ?>
                            </td>
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
                            <td>
                                <a href="index_admin.php?page=edit_product&id=<?php echo $row['Ma_san_pham']; ?>" class="btn btn-sm btn-warning">Sửa</a>
                                <a href="index_admin.php?page=list_product&Ma_san_pham=<?php echo $row['Ma_san_pham']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc muốn xoá sản phẩm này?')">Xoá</a>
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
</div>