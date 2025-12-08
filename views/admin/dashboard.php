<?php
// views/admin/dashboard.php - Giao diện Dashboard mới hiện đại

// Lấy số liệu an toàn (nếu controller truyền $stats dạng mảng)
$cnt1 = $stats['cnt1'] ?? 0;     // Hóa đơn
$cnt2 = $stats['cnt2'] ?? 0;     // Lịch trình
$goi  = $stats['goi']  ?? 0;     // Tour
$cnt5 = $stats['cnt5'] ?? 0;     // Booking
$blog = $stats['blog'] ?? 0;     // Blog
?>

<style>
  /* ===== DASHBOARD CONTAINER ===== */
  .dashboard-container {
    padding: 24px;
    max-width: 1400px;
    margin: 0 auto;
  }

  /* ===== DASHBOARD TITLE ===== */
  .dashboard-title {
    font-size: 28px;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 24px;
    padding-bottom: 16px;
    border-bottom: 2px solid var(--border);
  }

  /* ===== STATS GRID ===== */
  .stats-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 24px;
    margin-bottom: 32px;
  }

  .stat-card {
    background: white;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    text-decoration: none;
    display: block;
    color: inherit;
  }

  .stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: var(--card-color, #667eea);
    transition: width 0.3s ease;
  }

  .stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
    text-decoration: none;
    color: inherit;
  }

  .stat-card:hover::before {
    width: 100%;
    opacity: 0.1;
  }

  .stat-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 16px;
  }

  .stat-icon {
    width: 56px;
    height: 56px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: white;
    background: var(--card-color, #667eea);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  }

  .stat-number {
    font-size: 36px;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0;
    line-height: 1;
  }

  .stat-label {
    font-size: 14px;
    color: var(--text-light);
    margin-top: 8px;
    font-weight: 500;
  }

  .stat-change {
    font-size: 12px;
    color: var(--success);
    margin-top: 4px;
  }

  /* Màu sắc cho từng card */
  .stat-card[data-type="invoice"] { --card-color: #3b82f6; }
  .stat-card[data-type="schedule"] { --card-color: #10b981; }
  .stat-card[data-type="tour"] { --card-color: #8b5cf6; }
  .stat-card[data-type="booking"] { --card-color: #f59e0b; }
  .stat-card[data-type="blog"] { --card-color: #ec4899; }

  /* ===== QUICK ACTIONS ===== */
  .quick-actions {
    background: white;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    margin-bottom: 32px;
  }

  .section-title {
    font-size: 20px;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .section-title i {
    color: var(--primary);
  }

  .actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
  }

  .action-btn {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px;
    background: var(--bg-light);
    border-radius: 12px;
    text-decoration: none;
    color: var(--text-dark);
    transition: all 0.3s ease;
    border: 2px solid transparent;
  }

  .action-btn:hover {
    background: white;
    border-color: var(--primary);
    transform: translateX(4px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    text-decoration: none;
    color: var(--text-dark);
  }

  .action-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    color: white;
    background: var(--primary);
  }

  .action-text {
    font-weight: 600;
    font-size: 15px;
  }

  /* ===== CHART SECTION ===== */
  .chart-section {
    background: white;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    margin-bottom: 32px;
  }

  .chart-container {
    position: relative;
    height: 300px;
    margin-top: 20px;
  }

  /* ===== RESPONSIVE ===== */
  @media (max-width: 1200px) {
    .stats-grid {
      grid-template-columns: repeat(3, 1fr);
    }
  }

  @media (max-width: 768px) {
    .dashboard-container {
      padding: 16px;
    }

    .stats-grid {
      grid-template-columns: 1fr;
      gap: 16px;
    }

    .actions-grid {
      grid-template-columns: 1fr;
    }
  }

  /* ===== ANIMATIONS ===== */
  @keyframes fadeInUp {
    from {
      opacity: 0;
      transform: translateY(20px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .stat-card {
    animation: fadeInUp 0.5s ease forwards;
    opacity: 0;
  }

  .stat-card:nth-child(1) { animation-delay: 0.1s; }
  .stat-card:nth-child(2) { animation-delay: 0.2s; }
  .stat-card:nth-child(3) { animation-delay: 0.3s; }
  .stat-card:nth-child(4) { animation-delay: 0.4s; }
  .stat-card:nth-child(5) { animation-delay: 0.5s; }

  /* ===== PERIOD FILTER ===== */
  .period-btn {
    padding: 12px 24px;
    border: 2px solid var(--border);
    border-radius: 10px;
    background: white;
    color: var(--text-dark);
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
  }

  .period-btn:hover {
    border-color: var(--primary);
    background: var(--bg-light);
    transform: translateY(-2px);
  }

  .period-btn.active {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
  }

  /* ===== PERIOD STATS GRID ===== */
  .stats-grid-period {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 24px;
  }

  .period-stat-card {
    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
  }

  .period-stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
  }

  .period-stat-label {
    font-size: 13px;
    color: var(--text-light);
    font-weight: 600;
    margin-bottom: 8px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  .period-stat-value {
    font-size: 32px;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 4px;
  }

  .period-stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: white;
    margin-bottom: 12px;
  }

  .period-stat-card[data-type="booking"] .period-stat-icon { background: #3b82f6; }
  .period-stat-card[data-type="hoadon"] .period-stat-icon { background: #8b5cf6; }
  .period-stat-card[data-type="tour"] .period-stat-icon { background: #10b981; }
  .period-stat-card[data-type="revenue"] .period-stat-icon { background: #f59e0b; }
</style>

<div class="dashboard-container">
  <!-- Dashboard Title -->
  <h1 class="dashboard-title">Dashboard</h1>

  <!-- Statistics Cards -->
  <div class="stats-grid">
    <a href="<?= BASE_URL ?>?act=admin-tours" class="stat-card" data-type="tour">
      <div class="stat-header">
        <div class="stat-icon">
          <i class="fas fa-map-marked-alt"></i>
        </div>
      </div>
      <div class="stat-number"><?= number_format($goi) ?></div>
      <div class="stat-label">Tour</div>
      <div class="stat-change">↗ Tổng số tour</div>
    </a>

    <a href="<?= BASE_URL ?>?act=admin-departure-plans" class="stat-card" data-type="schedule">
      <div class="stat-header">
        <div class="stat-icon">
          <i class="fas fa-calendar-alt"></i>
        </div>
      </div>
      <div class="stat-number"><?= number_format($cnt2) ?></div>
      <div class="stat-label">Lịch trình</div>
      <div class="stat-change">↗ Tổng số lịch trình</div>
    </a>

    <a href="<?= BASE_URL ?>?act=admin-bookings" class="stat-card" data-type="booking">
      <div class="stat-header">
        <div class="stat-icon">
          <i class="fas fa-calendar-check"></i>
        </div>
      </div>
      <div class="stat-number"><?= number_format($cnt5) ?></div>
      <div class="stat-label">Booking</div>
      <div class="stat-change">↗ Tổng số booking</div>
    </a>

    <a href="<?= BASE_URL ?>?act=hoadon-list" class="stat-card" data-type="invoice">
      <div class="stat-header">
        <div class="stat-icon">
          <i class="fas fa-file-invoice-dollar"></i>
        </div>
      </div>
      <div class="stat-number"><?= number_format($cnt1) ?></div>
      <div class="stat-label">Hóa đơn</div>
      <div class="stat-change">↗ Tổng số hóa đơn</div>
    </a>

    <a href="<?= BASE_URL ?>?act=blog-list" class="stat-card" data-type="blog">
      <div class="stat-header">
        <div class="stat-icon">
          <i class="fas fa-newspaper"></i>
        </div>
      </div>
      <div class="stat-number"><?= number_format($blog) ?></div>
      <div class="stat-label">Blog</div>
      <div class="stat-change">↗ Tổng số bài viết</div>
    </a>
  </div>

  <!-- Quick Actions -->
  <div class="quick-actions">
    <h3 class="section-title">
      <i class="fas fa-bolt"></i>
      Hành động nhanh
    </h3>
    <div class="actions-grid">
      <a href="<?= BASE_URL ?>?act=admin-tour-create" class="action-btn">
        <div class="action-icon">
          <i class="fas fa-plus"></i>
        </div>
        <div class="action-text">Thêm tour mới</div>
      </a>
      <a href="<?= BASE_URL ?>?act=blog-create" class="action-btn">
        <div class="action-icon">
          <i class="fas fa-edit"></i>
        </div>
        <div class="action-text">Viết blog mới</div>
      </a>
      <a href="<?= BASE_URL ?>?act=hoadon-list" class="action-btn">
        <div class="action-icon">
          <i class="fas fa-list"></i>
        </div>
        <div class="action-text">Xem hóa đơn</div>
      </a>
    </div>
  </div>

  <!-- Statistics by Period -->
  <div class="chart-section">
    <h3 class="section-title">
      <i class="fas fa-chart-line"></i>
      Thống kê theo thời gian
    </h3>
    
    <!-- Period Filter -->
    <div style="display: flex; gap: 12px; margin-bottom: 24px; flex-wrap: wrap;">
      <button class="period-btn active" data-period="day" onclick="switchPeriod('day')">
        <i class="fas fa-calendar-day"></i> Hôm nay
      </button>
      <button class="period-btn" data-period="week" onclick="switchPeriod('week')">
        <i class="fas fa-calendar-week"></i> 7 ngày qua
      </button>
      <button class="period-btn" data-period="month" onclick="switchPeriod('month')">
        <i class="fas fa-calendar-alt"></i> 30 ngày qua
      </button>
    </div>

    <!-- Statistics Cards by Period -->
    <div class="stats-grid-period" id="periodStats">
      <!-- Sẽ được cập nhật bởi JavaScript -->
    </div>
  </div>

  <!-- Chart Section -->
  <div class="chart-section">
    <h3 class="section-title">
      <i class="fas fa-chart-bar"></i>
      Biểu đồ thống kê tổng quan
    </h3>
    <div class="chart-container">
      <canvas id="statsChart"></canvas>
    </div>
  </div>
</div>

<script>
// Dữ liệu thống kê theo thời gian
const periodStats = {
  day: <?= json_encode($statsByDay ?? []) ?>,
  week: <?= json_encode($statsByWeek ?? []) ?>,
  month: <?= json_encode($statsByMonth ?? []) ?>
};

// Hàm format số tiền
function formatPrice(price) {
  return new Intl.NumberFormat('vi-VN').format(Math.round(price)) + ' đ';
}

// Hàm hiển thị thống kê theo period
function displayPeriodStats(period) {
  const stats = periodStats[period] || {};
  const container = document.getElementById('periodStats');
  
  const periodLabels = {
    day: 'Hôm nay',
    week: '7 ngày qua',
    month: '30 ngày qua'
  };

  container.innerHTML = `
    <div class="period-stat-card" data-type="booking">
      <div class="period-stat-icon">
        <i class="fas fa-calendar-check"></i>
      </div>
      <div class="period-stat-label">Booking</div>
      <div class="period-stat-value">${stats.booking || 0}</div>
      <div style="font-size: 12px; color: var(--text-light);">${periodLabels[period]}</div>
    </div>
    
    <div class="period-stat-card" data-type="hoadon">
      <div class="period-stat-icon">
        <i class="fas fa-file-invoice-dollar"></i>
      </div>
      <div class="period-stat-label">Hóa đơn</div>
      <div class="period-stat-value">${stats.hoadon || 0}</div>
      <div style="font-size: 12px; color: var(--text-light);">${periodLabels[period]}</div>
    </div>
    
    <div class="period-stat-card" data-type="tour">
      <div class="period-stat-icon">
        <i class="fas fa-map-marked-alt"></i>
      </div>
      <div class="period-stat-label">Tour mới</div>
      <div class="period-stat-value">${stats.tour || 0}</div>
      <div style="font-size: 12px; color: var(--text-light);">${periodLabels[period]}</div>
    </div>
    
    <div class="period-stat-card" data-type="revenue">
      <div class="period-stat-icon">
        <i class="fas fa-money-bill-wave"></i>
      </div>
      <div class="period-stat-label">Doanh thu</div>
      <div class="period-stat-value">${formatPrice(stats.revenue || 0)}</div>
      <div style="font-size: 12px; color: var(--text-light);">${periodLabels[period]}</div>
    </div>
  `;
}

// Hàm chuyển đổi period
function switchPeriod(period) {
  // Cập nhật active button
  document.querySelectorAll('.period-btn').forEach(btn => {
    btn.classList.remove('active');
    if (btn.dataset.period === period) {
      btn.classList.add('active');
    }
  });
  
  // Hiển thị thống kê
  displayPeriodStats(period);
}

// Biểu đồ thống kê
document.addEventListener('DOMContentLoaded', function() {
  // Hiển thị thống kê mặc định (hôm nay)
  displayPeriodStats('day');
  
  const ctx = document.getElementById('statsChart');
  if (ctx) {
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: ['Tour', 'Lịch trình', 'Booking', 'Hóa đơn', 'Blog'],
        datasets: [{
          label: 'Số lượng',
          data: [
            <?= $goi ?>,
            <?= $cnt2 ?>,
            <?= $cnt5 ?>,
            <?= $cnt1 ?>,
            <?= $blog ?>
          ],
          backgroundColor: [
            'rgba(139, 92, 246, 0.8)',
            'rgba(16, 185, 129, 0.8)',
            'rgba(245, 158, 11, 0.8)',
            'rgba(59, 130, 246, 0.8)',
            'rgba(236, 72, 153, 0.8)'
          ],
          borderColor: [
            'rgb(139, 92, 246)',
            'rgb(16, 185, 129)',
            'rgb(245, 158, 11)',
            'rgb(59, 130, 246)',
            'rgb(236, 72, 153)'
          ],
          borderWidth: 2,
          borderRadius: 8
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            grid: {
              color: 'rgba(0, 0, 0, 0.05)'
            }
          },
          x: {
            grid: {
              display: false
            }
          }
        }
      }
    });
  }
});
</script>
