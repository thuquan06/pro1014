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
  <title>Quản trị</title>
  
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
    
    .logo {
      display: flex;
      align-items: center;
      gap: 12px;
      text-decoration: none;
      transition: transform 0.2s ease;
    }
    
    .logo:hover {
      transform: translateX(2px);
    }
    
    .logo-icon {
      width: 48px;
      height: 48px;
      background: var(--primary-gradient);
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 22px;
      box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
      transition: transform 0.3s ease;
    }
    
    .logo:hover .logo-icon {
      transform: rotate(-5deg) scale(1.05);
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
    
    .topbar-btn {
      width: 44px;
      height: 44px;
      background: var(--bg-light);
      border: none;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: all 0.3s ease;
      color: var(--text-dark);
      position: relative;
    }
    
    .topbar-btn:hover {
      background: var(--primary-gradient);
      color: white;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }
    
    .topbar-btn:active {
      transform: translateY(0);
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
      animation: fadeIn 0.5s ease;
    }
    
    /* Page header styles */
    .page-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 32px;
      padding-bottom: 20px;
      border-bottom: 2px solid var(--border);
    }
    
    .page-header h2 {
      font-size: 28px;
      font-weight: 700;
      color: var(--text-dark);
      margin: 0;
    }
    
    .page-header .actions {
      display: flex;
      gap: 12px;
    }
    
    .page-title {
      font-size: 32px;
      font-weight: 800;
      color: var(--text-dark);
      margin: 0 0 8px 0;
      display: flex;
      align-items: center;
      gap: 12px;
    }
    
    .page-title i {
      color: var(--primary);
      font-size: 28px;
    }
    
    .page-subtitle {
      color: var(--text-light);
      font-size: 15px;
      margin: 0;
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
      padding: 28px;
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
      padding: 8px 16px;
      font-size: 13px;
    }
    
    .btn-outline {
      background: transparent;
      border: 2px solid var(--primary);
      color: var(--primary);
    }
    
    .btn-outline:hover {
      background: var(--primary-gradient);
      color: white;
      border-color: transparent;
    }
    
    /* ===== TABLE ===== */
    .table-responsive {
      overflow-x: auto;
      border-radius: 12px;
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
    
    .stat-details small {
      font-size: 12px;
      color: var(--success);
      font-weight: 600;
      margin-top: 8px;
      display: block;
    }
    
    /* ===== FORM ===== */
    .form-group {
      margin-bottom: 24px;
    }
    
    .form-label {
      display: block;
      margin-bottom: 10px;
      font-weight: 600;
      color: var(--text-dark);
      font-size: 14px;
      display: flex;
      align-items: center;
      gap: 6px;
    }
    
    .form-label .required {
      color: var(--danger);
      font-weight: 700;
    }
    
    .form-control {
      width: 100%;
      padding: 12px 16px;
      border: 2px solid var(--border);
      border-radius: 10px;
      font-size: 14px;
      transition: all 0.3s ease;
      background: var(--bg-white);
      color: var(--text-dark);
      font-family: inherit;
    }
    
    .form-control:hover {
      border-color: var(--primary-light);
    }
    
    .form-control:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
      transform: translateY(-1px);
      background: white;
    }
    
    .form-control::placeholder {
      color: var(--text-muted);
    }
    
    textarea.form-control {
      min-height: 120px;
      resize: vertical;
      line-height: 1.6;
    }
    
    select.form-control {
      cursor: pointer;
      appearance: none;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236b7280' d='M10.293 3.293L6 7.586 1.707 3.293A1 1 0 00.293 4.707l5 5a1 1 0 001.414 0l5-5a1 1 0 10-1.414-1.414z'/%3E%3C/svg%3E");
      background-repeat: no-repeat;
      background-position: right 14px center;
      padding-right: 40px;
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
        display: flex !important;
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
    
    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }
    
    @keyframes slideInRight {
      from {
        opacity: 0;
        transform: translateX(-20px);
      }
      to {
        opacity: 1;
        transform: translateX(0);
      }
    }
    
    /* Page content animation */
    .content-wrapper > * {
      animation: fadeIn 0.5s ease;
    }
    
    /* Smooth scroll */
    html {
      scroll-behavior: smooth;
    }
    
    /* Loading state */
    .loading {
      opacity: 0.6;
      pointer-events: none;
      position: relative;
    }
    
    .loading::after {
      content: '';
      position: absolute;
      top: 50%;
      left: 50%;
      width: 20px;
      height: 20px;
      margin: -10px 0 0 -10px;
      border: 2px solid var(--primary);
      border-top-color: transparent;
      border-radius: 50%;
      animation: spin 0.6s linear infinite;
    }
    
    @keyframes spin {
      to { transform: rotate(360deg); }
    }
    
    /* ===== VOUCHER PAGE STYLES ===== */
    .filter-card {
      background: white;
      border: 1px solid var(--border);
      border-radius: 12px;
      padding: 20px 24px;
      margin-bottom: 24px;
      box-shadow: var(--shadow-sm);
    }
    
    .filter-card form {
      display: flex;
      gap: 12px;
      align-items: flex-end;
      flex-wrap: wrap;
    }
    
    .filter-card input[type="text"],
    .filter-card select {
      flex: 1;
      min-width: 200px;
      padding: 10px 14px;
      border: 2px solid var(--border);
      border-radius: 8px;
      font-size: 14px;
      transition: all 0.3s ease;
      background: var(--bg-white);
      color: var(--text-dark);
      font-family: inherit;
    }
    
    .filter-card input[type="text"]:focus,
    .filter-card select:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }
    
    .modern-table {
      width: 100%;
      border-collapse: collapse;
      background: white;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: var(--shadow-sm);
    }
    
    .modern-table thead {
      background: linear-gradient(135deg, rgba(37, 99, 235, 0.08) 0%, rgba(59, 130, 246, 0.08) 100%);
    }
    
    .modern-table th {
      padding: 16px 20px;
      text-align: left;
      font-weight: 700;
      color: var(--text-dark);
      font-size: 12px;
      text-transform: uppercase;
      letter-spacing: 0.8px;
      border-bottom: 2px solid var(--border);
    }
    
    .modern-table td {
      padding: 16px 20px;
      border-bottom: 1px solid var(--border);
      color: var(--text-dark);
      font-size: 14px;
      vertical-align: middle;
    }
    
    .modern-table tbody tr {
      transition: all 0.2s ease;
    }
    
    .modern-table tbody tr:hover {
      background: linear-gradient(90deg, rgba(37, 99, 235, 0.05) 0%, rgba(37, 99, 235, 0.02) 100%);
    }
    
    .modern-table tbody tr:last-child td {
      border-bottom: none;
    }
    
    /* Badge styles */
    .badge {
      display: inline-block;
      padding: 6px 12px;
      border-radius: 8px;
      font-size: 12px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    
    .badge-success {
      background: linear-gradient(135deg, rgba(16, 185, 129, 0.15) 0%, rgba(16, 185, 129, 0.1) 100%);
      color: #065f46;
      border: 1px solid rgba(16, 185, 129, 0.3);
    }
    
    .badge-secondary {
      background: linear-gradient(135deg, rgba(107, 114, 128, 0.15) 0%, rgba(107, 114, 128, 0.1) 100%);
      color: #374151;
      border: 1px solid rgba(107, 114, 128, 0.3);
    }
    
    .badge-warning {
      background: linear-gradient(135deg, rgba(245, 158, 11, 0.15) 0%, rgba(245, 158, 11, 0.1) 100%);
      color: #92400e;
      border: 1px solid rgba(245, 158, 11, 0.3);
    }
    
    .badge-danger {
      background: linear-gradient(135deg, rgba(239, 68, 68, 0.15) 0%, rgba(239, 68, 68, 0.1) 100%);
      color: #991b1b;
      border: 1px solid rgba(239, 68, 68, 0.3);
    }
    
    .badge-info {
      background: linear-gradient(135deg, rgba(6, 182, 212, 0.15) 0%, rgba(6, 182, 212, 0.1) 100%);
      color: #164e63;
      border: 1px solid rgba(6, 182, 212, 0.3);
    }
    
    /* Button outline variants */
    .btn-outline-primary {
      background: transparent;
      border: 2px solid var(--primary);
      color: var(--primary);
    }
    
    .btn-outline-primary:hover {
      background: var(--primary-gradient);
      color: white;
      border-color: transparent;
    }
    
    .btn-outline-warning {
      background: transparent;
      border: 2px solid var(--warning);
      color: var(--warning);
    }
    
    .btn-outline-warning:hover {
      background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
      color: white;
      border-color: transparent;
    }
    
    .btn-outline-danger {
      background: transparent;
      border: 2px solid var(--danger);
      color: var(--danger);
    }
    
    .btn-outline-danger:hover {
      background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
      color: white;
      border-color: transparent;
    }
    
    .btn-secondary {
      background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
      color: white;
      box-shadow: 0 4px 12px rgba(107, 114, 128, 0.3);
    }
    
    .btn-secondary:hover {
      box-shadow: 0 6px 20px rgba(107, 114, 128, 0.4);
    }
    
    /* Form styles for voucher pages */
    .form-container {
      max-width: 1000px;
    }
    
    .form-card {
      background: white;
      border: 1px solid var(--border);
      border-radius: 16px;
      margin-bottom: 24px;
      box-shadow: var(--shadow-sm);
      overflow: hidden;
    }
    
    .form-card .card-header {
      padding: 20px 24px;
      border-bottom: 1px solid var(--border);
      background: linear-gradient(135deg, rgba(37, 99, 235, 0.02) 0%, rgba(59, 130, 246, 0.02) 100%);
    }
    
    .form-card .card-header h3 {
      font-size: 18px;
      font-weight: 700;
      color: var(--text-dark);
      margin: 0;
    }
    
    .form-row {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
      padding: 24px;
    }
    
    .form-group-modern {
      display: flex;
      flex-direction: column;
    }
    
    .form-group-modern label {
      margin-bottom: 8px;
      font-weight: 600;
      color: var(--text-dark);
      font-size: 14px;
      display: flex;
      align-items: center;
      gap: 4px;
    }
    
    .form-group-modern label .required {
      color: var(--danger);
      font-weight: 700;
    }
    
    .form-group-modern input[type="text"],
    .form-group-modern input[type="number"],
    .form-group-modern input[type="date"],
    .form-group-modern select {
      padding: 12px 16px;
      border: 2px solid var(--border);
      border-radius: 10px;
      font-size: 14px;
      transition: all 0.3s ease;
      background: var(--bg-white);
      color: var(--text-dark);
      font-family: inherit;
    }
    
    .form-group-modern input[type="text"]:focus,
    .form-group-modern input[type="number"]:focus,
    .form-group-modern input[type="date"]:focus,
    .form-group-modern select:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
      transform: translateY(-1px);
    }
    
    .form-group-modern input::placeholder {
      color: var(--text-muted);
    }
    
    .form-group-modern select {
      cursor: pointer;
      appearance: none;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236b7280' d='M10.293 3.293L6 7.586 1.707 3.293A1 1 0 00.293 4.707l5 5a1 1 0 001.414 0l5-5a1 1 0 10-1.414-1.414z'/%3E%3C/svg%3E");
      background-repeat: no-repeat;
      background-position: right 14px center;
      padding-right: 40px;
    }
    
    .form-actions {
      display: flex;
      gap: 12px;
      justify-content: flex-end;
      padding: 24px;
      border-top: 1px solid var(--border);
      background: var(--bg-light);
    }
    
    .btn-cancel {
      padding: 12px 24px;
      border: 2px solid var(--border);
      border-radius: 10px;
      font-size: 14px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      text-decoration: none;
      color: var(--text-dark);
      background: white;
    }
    
    .btn-cancel:hover {
      background: var(--bg-light);
      border-color: var(--text-light);
      transform: translateY(-2px);
      box-shadow: var(--shadow-sm);
    }
    
    .btn-submit {
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
      background: var(--primary-gradient);
      color: white;
      box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }
    
    .btn-submit:hover {
      box-shadow: 0 6px 20px rgba(37, 99, 235, 0.4);
      transform: translateY(-2px);
    }
    
    .btn-submit:active {
      transform: translateY(0);
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
          <a href="?act=admin-departure-plans" class="nav-link">
            <i class="fas fa-calendar-alt"></i>
            <span>Quản lý lịch trình</span>
          </a>
        </div>
        <div class="nav-item">
          <a href="?act=admin-bookings" class="nav-link">
            <i class="fas fa-calendar-check"></i>
            <span>Quản lý Booking</span>
          </a>
        </div>
        <div class="nav-item">
          <a href="?act=admin-guides" class="nav-link">
            <i class="fas fa-user-tie"></i>
            <span>Hướng dẫn viên</span>
          </a>
        </div>
        <div class="nav-item">
          <a href="?act=admin-assignments" class="nav-link">
            <i class="fas fa-list-check"></i>
            <span>Danh sách phân công HDV</span>
          </a>
        </div>
        <div class="nav-item">
          <a href="?act=admin-attendance-list" class="nav-link">
            <i class="fas fa-clipboard-check"></i>
            <span>Điểm danh</span>
          </a>
        </div>
        <div class="nav-item">
          <a href="?act=admin-journals" class="nav-link">
            <i class="fas fa-book"></i>
            <span>Nhật ký tour</span>
          </a>
        </div>
      </div>
      
      <div class="nav-section">
        <div class="nav-section-title">Danh Sách</div>
        <div class="nav-item">
          <a href="?act=admin-services" class="nav-link">
            <i class="fas fa-concierge-bell"></i>
            <span>Danh sách Dịch vụ</span>
          </a>
        </div>
        <div class="nav-item">
          <a href="?act=admin-categories-tags" class="nav-link">
            <i class="fas fa-tags"></i>
            <span>Danh sách Phân loại & Tags</span>
          </a>
        </div>
        <div class="nav-item">
          <a href="?act=admin-vouchers" class="nav-link">
            <i class="fas fa-ticket-alt"></i>
            <span>Danh sách voucher</span>
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
        </div>
      </div>
      
      <div class="nav-section">
        <div class="nav-section-title">Quản lý Hóa đơn</div>
        <div class="nav-item">
          <a href="?act=hoadon-list" class="nav-link">
            <i class="fas fa-file-invoice-dollar"></i>
            <span>Danh sách Hóa đơn</span>
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