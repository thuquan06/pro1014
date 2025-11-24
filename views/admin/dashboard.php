<?php
/**
 * Dashboard - Trang chủ quản trị
 * Version: 2.0
 */
$stats = $stats ?? [];
?>

<!-- Stats Cards -->
<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-icon blue">
      <i class="fas fa-map-marked-alt"></i>
    </div>
    <div class="stat-details">
      <h4><?= $stats['total_tours'] ?? 0 ?></h4>
      <p>Tổng số Tour</p>
    </div>
  </div>
  
  <div class="stat-card">
    <div class="stat-icon green">
      <i class="fas fa-rocket"></i>
    </div>
    <div class="stat-details">
      <h4><?= $stats['active_tours'] ?? 0 ?></h4>
      <p>Tour đang hoạt động</p>
    </div>
  </div>
  
  <div class="stat-card">
    <div class="stat-icon orange">
      <i class="fas fa-file-invoice"></i>
    </div>
    <div class="stat-details">
      <h4><?= $stats['total_bookings'] ?? 0 ?></h4>
      <p>Đơn đặt tour</p>
    </div>
  </div>
  
  <div class="stat-card">
    <div class="stat-icon red">
      <i class="fas fa-newspaper"></i>
    </div>
    <div class="stat-details">
      <h4><?= $stats['total_blogs'] ?? 0 ?></h4>
      <p>Bài viết</p>
    </div>
  </div>
</div>

<!-- Quick Actions -->
<div class="card">
  <div class="card-header">
    <h3>
      <i class="fas fa-bolt"></i>
      Thao tác nhanh
    </h3>
  </div>
  <div class="card-body">
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
      <a href="?act=admin-tour-create" class="btn btn-primary">
        <i class="fas fa-plus"></i>
        Thêm Tour mới
      </a>
      <a href="?act=blog-create" class="btn btn-success">
        <i class="fas fa-pen"></i>
        Viết bài mới
      </a>
      <a href="?act=hoadon-list" class="btn btn-warning">
        <i class="fas fa-file-invoice"></i>
        Xem đơn hàng
      </a>
      <a href="?act=admin-tours" class="btn" style="background:#6c757d; color:white;">
        <i class="fas fa-list"></i>
        Quản lý Tour
      </a>
    </div>
  </div>
</div>

<!-- Recent Activity -->
<div class="card">
  <div class="card-header">
    <h3>
      <i class="fas fa-history"></i>
      Hoạt động gần đây
    </h3>
    <a href="#" class="btn btn-sm" style="background:#f8f9fa; color:#333;">
      Xem tất cả
    </a>
  </div>
  <div class="card-body">
    <div style="display: flex; flex-direction: column; gap: 15px;">
      <div style="display: flex; align-items: center; gap: 15px; padding: 15px; background: #f8f9fa; border-radius: 8px;">
        <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white;">
          <i class="fas fa-plus"></i>
        </div>
        <div style="flex: 1;">
          <strong>Tour mới được thêm</strong>
          <p style="margin: 0; font-size: 13px; color: #7f8c8d;">Vừa xong</p>
        </div>
        <span style="font-size: 12px; color: #7f8c8d;">
          <?= date('H:i') ?>
        </span>
      </div>
      
      <div style="display: flex; align-items: center; gap: 15px; padding: 15px; background: #f8f9fa; border-radius: 8px;">
        <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); display: flex; align-items: center; justify-content: center; color: white;">
          <i class="fas fa-edit"></i>
        </div>
        <div style="flex: 1;">
          <strong>Cập nhật bài viết</strong>
          <p style="margin: 0; font-size: 13px; color: #7f8c8d;">30 phút trước</p>
        </div>
        <span style="font-size: 12px; color: #7f8c8d;">
          <?= date('H:i', strtotime('-30 minutes')) ?>
        </span>
      </div>
      
      <div style="display: flex; align-items: center; gap: 15px; padding: 15px; background: #f8f9fa; border-radius: 8px;">
        <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); display: flex; align-items: center; justify-content: center; color: white;">
          <i class="fas fa-check"></i>
        </div>
        <div style="flex: 1;">
          <strong>Đơn hàng mới</strong>
          <p style="margin: 0; font-size: 13px; color: #7f8c8d;">1 giờ trước</p>
        </div>
        <span style="font-size: 12px; color: #7f8c8d;">
          <?= date('H:i', strtotime('-1 hour')) ?>
        </span>
      </div>
    </div>
  </div>
</div>

<!-- System Info -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
  <div class="card">
    <div class="card-header">
      <h3>
        <i class="fas fa-info-circle"></i>
        Thông tin hệ thống
      </h3>
    </div>
    <div class="card-body">
      <div style="display: flex; flex-direction: column; gap: 12px;">
        <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #e9ecef;">
          <span style="color: #7f8c8d;">PHP Version:</span>
          <strong><?= phpversion() ?></strong>
        </div>
        <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #e9ecef;">
          <span style="color: #7f8c8d;">Server:</span>
          <strong><?= $_SERVER['SERVER_SOFTWARE'] ?? 'N/A' ?></strong>
        </div>
        <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #e9ecef;">
          <span style="color: #7f8c8d;">Database:</span>
          <strong>MySQL</strong>
        </div>
        <div style="display: flex; justify-content: space-between; padding: 10px 0;">
          <span style="color: #7f8c8d;">Version:</span>
          <strong>2.0</strong>
        </div>
      </div>
    </div>
  </div>
  
  <div class="card">
    <div class="card-header">
      <h3>
        <i class="fas fa-chart-line"></i>
        Thống kê nhanh
      </h3>
    </div>
    <div class="card-body">
      <div style="display: flex; flex-direction: column; gap: 12px;">
        <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #e9ecef;">
          <span style="color: #7f8c8d;">Tour hôm nay:</span>
          <strong style="color: #28a745;">+<?= $stats['today_tours'] ?? 0 ?></strong>
        </div>
        <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #e9ecef;">
          <span style="color: #7f8c8d;">Đơn hàng hôm nay:</span>
          <strong style="color: #17a2b8;">+<?= $stats['today_bookings'] ?? 0 ?></strong>
        </div>
        <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #e9ecef;">
          <span style="color: #7f8c8d;">Bài viết tháng này:</span>
          <strong style="color: #ffc107;">+<?= $stats['month_blogs'] ?? 0 ?></strong>
        </div>
        <div style="display: flex; justify-content: space-between; padding: 10px 0;">
          <span style="color: #7f8c8d;">Doanh thu tháng:</span>
          <strong style="color: #dc3545;"><?= number_format($stats['month_revenue'] ?? 0) ?>đ</strong>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
  @keyframes fadeOut {
    from { opacity: 1; }
    to { opacity: 0; }
  }
</style>
