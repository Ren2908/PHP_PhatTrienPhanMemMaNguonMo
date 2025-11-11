<?php
$id_detail = $_GET['id'];
$query_detail = "SELECT * FROM khach_hang WHERE Ma_khach_hang = '$id_detail'";
$query_detail_result = mysqli_query($conn, $query_detail);

if (isset($_POST['submit'])) {
    $ten = trim($_POST['Ten_khach_hang']);
    $dia_chi = trim($_POST['Dia_chi']);
    $dien_thoai = $_POST['Dien_thoai'];
    $gioi_tinh = $_POST['Phai'];
    $email = $_POST['Email'];
    $query_update_detail = "
    UPDATE khach_hang 
    SET 
        Ten_khach_hang = '$ten',
        Phai = '$gioi_tinh',
        Dia_chi = '$dia_chi',
        Dien_thoai = '$dien_thoai',
        Email = '$email'
    WHERE Ma_khach_hang = '$id_detail' ";
    $result_update = mysqli_query($conn, $query_update_detail);
    if ($result_update) {
        echo '
          <script>
              setTimeout(function() {
                  window.location.href = "index_admin.php?page=edit_user_customer&id=' . $id_detail . '";
              },);
          </script>';
    } else {
        echo "Lỗi cập nhật khách hàng: " . mysqli_error($conn);
    }
}


?>

<form method="post" action="" enctype="multipart/form-data">
    <h3>Cập nhât khách hàng</h3>
    <?php while ($sp = mysqli_fetch_assoc($query_detail_result)) { ?>
        <div class="row mb-3">
            <div class="col-md-6">
                <label>Mã khách hàng:</label>
                <input type="text" name="Ma_khach_hang" class="form-control" value="<?php echo $sp['Ma_khach_hang']; ?>" readonly>
            </div>
            <div class="col-md-6">
                <label>Tên khách hàng:</label>
                <input type="text" name="Ten_khach_hang" class="form-control" required value="<?php echo $sp['Ten_khach_hang'] ?>">
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
                <input type="number" name="Dien_thoai" class="form-control" required value="<?php echo $sp['Dien_thoai'] ?>">
            </div>
            <div class="col-md-6">
                <label>Email:</label>
                <input type="email" name="Email" class="form-control" value="<?php echo $sp['Email']; ?>">
            </div>
        </div>
        <button type="submit" name="submit" class="btn btn-primary" onclick="return confirm('Bạn có chắc muốn cập nhật khách hàng này?')">Cập nhật khách hàng</button>
        <a href="index_admin.php?page=list_user_customer" class="btn btn-secondary">Về trang khách hàng</a>
    <?php } ?>
</form>