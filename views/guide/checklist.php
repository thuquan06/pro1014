<?php
/**
 * Checklist cho HDV - Xem và tick các mục
 * UC-Pretrip-Checklist: HDV thực hiện checklist
 */

function safe_html($value) {
    return htmlentities($value ?? '', ENT_QUOTES, 'UTF-8');
}
?>

<style>
.checklist-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
  flex-wrap: wrap;
  gap: 16px;
}

.checklist-title {
  font-size: 28px;
  font-weight: 700;
  color: #1f2937;
  margin: 0;
}

.tour-info {
  background: #f9fafb;
  padding: 16px;
  border-radius: 8px;
  margin-bottom: 24px;
}

.tour-info h3 {
  margin: 0 0 8px 0;
  color: #1f2937;
}

.tour-info p {
  margin: 4px 0;
  color: #6b7280;
  font-size: 14px;
}

.progress-section {
  background: white;
  border: 1px solid #e5e7eb;
  border-radius: 12px;
  padding: 20px;
  margin-bottom: 24px;
}

.progress-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 12px;
}

.progress-label {
  font-size: 14px;
  font-weight: 600;
  color: #6b7280;
}

.progress-value {
  font-size: 18px;
  font-weight: 700;
  color: #3b82f6;
}

.progress-value.completed {
  color: #10b981;
}

.progress-bar {
  background: #e5e7eb;
  height: 12px;
  border-radius: 6px;
  overflow: hidden;
}

.progress-fill {
  background: #3b82f6;
  height: 100%;
  transition: width 0.3s;
}

.progress-fill.completed {
  background: #10b981;
}

.ready-banner {
  background: #d1fae5;
  border-left: 4px solid #10b981;
  padding: 16px;
  border-radius: 8px;
  margin-bottom: 24px;
}

.ready-banner.approved {
  background: #dbeafe;
  border-left-color: #3b82f6;
}

.checklist-items {
  background: white;
  border: 1px solid #e5e7eb;
  border-radius: 12px;
  padding: 24px;
  margin-bottom: 24px;
}

.checklist-item {
  background: #f9fafb;
  border: 2px solid #e5e7eb;
  border-radius: 8px;
  padding: 16px;
  margin-bottom: 12px;
  transition: all 0.2s;
  cursor: pointer;
}

.checklist-item:hover {
  border-color: #d1d5db;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.checklist-item.completed {
  background: #d1fae5;
  border-color: #10b981;
}

.item-content {
  display: flex;
  align-items: start;
  gap: 12px;
}

.item-checkbox {
  width: 24px;
  height: 24px;
  border: 2px solid #9ca3af;
  border-radius: 4px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  margin-top: 2px;
  transition: all 0.2s;
}

.checklist-item.completed .item-checkbox {
  background: #10b981;
  border-color: #10b981;
  color: white;
}

.item-checkbox i {
  font-size: 14px;
  display: none;
}

.checklist-item.completed .item-checkbox i {
  display: block;
}

.item-details {
  flex: 1;
}

.item-name {
  font-weight: 600;
  color: #1f2937;
  font-size: 15px;
  margin-bottom: 4px;
}

.item-description {
  font-size: 13px;
  color: #6b7280;
  margin-top: 4px;
}

.item-meta {
  font-size: 11px;
  color: #9ca3af;
  margin-top: 8px;
  display: flex;
  gap: 16px;
}

.history-section {
  background: white;
  border: 1px solid #e5e7eb;
  border-radius: 12px;
  padding: 24px;
}

.history-item {
  padding: 12px;
  border-bottom: 1px solid #f3f4f6;
  display: flex;
  gap: 12px;
}

.history-item:last-child {
  border-bottom: none;
}

.history-icon {
  width: 32px;
  height: 32px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 14px;
  flex-shrink: 0;
}

.history-icon.tick {
  background: #d1fae5;
  color: #10b981;
}

.history-icon.untick {
  background: #fee2e2;
  color: #ef4444;
}

.history-content {
  flex: 1;
}

.history-action {
  font-weight: 600;
  color: #1f2937;
  font-size: 14px;
}

.history-details {
  font-size: 12px;
  color: #6b7280;
  margin-top: 4px;
}

.history-time {
  font-size: 11px;
  color: #9ca3af;
  margin-top: 4px;
}

.btn {
  padding: 10px 20px;
  border-radius: 6px;
  font-size: 14px;
  font-weight: 600;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: 6px;
  transition: all 0.2s;
  border: none;
  cursor: pointer;
}

.btn-secondary {
  background: #6b7280;
  color: white;
}

.btn-secondary:hover {
  background: #4b5563;
}
</style>

<div style="max-width: 1000px; margin: 0 auto; padding: 20px;">
  <div class="checklist-header">
    <h1 class="checklist-title">
      <i class="fas fa-clipboard-check"></i> Checklist Trước Ngày Khởi Hành
    </h1>
    <a href="<?= BASE_URL ?>?act=guide-assignments" class="btn btn-secondary">
      <i class="fas fa-arrow-left"></i> Quay lại
    </a>
  </div>

  <?php if ($tour): ?>
    <div class="tour-info">
      <h3><?= safe_html($tour['tengoi'] ?? 'Tour') ?></h3>
      <?php if ($departurePlan): ?>
        <p><strong>Ngày khởi hành:</strong> <?= $departurePlan['ngay_khoi_hanh'] ? date('d/m/Y', strtotime($departurePlan['ngay_khoi_hanh'])) : '-' ?></p>
        <?php if ($departurePlan['gio_khoi_hanh']): ?>
          <p><strong>Giờ khởi hành:</strong> <?= date('H:i', strtotime($departurePlan['gio_khoi_hanh'])) ?></p>
        <?php endif; ?>
        <?php if ($departurePlan['diem_tap_trung']): ?>
          <p><strong>Điểm tập trung:</strong> <?= safe_html($departurePlan['diem_tap_trung']) ?></p>
        <?php endif; ?>
      <?php endif; ?>
    </div>
  <?php endif; ?>

  <!-- Progress Section -->
  <div class="progress-section">
    <div class="progress-header">
      <span class="progress-label">Tiến độ hoàn thành</span>
      <span class="progress-value <?= $completionPercentage == 100 ? 'completed' : '' ?>">
        <?= $completionPercentage ?>%
      </span>
    </div>
    <div class="progress-bar">
      <div class="progress-fill <?= $completionPercentage == 100 ? 'completed' : '' ?>" style="width: <?= $completionPercentage ?>%;"></div>
    </div>
  </div>

  <!-- Ready Status Banner -->
  <?php if ($completionPercentage == 100 && $checklist['trang_thai_ready'] == 0): ?>
    <div class="ready-banner">
      <strong style="color: #065f46;"><i class="fas fa-check-circle"></i> Checklist đã hoàn thành!</strong>
      <p style="margin: 4px 0 0 0; color: #047857; font-size: 13px;">Tất cả các mục đã được tick. Vui lòng chờ admin duyệt trạng thái Ready.</p>
    </div>
  <?php elseif ($checklist['trang_thai_ready'] == 1): ?>
    <div class="ready-banner approved">
      <strong style="color: #1e40af;"><i class="fas fa-check-double"></i> Tour đã được duyệt Ready</strong>
      <?php if ($checklist['ngay_duyet_ready']): ?>
        <p style="margin: 4px 0 0 0; color: #1e3a8a; font-size: 13px;">
          Ngày duyệt: <?= date('d/m/Y H:i', strtotime($checklist['ngay_duyet_ready'])) ?>
        </p>
      <?php endif; ?>
    </div>
  <?php endif; ?>

  <!-- Checklist Items -->
  <div class="checklist-items">
    <h2 style="margin: 0 0 20px 0; font-size: 20px; font-weight: 700; color: #1f2937;">
      <i class="fas fa-list-check"></i> Danh sách mục checklist
    </h2>

    <?php if (!empty($items)): ?>
      <?php foreach ($items as $item): ?>
        <div class="checklist-item <?= $item['da_hoan_thanh'] ? 'completed' : '' ?>" 
             onclick="toggleItem(<?= $item['id'] ?>, <?= $item['da_hoan_thanh'] ? 'false' : 'true' ?>)">
          <div class="item-content">
            <div class="item-checkbox">
              <i class="fas fa-check"></i>
            </div>
            <div class="item-details">
              <div class="item-name"><?= safe_html($item['ten_muc']) ?></div>
              <?php if (!empty($item['mo_ta'])): ?>
                <div class="item-description"><?= safe_html($item['mo_ta']) ?></div>
              <?php endif; ?>
              <?php if ($item['da_hoan_thanh'] && $item['nguoi_tick']): ?>
                <div class="item-meta">
                  <span><i class="fas fa-user"></i> <?= safe_html($item['ten_hdv'] ?? $item['ten_admin'] ?? 'N/A') ?></span>
                  <?php if ($item['ngay_tick']): ?>
                    <span><i class="fas fa-clock"></i> <?= date('d/m/Y H:i', strtotime($item['ngay_tick'])) ?></span>
                  <?php endif; ?>
                </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div style="text-align: center; padding: 40px; color: #9ca3af;">
        <i class="fas fa-clipboard-list" style="font-size: 48px; margin-bottom: 16px;"></i>
        <p>Chưa có mục checklist nào.</p>
      </div>
    <?php endif; ?>
  </div>

  <!-- History Section -->
  <?php if (!empty($history)): ?>
    <div class="history-section">
      <h2 style="margin: 0 0 20px 0; font-size: 20px; font-weight: 700; color: #1f2937;">
        <i class="fas fa-history"></i> Lịch sử thay đổi
      </h2>
      <?php foreach ($history as $h): ?>
        <div class="history-item">
          <div class="history-icon <?= $h['hanh_dong'] ?>">
            <?php
            $icons = [
              'tick' => 'fa-check',
              'untick' => 'fa-times',
              'create_item' => 'fa-plus',
              'delete_item' => 'fa-trash',
              'update_item' => 'fa-edit',
              'approve_ready' => 'fa-check-double'
            ];
            $icon = $icons[$h['hanh_dong']] ?? 'fa-circle';
            ?>
            <i class="fas <?= $icon ?>"></i>
          </div>
          <div class="history-content">
            <div class="history-action">
              <?php
              $actions = [
                'tick' => 'Đã tick',
                'untick' => 'Đã bỏ tick',
                'create_item' => 'Đã tạo mục',
                'delete_item' => 'Đã xóa mục',
                'update_item' => 'Đã cập nhật mục',
                'approve_ready' => 'Đã duyệt Ready'
              ];
              echo $actions[$h['hanh_dong']] ?? $h['hanh_dong'];
              ?>
              <?php if ($h['chi_tiet'] && isset($h['chi_tiet']['ten_muc'])): ?>
                : <?= safe_html($h['chi_tiet']['ten_muc']) ?>
              <?php endif; ?>
            </div>
            <div class="history-details">
              <?= safe_html($h['ten_hdv'] ?? $h['ten_admin'] ?? 'N/A') ?>
            </div>
            <div class="history-time">
              <?= date('d/m/Y H:i:s', strtotime($h['ngay_tao'])) ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

<script>
function toggleItem(itemId, checked) {
  const form = document.createElement('form');
  form.method = 'POST';
  form.innerHTML = `
    <input type="hidden" name="action" value="toggle_item">
    <input type="hidden" name="item_id" value="${itemId}">
  `;
  document.body.appendChild(form);
  form.submit();
}
</script>


