<?php
$page_title = 'Đơn hàng của tôi';
session_start();
include('includes/ket_noi.php');
include('includes/header.php');

// Kiểm tra đăng nhập
if (!isset($_SESSION['ma_khach_hang'])) {
    header("Location: login.php");
    exit;
}

$ma_khach_hang = $_SESSION['ma_khach_hang'];

// Lấy đơn hàng theo mã khách hàng
$sql = "SELECT Ma_hoa_don, Ngay_tao, Tong_tien, Trang_thai
        FROM hoa_don
        WHERE Ma_khach_hang = '$ma_khach_hang'
        ORDER BY Ngay_tao DESC";

$result = mysqli_query($conn, $sql);
?>

<div class="container mt-4">
    <h2>Đơn hàng của tôi</h2>

    <?php if ($result && mysqli_num_rows($result) > 0): ?>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Mã đơn hàng</th>
                <th>Ngày đặt</th>
                <th>Tổng tiền</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>

            <?php 
       while ($row = mysqli_fetch_array($result)):

                // Chuyển trạng thái
                if ($row['Trang_thai'] == 0) {
                    $trang_thai_text = "Chờ Xác Nhận";
                    $trang_thai_class = "badge bg-warning"; 
                } 
                else if ($row['Trang_thai'] == 1) {
                    $trang_thai_text = "Đã Xác Nhận";
                    $trang_thai_class = "badge bg-primary"; 
                } 
                else if ($row['Trang_thai'] == 2) {
                    $trang_thai_text = "Đang Giao Cho Vận Chuyển";
                    $trang_thai_class = "badge bg-info"; 
                }  
                else if ($row['Trang_thai'] == 3) {
                    $trang_thai_text = "Đã Hoàn Thành";
                    $trang_thai_class = "badge bg-success"; 
                }  
                else if ($row['Trang_thai'] == 4) {
                    $trang_thai_text = "Đã Hủy";
                    $trang_thai_class = "badge bg-danger"; 
                } 
                else {
                    $trang_thai_text = "Không xác định";
                    $trang_thai_class = "badge bg-secondary"; 
                }
            ?>


            <tr>
                <td><?= $row['Ma_hoa_don'] ?></td>
                <td><?= date('d/m/Y H:i', strtotime($row['Ngay_tao'])) ?></td>
                <td><?= number_format($row['Tong_tien'], 0, ',', '.') ?> VND</td>
                <td><span class="<?= $trang_thai_class ?>"><?= $trang_thai_text ?></span></td>
                <td>
                    <a href="chi_tiet_don_hang.php?ma=<?= $row['Ma_hoa_don'] ?>" class="btn btn-sm btn-primary">
                        Xem chi tiết
                    </a>
                    <?php if ($row['Trang_thai'] == 1 || $row['Trang_thai'] == 0): ?>
                    <a href="huy_don.php?ma=<?= $row['Ma_hoa_don'] ?>" class="btn btn-sm btn-danger"
                        onclick="return confirm('Bạn có chắc muốn hủy đơn hàng này?');">
                        Hủy đơn
                    </a>
                    <?php endif; ?>
                </td>
            </tr>

            <?php endwhile; ?>

        </tbody>
    </table>

    <?php else: ?>
    <div class="alert alert-info">
        <p>Bạn chưa có đơn hàng nào.</p>
        <a href="index.php" class="btn btn-primary">Mua sắm ngay</a>
    </div>
    <?php endif; ?>
</div>

<?php include('includes/footer.html'); ?>