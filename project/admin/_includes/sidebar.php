<ul
  class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion"
  id="accordionSidebar">
  <!-- Sidebar - Brand -->
  <a
    class="sidebar-brand d-flex align-items-center justify-content-center"
    href="index_admin.php">
    <div class="sidebar-brand-icon rotate-n-15">
      <i class="fas fa-laugh-wink"></i>
    </div>
    <div class="sidebar-brand-text mx-3">SB Admin <sup>2</sup></div>
  </a>

  <!-- Divider -->
  <hr class="sidebar-divider my-0" />

  <!-- Nav Item - Dashboard -->
  <li class="nav-item active">
    <a class="nav-link" href="index_admin.php">
      <i class="fas fa-fw fa-tachometer-alt"></i>
      <span>Thống kê</span></a>
  </li>

  <!-- Divider -->
  <hr class="sidebar-divider" />

  <!-- Heading -->
  <div class="sidebar-heading">Quản lý</div>

  <li class="nav-item">
    <a
      class="nav-link collapsed"
      href="#"
      data-toggle="collapse"
      data-target="#collapseProducts"
      aria-expanded="true"
      aria-controls="collapseProducts">
      <i class="fas fa-fw fa-folder"></i>
      <span>Quản lý sản phẩm</span>
    </a>
    <div
      id="collapseProducts"
      class="collapse"
      aria-labelledby="headingProducts"
      data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        <h6 class="collapse-header">Sản phẩm:</h6>
        <a
          class="collapse-item"
          href="index_admin.php?page=list_product">Danh sách sản phẩm</a>
        <a class="collapse-item" href="index_admin.php?page=list_product_category">Danh sách loại sản phẩm</a>
        <a class="collapse-item" href="index_admin.php?page=list_product_brand">Danh sách nhà cung cấp</a>
      </div>
    </div>
  </li>
  <li class="nav-item">
    <a
      class="nav-link collapsed"
      href="#"
      data-toggle="collapse"
      data-target="#collapseCustomer"
      aria-expanded="true"
      aria-controls="collapseCustomer">
      <i class="fas fa-fw fa-folder"></i>
      <span>Quản lý người dùng</span>
    </a>
    <div
      id="collapseCustomer"
      class="collapse"
      aria-labelledby="headingCustomers"
      data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        <h6 class="collapse-header">Khách hàng:</h6>
        <a class="collapse-item" href="index_admin.php?page=list_user_customer">Danh sách khách hàng</a>
        <h6 class="collapse-header">Nhân viên:</h6>
        <a class="collapse-item" href="index_admin.php?page=list_user_employee">Danh sách nhân viên</a>
      </div>
    </div>
  </li>
  <li class="nav-item">
    <a
      class="nav-link collapsed"
      href="#"
      data-toggle="collapse"
      data-target="#collapseRevenue"
      aria-expanded="true"
      aria-controls="collapseRevenue">
      <i class="fas fa-fw fa-folder"></i>
      <span>Quản lý doanh thu</span>
    </a>
    <div
      id="collapseRevenue"
      class="collapse"
      aria-labelledby="headingRevenue"
      data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        <h6 class="collapse-header">Hoá đơn:</h6>
        <a class="collapse-item" href="index_admin.php?page=list_user">Danh sách hoá đơn</a>
      </div>
    </div>
  </li>
  <li class="nav-item">
    <a
      class="nav-link collapsed"
      href="#"
      data-toggle="collapse"
      data-target="#collapseAccount_Authorization"
      aria-expanded="true"
      aria-controls="collapseAccount_Authorization">
      <i class="fas fa-fw fa-folder"></i>
      <span>Phân quyền và tài khoản</span>
    </a>
    <div
      id="collapseAccount_Authorization"
      class="collapse"
      aria-labelledby="headingAccount_Authorization"
      data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        <h6 class="collapse-header">Tài khoản và quyền:</h6>
        <a class="collapse-item" href="index_admin.php?page=list_account">Danh sách tài khoản</a>
        <a class="collapse-item" href="index_admin.php?page=list_account">Danh sách quyền</a>
      </div>
    </div>
  </li>



  <!-- Divider -->
  <hr class="sidebar-divider d-none d-md-block" />

  <!-- Sidebar Toggler (Sidebar) -->
  <div class="text-center d-none d-md-inline">
    <button class="rounded-circle border-0" id="sidebarToggle"></button>
  </div>
</ul>