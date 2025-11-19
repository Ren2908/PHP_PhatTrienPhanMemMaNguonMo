<?php
$page_title = 'Đổi mật khẩu';
session_start();
include('includes/ket_noi.php');
include('includes/header.php');

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username']; 
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mat_khau_cu = $_POST['mat_khau_cu'];
    $mat_khau_moi = $_POST['mat_khau_moi'];
    $xac_nhan_mk = $_POST['xac_nhan_mk'];

    $sql = "SELECT Mat_khau FROM tai_khoan WHERE Ten_dang_nhap='$username'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        if (password_verify($mat_khau_cu, $row['Mat_khau'])) {
            if ($mat_khau_moi === $xac_nhan_mk) {
                $mat_khau_moi_hash = password_hash($mat_khau_moi, PASSWORD_DEFAULT);
                $sql_update = "UPDATE tai_khoan SET Mat_khau='$mat_khau_moi_hash' WHERE Ten_dang_nhap='$username'";
                
                if (mysqli_query($conn, $sql_update)) {
                    $message = "<div class='alert alert-success'>Đổi mật khẩu thành công!</div>";
                } else {
                    $message = "<div class='alert alert-danger'>Lỗi: " . mysqli_error($conn) . "</div>";
                }
            } else {
                $message = "<div class='alert alert-warning'>Mật khẩu mới và xác nhận không trùng khớp.</div>";
            }
        } else {
            $message = "<div class='alert alert-danger'>Mật khẩu cũ không đúng.</div>";
        }
    }
}
?>

<div class="container mt-4">
    <h2>Đổi mật khẩu</h2>
    <?php echo $message; ?>
    <form method="POST" action="">
        <div class="mb-3">
            <label>Mật khẩu cũ</label>
            <input type="password" name="mat_khau_cu" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Mật khẩu mới</label>
            <input type="password" name="mat_khau_moi" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Xác nhận mật khẩu mới</label>
            <input type="password" name="xac_nhan_mk" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Đổi mật khẩu</button>
        <a href="thong_tin_khach_hang.php" class="btn btn-secondary">Hủy</a>
    </form>
</div>

<?php
include('includes/footer.html');
?>