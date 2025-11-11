<?php
// views/admin/layout.php (ĐÃ SỬA)

// KHÔNG session_start()/redirect ở VIEW để tránh "headers already sent".
// Guard login làm ở Controller.
// Chuẩn hóa biến để tránh undefined.
$error = $error ?? null;
$msg   = $msg   ?? null;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <title>Trang quản trị</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSS -->
  <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
  <link href="assets/css/style.css" rel="stylesheet" type="text/css"/>
  <link href="assets/css/morris.css" rel="stylesheet" type="text/css"/>
  <link href="assets/css/font-awesome.css" rel="stylesheet" type="text/css"/>
  <link href="assets/css/basictable.css" rel="stylesheet" type="text/css"/>
  <link href="assets/css/jquery-ui.css" rel="stylesheet" type="text/css"/>
  <link href="assets/css/icon-font.min.css" rel="stylesheet" type="text/css"/>

  <!-- Fonts (tùy chọn) -->
  <link href='//fonts.googleapis.com/css?family=Roboto:700,500,300,100italic,100,400' rel='stylesheet' type='text/css'/>
  <link href='//fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'/>

  <!-- JS (đặt 1 số lib cần sớm) -->
  <script src="assets/js/jquery-2.1.4.min.js"></script>
  <script src="assets/js/jquery-ui.js"></script>
  <script src="assets/js/raphael-min.js"></script>
  <script src="assets/js/Chart.js"></script>
  <script src="assets/js/morris.js"></script>

  <style>
    /* Giảm khoảng trắng đầu content & tránh margin collapse */
    .left-content .outter-wp { padding-top: 16px; }
    .left-content .outter-wp > *:first-child { margin-top: 0 !important; }
    .spacer, .mt-100, .mt-120, .mt-150, .mt-200 { margin-top: 0 !important; height: auto !important; }

    /* Hiển thị khối thông báo đồng bộ với template cũ */
    .errorWrap {
      padding: 10px; margin: 0 0 20px; background:#fff; border-left:4px solid #dd3d36;
      box-shadow:0 1px 1px 0 rgba(0,0,0,.1);
    }
    .succWrap {
      padding: 10px; margin: 0 0 20px; background:#fff; border-left:4px solid #5cb85c;
      box-shadow:0 1px 1px 0 rgba(0,0,0,.1);
    }
    .cke_contents { height: 250px !important; }
  </style>
</head>
<body>

<div class="page-container"><!-- MỞ: page-container -->
  <!-- SIDEBAR -->
  <div class="sidebar-menu">
    <header class="logo1">
      <a href="#" class="sidebar-icon"><span class="fa fa-bars"></span></a>
    </header>
    <div style="border-top:1px ridge rgba(255,255,255,0.15)"></div>

    <div class="menu">
      <ul id="menu">
        <li><a href="<?= BASE_URL ?>?act=admin"><i class="fa fa-tachometer"></i> <span>Quản lý</span></a></li>

        <li id="menu-academico">
          <a href="#"><i class="glyphicon glyphicon-road" aria-hidden="true"></i><span>Tour</span>
            <span class="fa fa-angle-right" style="float:right"></span></a>
          <ul id="menu-academico-sub">
            <li><a href="<?= BASE_URL ?>?act=admin-tour-create">Tạo</a></li>
            <li><a href="<?= BASE_URL ?>?act=admin-tours">Quản lý</a></li>
          </ul>
        </li>

        <li id="menu-academico">
          <a href="#"><i class="fa fa-hotel" aria-hidden="true"></i><span>Khách sạn</span>
            <span class="fa fa-angle-right" style="float:right"></span></a>
          <ul id="menu-academico-sub">
            <li><a href="#">Tạo</a></li>
            <li><a href="#">Quản lý</a></li>
          </ul>
        </li>

        <li id="menu-academico">
          <a href="#"><i class="glyphicon glyphicon-file" aria-hidden="true"></i><span>Blog</span>
            <span class="fa fa-angle-right" style="float:right"></span></a>
          <ul id="menu-academico-sub">
            <li><a href="#">Tạo</a></li>
            <li><a href="#">Quản lý</a></li>
          </ul>
        </li>

        <li id="menu-academico">
          <a href="#"><i class="glyphicon glyphicon-list" aria-hidden="true"></i><span>Tỉnh</span>
            <span class="fa fa-angle-right" style="float:right"></span></a>
          <ul id="menu-academico-sub">
            <li><a href="#">Tạo</a></li>
            <li><a href="#">Quản lý</a></li>
          </ul>
        </li>

        <li><a href="#"><i class="fa fa-file-invoice-dollar"></i> <span>Hóa đơn</span></a></li>
        <li><a href="#"><i class="fa fa-users"></i> <span>Người dùng</span></a></li>
        <li><a href="#"><i class="glyphicon glyphicon-exclamation-sign"></i> <span>Trợ giúp</span></a></li>
        <li><a href="#"><i class="glyphicon glyphicon-envelope"></i> <span>Góp ý</span></a></li>
        <li><a href="#"><i class="glyphicon glyphicon-pencil"></i> <span>Văn bản</span></a></li>

        <li id="menu-academico">
          <a href="#"><i class="glyphicon glyphicon-user" aria-hidden="true"></i><span>Tài khoản</span>
            <span class="fa fa-angle-right" style="float:right"></span></a>
          <ul id="menu-academico-sub">
            <li><a href="#"><i class="glyphicon glyphicon-cog"></i>&ensp;Đổi mật khẩu</a></li>
            <li><a href="<?= BASE_URL ?>?act=logout"><i class="glyphicon glyphicon-off"></i>&ensp;Đăng xuất</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
  <!-- /SIDEBAR -->

  <!-- LEFT CONTENT (NẰM TRONG page-container) -->
  <div class="left-content">
    <div class="mother-grid-inner">

      <div class="header-main">
        <div class="logo-w3-agile">
          <h1><a href="<?= BASE_URL ?>?act=admin">Hệ thống quản lý đặt tour</a></h1>
        </div>
        <div class="profile_details w3l">
          <ul>
            <li class="dropdown profile_details_drop">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                <div class="profile_img">
                  <span class="prfil-img"><img src="assets/images/User-icon.png" alt=""></span>
                  <div class="user-name">
                    <p>Tài khoản</p><span>Quản trị viên</span>
                  </div>
                  <i class="fa fa-angle-down"></i><i class="fa fa-angle-up"></i>
                  <div class="clearfix"></div>
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
        // Nạp view con
        if (isset($viewContent) && file_exists($viewContent)) {
          // KHÔNG extract lẫn lộn; chỉ để sẵn các biến phổ biến.
          // View con sẽ dùng trực tiếp $stats, $tours, $tour, $provinces nếu có.
          include $viewContent;
        } else {
          echo "<div class='errorWrap'>Không tìm thấy view: ".htmlspecialchars($viewContent ?? '(chưa đặt)')."</div>";
        }
        ?>
      </div>

      <div class="copyrights">
        <p>© 2025 StarVel. All Rights Reserved</p>
      </div>

    </div><!-- /mother-grid-inner -->
  </div><!-- /left-content -->
</div><!-- /page-container -->

<!-- JS cuối trang -->
<script src="assets/js/jquery.nicescroll.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/scripts.js"></script>
<script src="assets/js/jquery.basictable.min.js"></script>
<script>
  // Toggle sidebar
  (function(){
    var toggle = true;
    $(".sidebar-icon").on("click", function(){
      if (toggle) {
        $(".page-container").addClass("sidebar-collapsed").removeClass("sidebar-collapsed-back");
        $("#menu span").css({position:"absolute"});
      } else {
        $(".page-container").removeClass("sidebar-collapsed").addClass("sidebar-collapsed-back");
        setTimeout(function(){ $("#menu span").css({position:"relative"}); }, 400);
      }
      toggle = !toggle;
    });
  })();

  // Header fixed on scroll
  $(function(){
    var navOff = $(".header-main").offset().top;
    $(window).on("scroll", function(){
      var s = $(window).scrollTop();
      if (s >= navOff) $(".header-main").addClass("fixed");
      else            $(".header-main").removeClass("fixed");
    });
  });
</script>
</body>
</html>
