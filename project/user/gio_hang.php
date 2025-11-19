<?php
$page_title = 'Giỏ Hàng';
session_start();
include('includes/ket_noi.php');
include('includes/header.php');

// 1. Kiểm tra đăng nhập
if (!isset($_SESSION['ma_khach_hang'])) {
    header("Location: login.php");
    exit;
}

$ma_khach_hang = $_SESSION['ma_khach_hang'];

// 2. Lấy danh sách giỏ hàng của khách
$query_GioHang = "
    SELECT g.ma_san_pham, g.so_luong, s.Ten_san_pham, s.Don_gia, s.Hinh_anh
    FROM gio_hang g
    JOIN san_pham s ON g.ma_san_pham = s.Ma_san_pham
    WHERE g.ma_khach_hang = '$ma_khach_hang'
";

$result = mysqli_query($conn, $query_GioHang);
?>

<div class="container mt-4">
    <h2>Giỏ hàng của bạn</h2>

    <?php if ($result && mysqli_num_rows($result) > 0): ?>

    <form method="POST" id="formThanhToan">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Chọn</th>
                    <th>Sản phẩm</th>
                    <th>Ảnh</th>
                    <th>Đơn giá</th>
                    <th>Số lượng</th>
                    <th>Thành tiền</th>
                    <th>Hành động</th>
                </tr>
            </thead>

            <tbody>
                <?php
                $tongTien = 0;
                while ($row = mysqli_fetch_assoc($result)):
                    $thanhTien = $row['Don_gia'] * $row['so_luong'];
                    $tongTien += $thanhTien;
                ?>

                <tr>
                    <td>
                        <input type="checkbox" name="chon_san_pham[]" value="<?= $row['ma_san_pham'] ?>">
                    </td>

                    <td><?= $row['Ten_san_pham'] ?></td>

                    <td>
                        <img src="images/<?= $row['Hinh_anh'] ?>" width="80">
                    </td>

                    <td><?= number_format($row['Don_gia'], 0, ',', '.') ?> VND</td>

                    <td>
                        <div style="display:flex; gap:5px;">
                            <input type="number" id="soluong_<?= $row['ma_san_pham'] ?>" value="<?= $row['so_luong'] ?>"
                                min="1" class="form-control" style="width:60px;">

                            <button type="button" class="btn btn-warning btn-sm"
                                onclick="capNhatSoLuong('<?= $row['ma_san_pham'] ?>')">
                                Cập nhật
                            </button>
                        </div>
                    </td>

                    <td><?= number_format($thanhTien,0,',','.') ?> VND</td>

                    <td>
                        <button type="button" class="btn btn-danger btn-sm"
                            onclick="xoaKhoiGio('<?= $row['ma_san_pham'] ?>')">
                            Xóa
                        </button>
                    </td>
                </tr>

                <?php endwhile; ?>
            </tbody>

            <tfoot>
                <tr>
                    <td colspan="5" class="text-end"><strong>Tổng tiền:</strong></td>
                    <td colspan="2"><strong><?= number_format($tongTien,0,',','.') ?> VND</strong></td>
                </tr>
            </tfoot>
        </table>

        <div class="mt-3">
            <a href="index.php" class="btn btn-secondary">Tiếp tục mua hàng</a>
            <button type="button" onclick="datHang()" class="btn btn-success">Thanh toán</button>
        </div>
    </form>

    <?php else: ?>

    <div class="alert alert-info">
        Giỏ hàng của bạn hiện đang trống.
        <br><br>
        <a href="index.php" class="btn btn-primary">Mua sắm ngay</a>
    </div>

    <?php endif; ?>
</div>

<?php include('includes/footer.html'); ?>
<script src="./js/gio_hang.js"></script>