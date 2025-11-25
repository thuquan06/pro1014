<?php
/**
 * Admin Layout - Giao diện quản trị SIÊU HIỆN ĐẠI
 * Version: 3.0 - Ultra Modern
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
  <title>Quản trị - StarVel Travel</title>
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  
  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
  
  <style>
    :root {
      --primary: #6366f1;
      --primary-dark: #4f46e5;
      --secondary: #8b5cf6;
      --success: #10b981;
      --danger: #ef4444;
      --warning: #f59e0b;
      --info: #06b6d4;
      --dark: #1e293b;
      --light: #f1f5f9;
      --sidebar-width: 280px;
      --sidebar-collapsed: 80px;
      --topbar-height: 70px;
      --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    [data-theme="dark"] {
      --bg-primary: #0f172a;
      --bg-secondary: #1e293b;
      --bg-card: #1e293b;
      --text-primary: #f1f5f9;
      --text-secondary: #94a3b8;
      --border-color: #334155;
    }
    
    [data-theme="light"] {
      --bg-primary: #f8fafc;
      --bg-secondary: #ffffff;
      --bg-card: #ffffff;
      --text-primary: #0f172a;
      --text-secondary: #64748b;
      --border-color: #e2e8f0;
    }
    
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    
    body {
      font-family: 'Poppins', -apple-system, BlinkMacSystemFont, sans-serif;
      background: var(--bg-primary);
      color: var(--text-primary);
      line-height: 1.6;
      transition: var(--transition);
    }
    
    /* ===== SIDEBAR ===== */
    .sidebar {
      position: fixed;
      left: 0;
      top: 0;
      width: var(--sidebar-width);
      height: 100vh;
      background: linear-gradient(180deg, #667eea 0%, #764ba2 100%);
      box-shadow: 4px 0 24px rgba(0,0,0,0.12);
      z-index: 1000;
      overflow: hidden;
      transition: var(--transition);
    }
    
    .sidebar.collapsed {
      width: var(--sidebar-collapsed);
    }
    
    .sidebar.collapsed .sidebar-logo-text,
    .sidebar.collapsed .user-details,
    .sidebar.collapsed .nav-text,
    .sidebar.collapsed .nav-section-title {
      opacity: 0;
      visibility: hidden;
      width: 0;
    }
    
    .sidebar.collapsed .sidebar-logo {
      justify-content: center;
    }
    
    .sidebar.collapsed .user-info {
      justify-content: center;
    }
    
    .sidebar.collapsed .nav-link {
      justify-content: center;
      padding: 12px 10px;
    }
    
    .sidebar-header {
      padding: 25px 20px;
      background: rgba(0,0,0,0.2);
      border-bottom: 1px solid rgba(255,255,255,0.15);
      position: relative;
    }
    
    .sidebar-logo {
      display: flex;
      align-items: center;
      gap: 15px;
      color: white;
      text-decoration: none;
      transition: var(--transition);
    }
    
    .sidebar-logo i {
      font-size: 36px;
      color: #fbbf24;
      text-shadow: 0 2px 10px rgba(251, 191, 36, 0.5);
    }
    
    .sidebar-logo-text {
      transition: var(--transition);
      white-space: nowrap;
    }
    
    .sidebar-logo-text h2 {
      font-size: 24px;
      font-weight: 800;
      margin: 0;
      color: white;
      letter-spacing: -0.5px;
    }
    
    .sidebar-logo-text p {
      font-size: 11px;
      color: rgba(255,255,255,0.7);
      margin: 0;
      text-transform: uppercase;
      letter-spacing: 2px;
      font-weight: 500;
    }
    
    .sidebar-toggle {
      position: absolute;
      right: -15px;
      top: 50%;
      transform: translateY(-50%);
      width: 30px;
      height: 30px;
      background: white;
      border: none;
      border-radius: 50%;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 2px 8px rgba(0,0,0,0.15);
      transition: var(--transition);
      color: var(--primary);
    }
    
    .sidebar-toggle:hover {
      transform: translateY(-50%) scale(1.1);
      box-shadow: 0 4px 12px rgba(0,0,0,0.2);
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
      transition: var(--transition);
    }
    
    .user-avatar {
      min-width: 50px;
      width: 50px;
      height: 50px;
      border-radius: 15px;
      background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 22px;
      font-weight: 700;
      border: 3px solid rgba(255,255,255,0.3);
      box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }
    
    .user-details {
      transition: var(--transition);
      white-space: nowrap;
      overflow: hidden;
    }
    
    .user-details h4 {
      font-size: 15px;
      color: white;
      margin: 0;
      font-weight: 600;
    }
    
    .user-details p {
      font-size: 12px;
      color: rgba(255,255,255,0.7);
      margin: 0;
    }
    
    .sidebar-nav {
      padding: 20px 0;
      overflow-y: auto;
      height: calc(100vh - 200px);
    }
    
    .sidebar-nav::-webkit-scrollbar {
      width: 6px;
    }
    
    .sidebar-nav::-webkit-scrollbar-track {
      background: rgba(255,255,255,0.05);
    }
    
    .sidebar-nav::-webkit-scrollbar-thumb {
      background: rgba(255,255,255,0.2);
      border-radius: 3px;
    }
    
    .nav-section {
      margin-bottom: 15px;
    }
    
    .nav-section-title {
      padding: 15px 20px 8px;
      font-size: 11px;
      font-weight: 700;
      color: rgba(255,255,255,0.6);
      text-transform: uppercase;
      letter-spacing: 1.5px;
      transition: var(--transition);
      white-space: nowrap;
    }
    
    .nav-item {
      margin: 3px 12px;
    }
    
    .nav-link {
      display: flex;
      align-items: center;
      gap: 15px;
      padding: 14px 18px;
      color: rgba(255,255,255,0.85);
      text-decoration: none;
      border-radius: 12px;
      transition: var(--transition);
      font-size: 14px;
      font-weight: 500;
      position: relative;
      overflow: hidden;
    }
    
    .nav-link::before {
      content: '';
      position: absolute;
      left: 0;
      top: 0;
      height: 100%;
      width: 4px;
      background: #fbbf24;
      transform: translateX(-4px);
      transition: var(--transition);
    }
    
    .nav-link:hover {
      background: rgba(255,255,255,0.15);
      color: white;
      transform: translateX(5px);
    }
    
    .nav-link:hover::before {
      transform: translateX(0);
    }
    
    .nav-link.active {
      background: rgba(255,255,255,0.25);
      color: white;
      box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }
    
    .nav-link.active::before {
      transform: translateX(0);
    }
    
    .nav-link i {
      min-width: 24px;
      text-align: center;
      font-size: 18px;
    }
    
    .nav-text {
      transition: var(--transition);
      white-space: nowrap;
    }
    
    .nav-link .badge {
      margin-left: auto;
      background: #ef4444;
      color: white;
      padding: 4px 10px;
      border-radius: 20px;
      font-size: 11px;
      font-weight: 700;
      animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
      0%, 100% { transform: scale(1); }
      50% { transform: scale(1.05); }
    }
    
    /* ===== MAIN CONTENT ===== */
    .main-content {
      margin-left: var(--sidebar-width);
      min-height: 100vh;
      transition: var(--transition);
      background: var(--bg-primary);
    }
    
    .main-content.expanded {
      margin-left: var(--sidebar-collapsed);
    }
    
    .top-bar {
      background: var(--bg-secondary);
      padding: 0 35px;
      height: var(--topbar-height);
      display: flex;
      align-items: center;
      justify-content: space-between;
      box-shadow: 0 2px 16px rgba(0,0,0,0.08);
      position: sticky;
      top: 0;
      z-index: 999;
      border-bottom: 1px solid var(--border-color);
    }
    
    .top-bar-left {
      display: flex;
      flex-direction: column;
    }
    
    .top-bar-left h1 {
      font-size: 28px;
      font-weight: 700;
      color: var(--text-primary);
      margin: 0;
      background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }
    
    .top-bar-left p {
      font-size: 13px;
      color: var(--text-secondary);
      margin: 0;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    
    .top-bar-right {
      display: flex;
      align-items: center;
      gap: 15px;
    }
    
    .theme-toggle {
      width: 45px;
      height: 45px;
      border-radius: 12px;
      background: var(--bg-primary);
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: var(--transition);
      color: var(--text-primary);
      border: 2px solid var(--border-color);
    }
    
    .theme-toggle:hover {
      transform: rotate(180deg) scale(1.1);
      background: var(--primary);
      color: white;
      border-color: var(--primary);
    }
    
    .top-bar-icon {
      position: relative;
      width: 45px;
      height: 45px;
      border-radius: 12px;
      background: var(--bg-primary);
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: var(--transition);
      color: var(--text-primary);
      border: 2px solid var(--border-color);
    }
    
    .top-bar-icon:hover {
      background: var(--primary);
      color: white;
      transform: translateY(-2px);
      border-color: var(--primary);
    }
    
    .top-bar-icon .badge {
      position: absolute;
      top: -8px;
      right: -8px;
      background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
      color: white;
      border-radius: 20px;
      padding: 3px 8px;
      font-size: 11px;
      font-weight: 700;
      box-shadow: 0 2px 8px rgba(239, 68, 68, 0.4);
    }
    
    .user-menu {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 8px 18px;
      border-radius: 30px;
      background: var(--bg-primary);
      cursor: pointer;
      transition: var(--transition);
      border: 2px solid var(--border-color);
    }
    
    .user-menu:hover {
      background: var(--primary);
      border-color: var(--primary);
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(99, 102, 241, 0.3);
    }
    
    .user-menu:hover .user-menu-avatar,
    .user-menu:hover .user-menu-name,
    .user-menu:hover i {
      color: white !important;
    }
    
    .user-menu-avatar {
      width: 38px;
      height: 38px;
      border-radius: 12px;
      background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-weight: 700;
      font-size: 16px;
    }
    
    .user-menu-name {
      font-size: 14px;
      font-weight: 600;
      color: var(--text-primary);
      transition: var(--transition);
    }
    
    .content-wrapper {
      padding: 35px;
      animation: fadeIn 0.5s ease-in;
    }
    
    /* ===== BREADCRUMB ===== */
    .breadcrumb-custom {
      background: var(--bg-card);
      padding: 18px 24px;
      border-radius: 16px;
      margin-bottom: 30px;
      box-shadow: 0 2px 12px rgba(0,0,0,0.06);
      display: flex;
      align-items: center;
      gap: 12px;
      font-size: 14px;
      border: 1px solid var(--border-color);
    }
    
    .breadcrumb-custom a {
      color: var(--primary);
      text-decoration: none;
      transition: var(--transition);
      font-weight: 500;
    }
    
    .breadcrumb-custom a:hover {
      color: var(--primary-dark);
      text-decoration: underline;
    }
    
    .breadcrumb-custom i {
      color: var(--text-secondary);
      font-size: 12px;
    }
    
    .breadcrumb-custom .active {
      color: var(--text-primary);
      font-weight: 600;
    }
    
    /* ===== ALERTS ===== */
    .alert {
      padding: 18px 24px;
      border-radius: 16px;
      margin-bottom: 25px;
      display: flex;
      align-items: center;
      gap: 15px;
      animation: slideInDown 0.4s ease-out;
      box-shadow: 0 4px 16px rgba(0,0,0,0.1);
      border-left: 5px solid;
    }
    
    .alert i {
      font-size: 24px;
    }
    
    .alert-success {
      background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
      border-left-color: var(--success);
      color: #065f46;
    }
    
    .alert-success i {
      color: var(--success);
    }
    
    .alert-error {
      background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
      border-left-color: var(--danger);
      color: #991b1b;
    }
    
    .alert-error i {
      color: var(--danger);
    }
    
    .alert-info {
      background: linear-gradient(135deg, #cffafe 0%, #a5f3fc 100%);
      border-left-color: var(--info);
      color: #164e63;
    }
    
    .alert-warning {
      background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
      border-left-color: var(--warning);
      color: #78350f;
    }
    
    /* ===== CARDS ===== */
    .card {
      background: var(--bg-card);
      border-radius: 20px;
      box-shadow: 0 4px 16px rgba(0,0,0,0.08);
      margin-bottom: 30px;
      overflow: hidden;
      transition: var(--transition);
      border: 1px solid var(--border-color);
    }
    
    .card:hover {
      box-shadow: 0 12px 32px rgba(0,0,0,0.12);
      transform: translateY(-4px);
    }
    
    .card-header {
      padding: 24px 30px;
      border-bottom: 2px solid var(--border-color);
      display: flex;
      align-items: center;
      justify-content: space-between;
      background: linear-gradient(135deg, rgba(99, 102, 241, 0.05) 0%, rgba(139, 92, 246, 0.05) 100%);
    }
    
    .card-header h3 {
      font-size: 20px;
      font-weight: 700;
      color: var(--text-primary);
      margin: 0;
      display: flex;
      align-items: center;
      gap: 12px;
    }
    
    .card-header h3 i {
      color: var(--primary);
      font-size: 24px;
    }
    
    .card-body {
      padding: 30px;
    }
    
    /* ===== BUTTONS ===== */
    .btn {
      padding: 12px 28px;
      border: none;
      border-radius: 12px;
      font-size: 14px;
      font-weight: 600;
      cursor: pointer;
      transition: var(--transition);
      display: inline-flex;
      align-items: center;
      gap: 10px;
      text-decoration: none;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .btn:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 24px rgba(0,0,0,0.2);
    }
    
    .btn-primary {
      background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
      color: white;
    }
    
    .btn-success {
      background: linear-gradient(135deg, #10b981 0%, #059669 100%);
      color: white;
    }
    
    .btn-danger {
      background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
      color: white;
    }
    
    .btn-warning {
      background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
      color: white;
    }
    
    .btn-sm {
      padding: 8px 18px;
      font-size: 13px;
    }
    
    /* ===== TABLE ===== */
    .table-responsive {
      overflow-x: auto;
      border-radius: 16px;
    }
    
    table {
      width: 100%;
      border-collapse: collapse;
    }
    
    table thead {
      background: linear-gradient(135deg, rgba(99, 102, 241, 0.1) 0%, rgba(139, 92, 246, 0.1) 100%);
    }
    
    table th {
      padding: 18px;
      text-align: left;
      font-weight: 700;
      color: var(--text-primary);
      font-size: 13px;
      text-transform: uppercase;
      letter-spacing: 0.8px;
      border-bottom: 3px solid var(--primary);
    }
    
    table td {
      padding: 18px;
      border-bottom: 1px solid var(--border-color);
      color: var(--text-secondary);
    }
    
    table tr:hover {
      background: rgba(99, 102, 241, 0.05);
    }
    
    /* ===== ANIMATIONS ===== */
    @keyframes slideInDown {
      from {
        opacity: 0;
        transform: translateY(-30px);
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
    
    @keyframes fadeOut {
      from { opacity: 1; }
      to { opacity: 0; }
    }
    
    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
      .sidebar {
        transform: translateX(-100%);
      }
      
      .sidebar.active {
        transform: translateX(0);
      }
      
      .main-content,
      .main-content.expanded {
        margin-left: 0;
      }
      
      .top-bar {
        padding: 0 20px;
      }
      
      .content-wrapper {
        padding: 20px;
      }
      
      .mobile-toggle {
        display: block;
      }
    }
    
    .mobile-toggle {
      display: none;
      width: 45px;
      height: 45px;
      border-radius: 12px;
      background: var(--primary);
      border: none;
      color: white;
      cursor: pointer;
      font-size: 20px;
    }
    
    /* ===== FORM ELEMENTS ===== */
    .form-group {
      margin-bottom: 24px;
    }
    
    .form-label {
      display: block;
      margin-bottom: 10px;
      font-weight: 600;
      color: var(--text-primary);
      font-size: 14px;
    }
    
    .form-control {
      width: 100%;
      padding: 14px 18px;
      border: 2px solid var(--border-color);
      border-radius: 12px;
      font-size: 14px;
      transition: var(--transition);
      background: var(--bg-card);
      color: var(--text-primary);
    }
    
    .form-control:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    }
    
    /* ===== STATS CARDS ===== */
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 25px;
      margin-bottom: 35px;
    }
    
    .stat-card {
      background: var(--bg-card);
      border-radius: 20px;
      padding: 30px;
      box-shadow: 0 4px 16px rgba(0,0,0,0.08);
      display: flex;
      align-items: center;
      gap: 25px;
      transition: var(--transition);
      border: 1px solid var(--border-color);
      position: relative;
      overflow: hidden;
    }
    
    .stat-card::before {
      content: '';
      position: absolute;
      top: 0;
      right: 0;
      width: 100px;
      height: 100px;
      background: linear-gradient(135deg, rgba(99, 102, 241, 0.1) 0%, rgba(139, 92, 246, 0.1) 100%);
      border-radius: 0 0 0 100%;
    }
    
    .stat-card:hover {
      transform: translateY(-8px);
      box-shadow: 0 16px 40px rgba(0,0,0,0.15);
    }
    
    .stat-icon {
      min-width: 70px;
      width: 70px;
      height: 70px;
      border-radius: 18px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 32px;
      color: white;
      box-shadow: 0 8px 24px rgba(0,0,0,0.2);
    }
    
    .stat-icon.blue {
      background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
    }
    
    .stat-icon.green {
      background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }
    
    .stat-icon.orange {
      background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    }
    
    .stat-icon.red {
      background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    }
    
    .stat-icon.purple {
      background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
    }
    
    .stat-icon.cyan {
      background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
    }
    
    .stat-details {
      flex: 1;
    }
    
    .stat-details h4 {
      font-size: 32px;
      font-weight: 800;
      color: var(--text-primary);
      margin: 0 0 5px 0;
      line-height: 1;
    }
    
    .stat-details p {
      font-size: 14px;
      color: var(--text-secondary);
      margin: 0;
      font-weight: 500;
    }
    
    .stat-details small {
      font-size: 12px;
      color: var(--success);
      font-weight: 600;
      display: flex;
      align-items: center;
      gap: 5px;
      margin-top: 8px;
    }
  </style>
</head>
<body data-theme="light">
  
  <!-- ===== SIDEBAR ===== -->
  <aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
      <a href="?act=admin" class="sidebar-logo">
        <i class="fas fa-plane-departure"></i>
        <div class="sidebar-logo-text">
          <h2>StarVel</h2>
          <p>Travel Admin</p>
        </div>
      </a>
      <button class="sidebar-toggle" onclick="toggleSidebar()">
        <i class="fas fa-angle-left"></i>
      </button>
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
            <span class="nav-text">Dashboard</span>
          </a>
        </div>
      </div>
      
      <div class="nav-section">
        <div class="nav-section-title">Quản lý Tour</div>
        <div class="nav-item">
          <a href="?act=admin-tours" class="nav-link">
            <i class="fas fa-map-marked-alt"></i>
            <span class="nav-text">Danh sách Tour</span>
          </a>
        </div>
        <div class="nav-item">
          <a href="?act=admin-tour-create" class="nav-link">
            <i class="fas fa-plus-circle"></i>
            <span class="nav-text">Thêm Tour mới</span>
          </a>
        </div>
        <div class="nav-item">
          <a href="?act=tour-publish-dashboard" class="nav-link">
            <i class="fas fa-rocket"></i>
            <span class="nav-text">Xuất bản Tour</span>
          </a>
        </div>
      </div>
      
      <div class="nav-section">
        <div class="nav-section-title">Nội dung</div>
        <div class="nav-item">
          <a href="?act=blog-list" class="nav-link">
            <i class="fas fa-newspaper"></i>
            <span class="nav-text">Bài viết</span>
          </a>
        </div>
        <div class="nav-item">
          <a href="?act=province-list" class="nav-link">
            <i class="fas fa-map-marker-alt"></i>
            <span class="nav-text">Tỉnh thành</span>
          </a>
        </div>
      </div>
      
      <div class="nav-section">
        <div class="nav-section-title">Đơn hàng</div>
        <div class="nav-item">
          <a href="?act=hoadon-list" class="nav-link">
            <i class="fas fa-file-invoice-dollar"></i>
            <span class="nav-text">Hóa đơn</span>
            <span class="badge">NEW</span>
          </a>
        </div>
      </div>
      
      <div class="nav-section">
        <div class="nav-section-title">Hệ thống</div>
        <div class="nav-item">
          <a href="?act=logout" class="nav-link" onclick="return confirm('Bạn có chắc muốn đăng xuất?')">
            <i class="fas fa-sign-out-alt"></i>
            <span class="nav-text">Đăng xuất</span>
          </a>
        </div>
      </div>
    </nav>
  </aside>
  
  <!-- ===== MAIN CONTENT ===== -->
  <main class="main-content" id="mainContent">
    <div class="top-bar">
      <button class="mobile-toggle" onclick="toggleMobileSidebar()">
        <i class="fas fa-bars"></i>
      </button>
      
      <div class="top-bar-left">
        <h1>Chào mừng trở lại! 👋</h1>
        <p>
          <i class="far fa-calendar"></i>
          <?php
          $days = ['Chủ Nhật', 'Thứ Hai', 'Thứ Ba', 'Thứ Tư', 'Thứ Năm', 'Thứ Sáu', 'Thứ Bảy'];
          echo $days[date('w')] . ', ' . date('d/m/Y');
          ?>
        </p>
      </div>
      
      <div class="top-bar-right">
        <div class="theme-toggle" onclick="toggleTheme()" title="Chuyển chế độ sáng/tối">
          <i class="fas fa-moon"></i>
        </div>
        
        <div class="top-bar-icon" title="Thông báo">
          <i class="fas fa-bell"></i>
          <span class="badge">3</span>
        </div>
        
        <div class="top-bar-icon" title="Tin nhắn">
          <i class="fas fa-envelope"></i>
          <span class="badge">5</span>
        </div>
        
        <div class="user-menu" onclick="window.location='?act=logout'" title="Đăng xuất">
          <div class="user-menu-avatar">
            <?= strtoupper(substr($_SESSION['alogin'] ?? 'A', 0, 1)) ?>
          </div>
          <span class="user-menu-name"><?= htmlspecialchars($_SESSION['alogin'] ?? 'Admin') ?></span>
          <i class="fas fa-chevron-down" style="font-size:12px; color:var(--text-secondary);"></i>
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
    // Sidebar Toggle
    function toggleSidebar() {
      const sidebar = document.getElementById('sidebar');
      const mainContent = document.getElementById('mainContent');
      const toggleIcon = document.querySelector('.sidebar-toggle i');
      
      sidebar.classList.toggle('collapsed');
      mainContent.classList.toggle('expanded');
      
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
    
    // Theme Toggle
    function toggleTheme() {
      const body = document.body;
      const themeIcon = document.querySelector('.theme-toggle i');
      
      if (body.getAttribute('data-theme') === 'light') {
        body.setAttribute('data-theme', 'dark');
        themeIcon.classList.remove('fa-moon');
        themeIcon.classList.add('fa-sun');
        localStorage.setItem('theme', 'dark');
      } else {
        body.setAttribute('data-theme', 'light');
        themeIcon.classList.remove('fa-sun');
        themeIcon.classList.add('fa-moon');
        localStorage.setItem('theme', 'light');
      }
    }
    
    // Load saved preferences
    window.addEventListener('DOMContentLoaded', () => {
      // Load theme
      const savedTheme = localStorage.getItem('theme');
      if (savedTheme === 'dark') {
        document.body.setAttribute('data-theme', 'dark');
        document.querySelector('.theme-toggle i').classList.remove('fa-moon');
        document.querySelector('.theme-toggle i').classList.add('fa-sun');
      }
      
      // Load sidebar state
      const sidebarCollapsed = localStorage.getItem('sidebarCollapsed');
      if (sidebarCollapsed === 'true') {
        document.getElementById('sidebar').classList.add('collapsed');
        document.getElementById('mainContent').classList.add('expanded');
        document.querySelector('.sidebar-toggle i').classList.remove('fa-angle-left');
        document.querySelector('.sidebar-toggle i').classList.add('fa-angle-right');
      }
    });
    
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
