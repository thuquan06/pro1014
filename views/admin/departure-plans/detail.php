<?php
/**
 * Departure Plan Detail View
 * Hiển thị chi tiết lịch khởi hành
 */

function formatDateTime($date, $time = null) {
    if (!$date) return 'N/A';
    $dateStr = date('d/m/Y', strtotime($date));
    $timeStr = $time ? date('H:i', strtotime($time)) : '';
    return $dateStr . ($timeStr ? ' ' . $timeStr : '');
}

function getTrangThaiText($status) {
    $statuses = [
        0 => ['text' => 'Đóng/Khóa', 'class' => 'danger', 'icon' => 'ban'],
        1 => ['text' => 'Đang mở', 'class' => 'success', 'icon' => 'check-circle'],
        2 => ['text' => 'Hết chỗ', 'class' => 'warning', 'icon' => 'exclamation-triangle'],
        3 => ['text' => 'Gần đầy', 'class' => 'info', 'icon' => 'info-circle']
    ];
    $statusInfo = $statuses[$status] ?? $statuses[1];
    return '<span class="status-badge ' . $statusInfo['class'] . '"><i class="fas fa-' . $statusInfo['icon'] . '"></i> ' . $statusInfo['text'] . '</span>';
}
?>

<style>
.detail-container {
  max-width: 1400px;
  margin: 0 auto;
  padding: 20px;
}

.detail-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
  flex-wrap: wrap;
  gap: 16px;
}

.detail-title {
  font-size: 28px;
  font-weight: 700;
  color: #1f2937;
  margin: 0;
}

.detail-actions {
  display: flex;
  gap: 12px;
  align-items: center;
}

.card {
  background: white;
  border-radius: 12px;
  box-shadow: 0 1px 3px rgba(0,0,0,0.1);
  margin-bottom: 24px;
  overflow: hidden;
}

.card-header {
  padding: 20px 24px;
  border-bottom: 1px solid #e5e7eb;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.card-title {
  font-size: 18px;
  font-weight: 700;
  color: #1f2937;
  display: flex;
  align-items: center;
  gap: 8px;
}

.card-body {
  padding: 24px;
}

.grid-2 {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 24px;
}

.info-section {
  border-right: 2px solid #3b82f6;
  padding-right: 24px;
}

.price-section {
  padding-left: 24px;
}

.info-group {
  margin-bottom: 20px;
}

.info-label {
  font-size: 12px;
  font-weight: 600;
  color: #6b7280;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  margin-bottom: 6px;
}

.info-value {
  font-size: 16px;
  color: #1f2937;
  font-weight: 500;
}

.price-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 12px 0;
  border-bottom: 1px solid #f3f4f6;
}

.price-row:last-child {
  border-bottom: none;
}

.price-label {
  font-size: 14px;
  color: #6b7280;
  display: flex;
  align-items: center;
  gap: 8px;
}

.price-value {
  font-size: 16px;
  color: #1f2937;
  font-weight: 600;
}

.status-badge {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 6px 12px;
  border-radius: 6px;
  font-size: 13px;
  font-weight: 600;
}

.status-badge.success {
  background: #d1fae5;
  color: #065f46;
}

.status-badge.danger {
  background: #fee2e2;
  color: #991b1b;
}

.status-badge.warning {
  background: #fef3c7;
  color: #92400e;
}

.status-badge.info {
  background: #dbeafe;
  color: #1e40af;
}

.btn {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 10px 20px;
  border-radius: 8px;
  font-weight: 600;
  text-decoration: none;
  transition: all 0.2s;
  border: none;
  cursor: pointer;
}

.btn-primary {
  background: #3b82f6;
  color: white;
}

.btn-primary:hover {
  background: #2563eb;
}

.btn-secondary {
  background: #6b7280;
  color: white;
}

.btn-secondary:hover {
  background: #4b5563;
}

.checklist-section {
  margin-top: 24px;
}

.progress-bar {
  width: 100%;
  height: 24px;
  background: #e5e7eb;
  border-radius: 12px;
  overflow: hidden;
  margin-bottom: 16px;
}

.progress-fill {
  height: 100%;
  background: linear-gradient(90deg, #3b82f6, #2563eb);
  transition: width 0.3s;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 12px;
  font-weight: 600;
}

.checklist-items {
  list-style: none;
  padding: 0;
  margin: 0;
}

.checklist-item {
  padding: 12px;
  border-bottom: 1px solid #f3f4f6;
  display: flex;
  align-items: center;
  gap: 12px;
}

.checklist-item:last-child {
  border-bottom: none;
}

.checklist-item i {
  color: #10b981;
  font-size: 18px;
}

.assignments-list {
  list-style: none;
  padding: 0;
  margin: 0;
}

.assignment-item {
  padding: 16px;
  background: #f9fafb;
  border-radius: 8px;
  margin-bottom: 12px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.assignment-info {
  flex: 1;
}

.assignment-name {
  font-weight: 600;
  color: #1f2937;
  margin-bottom: 4px;
}

.assignment-meta {
  font-size: 13px;
  color: #6b7280;
}
</style>

<div class="detail-container">
  <!-- Header -->
  <div class="detail-header">
    <h1 class="detail-title">
      <i class="fas fa-calendar-alt" style="color: #3b82f6;"></i>
      Chi tiết lịch khởi hành
    </h1>
    <div class="detail-actions">
      <a href="<?= BASE_URL ?>?act=admin-departure-plan-edit&id=<?= $departurePlan['id'] ?>" class="btn btn-primary">
        <i class="fas fa-edit"></i> Sửa
      </a>
      <a href="<?= BASE_URL ?>?act=admin-departure-plans<?= $departurePlan['id_tour'] ? '&tour_id=' . $departurePlan['id_tour'] : '' ?>" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Quay lại
      </a>
    </div>
  </div>

  <!-- Basic Info & Price -->
  <div class="card">
    <div class="card-header">
      <div class="card-title">
        <i class="fas fa-info-circle"></i> Thông tin & Giá
      </div>
    </div>
    
    <div class="card-body">
      <div class="grid-2">
        <div class="info-section">
          <h3 style="margin: 0 0 16px 0; font-size: 18px; font-weight: 700; color: #1f2937; padding-bottom: 12px; border-bottom: 2px solid #e5e7eb;">
            <i class="fas fa-info-circle" style="color: #3b82f6; margin-right: 8px;"></i>Thông tin
          </h3>
          
          <?php if ($tour): ?>
          <div class="info-group">
            <div class="info-label">Tour</div>
            <div class="info-value">
              <strong><?= htmlspecialchars($tour['tengoi'] ?? 'N/A') ?></strong>
              <?php if ($tour['id_goi']): ?>
                <br><small style="color: #6b7280;">ID: <?= $tour['id_goi'] ?></small>
              <?php endif; ?>
            </div>
          </div>
          <?php endif; ?>
          
          <div class="info-group">
            <div class="info-label">Ngày khởi hành</div>
            <div class="info-value">
              <i class="fas fa-calendar"></i> <?= formatDateTime($departurePlan['ngay_khoi_hanh'], $departurePlan['gio_khoi_hanh']) ?>
            </div>
          </div>
          
          <?php if ($departurePlan['ngay_ket_thuc']): ?>
          <div class="info-group">
            <div class="info-label">Ngày kết thúc</div>
            <div class="info-value">
              <i class="fas fa-calendar-check"></i> <?= formatDateTime($departurePlan['ngay_ket_thuc']) ?>
            </div>
          </div>
          <?php endif; ?>
          
          <?php if ($departurePlan['gio_tap_trung']): ?>
          <div class="info-group">
            <div class="info-label">Giờ tập trung</div>
            <div class="info-value">
              <i class="fas fa-clock"></i> <?= date('H:i', strtotime($departurePlan['gio_tap_trung'])) ?>
            </div>
          </div>
          <?php endif; ?>
          
          <div class="info-group">
            <div class="info-label">Điểm tập trung</div>
            <div class="info-value">
              <i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($departurePlan['diem_tap_trung'] ?? 'N/A') ?>
            </div>
          </div>
          
          <?php if ($departurePlan['phuong_tien']): ?>
          <div class="info-group">
            <div class="info-label">Phương tiện</div>
            <div class="info-value">
              <i class="fas fa-bus"></i> <?= htmlspecialchars($departurePlan['phuong_tien']) ?>
            </div>
          </div>
          <?php endif; ?>
          
          <div class="info-group">
            <div class="info-label">Số chỗ</div>
            <div class="info-value">
              Tối đa: <strong><?= $departurePlan['so_cho'] ?? 'N/A' ?></strong>
              <?php if (isset($departurePlan['so_cho_da_dat'])): ?>
                <br>Đã đặt: <strong><?= $departurePlan['so_cho_da_dat'] ?></strong>
              <?php endif; ?>
              <?php if (isset($departurePlan['so_cho_con_lai'])): ?>
                <br>Còn lại: <strong style="color: #10b981;"><?= $departurePlan['so_cho_con_lai'] ?></strong>
              <?php endif; ?>
            </div>
          </div>
          
          <div class="info-group">
            <div class="info-label">Trạng thái</div>
            <div class="info-value">
              <?= getTrangThaiText($departurePlan['trang_thai'] ?? 1) ?>
            </div>
          </div>
          
          <?php if ($departurePlan['ghi_chu']): ?>
          <div class="info-group">
            <div class="info-label">Ghi chú nội bộ</div>
            <div class="info-value" style="white-space: pre-wrap;"><?= htmlspecialchars($departurePlan['ghi_chu']) ?></div>
          </div>
          <?php endif; ?>
          
          <?php if ($departurePlan['ghi_chu_van_hanh']): ?>
          <div class="info-group">
            <div class="info-label">Ghi chú vận hành</div>
            <div class="info-value" style="white-space: pre-wrap;"><?= htmlspecialchars($departurePlan['ghi_chu_van_hanh']) ?></div>
          </div>
          <?php endif; ?>
        </div>
        
        <div class="price-section">
          <h3 style="margin: 0 0 16px 0; font-size: 18px; font-weight: 700; color: #1f2937; padding-bottom: 12px; border-bottom: 2px solid #e5e7eb;">
            <i class="fas fa-money-bill-wave" style="color: #3b82f6; margin-right: 8px;"></i>Giá
          </h3>
          
          <?php if ($departurePlan['gia_nguoi_lon']): ?>
          <div class="price-row">
            <span class="price-label"><i class="fas fa-user"></i> Người lớn</span>
            <span class="price-value"><?= number_format($departurePlan['gia_nguoi_lon'], 0, ',', '.') ?> đ</span>
          </div>
          <?php endif; ?>
          
          <?php if ($departurePlan['gia_tre_em']): ?>
          <div class="price-row">
            <span class="price-label"><i class="fas fa-child"></i> Trẻ em</span>
            <span class="price-value"><?= number_format($departurePlan['gia_tre_em'], 0, ',', '.') ?> đ</span>
          </div>
          <?php endif; ?>
          
          <?php if ($departurePlan['gia_tre_nho']): ?>
          <div class="price-row">
            <span class="price-label"><i class="fas fa-baby"></i> Trẻ nhỏ</span>
            <span class="price-value"><?= number_format($departurePlan['gia_tre_nho'], 0, ',', '.') ?> đ</span>
          </div>
          <?php endif; ?>
          
          <?php if ($departurePlan['uu_dai_giam_gia']): ?>
          <div class="price-row">
            <span class="price-label"><i class="fas fa-tag"></i> Ưu đãi giảm giá</span>
            <span class="price-value" style="color: #ef4444; font-weight: 700;">-<?= number_format($departurePlan['uu_dai_giam_gia'], 0) ?>%</span>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <!-- Checklist Section -->
  <?php if ($checklist): ?>
  <div class="card">
    <div class="card-header">
      <div class="card-title">
        <i class="fas fa-clipboard-check"></i> Pretrip Checklist
      </div>
      <a href="<?= BASE_URL ?>?act=admin-pretrip-checklist-items&checklist_id=<?= $checklist['id'] ?>" class="btn btn-primary" style="padding: 8px 16px; font-size: 14px;">
        <i class="fas fa-cog"></i> Quản lý
      </a>
    </div>
    <div class="card-body checklist-section">
      <div class="progress-bar">
        <div class="progress-fill" style="width: <?= $completionPercentage ?>%;">
          <?= $completionPercentage ?>%
        </div>
      </div>
      
      <?php if (!empty($checklistItems)): ?>
      <ul class="checklist-items">
        <?php foreach ($checklistItems as $item): ?>
        <li class="checklist-item">
          <i class="fas fa-<?= ($item['checked'] ?? false) ? 'check-circle' : 'circle' ?>"></i>
          <span style="flex: 1;">
            <strong><?= htmlspecialchars($item['ten_muc'] ?? '') ?></strong>
            <?php if (!empty($item['mo_ta'])): ?>
              <br><small style="color: #6b7280;"><?= htmlspecialchars($item['mo_ta']) ?></small>
            <?php endif; ?>
          </span>
          <?php if (!empty($item['nguoi_tick'])): ?>
            <small style="color: #6b7280;"><?= htmlspecialchars($item['nguoi_tick']) ?></small>
          <?php endif; ?>
        </li>
        <?php endforeach; ?>
      </ul>
      <?php else: ?>
      <p style="color: #6b7280; text-align: center; padding: 20px;">Chưa có mục checklist nào</p>
      <?php endif; ?>
    </div>
  </div>
  <?php else: ?>
  <div class="card">
    <div class="card-body" style="text-align: center; padding: 40px;">
      <i class="fas fa-clipboard" style="font-size: 48px; color: #9ca3af; margin-bottom: 16px;"></i>
      <p style="color: #6b7280; margin-bottom: 16px;">Chưa có checklist cho lịch khởi hành này</p>
      <a href="<?= BASE_URL ?>?act=admin-pretrip-checklist-create&departure_plan_id=<?= $departurePlan['id'] ?>" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tạo checklist
      </a>
    </div>
  </div>
  <?php endif; ?>

  <!-- Assignments Section -->
  <?php if (!empty($assignments)): ?>
  <div class="card">
    <div class="card-header">
      <div class="card-title">
        <i class="fas fa-users"></i> Phân công HDV
      </div>
      <a href="<?= BASE_URL ?>?act=admin-assignment-create&departure_plan_id=<?= $departurePlan['id'] ?>" class="btn btn-primary" style="padding: 8px 16px; font-size: 14px;">
        <i class="fas fa-plus"></i> Thêm phân công
      </a>
    </div>
    <div class="card-body">
      <ul class="assignments-list">
        <?php foreach ($assignments as $assignment): ?>
        <li class="assignment-item">
          <div class="assignment-info">
            <div class="assignment-name">
              <i class="fas fa-user"></i> <?= htmlspecialchars($assignment['ho_ten'] ?? 'N/A') ?>
            </div>
            <div class="assignment-meta">
              <?php if (!empty($assignment['so_dien_thoai'])): ?>
                <i class="fas fa-phone"></i> <?= htmlspecialchars($assignment['so_dien_thoai']) ?>
              <?php endif; ?>
              <?php if (!empty($assignment['email'])): ?>
                <span style="margin-left: 12px;"><i class="fas fa-envelope"></i> <?= htmlspecialchars($assignment['email']) ?></span>
              <?php endif; ?>
            </div>
          </div>
          <a href="<?= BASE_URL ?>?act=admin-assignment-edit&id=<?= $assignment['id'] ?>" class="btn btn-secondary" style="padding: 6px 12px; font-size: 13px;">
            <i class="fas fa-edit"></i> Sửa
          </a>
        </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
  <?php else: ?>
  <div class="card">
    <div class="card-body" style="text-align: center; padding: 40px;">
      <i class="fas fa-users" style="font-size: 48px; color: #9ca3af; margin-bottom: 16px;"></i>
      <p style="color: #6b7280; margin-bottom: 16px;">Chưa có phân công HDV cho lịch khởi hành này</p>
      <a href="<?= BASE_URL ?>?act=admin-assignment-create&departure_plan_id=<?= $departurePlan['id'] ?>" class="btn btn-primary">
        <i class="fas fa-plus"></i> Thêm phân công
      </a>
    </div>
  </div>
  <?php endif; ?>
</div>

