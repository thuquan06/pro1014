<?php
/**
 * Admin Dashboard - MINIMALIST CLEAN DESIGN
 * Version: 5.0 - Simple & Clean
 * Updated: 2025-11-25
 */

// Lấy số liệu
$cnt1 = $stats['cnt1'] ?? 0;     // Hóa đơn
$ks   = $stats['ks']   ?? 0;     // Khách sạn
$cnt2 = $stats['cnt2'] ?? 0;     // Góp ý
$goi  = $stats['goi']  ?? 0;     // Tour
$cnt5 = $stats['cnt5'] ?? 0;     // Trợ giúp
$blog = $stats['blog'] ?? 0;     // Blog
?>

<style>
  .page-title {
    font-size: 28px;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0 0 24px 0;
  }
  
  .dashboard-grid {
    display: grid;
    grid-template-columns: repeat(12, 1fr);
    gap: 24px;
    margin-bottom: 32px;
  }
  
  .col-12 { grid-column: span 12; }
  .col-8 { grid-column: span 8; }
  .col-4 { grid-column: span 4; }
  .col-6 { grid-column: span 6; }
  
  @media (max-width: 1024px) {
    .col-8, .col-4, .col-6 { grid-column: span 12; }
  }
  
  .quick-actions {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 16px;
  }
  
  .quick-action {
    background: white;
    border: 1px solid var(--border);
    border-radius: 10px;
    padding: 20px;
    text-align: center;
    text-decoration: none;
    transition: all 0.2s;
  }
  
  .quick-action:hover {
    border-color: var(--primary);
    box-shadow: 0 2px 8px rgba(37, 99, 235, 0.1);
  }
  
  .action-icon {
    width: 48px;
    height: 48px;
    margin: 0 auto 12px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    background: var(--bg-light);
    color: var(--primary);
  }
  
  .quick-action:hover .action-icon {
    background: var(--primary);
    color: white;
  }
  
  .action-text {
    font-size: 14px;
    font-weight: 600;
    color: var(--text-dark);
  }
  
  .chart-container {
    position: relative;
    height: 320px;
    padding: 10px;
  }
  
  .activity-list {
    max-height: 420px;
    overflow-y: auto;
  }
  
  .activity-list::-webkit-scrollbar {
    width: 5px;
  }
  
  .activity-list::-webkit-scrollbar-thumb {
    background: var(--border);
    border-radius: 10px;
  }
  
  .activity-item {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    padding: 16px 0;
    border-bottom: 1px solid var(--border);
  }
  
  .activity-item:last-child {
    border-bottom: none;
  }
  
  .activity-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    flex-shrink: 0;
  }
  
  .activity-icon.blue {
    background: #dbeafe;
    color: var(--primary);
  }
  
  .activity-icon.green {
    background: #d1fae5;
    color: var(--success);
  }
  
  .activity-icon.orange {
    background: #fed7aa;
    color: var(--warning);
  }
  
  .activity-icon.red {
    background: #fee2e2;
    color: var(--danger);
  }
  
  .activity-content {
    flex: 1;
    min-width: 0;
  }
  
  .activity-title {
    font-size: 14px;
    font-weight: 600;
    color: var(--text-dark);
    margin: 0 0 4px 0;
  }
  
  .activity-desc {
    font-size: 13px;
    color: var(--text-light);
    margin: 0;
  }
  
  .activity-time {
    font-size: 12px;
    color: var(--text-light);
    white-space: nowrap;
  }
  
  .tour-list {
    list-style: none;
    padding: 0;
    margin: 0;
  }
  
  .tour-item {
    padding: 16px 0;
    border-bottom: 1px solid var(--border);
  }
  
  .tour-item:last-child {
    border-bottom: none;
  }
  
  .tour-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
  }
  
  .tour-name {
    font-size: 14px;
    font-weight: 600;
    color: var(--text-dark);
  }
  
  .tour-percent {
    font-size: 14px;
    font-weight: 700;
    color: var(--primary);
  }
  
  .progress-bar {
    height: 6px;
    background: var(--bg-light);
    border-radius: 10px;
    overflow: hidden;
  }
  
  .progress-fill {
    height: 100%;
    background: var(--primary);
    border-radius: 10px;
    transition: width 1s ease-out;
  }
  
  .empty-state {
    text-align: center;
    padding: 40px 20px;
    color: var(--text-light);
  }
  
  .empty-state i {
    font-size: 48px;
    margin-bottom: 16px;
    opacity: 0.5;
  }
  
  .empty-state p {
    margin: 0;
    font-size: 14px;
  }
</style>

<!-- Stats Cards -->
<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-icon blue">
      <i class="fas fa-file-invoice-dollar"></i>
    </div>
    <div class="stat-details">
      <h4><?= number_format($cnt1) ?></h4>
      <p>Tổng hóa đơn</p>
      <small>↑ 12% so với tháng trước</small>
    </div>
  </div>
  
  <div class="stat-card">
    <div class="stat-icon green">
      <i class="fas fa-map-marked-alt"></i>
    </div>
    <div class="stat-details">
      <h4><?= number_format($goi) ?></h4>
      <p>Tour du lịch</p>
      <small>↑ 8% so với tháng trước</small>
    </div>
  </div>
  
  <div class="stat-card">
    <div class="stat-icon orange">
      <i class="fas fa-newspaper"></i>
    </div>
    <div class="stat-details">
      <h4><?= number_format($blog) ?></h4>
      <p>Bài viết</p>
      <small>↑ 5% so với tháng trước</small>
    </div>
  </div>
  
  <div class="stat-card">
    <div class="stat-icon red">
      <i class="fas fa-hotel"></i>
    </div>
    <div class="stat-details">
      <h4><?= number_format($ks) ?></h4>
      <p>Khách sạn</p>
      <small>Không đổi</small>
    </div>
  </div>
  
  <div class="stat-card">
    <div class="stat-icon purple">
      <i class="fas fa-comments"></i>
    </div>
    <div class="stat-details">
      <h4><?= number_format($cnt2) ?></h4>
      <p>Góp ý</p>
      <small>↑ 15% so với tháng trước</small>
    </div>
  </div>
  
  <div class="stat-card">
    <div class="stat-icon cyan">
      <i class="fas fa-life-ring"></i>
    </div>
    <div class="stat-details">
      <h4><?= number_format($cnt5) ?></h4>
      <p>Yêu cầu hỗ trợ</p>
      <small style="color: var(--danger);">↓ 3% so với tháng trước</small>
    </div>
  </div>
</div>

<!-- Quick Actions -->
<div class="card">
  <div class="card-header">
    <h3><i class="fas fa-bolt"></i> Thao tác nhanh</h3>
  </div>
  <div class="card-body">
    <div class="quick-actions">
      <a href="<?= BASE_URL ?>?act=admin-tour-create" class="quick-action">
        <div class="action-icon">
          <i class="fas fa-plus-circle"></i>
        </div>
        <div class="action-text">Thêm Tour mới</div>
      </a>
      
      <a href="<?= BASE_URL ?>?act=blog-create" class="quick-action">
        <div class="action-icon">
          <i class="fas fa-pen"></i>
        </div>
        <div class="action-text">Viết bài mới</div>
      </a>
      
      <a href="<?= BASE_URL ?>?act=hoadon-list" class="quick-action">
        <div class="action-icon">
          <i class="fas fa-file-invoice"></i>
        </div>
        <div class="action-text">Quản lý hóa đơn</div>
      </a>
      
      <a href="<?= BASE_URL ?>?act=province-create" class="quick-action">
        <div class="action-icon">
          <i class="fas fa-map-marker-alt"></i>
        </div>
        <div class="action-text">Thêm địa điểm</div>
      </a>
      
      <a href="<?= BASE_URL ?>?act=admin-tours" class="quick-action">
        <div class="action-icon">
          <i class="fas fa-list"></i>
        </div>
        <div class="action-text">Xem tất cả tour</div>
      </a>
      
      <a href="<?= BASE_URL ?>?act=blog-list" class="quick-action">
        <div class="action-icon">
          <i class="fas fa-newspaper"></i>
        </div>
        <div class="action-text">Danh sách bài viết</div>
      </a>
    </div>
  </div>
</div>

<!-- Charts & Activities -->
<div class="dashboard-grid">
  <!-- Revenue Chart -->
  <div class="col-8">
    <div class="card">
      <div class="card-header">
        <h3><i class="fas fa-chart-line"></i> Doanh thu 6 tháng gần đây</h3>
        <select class="form-control" style="width: 150px;">
          <option>6 tháng</option>
          <option>12 tháng</option>
          <option>Năm nay</option>
        </select>
      </div>
      <div class="card-body">
        <div class="chart-container">
          <canvas id="revenueChart"></canvas>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Recent Activity -->
  <div class="col-4">
    <div class="card">
      <div class="card-header">
        <h3><i class="fas fa-clock"></i> Hoạt động gần đây</h3>
      </div>
      <div class="card-body" style="padding: 24px 24px 12px 24px;">
        <div class="activity-list">
          <div class="activity-item">
            <div class="activity-icon blue">
              <i class="fas fa-map-marked-alt"></i>
            </div>
            <div class="activity-content">
              <div class="activity-title">Tour mới được thêm</div>
              <div class="activity-desc">Tour "Hà Nội - Hạ Long" vừa được tạo</div>
            </div>
            <div class="activity-time">2 giờ</div>
          </div>
          
          <div class="activity-item">
            <div class="activity-icon green">
              <i class="fas fa-check-circle"></i>
            </div>
            <div class="activity-content">
              <div class="activity-title">Đơn hàng xác nhận</div>
              <div class="activity-desc">Hóa đơn #12345 đã thanh toán</div>
            </div>
            <div class="activity-time">3 giờ</div>
          </div>
          
          <div class="activity-item">
            <div class="activity-icon orange">
              <i class="fas fa-newspaper"></i>
            </div>
            <div class="activity-content">
              <div class="activity-title">Blog mới đăng</div>
              <div class="activity-desc">"10 địa điểm du lịch mùa hè"</div>
            </div>
            <div class="activity-time">5 giờ</div>
          </div>
          
          <div class="activity-item">
            <div class="activity-icon red">
              <i class="fas fa-star"></i>
            </div>
            <div class="activity-content">
              <div class="activity-title">Đánh giá mới</div>
              <div class="activity-desc">5 sao cho Tour Đà Nẵng</div>
            </div>
            <div class="activity-time">1 ngày</div>
          </div>
          
          <div class="activity-item">
            <div class="activity-icon blue">
              <i class="fas fa-user-plus"></i>
            </div>
            <div class="activity-content">
              <div class="activity-title">Khách hàng mới</div>
              <div class="activity-desc">Nguyễn Văn A đăng ký</div>
            </div>
            <div class="activity-time">1 ngày</div>
          </div>
          
          <div class="activity-item">
            <div class="activity-icon green">
              <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="activity-content">
              <div class="activity-title">Thanh toán thành công</div>
              <div class="activity-desc">15.000.000đ từ Tour Phú Quốc</div>
            </div>
            <div class="activity-time">2 ngày</div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Tour Categories Chart -->
  <div class="col-6">
    <div class="card">
      <div class="card-header">
        <h3><i class="fas fa-chart-pie"></i> Phân loại Tour</h3>
      </div>
      <div class="card-body">
        <div class="chart-container" style="height: 280px;">
          <canvas id="categoriesChart"></canvas>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Top Tours -->
  <div class="col-6">
    <div class="card">
      <div class="card-header">
        <h3><i class="fas fa-fire"></i> Tour phổ biến</h3>
      </div>
      <div class="card-body">
        <ul class="tour-list">
          <li class="tour-item">
            <div class="tour-header">
              <span class="tour-name">Hà Nội - Hạ Long</span>
              <span class="tour-percent">85%</span>
            </div>
            <div class="progress-bar">
              <div class="progress-fill" style="width: 85%;"></div>
            </div>
          </li>
          
          <li class="tour-item">
            <div class="tour-header">
              <span class="tour-name">Đà Nẵng - Hội An</span>
              <span class="tour-percent">72%</span>
            </div>
            <div class="progress-bar">
              <div class="progress-fill" style="width: 72%; background: var(--success);"></div>
            </div>
          </li>
          
          <li class="tour-item">
            <div class="tour-header">
              <span class="tour-name">Phú Quốc</span>
              <span class="tour-percent">68%</span>
            </div>
            <div class="progress-bar">
              <div class="progress-fill" style="width: 68%; background: var(--warning);"></div>
            </div>
          </li>
          
          <li class="tour-item">
            <div class="tour-header">
              <span class="tour-name">Nha Trang</span>
              <span class="tour-percent">55%</span>
            </div>
            <div class="progress-bar">
              <div class="progress-fill" style="width: 55%; background: #06b6d4;"></div>
            </div>
          </li>
          
          <li class="tour-item">
            <div class="tour-header">
              <span class="tour-name">Sapa</span>
              <span class="tour-percent">45%</span>
            </div>
            <div class="progress-bar">
              <div class="progress-fill" style="width: 45%; background: #9333ea;"></div>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>

<script>
// Revenue Chart
const revenueCtx = document.getElementById('revenueChart');
if (revenueCtx) {
  new Chart(revenueCtx, {
    type: 'line',
    data: {
      labels: ['Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11'],
      datasets: [{
        label: 'Doanh thu (triệu đồng)',
        data: [450, 520, 480, 650, 720, 850],
        borderColor: '#2563eb',
        backgroundColor: 'rgba(37, 99, 235, 0.05)',
        borderWidth: 2,
        tension: 0.4,
        fill: true,
        pointBackgroundColor: '#2563eb',
        pointBorderColor: '#fff',
        pointBorderWidth: 2,
        pointRadius: 4,
        pointHoverRadius: 6
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { display: false },
        tooltip: {
          backgroundColor: 'rgba(0, 0, 0, 0.8)',
          padding: 12,
          titleFont: { size: 13 },
          bodyFont: { size: 13 },
          cornerRadius: 8
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          grid: { color: '#e5e7eb' },
          ticks: {
            font: { size: 12 },
            callback: function(value) { return value + 'M'; }
          }
        },
        x: {
          grid: { display: false },
          ticks: { font: { size: 12 } }
        }
      }
    }
  });
}

// Categories Chart
const categoriesCtx = document.getElementById('categoriesChart');
if (categoriesCtx) {
  new Chart(categoriesCtx, {
    type: 'doughnut',
    data: {
      labels: ['Tour Biển', 'Tour Núi', 'Tour Thành Phố', 'Tour Văn Hóa', 'Khác'],
      datasets: [{
        data: [35, 25, 20, 15, 5],
        backgroundColor: [
          '#2563eb',
          '#10b981',
          '#f59e0b',
          '#ef4444',
          '#9333ea'
        ],
        borderWidth: 0
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'bottom',
          labels: {
            padding: 15,
            font: { size: 12 },
            usePointStyle: true
          }
        },
        tooltip: {
          backgroundColor: 'rgba(0, 0, 0, 0.8)',
          padding: 12,
          titleFont: { size: 13 },
          bodyFont: { size: 13 },
          cornerRadius: 8,
          callbacks: {
            label: function(context) {
              return context.label + ': ' + context.parsed + '%';
            }
          }
        }
      }
    }
  });
}

// Animate progress bars
window.addEventListener('load', () => {
  document.querySelectorAll('.progress-fill').forEach(bar => {
    const width = bar.style.width;
    bar.style.width = '0%';
    setTimeout(() => {
      bar.style.width = width;
    }, 100);
  });
});
</script>
