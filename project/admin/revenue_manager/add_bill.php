<?php
include(__DIR__ . '/../_includes/config.php');
// --- Tạo mã hoá đơn tự động ---
$query_max = "SELECT MAX(CAST(SUBSTRING(Ma_hoa_don, 3) AS UNSIGNED)) AS max_id FROM hoa_don";
$result_max = mysqli_query($conn, $query_max);
$row_max = mysqli_fetch_assoc($result_max);
$new_id = 'HD' . ($row_max['max_id'] + 1);
$now_id = $new_id;
if (isset($_GET['ajax'])) {
    $type = isset($_GET['type']) ? $_GET['type'] : '';
    $term = isset($_GET['term']) ? $_GET['term'] : '';
    $data = array();
    if ($type === 'customer') {
        $sql = "select Dien_thoai,Ten_khach_hang from khach_hang where Dien_thoai like '%$term%' limit 5";
        $result = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = array(
                "label" => $row['Dien_thoai'], // sẽ hiển thị trong dropdown
                "value" => $row['Dien_thoai'],  // sẽ điền vào input khi chọn
                "name" => $row['Ten_khach_hang']    // để điền vào ô readonly
            );
        }
    } elseif ($type === 'product') {
        $sql = "select Ten_san_pham, So_luong from san_pham where Ten_san_pham like '%$term%' and So_luong > 0 limit 5";
        $result = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = array(
                "label" => $row['Ten_san_pham'],
                "value" => $row['Ten_san_pham'],
                "SoLuong" => $row['So_luong']
            );
        }
    } elseif ($type === 'check_exist') {
        $field = $_GET['field']; // ví dụ: Dien_thoai, Ten_san_pham,...
        $term = $_GET['term'];
        // kiểm tra tương ứng tuỳ ô nhập
        if ($field === 'Dien_thoai') {
            $sql = "SELECT 1 FROM khach_hang WHERE Dien_thoai = '$term'";
        } elseif ($field === 'Ten_san_pham[]') {
            $sql = "SELECT 1 FROM san_pham WHERE Ten_san_pham = '$term' and So_luong > 0";
        }

        $result = mysqli_query($conn, $sql);
        echo json_encode(['exists' => mysqli_num_rows($result) > 0]);
        exit;
    }
    echo json_encode($data);
    exit;
}

if (isset($_POST['submit'])) {
    $dien_thoai = isset($_POST['Dien_thoai']) ? $_POST['Dien_thoai'] : '';
    $products = isset($_POST['Ten_san_pham']) ? $_POST['Ten_san_pham'] : [];
    $quantities = isset($_POST['So_luong']) ? $_POST['So_luong'] : [];
    $maNV = 'NV1';
    $query_maKH = "select Ma_khach_hang from khach_hang where Dien_thoai = '$dien_thoai'";
    $query_maKH_result = mysqli_query($conn, $query_maKH);
    $maKH = mysqli_fetch_assoc($query_maKH_result);
    $maKH = $maKH['Ma_khach_hang'];

    $query_add_bill = "insert into hoa_don (Ma_hoa_don, Ma_khach_hang, Ma_nhan_vien, Trang_thai, Loai_don_hang, Tong_tien) values ('$new_id', '$maKH', '$maNV', 1, 0, 0)";
    $query_add_bill_result = mysqli_query($conn, $query_add_bill);
    if ($query_add_bill_result) {
        echo '<div class="alert alert-success">Thêm hoá đơn thành công! Mã hoá đơn: ' . $new_id . '</div>';
    } else {
        echo '<div class="alert alert-danger">Lỗi: ' . mysqli_error($conn) . '</div>';
    }
    for ($i = 0; $i < count($products); $i++) {
        $tenSP = $products[$i];
        $soLuong = (int)$quantities[$i];
        // Lấy mã sản phẩm, đơn giá, số lượng từ tên
        $query_MaSP = "select Ma_san_pham, Don_gia from san_pham where Ten_san_pham = '$tenSP'";
        $query_MaSP_result = mysqli_query($conn, $query_MaSP);
        $query_MaSP_result_show = mysqli_fetch_assoc($query_MaSP_result);
        $maSP = $query_MaSP_result_show['Ma_san_pham'];
        $don_gia = $query_MaSP_result_show['Don_gia'];
        //Cập nhật số lượng sản phẩm từ mã sản phẩm
        $query_update_SoLuongSP = "update san_pham set So_luong = greatest(So_luong - $soLuong, 0) where Ma_san_pham = '$maSP'";
        if (!mysqli_query($conn, $query_update_SoLuongSP)) {
            echo "Lỗi biến query_update_SoLuongSP: " . mysqli_error($conn);
        }
        //Thêm chi tiết hoá đơn
        $query_add_CTHD = "INSERT INTO chi_tiet_hoa_don (Ma_hoa_don, Ma_san_pham, So_luong, Don_gia) VALUES ('$now_id','$maSP','$soLuong','$don_gia')";

        mysqli_query($conn, $query_add_CTHD);
        // if (!mysqli_query($conn,  $query_add_CTHD)) {
        //     echo "Lỗi biến query_add_CTHD: " . mysqli_error($conn);
        // }
    }
}

?>

<h3>Thêm hoá đơn mới</h3>
<form action="" method="post" enctype="multipart/form-data" class="mt-3">
    <div class="row mb-3">
        <div class="col-md-12">
            <label>Mã hoá đơn:</label>
            <input type="text" name="Ma_hoa_don" class="form-control" value="<?php echo $new_id; ?>" readonly>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-6">
            <label>Số điện thoại:</label>
            <input type="number" id="customer_search" name="Dien_thoai" class="form-control auto-check" placeholder="Nhập số điện thoại" value="<?php echo isset($_POST['Dien_thoai']) ? $_POST['Dien_thoai'] : ''  ?>" required step="any">

        </div>
        <div class="col-md-6">
            <label>Tên khách hàng:</label>
            <input type="text" id="customer_name" name="Ten_khach_hang" class="form-control auto-check" value="<?php echo isset($_POST['Ten_khach_hang']) ? $_POST['Ten_khach_hang'] : ''  ?>" readonly>
        </div>
    </div>
    <div id="product_list">
        <div class="row mb-3 d-flex align-items-end product_row">
            <div class="col-md-6">
                <label>Tên sản phẩm:</label>
                <input type="text" id="product_search" name="Ten_san_pham[]" class="form-control auto-check" placeholder="Nhập tên sản phẩm" required step="any">
            </div>
            <div class="col-md-4">
                <label>Số lượng:</label>
                <input type="number" name="So_luong[]" class="form-control" placeholder="Nhập số lượng" required>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-secondary w-100 add_product_btn auto-check">Thêm sản phẩm</button>
            </div>
        </div>
    </div>

    <button type="submit" name="submit" class="btn btn-primary">Thêm hoá đơn</button>
    <a href="index_admin.php?page=list_bill" class="btn btn-secondary">Về trang hoá đơn</a>
</form>

<script>
    $(function() {
        $(document).ready(function() {
            // Hàm khởi tạo autocomplete cho sản phẩm
            function initProductAutocomplete(inputElement) {
                inputElement.autocomplete({
                    source: function(request, response) {
                        $.ajax({
                            url: "revenue_manager/add_bill.php",
                            dataType: "json",
                            type: 'GET',
                            data: {
                                ajax: 1,
                                type: 'product',
                                term: request.term
                            },
                            success: function(data) {
                                if (data.length === 0) {
                                    response([{
                                        label: "Không tồn tại",
                                        value: "",
                                        isNotFound: true
                                    }])
                                } else {
                                    // Lấy tất cả giá trị đã nhập ở các input khác
                                    var existing = [];
                                    $('input[name="Ten_san_pham[]"]').each(function() {
                                        if ($(this).val()) {
                                            existing.push($(this).val());
                                        }
                                    });

                                    // Lọc ra những giá trị đã tồn tại
                                    var filtered = data.filter(function(item) {
                                        return existing.indexOf(item.value) === -1;
                                    });

                                    response(filtered);
                                }
                            }
                        });
                    },
                    minLength: 1,
                    select: function(event, ui) {
                        if (ui.item.isNotFound) {
                            alert("Sản phẩm này không tồn tại trong database!");
                            $(this).val("");
                            return false;
                        } else {
                            // Lưu số lượng tồn kho vào data attribute
                            $(this).data('max-quantity', ui.item.SoLuong);

                            // Cập nhật placeholder cho ô số lượng tương ứng
                            var quantityInput = $(this).closest('.product_row').find('input[name="So_luong[]"]');
                            quantityInput.attr('placeholder', 'Tồn kho: ' + ui.item.SoLuong);
                            quantityInput.attr('max', ui.item.SoLuong);
                            quantityInput.val(''); // Reset giá trị
                        }
                    }
                });
            }

            // Khởi tạo autocomplete cho ô sản phẩm đầu tiên
            initProductAutocomplete($('#product_search'));

            // Khi nhấn nút thêm
            $(document).on('click', '.add_product_btn', function() {
                var newRow = $(this).closest('.product_row').clone();

                // Xóa giá trị input và data attribute
                newRow.find('input[name="Ten_san_pham[]"]').val('').removeData('max-quantity').removeClass('invalid');
                newRow.find('input[name="So_luong[]"]').val('').attr('placeholder', 'Nhập số lượng').removeAttr('max').removeClass('is-invalid');

                // Khởi tạo autocomplete cho row mới
                initProductAutocomplete(newRow.find('input[name="Ten_san_pham[]"]'));

                // Đổi nút "Thêm" thành nút "Xoá"
                newRow.find('.add_product_btn')
                    .removeClass('add_product_btn btn-secondary')
                    .addClass('remove_product_btn btn-danger')
                    .text('Xoá');

                $('#product_list').append(newRow);
            });

            // Khi nhấn nút xoá
            $(document).on('click', '.remove_product_btn', function() {
                $(this).closest('.product_row').remove();
            });

            // Kiểm tra tên sản phẩm khi blur (cho cả row đầu và row clone)
            $(document).on('blur', 'input[name="Ten_san_pham[]"]', function() {
                var productInput = $(this);
                var productName = productInput.val().trim();
                if (productName === '') return;
                // Kiểm tra xem đã chọn từ autocomplete chưa (có max-quantity)
                if (!productInput.data('max-quantity')) {
                    $.ajax({
                        url: 'revenue_manager/add_bill.php',
                        type: 'GET',
                        dataType: 'json',
                        data: {
                            ajax: 1,
                            type: 'check_exist',
                            field: 'Ten_san_pham[]',
                            term: productName
                        },
                        success: function(res) {
                            if (!res.exists) {
                                productInput.addClass('invalid');
                                alert('Sản phẩm "' + productName + '" không tồn tại trong cơ sở dữ liệu!');
                                productInput.val('');
                                // Reset ô số lượng tương ứng
                                productInput.closest('.product_row').find('input[name="So_luong[]"]')
                                    .val('')
                                    .attr('placeholder', 'Nhập số lượng')
                                    .removeAttr('max');
                            }
                        }
                    });
                }
            });
            // Kiểm tra khi blur (rời khỏi ô số lượng)
            $(document).on('blur', 'input[name="So_luong[]"]', function() {
                var quantityInput = $(this);
                var productInput = quantityInput.closest('.product_row').find('input[name="Ten_san_pham[]"]');
                var maxQuantity = productInput.data('max-quantity');
                var enteredQuantity = parseInt(quantityInput.val());

                // Kiểm tra nếu chưa chọn sản phẩm
                if (!maxQuantity && quantityInput.val()) {
                    alert('Vui lòng chọn sản phẩm trước khi nhập số lượng!');
                    quantityInput.val('');
                    productInput.focus();
                    return;
                }

                // Kiểm tra số lượng vượt quá
                if (maxQuantity && enteredQuantity > maxQuantity) {
                    alert('Số lượng nhập (' + enteredQuantity + ') vượt quá số lượng tồn kho (' + maxQuantity + ')!');
                    quantityInput.val('');
                    quantityInput.focus();
                }
            });
        });
        // Autocomplete cho khách hàng
        $("#customer_search").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "revenue_manager/add_bill.php",
                    dataType: "json",
                    type: 'GET',
                    data: {
                        ajax: 1,
                        type: 'customer',
                        term: request.term
                    },
                    success: function(data) {
                        if (data.length === 0) {
                            response([{
                                label: "Không tồn tại",
                                value: "",
                                isNotFound: true
                            }])
                        } else {
                            response(data);
                        }
                    }
                });
            },
            minLength: 1,
            select: function(event, ui) {
                if (ui.item.isNotFound) {
                    alert("Số điện thoại này không tồn tại trong database!");
                    $(this).val("");
                    $("#customer_name").val("");
                    return false;
                } else {
                    $("#customer_name").val(ui.item.name);
                }
            }
        });

    });
</script>