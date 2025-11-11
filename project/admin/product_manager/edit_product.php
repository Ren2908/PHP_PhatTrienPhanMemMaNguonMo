<?php
$id_detail = $_GET['id'];
$query_detail = "SELECT * FROM san_pham WHERE Ma_san_pham = '$id_detail'";
$query_detail_result = mysqli_query($conn, $query_detail);

$query_NCC = "SELECT * FROM nha_cung_cap";
$query_NCC_result = mysqli_query($conn, $query_NCC);

$query_LSP = "SELECT * FROM loai_san_pham";
$query_LSP_result = mysqli_query($conn, $query_LSP);


// Xử lý submit form
if (isset($_POST['submit'])) {
    $ten = trim($_POST['Ten_san_pham']);
    $loai = $_POST['Ma_loai'];
    $sl = $_POST['So_luong'];
    $gia = $_POST['Don_gia'];
    $ncc = $_POST['Ma_nha_cung_cap'];
    $mo_ta = $_POST['Mo_ta'];
    // Xử lý ảnh (nếu có upload mới)
    $hinh_anh = '';
    if (isset($_FILES['Hinh_anh']) && $_FILES['Hinh_anh']['error'] == 0) {
        //Xoá ảnh cũ
        $query_image = "SELECT Hinh_anh FROM san_pham WHERE Ma_san_pham = '$id_detail'";
        $result_query_image = mysqli_query($conn, $query_image);
        if ($row_image = mysqli_fetch_assoc($result_query_image)) {
            $file_name = $row_image['Hinh_anh'];
            $path = __DIR__ . "/../_images/" . $file_name;
            if (file_exists($path)) {
                unlink($path); // xóa file
            }
        }
        $hinh_anh = time() . '_' . $_FILES['Hinh_anh']['name'];
        move_uploaded_file($_FILES['Hinh_anh']['tmp_name'], __DIR__ . '/../_images/' . $hinh_anh);
    } else {
        // Nếu không upload ảnh mới thì giữ ảnh cũ
        $query_image = "SELECT Hinh_anh FROM san_pham WHERE Ma_san_pham = '$id_detail'";
        $result_query_image = mysqli_query($conn, $query_image);
        $row_old = mysqli_fetch_assoc($result_query_image);
        $hinh_anh = $row_old['Hinh_anh'];
    }
    //Kiểm tra trùng
    $query_duplicate = "SELECT Ten_san_pham, Ma_san_pham from san_pham where Ten_san_pham = '$ten' and Ma_san_pham !='$id_detail'";
    $query_duplicate_result = mysqli_query($conn, $query_duplicate);
    $duplicate_result = mysqli_fetch_assoc($query_duplicate_result);

    if (mysqli_num_rows($query_duplicate_result) > 0) {
        echo '<div class="alert alert-danger">Tên sản phẩm đã tồn tại! Mã SP: ' . $duplicate_result['Ma_san_pham'] . '</div>';
    } else {
        $query_update_detail = "
    UPDATE san_pham 
    SET 
        Ten_san_pham = '$ten',
        Ma_loai = '$loai',
        So_luong = '$sl',
        Don_gia = '$gia',
        Ma_nha_cung_cap = '$ncc',
        Mo_ta = '$mo_ta',
        Hinh_anh = '$hinh_anh'
    WHERE Ma_san_pham = '$id_detail' ";

        $result_update = mysqli_query($conn, $query_update_detail);
        if ($result_update) {
            echo '
          <script>
              setTimeout(function() {
                  window.location.href = "index_admin.php?page=edit_product&id=' . $id_detail . '";
              },);
          </script>';
        } else {
            echo "Lỗi cập nhật sản phẩm: " . mysqli_error($conn);
        }
    }
}
?>

<form method="post" action="" enctype="multipart/form-data">
    <h3>Cập nhât sản phẩm</h3>
    <?php while ($sp = mysqli_fetch_assoc($query_detail_result)) { ?>
        <div class="row mb-3">
            <div class="col-md-6">
                <label>Mã sản phẩm:</label>
                <input type="text" name="Ma_san_pham" class="form-control" value="<?php echo $sp['Ma_san_pham']; ?>" readonly>
            </div>
            <div class="col-md-6">
                <label>Tên sản phẩm:</label>
                <input type="text" name="Ten_san_pham" class="form-control" required value="<?php echo $sp['Ten_san_pham'] ?>">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label>Loại sản phẩm:</label>
                <select name="Ma_loai" class="form-control" required>
                    <?php
                    // mysqli_data_seek($query_LSP_result, 0);
                    while ($lsp = mysqli_fetch_assoc($query_LSP_result)):
                        $selected = ($lsp['Ma_loai'] == $sp['Ma_loai']) ? 'selected' : '';
                    ?>
                        <option value="<?php echo $lsp['Ma_loai']; ?>" <?php echo $selected; ?>>
                            <?php echo $lsp['Ten_loai']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-6">
                <label>Nhà cung cấp:</label>
                <select name="Ma_nha_cung_cap" class="form-control" required>
                    <?php
                    // mysqli_data_seek($query_NCC_result, 0);
                    while ($ncc = mysqli_fetch_assoc($query_NCC_result)):
                        $selected = ($ncc['Ma_nha_cung_cap'] == $sp['Ma_nha_cung_cap']) ? 'selected' : '';
                    ?>
                        <option value="<?php echo $ncc['Ma_nha_cung_cap']; ?>" <?php echo $selected; ?>>
                            <?php echo $ncc['Ten_nha_cung_cap']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>

            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label>Số lượng:</label>
                <input type="number" name="So_luong" class="form-control" value="<?php echo $sp['So_luong'] ?>" required>
            </div>
            <div class="col-md-4">
                <label>Đơn giá:</label>
                <input type="number" name="Don_gia" class="form-control" value="<?php echo $sp['Don_gia'] ?>" step="any" required>
            </div>
            <div class="col-md-4">
                <label>Hình ảnh:</label>
                <div class="row">
                    <div class="col"><input type="file" name="Hinh_anh" class="form-control" accept="image/*"></div>
                    <div class="col">
                        <?php
                        $imagePath = "_images/" . $sp['Hinh_anh'];
                        $fullPath = __DIR__ . "/../_images/" . $sp['Hinh_anh'];
                        if (!empty($sp['Hinh_anh']) && file_exists($fullPath)) {
                            echo '<img src="' . $imagePath . '" alt="Ảnh sản phẩm" style="width:200px;height:200px;object-fit:cover;border-radius:8px;">';
                        } else {
                            echo '<span style="color:gray;">Không có ảnh</span>';
                        }
                        ?>
                    </div>
                </div>

            </div>
        </div>
        <div class="mb-3">
            <label>Mô tả:</label>
            <textarea name="Mo_ta" class="form-control" rows="3"><?php echo $sp['Mo_ta']; ?></textarea>
        </div>
        <button type="submit" name="submit" class="btn btn-primary" onclick="return confirm('Bạn có chắc muốn cập nhật sản phẩm này?')">Cập nhật sản phẩm</button>
        <a href="index_admin.php?page=list_product" class="btn btn-secondary">Về trang sản phẩm</a>
    <?php } ?>
</form>