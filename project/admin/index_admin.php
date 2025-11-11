<?php
include(__DIR__ . '/_includes/config.php');

$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta
    name="viewport"
    content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta name="description" content="" />
  <meta name="author" content="" />

  <title>SB Admin 2 - Dashboard</title>

  <!-- Custom fonts for this template-->
  <link
    href="../_assets/admin/vendor/fontawesome-free/css/all.min.css"
    rel="stylesheet"
    type="text/css" />
  <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet" />

  <!-- Custom styles for this template-->
  <link
    href="../_assets/admin/css/sb-admin-2.min.css"
    rel="stylesheet" />
</head>

<body id="page-top">
  <!-- Page Wrapper -->
  <div id="wrapper">
    <!-- Sidebar -->
    <?php include('_includes/sidebar.php') ?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
      <!-- Main Content -->
      <div id="content">
        <!-- Topbar -->
        <?php include('_includes/topbar.php') ?>
        <!-- End of Topbar -->
        <!-- Begin Page Content -->
        <div class="container-fluid">
          <?php
          // load nội dung chính theo biến $page
          if ($page == 'list_product') {
            include('_product_manager/list_product.php');
          } elseif ($page == 'add_product') {
            include('_product_manager/add_product.php');
          } elseif ($page == 'edit_product') {
            include('./_product_manager/edit_product.php');
          } elseif ($page == 'list_product_category') {
            include('./_product_manager/list_product_category.php');
          } elseif ($page == 'add_product_category') {
            include('_product_manager/add_product_category.php');
          } elseif ($page == 'edit_product_category') {
            include('./_product_manager/edit_product_category.php');
          } elseif ($page == 'list_product_brand') {
            include('./_product_manager/list_product_brand.php');
          } elseif ($page == 'add_product_brand') {
            include('_product_manager/add_product_brand.php');
          } elseif ($page == 'edit_product_brand') {
            include('./_product_manager/edit_product_brand.php');
          } else {
            include('./_includes/404_error.php');
          }
          ?>
        </div>
        <!-- /.container-fluid -->
      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      <?php include('_includes/footer.php') ?>
      <!-- End of Footer -->
    </div>
    <!-- End of Content Wrapper -->
  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Logout Modal-->
  <?php include('_includes/logout_modal.php') ?>

  <!-- Bootstrap core JavaScript-->
  <script src="../_assets/admin/vendor/jquery/jquery.min.js"></script>
  <script src="../_assets/admin/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../_assets/admin/vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="../_assets/admin/js/sb-admin-2.min.js"></script>

  <!-- Page level plugins -->
  <script src="../_assets/admin/vendor/chart.js/Chart.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="../_assets/admin/js/demo/chart-area-demo.js"></script>
  <script src="../_assets/admin/js/demo/chart-pie-demo.js"></script>
</body>

</html>