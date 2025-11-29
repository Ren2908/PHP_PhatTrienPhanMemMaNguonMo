<?php
$id_detail = $_GET['id'];
$query_detail = "SELECT * FROM bao_hanh WHERE Ma_bao_hanh = '$id_detail'";
$query_detail_result = mysqli_query($conn, $query_detail);

// Xử lý submit form
if (isset($_POST['submit'])) {

    $ten = trim($_POST['Thoi_gian']);
    $dieu_kien = trim($_POST['Dieu_kien']);

    //Kiểm tra trùng
    $query_duplicate = "SELECT Ma_bao_hanh, Thoi_gian from bao_hanh where Thoi_gian = '$ten' and Ma_bao_hanh != '$id_detail'";
    $query_duplicate_result = mysqli_query($conn, $query_duplicate);
    $duplicate_result = mysqli_fetch_assoc($query_duplicate_result);

    if (mysqli_num_rows($query_duplicate_result) > 0) {
        echo '<div class="alert alert-danger">bảo hành đã tồn tại! Mã LSP: ' . $duplicate_result['Ma_bao_hanh'] . '</div>';
    } else {
        $query_update_detail = "
    UPDATE bao_hanh 
    SET 
        Thoi_gian = '$ten',
        Dieu_kien = '$dieu_kien'
    WHERE Ma_bao_hanh = '$id_detail' ";
        $result_update = mysqli_query($conn, $query_update_detail);
        if ($result_update) {
            echo '
          <script>
              setTimeout(function() {
                  window.location.href = "index_admin.php?page=edit_product_warranty&id=' . $id_detail . '";
              },);
          </script>';
        } else {
            echo "Lỗi cập nhật bảo hành: " . mysqli_error($conn);
        }
    }
}
?>

<form method="post" action="" enctype="multipart/form-data">
    <h3>Cập nhât bảo hành</h3>
    <?php while ($sp = mysqli_fetch_assoc($query_detail_result)) { ?>
        <div class="row mb-3">
            <div class="col-md-12">
                <label>Mã bảo hành:</label>
                <input type="text" name="Ma_bao_hanh_san_pham" class="form-control" value="<?php echo $sp['Ma_bao_hanh']; ?>" readonly>
            </div>
            <div class="col-md-12">
                <label>Thời gian:</label>
                <input type="number" name="Thoi_gian" class="form-control" required value="<?php echo $sp['Thoi_gian'] ?>">
            </div>
            <div class="col-md-12">
                <label>Điều kiện:</label>
                <input type="text" name="Dieu_kien" class="form-control" required value="<?php echo $sp['Dieu_kien'] ?>">
            </div>
        </div>
        <button type="submit" name="submit" class="btn btn-primary" onclick="return confirm('Bạn có chắc muốn cập nhật bảo hành này?')">Cập nhật bảo hành</button>
        <a href="index_admin.php?page=list_product_warranty" class="btn btn-secondary">Về trang bảo hành</a>
    <?php } ?>
</form>