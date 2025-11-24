<?php
/**
 * Admin Layout - Giao diện quản trị HIỆN ĐẠI
 * Version: 2.0
 * Updated: 2025-11-24
 */
$error = $error ?? null;
$msg   = $msg   ?? null;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Quản trị - StarVel Travel</title>
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    
    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
      background: #f8f9fa;
      color: #333;
      line-height: 1.6;
    }
    
    /* ===== SIDEBAR ===== */
    .sidebar {
      position: fixed;
      left: 0;
      top: 0;
      width: 260px;
      height: 100vh;
      background: linear-gradient(180deg, #1e3c72 0%, #2a5298 100%);
      box-shadow: 2px 0 10px rgba(0,0,0,0.1);
      z-index: 1000;
      overflow-y: auto;
      transition: all 0.3s;
    }
    
    .sidebar::-webkit-scrollbar {
      width: 6px;
    }
    
    .sidebar::-webkit-scrollbar-track {
      background: rgba(255,255,255,0.1);
    }
    
    .sidebar::-webkit-scrollbar-thumb {
      background: rgba(255,255,255,0.3);
      border-radius: 3px;
    }
    
    .sidebar-header {
      padding: 25px 20px;
      background: rgba(0,0,0,0.2);
      border-bottom: 1px solid rgba(255,255,255,0.1);
    }
    
    .sidebar-logo {
      display: flex;
      align-items: center;
      gap: 12px;
      color: white;
      text-decoration: none;
    }
    
    .sidebar-logo i {
      font-size: 32px;
      color: #ffd700;
    }
    
    .sidebar-logo-text h2 {
      font-size: 20px;
      font-weight: 700;
      margin: 0;
      color: white;
    }
    
    .sidebar-logo-text p {
      font-size: 11px;
      color: rgba(255,255,255,0.7);
      margin: 0;
      text-transform: uppercase;
      letter-spacing: 1px;
    }
    
    .sidebar-user {
      padding: 20px;
      background: rgba(0,0,0,0.15);
      border-bottom: 1px solid rgba(255,255,255,0.1);
    }
    
    .user-info {
      display: flex;
      align-items: center;
      gap: 12px;
    }
    
    .user-avatar {
      width: 45px;
      height: 45px;
      border-radius: 50%;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 20px;
      font-weight: 600;
      border: 2px solid rgba(255,255,255,0.2);
    }
    
    .user-details h4 {
      font-size: 14px;
      color: white;
      margin: 0;
      font-weight: 600;
    }
    
    .user-details p {
      font-size: 12px;
      color: rgba(255,255,255,0.6);
      margin: 0;
    }
    
    .sidebar-nav {
      padding: 15px 0;
    }
    
    .nav-section {
      margin-bottom: 10px;
    }
    
    .nav-section-title {
      padding: 15px 20px 8px;
      font-size: 11px;
      font-weight: 600;
      color: rgba(255,255,255,0.5);
      text-transform: uppercase;
      letter-spacing: 1px;
    }
    
    .nav-item {
      margin: 2px 10px;
    }
    
    .nav-link {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 12px 15px;
      color: rgba(255,255,255,0.9);
      text-decoration: none;
      border-radius: 8px;
      transition: all 0.3s;
      font-size: 14px;
      font-weight: 500;
    }
    
    .nav-link:hover {
      background: rgba(255,255,255,0.15);
      color: white;
      transform: translateX(3px);
    }
    
    .nav-link.active {
      background: rgba(255,255,255,0.2);
      color: white;
      box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    }
    
    .nav-link i {
      width: 20px;
      text-align: center;
      font-size: 16px;
    }
    
    .nav-link .badge {
      margin-left: auto;
      background: #ff4757;
      color: white;
      padding: 3px 8px;
      border-radius: 12px;
      font-size: 11px;
      font-weight: 600;
    }
    
    /* ===== MAIN CONTENT ===== */
    .main-content {
      margin-left: 260px;
      min-height: 100vh;
      transition: all 0.3s;
    }
    
    .top-bar {
      background: white;
      padding: 0 30px;
      height: 70px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      box-shadow: 0 2px 8px rgba(0,0,0,0.05);
      position: sticky;
      top: 0;
      z-index: 999;
    }
    
    .top-bar-left h1 {
      font-size: 24px;
      font-weight: 700;
      color: #2c3e50;
      margin: 0;
    }
    
    .top-bar-left p {
      font-size: 13px;
      color: #7f8c8d;
      margin: 0;
    }
    
    .top-bar-right {
      display: flex;
      align-items: center;
      gap: 20px;
    }
    
    .top-bar-icon {
      position: relative;
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: #f8f9fa;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: all 0.3s;
      color: #7f8c8d;
    }
    
    .top-bar-icon:hover {
      background: #e9ecef;
      color: #2c3e50;
      transform: scale(1.05);
    }
    
    .top-bar-icon .badge {
      position: absolute;
      top: -5px;
      right: -5px;
      background: #ff4757;
      color: white;
      border-radius: 10px;
      padding: 2px 6px;
      font-size: 10px;
      font-weight: 600;
    }
    
    .user-menu {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 8px 15px;
      border-radius: 25px;
      background: #f8f9fa;
      cursor: pointer;
      transition: all 0.3s;
    }
    
    .user-menu:hover {
      background: #e9ecef;
    }
    
    .user-menu img {
      width: 35px;
      height: 35px;
      border-radius: 50%;
      border: 2px solid #ddd;
    }
    
    .user-menu-name {
      font-size: 14px;
      font-weight: 600;
      color: #2c3e50;
    }
    
    .content-wrapper {
      padding: 30px;
    }
    
    /* ===== BREADCRUMB ===== */
    .breadcrumb-custom {
      background: white;
      padding: 15px 20px;
      border-radius: 10px;
      margin-bottom: 25px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.05);
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 14px;
    }
    
    .breadcrumb-custom a {
      color: #3498db;
      text-decoration: none;
      transition: color 0.3s;
    }
    
    .breadcrumb-custom a:hover {
      color: #2980b9;
    }
    
    .breadcrumb-custom i {
      color: #bdc3c7;
      font-size: 12px;
    }
    
    .breadcrumb-custom .active {
      color: #7f8c8d;
      font-weight: 500;
    }
    
    /* ===== ALERTS ===== */
    .alert {
      padding: 15px 20px;
      border-radius: 10px;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 12px;
      animation: slideInDown 0.3s;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .alert i {
      font-size: 20px;
    }
    
    .alert-success {
      background: #d4edda;
      border-left: 4px solid #28a745;
      color: #155724;
    }
    
    .alert-success i {
      color: #28a745;
    }
    
    .alert-error {
      background: #f8d7da;
      border-left: 4px solid #dc3545;
      color: #721c24;
    }
    
    .alert-error i {
      color: #dc3545;
    }
    
    .alert-info {
      background: #d1ecf1;
      border-left: 4px solid #17a2b8;
      color: #0c5460;
    }
    
    .alert-warning {
      background: #fff3cd;
      border-left: 4px solid #ffc107;
      color: #856404;
    }
    
    /* ===== CARDS ===== */
    .card {
      background: white;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.05);
      margin-bottom: 25px;
      overflow: hidden;
      transition: all 0.3s;
    }
    
    .card:hover {
      box-shadow: 0 4px 16px rgba(0,0,0,0.1);
      transform: translateY(-2px);
    }
    
    .card-header {
      padding: 20px 25px;
      border-bottom: 1px solid #e9ecef;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    
    .card-header h3 {
      font-size: 18px;
      font-weight: 600;
      color: #2c3e50;
      margin: 0;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    
    .card-header h3 i {
      color: #3498db;
    }
    
    .card-body {
      padding: 25px;
    }
    
    /* ===== BUTTONS ===== */
    .btn {
      padding: 10px 20px;
      border: none;
      border-radius: 8px;
      font-size: 14px;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.3s;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      text-decoration: none;
    }
    
    .btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    .btn-primary {
      background: #3498db;
      color: white;
    }
    
    .btn-primary:hover {
      background: #2980b9;
    }
    
    .btn-success {
      background: #28a745;
      color: white;
    }
    
    .btn-danger {
      background: #dc3545;
      color: white;
    }
    
    .btn-warning {
      background: #ffc107;
      color: #333;
    }
    
    .btn-sm {
      padding: 6px 12px;
      font-size: 12px;
    }
    
    /* ===== TABLE ===== */
    .table-responsive {
      overflow-x: auto;
    }
    
    table {
      width: 100%;
      border-collapse: collapse;
    }
    
    table thead {
      background: #f8f9fa;
    }
    
    table th {
      padding: 15px;
      text-align: left;
      font-weight: 600;
      color: #2c3e50;
      font-size: 13px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      border-bottom: 2px solid #dee2e6;
    }
    
    table td {
      padding: 15px;
      border-bottom: 1px solid #e9ecef;
      color: #555;
    }
    
    table tr:hover {
      background: #f8f9fa;
    }
    
    /* ===== ANIMATIONS ===== */
    @keyframes slideInDown {
      from {
        opacity: 0;
        transform: translateY(-20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }
    
    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
      .sidebar {
        transform: translateX(-100%);
      }
      
      .sidebar.active {
        transform: translateX(0);
      }
      
      .main-content {
        margin-left: 0;
      }
      
      .top-bar {
        padding: 0 15px;
      }
      
      .content-wrapper {
        padding: 15px;
      }
    }
    
    /* ===== FORM ELEMENTS ===== */
    .form-group {
      margin-bottom: 20px;
    }
    
    .form-label {
      display: block;
      margin-bottom: 8px;
      font-weight: 500;
      color: #2c3e50;
      font-size: 14px;
    }
    
    .form-control {
      width: 100%;
      padding: 12px 15px;
      border: 1px solid #ddd;
      border-radius: 8px;
      font-size: 14px;
      transition: all 0.3s;
    }
    
    .form-control:focus {
      outline: none;
      border-color: #3498db;
      box-shadow: 0 0 0 3px rgba(52,152,219,0.1);
    }
    
    /* ===== STATS CARDS ===== */
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
      margin-bottom: 30px;
    }
    
    .stat-card {
      background: white;
      border-radius: 12px;
      padding: 25px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.05);
      display: flex;
      align-items: center;
      gap: 20px;
      transition: all 0.3s;
    }
    
    .stat-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 24px rgba(0,0,0,0.15);
    }
    
    .stat-icon {
      width: 60px;
      height: 60px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 28px;
      color: white;
    }
    
    .stat-icon.blue {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .stat-icon.green {
      background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }
    
    .stat-icon.orange {
      background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }
    
    .stat-icon.red {
      background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    }
    
    .stat-details h4 {
      font-size: 28px;
      font-weight: 700;
      color: #2c3e50;
      margin: 0;
    }
    
    .stat-details p {
      font-size: 14px;
      color: #7f8c8d;
      margin: 0;
    }
  </style>
</head>
<body>
  
  <!-- ===== SIDEBAR ===== -->
  <aside class="sidebar">
    <div class="sidebar-header">
      <a href="?act=admin" class="sidebar-logo">
        <i class="fas fa-plane-departure"></i>
        <div class="sidebar-logo-text">
          <h2>StarVel</h2>
          <p>Travel Admin</p>
        </div>
      </a>
    </div>
    
    <div class="sidebar-user">
      <div class="user-info">
        <div class="user-avatar">
          <?php 
          $username = $_SESSION['alogin'] ?? 'A';
          echo strtoupper(substr($username, 0, 1)); 
          ?>
        </div>
        <div class="user-details">
          <h4><?= htmlspecialchars($_SESSION['alogin'] ?? 'Admin') ?></h4>
          <p>Quản trị viên</p>
        </div>
      </div>
    </div>
    
    <nav class="sidebar-nav">
      <div class="nav-section">
        <div class="nav-section-title">Tổng quan</div>
        <div class="nav-item">
          <a href="?act=admin" class="nav-link">
            <i class="fas fa-home"></i>
            <span>Dashboard</span>
          </a>
        </div>
      </div>
      
      <div class="nav-section">
        <div class="nav-section-title">Quản lý Tour</div>
        <div class="nav-item">
          <a href="?act=admin-tours" class="nav-link">
            <i class="fas fa-map-marked-alt"></i>
            <span>Danh sách Tour</span>
          </a>
        </div>
        <div class="nav-item">
          <a href="?act=admin-tour-create" class="nav-link">
            <i class="fas fa-plus-circle"></i>
            <span>Thêm Tour mới</span>
          </a>
        </div>
        <div class="nav-item">
          <a href="?act=tour-publish-dashboard" class="nav-link">
            <i class="fas fa-rocket"></i>
            <span>Xuất bản Tour</span>
          </a>
        </div>
      </div>
      
      <div class="nav-section">
        <div class="nav-section-title">Nội dung</div>
        <div class="nav-item">
          <a href="?act=blog-list" class="nav-link">
            <i class="fas fa-newspaper"></i>
            <span>Bài viết</span>
          </a>
        </div>
        <div class="nav-item">
          <a href="?act=province-list" class="nav-link">
            <i class="fas fa-map-marker-alt"></i>
            <span>Tỉnh thành</span>
          </a>
        </div>
      </div>
      
      <div class="nav-section">
        <div class="nav-section-title">Đơn hàng</div>
        <div class="nav-item">
          <a href="?act=hoadon-list" class="nav-link">
            <i class="fas fa-file-invoice"></i>
            <span>Hóa đơn</span>
            <span class="badge">NEW</span>
          </a>
        </div>
      </div>
      
      <div class="nav-section">
        <div class="nav-section-title">Hệ thống</div>
        <div class="nav-item">
          <a href="?act=logout" class="nav-link" onclick="return confirm('Bạn có chắc muốn đăng xuất?')">
            <i class="fas fa-sign-out-alt"></i>
            <span>Đăng xuất</span>
          </a>
        </div>
      </div>
    </nav>
  </aside>
  
  <!-- ===== MAIN CONTENT ===== -->
  <main class="main-content">
    <div class="top-bar">
      <div class="top-bar-left">
        <h1>Chào mừng trở lại!</h1>
        <p><?= date('l, d/m/Y') ?></p>
      </div>
      <div class="top-bar-right">
        <div class="top-bar-icon">
          <i class="fas fa-bell"></i>
          <span class="badge">3</span>
        </div>
        <div class="top-bar-icon">
          <i class="fas fa-envelope"></i>
        </div>
        <div class="user-menu" onclick="window.location='?act=logout'">
          <div class="user-avatar" style="width:35px; height:35px; font-size:14px;">
            <?= strtoupper(substr($_SESSION['alogin'] ?? 'A', 0, 1)) ?>
          </div>
          <span class="user-menu-name"><?= htmlspecialchars($_SESSION['alogin'] ?? 'Admin') ?></span>
          <i class="fas fa-chevron-down" style="font-size:12px; color:#7f8c8d;"></i>
        </div>
      </div>
    </div>
    
    <div class="content-wrapper">
      <!-- Success Message -->
      <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success">
          <i class="fas fa-check-circle"></i>
          <span><?= htmlspecialchars($_SESSION['success']) ?></span>
        </div>
        <?php unset($_SESSION['success']); ?>
      <?php endif; ?>
      
      <!-- Error Message -->
      <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-error">
          <i class="fas fa-exclamation-circle"></i>
          <span><?= htmlspecialchars($_SESSION['error']) ?></span>
        </div>
        <?php unset($_SESSION['error']); ?>
      <?php endif; ?>
      
      <!-- Page Content -->
      <?= $content ?? '' ?>
    </div>
  </main>
  
  <script>
    // Auto hide alerts after 5 seconds
    setTimeout(() => {
      document.querySelectorAll('.alert').forEach(alert => {
        alert.style.animation = 'fadeOut 0.5s';
        setTimeout(() => alert.remove(), 500);
      });
    }, 5000);
    
    // Highlight active nav link
    const currentUrl = window.location.href;
    document.querySelectorAll('.nav-link').forEach(link => {
      if (link.href === currentUrl) {
        link.classList.add('active');
      }
    });
  </script>
</body>
</html>
