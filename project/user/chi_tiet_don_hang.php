<?php
$page_title = 'Chi tiết đơn hàng';
session_start();
include('includes/ket_noi.php');
include('includes/header.php');

// Kiểm tra đăng nhập
if (!isset($_SESSION['ma_khach_hang'])) {
    header("Location: login.php");
    exit;
}

// Kiểm tra mã đơn hàng
if (!isset($_GET['ma'])) {
    header("Location: don_hang_cua_toi.php");
    exit;
}

$ma_khach_hang = $_SESSION['ma_khach_hang'];
$ma_hoa_don = $_GET['ma'];

// Lấy thông tin đơn hàng
$sql = "SELECT Ma_hoa_don, Ngay_tao, Tong_tien, Trang_thai
        FROM hoa_don
        WHERE Ma_hoa_don = '$ma_hoa_don' AND Ma_khach_hang = '$ma_khach_hang'";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    echo "<div class='container mt-4'><div class='alert alert-danger'>Không tìm thấy đơn hàng!</div></div>";
    include('includes/footer.html');
    exit;
}

$don_hang = mysqli_fetch_array($result);

// Lấy chi tiết sản phẩm trong đơn hàng
$sql_ct = "SELECT ct.Ma_san_pham, ct.So_luong, ct.Don_gia, s.Ten_san_pham, s.Hinh_anh
           FROM chi_tiet_hoa_don ct
           JOIN san_pham s ON ct.Ma_san_pham = s.Ma_san_pham
           WHERE ct.Ma_hoa_don = '$ma_hoa_don'";

$chi_tiet = mysqli_query($conn, $sql_ct);

// Chuyển đổi trạng thái
if ($don_hang['Trang_thai'] == 0) {
    $trang_thai_text = 'Chờ Xác Nhận';
    $trang_thai_class = 'badge bg-warning'; 
} 
else if ($don_hang['Trang_thai'] == 1) {
    $trang_thai_text = 'Đã Xác Nhận';
    $trang_thai_class = 'badge bg-primary'; 
} 
else if ($don_hang['Trang_thai'] == 2) {
    $trang_thai_text = 'Đang Giao';
    $trang_thai_class = 'badge bg-info'; 
} 
else if ($don_hang['Trang_thai'] == 3) {
    $trang_thai_text = 'Hoàn Thành';
    $trang_thai_class = 'badge bg-success'; 
} 
else if ($don_hang['Trang_thai'] == 4) {
    $trang_thai_text = 'Đã Hủy';
    $trang_thai_class = 'badge bg-danger'; 
} 
else {
    $trang_thai_text = 'Không xác định';
    $trang_thai_class = 'badge bg-secondary'; 
}

?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Chi tiết đơn hàng</h2>
        <a href="don_hang_cua_toi.php" class="btn btn-secondary">← Quay lại</a>
    </div>

    <!-- Thông tin đơn hàng -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Mã đơn hàng:</strong> <?= $don_hang['Ma_hoa_don'] ?></p>
                    <p><strong>Ngày đặt:</strong> <?= date('d/m/Y H:i', strtotime($don_hang['Ngay_tao'])) ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Trạng thái:</strong> <span
                            class="<?= $trang_thai_class ?>"><?= $trang_thai_text ?></span></p>
                    <p><strong>Tổng tiền:</strong> <span
                            class="text-danger fw-bold"><?= number_format($don_hang['Tong_tien'],0,',','.') ?>
                            VND</span></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Chi tiết sản phẩm -->
    <h4>Sản phẩm trong đơn hàng</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Sản phẩm</th>
                <th>Ảnh</th>
                <th>Đơn giá</th>
                <th>Số lượng</th>
                <th>Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = mysqli_fetch_array($chi_tiet)):
                $subtotal = $row['Don_gia'] * $row['So_luong'];
            ?>
            <tr>
                <td><?= $row['Ten_san_pham'] ?></td>
                <td><img src="../admin/_images/<?= $row['Hinh_anh'] ?>" width="80"></td>
                <td><?= number_format($row['Don_gia'],0,',','.') ?> VND</td>
                <td><?= $row['So_luong'] ?></td>
                <td><?= number_format($subtotal,0,',','.') ?> VND</td>
            </tr>
            <?php endwhile; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="text-end"><strong>Tổng cộng:</strong></td>
                <td><strong><?= number_format($don_hang['Tong_tien'],0,',','.') ?> VND</strong></td>
            </tr>
        </tfoot>

    </table>
</div>

<?php include('includes/footer.html'); ?>