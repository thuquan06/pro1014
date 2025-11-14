<?php
/**
 * views/admin/dashboard.php
 * Dashboard hiện đại, responsive, không có khách sạn
 */

// Lấy số liệu an toàn
$cnt1 = $stats['cnt1'] ?? 0;     // Hóa đơn
$cnt2 = $stats['cnt2'] ?? 0;     // Góp ý
$goi  = $stats['goi']  ?? 0;     // Tour
$cnt5 = $stats['cnt5'] ?? 0;     // Trợ giúp
$blog = $stats['blog'] ?? 0;     // Blog
?>

<style>
/* ===== BASE STYLES ===== */
.dash-wrap {
  padding: 0;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* ===== HEADER ===== */
.dash-header {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  padding: 32px 24px;
  border-radius: 16px;
  margin-bottom: 32px;
  box-shadow: 0 10px 40px rgba(102, 126, 234, 0.25);
  animation: fadeInDown 0.6s ease-out;
}

.dash-header h1 {
  margin: 0 0 8px 0;
  font-size: 32px;
  font-weight: 700;
  letter-spacing: -0.5px;
}

.dash-header p {
  margin: 0;
  opacity: 0.95;
  font-size: 15px;
}

/* ===== SECTION TITLE ===== */
.section-title {
  font-size: 20px;
  font-weight: 700;
  color: #2d3748;
  margin: 0 0 20px 0;
  display: flex;
  align-items: center;
  gap: 10px;
}

.section-title::before {
  content: '';
  width: 4px;
  height: 24px;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border-radius: 4px;
}

/* ===== QUICK ACTIONS ===== */
.quick-actions {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 20px;
  margin-bottom: 32px;
}

.quick-card {
  background: white;
  border-radius: 16px;
  padding: 24px;
  display: flex;
  align-items: center;
  gap: 20px;
  text-decoration: none;
  color: inherit;
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  border: 2px solid transparent;
  position: relative;
  overflow: hidden;
}

.quick-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 4px;
  background: linear-gradient(90deg, var(--card-color) 0%, var(--card-color-light) 100%);
  transform: scaleX(0);
  transform-origin: left;
  transition: transform 0.3s ease;
}

.quick-card:hover::before {
  transform: scaleX(1);
}

.quick-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 12px 32px rgba(0, 0, 0, 0.12);
  border-color: var(--card-color);
}

.quick-card.tour {
  --card-color: #667eea;
  --card-color-light: #764ba2;
}

.quick-card.blog {
  --card-color: #f093fb;
  --card-color-light: #f5576c;
}

.quick-icon {
  width: 64px;
  height: 64px;
  border-radius: 16px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 28px;
  color: white;
  background: linear-gradient(135deg, var(--card-color) 0%, var(--card-color-light) 100%);
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
  flex-shrink: 0;
  transition: transform 0.3s ease;
}

.quick-card:hover .quick-icon {
  transform: scale(1.1) rotate(5deg);
}

.quick-text h3 {
  margin: 0 0 4px 0;
  font-size: 18px;
  font-weight: 700;
  color: #2d3748;
}

.quick-text p {
  margin: 0;
  font-size: 13px;
  color: #718096;
}

/* ===== STATS GRID ===== */
.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 20px;
  margin-bottom: 32px;
}

.stat-card {
  background: white;
  border-radius: 16px;
  padding: 24px;
  text-align: center;
  text-decoration: none;
  color: inherit;
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  border: 2px solid transparent;
  position: relative;
  overflow: hidden;
}

.stat-card::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  height: 100%;
  background: linear-gradient(135deg, var(--stat-color) 0%, var(--stat-color-light) 100%);
  opacity: 0;
  transition: opacity 0.3s ease;
  z-index: 0;
}

.stat-card:hover::after {
  opacity: 0.05;
}

.stat-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 12px 32px rgba(0, 0, 0, 0.12);
  border-color: var(--stat-color);
}

.stat-card.invoice {
  --stat-color: #4299e1;
  --stat-color-light: #3182ce;
}

.stat-card.feedback {
  --stat-color: #48bb78;
  --stat-color-light: #38a169;
}

.stat-card.tour {
  --stat-color: #ed8936;
  --stat-color-light: #dd6b20;
}

.stat-card.help {
  --stat-color: #9f7aea;
  --stat-color-light: #805ad5;
}

.stat-card.blog {
  --stat-color: #f56565;
  --stat-color-light: #e53e3e;
}

.stat-icon {
  width: 56px;
  height: 56px;
  margin: 0 auto 16px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 24px;
  color: white;
  background: linear-gradient(135deg, var(--stat-color) 0%, var(--stat-color-light) 100%);
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
  position: relative;
  z-index: 1;
  transition: transform 0.3s ease;
}

.stat-card:hover .stat-icon {
  transform: scale(1.15);
}

.stat-number {
  font-size: 36px;
  font-weight: 800;
  color: var(--stat-color);
  margin: 0 0 8px 0;
  position: relative;
  z-index: 1;
  transition: color 0.3s ease;
}

.stat-card:hover .stat-number {
  color: var(--stat-color-light);
}

.stat-label {
  font-size: 14px;
  font-weight: 600;
  color: #718096;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  position: relative;
  z-index: 1;
}

/* ===== ANIMATIONS ===== */
@keyframes fadeInDown {
  from {
    opacity: 0;
    transform: translateY(-20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.quick-card,
.stat-card {
  animation: fadeInUp 0.6s ease-out backwards;
}

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

.quick-card:nth-child(1) { animation-delay: 0.1s; }
.quick-card:nth-child(2) { animation-delay: 0.2s; }

.stat-card:nth-child(1) { animation-delay: 0.1s; }
.stat-card:nth-child(2) { animation-delay: 0.2s; }
.stat-card:nth-child(3) { animation-delay: 0.3s; }
.stat-card:nth-child(4) { animation-delay: 0.4s; }
.stat-card:nth-child(5) { animation-delay: 0.5s; }

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
  .dash-header {
    padding: 24px 16px;
  }
  
  .dash-header h1 {
    font-size: 24px;
  }
  
  .quick-actions {
    grid-template-columns: 1fr;
  }
  
  .stats-grid {
    grid-template-columns: repeat(2, 1fr);
  }
  
  .section-title {
    font-size: 18px;
  }
}

@media (max-width: 480px) {
  .stats-grid {
    grid-template-columns: 1fr;
  }
  
  .quick-card {
    padding: 20px;
  }
  
  .quick-icon {
    width: 56px;
    height: 56px;
    font-size: 24px;
  }
}
</style>

<div class="dash-wrap">
  
  <!-- Header -->
  <div class="dash-header">
    <h1>👋 Chào mừng trở lại!</h1>
    <p>Quản lý tour du lịch của bạn một cách dễ dàng</p>
  </div>

  <!-- Quick Actions -->
  <h2 class="section-title">🚀 Hành động nhanh</h2>
  <div class="quick-actions">
    
    <a href="<?= BASE_URL ?>?act=admin-tour-create" class="quick-card tour">
      <div class="quick-icon">
        <i class="glyphicon glyphicon-road"></i>
      </div>
      <div class="quick-text">
        <h3>Thêm Tour Mới</h3>
        <p>Tạo gói tour du lịch mới</p>
      </div>
    </a>

    <a href="#" class="quick-card blog">
      <div class="quick-icon">
        <i class="glyphicon glyphicon-pencil"></i>
      </div>
      <div class="quick-text">
        <h3>Viết Blog</h3>
        <p>Chia sẻ tin tức và mẹo du lịch</p>
      </div>
    </a>

  </div>

  <!-- Stats -->
  <h2 class="section-title">📊 Tổng quan hệ thống</h2>
  <div class="stats-grid">
    
    <a href="#" class="stat-card invoice">
      <div class="stat-icon">
        <i class="glyphicon glyphicon-credit-card"></i>
      </div>
      <div class="stat-number"><?= number_format($cnt1) ?></div>
      <div class="stat-label">Hóa đơn</div>
    </a>

    <a href="#" class="stat-card feedback">
      <div class="stat-icon">
        <i class="glyphicon glyphicon-comment"></i>
      </div>
      <div class="stat-number"><?= number_format($cnt2) ?></div>
      <div class="stat-label">Góp ý</div>
    </a>

    <a href="<?= BASE_URL ?>?act=admin-tours" class="stat-card tour">
      <div class="stat-icon">
        <i class="glyphicon glyphicon-road"></i>
      </div>
      <div class="stat-number"><?= number_format($goi) ?></div>
      <div class="stat-label">Tour</div>
    </a>

    <a href="#" class="stat-card help">
      <div class="stat-icon">
        <i class="glyphicon glyphicon-question-sign"></i>
      </div>
      <div class="stat-number"><?= number_format($cnt5) ?></div>
      <div class="stat-label">Trợ giúp</div>
    </a>

    <a href="#" class="stat-card blog">
      <div class="stat-icon">
        <i class="glyphicon glyphicon-book"></i>
      </div>
      <div class="stat-number"><?= number_format($blog) ?></div>
      <div class="stat-label">Blog</div>
    </a>

  </div>

</div>