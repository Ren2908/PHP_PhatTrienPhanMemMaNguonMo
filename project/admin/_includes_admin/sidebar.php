     <a class="dropdown-item" href="../user/index.php">
         Về trang chủ shop
     </a>
     <?php if (isset($_SESSION['ma_quyen']) && $_SESSION['ma_quyen'] == 'Q1'): ?>
         <li><a href="index_admin.php?page=dashboard">Thống kê</a></li>

         <!-- Quản lý sản phẩm -->
         <li><strong>Quản lý sản phẩm</strong></li>
         <li><a href="index_admin.php?page=list_product">Danh sách sản phẩm</a></li>
         <li><a href="index_admin.php?page=list_product_category">Danh sách loại sản phẩm</a></li>
         <li><a href="index_admin.php?page=list_product_brand">Danh sách nhà cung cấp</a></li>

         <!-- Quản lý người dùng -->
         <li><strong>Quản lý người dùng</strong></li>
         <li><a href="index_admin.php?page=list_user_customer">Danh sách khách hàng</a></li>
         <li><a href="index_admin.php?page=list_user_employee">Danh sách nhân viên</a></li>

         <!-- Quản lý doanh thu -->
         <li><strong>Quản lý doanh thu</strong></li>
         <li><a href="index_admin.php?page=list_bill">Danh sách hoá đơn</a></li>

         <!-- Tài khoản & quyền -->
         <li><strong>Tài khoản & quyền</strong></li>
         <li><a href="index_admin.php?page=list_authorization">Danh sách quyền</a></li>
         <li><a href="index_admin.php?page=list_account">Danh sách tài khoản</a></li>
     <?php elseif (isset($_SESSION['ma_quyen']) && $_SESSION['ma_quyen'] == 'Q2'): ?>
         <!-- Tài khoản & quyền -->
         <li><strong>Tài khoản & quyền</strong></li>
         <li><a href="index_admin.php?page=list_account">Danh sách tài khoản</a></li>
         <!-- Quản lý doanh thu -->
         <li><strong>Quản lý doanh thu</strong></li>
         <li><a href="index_admin.php?page=list_bill">Danh sách hoá đơn</a></li>
     <?php endif; ?>


     <li>
         <a href="../user/logout.php"
             class="text-danger"
             onclick="return confirm('Bạn có chắc muốn đăng xuất không?');">
             Đăng xuất
         </a>
     </li>