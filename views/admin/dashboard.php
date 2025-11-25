<?php
/**
 * Admin Dashboard - SIÊU HIỆN ĐẠI với Biểu đồ
 * Version: 3.0 - Ultra Modern
 * Updated: 2025-11-25
 */

// Lấy số liệu an toàn
$cnt1 = $stats['cnt1'] ?? 0;     // Hóa đơn
$ks   = $stats['ks']   ?? 0;     // Khách sạn
$cnt2 = $stats['cnt2'] ?? 0;     // Góp ý
$goi  = $stats['goi']  ?? 0;     // Tour
$cnt5 = $stats['cnt5'] ?? 0;     // Trợ giúp
$blog = $stats['blog'] ?? 0;     // Blog
?>

<style>
  .dashboard-grid {
    display: grid;
    grid-template-columns: repeat(12, 1fr);
    gap: 25px;
    margin-bottom: 35px;
  }
  
  .col-12 { grid-column: span 12; }
  .col-8 { grid-column: span 8; }
  .col-4 { grid-column: span 4; }
  .col-6 { grid-column: span 6; }
  .col-3 { grid-column: span 3; }
  
  @media (max-width: 1200px) {
    .col-8, .col-4 { grid-column: span 12; }
    .col-6 { grid-column: span 12; }
  }
  
  @media (max-width: 768px) {
    .col-3 { grid-column: span 12; }
  }
  
  .quick-actions {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
  }
  
  .quick-action-card {
    background: var(--bg-card);
    border-radius: 16px;
    padding: 24px;
    text-align: center;
    cursor: pointer;
    transition: var(--transition);
    border: 2px solid var(--border-color);
    text-decoration: none;
    color: var(--text-primary);
  }
  
  .quick-action-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 32px rgba(99, 102, 241, 0.2);
    border-color: var(--primary);
  }
  
  .quick-action-icon {
    width: 70px;
    height: 70px;
    margin: 0 auto 15px;
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 32px;
    color: white;
    background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
    box-shadow: 0 8px 24px rgba(99, 102, 241, 0.3);
  }
  
  .quick-action-card h4 {
    font-size: 16px;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0;
  }
  
  .chart-container {
    position: relative;
    height: 350px;
    padding: 20px;
  }
  
  .recent-activities {
    max-height: 400px;
    overflow-y: auto;
  }
  
  .activity-item {
    display: flex;
    align-items: flex-start;
    gap: 15px;
    padding: 18px;
    border-bottom: 1px solid var(--border-color);
    transition: var(--transition);
  }
  
  .activity-item:last-child {
    border-bottom: none;
  }
  
  .activity-item:hover {
    background: rgba(99, 102, 241, 0.05);
  }
  
  .activity-icon {
    min-width: 45px;
    width: 45px;
    height: 45px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    color: white;
  }
  
  .activity-icon.blue { background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); }
  .activity-icon.green { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
  .activity-icon.orange { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
  .activity-icon.red { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); }
  
  .activity-details {
    flex: 1;
  }
  
  .activity-details h5 {
    font-size: 14px;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0 0 5px 0;
  }
  
  .activity-details p {
    font-size: 13px;
    color: var(--text-secondary);
    margin: 0;
  }
  
  .activity-time {
    font-size: 12px;
    color: var(--text-secondary);
    white-space: nowrap;
  }
  
  .progress-bar {
    width: 100%;
    height: 8px;
    background: var(--border-color);
    border-radius: 10px;
    overflow: hidden;
    margin-top: 8px;
  }
  
  .progress-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--primary) 0%, var(--secondary) 100%);
    border-radius: 10px;
    transition: width 1s ease-out;
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
      <small><i class="fas fa-arrow-up"></i> +12.5% so với tháng trước</small>
    </div>
  </div>
  
  <div class="stat-card">
    <div class="stat-icon green">
      <i class="fas fa-map-marked-alt"></i>
    </div>
    <div class="stat-details">
      <h4><?= number_format($goi) ?></h4>
      <p>Tour du lịch</p>
      <small><i class="fas fa-arrow-up"></i> +8.2% so với tháng trước</small>
    </div>
  </div>
  
  <div class="stat-card">
    <div class="stat-icon orange">
      <i class="fas fa-newspaper"></i>
    </div>
    <div class="stat-details">
      <h4><?= number_format($blog) ?></h4>
      <p>Bài viết blog</p>
      <small><i class="fas fa-arrow-up"></i> +5.7% so với tháng trước</small>
    </div>
  </div>
  
  <div class="stat-card">
    <div class="stat-icon red">
      <i class="fas fa-hotel"></i>
    </div>
    <div class="stat-details">
      <h4><?= number_format($ks) ?></h4>
      <p>Khách sạn</p>
      <small><i class="fas fa-minus"></i> Không thay đổi</small>
    </div>
  </div>
  
  <div class="stat-card">
    <div class="stat-icon purple">
      <i class="fas fa-comments"></i>
    </div>
    <div class="stat-details">
      <h4><?= number_format($cnt2) ?></h4>
      <p>Góp ý khách hàng</p>
      <small><i class="fas fa-arrow-up"></i> +15.3% so với tháng trước</small>
    </div>
  </div>
  
  <div class="stat-card">
    <div class="stat-icon cyan">
      <i class="fas fa-life-ring"></i>
    </div>
    <div class="stat-details">
      <h4><?= number_format($cnt5) ?></h4>
      <p>Yêu cầu trợ giúp</p>
      <small><i class="fas fa-arrow-down" style="color: #ef4444;"></i> -3.1% so với tháng trước</small>
    </div>
  </div>
</div>

<!-- Quick Actions -->
<div class="card">
  <div class="card-header">
    <h3><i class="fas fa-bolt"></i> Hành động nhanh</h3>
  </div>
  <div class="card-body">
    <div class="quick-actions">
      <a href="<?= BASE_URL ?>?act=admin-tour-create" class="quick-action-card">
        <div class="quick-action-icon">
          <i class="fas fa-plus-circle"></i>
        </div>
        <h4>Thêm Tour Mới</h4>
      </a>
      
      <a href="<?= BASE_URL ?>?act=blog-create" class="quick-action-card">
        <div class="quick-action-icon" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
          <i class="fas fa-pen-fancy"></i>
        </div>
        <h4>Viết Blog Mới</h4>
      </a>
      
      <a href="<?= BASE_URL ?>?act=hoadon-list" class="quick-action-card">
        <div class="quick-action-icon" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
          <i class="fas fa-file-invoice"></i>
        </div>
        <h4>Quản lý Hóa Đơn</h4>
      </a>
      
      <a href="<?= BASE_URL ?>?act=province-create" class="quick-action-card">
        <div class="quick-action-icon" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
          <i class="fas fa-map-marker-alt"></i>
        </div>
        <h4>Thêm Địa Điểm</h4>
      </a>
    </div>
  </div>
</div>

<!-- Charts & Activities Grid -->
<div class="dashboard-grid">
  <!-- Revenue Chart -->
  <div class="col-8">
    <div class="card">
      <div class="card-header">
        <h3><i class="fas fa-chart-line"></i> Doanh thu 6 tháng gần đây</h3>
        <div>
          <select class="form-control" style="width: 150px; display: inline-block;">
            <option>6 tháng</option>
            <option>12 tháng</option>
            <option>Năm nay</option>
          </select>
        </div>
      </div>
      <div class="card-body">
        <div class="chart-container">
          <canvas id="revenueChart"></canvas>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Recent Activities -->
  <div class="col-4">
    <div class="card">
      <div class="card-header">
        <h3><i class="fas fa-history"></i> Hoạt động gần đây</h3>
      </div>
      <div class="card-body" style="padding: 0;">
        <div class="recent-activities">
          <div class="activity-item">
            <div class="activity-icon blue">
              <i class="fas fa-map-marked-alt"></i>
            </div>
            <div class="activity-details">
              <h5>Tour mới được thêm</h5>
              <p>Tour "Hà Nội - Hạ Long - Sapa" vừa được tạo</p>
            </div>
            <div class="activity-time">2 giờ trước</div>
          </div>
          
          <div class="activity-item">
            <div class="activity-icon green">
              <i class="fas fa-check-circle"></i>
            </div>
            <div class="activity-details">
              <h5>Đơn hàng được xác nhận</h5>
              <p>Hóa đơn #12345 đã được thanh toán</p>
            </div>
            <div class="activity-time">3 giờ trước</div>
          </div>
          
          <div class="activity-item">
            <div class="activity-icon orange">
              <i class="fas fa-newspaper"></i>
            </div>
            <div class="activity-details">
              <h5>Blog mới được đăng</h5>
              <p>"10 địa điểm du lịch mùa hè" đã xuất bản</p>
            </div>
            <div class="activity-time">5 giờ trước</div>
          </div>
          
          <div class="activity-item">
            <div class="activity-icon red">
              <i class="fas fa-star"></i>
            </div>
            <div class="activity-details">
              <h5>Đánh giá mới</h5>
              <p>Khách hàng đánh giá 5 sao cho Tour Đà Nẵng</p>
            </div>
            <div class="activity-time">1 ngày trước</div>
          </div>
          
          <div class="activity-item">
            <div class="activity-icon blue">
              <i class="fas fa-user-plus"></i>
            </div>
            <div class="activity-details">
              <h5>Khách hàng mới</h5>
              <p>Nguyễn Văn A vừa đăng ký tài khoản</p>
            </div>
            <div class="activity-time">1 ngày trước</div>
          </div>
          
          <div class="activity-item">
            <div class="activity-icon green">
              <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="activity-details">
              <h5>Thanh toán thành công</h5>
              <p>Giao dịch 15.000.000đ từ Tour Phú Quốc</p>
            </div>
            <div class="activity-time">2 ngày trước</div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Tour Categories -->
  <div class="col-6">
    <div class="card">
      <div class="card-header">
        <h3><i class="fas fa-chart-pie"></i> Phân loại Tour</h3>
      </div>
      <div class="card-body">
        <div class="chart-container" style="height: 300px;">
          <canvas id="tourCategoriesChart"></canvas>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Top Tours -->
  <div class="col-6">
    <div class="card">
      <div class="card-header">
        <h3><i class="fas fa-fire"></i> Tour phổ biến nhất</h3>
      </div>
      <div class="card-body">
        <div style="padding: 10px 0;">
          <div style="margin-bottom: 25px;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
              <span style="font-weight: 600; color: var(--text-primary);">Tour Hà Nội - Hạ Long</span>
              <span style="font-weight: 700; color: var(--primary);">85%</span>
            </div>
            <div class="progress-bar">
              <div class="progress-fill" style="width: 85%;"></div>
            </div>
          </div>
          
          <div style="margin-bottom: 25px;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
              <span style="font-weight: 600; color: var(--text-primary);">Tour Đà Nẵng - Hội An</span>
              <span style="font-weight: 700; color: var(--success);">72%</span>
            </div>
            <div class="progress-bar">
              <div class="progress-fill" style="width: 72%; background: linear-gradient(90deg, #10b981 0%, #059669 100%);"></div>
            </div>
          </div>
          
          <div style="margin-bottom: 25px;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
              <span style="font-weight: 600; color: var(--text-primary);">Tour Phú Quốc</span>
              <span style="font-weight: 700; color: var(--warning);">68%</span>
            </div>
            <div class="progress-bar">
              <div class="progress-fill" style="width: 68%; background: linear-gradient(90deg, #f59e0b 0%, #d97706 100%);"></div>
            </div>
          </div>
          
          <div style="margin-bottom: 25px;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
              <span style="font-weight: 600; color: var(--text-primary);">Tour Nha Trang</span>
              <span style="font-weight: 700; color: var(--info);">55%</span>
            </div>
            <div class="progress-bar">
              <div class="progress-fill" style="width: 55%; background: linear-gradient(90deg, #06b6d4 0%, #0891b2 100%);"></div>
            </div>
          </div>
          
          <div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
              <span style="font-weight: 600; color: var(--text-primary);">Tour Sapa</span>
              <span style="font-weight: 700; color: var(--secondary);">45%</span>
            </div>
            <div class="progress-bar">
              <div class="progress-fill" style="width: 45%; background: linear-gradient(90deg, #8b5cf6 0%, #7c3aed 100%);"></div>
            </div>
          </div>
        </div>
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
        borderColor: 'rgb(99, 102, 241)',
        backgroundColor: 'rgba(99, 102, 241, 0.1)',
        borderWidth: 3,
        tension: 0.4,
        fill: true,
        pointBackgroundColor: 'rgb(99, 102, 241)',
        pointBorderColor: '#fff',
        pointBorderWidth: 2,
        pointRadius: 6,
        pointHoverRadius: 8
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: false
        },
        tooltip: {
          backgroundColor: 'rgba(0, 0, 0, 0.8)',
          padding: 12,
          titleFont: { size: 14, weight: 'bold' },
          bodyFont: { size: 13 },
          cornerRadius: 8
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          grid: {
            color: 'rgba(0, 0, 0, 0.05)'
          },
          ticks: {
            font: { size: 12 },
            callback: function(value) {
              return value + 'M';
            }
          }
        },
        x: {
          grid: {
            display: false
          },
          ticks: {
            font: { size: 12 }
          }
        }
      }
    }
  });
}

// Tour Categories Chart
const categoriesCtx = document.getElementById('tourCategoriesChart');
if (categoriesCtx) {
  new Chart(categoriesCtx, {
    type: 'doughnut',
    data: {
      labels: ['Tour Biển', 'Tour Núi', 'Tour Thành Phố', 'Tour Văn Hóa', 'Tour Khác'],
      datasets: [{
        data: [35, 25, 20, 15, 5],
        backgroundColor: [
          'rgba(99, 102, 241, 0.8)',
          'rgba(16, 185, 129, 0.8)',
          'rgba(245, 158, 11, 0.8)',
          'rgba(239, 68, 68, 0.8)',
          'rgba(139, 92, 246, 0.8)'
        ],
        borderColor: [
          'rgb(99, 102, 241)',
          'rgb(16, 185, 129)',
          'rgb(245, 158, 11)',
          'rgb(239, 68, 68)',
          'rgb(139, 92, 246)'
        ],
        borderWidth: 2
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
            font: { size: 12, weight: '500' },
            usePointStyle: true,
            pointStyle: 'circle'
          }
        },
        tooltip: {
          backgroundColor: 'rgba(0, 0, 0, 0.8)',
          padding: 12,
          titleFont: { size: 14, weight: 'bold' },
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

// Animate progress bars on load
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
