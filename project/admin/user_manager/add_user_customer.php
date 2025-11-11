<?php
// --- Tạo mã SP tự động ---
$query_max = "SELECT MAX(CAST(SUBSTRING(Ma_khach_hang, 3) AS UNSIGNED)) AS max_id FROM khach_hang";
$result_max = mysqli_query($conn, $query_max);
$row_max = mysqli_fetch_assoc($result_max);

$new_id = 'KH' . ($row_max['max_id'] + 1);

// Xử lý submit form
if (isset($_POST['submit'])) {
    $ten = trim($_POST['Ten_khach_hang']);
    $dia_chi = trim($_POST['Dia_chi']);
    $gioi_tinh = $_POST['Gioi_tinh'];
    $dien_thoai = trim($_POST['Dien_thoai']);
    $email = trim($_POST['Email']);

    $insert = "INSERT INTO khach_hang 
               VALUES ('$new_id', '$ten', '$gioi_tinh', '$dia_chi', '$dien_thoai','$email')";
    if (mysqli_query($conn, $insert)) {
        echo '<div class="alert alert-success">Thêm Khách hàng thành công! Mã KH: ' . $new_id . '</div>';
    } else {
        echo '<div class="alert alert-danger">Lỗi: ' . mysqli_error($conn) . '</div>';
    }
}
?>

<h3>Thêm Khách hàng mới</h3>
<form action="" method="post" enctype="multipart/form-data" class="mt-3">
    <div class="row mb-3">
        <div class="col-md-6">
            <label>Mã Khách hàng:</label>
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
            <input type="number" name="Dien_thoai" class="form-control" required value="<?php echo isset($_POST['Dien_thoai']) ? htmlspecialchars($_POST['Dien_thoai']) : ''; ?>">
        </div>
        <div class="col-md-6">
            <label>Email:</label>
            <input type="text" name="Email" class="form-control" required value="<?php echo isset($_POST['Email']) ? htmlspecialchars($_POST['Email']) : ''; ?>">
        </div>
    </div>
    <button type="submit" name="submit" class="btn btn-primary">Thêm Khách hàng</button>
    <a href="index_admin.php?page=list_user_customer" class="btn btn-secondary">Về trang Khách hàng</a>
</form>