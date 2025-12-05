<?php

$new_id = uniqid('KH');

// Xử lý submit form
if (isset($_POST['submit'])) {
    $ten = trim($_POST['Ten_khach_hang']);
    $dia_chi = trim($_POST['Dia_chi']);
    $gioi_tinh = $_POST['Gioi_tinh'];
    $dien_thoai = trim($_POST['Dien_thoai']);
    $email = trim($_POST['Email']);
    $tk = trim($_POST['Ten_dang_nhap']);
    $mk = trim($_POST['Mat_khau']);
    $mk_hash = password_hash($mk, PASSWORD_DEFAULT);

    // --- KIỂM TRA TRÙNG SỐ ĐIỆN THOẠI TRONG KHÁCH HÀNG ---
    $query_duplicate_KH = "
        SELECT Ma_khach_hang 
        FROM khach_hang 
        WHERE Dien_thoai = '$dien_thoai'
    ";
    $res_KH = mysqli_query($conn, $query_duplicate_KH);
    $duplicate_KH = mysqli_fetch_assoc($res_KH);

    // --- KIỂM TRA TRÙNG SỐ ĐIỆN THOẠI TRONG NHÂN VIÊN ---
    $query_duplicate_NV = "
        SELECT Ma_nhan_vien 
        FROM nhan_vien 
        WHERE Dien_thoai = '$dien_thoai'
    ";
    $res_NV = mysqli_query($conn, $query_duplicate_NV);
    $duplicate_NV = mysqli_fetch_assoc($res_NV);

    // --- KIỂM TRA TRÙNG TÀI KHOẢN ---
    $query_duplicate_TK = "
        SELECT Ten_dang_nhap 
        FROM tai_khoan 
        WHERE Ten_dang_nhap = '$tk'
    ";
    $res_TK = mysqli_query($conn, $query_duplicate_TK);

    // --- XỬ LÝ ---
    if (mysqli_num_rows($res_KH) > 0) {
        echo '<div class="alert alert-danger">
                Số điện thoại đã tồn tại trong bảng KHÁCH HÀNG! Mã KH: ' . $duplicate_KH['Ma_khach_hang'] . '
              </div>';
    } elseif (mysqli_num_rows($res_NV) > 0) {
        echo '<div class="alert alert-danger">
                Số điện thoại đã tồn tại trong bảng NHÂN VIÊN! Mã NV: ' . $duplicate_NV['Ma_nhan_vien'] . '
              </div>';
    } elseif (mysqli_num_rows($res_TK) > 0) {
        echo '<div class="alert alert-danger">Tài khoản này đã tồn tại!</div>';
    } else {
        // INSERT KHÁCH HÀNG
        $insert_infor = "
            INSERT INTO khach_hang 
            (Ma_khach_hang, Ten_khach_hang, Phai, Dia_chi, Dien_thoai, Email)
            VALUES 
            ('$new_id', '$ten', '$gioi_tinh', '$dia_chi', '$dien_thoai', '$email')
        ";

        if (mysqli_query($conn, $insert_infor)) {
            echo '<div class="alert alert-success">Thêm khách hàng thành công! Mã: ' . $new_id . '</div>';
        } else {
            echo '<div class="alert alert-danger">Lỗi KH: ' . mysqli_error($conn) . '</div>';
        }

        // INSERT TÀI KHOẢN
        $insert_account = "
            INSERT INTO tai_khoan 
            (Ten_dang_nhap, Mat_khau, Ma_quyen, Ma_nhan_vien, Ma_khach_hang, Trang_thai)
            VALUES 
            ('$tk', '$mk_hash', 'Q3', NULL, '$new_id', 1)
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
    <h3>Thêm thông tin khách hàng</h3>
    <div class="row mb-3">
        <div class="col-md-6">
            <label>Mã khách hàng:</label>
            <input type="text" name="Ma_khach_hang" class="form-control" value="<?php echo $new_id; ?>" readonly>
        </div>
        <div class="col-md-6">
            <label>Tên Khách hàng:</label>
            <input type="text" name="Ten_khach_hang" class="form-control" required value="<?php echo isset($_POST['Ten_khach_hang']) ? htmlspecialchars($_POST['Ten_khach_hang']) : ''; ?>">
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
            <input type="number" min="0" onkeypress="if (event.key === '-' || event.key === '+') event.preventDefault();" name="Dien_thoai" class="form-control" required value="<?php echo isset($_POST['Dien_thoai']) ? htmlspecialchars($_POST['Dien_thoai']) : ''; ?>">
        </div>
        <div class="col-md-6">
            <label>Email:</label>
            <input type="text" name="Email" class="form-control" required value="<?php echo isset($_POST['Email']) ? htmlspecialchars($_POST['Email']) : ''; ?>">
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
    <button type="submit" name="submit" class="btn btn-primary">Thêm tài khoản Khách hàng</button>
    <a href="index_admin.php?page=list_account" class="btn btn-secondary">Về trang tài khoản</a>
</form>