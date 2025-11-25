<?php
// views/admin/dashboard.php - Giao diện Dashboard mới hiện đại

// Lấy số liệu an toàn (nếu controller truyền $stats dạng mảng)
$cnt1 = $stats['cnt1'] ?? 0;     // Hóa đơn
$cnt2 = $stats['cnt2'] ?? 0;     // Góp ý
$goi  = $stats['goi']  ?? 0;     // Tour
$cnt5 = $stats['cnt5'] ?? 0;     // Trợ giúp
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
  .stat-card[data-type="feedback"] { --card-color: #f59e0b; }
  .stat-card[data-type="tour"] { --card-color: #8b5cf6; }
  .stat-card[data-type="support"] { --card-color: #ef4444; }
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

    <a href="#" class="stat-card" data-type="invoice">
      <div class="stat-header">
        <div class="stat-icon">
          <i class="fas fa-file-invoice-dollar"></i>
        </div>
      </div>
      <div class="stat-number"><?= number_format($cnt1) ?></div>
      <div class="stat-label">Hóa đơn</div>
      <div class="stat-change">↗ Tổng số hóa đơn</div>
    </a>

    <a href="#" class="stat-card" data-type="blog">
      <div class="stat-header">
        <div class="stat-icon">
          <i class="fas fa-newspaper"></i>
        </div>
      </div>
      <div class="stat-number"><?= number_format($blog) ?></div>
      <div class="stat-label">Blog</div>
      <div class="stat-change">↗ Tổng số bài viết</div>
    </a>

    <a href="#" class="stat-card" data-type="feedback">
      <div class="stat-header">
        <div class="stat-icon">
          <i class="fas fa-comments"></i>
        </div>
      </div>
      <div class="stat-number"><?= number_format($cnt2) ?></div>
      <div class="stat-label">Góp ý</div>
      <div class="stat-change">↗ Tổng số góp ý</div>
    </a>

    <a href="#" class="stat-card" data-type="support">
      <div class="stat-header">
        <div class="stat-icon">
          <i class="fas fa-life-ring"></i>
        </div>
      </div>
      <div class="stat-number"><?= number_format($cnt5) ?></div>
      <div class="stat-label">Trợ giúp</div>
      <div class="stat-change">↗ Tổng số yêu cầu</div>
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

  <!-- Chart Section -->
  <div class="chart-section">
    <h3 class="section-title">
      <i class="fas fa-chart-line"></i>
      Thống kê tổng quan
    </h3>
    <div class="chart-container">
      <canvas id="statsChart"></canvas>
    </div>
  </div>
</div>

<script>
// Biểu đồ thống kê
document.addEventListener('DOMContentLoaded', function() {
  const ctx = document.getElementById('statsChart');
  if (ctx) {
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: ['Tour', 'Hóa đơn', 'Blog', 'Góp ý', 'Trợ giúp'],
        datasets: [{
          label: 'Số lượng',
          data: [
            <?= $goi ?>,
            <?= $cnt1 ?>,
            <?= $blog ?>,
            <?= $cnt2 ?>,
            <?= $cnt5 ?>
          ],
          backgroundColor: [
            'rgba(139, 92, 246, 0.8)',
            'rgba(59, 130, 246, 0.8)',
            'rgba(236, 72, 153, 0.8)',
            'rgba(245, 158, 11, 0.8)',
            'rgba(239, 68, 68, 0.8)'
          ],
          borderColor: [
            'rgb(139, 92, 246)',
            'rgb(59, 130, 246)',
            'rgb(236, 72, 153)',
            'rgb(245, 158, 11)',
            'rgb(239, 68, 68)'
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
