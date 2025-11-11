<?php
// --- Tạo mã SP tự động ---
$query_max = "SELECT MAX(CAST(SUBSTRING(Ma_san_pham, 3) AS UNSIGNED)) AS max_id FROM san_pham";
$result_max = mysqli_query($conn, $query_max);
$row_max = mysqli_fetch_assoc($result_max);

$new_sp = 'SP' . ($row_max['max_id'] + 1);


// Lấy danh sách loại sản phẩm
$query_loai = "SELECT Ma_loai, Ten_loai FROM loai_san_pham";
$result_loai = mysqli_query($conn, $query_loai);

// Lấy danh sách nhà cung cấp
$query_ncc = "SELECT Ma_nha_cung_cap, Ten_nha_cung_cap FROM nha_cung_cap";
$result_ncc = mysqli_query($conn, $query_ncc);

// Xử lý submit form
if (isset($_POST['submit'])) {
    $ten_sp = trim($_POST['Ten_san_pham']);
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

    //Kiểm tra trùng
    $query_duplicate = "SELECT Ten_san_pham, Ma_san_pham from san_pham where Ten_san_pham = '$ten_sp'";
    $query_duplicate_result = mysqli_query($conn, $query_duplicate);
    $duplicate_result = mysqli_fetch_assoc($query_duplicate_result);

    if (mysqli_num_rows($query_duplicate_result) > 0) {
        echo '<div class="alert alert-danger">Sản phẩm đã tồn tại! Mã SP: ' . $duplicate_result['Ma_san_pham'] . '</div>';
    } else {
        $insert = "INSERT INTO san_pham (Ma_san_pham, Ten_san_pham, Ma_loai, So_luong, Don_gia, Ma_nha_cung_cap, Mo_ta, Hinh_anh)
               VALUES ('$new_sp', '$ten_sp', '$ma_loai', $so_luong, $don_gia, '$ma_ncc', '$mo_ta', '$hinh_anh' )";
        if (mysqli_query($conn, $insert)) {
            echo '<div class="alert alert-success">Thêm sản phẩm thành công! Mã SP: ' . $new_sp . '</div>';
        } else {
            echo '<div class="alert alert-danger">Lỗi: ' . mysqli_error($conn) . '</div>';
        }
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
            <input type="text" name="Ten_san_pham" class="form-control" required value="<?php echo isset($_POST['Ten_san_pham']) ? htmlspecialchars($_POST['Ten_san_pham']) : ''; ?>">
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <label>Loại sản phẩm:</label>
            <select name="Ma_loai" class="form-control" required>
                <option value="">-- Chọn loại --</option>
                <?php
                mysqli_data_seek($result_loai, 0);
                while ($row = mysqli_fetch_assoc($result_loai)):
                    $selected = isset($_POST['Ma_loai']) && $_POST['Ma_loai'] == $row['Ma_loai'] ? 'selected' : '';
                ?>
                    <option value="<?php echo $row['Ma_loai']; ?>" <?php echo $selected; ?>><?php echo $row['Ten_loai']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="col-md-6">
            <label>Nhà cung cấp:</label>
            <select name="Ma_nha_cung_cap" class="form-control" required>
                <option value="">-- Chọn nhà cung cấp --</option>
                <?php
                mysqli_data_seek($result_ncc, 0);
                while ($row = mysqli_fetch_assoc($result_ncc)):
                    $selected = isset($_POST['Ma_nha_cung_cap']) && $_POST['Ma_nha_cung_cap'] == $row['Ma_nha_cung_cap'] ? 'selected' : '';
                ?>
                    <option value="<?php echo $row['Ma_nha_cung_cap']; ?>" <?php echo $selected; ?>><?php echo $row['Ten_nha_cung_cap']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-4">
            <label>Số lượng:</label>
            <input type="number" name="So_luong" class="form-control" value="<?php echo isset($_POST['So_luong']) ? htmlspecialchars($_POST['So_luong']) : '0'; ?>">
        </div>
        <div class="col-md-4">
            <label>Đơn giá:</label>
            <input type="number" step="0.01" name="Don_gia" class="form-control" required value="<?php echo isset($_POST['Don_gia']) ? htmlspecialchars($_POST['Don_gia']) : ''; ?>">
        </div>
        <div class="col-md-4">
            <label>Hình ảnh:</label>
            <input type="file" name="Hinh_anh" class="form-control" accept="image/*">
            <?php if (isset($hinh_anh) && $hinh_anh != ''): ?>
                <small class="text-success">File đã tải lên: <?php echo htmlspecialchars($hinh_anh); ?></small>
            <?php endif; ?>
        </div>
    </div>

    <div class="mb-3">
        <label>Mô tả:</label>
        <textarea name="Mo_ta" class="form-control" rows="3"><?php echo isset($_POST['Mo_ta']) ? htmlspecialchars($_POST['Mo_ta']) : ''; ?></textarea>
    </div>

    <button type="submit" name="submit" class="btn btn-primary">Thêm sản phẩm</button>
    <a href="index_admin.php?page=list_product" class="btn btn-secondary">Về trang sản phẩm</a>
</form>