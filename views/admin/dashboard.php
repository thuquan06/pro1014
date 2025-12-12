<?php
// views/admin/dashboard.php - Dashboard mới với đầy đủ tính năng

// Helper functions
function formatPrice($price) {
    return number_format($price, 0, ',', '.') . ' đ';
}

function formatDate($date) {
    return $date ? date('d/m/Y', strtotime($date)) : '-';
}

function formatDateTime($datetime) {
    return $datetime ? date('d/m/Y H:i', strtotime($datetime)) : '-';
}

function getStatusBadge($status) {
    // Chuyển đổi status sang int để đảm bảo so sánh đúng
    $status = (int)$status;
    // Mapping theo BookingModel: 0=Chờ xử lý, 2=Đã đặt cọc, 3=Đã thanh toán, 4=Hoàn thành, 5=Hủy
    $badges = [
        0 => ['text' => 'Chờ xử lý', 'class' => 'warning'],
        1 => ['text' => 'Chờ xử lý', 'class' => 'warning'], // Fallback
        2 => ['text' => 'Đã đặt cọc', 'class' => 'info'],
        3 => ['text' => 'Đã thanh toán', 'class' => 'success'],
        4 => ['text' => 'Hoàn thành', 'class' => 'success'],
        5 => ['text' => 'Đã hủy', 'class' => 'danger']
    ];
    $badge = $badges[$status] ?? ['text' => 'Không xác định', 'class' => 'secondary'];
    return '<span class="badge badge-' . $badge['class'] . '">' . $badge['text'] . '</span>';
}

// Default values
$stats = $stats ?? [];
$upcomingDepartures = $upcomingDepartures ?? [];
$recentBookings = $recentBookings ?? [];
$todayTours = $todayTours ?? [];
$notifications = $notifications ?? [];
$actionsNeeded = $actionsNeeded ?? [];
$guideStatus = $guideStatus ?? ['active' => 0, 'available' => 0, 'on_tour' => 0];
$upcomingGuideSchedule = $upcomingGuideSchedule ?? [];
?>

<style>
  .dashboard-container {
    padding: 20px;
    max-width: 100%;
    margin: 0 auto;
    box-sizing: border-box;
  }

  .dashboard-title {
    font-size: 24px;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    gap: 12px;
  }

/* Overview Cards */
  .stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    margin-bottom: 24px;
  }

  .stat-card {
    background: white;
    border-radius: 12px;
    padding: 16px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    border-left: 4px solid;
  }

  .stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 16px rgba(0,0,0,0.15);
}

.stat-card.primary { border-left-color: #3b82f6; }
.stat-card.success { border-left-color: #10b981; }
.stat-card.warning { border-left-color: #f59e0b; }
.stat-card.danger { border-left-color: #ef4444; }
.stat-card.info { border-left-color: #8b5cf6; }

  .stat-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
  }

  .stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: white;
}

.stat-card.primary .stat-icon { background: #3b82f6; }
.stat-card.success .stat-icon { background: #10b981; }
.stat-card.warning .stat-icon { background: #f59e0b; }
.stat-card.danger .stat-icon { background: #ef4444; }
.stat-card.info .stat-icon { background: #8b5cf6; }

.stat-value {
    font-size: 18px;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 4px;
  }

  .stat-label {
    font-size: 14px;
    color: #6b7280;
    font-weight: 500;
  }

/* Section Cards */
.section-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    margin-bottom: 20px;
  }

  .section-title {
    font-size: 18px;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
    padding-bottom: 10px;
    border-bottom: 2px solid #e5e7eb;
  }

  .section-title i {
    color: #3b82f6;
}

/* Table Styles */
.data-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 12px;
    font-size: 14px;
  }

.data-table thead {
    background: #f9fafb;
}

.data-table th {
    padding: 10px 12px;
    text-align: left;
    font-weight: 600;
    color: #374151;
    font-size: 13px;
    border-bottom: 2px solid #e5e7eb;
    white-space: nowrap;
}

.data-table td {
    padding: 10px 12px;
    border-bottom: 1px solid #e5e7eb;
    color: #1f2937;
    font-size: 13px;
}

.data-table tbody tr:hover {
    background: #f9fafb;
}

/* Badge Styles */
.badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
}

.badge-warning { background: #fef3c7; color: #92400e; }
.badge-success { background: #d1fae5; color: #065f46; }
.badge-danger { background: #fee2e2; color: #991b1b; }
.badge-info { background: #dbeafe; color: #1e40af; }
.badge-secondary { background: #f3f4f6; color: #374151; }

/* Notification Styles */
.notification-item {
    padding: 12px 16px;
    border-radius: 8px;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 12px;
    transition: all 0.2s;
  }

.notification-item:hover {
    transform: translateX(4px);
}

.notification-item.warning { background: #fef3c7; border-left: 4px solid #f59e0b; }
.notification-item.danger { background: #fee2e2; border-left: 4px solid #ef4444; }
.notification-item.info { background: #dbeafe; border-left: 4px solid #3b82f6; }

.notification-icon {
    font-size: 20px;
}

.notification-item.warning .notification-icon { color: #f59e0b; }
.notification-item.danger .notification-icon { color: #ef4444; }
.notification-item.info .notification-icon { color: #3b82f6; }

/* Action Item Styles */
.action-item {
    padding: 16px;
    border-radius: 8px;
    background: #f9fafb;
    margin-bottom: 12px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: all 0.2s;
}

.action-item:hover {
    background: #f3f4f6;
    transform: translateX(4px);
  }

.action-count {
    font-size: 24px;
    font-weight: 700;
    color: #3b82f6;
  }

/* Empty State */
.empty-state {
    text-align: center;
    padding: 40px 20px;
    color: #6b7280;
}

.empty-state i {
    font-size: 48px;
    margin-bottom: 16px;
    color: #d1d5db;
}

/* Two Column Layout */
.two-column {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
  }

/* Responsive */
@media (max-width: 1400px) {
    .stats-grid {
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 12px;
    }
    
    .stat-value {
        font-size: 17px;
    }
    
    .stat-icon {
        width: 40px;
        height: 40px;
        font-size: 18px;
    }
}

@media (max-width: 1200px) {
    .two-column {
        grid-template-columns: 1fr;
    }
    
    .dashboard-container {
        padding: 16px;
    }
  }

  @media (max-width: 768px) {
    .dashboard-container {
        padding: 12px;
    }

    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    }
    
    .stat-card {
        padding: 12px;
    }
    
    .stat-value {
        font-size: 16px;
    }
    
    .stat-label {
        font-size: 12px;
  }

    .section-card {
        padding: 16px;
  }

    .data-table {
        font-size: 12px;
  }

    .data-table th,
    .data-table td {
        padding: 8px;
    }
}
</style>

<div class="dashboard-container">
    <h1 class="dashboard-title">
        <i class="fas fa-chart-line"></i>
        Dashboard
    </h1>

    <!-- 1. Thống kê tổng quan -->
  <div class="stats-grid">
        <div class="stat-card primary">
      <div class="stat-header">
        <div class="stat-icon">
          <i class="fas fa-map-marked-alt"></i>
        </div>
      </div>
            <div class="stat-value"><?= number_format($stats['total_tours_active'] ?? 0) ?></div>
            <div class="stat-label">Tour đang mở bán</div>
        </div>

        <div class="stat-card success">
      <div class="stat-header">
        <div class="stat-icon">
          <i class="fas fa-calendar-check"></i>
        </div>
      </div>
            <div class="stat-value"><?= number_format($stats['bookings_today'] ?? 0) ?></div>
            <div class="stat-label">Booking hôm nay</div>
            <div style="font-size: 12px; color: #6b7280; margin-top: 4px;">
                <?= number_format($stats['bookings_week'] ?? 0) ?> trong tuần
            </div>
        </div>

        <div class="stat-card info">
      <div class="stat-header">
        <div class="stat-icon">
                    <i class="fas fa-calendar-alt"></i>
        </div>
      </div>
            <div class="stat-value"><?= number_format($stats['upcoming_departures'] ?? 0) ?></div>
            <div class="stat-label">Lịch khởi hành sắp tới</div>
        </div>

        <div class="stat-card warning">
            <div class="stat-header">
                <div class="stat-icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
        </div>
            <div class="stat-value"><?= formatPrice($stats['revenue_month'] ?? 0) ?></div>
            <div class="stat-label">Doanh thu tháng này</div>
        </div>

  </div>

    <!-- 2. Lịch khởi hành sắp tới -->
    <div class="section-card">
    <h3 class="section-title">
            <i class="fas fa-calendar-alt"></i>
            Lịch khởi hành sắp tới
    </h3>
        <?php if (!empty($upcomingDepartures)): ?>
            <div style="overflow-x: auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Tour</th>
                            <th>Ngày khởi hành</th>
                            <th>Số khách</th>
                            <th>Trạng thái</th>
                            <th>HDV</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($upcomingDepartures as $dep): ?>
                            <tr>
                                <td>
                                    <strong><?= htmlspecialchars($dep['ten_tour'] ?? 'N/A') ?></strong>
                                </td>
                                <td><?= formatDate($dep['ngay_khoi_hanh']) ?></td>
                                <td><?= number_format($dep['so_khach'] ?? 0) ?></td>
                                <td>
                                    <?php if ($dep['trang_thai'] == 'Assigned'): ?>
                                        <span class="badge badge-success">Assigned</span>
                                    <?php else: ?>
                                        <span class="badge badge-warning">Open</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($dep['hdv_phu_trach'] ?? 'Chưa phân công') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-calendar-times"></i>
                <p>Chưa có lịch khởi hành sắp tới</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- 3. Booking mới nhất -->
    <div class="section-card">
        <h3 class="section-title">
            <i class="fas fa-clipboard-list"></i>
            Booking mới nhất
        </h3>
        <?php if (!empty($recentBookings)): ?>
            <div style="overflow-x: auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Mã booking</th>
                            <th>Tên khách</th>
                            <th>Tour</th>
                            <th>Ngày đặt</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentBookings as $booking): ?>
                            <tr>
                                <td>
                                    <a href="<?= BASE_URL ?>?act=admin-booking-detail&id=<?= $booking['id'] ?>" 
                                       style="color: #3b82f6; font-weight: 600;">
                                        <?= htmlspecialchars($booking['ma_booking'] ?? 'N/A') ?>
                                    </a>
                                </td>
                                <td><?= htmlspecialchars($booking['ho_ten'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($booking['ten_tour'] ?? 'N/A') ?></td>
                                <td><?= formatDateTime($booking['ngay_dat']) ?></td>
                                <td style="color: #10b981; font-weight: 600;">
                                    <?= formatPrice($booking['tong_tien'] ?? 0) ?>
                                </td>
                                <td><?= getStatusBadge($booking['trang_thai'] ?? 0) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-clipboard"></i>
                <p>Chưa có booking nào</p>
    </div>
        <?php endif; ?>
  </div>

    <!-- 4. Tình trạng tour hôm nay -->
    <div class="section-card">
    <h3 class="section-title">
            <i class="fas fa-route"></i>
            Tình trạng tour hôm nay
    </h3>
        <?php if (!empty($todayTours)): ?>
            <div style="overflow-x: auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Tour</th>
                            <th>Giờ khởi hành</th>
                            <th>Điểm tập trung</th>
                            <th>HDV phụ trách</th>
                            <th>Số điện thoại</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($todayTours as $tour): ?>
                            <tr>
                                <td>
                                    <strong><?= htmlspecialchars($tour['ten_tour'] ?? 'N/A') ?></strong>
                                </td>
                                <td><?= $tour['gio_khoi_hanh'] ? date('H:i', strtotime($tour['gio_khoi_hanh'])) : '-' ?></td>
                                <td><?= htmlspecialchars($tour['diem_tap_trung'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($tour['hdv']['ho_ten'] ?? 'Chưa phân công') ?></td>
                                <td>
                                    <?php if (!empty($tour['hdv']['so_dien_thoai'])): ?>
                                        <a href="tel:<?= htmlspecialchars($tour['hdv']['so_dien_thoai']) ?>" 
                                           style="color: #3b82f6;">
                                            <?= htmlspecialchars($tour['hdv']['so_dien_thoai']) ?>
                                        </a>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-calendar-times"></i>
                <p>Không có tour khởi hành hôm nay</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- 7. Biểu đồ thống kê -->
    <div class="section-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 class="section-title" style="margin: 0;">
                <i class="fas fa-chart-bar"></i>
                Biểu đồ thống kê
            </h3>
            <div style="display: flex; gap: 8px;">
                <button class="chart-filter-btn active" data-period="day">Theo ngày</button>
                <button class="chart-filter-btn" data-period="week">Theo tuần</button>
                <button class="chart-filter-btn" data-period="month">Theo tháng</button>
  </div>
</div>

        <div style="position: relative; height: 400px;">
            <canvas id="statsChart"></canvas>
      </div>
    </div>
    
    </div>
    
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<style>
.chart-filter-btn {
    padding: 8px 16px;
    border: 1px solid #e5e7eb;
    background: white;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    color: #6b7280;
    transition: all 0.2s;
}

.chart-filter-btn:hover {
    background: #f9fafb;
    border-color: #3b82f6;
    color: #3b82f6;
}

.chart-filter-btn.active {
    background: #3b82f6;
    border-color: #3b82f6;
    color: white;
}
</style>

<script>
let statsChart = null;
let currentPeriod = 'day';

// Khởi tạo biểu đồ
function initChart(period = 'day') {
    currentPeriod = period;
    
  // Cập nhật active button
    document.querySelectorAll('.chart-filter-btn').forEach(btn => {
    btn.classList.remove('active');
    if (btn.dataset.period === period) {
      btn.classList.add('active');
    }
  });
  
    // Load dữ liệu
    fetch(`?act=admin-dashboard-chart-data&period=${period}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error('Error loading chart data:', data.error);
                return;
            }
  
            const ctx = document.getElementById('statsChart').getContext('2d');
            
            // Xóa biểu đồ cũ nếu có
            if (statsChart) {
                statsChart.destroy();
            }
            
            // Tạo biểu đồ mới
            statsChart = new Chart(ctx, {
                type: 'line',
      data: {
                    labels: data.labels,
                    datasets: [
                        {
                            label: 'Số booking',
                            data: data.bookings,
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            tension: 0.4,
                            fill: true,
                            yAxisID: 'y'
                        },
                        {
                            label: 'Doanh thu (triệu VNĐ)',
                            data: data.revenue.map(r => r / 1000000), // Chuyển sang triệu VNĐ
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            tension: 0.4,
                            fill: true,
                            yAxisID: 'y1'
                        }
                    ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
        plugins: {
          legend: {
                            display: true,
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.datasetIndex === 1) {
                                        // Doanh thu
                                        label += new Intl.NumberFormat('vi-VN').format(context.parsed.y) + ' triệu VNĐ';
                                    } else {
                                        // Booking
                                        label += context.parsed.y + ' booking';
                                    }
                                    return label;
                                }
                            }
          }
        },
        scales: {
          y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            title: {
                                display: true,
                                text: 'Số booking'
                            },
                            ticks: {
                                stepSize: 1
            }
          },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            title: {
                                display: true,
                                text: 'Doanh thu (triệu VNĐ)'
                            },
            grid: {
                                drawOnChartArea: false,
                            },
          }
        }
      }
    });
        })
        .catch(error => {
            console.error('Error loading chart:', error);
        });
}

// Event listeners cho các nút filter
document.querySelectorAll('.chart-filter-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const period = this.dataset.period;
        initChart(period);
    });
});

// Khởi tạo biểu đồ khi trang load
document.addEventListener('DOMContentLoaded', function() {
    initChart('day');
});
</script>

