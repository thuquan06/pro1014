<?php
/**
 * Admin Layout - MINIMALIST CLEAN DESIGN
 * Version: 5.0 - Simple & Clean
 * Updated: 2025-11-25
 */
$error = $error ?? null;
$msg   = $msg   ?? null;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>StarVel Travel - Quản trị</title>
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  
  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
  
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    
    :root {
      --primary: #2563eb;
      --primary-dark: #1e40af;
      --success: #10b981;
      --danger: #ef4444;
      --warning: #f59e0b;
      --text-dark: #1f2937;
      --text-light: #6b7280;
      --border: #e5e7eb;
      --bg-light: #f9fafb;
      --sidebar-width: 260px;
    }
    
    body {
      font-family: 'Inter', -apple-system, sans-serif;
      background: #ffffff;
      color: var(--text-dark);
      line-height: 1.6;
    }
    
    /* ===== SIDEBAR ===== */
    .sidebar {
      position: fixed;
      left: 0;
      top: 0;
      width: var(--sidebar-width);
      height: 100vh;
      background: #ffffff;
      border-right: 1px solid var(--border);
      overflow-y: auto;
      z-index: 1000;
    }
    
    .sidebar::-webkit-scrollbar {
      width: 5px;
    }
    
    .sidebar::-webkit-scrollbar-thumb {
      background: var(--border);
      border-radius: 10px;
    }
    
    .sidebar-header {
      padding: 24px 20px;
      border-bottom: 1px solid var(--border);
    }
    
    .logo {
      display: flex;
      align-items: center;
      gap: 12px;
      text-decoration: none;
    }
    
    .logo-icon {
      width: 40px;
      height: 40px;
      background: var(--primary);
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 20px;
    }
    
    .logo-text h2 {
      font-size: 20px;
      font-weight: 700;
      color: var(--text-dark);
      margin: 0;
    }
    
    .logo-text p {
      font-size: 12px;
      color: var(--text-light);
      margin: 0;
    }
    
    .sidebar-user {
      padding: 20px;
      border-bottom: 1px solid var(--border);
    }
    
    .user-info {
      display: flex;
      align-items: center;
      gap: 12px;
    }
    
    .user-avatar {
      width: 44px;
      height: 44px;
      background: var(--bg-light);
      border: 2px solid var(--border);
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      color: var(--primary);
      font-size: 16px;
    }
    
    .user-details h4 {
      font-size: 14px;
      font-weight: 600;
      color: var(--text-dark);
      margin: 0;
    }
    
    .user-details p {
      font-size: 12px;
      color: var(--text-light);
      margin: 0;
    }
    
    .sidebar-nav {
      padding: 20px 0;
    }
    
    .nav-section {
      margin-bottom: 24px;
    }
    
    .nav-section-title {
      padding: 0 20px 8px;
      font-size: 11px;
      font-weight: 600;
      color: var(--text-light);
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    
    .nav-item {
      margin: 2px 12px;
    }
    
    .nav-link {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 10px 16px;
      color: var(--text-dark);
      text-decoration: none;
      border-radius: 8px;
      transition: all 0.2s;
      font-size: 14px;
      font-weight: 500;
    }
    
    .nav-link:hover {
      background: var(--bg-light);
      color: var(--primary);
    }
    
    .nav-link.active {
      background: var(--primary);
      color: white;
    }
    
    .nav-link i {
      width: 20px;
      text-align: center;
      font-size: 16px;
    }
    
    .nav-link .badge {
      margin-left: auto;
      background: var(--danger);
      color: white;
      padding: 2px 8px;
      border-radius: 10px;
      font-size: 11px;
      font-weight: 600;
    }
    
    /* ===== MAIN CONTENT ===== */
    .main-content {
      margin-left: var(--sidebar-width);
      min-height: 100vh;
      background: var(--bg-light);
    }
    
    .topbar {
      background: #ffffff;
      padding: 20px 32px;
      border-bottom: 1px solid var(--border);
      display: flex;
      align-items: center;
      justify-content: space-between;
      position: sticky;
      top: 0;
      z-index: 999;
    }
    
    .topbar-left h1 {
      font-size: 24px;
      font-weight: 700;
      color: var(--text-dark);
      margin: 0 0 4px 0;
    }
    
    .topbar-left p {
      font-size: 14px;
      color: var(--text-light);
      margin: 0;
    }
    
    .topbar-right {
      display: flex;
      align-items: center;
      gap: 12px;
    }
    
    .topbar-btn {
      width: 40px;
      height: 40px;
      background: var(--bg-light);
      border: none;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: all 0.2s;
      color: var(--text-dark);
      position: relative;
    }
    
    .topbar-btn:hover {
      background: var(--primary);
      color: white;
    }
    
    .topbar-btn .badge {
      position: absolute;
      top: -4px;
      right: -4px;
      background: var(--danger);
      color: white;
      border-radius: 10px;
      padding: 2px 6px;
      font-size: 10px;
      font-weight: 600;
      border: 2px solid white;
    }
    
    .user-menu {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 8px 12px;
      border-radius: 10px;
      background: var(--bg-light);
      cursor: pointer;
      transition: all 0.2s;
    }
    
    .user-menu:hover {
      background: var(--primary);
      color: white;
    }
    
    .user-menu:hover .user-menu-avatar {
      background: white;
      color: var(--primary);
    }
    
    .user-menu-avatar {
      width: 32px;
      height: 32px;
      background: white;
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      font-size: 14px;
      color: var(--primary);
    }
    
    .user-menu-name {
      font-size: 14px;
      font-weight: 600;
      color: var(--text-dark);
    }
    
    .content-wrapper {
      padding: 32px;
    }
    
    /* ===== ALERTS ===== */
    .alert {
      padding: 16px 20px;
      border-radius: 10px;
      margin-bottom: 24px;
      display: flex;
      align-items: center;
      gap: 12px;
      border-left: 4px solid;
      font-size: 14px;
      font-weight: 500;
    }
    
    .alert i {
      font-size: 20px;
    }
    
    .alert-success {
      background: #d1fae5;
      border-left-color: var(--success);
      color: #065f46;
    }
    
    .alert-success i { color: var(--success); }
    
    .alert-error {
      background: #fee2e2;
      border-left-color: var(--danger);
      color: #991b1b;
    }
    
    .alert-error i { color: var(--danger); }
    
    /* ===== CARDS ===== */
    .card {
      background: white;
      border-radius: 12px;
      border: 1px solid var(--border);
      margin-bottom: 24px;
    }
    
    .card-header {
      padding: 20px 24px;
      border-bottom: 1px solid var(--border);
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    
    .card-header h3 {
      font-size: 18px;
      font-weight: 700;
      color: var(--text-dark);
      margin: 0;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    
    .card-header h3 i {
      color: var(--primary);
      font-size: 20px;
    }
    
    .card-body {
      padding: 24px;
    }
    
    /* ===== BUTTONS ===== */
    .btn {
      padding: 10px 20px;
      border: none;
      border-radius: 8px;
      font-size: 14px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.2s;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      text-decoration: none;
    }
    
    .btn:hover {
      transform: translateY(-1px);
    }
    
    .btn-primary {
      background: var(--primary);
      color: white;
    }
    
    .btn-primary:hover {
      background: var(--primary-dark);
    }
    
    .btn-success {
      background: var(--success);
      color: white;
    }
    
    .btn-danger {
      background: var(--danger);
      color: white;
    }
    
    .btn-sm {
      padding: 6px 12px;
      font-size: 13px;
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
      background: var(--bg-light);
    }
    
    table th {
      padding: 12px 16px;
      text-align: left;
      font-weight: 600;
      color: var(--text-dark);
      font-size: 13px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      border-bottom: 2px solid var(--border);
    }
    
    table td {
      padding: 12px 16px;
      border-bottom: 1px solid var(--border);
      color: var(--text-dark);
      font-size: 14px;
    }
    
    table tr:hover {
      background: var(--bg-light);
    }
    
    /* ===== STATS CARDS ===== */
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
      gap: 24px;
      margin-bottom: 32px;
    }
    
    .stat-card {
      background: white;
      border: 1px solid var(--border);
      border-radius: 12px;
      padding: 24px;
      display: flex;
      align-items: center;
      gap: 20px;
      transition: all 0.2s;
    }
    
    .stat-card:hover {
      border-color: var(--primary);
      box-shadow: 0 4px 12px rgba(37, 99, 235, 0.1);
    }
    
    .stat-icon {
      width: 56px;
      height: 56px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 24px;
    }
    
    .stat-icon.blue {
      background: #dbeafe;
      color: var(--primary);
    }
    
    .stat-icon.green {
      background: #d1fae5;
      color: var(--success);
    }
    
    .stat-icon.orange {
      background: #fed7aa;
      color: var(--warning);
    }
    
    .stat-icon.red {
      background: #fee2e2;
      color: var(--danger);
    }
    
    .stat-icon.purple {
      background: #e9d5ff;
      color: #9333ea;
    }
    
    .stat-icon.cyan {
      background: #cffafe;
      color: #06b6d4;
    }
    
    .stat-details {
      flex: 1;
    }
    
    .stat-details h4 {
      font-size: 28px;
      font-weight: 700;
      color: var(--text-dark);
      margin: 0 0 4px 0;
      line-height: 1;
    }
    
    .stat-details p {
      font-size: 14px;
      color: var(--text-light);
      margin: 0;
      font-weight: 500;
    }
    
    .stat-details small {
      font-size: 12px;
      color: var(--success);
      font-weight: 600;
      margin-top: 8px;
      display: block;
    }
    
    /* ===== FORM ===== */
    .form-group {
      margin-bottom: 20px;
    }
    
    .form-label {
      display: block;
      margin-bottom: 8px;
      font-weight: 600;
      color: var(--text-dark);
      font-size: 14px;
    }
    
    .form-control {
      width: 100%;
      padding: 10px 14px;
      border: 1px solid var(--border);
      border-radius: 8px;
      font-size: 14px;
      transition: all 0.2s;
      background: white;
      color: var(--text-dark);
    }
    
    .form-control:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
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
      
      .topbar {
        padding: 16px 20px;
      }
      
      .content-wrapper {
        padding: 20px;
      }
      
      .mobile-toggle {
        display: block !important;
      }
    }
    
    .mobile-toggle {
      display: none;
      width: 40px;
      height: 40px;
      background: var(--bg-light);
      border: none;
      border-radius: 10px;
      color: var(--text-dark);
      cursor: pointer;
      font-size: 18px;
    }
    
    /* ===== ANIMATIONS ===== */
    @keyframes fadeOut {
      from { opacity: 1; }
      to { opacity: 0; }
    }
  </style>
</head>
<body>
  
  <!-- ===== SIDEBAR ===== -->
  <aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
      <a href="?act=admin" class="logo">
        <div class="logo-icon">
          <i class="fas fa-plane"></i>
        </div>
        <div class="logo-text">
          <h2>StarVel Travel</h2>
          <p>Admin Panel</p>
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
            <i class="fas fa-chart-line"></i>
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
        <div class="nav-item">
          <a href="?act=admin-departure-plans" class="nav-link">
            <i class="fas fa-calendar-alt"></i>
            <span>Lịch khởi hành</span>
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
            <i class="fas fa-file-invoice-dollar"></i>
            <span>Hóa đơn</span>
            <span class="badge">3</span>
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
    <div class="topbar">
      <button class="mobile-toggle" onclick="toggleMobileSidebar()">
        <i class="fas fa-bars"></i>
      </button>
      
      <div class="topbar-left">
        <h1>Chào mừng, <?= htmlspecialchars($_SESSION['alogin'] ?? 'Admin') ?>!</h1>
        <p>
          <?php
          $days = ['Chủ Nhật', 'Thứ Hai', 'Thứ Ba', 'Thứ Tư', 'Thứ Năm', 'Thứ Sáu', 'Thứ Bảy'];
          echo $days[date('w')] . ', ' . date('d/m/Y');
          ?>
        </p>
      </div>
      
      <div class="topbar-right">
        <button class="topbar-btn" title="Thông báo">
          <i class="fas fa-bell"></i>
          <span class="badge">3</span>
        </button>
        
        <button class="topbar-btn" title="Tin nhắn">
          <i class="fas fa-envelope"></i>
          <span class="badge">5</span>
        </button>
        
        <div class="user-menu" onclick="window.location='?act=logout'" title="Đăng xuất">
          <div class="user-menu-avatar">
            <?= strtoupper(substr($_SESSION['alogin'] ?? 'A', 0, 1)) ?>
          </div>
          <span class="user-menu-name"><?= htmlspecialchars($_SESSION['alogin'] ?? 'Admin') ?></span>
          <i class="fas fa-chevron-down" style="font-size:12px; color: var(--text-light);"></i>
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
    // Mobile Sidebar Toggle
    function toggleMobileSidebar() {
      const sidebar = document.getElementById('sidebar');
      sidebar.classList.toggle('active');
    }
    
    // Auto hide alerts
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
    
    // Close mobile sidebar when clicking outside
    document.addEventListener('click', (e) => {
      const sidebar = document.getElementById('sidebar');
      const mobileToggle = document.querySelector('.mobile-toggle');
      
      if (window.innerWidth <= 768 && 
          !sidebar.contains(e.target) && 
          e.target !== mobileToggle &&
          !mobileToggle.contains(e.target)) {
        sidebar.classList.remove('active');
      }
    });
  </script>
</body>
</html>
