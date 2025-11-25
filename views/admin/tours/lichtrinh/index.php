<?php
/**
 * Lịch trình Tour - Modern Interface
 * Updated: 2025-11-25
 */

ob_start();
?>

<style>
.schedule-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
  flex-wrap: wrap;
  gap: 16px;
}

.schedule-title-section {
  flex: 1;
  min-width: 300px;
}

.schedule-title {
  font-size: 28px;
  font-weight: 700;
  color: var(--text-dark);
  margin: 0 0 12px 0;
}

.tour-selector {
  background: white;
  border: 1px solid var(--border);
  border-radius: 8px;
  padding: 12px 16px;
  font-size: 14px;
  width: 100%;
  max-width: 400px;
  transition: all 0.2s;
}

.tour-selector:focus {
  outline: none;
  border-color: var(--primary);
  box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.schedule-actions {
  display: flex;
  gap: 12px;
  flex-wrap: wrap;
}

.timeline-card {
  background: white;
  border: 1px solid var(--border);
  border-radius: 12px;
  overflow: hidden;
  margin-bottom: 20px;
}

.timeline-container {
  position: relative;
  padding: 40px 0 40px 80px;
}

.timeline-line {
  position: absolute;
  left: 40px;
  top: 0;
  bottom: 0;
  width: 4px;
  background: linear-gradient(to bottom, var(--primary), #10b981);
}

.timeline-item {
  position: relative;
  margin-bottom: 32px;
}

.timeline-dot {
  position: absolute;
  left: -60px;
  top: 8px;
  width: 60px;
  height: 60px;
  background: var(--primary);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 20px;
  font-weight: 700;
  color: white;
  border: 4px solid white;
  box-shadow: 0 0 0 2px var(--primary);
  z-index: 2;
}

.day-card {
  background: white;
  border: 1px solid var(--border);
  border-radius: 12px;
  padding: 24px;
  transition: all 0.3s;
  margin-left: 20px;
}

.day-card:hover {
  transform: translateX(8px);
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
}

.day-header {
  display: flex;
  justify-content: space-between;
  align-items: start;
  margin-bottom: 16px;
  padding-bottom: 16px;
  border-bottom: 2px solid var(--bg-light);
}

.day-title {
  font-size: 20px;
  font-weight: 700;
  color: var(--text-dark);
  margin: 0;
}

.day-actions {
  display: flex;
  gap: 8px;
}

.btn-icon {
  width: 36px;
  height: 36px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 8px;
  border: none;
  cursor: pointer;
  transition: all 0.2s;
  text-decoration: none;
}

.btn-icon.edit {
  background: #fef3c7;
  color: #78350f;
}

.btn-icon.edit:hover {
  background: #f59e0b;
  color: white;
}

.btn-icon.delete {
  background: #fee2e2;
  color: #991b1b;
}

.btn-icon.delete:hover {
  background: #ef4444;
  color: white;
}

.day-content {
  color: var(--text-dark);
  line-height: 1.7;
  margin-bottom: 16px;
}

.info-badge {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 8px 14px;
  border-radius: 20px;
  font-size: 13px;
  font-weight: 600;
  margin-right: 8px;
  margin-bottom: 8px;
}

.info-badge.location {
  background: #d1fae5;
  color: #065f46;
}

.info-badge.duration {
  background: #fef3c7;
  color: #78350f;
}

.info-badge.meal {
  background: #dbeafe;
  color: #1e40af;
}

.info-badge.hotel {
  background: #e0e7ff;
  color: #3730a3;
}

.info-box {
  background: var(--bg-light);
  border-left: 4px solid var(--primary);
  border-radius: 8px;
  padding: 16px;
  margin: 16px 0;
}

.info-box.activities {
  border-left-color: #10b981;
  background: #f0fdf4;
}

.info-box.guide-note {
  border-left-color: #f59e0b;
  background: #fffbeb;
}

.info-box-title {
  font-weight: 700;
  color: var(--text-dark);
  margin: 0 0 12px 0;
  display: flex;
  align-items: center;
  gap: 8px;
}

.info-box-content {
  color: var(--text-dark);
  line-height: 1.6;
  white-space: pre-wrap;
}

.empty-state {
  text-align: center;
  padding: 80px 20px;
  color: var(--text-light);
}

.empty-state i {
  font-size: 80px;
  opacity: 0.3;
  margin-bottom: 20px;
}

.empty-state h3 {
  font-size: 20px;
  margin: 20px 0 12px;
  color: var(--text-dark);
}

.summary-card {
  background: linear-gradient(135deg, var(--primary), #1e40af);
  border-radius: 12px;
  padding: 20px;
  color: white;
  text-align: center;
  margin-top: 24px;
}

.summary-card h4 {
  margin: 0;
  font-size: 32px;
  font-weight: 700;
}

.summary-card p {
  margin: 8px 0 0;
  opacity: 0.9;
}

@media (max-width: 768px) {
  .timeline-container {
    padding-left: 60px;
  }
  
  .timeline-line {
    left: 30px;
  }
  
  .timeline-dot {
    left: -50px;
    width: 50px;
    height: 50px;
    font-size: 16px;
  }
  
  .day-card {
    margin-left: 10px;
  }
}
</style>

<!-- Page Header -->
<div class="schedule-header">
  <div class="schedule-title-section">
    <h1 class="schedule-title">
      <i class="fas fa-calendar-alt" style="color: var(--primary);"></i>
      Quản lý Lịch trình
    </h1>
    <select id="select-tour" class="tour-selector" onchange="if(this.value) window.location.href='<?= BASE_URL ?>?act=tour-lichtrinh&id_goi=' + this.value">
      <option value="">-- Chọn tour để xem lịch trình --</option>
      <?php if (!empty($allTours)): ?>
        <?php foreach ($allTours as $tour): ?>
          <option value="<?= $tour['id'] ?>" <?= $tour['id'] == $idGoi ? 'selected' : '' ?>>
            #<?= $tour['id'] ?> - <?= htmlspecialchars($tour['ten_goi']) ?>
          </option>
        <?php endforeach; ?>
      <?php endif; ?>
    </select>
  </div>
  
  <div class="schedule-actions">
    <a href="<?= BASE_URL ?>?act=tour-lichtrinh-them&id_goi=<?= $idGoi ?>" class="btn btn-primary">
      <i class="fas fa-plus-circle"></i>
      Thêm ngày mới
    </a>
    <a href="<?= BASE_URL ?>?act=admin-tours" class="btn btn-secondary">
      <i class="fas fa-arrow-left"></i>
      Quay lại
    </a>
  </div>
</div>

<!-- Thông báo -->
<?php if (isset($_SESSION['success'])): ?>
  <div class="alert alert-success">
    <i class="fas fa-check-circle"></i>
    <?= $_SESSION['success'] ?>
  </div>
  <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
  <div class="alert alert-error">
    <i class="fas fa-exclamation-circle"></i>
    <?= $_SESSION['error'] ?>
  </div>
  <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<!-- Danh sách lịch trình -->
<div class="timeline-card">
  <?php if (empty($lichtrinh)): ?>
    <div class="empty-state">
      <i class="fas fa-calendar-times"></i>
      <h3>Chưa có lịch trình</h3>
      <p>Hãy thêm lịch trình chi tiết cho tour này</p>
      <br>
      <a href="<?= BASE_URL ?>?act=tour-lichtrinh-them&id_goi=<?= $idGoi ?>" class="btn btn-primary">
        <i class="fas fa-plus-circle"></i>
        Thêm lịch trình đầu tiên
      </a>
    </div>
  <?php else: ?>
    <div class="timeline-container">
      <div class="timeline-line"></div>
      
      <?php foreach ($lichtrinh as $index => $ngay): ?>
        <div class="timeline-item">
          <div class="timeline-dot">
            <?= $ngay['ngay_thu'] ?>
          </div>
          
          <div class="day-card">
            <div class="day-header">
              <h3 class="day-title">
                <?= htmlspecialchars($ngay['tieude']) ?>
              </h3>
              <div class="day-actions">
                <a href="<?= BASE_URL ?>?act=tour-lichtrinh-sua&id=<?= $ngay['id'] ?>&id_goi=<?= $idGoi ?>" 
                   class="btn-icon edit" title="Chỉnh sửa">
                  <i class="fas fa-edit"></i>
                </a>
                <a href="<?= BASE_URL ?>?act=tour-lichtrinh-xoa&id=<?= $ngay['id'] ?>&id_goi=<?= $idGoi ?>" 
                   class="btn-icon delete" 
                   onclick="return confirm('Bạn có chắc muốn xóa lịch trình ngày <?= $ngay['ngay_thu'] ?>?')"
                   title="Xóa">
                  <i class="fas fa-trash"></i>
                </a>
              </div>
            </div>
            
            <div class="day-content">
              <?= nl2br(htmlspecialchars($ngay['mota'])) ?>
            </div>
            
            <!-- Info Badges -->
            <div style="margin: 16px 0;">
              <?php if (!empty($ngay['diemden'])): ?>
                <span class="info-badge location">
                  <i class="fas fa-map-marker-alt"></i>
                  <?= htmlspecialchars($ngay['diemden']) ?>
                </span>
              <?php endif; ?>
              
              <?php if (!empty($ngay['thoiluong'])): ?>
                <span class="info-badge duration">
                  <i class="fas fa-clock"></i>
                  <?= htmlspecialchars($ngay['thoiluong']) ?>
                </span>
              <?php endif; ?>
              
              <?php if (!empty($ngay['buaan'])): ?>
                <span class="info-badge meal">
                  <i class="fas fa-utensils"></i>
                  <?= htmlspecialchars($ngay['buaan']) ?>
                </span>
              <?php endif; ?>
              
              <?php if (!empty($ngay['noinghi'])): ?>
                <span class="info-badge hotel">
                  <i class="fas fa-hotel"></i>
                  <?= htmlspecialchars($ngay['noinghi']) ?>
                </span>
              <?php endif; ?>
            </div>
            
            <!-- Hoạt động -->
            <?php if (!empty($ngay['hoatdong'])): ?>
              <div class="info-box activities">
                <h4 class="info-box-title">
                  <i class="fas fa-list-ul"></i>
                  Hoạt động trong ngày
                </h4>
                <div class="info-box-content">
                  <?= htmlspecialchars($ngay['hoatdong']) ?>
                </div>
              </div>
            <?php endif; ?>
            
            <!-- Ghi chú HDV -->
            <?php if (!empty($ngay['ghichu_hdv'])): ?>
              <div class="info-box guide-note">
                <h4 class="info-box-title">
                  <i class="fas fa-user-secret"></i>
                  Ghi chú cho HDV (nội bộ)
                </h4>
                <div class="info-box-content">
                  <?= nl2br(htmlspecialchars($ngay['ghichu_hdv'])) ?>
                </div>
              </div>
            <?php endif; ?>
            
            <div style="margin-top: 16px; padding-top: 16px; border-top: 1px solid var(--border); color: var(--text-light); font-size: 12px;">
              <i class="fas fa-clock"></i>
              Tạo lúc: <?= date('d/m/Y H:i', strtotime($ngay['thoigian_tao'])) ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Tổng kết -->
    <div class="summary-card">
      <h4><?= count($lichtrinh) ?></h4>
      <p>Tổng số ngày lịch trình</p>
    </div>
  <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
include './views/admin/layout.php';
?>
