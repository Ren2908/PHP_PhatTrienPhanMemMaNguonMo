<?php
$id_detail = $_GET['id'];

// Lấy chi tiết tài khoản
$query_detail = "SELECT * FROM tai_khoan WHERE Ten_dang_nhap = '$id_detail'";
$query_detail_result = mysqli_query($conn, $query_detail);

// Lấy danh sách quyền
$query_quyen = "SELECT * FROM quyen";
$query_quyen_result = mysqli_query($conn, $query_quyen);

// Xử lý submit form
if (isset($_POST['submit'])) {
    $ten_dang_nhap = trim($_POST['Ten_dang_nhap']);
    $mat_khau = trim($_POST['Mat_khau']);
    $ma_quyen = $_POST['Ma_quyen'];

    // Mã hoá mật khẩu trước khi cập nhật
    $mat_khau_hash = password_hash($mat_khau, PASSWORD_DEFAULT);

    // Cập nhật tài khoản
    $query_update_detail = "
        UPDATE tai_khoan 
        SET 
            Ten_dang_nhap = '$ten_dang_nhap',
            Mat_khau = '$mat_khau_hash',
            Ma_quyen = '$ma_quyen'
        WHERE Ten_dang_nhap = '$id_detail'
    ";

    $result_update = mysqli_query($conn, $query_update_detail);
    if ($result_update) {
        echo '<div class="alert alert-success">Cập nhật thành công!</div>';
        echo '<script>
            setTimeout(function() {
                window.location.href = "index_admin.php?page=list_account";
            }, 1000);
        </script>';
    } else {
        echo "Lỗi cập nhật tài khoản: " . mysqli_error($conn);
    }
}
?>

<form method="post" action="">
    <h3>Cập nhật tài khoản</h3>
    <?php if ($sp = mysqli_fetch_assoc($query_detail_result)) { ?>
        <div class="row mb-3">
            <div class="col-md-12">
                <label>Tên đăng nhập:</label>
                <input type="text" name="Ten_dang_nhap" class="form-control" required value="<?php echo $sp['Ten_dang_nhap'] ?>">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label>Mật khẩu mới:</label>
                <input type="text" name="Mat_khau" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label>Quyền:</label>
                <select name="Ma_quyen" class="form-control" required>
                    <?php
                    mysqli_data_seek($query_quyen_result, 0); // reset result pointer
                    while ($ncc = mysqli_fetch_assoc($query_quyen_result)):
                        $selected = ($ncc['Ma_quyen'] == $sp['Ma_quyen']) ? 'selected' : '';
                    ?>
                        <option value="<?php echo $ncc['Ma_quyen']; ?>" <?php echo $selected; ?>>
                            <?php echo $ncc['Ten_quyen']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
        </div>
        <button type="submit" name="submit" class="btn btn-primary" onclick="return confirm('Bạn có chắc muốn cập nhật tài khoản này?')">Cập nhật</button>
        <a href="index_admin.php?page=list_account" class="btn btn-secondary">Quay lại danh sách tài khoản</a>
    <?php } ?>
</form>