<?php
$id_detail = $_GET['id'];
$query_detail = "SELECT * FROM nhan_vien WHERE Ma_nhan_vien = '$id_detail'";
$query_detail_result = mysqli_query($conn, $query_detail);

if (isset($_POST['submit'])) {
    $ten = trim($_POST['Ten_nhan_vien']);
    $dia_chi = trim($_POST['Dia_chi']);
    $dien_thoai = $_POST['Dien_thoai'];
    $gioi_tinh = $_POST['Phai'];
    $chuc_vu = $_POST['Chuc_vu'];
    //Kiểm tra trùng
    $query_duplicate = "SELECT Dien_thoai, Ma_nhan_vien from nhan_vien where Dien_thoai = '$dien_thoai' and Ma_nhan_vien != '$id_detail'";
    $query_duplicate_result = mysqli_query($conn, $query_duplicate);
    $duplicate_result = mysqli_fetch_assoc($query_duplicate_result);

    if (mysqli_num_rows($query_duplicate_result) > 0) {
        echo '<div class="alert alert-danger">Số điện thoại đã tồn tại! Mã nhân viên: ' . $duplicate_result['Ma_nhan_vien'] . '</div>';
    } else {
        $query_update_detail = "
    UPDATE nhan_vien 
    SET 
        Ten_nhan_vien = '$ten',
        Phai = '$gioi_tinh',
        Dia_chi = '$dia_chi',
        Dien_thoai = '$dien_thoai',
        Chuc_vu = '$chuc_vu'
    WHERE Ma_nhan_vien = '$id_detail' ";
        $result_update = mysqli_query($conn, $query_update_detail);
        if ($result_update) {
            echo '
          <script>
              setTimeout(function() {
                  window.location.href = "index_admin.php?page=edit_user_employee&id=' . $id_detail . '";
              },);
          </script>';
        } else {
            echo "Lỗi cập nhật Nhân viên: " . mysqli_error($conn);
        }
    }
}


?>

<form method="post" action="" enctype="multipart/form-data">
    <h3>Cập nhât Nhân viên</h3>
    <?php while ($sp = mysqli_fetch_assoc($query_detail_result)) { ?>
        <div class="row mb-3">
            <div class="col-md-6">
                <label>Mã Nhân viên:</label>
                <input type="text" name="Ma_nhan_vien" class="form-control" value="<?php echo $sp['Ma_nhan_vien']; ?>" readonly>
            </div>
            <div class="col-md-6">
                <label>Tên Nhân viên:</label>
                <input type="text" name="Ten_nhan_vien" class="form-control" required value="<?php echo $sp['Ten_nhan_vien'] ?>">
            </div>
            <div class="col-md-6">
                <label>Giới tính</label>
                <select name="Phai" class="form-control" required>
                    <option value="">-- Chọn giới tính --</option>
                    <option value="1" <?php echo ($sp['Phai'] == 1) ? 'selected' : '' ?>> Nam </option>
                    <option value="0" <?php echo ($sp['Phai'] == 0) ? 'selected' : '' ?>> Nữ </option>
                </select>
            </div>

            <div class="col-md-6">
                <label>Địa chỉ:</label>
                <input type="text" name="Dia_chi" class="form-control" value="<?php echo $sp['Dia_chi']; ?>">
            </div>
            <div class="col-md-6">
                <label>Điện thoại:</label>
                <input type="number" min="0" onkeypress="if (event.key === '-' || event.key === '+') event.preventDefault();" name="Dien_thoai" class="form-control" required value="<?php echo $sp['Dien_thoai'] ?>">
            </div>
            <div class="col-md-6">
                <label>Chức vụ:</label>
                <input type="text" name="Chuc_vu" class="form-control" value="<?php echo $sp['Chuc_vu']; ?>">
            </div>
        </div>
        <button type="submit" name="submit" class="btn btn-primary" onclick="return confirm('Bạn có chắc muốn cập nhật Nhân viên này?')">Cập nhật Nhân viên</button>
        <a href="index_admin.php?page=list_user_employee" class="btn btn-secondary">Về trang Nhân viên</a>
    <?php } ?>
</form>