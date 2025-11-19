<?php
$page_title = 'Sửa thông tin khách hàng';
session_start();
include('includes/ket_noi.php');
include('includes/header.php');

if (!isset($_SESSION['ma_khach_hang'])) {
    header("Location: login.php");
    exit;
}

$ma_khach_hang = $_SESSION['ma_khach_hang'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ten_khach_hang = $_POST['ten_khach_hang'];
    $phai = $_POST['phai'];
    $dia_chi = $_POST['dia_chi'];
    $dien_thoai = $_POST['dien_thoai'];
    $email = $_POST['email'];

    $sql_update = "
        UPDATE khach_hang
        SET Ten_khach_hang='$ten_khach_hang',
            Phai='$phai',
            Dia_chi='$dia_chi',
            Dien_thoai='$dien_thoai',
            Email='$email'
        WHERE Ma_khach_hang='$ma_khach_hang'
    ";

    if (mysqli_query($conn, $sql_update)) {
        echo "<div class='alert alert-success'>Cập nhật thông tin thành công!</div>";
    } else {
        echo "<div class='alert alert-danger'>Lỗi: " . mysqli_error($conn) . "</div>";
    }
}

$sql = "SELECT * FROM khach_hang WHERE Ma_khach_hang='$ma_khach_hang'";
$result = mysqli_query($conn, $sql);
$khach_hang = mysqli_fetch_assoc($result);
?>

<div class="container mt-4">
    <h2>Sửa thông tin khách hàng</h2>
    <form method="POST" action="">
        <div class="mb-3">
            <label>Họ và tên</label>
            <input type="text" name="ten_khach_hang" class="form-control"
                value="<?php echo $khach_hang['Ten_khach_hang']; ?>" required>
        </div>
        <div class="mb-3">
            <label>Giới tính</label>
            <select name="phai" class="form-control">
                <option value="1" <?php if($khach_hang['Phai']==1) echo "selected"; ?>>Nam</option>
                <option value="0" <?php if($khach_hang['Phai']==0) echo "selected"; ?>>Nữ</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Địa chỉ</label>
            <input type="text" name="dia_chi" class="form-control" value="<?php echo $khach_hang['Dia_chi']; ?>"
                required>
        </div>
        <div class="mb-3">
            <label>Số điện thoại</label>
            <input type="text" name="dien_thoai" class="form-control" value="<?php echo $khach_hang['Dien_thoai']; ?>"
                required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="<?php echo $khach_hang['Email']; ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="khach_hang.php" class="btn btn-secondary">Hủy</a>
    </form>
</div>

<?php
include('includes/footer.html');
?>