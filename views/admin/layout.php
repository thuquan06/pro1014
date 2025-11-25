<?php
/**
 * Admin Layout - GLASSMORPHISM DESIGN
 * Version: 4.0 - Glassmorphism & Material
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
  <title>StarVel Travel - Admin Panel</title>
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  
  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
  
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    
    :root {
      --primary: #FF6B6B;
      --secondary: #4ECDC4;
      --accent: #FFD93D;
      --success: #6BCF7F;
      --danger: #FF5A5F;
      --warning: #FFA07A;
      --info: #5DADE2;
      --purple: #C77DFF;
      --pink: #FF6BB5;
    }
    
    body {
      font-family: 'Outfit', sans-serif;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
      background-attachment: fixed;
      color: #2c3e50;
      line-height: 1.6;
      overflow-x: hidden;
    }
    
    /* Animated Background */
    body::before {
      content: '';
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: 
        radial-gradient(circle at 20% 80%, rgba(255, 107, 107, 0.3) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, rgba(78, 205, 196, 0.3) 0%, transparent 50%),
        radial-gradient(circle at 40% 40%, rgba(255, 217, 61, 0.2) 0%, transparent 50%);
      animation: moveBackground 20s ease infinite;
      pointer-events: none;
      z-index: 0;
    }
    
    @keyframes moveBackground {
      0%, 100% { transform: translate(0, 0) scale(1); }
      33% { transform: translate(30px, -50px) scale(1.1); }
      66% { transform: translate(-20px, 20px) scale(0.9); }
    }
    
    /* Glass Card */
    .glass {
      background: rgba(255, 255, 255, 0.15);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border: 1px solid rgba(255, 255, 255, 0.3);
      box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.2);
    }
    
    .glass-white {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border: 1px solid rgba(255, 255, 255, 0.5);
      box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.1);
    }
    
    /* ===== SIDEBAR ===== */
    .sidebar {
      position: fixed;
      left: 20px;
      top: 20px;
      width: 280px;
      height: calc(100vh - 40px);
      z-index: 1000;
      border-radius: 25px;
      overflow: hidden;
      transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    }
    
    .sidebar.collapsed {
      width: 90px;
    }
    
    .sidebar-bg {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(180deg, 
        rgba(255, 107, 107, 0.9) 0%,
        rgba(78, 205, 196, 0.9) 50%,
        rgba(199, 125, 255, 0.9) 100%);
      z-index: -1;
    }
    
    .sidebar-content {
      height: 100%;
      overflow-y: auto;
      overflow-x: hidden;
      padding: 25px 0;
    }
    
    .sidebar-content::-webkit-scrollbar {
      width: 4px;
    }
    
    .sidebar-content::-webkit-scrollbar-thumb {
      background: rgba(255, 255, 255, 0.5);
      border-radius: 10px;
    }
    
    .sidebar-header {
      padding: 0 25px 25px;
      border-bottom: 2px solid rgba(255, 255, 255, 0.2);
      margin-bottom: 25px;
      position: relative;
    }
    
    .sidebar-logo {
      display: flex;
      align-items: center;
      gap: 15px;
      text-decoration: none;
      color: white;
    }
    
    .logo-icon {
      min-width: 55px;
      width: 55px;
      height: 55px;
      background: rgba(255, 255, 255, 0.25);
      backdrop-filter: blur(10px);
      border-radius: 18px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 28px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
      border: 2px solid rgba(255, 255, 255, 0.3);
    }
    
    .logo-text {
      flex: 1;
      transition: all 0.3s;
    }
    
    .sidebar.collapsed .logo-text,
    .sidebar.collapsed .user-details,
    .sidebar.collapsed .nav-text,
    .sidebar.collapsed .nav-section-title {
      opacity: 0;
      width: 0;
      overflow: hidden;
    }
    
    .logo-text h2 {
      font-size: 26px;
      font-weight: 900;
      color: white;
      margin: 0;
      text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    }
    
    .logo-text p {
      font-size: 11px;
      color: rgba(255, 255, 255, 0.8);
      margin: 0;
      text-transform: uppercase;
      letter-spacing: 3px;
      font-weight: 600;
    }
    
    .sidebar-toggle {
      position: absolute;
      right: -15px;
      top: 50%;
      transform: translateY(-50%);
      width: 35px;
      height: 35px;
      background: white;
      border: none;
      border-radius: 50%;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
      transition: all 0.3s;
      color: var(--primary);
      font-size: 16px;
      font-weight: 700;
    }
    
    .sidebar-toggle:hover {
      transform: translateY(-50%) scale(1.15) rotate(180deg);
    }
    
    .sidebar-user {
      padding: 20px 25px;
      margin-bottom: 20px;
    }
    
    .user-card {
      background: rgba(255, 255, 255, 0.2);
      backdrop-filter: blur(10px);
      border-radius: 20px;
      padding: 18px;
      border: 1px solid rgba(255, 255, 255, 0.3);
      display: flex;
      align-items: center;
      gap: 15px;
      transition: all 0.3s;
    }
    
    .user-card:hover {
      background: rgba(255, 255, 255, 0.3);
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }
    
    .user-avatar {
      min-width: 55px;
      width: 55px;
      height: 55px;
      border-radius: 16px;
      background: linear-gradient(135deg, #FFD93D 0%, #FF6B6B 100%);
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 24px;
      font-weight: 800;
      border: 3px solid rgba(255, 255, 255, 0.5);
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }
    
    .user-details h4 {
      font-size: 16px;
      color: white;
      margin: 0 0 3px 0;
      font-weight: 700;
    }
    
    .user-details p {
      font-size: 12px;
      color: rgba(255, 255, 255, 0.8);
      margin: 0;
      font-weight: 500;
    }
    
    .nav-section {
      margin-bottom: 10px;
    }
    
    .nav-section-title {
      padding: 12px 25px 8px;
      font-size: 10px;
      font-weight: 800;
      color: rgba(255, 255, 255, 0.7);
      text-transform: uppercase;
      letter-spacing: 2px;
    }
    
    .nav-item {
      margin: 4px 15px;
    }
    
    .nav-link {
      display: flex;
      align-items: center;
      gap: 15px;
      padding: 14px 20px;
      color: white;
      text-decoration: none;
      border-radius: 15px;
      transition: all 0.3s;
      font-size: 15px;
      font-weight: 600;
      position: relative;
    }
    
    .nav-link:hover {
      background: rgba(255, 255, 255, 0.2);
      transform: translateX(5px);
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
    
    .nav-link.active {
      background: rgba(255, 255, 255, 0.95);
      color: var(--primary);
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
    }
    
    .nav-link i {
      min-width: 24px;
      text-align: center;
      font-size: 20px;
    }
    
    .nav-link .badge {
      margin-left: auto;
      background: var(--danger);
      color: white;
      padding: 4px 10px;
      border-radius: 20px;
      font-size: 11px;
      font-weight: 800;
      box-shadow: 0 2px 10px rgba(255, 90, 95, 0.5);
    }
    
    /* ===== MAIN CONTENT ===== */
    .main-wrapper {
      margin-left: 320px;
      padding: 20px;
      min-height: 100vh;
      transition: all 0.4s;
      position: relative;
      z-index: 1;
    }
    
    .main-wrapper.expanded {
      margin-left: 130px;
    }
    
    .topbar {
      border-radius: 25px;
      padding: 20px 30px;
      margin-bottom: 25px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    
    .topbar-left h1 {
      font-size: 32px;
      font-weight: 900;
      color: white;
      margin: 0 0 5px 0;
      text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    }
    
    .topbar-left p {
      font-size: 14px;
      color: rgba(255, 255, 255, 0.9);
      margin: 0;
      display: flex;
      align-items: center;
      gap: 8px;
      font-weight: 600;
    }
    
    .topbar-right {
      display: flex;
      align-items: center;
      gap: 15px;
    }
    
    .topbar-btn {
      width: 50px;
      height: 50px;
      border-radius: 16px;
      background: rgba(255, 255, 255, 0.25);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.3);
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: all 0.3s;
      color: white;
      font-size: 20px;
      position: relative;
    }
    
    .topbar-btn:hover {
      background: rgba(255, 255, 255, 0.95);
      color: var(--primary);
      transform: translateY(-3px) rotate(5deg);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }
    
    .topbar-btn .badge {
      position: absolute;
      top: -5px;
      right: -5px;
      background: var(--danger);
      color: white;
      border-radius: 20px;
      padding: 3px 8px;
      font-size: 11px;
      font-weight: 800;
      box-shadow: 0 2px 10px rgba(255, 90, 95, 0.5);
    }
    
    .user-profile {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 8px 20px 8px 8px;
      border-radius: 30px;
      background: rgba(255, 255, 255, 0.25);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.3);
      cursor: pointer;
      transition: all 0.3s;
    }
    
    .user-profile:hover {
      background: rgba(255, 255, 255, 0.95);
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }
    
    .user-profile:hover .profile-name,
    .user-profile:hover i {
      color: var(--primary) !important;
    }
    
    .profile-avatar {
      width: 45px;
      height: 45px;
      border-radius: 14px;
      background: linear-gradient(135deg, var(--accent) 0%, var(--primary) 100%);
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-weight: 800;
      font-size: 18px;
      border: 2px solid rgba(255, 255, 255, 0.5);
    }
    
    .profile-name {
      font-size: 15px;
      font-weight: 700;
      color: white;
      transition: all 0.3s;
    }
    
    .content-area {
      position: relative;
      z-index: 1;
    }
    
    /* ===== ALERTS ===== */
    .alert {
      padding: 20px 25px;
      border-radius: 20px;
      margin-bottom: 25px;
      display: flex;
      align-items: center;
      gap: 15px;
      animation: slideInRight 0.5s ease-out;
      border: 2px solid;
      font-weight: 600;
    }
    
    .alert i {
      font-size: 26px;
    }
    
    .alert-success {
      background: rgba(107, 207, 127, 0.15);
      backdrop-filter: blur(10px);
      border-color: var(--success);
      color: #2d7a3e;
    }
    
    .alert-success i { color: var(--success); }
    
    .alert-error {
      background: rgba(255, 90, 95, 0.15);
      backdrop-filter: blur(10px);
      border-color: var(--danger);
      color: #991b1b;
    }
    
    .alert-error i { color: var(--danger); }
    
    /* ===== CARDS ===== */
    .card {
      border-radius: 25px;
      margin-bottom: 25px;
      overflow: hidden;
      transition: all 0.4s;
    }
    
    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
    }
    
    .card-header {
      padding: 25px 30px;
      border-bottom: 2px solid rgba(0, 0, 0, 0.05);
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    
    .card-header h3 {
      font-size: 22px;
      font-weight: 800;
      color: #2c3e50;
      margin: 0;
      display: flex;
      align-items: center;
      gap: 12px;
    }
    
    .card-header h3 i {
      font-size: 26px;
      background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }
    
    .card-body {
      padding: 30px;
    }
    
    /* ===== BUTTONS ===== */
    .btn {
      padding: 14px 32px;
      border: none;
      border-radius: 16px;
      font-size: 15px;
      font-weight: 700;
      cursor: pointer;
      transition: all 0.3s;
      display: inline-flex;
      align-items: center;
      gap: 10px;
      text-decoration: none;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    
    .btn:hover {
      transform: translateY(-3px);
      box-shadow: 0 12px 30px rgba(0, 0, 0, 0.25);
    }
    
    .btn-primary {
      background: linear-gradient(135deg, var(--primary) 0%, var(--pink) 100%);
      color: white;
    }
    
    .btn-success {
      background: linear-gradient(135deg, var(--success) 0%, var(--secondary) 100%);
      color: white;
    }
    
    .btn-danger {
      background: linear-gradient(135deg, var(--danger) 0%, var(--warning) 100%);
      color: white;
    }
    
    /* ===== ANIMATIONS ===== */
    @keyframes slideInRight {
      from {
        opacity: 0;
        transform: translateX(50px);
      }
      to {
        opacity: 1;
        transform: translateX(0);
      }
    }
    
    @keyframes fadeOut {
      from { opacity: 1; }
      to { opacity: 0; }
    }
    
    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
      .sidebar {
        left: -300px;
      }
      
      .sidebar.active {
        left: 20px;
      }
      
      .main-wrapper,
      .main-wrapper.expanded {
        margin-left: 0;
      }
      
      .mobile-toggle {
        display: block !important;
      }
    }
    
    .mobile-toggle {
      display: none;
      width: 50px;
      height: 50px;
      border-radius: 16px;
      background: rgba(255, 255, 255, 0.95);
      border: none;
      color: var(--primary);
      cursor: pointer;
      font-size: 20px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
    
    /* ===== STATS CARDS ===== */
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 25px;
      margin-bottom: 30px;
    }
    
    .stat-card {
      border-radius: 25px;
      padding: 30px;
      display: flex;
      align-items: center;
      gap: 25px;
      transition: all 0.4s;
      position: relative;
      overflow: hidden;
    }
    
    .stat-card::before {
      content: '';
      position: absolute;
      top: -50%;
      right: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle, rgba(255, 255, 255, 0.3) 0%, transparent 70%);
      animation: rotate 10s linear infinite;
    }
    
    @keyframes rotate {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
    
    .stat-card:hover {
      transform: translateY(-8px) scale(1.02);
      box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);
    }
    
    .stat-icon {
      min-width: 80px;
      width: 80px;
      height: 80px;
      border-radius: 22px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 38px;
      color: white;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
      position: relative;
      z-index: 1;
    }
    
    .stat-details {
      flex: 1;
      position: relative;
      z-index: 1;
    }
    
    .stat-details h4 {
      font-size: 36px;
      font-weight: 900;
      color: #2c3e50;
      margin: 0 0 5px 0;
      line-height: 1;
    }
    
    .stat-details p {
      font-size: 15px;
      color: #5a6c7d;
      margin: 0;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    
    .stat-details small {
      font-size: 13px;
      color: var(--success);
      font-weight: 700;
      display: flex;
      align-items: center;
      gap: 5px;
      margin-top: 10px;
    }
  </style>
</head>
<body>
  
  <!-- ===== SIDEBAR ===== -->
  <aside class="sidebar glass" id="sidebar">
    <div class="sidebar-bg"></div>
    <div class="sidebar-content">
      <div class="sidebar-header">
        <a href="?act=admin" class="sidebar-logo">
          <div class="logo-icon">
            <i class="fas fa-plane-departure"></i>
          </div>
          <div class="logo-text">
            <h2>StarVel</h2>
            <p>Travel</p>
          </div>
        </a>
        <button class="sidebar-toggle" onclick="toggleSidebar()">
          <i class="fas fa-angle-left"></i>
        </button>
      </div>
      
      <div class="sidebar-user">
        <div class="user-card">
          <div class="user-avatar">
            <?php 
            $username = $_SESSION['alogin'] ?? 'A';
            echo strtoupper(substr($username, 0, 1)); 
            ?>
          </div>
          <div class="user-details">
            <h4><?= htmlspecialchars($_SESSION['alogin'] ?? 'Admin') ?></h4>
            <p>Administrator</p>
          </div>
        </div>
      </div>
      
      <nav>
        <div class="nav-section">
          <div class="nav-section-title">Dashboard</div>
          <div class="nav-item">
            <a href="?act=admin" class="nav-link">
              <i class="fas fa-chart-line"></i>
              <span class="nav-text">Overview</span>
            </a>
          </div>
        </div>
        
        <div class="nav-section">
          <div class="nav-section-title">Tour Management</div>
          <div class="nav-item">
            <a href="?act=admin-tours" class="nav-link">
              <i class="fas fa-map-marked-alt"></i>
              <span class="nav-text">All Tours</span>
            </a>
          </div>
          <div class="nav-item">
            <a href="?act=admin-tour-create" class="nav-link">
              <i class="fas fa-plus-circle"></i>
              <span class="nav-text">Add New</span>
            </a>
          </div>
          <div class="nav-item">
            <a href="?act=tour-publish-dashboard" class="nav-link">
              <i class="fas fa-rocket"></i>
              <span class="nav-text">Publish</span>
            </a>
          </div>
        </div>
        
        <div class="nav-section">
          <div class="nav-section-title">Content</div>
          <div class="nav-item">
            <a href="?act=blog-list" class="nav-link">
              <i class="fas fa-newspaper"></i>
              <span class="nav-text">Blog Posts</span>
            </a>
          </div>
          <div class="nav-item">
            <a href="?act=province-list" class="nav-link">
              <i class="fas fa-map-marker-alt"></i>
              <span class="nav-text">Locations</span>
            </a>
          </div>
        </div>
        
        <div class="nav-section">
          <div class="nav-section-title">Orders</div>
          <div class="nav-item">
            <a href="?act=hoadon-list" class="nav-link">
              <i class="fas fa-file-invoice-dollar"></i>
              <span class="nav-text">Invoices</span>
              <span class="badge">NEW</span>
            </a>
          </div>
        </div>
        
        <div class="nav-section">
          <div class="nav-section-title">System</div>
          <div class="nav-item">
            <a href="?act=logout" class="nav-link" onclick="return confirm('Bạn có chắc muốn đăng xuất?')">
              <i class="fas fa-sign-out-alt"></i>
              <span class="nav-text">Logout</span>
            </a>
          </div>
        </div>
      </nav>
    </div>
  </aside>
  
  <!-- ===== MAIN CONTENT ===== -->
  <main class="main-wrapper" id="mainWrapper">
    <div class="topbar glass">
      <button class="mobile-toggle" onclick="toggleMobileSidebar()">
        <i class="fas fa-bars"></i>
      </button>
      
      <div class="topbar-left">
        <h1>Welcome Back! 🌟</h1>
        <p>
          <i class="far fa-calendar-alt"></i>
          <?php
          $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
          echo $days[date('w')] . ', ' . date('F d, Y');
          ?>
        </p>
      </div>
      
      <div class="topbar-right">
        <div class="topbar-btn" title="Notifications">
          <i class="fas fa-bell"></i>
          <span class="badge">5</span>
        </div>
        
        <div class="topbar-btn" title="Messages">
          <i class="fas fa-envelope"></i>
          <span class="badge">12</span>
        </div>
        
        <div class="topbar-btn" title="Settings">
          <i class="fas fa-cog"></i>
        </div>
        
        <div class="user-profile" onclick="window.location='?act=logout'" title="Logout">
          <div class="profile-avatar">
            <?= strtoupper(substr($_SESSION['alogin'] ?? 'A', 0, 1)) ?>
          </div>
          <span class="profile-name"><?= htmlspecialchars($_SESSION['alogin'] ?? 'Admin') ?></span>
          <i class="fas fa-chevron-down" style="font-size:12px; color: white;"></i>
        </div>
      </div>
    </div>
    
    <div class="content-area">
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
    // Sidebar Toggle
    function toggleSidebar() {
      const sidebar = document.getElementById('sidebar');
      const mainWrapper = document.getElementById('mainWrapper');
      const toggleIcon = document.querySelector('.sidebar-toggle i');
      
      sidebar.classList.toggle('collapsed');
      mainWrapper.classList.toggle('expanded');
      
      if (sidebar.classList.contains('collapsed')) {
        toggleIcon.classList.remove('fa-angle-left');
        toggleIcon.classList.add('fa-angle-right');
        localStorage.setItem('sidebarCollapsed', 'true');
      } else {
        toggleIcon.classList.remove('fa-angle-right');
        toggleIcon.classList.add('fa-angle-left');
        localStorage.setItem('sidebarCollapsed', 'false');
      }
    }
    
    // Mobile Sidebar Toggle
    function toggleMobileSidebar() {
      const sidebar = document.getElementById('sidebar');
      sidebar.classList.toggle('active');
    }
    
    // Load saved preferences
    window.addEventListener('DOMContentLoaded', () => {
      const sidebarCollapsed = localStorage.getItem('sidebarCollapsed');
      if (sidebarCollapsed === 'true') {
        document.getElementById('sidebar').classList.add('collapsed');
        document.getElementById('mainWrapper').classList.add('expanded');
        document.querySelector('.sidebar-toggle i').classList.remove('fa-angle-left');
        document.querySelector('.sidebar-toggle i').classList.add('fa-angle-right');
      }
    });
    
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
