<?php
$id_detail = $_GET['id'];
$query_detail = "SELECT * FROM loai_san_pham WHERE Ma_loai = '$id_detail'";
$query_detail_result = mysqli_query($conn, $query_detail);

// Xử lý submit form
if (isset($_POST['submit'])) {

    $ten = trim($_POST['Ten_loai_san_pham']);

    //Kiểm tra trùng
    $query_duplicate = "SELECT Ma_loai, Ten_loai from loai_san_pham where Ten_loai = '$ten' and Ma_loai != '$id_detail'";
    $query_duplicate_result = mysqli_query($conn, $query_duplicate);
    $duplicate_result = mysqli_fetch_assoc($query_duplicate_result);

    if (mysqli_num_rows($query_duplicate_result) > 0) {
        echo '<div class="alert alert-danger">Loại sản phẩm đã tồn tại! Mã LSP: ' . $duplicate_result['Ma_loai'] . '</div>';
    } else {
        $query_update_detail = "
    UPDATE loai_san_pham 
    SET 
        Ten_loai = '$ten'
    WHERE Ma_loai = '$id_detail' ";
        $result_update = mysqli_query($conn, $query_update_detail);
        if ($result_update) {
            echo '
          <script>
              setTimeout(function() {
                  window.location.href = "index_admin.php?page=edit_product_category&id=' . $id_detail . '";
              },);
          </script>';
        } else {
            echo "Lỗi cập nhật loại sản phẩm: " . mysqli_error($conn);
        }
    }
}
?>

<form method="post" action="" enctype="multipart/form-data">
    <h3>Cập nhât loại sản phẩm</h3>
    <?php while ($sp = mysqli_fetch_assoc($query_detail_result)) { ?>
        <div class="row mb-3">
            <div class="col-md-12">
                <label>Mã loại sản phẩm:</label>
                <input type="text" name="Ma_loai_san_pham" class="form-control" value="<?php echo $sp['Ma_loai']; ?>" readonly>
            </div>
            <div class="col-md-12">
                <label>Tên loại sản phẩm:</label>
                <input type="text" name="Ten_loai_san_pham" class="form-control" required value="<?php echo $sp['Ten_loai'] ?>">
            </div>
        </div>
        <button type="submit" name="submit" class="btn btn-primary" onclick="return confirm('Bạn có chắc muốn cập nhật loại sản phẩm này?')">Cập nhật loại sản phẩm</button>
        <a href="index_admin.php?page=list_product_category" class="btn btn-secondary">Về trang loại sản phẩm</a>
    <?php } ?>
</form>