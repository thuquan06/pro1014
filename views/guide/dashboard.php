<?php
// views/guide/dashboard.php
?>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

<div class="dashboard-container">
  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-icon green">
        <i class="fas fa-calendar-check"></i>
      </div>
      <div class="stat-details">
        <h4><?= $stats['total'] ?? 0 ?></h4>
        <p>Tổng phân công</p>
      </div>
    </div>
    
    <div class="stat-card">
      <div class="stat-icon blue">
        <i class="fas fa-clock"></i>
      </div>
      <div class="stat-details">
        <h4><?= $stats['upcoming'] ?? 0 ?></h4>
        <p>Sắp tới</p>
      </div>
    </div>
    
    <div class="stat-card">
      <div class="stat-icon orange">
        <i class="fas fa-check-circle"></i>
      </div>
      <div class="stat-details">
        <h4><?= $stats['completed'] ?? 0 ?></h4>
        <p>Đã hoàn thành</p>
      </div>
    </div>
    
    <div class="stat-card">
      <div class="stat-icon purple">
        <i class="fas fa-user-check"></i>
      </div>
      <div class="stat-details">
        <h4><?= $stats['active'] ?? 0 ?></h4>
        <p>Đang hoạt động</p>
      </div>
    </div>
    
    <div class="stat-card">
      <div class="stat-icon green">
        <i class="fas fa-money-bill-wave"></i>
      </div>
      <div class="stat-details">
        <h4><?= number_format($stats['total_salary'] ?? 0, 0, ',', '.') ?> đ</h4>
        <p>Tổng lương</p>
      </div>
    </div>
    
    <div class="stat-card">
      <div class="stat-icon blue">
        <i class="fas fa-book"></i>
      </div>
      <div class="stat-details">
        <h4><?= $stats['total_journals'] ?? 0 ?></h4>
        <p>Nhật ký tour</p>
      </div>
    </div>
    
    <div class="stat-card">
      <div class="stat-icon orange">
        <i class="fas fa-exclamation-triangle"></i>
      </div>
      <div class="stat-details">
        <h4><?= $stats['total_incidents'] ?? 0 ?></h4>
        <p>Báo cáo sự cố</p>
      </div>
    </div>
  </div>

  <!-- Biểu đồ thống kê -->
  <?php if (!empty($stats['monthly_stats'])): ?>
  <div class="card" style="margin-bottom: 24px;">
    <div class="card-header">
      <h3><i class="fas fa-chart-line"></i> Thống kê 6 tháng gần nhất</h3>
    </div>
    <div class="card-body">
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
        <div>
          <h4 style="font-size: 16px; font-weight: 600; margin-bottom: 16px;">Số tour theo tháng</h4>
          <canvas id="toursChart" style="max-height: 300px;"></canvas>
        </div>
        <div>
          <h4 style="font-size: 16px; font-weight: 600; margin-bottom: 16px;">Lương theo tháng</h4>
          <canvas id="salaryChart" style="max-height: 300px;"></canvas>
        </div>
      </div>
    </div>
  </div>
  
  <script>
    // Biểu đồ số tour
    const toursCtx = document.getElementById('toursChart').getContext('2d');
    new Chart(toursCtx, {
      type: 'bar',
      data: {
        labels: <?= json_encode(array_column($stats['monthly_stats'], 'month')) ?>,
        datasets: [{
          label: 'Số tour',
          data: <?= json_encode(array_column($stats['monthly_stats'], 'count')) ?>,
          backgroundColor: 'rgba(16, 185, 129, 0.8)',
          borderColor: 'rgba(16, 185, 129, 1)',
          borderWidth: 2,
          borderRadius: 8
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
          legend: {
            display: false
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              stepSize: 1
            }
          }
        }
      }
    });
    
    // Biểu đồ lương
    const salaryCtx = document.getElementById('salaryChart').getContext('2d');
    new Chart(salaryCtx, {
      type: 'line',
      data: {
        labels: <?= json_encode(array_column($stats['monthly_stats'], 'month')) ?>,
        datasets: [{
          label: 'Lương (VNĐ)',
          data: <?= json_encode(array_column($stats['monthly_stats'], 'salary')) ?>,
          borderColor: 'rgba(59, 130, 246, 1)',
          backgroundColor: 'rgba(59, 130, 246, 0.1)',
          borderWidth: 3,
          fill: true,
          tension: 0.4
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
          legend: {
            display: false
          },
          tooltip: {
            callbacks: {
              label: function(context) {
                return new Intl.NumberFormat('vi-VN').format(context.parsed.y) + ' đ';
              }
            }
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              callback: function(value) {
                return new Intl.NumberFormat('vi-VN', { notation: 'compact' }).format(value) + ' đ';
              }
            }
          }
        }
      }
    });
  </script>
  <?php endif; ?>

  <div class="card">
    <div class="card-header">
      <h3><i class="fas fa-list"></i> Phân công sắp tới</h3>
      <a href="?act=guide-assignments" class="btn btn-primary btn-sm">
        <i class="fas fa-eye"></i> Xem tất cả
      </a>
    </div>
    <div class="card-body">
      <?php if (empty($recentAssignments)): ?>
        <p style="color: var(--text-light); text-align: center; padding: 20px;">
          <i class="fas fa-inbox" style="font-size: 48px; opacity: 0.3; margin-bottom: 12px; display: block;"></i>
          Chưa có phân công nào sắp tới
        </p>
      <?php else: ?>
        <div class="table-responsive">
          <table>
            <thead>
              <tr>
                <th>Tour</th>
                <th>Ngày khởi hành</th>
                <th>Vai trò</th>
                <th>Thời gian</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($recentAssignments as $assignment): ?>
                <tr>
                  <td>
                    <?php if (!empty($assignment['ten_tour'])): ?>
                      <strong><?= htmlspecialchars($assignment['ten_tour']) ?></strong>
                    <?php elseif (!empty($assignment['id_lich_khoi_hanh'])): ?>
                      <strong style="color: var(--text-light);">Tour #<?= htmlspecialchars($assignment['id_lich_khoi_hanh']) ?></strong>
                      <br><small style="color: var(--text-light); font-style: italic;">Chưa có thông tin tour</small>
                    <?php else: ?>
                      <strong style="color: var(--text-light);">Phân công #<?= htmlspecialchars($assignment['id']) ?></strong>
                      <br><small style="color: var(--text-light); font-style: italic;">Chưa có lịch khởi hành</small>
                    <?php endif; ?>
                  </td>
                  <td>
                    <?php if ($assignment['ngay_khoi_hanh']): ?>
                      <?= date('d/m/Y', strtotime($assignment['ngay_khoi_hanh'])) ?>
                      <?php if ($assignment['gio_khoi_hanh']): ?>
                        <br><small style="color: var(--text-light);"><?= htmlspecialchars($assignment['gio_khoi_hanh']) ?></small>
                      <?php endif; ?>
                    <?php else: ?>
                      <span style="color: var(--text-light); font-style: italic;">Chưa cập nhật</span>
                    <?php endif; ?>
                  </td>
                  <td><?= htmlspecialchars($assignment['vai_tro'] ?? 'HDV chính') ?></td>
                  <td>
                    <?php if ($assignment['ngay_bat_dau'] && $assignment['ngay_ket_thuc']): ?>
                      <?= date('d/m/Y', strtotime($assignment['ngay_bat_dau'])) ?>
                      <br><small style="color: var(--text-light);">đến</small><br>
                      <?= date('d/m/Y', strtotime($assignment['ngay_ket_thuc'])) ?>
                    <?php else: ?>
                      N/A
                    <?php endif; ?>
                  </td>
                  <td>
                    <?php if ($assignment['trang_thai'] == 1): ?>
                      <span style="background: #d1fae5; color: #065f46; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                        <i class="fas fa-check-circle"></i> Hoạt động
                      </span>
                    <?php else: ?>
                      <span style="background: #fee2e2; color: #991b1b; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                        <i class="fas fa-times-circle"></i> Tạm dừng
                      </span>
                    <?php endif; ?>
                  </td>
                  <td>
                    <a href="?act=guide-assignment-detail&id=<?= $assignment['id'] ?>" class="btn btn-primary btn-sm">
                      <i class="fas fa-eye"></i> Chi tiết
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <div class="card">
    <div class="card-header">
      <h3><i class="fas fa-user"></i> Thông tin cá nhân</h3>
      <a href="?act=guide-profile" class="btn btn-primary btn-sm">
        <i class="fas fa-edit"></i> Cập nhật
      </a>
    </div>
    <div class="card-body">
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
        <div>
          <strong style="color: var(--text-light); font-size: 12px; text-transform: uppercase;">Họ tên</strong>
          <p style="font-size: 16px; font-weight: 600; margin-top: 4px;"><?= htmlspecialchars($guide['ho_ten'] ?? 'N/A') ?></p>
        </div>
        <div>
          <strong style="color: var(--text-light); font-size: 12px; text-transform: uppercase;">Email</strong>
          <p style="font-size: 16px; font-weight: 600; margin-top: 4px;"><?= htmlspecialchars($guide['email'] ?? 'N/A') ?></p>
        </div>
        <div>
          <strong style="color: var(--text-light); font-size: 12px; text-transform: uppercase;">Số điện thoại</strong>
          <p style="font-size: 16px; font-weight: 600; margin-top: 4px;"><?= htmlspecialchars($guide['so_dien_thoai'] ?? 'N/A') ?></p>
        </div>
        <div>
          <strong style="color: var(--text-light); font-size: 12px; text-transform: uppercase;">Kinh nghiệm</strong>
          <p style="font-size: 16px; font-weight: 600; margin-top: 4px;"><?= htmlspecialchars($guide['kinh_nghiem'] ?? 0) ?> năm</p>
        </div>
        <div>
          <strong style="color: var(--text-light); font-size: 12px; text-transform: uppercase;">Đánh giá</strong>
          <p style="font-size: 16px; font-weight: 600; margin-top: 4px;">
            <?php if (!empty($guide['danh_gia'])): ?>
              <?= number_format($guide['danh_gia'], 1) ?> <i class="fas fa-star" style="color: #f59e0b;"></i>
            <?php else: ?>
              Chưa có đánh giá
            <?php endif; ?>
          </p>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
.dashboard-container {
  max-width: 1400px;
  margin: 0 auto;
}

.stat-card {
  animation: fadeInUp 0.5s ease-out;
  animation-fill-mode: both;
}

.stat-card:nth-child(1) { animation-delay: 0.1s; }
.stat-card:nth-child(2) { animation-delay: 0.2s; }
.stat-card:nth-child(3) { animation-delay: 0.3s; }
.stat-card:nth-child(4) { animation-delay: 0.4s; }
.stat-card:nth-child(5) { animation-delay: 0.5s; }
.stat-card:nth-child(6) { animation-delay: 0.6s; }
.stat-card:nth-child(7) { animation-delay: 0.7s; }

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

@media (max-width: 768px) {
  .stats-grid {
    grid-template-columns: 1fr !important;
  }
  
  #toursChart, #salaryChart {
    max-height: 250px !important;
  }
  
  .card-body > div {
    grid-template-columns: 1fr !important;
  }
}
</style>


