<?php
//Top 5 nhà cung cấp bán ít/nhiều nhất (start)

// TOP 5 bán nhiều nhất theo NGÀY (hôm nay)
$sql_topNhieu_nhaCungCap_ngay = "
SELECT 
    ncc.Ten_nha_cung_cap,
    SUM(ct.So_luong) AS tong_so_luong
FROM chi_tiet_hoa_don AS ct
JOIN san_pham AS sp 
    ON ct.Ma_san_pham = sp.Ma_san_pham
JOIN nha_cung_cap AS ncc
    ON sp.Ma_nha_cung_cap = ncc.Ma_nha_cung_cap
JOIN hoa_don AS hd
    ON ct.Ma_hoa_don = hd.Ma_hoa_don
WHERE hd.Trang_thai = 3   
    AND DATE(hd.Ngay_tao) = CURDATE()
GROUP BY ncc.Ma_nha_cung_cap, ncc.Ten_nha_cung_cap
ORDER BY tong_so_luong DESC
LIMIT 5
";
$result_topNhieu_nhaCungCap_ngay = mysqli_query($conn, $sql_topNhieu_nhaCungCap_ngay);
$labels_topNhieu_nhaCungCap_ngay = [];
$data_topNhieu_nhaCungCap_ngay = [];
while ($row = mysqli_fetch_assoc($result_topNhieu_nhaCungCap_ngay)) {
    $labels_topNhieu_nhaCungCap_ngay[] = $row['Ten_nha_cung_cap'];
    $data_topNhieu_nhaCungCap_ngay[] = (int)$row['tong_so_luong'];
}

// TOP 5 bán ít nhất theo NGÀY
$sql_topIt_nhaCungCap_ngay = "
SELECT 
    ncc.Ten_nha_cung_cap,
    SUM(ct.So_luong) AS tong_so_luong
FROM chi_tiet_hoa_don AS ct
JOIN san_pham AS sp 
    ON ct.Ma_san_pham = sp.Ma_san_pham
JOIN nha_cung_cap AS ncc
    ON sp.Ma_nha_cung_cap = ncc.Ma_nha_cung_cap
JOIN hoa_don AS hd
    ON ct.Ma_hoa_don = hd.Ma_hoa_don
WHERE hd.Trang_thai = 3   
    AND DATE(hd.Ngay_tao) = CURDATE()
GROUP BY ncc.Ma_nha_cung_cap, ncc.Ten_nha_cung_cap
ORDER BY tong_so_luong ASC
LIMIT 5
";
$result_topIt_nhaCungCap_ngay = mysqli_query($conn, $sql_topIt_nhaCungCap_ngay);
$labels_topIt_nhaCungCap_ngay = [];
$data_topIt_nhaCungCap_ngay = [];
while ($row = mysqli_fetch_assoc($result_topIt_nhaCungCap_ngay)) {
    $labels_topIt_nhaCungCap_ngay[] = $row['Ten_nha_cung_cap'];
    $data_topIt_nhaCungCap_ngay[] = (int)$row['tong_so_luong'];
}

// TOP 5 bán nhiều nhất theo THÁNG (tháng hiện tại)
$sql_topNhieu_nhaCungCap_thang = "
SELECT 
    ncc.Ten_nha_cung_cap,
    SUM(ct.So_luong) AS tong_so_luong
FROM chi_tiet_hoa_don AS ct
JOIN san_pham AS sp 
    ON ct.Ma_san_pham = sp.Ma_san_pham
JOIN nha_cung_cap AS ncc
    ON sp.Ma_nha_cung_cap = ncc.Ma_nha_cung_cap
JOIN hoa_don AS hd
    ON ct.Ma_hoa_don = hd.Ma_hoa_don
WHERE hd.Trang_thai = 3   
    AND MONTH(hd.Ngay_tao) = MONTH(CURDATE())
    AND YEAR(hd.Ngay_tao) = YEAR(CURDATE())
GROUP BY ncc.Ma_nha_cung_cap, ncc.Ten_nha_cung_cap
ORDER BY tong_so_luong DESC
LIMIT 5
";
$result_topNhieu_nhaCungCap_thang = mysqli_query($conn, $sql_topNhieu_nhaCungCap_thang);
$labels_topNhieu_nhaCungCap_thang = [];
$data_topNhieu_nhaCungCap_thang = [];
while ($row = mysqli_fetch_assoc($result_topNhieu_nhaCungCap_thang)) {
    $labels_topNhieu_nhaCungCap_thang[] = $row['Ten_nha_cung_cap'];
    $data_topNhieu_nhaCungCap_thang[] = (int)$row['tong_so_luong'];
}

// TOP 5 bán ít nhất theo THÁNG
$sql_topIt_nhaCungCap_thang = "
SELECT 
    ncc.Ten_nha_cung_cap,
    SUM(ct.So_luong) AS tong_so_luong
FROM chi_tiet_hoa_don AS ct
JOIN san_pham AS sp 
    ON ct.Ma_san_pham = sp.Ma_san_pham
JOIN nha_cung_cap AS ncc
    ON sp.Ma_nha_cung_cap = ncc.Ma_nha_cung_cap
JOIN hoa_don AS hd
    ON ct.Ma_hoa_don = hd.Ma_hoa_don
WHERE hd.Trang_thai = 3   
    AND MONTH(hd.Ngay_tao) = MONTH(CURDATE())
    AND YEAR(hd.Ngay_tao) = YEAR(CURDATE())
GROUP BY ncc.Ma_nha_cung_cap, ncc.Ten_nha_cung_cap
ORDER BY tong_so_luong ASC
LIMIT 5
";
$result_topIt_nhaCungCap_thang = mysqli_query($conn, $sql_topIt_nhaCungCap_thang);
$labels_topIt_nhaCungCap_thang = [];
$data_topIt_nhaCungCap_thang = [];
while ($row = mysqli_fetch_assoc($result_topIt_nhaCungCap_thang)) {
    $labels_topIt_nhaCungCap_thang[] = $row['Ten_nha_cung_cap'];
    $data_topIt_nhaCungCap_thang[] = (int)$row['tong_so_luong'];
}

// TOP 5 bán nhiều nhất theo NĂM (năm hiện tại)
$sql_topNhieu_nhaCungCap_nam = "
SELECT 
    ncc.Ten_nha_cung_cap,
    SUM(ct.So_luong) AS tong_so_luong
FROM chi_tiet_hoa_don AS ct
JOIN san_pham AS sp 
    ON ct.Ma_san_pham = sp.Ma_san_pham
JOIN nha_cung_cap AS ncc
    ON sp.Ma_nha_cung_cap = ncc.Ma_nha_cung_cap
JOIN hoa_don AS hd
    ON ct.Ma_hoa_don = hd.Ma_hoa_don
WHERE hd.Trang_thai = 3   
    AND YEAR(hd.Ngay_tao) = YEAR(CURDATE())
GROUP BY ncc.Ma_nha_cung_cap, ncc.Ten_nha_cung_cap
ORDER BY tong_so_luong DESC
LIMIT 5
";
$result_topNhieu_nhaCungCap_nam = mysqli_query($conn, $sql_topNhieu_nhaCungCap_nam);
$labels_topNhieu_nhaCungCap_nam = [];
$data_topNhieu_nhaCungCap_nam = [];
while ($row = mysqli_fetch_assoc($result_topNhieu_nhaCungCap_nam)) {
    $labels_topNhieu_nhaCungCap_nam[] = $row['Ten_nha_cung_cap'];
    $data_topNhieu_nhaCungCap_nam[] = (int)$row['tong_so_luong'];
}

// TOP 5 bán ít nhất theo NĂM
$sql_topIt_nhaCungCap_nam = "
SELECT 
    ncc.Ten_nha_cung_cap,
    SUM(ct.So_luong) AS tong_so_luong
FROM chi_tiet_hoa_don AS ct
JOIN san_pham AS sp 
    ON ct.Ma_san_pham = sp.Ma_san_pham
JOIN nha_cung_cap AS ncc
    ON sp.Ma_nha_cung_cap = ncc.Ma_nha_cung_cap
JOIN hoa_don AS hd
    ON ct.Ma_hoa_don = hd.Ma_hoa_don
WHERE hd.Trang_thai = 3   
    AND YEAR(hd.Ngay_tao) = YEAR(CURDATE())
GROUP BY ncc.Ma_nha_cung_cap, ncc.Ten_nha_cung_cap
ORDER BY tong_so_luong ASC
LIMIT 5
";
$result_topIt_nhaCungCap_nam = mysqli_query($conn, $sql_topIt_nhaCungCap_nam);
$labels_topIt_nhaCungCap_nam = [];
$data_topIt_nhaCungCap_nam = [];
while ($row = mysqli_fetch_assoc($result_topIt_nhaCungCap_nam)) {
    $labels_topIt_nhaCungCap_nam[] = $row['Ten_nha_cung_cap'];
    $data_topIt_nhaCungCap_nam[] = (int)$row['tong_so_luong'];
}
//Top 5 nhà cung cấp bán ít/nhiều nhất (end)
?>

<div class="row">
    <div class="col-xl-12 col-lg-12">
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary" id="chartTitle_nhaCungCap">
                    5 nhà cung cấp bán nhiều nhất (Hôm nay)
                </h6>
                <div class="d-flex gap-2">
                    <!-- Dropdown chọn thời gian -->
                    <div class="dropdown no-arrow mr-2">
                        <a class="dropdown-toggle btn btn-sm btn-outline-primary" href="#" role="button"
                            id="dropdownTime_nhaCungCap" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-calendar-alt"></i> <span id="timeLabel_nhaCungCap">Hôm nay</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                            aria-labelledby="dropdownTime_nhaCungCap">
                            <div class="dropdown-header">Chọn thời gian:</div>
                            <a class="dropdown-item" href="#" onclick="changeTimeFilter_nhaCungCap('ngay'); return false;">
                                <i class="fas fa-calendar-day"></i> Hôm nay
                            </a>
                            <a class="dropdown-item" href="#" onclick="changeTimeFilter_nhaCungCap('thang'); return false;">
                                <i class="fas fa-calendar-week"></i> Tháng này
                            </a>
                            <a class="dropdown-item" href="#" onclick="changeTimeFilter_nhaCungCap('nam'); return false;">
                                <i class="fas fa-calendar"></i> Năm nay
                            </a>
                        </div>
                    </div>

                    <!-- Dropdown chọn nhiều/ít -->
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle btn btn-sm btn-outline-secondary" href="#" role="button"
                            id="dropdownSort_nhaCungCap" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-sort-amount-down"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                            aria-labelledby="dropdownSort_nhaCungCap">
                            <div class="dropdown-header">Sắp xếp:</div>
                            <a class="dropdown-item" href="#" onclick="changeSortType_nhaCungCap('nhieu'); return false;">
                                <i class="fas fa-sort-amount-down"></i> Nhiều nhất
                            </a>
                            <a class="dropdown-item" href="#" onclick="changeSortType_nhaCungCap('it'); return false;">
                                <i class="fas fa-sort-amount-up"></i> Ít nhất
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <canvas id="myChart_nhaCungCap" width="400" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Top 5 nhà cung cấp bán ít/nhiều nhất (start) -->
<script>
    // Dữ liệu từ PHP - Chart theo thời gian
    const dataSource_nhaCungCap = {
        ngay: {
            nhieu: {
                labels: <?php echo json_encode($labels_topNhieu_nhaCungCap_ngay); ?>,
                data: <?php echo json_encode($data_topNhieu_nhaCungCap_ngay); ?>,
                title: '5 nhà cung cấp bán nhiều nhất (Hôm nay)'
            },
            it: {
                labels: <?php echo json_encode($labels_topIt_nhaCungCap_ngay); ?>,
                data: <?php echo json_encode($data_topIt_nhaCungCap_ngay); ?>,
                title: '5 nhà cung cấp bán ít nhất (Hôm nay)'
            }
        },
        thang: {
            nhieu: {
                labels: <?php echo json_encode($labels_topNhieu_nhaCungCap_thang); ?>,
                data: <?php echo json_encode($data_topNhieu_nhaCungCap_thang); ?>,
                title: '5 nhà cung cấp bán nhiều nhất (Tháng này)'
            },
            it: {
                labels: <?php echo json_encode($labels_topIt_nhaCungCap_thang); ?>,
                data: <?php echo json_encode($data_topIt_nhaCungCap_thang); ?>,
                title: '5 nhà cung cấp bán ít nhất (Tháng này)'
            }
        },
        nam: {
            nhieu: {
                labels: <?php echo json_encode($labels_topNhieu_nhaCungCap_nam); ?>,
                data: <?php echo json_encode($data_topNhieu_nhaCungCap_nam); ?>,
                title: '5 nhà cung cấp bán nhiều nhất (Năm nay)'
            },
            it: {
                labels: <?php echo json_encode($labels_topIt_nhaCungCap_nam); ?>,
                data: <?php echo json_encode($data_topIt_nhaCungCap_nam); ?>,
                title: '5 nhà cung cấp bán ít nhất (Năm nay)'
            }
        }
    };

    // Biến lưu trạng thái hiện tại
    let currentTimeFilter_nhaCungCap = 'ngay';
    let currentSortType_nhaCungCap = 'nhieu';

    // Khởi tạo chart
    const ctx_nhaCungCap = document.getElementById('myChart_nhaCungCap').getContext('2d');
    const myChart_nhaCungCap = new Chart(ctx_nhaCungCap, {
        type: 'bar',
        data: {
            labels: dataSource_nhaCungCap.ngay.nhieu.labels,
            datasets: [{
                label: 'Số lượng đã bán',
                data: dataSource_nhaCungCap.ngay.nhieu.data,
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
            },
            animation: {
                duration: 750
            }
        }
    });

    // Hàm cập nhật chart
    function updateChart_nhaCungCap() {
        const source = dataSource_nhaCungCap[currentTimeFilter_nhaCungCap][currentSortType_nhaCungCap];

        // Update dữ liệu chart
        myChart_nhaCungCap.data.labels = source.labels;
        myChart_nhaCungCap.data.datasets[0].data = source.data;

        // Update tiêu đề
        document.getElementById('chartTitle_nhaCungCap').textContent = source.title;

        // Đổi màu theo loại sắp xếp
        if (currentSortType_nhaCungCap === 'it') {
            myChart_nhaCungCap.data.datasets[0].backgroundColor = 'rgba(255, 99, 132, 0.2)';
            myChart_nhaCungCap.data.datasets[0].borderColor = 'rgba(255, 99, 132, 1)';
        } else {
            myChart_nhaCungCap.data.datasets[0].backgroundColor = 'rgba(75, 192, 192, 0.2)';
            myChart_nhaCungCap.data.datasets[0].borderColor = 'rgba(75, 192, 192, 1)';
        }

        myChart_nhaCungCap.update();
    }

    // Hàm thay đổi bộ lọc thời gian
    function changeTimeFilter_nhaCungCap(timeType) {
        currentTimeFilter_nhaCungCap = timeType;

        // Update label thời gian
        const timeLabels = {
            'ngay': 'Hôm nay',
            'thang': 'Tháng này',
            'nam': 'Năm nay'
        };
        document.getElementById('timeLabel_nhaCungCap').textContent = timeLabels[timeType];

        updateChart_nhaCungCap();
    }

    // Hàm thay đổi kiểu sắp xếp
    function changeSortType_nhaCungCap(sortType) {
        currentSortType_nhaCungCap = sortType;
        updateChart_nhaCungCap();
    }
</script>
<!-- Top 5 nhà cung cấp bán ít/nhiều nhất (end) -->