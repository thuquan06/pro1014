<?php
/**
 * Admin Dashboard - GLASSMORPHISM DESIGN
 * Version: 4.0 - Ultra Colorful
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
  .dashboard-header {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
  }
  
  .welcome-card {
    grid-column: 1 / -1;
    background: linear-gradient(135deg, #FF6B6B 0%, #4ECDC4 100%);
    border-radius: 30px;
    padding: 40px;
    color: white;
    position: relative;
    overflow: hidden;
    box-shadow: 0 15px 40px rgba(255, 107, 107, 0.3);
  }
  
  .welcome-card::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 300px;
    height: 300px;
    background: radial-gradient(circle, rgba(255, 255, 255, 0.2) 0%, transparent 70%);
    animation: float 6s ease-in-out infinite;
  }
  
  @keyframes float {
    0%, 100% { transform: translate(0, 0) scale(1); }
    50% { transform: translate(-20px, 20px) scale(1.1); }
  }
  
  .welcome-card h2 {
    font-size: 42px;
    font-weight: 900;
    margin: 0 0 10px 0;
    text-shadow: 0 2px 20px rgba(0, 0, 0, 0.2);
  }
  
  .welcome-card p {
    font-size: 18px;
    margin: 0;
    opacity: 0.95;
    font-weight: 600;
  }
  
  .welcome-card .emoji {
    position: absolute;
    right: 50px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 120px;
    opacity: 0.15;
  }
  
  .mini-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 15px;
    margin-bottom: 30px;
  }
  
  .mini-stat {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 25px 20px;
    text-align: center;
    border: 2px solid rgba(255, 255, 255, 0.5);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    transition: all 0.3s;
    position: relative;
    overflow: hidden;
  }
  
  .mini-stat::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 4px;
    background: linear-gradient(90deg, var(--primary) 0%, var(--secondary) 100%);
  }
  
  .mini-stat:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
  }
  
  .mini-stat-icon {
    width: 60px;
    height: 60px;
    margin: 0 auto 15px;
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    color: white;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
  }
  
  .mini-stat-icon.red { background: linear-gradient(135deg, #FF6B6B 0%, #FF5A5F 100%); }
  .mini-stat-icon.blue { background: linear-gradient(135deg, #4ECDC4 0%, #5DADE2 100%); }
  .mini-stat-icon.yellow { background: linear-gradient(135deg, #FFD93D 0%, #FFA07A 100%); }
  .mini-stat-icon.green { background: linear-gradient(135deg, #6BCF7F 0%, #4ECDC4 100%); }
  .mini-stat-icon.purple { background: linear-gradient(135deg, #C77DFF 0%, #FF6BB5 100%); }
  .mini-stat-icon.orange { background: linear-gradient(135deg, #FFA07A 0%, #FF6B6B 100%); }
  
  .mini-stat h3 {
    font-size: 36px;
    font-weight: 900;
    color: #2c3e50;
    margin: 0 0 5px 0;
    line-height: 1;
  }
  
  .mini-stat p {
    font-size: 13px;
    color: #5a6c7d;
    margin: 0;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }
  
  .dashboard-grid {
    display: grid;
    grid-template-columns: repeat(12, 1fr);
    gap: 25px;
    margin-bottom: 30px;
  }
  
  .col-12 { grid-column: span 12; }
  .col-8 { grid-column: span 8; }
  .col-4 { grid-column: span 4; }
  .col-6 { grid-column: span 6; }
  
  @media (max-width: 1200px) {
    .col-8, .col-4, .col-6 { grid-column: span 12; }
  }
  
  .action-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
  }
  
  .action-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 22px;
    padding: 30px;
    text-align: center;
    text-decoration: none;
    border: 2px solid rgba(255, 255, 255, 0.5);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    transition: all 0.4s;
    position: relative;
    overflow: hidden;
  }
  
  .action-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, transparent 0%, rgba(255, 107, 107, 0.1) 100%);
    opacity: 0;
    transition: opacity 0.3s;
  }
  
  .action-card:hover::before {
    opacity: 1;
  }
  
  .action-card:hover {
    transform: translateY(-10px) rotate(-2deg);
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);
    border-color: var(--primary);
  }
  
  .action-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 20px;
    border-radius: 22px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 40px;
    color: white;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    position: relative;
    z-index: 1;
  }
  
  .action-card h4 {
    font-size: 17px;
    font-weight: 800;
    color: #2c3e50;
    margin: 0;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    position: relative;
    z-index: 1;
  }
  
  .chart-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 25px;
    padding: 30px;
    border: 2px solid rgba(255, 255, 255, 0.5);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
  }
  
  .chart-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
  }
  
  .chart-header h3 {
    font-size: 24px;
    font-weight: 900;
    color: #2c3e50;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 12px;
  }
  
  .chart-header h3 i {
    font-size: 28px;
    background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }
  
  .chart-container {
    position: relative;
    height: 350px;
  }
  
  .activity-feed {
    max-height: 500px;
    overflow-y: auto;
    padding-right: 10px;
  }
  
  .activity-feed::-webkit-scrollbar {
    width: 6px;
  }
  
  .activity-feed::-webkit-scrollbar-thumb {
    background: linear-gradient(180deg, var(--primary) 0%, var(--secondary) 100%);
    border-radius: 10px;
  }
  
  .activity-item {
    display: flex;
    gap: 18px;
    padding: 20px;
    border-radius: 18px;
    margin-bottom: 12px;
    transition: all 0.3s;
    background: rgba(255, 255, 255, 0.5);
    border: 1px solid rgba(0, 0, 0, 0.05);
  }
  
  .activity-item:hover {
    background: rgba(255, 255, 255, 0.9);
    transform: translateX(5px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
  }
  
  .activity-icon {
    min-width: 50px;
    width: 50px;
    height: 50px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    color: white;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
  }
  
  .activity-content {
    flex: 1;
  }
  
  .activity-content h5 {
    font-size: 15px;
    font-weight: 700;
    color: #2c3e50;
    margin: 0 0 5px 0;
  }
  
  .activity-content p {
    font-size: 14px;
    color: #5a6c7d;
    margin: 0;
  }
  
  .activity-time {
    font-size: 12px;
    color: #95a5a6;
    font-weight: 600;
    white-space: nowrap;
  }
  
  .progress-section {
    padding: 15px 0;
  }
  
  .progress-item {
    margin-bottom: 25px;
  }
  
  .progress-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
  }
  
  .progress-label {
    font-weight: 700;
    color: #2c3e50;
    font-size: 15px;
  }
  
  .progress-value {
    font-weight: 900;
    font-size: 16px;
    background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }
  
  .progress-bar-container {
    height: 12px;
    background: rgba(0, 0, 0, 0.05);
    border-radius: 20px;
    overflow: hidden;
    box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
  }
  
  .progress-bar-fill {
    height: 100%;
    border-radius: 20px;
    transition: width 1.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
  }
</style>

<!-- Welcome Card -->
<div class="welcome-card">
  <div style="position: relative; z-index: 1;">
    <h2>Welcome Back, <?= htmlspecialchars($_SESSION['alogin'] ?? 'Admin') ?>! 🎉</h2>
    <p>Here's what's happening with your travel business today</p>
  </div>
  <div class="emoji">✈️</div>
</div>

<!-- Mini Stats -->
<div class="mini-stats">
  <div class="mini-stat">
    <div class="mini-stat-icon red">
      <i class="fas fa-file-invoice-dollar"></i>
    </div>
    <h3><?= number_format($cnt1) ?></h3>
    <p>Invoices</p>
  </div>
  
  <div class="mini-stat">
    <div class="mini-stat-icon blue">
      <i class="fas fa-map-marked-alt"></i>
    </div>
    <h3><?= number_format($goi) ?></h3>
    <p>Tours</p>
  </div>
  
  <div class="mini-stat">
    <div class="mini-stat-icon yellow">
      <i class="fas fa-newspaper"></i>
    </div>
    <h3><?= number_format($blog) ?></h3>
    <p>Blogs</p>
  </div>
  
  <div class="mini-stat">
    <div class="mini-stat-icon green">
      <i class="fas fa-hotel"></i>
    </div>
    <h3><?= number_format($ks) ?></h3>
    <p>Hotels</p>
  </div>
  
  <div class="mini-stat">
    <div class="mini-stat-icon purple">
      <i class="fas fa-comments"></i>
    </div>
    <h3><?= number_format($cnt2) ?></h3>
    <p>Feedback</p>
  </div>
  
  <div class="mini-stat">
    <div class="mini-stat-icon orange">
      <i class="fas fa-life-ring"></i>
    </div>
    <h3><?= number_format($cnt5) ?></h3>
    <p>Support</p>
  </div>
</div>

<!-- Quick Actions -->
<div class="card glass-white">
  <div class="card-header">
    <h3><i class="fas fa-bolt"></i> Quick Actions</h3>
  </div>
  <div class="card-body">
    <div class="action-cards">
      <a href="<?= BASE_URL ?>?act=admin-tour-create" class="action-card">
        <div class="action-icon" style="background: linear-gradient(135deg, #FF6B6B 0%, #FF5A5F 100%);">
          <i class="fas fa-plus-circle"></i>
        </div>
        <h4>New Tour</h4>
      </a>
      
      <a href="<?= BASE_URL ?>?act=blog-create" class="action-card">
        <div class="action-icon" style="background: linear-gradient(135deg, #4ECDC4 0%, #5DADE2 100%);">
          <i class="fas fa-pen-fancy"></i>
        </div>
        <h4>Write Blog</h4>
      </a>
      
      <a href="<?= BASE_URL ?>?act=hoadon-list" class="action-card">
        <div class="action-icon" style="background: linear-gradient(135deg, #FFD93D 0%, #FFA07A 100%);">
          <i class="fas fa-file-invoice"></i>
        </div>
        <h4>Invoices</h4>
      </a>
      
      <a href="<?= BASE_URL ?>?act=province-create" class="action-card">
        <div class="action-icon" style="background: linear-gradient(135deg, #C77DFF 0%, #FF6BB5 100%);">
          <i class="fas fa-map-marker-alt"></i>
        </div>
        <h4>Add Location</h4>
      </a>
    </div>
  </div>
</div>

<!-- Charts & Activities -->
<div class="dashboard-grid">
  <!-- Revenue Chart -->
  <div class="col-8">
    <div class="chart-card">
      <div class="chart-header">
        <h3><i class="fas fa-chart-area"></i> Revenue Overview</h3>
        <select style="padding: 10px 15px; border-radius: 12px; border: 2px solid rgba(0,0,0,0.1); font-weight: 600; background: white;">
          <option>Last 6 Months</option>
          <option>Last Year</option>
          <option>This Year</option>
        </select>
      </div>
      <div class="chart-container">
        <canvas id="revenueChart"></canvas>
      </div>
    </div>
  </div>
  
  <!-- Recent Activity -->
  <div class="col-4">
    <div class="chart-card">
      <div class="chart-header">
        <h3><i class="fas fa-history"></i> Recent Activity</h3>
      </div>
      <div class="activity-feed">
        <div class="activity-item">
          <div class="activity-icon" style="background: linear-gradient(135deg, #FF6B6B 0%, #FF5A5F 100%);">
            <i class="fas fa-map-marked-alt"></i>
          </div>
          <div class="activity-content">
            <h5>New Tour Added</h5>
            <p>Hanoi - Halong Bay Tour created</p>
          </div>
          <div class="activity-time">2h ago</div>
        </div>
        
        <div class="activity-item">
          <div class="activity-icon" style="background: linear-gradient(135deg, #6BCF7F 0%, #4ECDC4 100%);">
            <i class="fas fa-check-circle"></i>
          </div>
          <div class="activity-content">
            <h5>Order Confirmed</h5>
            <p>Invoice #12345 paid successfully</p>
          </div>
          <div class="activity-time">3h ago</div>
        </div>
        
        <div class="activity-item">
          <div class="activity-icon" style="background: linear-gradient(135deg, #FFD93D 0%, #FFA07A 100%);">
            <i class="fas fa-newspaper"></i>
          </div>
          <div class="activity-content">
            <h5>Blog Published</h5>
            <p>"Top 10 Summer Destinations"</p>
          </div>
          <div class="activity-time">5h ago</div>
        </div>
        
        <div class="activity-item">
          <div class="activity-icon" style="background: linear-gradient(135deg, #C77DFF 0%, #FF6BB5 100%);">
            <i class="fas fa-star"></i>
          </div>
          <div class="activity-content">
            <h5>New Review</h5>
            <p>5-star rating for Danang Tour</p>
          </div>
          <div class="activity-time">1d ago</div>
        </div>
        
        <div class="activity-item">
          <div class="activity-icon" style="background: linear-gradient(135deg, #5DADE2 0%, #4ECDC4 100%);">
            <i class="fas fa-user-plus"></i>
          </div>
          <div class="activity-content">
            <h5>New Customer</h5>
            <p>Nguyen Van A registered</p>
          </div>
          <div class="activity-time">1d ago</div>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Tour Categories -->
  <div class="col-6">
    <div class="chart-card">
      <div class="chart-header">
        <h3><i class="fas fa-chart-pie"></i> Tour Categories</h3>
      </div>
      <div class="chart-container" style="height: 320px;">
        <canvas id="categoriesChart"></canvas>
      </div>
    </div>
  </div>
  
  <!-- Top Performing Tours -->
  <div class="col-6">
    <div class="chart-card">
      <div class="chart-header">
        <h3><i class="fas fa-fire"></i> Top Performing</h3>
      </div>
      <div class="progress-section">
        <div class="progress-item">
          <div class="progress-header">
            <span class="progress-label">Hanoi - Halong Bay</span>
            <span class="progress-value">92%</span>
          </div>
          <div class="progress-bar-container">
            <div class="progress-bar-fill" style="width: 92%; background: linear-gradient(90deg, #FF6B6B 0%, #FF5A5F 100%);"></div>
          </div>
        </div>
        
        <div class="progress-item">
          <div class="progress-header">
            <span class="progress-label">Danang - Hoi An</span>
            <span class="progress-value">85%</span>
          </div>
          <div class="progress-bar-container">
            <div class="progress-bar-fill" style="width: 85%; background: linear-gradient(90deg, #4ECDC4 0%, #5DADE2 100%);"></div>
          </div>
        </div>
        
        <div class="progress-item">
          <div class="progress-header">
            <span class="progress-label">Phu Quoc Island</span>
            <span class="progress-value">78%</span>
          </div>
          <div class="progress-bar-container">
            <div class="progress-bar-fill" style="width: 78%; background: linear-gradient(90deg, #FFD93D 0%, #FFA07A 100%);"></div>
          </div>
        </div>
        
        <div class="progress-item">
          <div class="progress-header">
            <span class="progress-label">Nha Trang Beach</span>
            <span class="progress-value">71%</span>
          </div>
          <div class="progress-bar-container">
            <div class="progress-bar-fill" style="width: 71%; background: linear-gradient(90deg, #6BCF7F 0%, #4ECDC4 100%);"></div>
          </div>
        </div>
        
        <div class="progress-item">
          <div class="progress-header">
            <span class="progress-label">Sapa Adventure</span>
            <span class="progress-value">65%</span>
          </div>
          <div class="progress-bar-container">
            <div class="progress-bar-fill" style="width: 65%; background: linear-gradient(90deg, #C77DFF 0%, #FF6BB5 100%);"></div>
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
      labels: ['June', 'July', 'August', 'September', 'October', 'November'],
      datasets: [{
        label: 'Revenue (Million VND)',
        data: [450, 520, 480, 650, 720, 850],
        borderColor: '#FF6B6B',
        backgroundColor: 'rgba(255, 107, 107, 0.1)',
        borderWidth: 4,
        tension: 0.4,
        fill: true,
        pointBackgroundColor: '#FF6B6B',
        pointBorderColor: '#fff',
        pointBorderWidth: 3,
        pointRadius: 8,
        pointHoverRadius: 12,
        pointHoverBackgroundColor: '#FF5A5F',
        pointHoverBorderWidth: 4
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { display: false },
        tooltip: {
          backgroundColor: 'rgba(0, 0, 0, 0.9)',
          padding: 16,
          titleFont: { size: 16, weight: 'bold' },
          bodyFont: { size: 14 },
          cornerRadius: 12,
          displayColors: false
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          grid: { color: 'rgba(0, 0, 0, 0.05)' },
          ticks: {
            font: { size: 13, weight: '600' },
            callback: function(value) { return value + 'M'; }
          }
        },
        x: {
          grid: { display: false },
          ticks: { font: { size: 13, weight: '600' } }
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
      labels: ['Beach Tours', 'Mountain Tours', 'City Tours', 'Cultural Tours', 'Others'],
      datasets: [{
        data: [35, 25, 20, 15, 5],
        backgroundColor: [
          'rgba(255, 107, 107, 0.9)',
          'rgba(78, 205, 196, 0.9)',
          'rgba(255, 217, 61, 0.9)',
          'rgba(199, 125, 255, 0.9)',
          'rgba(255, 107, 181, 0.9)'
        ],
        borderColor: '#fff',
        borderWidth: 4
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'bottom',
          labels: {
            padding: 20,
            font: { size: 13, weight: '700' },
            usePointStyle: true,
            pointStyle: 'circle'
          }
        },
        tooltip: {
          backgroundColor: 'rgba(0, 0, 0, 0.9)',
          padding: 16,
          titleFont: { size: 16, weight: 'bold' },
          bodyFont: { size: 14 },
          cornerRadius: 12,
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
  document.querySelectorAll('.progress-bar-fill').forEach(bar => {
    const width = bar.style.width;
    bar.style.width = '0%';
    setTimeout(() => {
      bar.style.width = width;
    }, 300);
  });
});
</script>
