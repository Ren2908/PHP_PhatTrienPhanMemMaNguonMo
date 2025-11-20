<?php
session_start();
include_once('includes/ket_noi.php');
$page_title = 'Register';
include ('includes/header.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Lấy dữ liệu từ form
    $username = $_POST["username"];
    $password = $_POST["password"];

    // 2. SQL lấy thông tin tài khoản
    $sql = "SELECT * FROM tai_khoan WHERE Ten_dang_nhap = '$username'";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);

    // 3. Kiểm tra mật khẩu
    if ($user && ($password === $user["Mat_khau"]) && ($user["Ma_khach_hang"] !== NULL)) {
        // Lưu thông tin vào session
        $_SESSION["username"] = $user["Ten_dang_nhap"];
        $_SESSION["ma_quyen"] = $user["Ma_quyen"];
        $_SESSION["ma_khach_hang"] = $user["Ma_khach_hang"];
        $_SESSION["Ma_nhan_vien"] = $user["Ma_nhan_vien"];

        // 4. Chuyển hướng theo quyền
        header("Location: index.php");
        exit();
    } else {
        echo "<div class='alert alert-danger text-center'>Sai username hoặc password!</div>";
    }
}
?>

<div class="container d-flex justify-content-center align-items-center" style="height: 90vh;">
    <div class="card shadow-lg" style="width: 420px;">
        <div class="card-header bg-primary text-white text-center">
            <h3 class="mb-0">Đăng nhập</h3>
        </div>

        <div class="card-body">
            <form method="POST">

                <div class="mb-3">
                    <label class="form-label">Tên đăng nhập</label>
                    <input type="text" name="username" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Mật khẩu</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-success px-4">Đăng nhập</button>
                </div>

            </form>
        </div>

        <div class="card-footer text-center">
            Chưa có tài khoản đi tới trang đăng ký ?
            <a href='register.php'>Đăng Ký</a>
        </div>
    </div>
</div>
</div>