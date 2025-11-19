<?php
session_start();
include_once('includes/ket_noi.php');
$page_title = 'Thông Tin Cửa Hàng';
include ('includes/header.php');
?>

<div class="container">
    <h1>THÔNG TIN CỬA HÀNG</h1>

    <div class="info-box">
        <p><i class="fa-solid fa-shop"></i> <b>Tên cửa hàng:</b> 3TL Store</p>
        <p><i class="fa-solid fa-location-dot"></i> <b>Địa chỉ:</b> Số 2 Nguyễn Đình Chiểu, Nha Trang , Khánh Hòa</p>
        <p><i class="fa-solid fa-phone"></i> <b>Số điện thoại:</b> 0377 8752 95</p>
        <p><i class="fa-solid fa-envelope"></i> <b>Email:</b> 3tlstore@gmail.com</p>
        <p><i class="fa-solid fa-clock"></i> <b>Giờ mở cửa:</b> 8:00 – 21:00 (Thứ 2 – Chủ Nhật)</p>
    </div>

    <h2>Mạng xã hội</h2>
    <div class="social">
        <a href="#"><i class="fa-brands fa-facebook"></i> Facebook: fb.com/3tlstore</a>
        <a href="#"><i class="fa-brands fa-tiktok"></i> TikTok: tiktok.com/@3tlstore</a>
        <a href="#"><i class="fa-brands fa-instagram"></i> Instagram: instagram.com/3tlstore</a>
    </div>

    <h2>Bản đồ</h2>
    <div class="map">
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3898.7063016443135!2d109.19980097482907!3d12.268143687986354!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x317067ed3a052f11%3A0xd464ee0a6e53e8b7!2zVHLGsOG7nW5nIMSQ4bqhaSBo4buNYyBOaGEgVHJhbmc!5e0!3m2!1svi!2s!4v1763550138729!5m2!1svi!2s"
            width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
</div>

<?php include ('includes/footer.html'); ?>