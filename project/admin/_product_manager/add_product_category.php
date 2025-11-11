<?php
// --- Tạo mã SP tự động ---
$query_max = "SELECT MAX(CAST(SUBSTRING(Ma_loai, 2) AS UNSIGNED)) AS max_id FROM loai_san_pham";
$result_max = mysqli_query($conn, $query_max);
$row_max = mysqli_fetch_assoc($result_max);

$new_lsp = 'L' . ($row_max['max_id'] + 1);

// Xử lý submit form
if (isset($_POST['submit'])) {
    $ten_lsp = trim($_POST['Ten_loai_san_pham']);
    //Kiểm tra trùng
    $query_duplicate = "SELECT Ma_loai, Ten_loai from loai_san_pham where Ten_loai = '$ten_lsp'";
    $query_duplicate_result = mysqli_query($conn, $query_duplicate);
    $duplicate_result = mysqli_fetch_assoc($query_duplicate_result);

    if (mysqli_num_rows($query_duplicate_result) > 0) {
        echo '<div class="alert alert-danger">Loại sản phẩm đã tồn tại! Mã LSP: ' . $duplicate_result['Ma_loai'] . '</div>';
    } else {
        $insert = "INSERT INTO loai_san_pham (Ma_loai, Ten_loai)
               VALUES ('$new_lsp', '$ten_lsp')";

        if (mysqli_query($conn, $insert)) {
            echo '<div class="alert alert-success">Thêm loại sản phẩm thành công! Mã LSP: ' . $new_lsp . '</div>';
        } else {
            echo '<div class="alert alert-danger">Lỗi: ' . mysqli_error($conn) . '</div>';
        }
    }
}
?>

<h3>Thêm loại sản phẩm mới</h3>
<form action="" method="post" enctype="multipart/form-data" class="mt-3">
    <div class="row mb-3">
        <div class="col-md-12">
            <label>Mã loại sản phẩm:</label>
            <input type="text" name="Ma_loai_san_pham" class="form-control" value="<?php echo $new_lsp; ?>" readonly>
        </div>
        <div class="col-md-12">
            <label>Tên loại sản phẩm:</label>
            <input type="text" name="Ten_loai_san_pham" class="form-control" required value="<?php echo isset($_POST['Ten_loai_san_pham']) ? htmlspecialchars($_POST['Ten_loai_san_pham']) : ''; ?>">
        </div>
    </div>

    <button type="submit" name="submit" class="btn btn-primary">Thêm loại sản phẩm</button>
    <a href="index_admin.php?page=list_product_category">Về trang loại sản phẩm</a>
</form>