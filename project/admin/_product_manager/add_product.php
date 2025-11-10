<?php
// --- Tạo mã SP tự động ---
$query_last_sp = "SELECT Ma_san_pham FROM san_pham ORDER BY Ma_san_pham DESC LIMIT 1";
$result_last_sp = mysqli_query($conn, $query_last_sp);
$row_last = mysqli_fetch_assoc($result_last_sp);

if ($row_last) {
    // Lấy số từ mã SP, ví dụ SP5 -> 5
    $last_num = (int) str_replace('SP', '', $row_last['Ma_san_pham']);
    $new_sp = 'SP' . ($last_num + 1);
} else {
    // Nếu chưa có SP nào
    $new_sp = 'SP1';
}

// Lấy danh sách loại sản phẩm
$query_loai = "SELECT Ma_loai, Ten_loai FROM loai_san_pham";
$result_loai = mysqli_query($conn, $query_loai);

// Lấy danh sách nhà cung cấp
$query_ncc = "SELECT Ma_nha_cung_cap, Ten_nha_cung_cap FROM nha_cung_cap";
$result_ncc = mysqli_query($conn, $query_ncc);

// Xử lý submit form
if (isset($_POST['submit'])) {
    $ten_sp = $_POST['Ten_san_pham'];
    $ma_loai = $_POST['Ma_loai'];
    $so_luong = $_POST['So_luong'];
    $don_gia = $_POST['Don_gia'];
    $ma_ncc = $_POST['Ma_nha_cung_cap'];
    $mo_ta = $_POST['Mo_ta'];

    // Xử lý upload hình ảnh
    $hinh_anh = '';
    if (isset($_FILES['Hinh_anh']) && $_FILES['Hinh_anh']['error'] == 0) {
        $hinh_anh = time() . '_' . $_FILES['Hinh_anh']['name'];
        move_uploaded_file($_FILES['Hinh_anh']['tmp_name'], __DIR__ . '/../_images/' . $hinh_anh);
    }



    $insert = "INSERT INTO san_pham (Ma_san_pham, Ten_san_pham, Ma_loai, So_luong, Don_gia, Ma_nha_cung_cap, Mo_ta, Hinh_anh)
               VALUES ('$new_sp', '$ten_sp', '$ma_loai', $so_luong, $don_gia, '$ma_ncc', '$mo_ta', '$hinh_anh')";

    if (mysqli_query($conn, $insert)) {
        echo '<div class="alert alert-success">Thêm sản phẩm thành công! Mã SP: ' . $new_sp . '</div>';
        // tạo lại mã mới cho sản phẩm tiếp theo
        $new_sp = 'SP' . ($last_num + 2);
    } else {
        echo '<div class="alert alert-danger">Lỗi: ' . mysqli_error($conn) . '</div>';
    }
}
?>

<h3>Thêm sản phẩm mới</h3>
<form action="" method="post" enctype="multipart/form-data" class="mt-3">
    <div class="row mb-3">
        <div class="col-md-6">
            <label>Mã sản phẩm:</label>
            <input type="text" name="Ma_san_pham" class="form-control" value="<?php echo $new_sp; ?>" readonly>
        </div>
        <div class="col-md-6">
            <label>Tên sản phẩm:</label>
            <input type="text" name="Ten_san_pham" class="form-control" required>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <label>Loại sản phẩm:</label>
            <select name="Ma_loai" class="form-control" required>
                <option value="">-- Chọn loại --</option>
                <?php while ($row = mysqli_fetch_assoc($result_loai)): ?>
                    <option value="<?php echo $row['Ma_loai']; ?>"><?php echo $row['Ten_loai']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="col-md-6">
            <label>Nhà cung cấp:</label>
            <select name="Ma_nha_cung_cap" class="form-control" required>
                <option value="">-- Chọn nhà cung cấp --</option>
                <?php while ($row = mysqli_fetch_assoc($result_ncc)): ?>
                    <option value="<?php echo $row['Ma_nha_cung_cap']; ?>"><?php echo $row['Ten_nha_cung_cap']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-4">
            <label>Số lượng:</label>
            <input type="number" name="So_luong" class="form-control" value="0">
        </div>
        <div class="col-md-4">
            <label>Đơn giá:</label>
            <input type="number" step="0.01" name="Don_gia" class="form-control" required>
        </div>
        <div class="col-md-4">
            <label>Hình ảnh:</label>
            <input type="file" name="Hinh_anh" class="form-control" accept="image/*">
        </div>
    </div>

    <div class="mb-3">
        <label>Mô tả:</label>
        <textarea name="Mo_ta" class="form-control" rows="3"></textarea>
    </div>

    <button type="submit" name="submit" class="btn btn-primary">Thêm sản phẩm</button>
    <a href="index_admin.php?page=list_product">Về trang sản phẩm</a>
</form>