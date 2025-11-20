<?php
$query_hoa_don_status_on = "SELECT COUNT(*) AS so_don
FROM hoa_don
WHERE Loai_don_hang = 1";
$query_hoa_don_status_on_result = mysqli_query($conn, $query_hoa_don_status_on);
$hoa_don_status_on_row = mysqli_fetch_assoc($query_hoa_don_status_on_result);
$hoa_don_status_on = (int)$hoa_don_status_on_row['so_don'];

$query_hoa_don_status_off = "SELECT COUNT(*) AS so_don
FROM hoa_don
WHERE Loai_don_hang = 0";
$query_hoa_don_status_off_result = mysqli_query($conn, $query_hoa_don_status_off);
$hoa_don_status_off_row = mysqli_fetch_assoc($query_hoa_don_status_off_result);
$hoa_don_status_off = (int)$hoa_don_status_off_row['so_don'];

// Chờ xác nhận (0)
$q0 = mysqli_query($conn, "SELECT COUNT(*) AS so_don FROM hoa_don WHERE Trang_thai = 0");
$don_0 = (int) mysqli_fetch_assoc($q0)['so_don'];

// Đã xác nhận (1)
$q1 = mysqli_query($conn, "SELECT COUNT(*) AS so_don FROM hoa_don WHERE Trang_thai = 1");
$don_1 = (int) mysqli_fetch_assoc($q1)['so_don'];

// Đã giao vận chuyển (2)
$q2 = mysqli_query($conn, "SELECT COUNT(*) AS so_don FROM hoa_don WHERE Trang_thai = 2");
$don_2 = (int) mysqli_fetch_assoc($q2)['so_don'];

// Đã hoàn thành (3)
$q3 = mysqli_query($conn, "SELECT COUNT(*) AS so_don FROM hoa_don WHERE Trang_thai = 3");
$don_3 = (int) mysqli_fetch_assoc($q3)['so_don'];

// Đã hủy (4)
$q4 = mysqli_query($conn, "SELECT COUNT(*) AS so_don FROM hoa_don WHERE Trang_thai = 4");
$don_4 = (int) mysqli_fetch_assoc($q4)['so_don'];

$query_tong_hd = "SELECT COUNT(*) AS tong_hoa_don FROM hoa_don";
$result_tong_hd = mysqli_query($conn, $query_tong_hd);

$row_tong_hd = mysqli_fetch_assoc($result_tong_hd);
$tong_hoa_don = (int)$row_tong_hd['tong_hoa_don'];

// Số hóa đơn hôm nay
$don_hom_nay_query = "SELECT COUNT(*) AS so_don FROM hoa_don WHERE DATE(Ngay_tao) = CURDATE()";
$don_hom_nay_result = mysqli_query($conn, $don_hom_nay_query);
$don_hom_nay = (int) mysqli_fetch_assoc($don_hom_nay_result)['so_don'];

// Số hóa đơn tháng này
$don_thang_nay_query = "SELECT COUNT(*) AS so_don FROM hoa_don WHERE YEAR(Ngay_tao) = YEAR(CURDATE()) AND MONTH(Ngay_tao) = MONTH(CURDATE())";
$don_thang_nay_result = mysqli_query($conn, $don_thang_nay_query);
$don_thang_nay = (int) mysqli_fetch_assoc($don_thang_nay_result)['so_don'];

// Số hóa đơn năm này
$don_nam_nay_query = "SELECT COUNT(*) AS so_don FROM hoa_don WHERE YEAR(Ngay_tao) = YEAR(CURDATE())";
$don_nam_nay_result = mysqli_query($conn, $don_nam_nay_query);
$don_nam_nay = (int) mysqli_fetch_assoc($don_nam_nay_result)['so_don'];

// Tổng số tài khoản
$sql_total = "SELECT COUNT(*) AS total FROM tai_khoan";
$result_total = mysqli_query($conn, $sql_total);
$total_accounts = (int)mysqli_fetch_assoc($result_total)['total'];

// Tổng số tài khoản nhân viên (Ma_nhan_vien NOT NULL)
$sql_nv = "SELECT COUNT(*) AS total_nv FROM tai_khoan WHERE Ma_nhan_vien IS NOT NULL";
$result_nv = mysqli_query($conn, $sql_nv);
$total_nv = (int)mysqli_fetch_assoc($result_nv)['total_nv'];

// Tổng số tài khoản khách hàng (Ma_khach_hang NOT NULL)
$sql_kh = "SELECT COUNT(*) AS total_kh FROM tai_khoan WHERE Ma_khach_hang IS NOT NULL";
$result_kh = mysqli_query($conn, $sql_kh);
$total_kh = (int)mysqli_fetch_assoc($result_kh)['total_kh'];

// Số tài khoản hoạt động (Trang_thai = 1)
$sql_active = "SELECT COUNT(*) AS active FROM tai_khoan WHERE Trang_thai = 1";
$result_active = mysqli_query($conn, $sql_active);
$active_accounts = (int)mysqli_fetch_assoc($result_active)['active'];

// Số tài khoản bị khóa (Trang_thai = 0)
$sql_locked = "SELECT COUNT(*) AS locked FROM tai_khoan WHERE Trang_thai = 0";
$result_locked = mysqli_query($conn, $sql_locked);
$locked_accounts = (int)mysqli_fetch_assoc($result_locked)['locked'];

// --- Thống kê theo ngày ---
$sql_by_day = "
SELECT DATE(Ngay_tao) AS ngay, COUNT(*) AS total
FROM tai_khoan
GROUP BY DATE(Ngay_tao)
ORDER BY DATE(Ngay_tao) DESC
";
$result_by_day = mysqli_query($conn, $sql_by_day);
$accounts_by_day = [];
while ($row = mysqli_fetch_assoc($result_by_day)) {
    $accounts_by_day[] = $row;
}

// --- Thống kê theo tháng ---
$sql_by_month = "
SELECT DATE_FORMAT(Ngay_tao, '%Y-%m') AS thang, COUNT(*) AS total
FROM tai_khoan
GROUP BY DATE_FORMAT(Ngay_tao, '%Y-%m')
ORDER BY DATE_FORMAT(Ngay_tao, '%Y-%m') DESC
";
$result_by_month = mysqli_query($conn, $sql_by_month);
$accounts_by_month = [];
while ($row = mysqli_fetch_assoc($result_by_month)) {
    $accounts_by_month[] = $row;
}

// --- Thống kê theo năm ---
$sql_by_year = "
SELECT YEAR(Ngay_tao) AS nam, COUNT(*) AS total
FROM tai_khoan
GROUP BY YEAR(Ngay_tao)
ORDER BY YEAR(Ngay_tao) DESC
";
$result_by_year = mysqli_query($conn, $sql_by_year);
$accounts_by_year = [];
while ($row = mysqli_fetch_assoc($result_by_year)) {
    $accounts_by_year[] = $row;
}

$sql = "select Ten_san_pham, So_luong from san_pham";
$result = mysqli_query($conn, $sql);
$labels = array();
$data = array();

while ($row = mysqli_fetch_assoc($result)) {
    $labels[] = $row['Ten_san_pham'];
    $data[] = (int) $row['So_luong'];
}

?>
<!-- Content Row -->
<h1 class="h3 mb-0 text-gray-800">Hoá đơn</h1>

<div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div
                            class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Số đơn hàng đặt online </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo $hoa_don_status_on ?> </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-file-invoice fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div
                            class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Số đơn hàng đặt offline </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo $hoa_don_status_off ?> </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-file-invoice fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Tổng hóa đơn
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo $tong_hoa_don; ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-file-invoice fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <!-- Chờ xác nhận (0) -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Chờ xác nhận
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo $don_0; ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-hourglass-start fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Đã xác nhận (1) -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Đã xác nhận
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo $don_1; ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Đã giao vận chuyển (2) -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Đang giao hàng
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo $don_2; ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-shipping-fast fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Đã hoàn thành (3) -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Đã hoàn thành
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo $don_3; ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-double fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Đã hủy (4) -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            Đã hủy
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo $don_4; ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<div class="row">
    <!-- Hóa đơn hôm nay -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Hóa đơn hôm nay
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo $don_hom_nay; ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hóa đơn tháng này -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Hóa đơn tháng này
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo $don_thang_nay; ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hóa đơn năm này -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Hóa đơn năm này
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo $don_nam_nay; ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Tổng tài khoản -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Tổng tài khoản
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo $total_accounts; ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tài khoản nhân viên -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Tài khoản nhân viên
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo $total_nv; ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-tie fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tài khoản khách hàng -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Tài khoản khách hàng
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo $total_kh; ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tài khoản hoạt động -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Hoạt động
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo $active_accounts; ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tài khoản bị khóa -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            Bị khóa
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo $locked_accounts; ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-lock fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Thống kê theo ngày -->
    <?php foreach ($accounts_by_day as $item): ?>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Ngày: <?php echo $item['ngay']; ?>
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo $item['total']; ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <!-- Thống kê theo tháng -->
    <?php foreach ($accounts_by_month as $item): ?>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Tháng: <?php echo $item['thang']; ?>
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo $item['total']; ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <!-- Thống kê theo năm -->
    <?php foreach ($accounts_by_year as $item): ?>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Năm: <?php echo $item['nam']; ?>
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo $item['total']; ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>


</div>
<div class="row"><!-- Thống kê theo ngày -->
    <?php foreach ($accounts_by_day as $item): ?>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Ngày: <?php echo $item['ngay']; ?>
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo $item['total']; ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <!-- Thống kê theo tháng -->
    <?php foreach ($accounts_by_month as $item): ?>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Tháng: <?php echo $item['thang']; ?>
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo $item['total']; ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <!-- Thống kê theo năm -->
    <?php foreach ($accounts_by_year as $item): ?>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Năm: <?php echo $item['nam']; ?>
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo $item['total']; ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <h2>Số lượng khách hàng theo sản phẩm</h2>
    <canvas id="myChart" width="400" height="200"></canvas>
</div>


<script>
    const labels = <?php echo json_encode($labels); ?>;
    const data = <?php echo json_encode($data); ?>;

    const ctx = document.getElementById('myChart').getContext('2d');
    const myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Số lượng khách hàng',
                data: data,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>