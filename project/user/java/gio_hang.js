// File: js/cart.js

function themVaoGio(maSanPham) {
    console.log('Đang thêm sản phẩm:', maSanPham);

    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'them_vao_gio_hang.php?id=' + maSanPham, true);

    xhr.onload = function () {
        console.log('Trạng thái:', xhr.status);
        console.log('Phản hồi:', xhr.responseText);

        if (xhr.status == 200) {
            if (xhr.responseText.includes('Chưa đăng nhập')) {
                alert(' Vui lòng đăng nhập!');
                window.location.href = 'login.php';
            } else {
                alert(' Đã thêm vào giỏ hàng!');
            }
        } else {
            alert('Có lỗi xảy ra!');
        }
    };

    xhr.onerror = function () {
        console.log('Lỗi kết nối');
        alert(' Không thể kết nối!');
    };

    xhr.send();
}

// Hàm xóa khỏi giỏ hàng (nếu cần)
function xoaKhoiGio(maSanPham) {
    if (confirm('Bạn có chắc muốn xóa sản phẩm này?')) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'xoa_gio_hang.php?id=' + maSanPham, true);

        xhr.onload = function () {
            if (xhr.status == 200) {
                alert(' Đã xóa khỏi giỏ hàng!');
                location.reload(); // Tải lại trang
            }
        };

        xhr.send();
    }
}

// Hàm cập nhật số lượng 
function capNhatSoLuong(maSanPham) {
    var soLuong = document.getElementById('soluong_' + maSanPham).value;

    if (soLuong < 1) {
        alert('Số lượng phải lớn hơn 0!');
        return;
    }

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'sua_so_luong.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        if (xhr.status == 200) {
            alert(' Đã cập nhật số lượng!');
            location.reload();
        } else {
            alert(' Có lỗi khi cập nhật!');
        }
    };

    xhr.send('ma_san_pham=' + maSanPham + '&so_luong=' + soLuong);
}

// Hàm đặt hàng
function datHang() {
    var checkboxes = document.querySelectorAll('input[name="chon_san_pham[]"]:checked');


    if (checkboxes.length === 0) {
        alert(' Vui lòng chọn sản phầm mà bạn muốn thanh toán');
        return;
    }

    if (!confirm('Bạn có chắc lÀ muốn đặt ' + checkboxes.length + ' sản phẩm này không?')) {
        return;
    }

    var formData = new FormData(document.getElementById('formThanhToan'));

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'thanh_toan.php', true);

    xhr.onload = function () {
        console.log('Phản hồi:', xhr.responseText);

        if (xhr.status == 200) {
            if (xhr.responseText.includes('Chưa đăng nhập')) {
                alert(' Vui lòng đăng nhập!');
                window.location.href = 'login.php';
            } else if (xhr.responseText.includes('Vui lòng chọn')) {
                alert(' ' + xhr.responseText);
            } else if (xhr.responseText.includes('Đặt hàng thành công')) {
                alert(' ' + xhr.responseText);
                location.reload();
            } else {
                alert(xhr.responseText);
            }
        }
    };

    xhr.onerror = function () {
        alert(' Không thể kết nối!');
    };

    xhr.send(formData);
}