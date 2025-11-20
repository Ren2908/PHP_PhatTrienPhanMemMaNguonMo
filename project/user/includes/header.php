<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <title>
        <?php echo $page_title; ?>

    </title>
    <link rel="stylesheet" href="includes/style.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="includes/thong_tin.css" type="text/css" media="screen" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
</head>

<body>

    <div id="header">
        <div class="header-container">
            <!-- Logo và tên cửa hàng -->
            <div class="logo-title">
                <a href="index.php"><img src="includes/image_menu/logo.jpg" alt="Store Logo" class="logo" /></a>
                <div class="title">
                    <h1>3TL Store</h1>
                    <h2>Uy Tín Tạo Niềm Tin</h2>
                </div>
            </div>


            <!-- Menu + nút -->
            <div class="menu-buttons">
                <ul class="main-menu">
                    <li>
                        <a href="index.php" class="active">
                            <i class="fas fa-home"></i> Trang Chủ
                        </a>
                    </li>

                    <li>
                        <a href="gio_hang.php">
                            <i class="fas fa-shopping-cart"></i> Giỏ Hàng
                        </a>
                    </li>

                    <li>
                        <a href="don_hang_cua_toi.php">
                            <i class="fas fa-box"></i> Đơn Hàng
                        </a>
                    </li>
                    <li>
                        <a href="thong_tin.php">
                            <i class="fas fa-box"></i> Thông Tin
                        </a>
                    </li>

                    <?php
                    if (isset($_SESSION['ma_quyen']) && ($_SESSION['ma_quyen'] == 'Q1' || $_SESSION['ma_quyen'] == 'Q2')) {
                        echo '
                        <li>
                            <a href="../admin/index_admin.php">
                                <i class="fas fa-tools"></i> Quản Lý
                            </a>
                        </li>';
                    }
                    ?>
                </ul>

                <div class="action-buttons">
                    <?php if (isset($_SESSION['username'])): ?>
                    <!-- Đã đăng nhập -->
                    <span class="welcome-message">
                        <i class="fas fa-user-circle"></i>
                        Xin chào, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>
                    </span>

                    <a href="logout.php" class="btn">
                        <i class="fas fa-sign-out-alt"></i> Đăng Xuất
                    </a>
                    <a href="khach_hang.php" class="btn">
                        <i class="fas fa-user "></i>
                    </a>

                    <?php else: ?>
                    <!-- Chưa đăng nhập -->
                    <a href="login.php" class="btn">
                        <i class="fas fa-sign-in-alt"></i> Đăng Nhập
                    </a>

                    <a href="register.php" class="btn">
                        <i class="fas fa-user-plus"></i> Đăng Ký
                    </a>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>

    <div id="content">
        <!-- Page content -->
    </div>
</body>

</html>