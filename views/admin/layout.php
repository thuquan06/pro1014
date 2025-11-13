<?php
// ✅ views/admin/layout.php - phiên bản FIX 2025
$error = $error ?? null;
$msg   = $msg   ?? null;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <title>Trang quản trị</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- ===== CSS ===== -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/morris.js@0.5.1/morris.css">
  <link href="assets/css/style.css" rel="stylesheet">
  <link href="assets/css/basictable.css" rel="stylesheet">
  <link href="assets/css/jquery-ui.css" rel="stylesheet">
  <link href="assets/css/icon-font.min.css" rel="stylesheet">
  <link rel="icon" href="assets/images/favicon.ico" type="image/x-icon">

  <!-- Google Fonts -->
  <link href='//fonts.googleapis.com/css?family=Roboto:700,500,300,100italic,100,400' rel='stylesheet'>
  <link href='//fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet'>

  <style>
    .left-content .outter-wp { padding-top: 16px; }
    .errorWrap {
      padding: 10px; margin: 10px 0; background:#fff;
      border-left:4px solid #dd3d36; box-shadow:0 1px 1px rgba(0,0,0,.1);
    }
    .succWrap {
      padding: 10px; margin: 10px 0; background:#fff;
      border-left:4px solid #5cb85c; box-shadow:0 1px 1px rgba(0,0,0,.1);
    }
  </style>
</head>
<body>

<div class="page-container"><!-- KHÔNG có sidebar-collapsed -->
  <!-- ===== SIDEBAR ===== -->
  <div class="sidebar-menu">
    <header class="logo1">
      <a href="#" class="sidebar-icon"><span class="fa fa-bars"></span></a>
    </header>
    <div style="border-top:1px ridge rgba(255,255,255,0.15)"></div>

    <div class="menu">
      <ul id="menu">
        <li><a href="<?= BASE_URL ?>?act=admin"><i class="fa fa-tachometer"></i><span> Quản lý</span></a></li>

        <li id="menu-academico">
          <a href="#"><i class="glyphicon glyphicon-road"></i><span> Tour</span>
            <span class="fa fa-angle-right" style="float:right"></span></a>
          <ul id="menu-academico-sub">
            <li><a href="<?= BASE_URL ?>?act=admin-tour-create">Tạo</a></li>
            <li><a href="<?= BASE_URL ?>?act=admin-tours">Quản lý</a></li>
          </ul>
        </li>

        <li><a href="#"><i class="glyphicon glyphicon-file"></i><span> Blog</span></a></li>
        <li><a href="#"><i class="glyphicon glyphicon-list"></i><span> Tỉnh</span></a></li>
        <li><a href="#"><i class="fa fa-file-invoice-dollar"></i><span> Hóa đơn</span></a></li>
        <li><a href="#"><i class="fa fa-users"></i><span> Người dùng</span></a></li>
        <li><a href="#"><i class="glyphicon glyphicon-envelope"></i><span> Góp ý</span></a></li>
        <li><a href="#"><i class="glyphicon glyphicon-user"></i><span> Tài khoản</span></a></li>
      </ul>
    </div>
  </div>
  <!-- /SIDEBAR -->

  <!-- ===== LEFT CONTENT ===== -->
  <div class="left-content">
    <div class="mother-grid-inner">
      <div class="header-main">
        <div class="logo-w3-agile">
          <h1><a href="<?= BASE_URL ?>?act=admin">Hệ thống quản lý đặt tour</a></h1>
        </div>
        <div class="profile_details w3l">
          <ul>
            <li class="dropdown profile_details_drop">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <div class="profile_img">
                  <span class="prfil-img"><img src="assets/images/User-icon.png" alt=""></span>
                  <div class="user-name">
                    <p>Tài khoản</p><span>Quản trị viên</span>
                  </div>
                  <i class="fa fa-angle-down"></i>
                </div>
              </a>
              <ul class="dropdown-menu drp-mnu" style="left:12%;">
                <li><a href="#"><i class="glyphicon glyphicon-cog"></i> Đổi mật khẩu</a></li>
                <li><a href="<?= BASE_URL ?>?act=logout"><i class="glyphicon glyphicon-off"></i> Đăng xuất</a></li>
              </ul>
            </li>
          </ul>
        </div>
        <div class="clearfix"></div>
      </div>

      <div class="outter-wp">
        <?php
        echo isset($content) && $content !== ''
          ? $content
          : "<div class='errorWrap'>Không có nội dung view.</div>";
        ?>
      </div>

      <div class="copyrights">
        <p>© 2025 StarVel. All Rights Reserved</p>
      </div>

    </div><!-- /mother-grid-inner -->
  </div><!-- /left-content -->
</div><!-- /page-container -->

<!-- ===== JS (THỨ TỰ CHUẨN) ===== -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/raphael@2.3.0/raphael.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/morris.js@0.5.1/morris.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery.nicescroll@3.7.6/jquery.nicescroll.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery.basictable@1.0.0/dist/jquery.basictable.min.js"></script>

<!-- Script nội bộ -->
<script src="assets/js/scripts.js"></script>

<script>
  // Sidebar toggle fix
  (function($){
    let toggle = false; // Mặc định sidebar mở
    $(".sidebar-icon").on("click", function(){
      if (!toggle) {
        $(".page-container").addClass("sidebar-collapsed").removeClass("sidebar-collapsed-back");
        $("#menu span").css({position:"absolute"});
      } else {
        $(".page-container").removeClass("sidebar-collapsed").addClass("sidebar-collapsed-back");
        setTimeout(function(){ $("#menu span").css({position:"relative"}); }, 400);
      }
      toggle = !toggle;
    });
  })(jQuery);

  // Header fixed on scroll
  $(function(){
    var navOff = $(".header-main").offset().top;
    $(window).on("scroll", function(){
      var s = $(window).scrollTop();
      if (s >= navOff) $(".header-main").addClass("fixed");
      else $(".header-main").removeClass("fixed");
    });
  });
</script>
</body>
</html>
