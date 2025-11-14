<?php try {
    // 1. Thêm khách hàng vào bảng khach_hang
    $sql_kh = "INSERT INTO khach_hang (Ma_khach_hang, Ten_khach_hang, Phai, Dia_chi, Dien_thoai, Email) VALUES (?, ?, ?, ?, ?, ?)";
    $ma_khach_hang = uniqid('KH'); // Tạo mã khách hàng tự động
    $stmt_kh = $conn->prepare($sql_kh);
    $stmt_kh->bind_param("ssisss", $ma_khach_hang, $ten_khach_hang, $phai, $dia_chi, $dien_thoai, $email);
    $stmt_kh->execute();

    // 2. Thêm tài khoản vào bảng tai_khoan với quyền KhachHang (Q3)
    $ma_quyen = 'Q3'; // Quyền khách hàng
    $trang_thai = 1;
    $ngay_tao = date("Y-m-d H:i:s");

    $sql_tk = "INSERT INTO tai_khoan (Ten_dang_nhap, Mat_khau, Ma_quyen, Ma_khach_hang, Trang_thai, Ngay_tao) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt_tk = $conn->prepare($sql_tk);
    $stmt_tk->bind_param("ssssis", $username, $password, $ma_quyen, $ma_khach_hang, $trang_thai, $ngay_tao);
    $stmt_tk->execute();

    $conn->commit();
    echo "Đăng ký thành công! <a href='login.php'>Đăng nhập</a>";
} catch (Exception $e) {
    $conn->rollback();
    echo "Lỗi: " . $e->getMessage();
}
$ma_khach_hang = uniqid('KH'); // Tạo mã khách hàng tự động  
?>

<?php
session_start();
include('includes/ket_noi.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM tai_khoan WHERE Ten_dang_nhap=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user["Mat_khau"])) {
        $_SESSION["username"] = $user["Ten_dang_nhap"];
        $_SESSION["ma_quyen"] = $user["Ma_quyen"];

        // Chuyển hướng theo quyền
        if ($user["Ma_quyen"] == 'Q1') {
            header("Location: index.php");
        } elseif ($user["Ma_quyen"] == 'Q2') {
            header("Location: index.php");
        } else {
            header("Location: index.php");
        }
        exit();
    } else {
        echo "Sai username hoặc password!";
    }
}
?>




<form method="POST">
    <h2>Đăng nhập</h2>
    Username: <input type="text" name="username" required><br>
    Password: <input type="password" name="password" required><br>
    <button type="submit">Đăng nhập</button>
</form>

<?php
$page_title = 'Register';
include('includes/header.html');
include('includes/ket_noi.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form
    $ten_khach_hang = $_POST["ten_khach_hang"];
    $phai = $_POST["phai"];
    $dia_chi = $_POST["dia_chi"];
    $dien_thoai = $_POST["dien_thoai"];
    $email = $_POST["email"];
    $username = $_POST["username"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    // Bắt đầu transaction
    $conn->begin_transaction();

    try {
        // 1. Thêm khách hàng vào bảng khach_hang
        $sql_kh = "INSERT INTO khach_hang (Ma_khach_hang, Ten_khach_hang, Phai, Dia_chi, Dien_thoai, Email) VALUES (?, ?, ?, ?, ?, ?)";
        $ma_khach_hang = uniqid('KH'); // Tạo mã khách hàng tự động
        $stmt_kh = $conn->prepare($sql_kh);
        $stmt_kh->bind_param("ssisss", $ma_khach_hang, $ten_khach_hang, $phai, $dia_chi, $dien_thoai, $email);
        $stmt_kh->execute();

        // 2. Thêm tài khoản vào bảng tai_khoan với quyền KhachHang (Q3)
        $ma_quyen = 'Q3'; // Quyền khách hàng
        $trang_thai = 1;
        $ngay_tao = date("Y-m-d H:i:s");

        $sql_tk = "INSERT INTO tai_khoan (Ten_dang_nhap, Mat_khau, Ma_quyen, Ma_khach_hang, Trang_thai, Ngay_tao) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt_tk = $conn->prepare($sql_tk);
        $stmt_tk->bind_param("ssssis", $username, $password, $ma_quyen, $ma_khach_hang, $trang_thai, $ngay_tao);
        $stmt_tk->execute();

        $conn->commit();
        echo "Đăng ký thành công! <a href='login.php'>Đăng nhập</a>";
    } catch (Exception $e) {
        $conn->rollback();
        echo "Lỗi: " . $e->getMessage();
    }
}
?>

<form method="POST">
    <h2>Đăng ký</h2>
    Họ và tên: <input type="text" name="ten_khach_hang" required><br>
    Giới tính:
    <select name="phai">
        <option value="1">Nam</option>
        <option value="0">Nữ</option>
    </select><br>
    Địa chỉ: <input type="text" name="dia_chi" required><br>
    Điện thoại: <input type="text" name="dien_thoai" required><br>
    Email: <input type="email" name="email" required><br>
    Username: <input type="text" name="username" required><br>
    Password: <input type="password" name="password" required><br>
    <button type="submit">Đăng ký</button>
</form>

<?php include('includes/footer.html'); ?>