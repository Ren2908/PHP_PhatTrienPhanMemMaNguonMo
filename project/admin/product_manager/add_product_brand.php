<?php
// --- Tạo mã SP tự động ---
$query_max = "SELECT MAX(CAST(SUBSTRING(Ma_nha_cung_cap, 4) AS UNSIGNED)) AS max_id FROM nha_cung_cap";
$result_max = mysqli_query($conn, $query_max);
$row_max = mysqli_fetch_assoc($result_max);

$new_ncc = 'NCC' . ($row_max['max_id'] + 1);

// Xử lý submit form
if (isset($_POST['submit'])) {
    $ten_ncc = trim($_POST['Ten_nha_cung_cap']);
    $dia_chi = trim($_POST['Dia_chi']);
    $dien_thoai = trim($_POST['Dien_thoai']);
    $email = trim($_POST['Email']);

    //Kiểm tra trùng
    $query_duplicate = "SELECT Ten_nha_cung_cap, Ma_nha_cung_cap from nha_cung_cap where Ten_nha_cung_cap = '$ten_ncc'";
    $query_duplicate_result = mysqli_query($conn, $query_duplicate);
    $duplicate_result = mysqli_fetch_assoc($query_duplicate_result);

    if (mysqli_num_rows($query_duplicate_result) > 0) {
        echo '<div class="alert alert-danger">nhà cung cấp đã tồn tại! Mã NCC: ' . $duplicate_result['Ma_nha_cung_cap'] . '</div>';
    } else {
        $insert = "INSERT INTO nha_cung_cap 
               VALUES ('$new_ncc', '$ten_ncc', '$dia_chi', '$dien_thoai','$email')";
        if (mysqli_query($conn, $insert)) {
            echo '<div class="alert alert-success">Thêm nhà cung cấp thành công! Mã NCC: ' . $new_ncc . '</div>';
        } else {
            echo '<div class="alert alert-danger">Lỗi: ' . mysqli_error($conn) . '</div>';
        }
    }
}
?>

<h3>Thêm nhà cung cấp mới</h3>
<form action="" method="post" enctype="multipart/form-data" class="mt-3">
    <div class="row mb-3">
        <div class="col-md-12">
            <label>Mã nhà cung cấp:</label>
            <input type="text" name="Ma_nha_cung_cap" class="form-control" value="<?php echo $new_ncc; ?>" readonly>
        </div>
        <div class="col-md-6">
            <label>Tên nhà cung cấp:</label>
            <input type="text" name="Ten_nha_cung_cap" class="form-control" required value="<?php echo isset($_POST['Ten_nha_cung_cap']) ? htmlspecialchars($_POST['Ten_nha_cung_cap']) : ''; ?>">
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
    <button type="submit" name="submit" class="btn btn-primary">Thêm nhà cung cấp</button>
    <a href="index_admin.php?page=list_product_brand" class="btn btn-secondary">Về trang nhà cung cấp</a>
</form>