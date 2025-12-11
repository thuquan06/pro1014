<?php
/**
 * Guide Layout - Giao diện dành cho Hướng dẫn viên
 */
$error = $error ?? null;
$msg   = $msg   ?? null;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>StarVel Travel - Hướng dẫn viên</title>
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    
    :root {
      --primary: #2563eb;
      --primary-dark: #1e40af;
      --primary-light: #3b82f6;
      --primary-gradient: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
      --success: #10b981;
      --danger: #ef4444;
      --warning: #f59e0b;
      --info: #06b6d4;
      --purple: #9333ea;
      --text-dark: #1f2937;
      --text-light: #6b7280;
      --text-muted: #9ca3af;
      --border: #e5e7eb;
      --bg-light: #f9fafb;
      --bg-white: #ffffff;
      --sidebar-width: 280px;
      --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
      --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
      --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
      --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
      --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }
    
    body {
      font-family: 'Inter', -apple-system, sans-serif;
      background: #ffffff;
      color: var(--text-dark);
      line-height: 1.6;
      overflow-x: hidden;
      overflow-y: auto;
    }
    
    html {
      overflow-x: hidden;
      overflow-y: auto;
    }
    
    /* ===== SIDEBAR ===== */
    .sidebar {
      position: fixed;
      left: 0;
      top: 0;
      width: var(--sidebar-width);
      height: 100vh;
      background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
      border-right: 1px solid var(--border);
      overflow-y: auto;
      z-index: 1000;
      box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
      transition: transform 0.3s ease;
      padding-top: 16px;
    }
    
    .sidebar::-webkit-scrollbar {
      width: 5px;
    }
    
    .sidebar::-webkit-scrollbar-thumb {
      background: var(--border);
      border-radius: 10px;
    }
    
    .sidebar-header {
      display: none;
      padding: 0;
      border: none;
      background: none;
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
      width: 48px;
      height: 48px;
      background: var(--primary-gradient);
      border: 2px solid rgba(255, 255, 255, 0.2);
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      color: white;
      font-size: 18px;
      box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
      transition: transform 0.3s ease;
    }
    
    .user-info:hover .user-avatar {
      transform: scale(1.1) rotate(5deg);
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
      padding: 12px 16px;
      color: var(--text-dark);
      text-decoration: none;
      border-radius: 10px;
      transition: all 0.3s ease;
      font-size: 14px;
      font-weight: 500;
      position: relative;
      margin: 4px 0;
    }
    
    .nav-link::before {
      content: '';
      position: absolute;
      left: 0;
      top: 50%;
      transform: translateY(-50%);
      width: 3px;
      height: 0;
      background: var(--primary-gradient);
      border-radius: 0 3px 3px 0;
      transition: height 0.3s ease;
    }
    
    .nav-link:hover {
      background: linear-gradient(90deg, rgba(37, 99, 235, 0.1) 0%, rgba(37, 99, 235, 0.05) 100%);
      color: var(--primary);
      transform: translateX(4px);
      padding-left: 20px;
    }
    
    .nav-link:hover::before {
      height: 60%;
    }
    
    .nav-link.active {
      background: var(--primary-gradient);
      color: white;
      box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
      transform: translateX(4px);
    }
    
    .nav-link.active::before {
      height: 100%;
      width: 4px;
    }
    
    .nav-link.active i {
      transform: scale(1.1);
    }
    
    .nav-link i {
      width: 20px;
      text-align: center;
      font-size: 16px;
    }
    
    /* ===== MAIN CONTENT ===== */
    .main-content {
      margin-left: var(--sidebar-width);
      min-height: 100vh;
      background: var(--bg-light);
      overflow-y: visible;
      overflow-x: hidden;
    }
    
    .topbar {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      padding: 20px 32px;
      border-bottom: 1px solid var(--border);
      display: flex;
      align-items: center;
      justify-content: space-between;
      position: sticky;
      top: 0;
      z-index: 999;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
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
    
    .user-menu {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 8px 16px 8px 8px;
      border-radius: 12px;
      background: var(--bg-light);
      cursor: pointer;
      transition: all 0.3s ease;
      border: 2px solid transparent;
    }
    
    .user-menu:hover {
      background: var(--primary-gradient);
      color: white;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
      border-color: rgba(255, 255, 255, 0.2);
    }
    
    .user-menu:hover .user-menu-avatar {
      background: white;
      color: var(--primary);
      transform: scale(1.1);
    }
    
    .user-menu:hover .user-menu-name {
      color: white;
    }
    
    .user-menu-avatar {
      width: 36px;
      height: 36px;
      background: var(--primary-gradient);
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      font-size: 15px;
      color: white;
      box-shadow: 0 2px 8px rgba(37, 99, 235, 0.2);
      transition: all 0.3s ease;
    }
    
    .user-menu-name {
      transition: color 0.3s ease;
    }
    
    .user-menu-name {
      font-size: 14px;
      font-weight: 600;
      color: var(--text-dark);
    }
    
    .content-wrapper {
      padding: 32px;
      min-height: calc(100vh - 100px);
      overflow-y: visible;
      overflow-x: hidden;
    }
    
    /* Smooth scroll */
    html {
      scroll-behavior: smooth;
    }
    
    /* ===== ALERTS ===== */
    .alert {
      padding: 18px 24px;
      border-radius: 12px;
      margin-bottom: 24px;
      display: flex;
      align-items: center;
      gap: 14px;
      border-left: 4px solid;
      font-size: 14px;
      font-weight: 500;
      box-shadow: var(--shadow-sm);
      animation: slideInDown 0.4s ease;
      position: relative;
      overflow: hidden;
    }
    
    .alert::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 4px;
      height: 100%;
      background: currentColor;
      opacity: 0.3;
    }
    
    .alert i {
      font-size: 22px;
      flex-shrink: 0;
    }
    
    .alert-success {
      background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(16, 185, 129, 0.05) 100%);
      border-left-color: var(--success);
      color: #065f46;
    }
    
    .alert-success i { 
      color: var(--success);
      animation: pulse 2s infinite;
    }
    
    .alert-error {
      background: linear-gradient(135deg, rgba(239, 68, 68, 0.1) 0%, rgba(239, 68, 68, 0.05) 100%);
      border-left-color: var(--danger);
      color: #991b1b;
    }
    
    .alert-error i { 
      color: var(--danger);
      animation: shake 0.5s ease;
    }
    
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
    
    @keyframes pulse {
      0%, 100% { transform: scale(1); }
      50% { transform: scale(1.1); }
    }
    
    @keyframes shake {
      0%, 100% { transform: translateX(0); }
      25% { transform: translateX(-5px); }
      75% { transform: translateX(5px); }
    }
    
    /* ===== CARDS ===== */
    .card {
      background: white;
      border-radius: 16px;
      border: 1px solid var(--border);
      margin-bottom: 24px;
      box-shadow: var(--shadow-sm);
      transition: all 0.3s ease;
      overflow: hidden;
    }
    
    .card:hover {
      box-shadow: var(--shadow-md);
      transform: translateY(-2px);
    }
    
    .card-header {
      padding: 24px 28px;
      border-bottom: 1px solid var(--border);
      display: flex;
      align-items: center;
      justify-content: space-between;
      background: linear-gradient(135deg, rgba(37, 99, 235, 0.02) 0%, rgba(59, 130, 246, 0.02) 100%);
    }
    
    .card-header h3 {
      font-size: 18px;
      font-weight: 700;
      color: var(--text-dark);
      margin: 0;
      display: flex;
      align-items: center;
      gap: 12px;
    }
    
    .card-header h3 i {
      color: var(--primary);
      font-size: 20px;
      width: 24px;
      height: 24px;
      display: flex;
      align-items: center;
      justify-content: center;
      background: rgba(37, 99, 235, 0.1);
      border-radius: 8px;
      padding: 4px;
    }
    
    .card-body {
      padding: 24px;
    }
    
    /* ===== BUTTONS ===== */
    .btn {
      padding: 12px 24px;
      border: none;
      border-radius: 10px;
      font-size: 14px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      text-decoration: none;
      position: relative;
      overflow: hidden;
    }
    
    .btn::before {
      content: '';
      position: absolute;
      top: 50%;
      left: 50%;
      width: 0;
      height: 0;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.2);
      transform: translate(-50%, -50%);
      transition: width 0.6s, height 0.6s;
    }
    
    .btn:hover::before {
      width: 300px;
      height: 300px;
    }
    
    .btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    
    .btn:active {
      transform: translateY(0);
    }
    
    .btn-primary {
      background: var(--primary-gradient);
      color: white;
      box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }
    
    .btn-primary:hover {
      box-shadow: 0 6px 20px rgba(37, 99, 235, 0.4);
    }
    
    .btn-success {
      background: linear-gradient(135deg, #10b981 0%, #059669 100%);
      color: white;
      box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }
    
    .btn-success:hover {
      box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
    }
    
    .btn-danger {
      background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
      color: white;
      box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }
    
    .btn-danger:hover {
      box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
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
    
    table {
      width: 100%;
      border-collapse: collapse;
      background: white;
    }
    
    table thead {
      background: linear-gradient(135deg, rgba(37, 99, 235, 0.05) 0%, rgba(59, 130, 246, 0.05) 100%);
    }
    
    table th {
      padding: 16px 20px;
      text-align: left;
      font-weight: 700;
      color: var(--text-dark);
      font-size: 12px;
      text-transform: uppercase;
      letter-spacing: 0.8px;
      border-bottom: 2px solid var(--border);
      white-space: nowrap;
    }
    
    table td {
      padding: 16px 20px;
      border-bottom: 1px solid var(--border);
      color: var(--text-dark);
      font-size: 14px;
      vertical-align: middle;
    }
    
    table tbody tr {
      transition: all 0.2s ease;
    }
    
    table tbody tr:hover {
      background: linear-gradient(90deg, rgba(37, 99, 235, 0.05) 0%, rgba(37, 99, 235, 0.02) 100%);
      transform: scale(1.01);
    }
    
    table tbody tr:last-child td {
      border-bottom: none;
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
      border-radius: 16px;
      padding: 28px;
      display: flex;
      align-items: center;
      gap: 20px;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }
    
    .stat-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 4px;
      height: 100%;
      background: var(--primary-gradient);
      transition: width 0.3s ease;
    }
    
    .stat-card:hover {
      border-color: var(--primary);
      box-shadow: 0 8px 24px rgba(37, 99, 235, 0.15);
      transform: translateY(-4px);
    }
    
    .stat-card:hover::before {
      width: 100%;
      opacity: 0.05;
    }
    
    .stat-icon {
      width: 64px;
      height: 64px;
      border-radius: 14px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 28px;
      flex-shrink: 0;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s ease;
    }
    
    .stat-card:hover .stat-icon {
      transform: scale(1.1) rotate(5deg);
    }
    
    .stat-icon.blue {
      background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
      color: white;
    }
    
    .stat-icon.green {
      background: linear-gradient(135deg, #10b981 0%, #059669 100%);
      color: white;
    }
    
    .stat-icon.orange {
      background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
      color: white;
    }
    
    .stat-icon.red {
      background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
      color: white;
    }
    
    .stat-icon.purple {
      background: linear-gradient(135deg, #9333ea 0%, #7e22ce 100%);
      color: white;
    }
    
    .stat-icon.cyan {
      background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
      color: white;
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
        min-height: auto;
      }
      
      .mobile-toggle {
        display: block !important;
      }
    }
    
    .mobile-toggle {
      display: none;
      width: 44px;
      height: 44px;
      background: var(--bg-light);
      border: 2px solid var(--border);
      border-radius: 12px;
      color: var(--text-dark);
      cursor: pointer;
      font-size: 18px;
      transition: all 0.3s ease;
      align-items: center;
      justify-content: center;
    }
    
    .mobile-toggle:hover {
      background: var(--primary-gradient);
      color: white;
      border-color: transparent;
      transform: scale(1.05);
    }
    
    .mobile-toggle:active {
      transform: scale(0.95);
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
    <div class="sidebar-header"></div>
    
    <div class="sidebar-user">
      <div class="user-info">
        <div class="user-avatar">
          <?php 
          $guideName = $_SESSION['guide_name'] ?? 'G';
          echo strtoupper(substr($guideName, 0, 1)); 
          ?>
        </div>
        <div class="user-details">
          <h4><?= htmlspecialchars($_SESSION['guide_name'] ?? 'Hướng dẫn viên') ?></h4>
          <p>Hướng dẫn viên</p>
        </div>
      </div>
    </div>
    
    <nav class="sidebar-nav">
      <div class="nav-section">
        <div class="nav-section-title">Tổng quan</div>
        <div class="nav-item">
          <a href="?act=guide-dashboard" class="nav-link">
            <i class="fas fa-chart-line"></i>
            <span>Dashboard</span>
          </a>
        </div>
      </div>
      
      <div class="nav-section">
        <div class="nav-section-title">Phân công</div>
        <div class="nav-item">
          <a href="?act=guide-assignments" class="nav-link">
            <i class="fas fa-calendar-check"></i>
            <span>Danh sách phân công</span>
          </a>
        </div>
        <div class="nav-item">
          <a href="?act=guide-schedule" class="nav-link">
            <i class="fas fa-calendar-alt"></i>
            <span>Lịch làm việc</span>
          </a>
        </div>
      </div>
      
      <div class="nav-section">
        <div class="nav-section-title">Nhật ký</div>
        <div class="nav-item">
          <a href="?act=guide-journals" class="nav-link">
            <i class="fas fa-book"></i>
            <span>Nhật ký tour</span>
          </a>
        </div>
        <div class="nav-item">
          <a href="?act=guide-incidents" class="nav-link">
            <i class="fas fa-exclamation-triangle"></i>
            <span>Báo cáo sự cố</span>
          </a>
        </div>
      </div>
      
      <div class="nav-section">
        <div class="nav-section-title">Tài khoản</div>
        <div class="nav-item">
          <a href="?act=guide-profile" class="nav-link">
            <i class="fas fa-user"></i>
            <span>Thông tin cá nhân</span>
          </a>
        </div>
        <div class="nav-item">
          <a href="?act=guide-logout" class="nav-link" onclick="return confirm('Bạn có chắc muốn đăng xuất?')">
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
        <h1>Chào mừng, <?= htmlspecialchars($_SESSION['guide_name'] ?? 'Hướng dẫn viên') ?>!</h1>
        <p>
          <?php
          $days = ['Chủ Nhật', 'Thứ Hai', 'Thứ Ba', 'Thứ Tư', 'Thứ Năm', 'Thứ Sáu', 'Thứ Bảy'];
          echo $days[date('w')] . ', ' . date('d/m/Y');
          ?>
        </p>
      </div>
      
      <div class="topbar-right">
        <div class="user-menu" onclick="window.location='?act=guide-profile'" title="Thông tin cá nhân">
          <div class="user-menu-avatar">
            <?= strtoupper(substr($_SESSION['guide_name'] ?? 'G', 0, 1)) ?>
          </div>
          <span class="user-menu-name"><?= htmlspecialchars($_SESSION['guide_name'] ?? 'Guide') ?></span>
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


