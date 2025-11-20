<?php
// Doanh thu theo GIỜ (24 giờ trong ngày hôm nay)
$sql_doanhThu_gio = "
SELECT 
    HOUR(hd.Ngay_tao) AS gio,
    SUM(hd.Tong_tien) AS doanh_thu
FROM hoa_don AS hd
WHERE hd.Trang_thai = 3   
    AND DATE(hd.Ngay_tao) = CURDATE()
GROUP BY HOUR(hd.Ngay_tao)
ORDER BY gio ASC
";
$result_doanhThu_gio = mysqli_query($conn, $sql_doanhThu_gio);

// Khởi tạo mảng 24 giờ với giá trị 0
$data_doanhThu_gio = array_fill(0, 24, 0);
while ($row = mysqli_fetch_assoc($result_doanhThu_gio)) {
    $data_doanhThu_gio[(int)$row['gio']] = (float)$row['doanh_thu'];
}
$labels_doanhThu_gio = range(0, 23); // [0, 1, 2, ..., 23]

// Doanh thu theo NGÀY (các ngày trong tháng hiện tại)
$sql_doanhThu_ngay = "
SELECT 
    DAY(hd.Ngay_tao) AS ngay,
    SUM(hd.Tong_tien) AS doanh_thu
FROM hoa_don AS hd
WHERE hd.Trang_thai = 3   
    AND MONTH(hd.Ngay_tao) = MONTH(CURDATE())
    AND YEAR(hd.Ngay_tao) = YEAR(CURDATE())
GROUP BY DAY(hd.Ngay_tao)
ORDER BY ngay ASC
";
$result_doanhThu_ngay = mysqli_query($conn, $sql_doanhThu_ngay);

// Lấy số ngày trong tháng hiện tại
$soNgayTrongThang = date('t');
$data_doanhThu_ngay = array_fill(0, $soNgayTrongThang, 0);
while ($row = mysqli_fetch_assoc($result_doanhThu_ngay)) {
    $data_doanhThu_ngay[(int)$row['ngay'] - 1] = (float)$row['doanh_thu'];
}
$labels_doanhThu_ngay = range(1, $soNgayTrongThang); // [1, 2, 3, ..., 30/31]

// Doanh thu theo THÁNG (12 tháng trong năm hiện tại)
$sql_doanhThu_thang = "
SELECT 
    MONTH(hd.Ngay_tao) AS thang,
    SUM(hd.Tong_tien) AS doanh_thu
FROM hoa_don AS hd
WHERE hd.Trang_thai = 3   
    AND YEAR(hd.Ngay_tao) = YEAR(CURDATE())
GROUP BY MONTH(hd.Ngay_tao)
ORDER BY thang ASC
";
$result_doanhThu_thang = mysqli_query($conn, $sql_doanhThu_thang);

// Khởi tạo mảng 12 tháng với giá trị 0
$data_doanhThu_thang = array_fill(0, 12, 0);
while ($row = mysqli_fetch_assoc($result_doanhThu_thang)) {
    $data_doanhThu_thang[(int)$row['thang'] - 1] = (float)$row['doanh_thu'];
}
$labels_doanhThu_thang = ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'T8', 'T9', 'T10', 'T11', 'T12'];
?>

<div class="row">
    <div class="col-xl-12 col-lg-12">
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary" id="chartTitle_doanhThu">
                    Doanh thu theo giờ (Hôm nay)
                </h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle btn btn-sm btn-outline-primary" href="#" role="button"
                        id="dropdownTime_doanhThu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-calendar-alt"></i> <span id="timeLabel_doanhThu">Theo giờ</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                        aria-labelledby="dropdownTime_doanhThu">
                        <div class="dropdown-header">Chọn thời gian:</div>
                        <a class="dropdown-item" href="#" onclick="changeTimeFilter_doanhThu('gio'); return false;">
                            <i class="fas fa-clock"></i> Theo giờ (Hôm nay)
                        </a>
                        <a class="dropdown-item" href="#" onclick="changeTimeFilter_doanhThu('ngay'); return false;">
                            <i class="fas fa-calendar-day"></i> Theo ngày (Tháng này)
                        </a>
                        <a class="dropdown-item" href="#" onclick="changeTimeFilter_doanhThu('thang'); return false;">
                            <i class="fas fa-calendar"></i> Theo tháng (Năm nay)
                        </a>
                    </div>
                </div>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <canvas id="myChart_doanhThu" width="400" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
    // Dữ liệu từ PHP - Doanh thu theo thời gian
    const dataSource_doanhThu = {
        gio: {
            labels: <?php echo json_encode($labels_doanhThu_gio); ?>,
            data: <?php echo json_encode($data_doanhThu_gio); ?>,
            title: 'Doanh thu theo giờ (Hôm nay)',
            labelText: 'Theo giờ',
            xAxisLabel: 'Giờ'
        },
        ngay: {
            labels: <?php echo json_encode($labels_doanhThu_ngay); ?>,
            data: <?php echo json_encode($data_doanhThu_ngay); ?>,
            title: 'Doanh thu theo ngày (Tháng <?php echo date("m/Y"); ?>)',
            labelText: 'Theo ngày',
            xAxisLabel: 'Ngày'
        },
        thang: {
            labels: <?php echo json_encode($labels_doanhThu_thang); ?>,
            data: <?php echo json_encode($data_doanhThu_thang); ?>,
            title: 'Doanh thu theo tháng (Năm <?php echo date("Y"); ?>)',
            labelText: 'Theo tháng',
            xAxisLabel: 'Tháng'
        }
    };

    // Biến lưu trạng thái hiện tại
    let currentTimeFilter_doanhThu = 'gio';

    // Khởi tạo chart
    const ctx_doanhThu = document.getElementById('myChart_doanhThu').getContext('2d');
    const myChart_doanhThu = new Chart(ctx_doanhThu, {
        type: 'line',
        data: {
            labels: dataSource_doanhThu.gio.labels,
            datasets: [{
                label: 'Doanh thu (VNĐ)',
                data: dataSource_doanhThu.gio.data,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2,
                tension: 0.4,
                fill: true,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString('vi-VN') + ' đ';
                        }
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: dataSource_doanhThu.gio.xAxisLabel
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Doanh thu: ' + context.parsed.y.toLocaleString('vi-VN') + ' đ';
                        }
                    }
                },
                legend: {
                    display: true
                }
            },
            animation: {
                duration: 750
            }
        }
    });

    // Hàm thay đổi bộ lọc thời gian
    function changeTimeFilter_doanhThu(timeType) {
        currentTimeFilter_doanhThu = timeType;

        const source = dataSource_doanhThu[timeType];

        // Update dữ liệu chart
        myChart_doanhThu.data.labels = source.labels;
        myChart_doanhThu.data.datasets[0].data = source.data;

        // Update tiêu đề
        document.getElementById('chartTitle_doanhThu').textContent = source.title;

        // Update label thời gian
        document.getElementById('timeLabel_doanhThu').textContent = source.labelText;

        // Update label trục X
        myChart_doanhThu.options.scales.x.title.text = source.xAxisLabel;

        myChart_doanhThu.update();
    }
</script>