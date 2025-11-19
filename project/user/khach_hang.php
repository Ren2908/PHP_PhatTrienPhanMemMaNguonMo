<?php
$page_title = 'Thông tin khách hàng';
session_start();
include('includes/ket_noi.php');
include('includes/header.php');

// Kiểm tra đăng nhập
if (!isset($_SESSION['ma_khach_hang'])) {
    header("Location: login.php");
    exit;
}

$ma_khach_hang = $_SESSION['ma_khach_hang'];

// Lấy thông tin khách hàng
$sql = "SELECT * FROM khach_hang WHERE Ma_khach_hang = '$ma_khach_hang'";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $khach_hang = mysqli_fetch_assoc($result);
    ?>
<div class="container mt-4">
    <h2>Thông tin khách hàng</h2>
    <table class="table table-bordered mt-3">
        <tr>
            <th>Mã khách hàng</th>
            <td><?php echo $khach_hang['Ma_khach_hang']; ?></td>
        </tr>
        <tr>
            <th>Họ và tên</th>
            <td><?php echo $khach_hang['Ten_khach_hang']; ?></td>
        </tr>
        <tr>
            <th>Giới tính</th>
            <td><?php echo ($khach_hang['Phai'] == 1) ? 'Nam' : 'Nữ'; ?></td>
        </tr>
        <tr>
            <th>Địa chỉ</th>
            <td><?php echo $khach_hang['Dia_chi']; ?></td>
        </tr>
        <tr>
            <th>Số điện thoại</th>
            <td><?php echo $khach_hang['Dien_thoai']; ?></td>
        </tr>
        <tr>
            <th>Email</th>
            <td><?php echo $khach_hang['Email']; ?></td>
        </tr>
    </table>

    <a href="sua_thong_tin_khach_hang.php" class="btn btn-primary">Sửa thông tin</a>
    <a href="doi_mat_khau.php" class="btn btn-primary">Đổi Mật Khẩu</a>

</div>
<?php
} else {
    echo "<p>Không tìm thấy thông tin khách hàng.</p>";
}


?>
<?php
include('includes/footer.html');
?>