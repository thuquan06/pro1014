<?php
// ✅ views/admin/layout.php - PHIÊN BẢN CẬP NHẬT 2025 (CHI TIẾT TOUR)
$error = $error ?? null;
$msg   = $msg   ?? null;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <title>Trang quản trị - StarVel</title>
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
      cursor: pointer;
    }
    .succWrap {
      padding: 10px; margin: 10px 0; background:#fff;
      border-left:4px solid #5cb85c; box-shadow:0 1px 1px rgba(0,0,0,.1);
      cursor: pointer;
    }
    
    /* ===== CSS CHI TIẾT TOUR (MỚI) ===== */
    .dropdown-header {
      padding: 10px 20px;
      font-weight: bold;
      color: #999;
      text-transform: uppercase;
      font-size: 11px;
    }
    
    .divider {
      height: 1px;
      margin: 9px 0;
      overflow: hidden;
      background-color: rgba(255,255,255,0.1);
    }
    
    /* Breadcrumb đẹp hơn */
    .breadcrumb {
      background: #f5f5f5;
      border-radius: 4px;
      padding: 10px 15px;
      margin-bottom: 20px;
      box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    
    .breadcrumb > li + li:before {
      content: "›";
      padding: 0 8px;
      color: #999;
    }
    
    .breadcrumb > .active {
      color: #5cb85c;
      font-weight: bold;
    }
    
    /* Timeline styles */
    .timeline-container {
      position: relative;
    }
    
    /* Gallery grid */
    .gallery-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
      gap: 15px;
    }
    
    /* Badge colors */
    .badge-primary { background: #5cb85c; }
    .badge-info { background: #5bc0de; }
    .badge-warning { background: #f0ad4e; }
    
    /* Loading overlay */
    #loadingOverlay {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0,0,0,0.6);
      z-index: 9999;
    }
    
    #loadingOverlay .spinner {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      text-align: center;
      color: white;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
      .timeline-container { padding-left: 30px; }
    }
    
  </style>
</head>
<body>

<div class="page-container">
  <!-- ===== SIDEBAR ===== -->
  <div class="sidebar-menu">
    <header class="logo1">
      <a href="#" class="sidebar-icon"><span class="fa fa-bars"></span></a>
    </header>
    <div style="border-top:1px ridge rgba(255,255,255,0.15)"></div>

    <div class="menu">
      <ul id="menu">
        <li><a href="<?= BASE_URL ?>?act=admin"><i class="fa fa-tachometer"></i><span> Quản lý</span></a></li>

        <!-- ===== MENU TOUR (CẬP NHẬT) ===== -->
        <li id="menu-academico">
          <a href="#"><i class="glyphicon glyphicon-road"></i><span> Tour</span>
            <span class="fa fa-angle-right" style="float:right"></span></a>
          <ul id="menu-academico-sub">
            <li><a href="<?= BASE_URL ?>?act=admin-tour-create">Tạo mới</a></li>
            <li><a href="<?= BASE_URL ?>?act=admin-tours">Quản lý</a></li>
            
            <!-- ✨ MENU CHI TIẾT TOUR (MỚI) -->
            <li class="divider"></li>
            <li class="dropdown-header">Chi tiết Tour</li>
            <li><a href="<?= BASE_URL ?>?act=tour-lichtrinh&id_goi=71">📅 Lịch trình</a></li>
            <li><a href="<?= BASE_URL ?>?act=tour-gallery&id_goi=71">📸 Gallery</a></li>
            <li><a href="<?= BASE_URL ?>?act=tour-chinhsach&id_goi=71">📋 Chính sách</a></li>
            <!-- Versions (cần chọn tour trước) -->
            <li><a href="<?= BASE_URL ?>?act=tour-versions"><i class="fa fa-code-fork"></i><span>Versions</span></a></li>
            <li><a href="<?= BASE_URL ?>?act=tour-phanloai&id_goi=71">🏷️ Phân loại</a></li>
            <li><a href="<?= BASE_URL ?>?act=tour-publish-dashboard"><i class="fa fa-rocket"></i><span>Publish Dashboard</span></a></li>
          </ul>
        </li>

        <!-- ===== MENU BLOG ===== -->
      <li id="menu-blog">
        <a href="#">
          <i class="glyphicon glyphicon-file"></i>
          <span> Blog</span>
          <span class="fa fa-angle-right" style="float:right"></span>
        </a>
        <ul id="menu-blog-sub">
          <li><a href="<?= BASE_URL ?>?act=blog-list">Danh sách bài viết</a></li>
          <li><a href="<?= BASE_URL ?>?act=blog-create">Tạo bài viết mới</a></li>
        </ul>
      </li>
        <!-- ===== KẾT THÚC MENU BLOG ===== -->   
<li id="menu-province">
    <a href="#">
        <i class="glyphicon glyphicon-list"></i>
        <span> Tỉnh</span>
        <span class="fa fa-angle-right" style="float:right"></span>
    </a>
    <ul>
        <li><a href="<?= BASE_URL ?>?act=province-list">Danh sách</a></li>
        <li><a href="<?= BASE_URL ?>?act=province-create">Thêm mới</a></li>
    </ul>
</li>
        
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
        <p>© 2025 StarVel. All Rights Reserved | Powered by StarVel Team</p>
      </div>

    </div><!-- /mother-grid-inner -->
  </div><!-- /left-content -->
</div><!-- /page-container -->

<!-- ===== LOADING OVERLAY ===== -->
<div id="loadingOverlay">
  <div class="spinner">
    <i class="fa fa-spinner fa-spin fa-4x"></i>
    <p style="margin-top: 20px; font-size: 18px;">Đang xử lý...</p>
  </div>
</div>

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
  // ===== SIDEBAR TOGGLE =====
  (function($){
    let toggle = false;
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

  // ===== HEADER FIXED =====
  $(function(){
    var navOff = $(".header-main").offset().top;
    $(window).on("scroll", function(){
      var s = $(window).scrollTop();
      if (s >= navOff) $(".header-main").addClass("fixed");
      else $(".header-main").removeClass("fixed");
    });
  });
  
  // ===== AUTO HIDE NOTIFICATIONS =====
  $(function() {
    setTimeout(function() {
      $('.succWrap, .errorWrap').fadeOut('slow');
    }, 5000);
    
    $('.succWrap, .errorWrap').on('click', function() {
      $(this).fadeOut('fast');
    });
  });
  
  // ===== ACTIVE MENU =====
  $(function() {
    var currentUrl = window.location.href;
    $('#menu a').each(function() {
      var href = $(this).attr('href');
      if (href && currentUrl.indexOf(href) > -1 && href.length > 10) {
        $(this).parent().addClass('active');
        $(this).closest('ul').show();
        $(this).closest('li[id^="menu-"]').addClass('active');
      }
    });
  });
  
  // ===== LOADING ON FORM SUBMIT =====
  $(function() {
    $('form').on('submit', function(e) {
      // Không hiện loading nếu có lỗi validation
      if (this.checkValidity && !this.checkValidity()) {
        return true;
      }
      $('#loadingOverlay').fadeIn();
    });
  });
  
  // ===== CONFIRM DELETE =====
  window.confirmDelete = function(message) {
    return confirm(message || 'Bạn có chắc muốn xóa?');
  };
  
  // ===== PREVIEW IMAGES =====
  window.previewImages = function(input, container) {
    if (input.files) {
      $(container).html('');
      Array.from(input.files).forEach(function(file) {
        if (file.type.startsWith('image/')) {
          var reader = new FileReader();
          reader.onload = function(e) {
            $(container).append(
              '<div class="col-md-2" style="margin-bottom:10px;">' +
              '<img src="' + e.target.result + '" class="img-thumbnail" style="width:100%;height:150px;object-fit:cover;">' +
              '</div>'
            );
          };
          reader.readAsDataURL(file);
        }
      });
    }
  };
</script>

<!-- ===== CUSTOM SCRIPTS CHO TỪNG TRANG (TÙY CHỌN) ===== -->
<?php if (isset($extra_scripts)): ?>
  <?= $extra_scripts ?>
<?php endif; ?>

</body>
</html>