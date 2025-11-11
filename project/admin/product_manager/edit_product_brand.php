<?php
$id_detail = $_GET['id'];
$query_detail = "SELECT * FROM nha_cung_cap WHERE Ma_nha_cung_cap = '$id_detail'";
$query_detail_result = mysqli_query($conn, $query_detail);

if (isset($_POST['submit'])) {
    $ten_ncc = trim($_POST['Ten_nha_cung_cap']);
    $dia_chi = trim($_POST['Dia_chi']);
    $dien_thoai = $_POST['Dien_thoai'];
    $email = $_POST['Email'];
    //Kiểm tra trùng
    $query_duplicate = "SELECT Ten_nha_cung_cap, Ma_nha_cung_cap from nha_cung_cap where Ten_nha_cung_cap = '$ten_ncc' and Ma_nha_cung_cap != '$id_detail'";
    $query_duplicate_result = mysqli_query($conn, $query_duplicate);
    $duplicate_result = mysqli_fetch_assoc($query_duplicate_result);

    if (mysqli_num_rows($query_duplicate_result) > 0) {
        echo '<div class="alert alert-danger">Tên nhà cung cấp đã tồn tại! Mã NCC: ' . $duplicate_result['Ma_nha_cung_cap'] . '</div>';
    } else {
        $query_update_detail = "
    UPDATE nha_cung_cap 
    SET 
        Ten_nha_cung_cap = '$ten_ncc',
        Dia_chi = '$dia_chi',
        Dien_thoai = '$dien_thoai',
        Email = '$email'
    WHERE Ma_nha_cung_cap = '$id_detail' ";
        $result_update = mysqli_query($conn, $query_update_detail);
        if ($result_update) {
            echo '
          <script>
              setTimeout(function() {
                  window.location.href = "index_admin.php?page=edit_product_brand&id=' . $id_detail . '";
              },);
          </script>';
        } else {
            echo "Lỗi cập nhật nhà cung cấp: " . mysqli_error($conn);
        }
    }
}


?>

<form method="post" action="" enctype="multipart/form-data">
    <h3>Cập nhât nhà cung cấp</h3>
    <?php while ($sp = mysqli_fetch_assoc($query_detail_result)) { ?>
        <div class="row mb-3">
            <div class="col-md-12">
                <label>Mã nhà cung cấp:</label>
                <input type="text" name="Ma_nha_cung_cap" class="form-control" value="<?php echo $sp['Ma_nha_cung_cap']; ?>" readonly>
            </div>
            <div class="col-md-6">
                <label>Tên nhà cung cấp:</label>
                <input type="text" name="Ten_nha_cung_cap" class="form-control" required value="<?php echo $sp['Ten_nha_cung_cap'] ?>">
            </div>
            <div class="col-md-6">
                <label>Địa chỉ:</label>
                <input type="text" name="Dia_chi" class="form-control" value="<?php echo $sp['Dia_chi']; ?>">
            </div>
            <div class="col-md-6">
                <label>Điện thoại:</label>
                <input type="number" name="Dien_thoai" class="form-control" required value="<?php echo $sp['Dien_thoai'] ?>">
            </div>
            <div class="col-md-6">
                <label>Email:</label>
                <input type="email" name="Email" class="form-control" value="<?php echo $sp['Email']; ?>">
            </div>
        </div>
        <button type="submit" name="submit" class="btn btn-primary" onclick="return confirm('Bạn có chắc muốn cập nhật nhà cung cấp này?')">Cập nhật nhà cung cấp</button>
        <a href="index_admin.php?page=list_product_brand" class="btn btn-secondary">Về trang nhà cung cấp</a>
    <?php } ?>
</form>