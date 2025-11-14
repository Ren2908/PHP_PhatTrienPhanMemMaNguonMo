<?php

$new_id = uniqid('NV');

if (isset($_POST['submit'])) {
    $ten = trim($_POST['Ten_nhan_vien']);
    $dia_chi = trim($_POST['Dia_chi']);
    $gioi_tinh = $_POST['Gioi_tinh'];
    $dien_thoai = trim($_POST['Dien_thoai']);
    $chuc_vu = 'Nhân viên';

    $tk = trim($_POST['Ten_dang_nhap']);
    $mk = trim($_POST['Mat_khau']);
    $mk_hash = password_hash($mk, PASSWORD_DEFAULT);

    // --- KIỂM TRA TRÙNG SĐT TRONG NHÂN VIÊN ---
    $sql_check_nv = "SELECT Ma_nhan_vien FROM nhan_vien WHERE Dien_thoai = '$dien_thoai'";
    $res_check_nv = mysqli_query($conn, $sql_check_nv);
    $data_nv = mysqli_fetch_assoc($res_check_nv);

    // --- KIỂM TRA TRÙNG SĐT TRONG KHÁCH HÀNG ---
    $sql_check_kh = "SELECT Ma_khach_hang FROM khach_hang WHERE Dien_thoai = '$dien_thoai'";
    $res_check_kh = mysqli_query($conn, $sql_check_kh);
    $data_kh = mysqli_fetch_assoc($res_check_kh);

    // --- KIỂM TRA TRÙNG USERNAME ---
    $sql_check_user = "SELECT Ten_dang_nhap FROM tai_khoan WHERE Ten_dang_nhap = '$tk'";
    $res_check_user = mysqli_query($conn, $sql_check_user);

    // ---- XỬ LÝ THÔNG BÁO LỖI ----
    if (mysqli_num_rows($res_check_nv) > 0) {
        echo '<div class="alert alert-danger">
                Số điện thoại đã tồn tại trong bảng NHÂN VIÊN! Mã NV: ' . $data_nv['Ma_nhan_vien'] . '
              </div>';
    } elseif (mysqli_num_rows($res_check_kh) > 0) {
        echo '<div class="alert alert-danger">
                Số điện thoại đã tồn tại trong bảng KHÁCH HÀNG! Mã KH: ' . $data_kh['Ma_khach_hang'] . '
              </div>';
    } elseif (mysqli_num_rows($res_check_user) > 0) {
        echo '<div class="alert alert-danger">Tài khoản đã tồn tại!</div>';
    } else {

        // --- THÊM NHÂN VIÊN ---
        $insert_nv = "
            INSERT INTO nhan_vien
            (Ma_nhan_vien, Ten_nhan_vien, Phai, Dia_chi, Dien_thoai, Chuc_vu)
            VALUES 
            ('$new_id', '$ten', '$gioi_tinh', '$dia_chi', '$dien_thoai', '$chuc_vu')
        ";

        if (mysqli_query($conn, $insert_nv)) {
            echo '<div class="alert alert-success">Thêm nhân viên thành công! Mã: ' . $new_id . '</div>';
        } else {
            echo '<div class="alert alert-danger">Lỗi NV: ' . mysqli_error($conn) . '</div>';
        }

        // --- THÊM TÀI KHOẢN ---
        $insert_account = "
            INSERT INTO tai_khoan
            (Ten_dang_nhap, Mat_khau, Ma_quyen, Ma_nhan_vien, Ma_khach_hang, Trang_thai)
            VALUES
            ('$tk', ' $mk_hash', 'Q2', '$new_id', NULL, 1)
        ";

        if (mysqli_query($conn, $insert_account)) {
            echo '<div class="alert alert-success">Thêm tài khoản thành công! User: ' . $tk . '</div>';
        } else {
            echo '<div class="alert alert-danger">Lỗi TK: ' . mysqli_error($conn) . '</div>';
        }
    }
}
?>





<form action="" method="post" enctype="multipart/form-data" class="mt-3">
    <h3>Thêm thông tin nhân viên</h3>
    <div class="row mb-3">
        <div class="col-md-12">
            <label>Mã nhân viên:</label>
            <input type="text" name="Ma_nhan_vien" class="form-control" value="<?php echo $new_id; ?>" readonly>
        </div>
        <div class="col-md-6">
            <label>Tên nhân viên:</label>
            <input type="text" name="Ten_nhan_vien" class="form-control" required value="<?php echo isset($_POST['Ten_nhan_vien']) ? htmlspecialchars($_POST['Ten_nhan_vien']) : ''; ?>">
        </div>
        <div class="col-md-6">
            <label>Giới tính:</label>
            <select name="Gioi_tinh" class="form-control" required>
                <option value="">-- Chọn giới tính --</option>
                <option value="1" <?php echo (isset($_POST['Gioi_tinh']) && $_POST['Gioi_tinh'] == '1') ? 'selected' : '' ?>>Nam</option>
                <option value="0" <?php echo (isset($_POST['Gioi_tinh']) && $_POST['Gioi_tinh'] == '0') ? 'selected' : '' ?>>Nữ</option>
            </select>
        </div>
        <div class="col-md-6">
            <label>Địa chỉ:</label>
            <input type="text" name="Dia_chi" class="form-control" required value="<?php echo isset($_POST['Dia_chi']) ? htmlspecialchars($_POST['Dia_chi']) : ''; ?>">
        </div>
        <div class="col-md-6">
            <label>Điện thoại:</label>
            <input type="number" name="Dien_thoai" class="form-control" required value="<?php echo isset($_POST['Dien_thoai']) ? htmlspecialchars($_POST['Dien_thoai']) : ''; ?>">
        </div>

    </div>
    <h3>Thêm tài khoản</h3>
    <div class="row mb-3">
        <div class="col-md-6">
            <label>Tài khoản:</label>
            <input type="text" name="Ten_dang_nhap" class="form-control" required value="<?php echo isset($_POST['Ten_dang_nhap']) ? htmlspecialchars($_POST['Ten_dang_nhap']) : ''; ?>">
        </div>
        <div class="col-md-6">
            <label>Mật khẩu:</label>
            <input type="text" name="Mat_khau" class="form-control" required value="<?php echo isset($_POST['Mat_khau']) ? htmlspecialchars($_POST['Mat_khau']) : ''; ?>">
        </div>
    </div>
    <button type="submit" name="submit" class="btn btn-primary">Thêm tài khoản nhân viên</button>
    <a href="index_admin.php?page=list_account" class="btn btn-secondary">Về trang tài khoản</a>
</form>