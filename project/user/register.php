<?php 
$page_title = 'Register';
include ('includes/header.php');
include('includes/ket_noi.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 1. Lấy dữ liệu từ form
    $ten_khach_hang = $_POST["ten_khach_hang"];
    $phai = $_POST["phai"];
    $dia_chi = $_POST["dia_chi"];
    $dien_thoai = $_POST["dien_thoai"];
    $email = $_POST["email"];
    $username = $_POST["username"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    // 2. Kiểm tra username có tồn tại chưa
    $check_sql = "SELECT * FROM tai_khoan WHERE Ten_dang_nhap = '$username'";
    $result = mysqli_query($conn, $check_sql);

    if (mysqli_num_rows($result) > 0) {
        echo "<div class='alert alert-warning'>Tên đăng nhập đã tồn tại, vui lòng chọn tên khác!</div>";
    } else {
        // 3. Tạo mã khách hàng tự động
        $ma_khach_hang = uniqid('KH');

        // 4. Tạo ngày tạo tài khoản
        $ngay_tao = date("Y-m-d H:i:s");

        // 5. SQL thêm khách hàng
        $sql_kh = "
            INSERT INTO khach_hang (Ma_khach_hang, Ten_khach_hang, Phai, Dia_chi, Dien_thoai, Email)
            VALUES ('$ma_khach_hang', '$ten_khach_hang', '$phai', '$dia_chi', '$dien_thoai', '$email')
        ";

        // 6. SQL thêm tài khoản với quyền Khách hàng Q3
        $ma_quyen = 'Q3';
        $trang_thai = 1;
        $sql_tk = "
            INSERT INTO tai_khoan (Ten_dang_nhap, Mat_khau, Ma_quyen, Ma_khach_hang, Trang_thai, Ngay_tao)
            VALUES ('$username', '$password', '$ma_quyen', '$ma_khach_hang', '$trang_thai', '$ngay_tao')
        ";

        // 7. Thực hiện transaction
        mysqli_begin_transaction($conn);
        try {
            mysqli_query($conn, $sql_kh);
            mysqli_query($conn, $sql_tk);
            mysqli_commit($conn);

            echo "<div class='alert alert-success'>Đăng ký thành công! <a href='login.php'>Đăng nhập</a></div>";
        } catch (Exception $e) {
            mysqli_rollback($conn);
            echo "<div class='alert alert-danger'>Lỗi: " . $e->getMessage() . "</div>";
        }
    }
}
?>


<form method="POST" class="container mt-4" style="max-width: 550px;">
    <div class="card shadow">
        <div class="card-header bg-primary text-white text-center">
            <h3 class="mb-0">Đăng ký tài khoản</h3>
        </div>

        <div class="card-body">

            <div class="mb-3">
                <label class="form-label">Họ và tên</label>
                <input type="text" name="ten_khach_hang" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Giới tính</label>
                <select name="phai" class="form-select">
                    <option value="1">Nam</option>
                    <option value="0">Nữ</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Địa chỉ</label>
                <input type="text" name="dia_chi" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Điện thoại</label>
                <input type="text" name="dien_thoai" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Tên đăng nhập</label>
                <input type="text" name="username" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Mật khẩu</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-success px-4">Đăng ký</button>
            </div>
            <div class="text-center">
                Đã có tài khoản đi tới trang đăng nhập ?
                <a href='login.php'>Đăng nhập</a>
            </div>

        </div>
    </div>
</form>


<?php include ('includes/footer.html'); ?>