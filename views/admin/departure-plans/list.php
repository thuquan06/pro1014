<?php
/**
 * Departure Plans List - Modern Interface
 * UC-Departure-Plan: Quản lý lịch khởi hành
 */

function safe_html($value) {
    return htmlentities($value ?? '', ENT_QUOTES, 'UTF-8');
}

function getTrangThaiText($status) {
    return $status == 1 
        ? '<span class="status-badge success"><i class="fas fa-check-circle"></i> Hoạt động</span>'
        : '<span class="status-badge danger"><i class="fas fa-ban"></i> Tạm dừng</span>';
}

function formatDateTime($date, $time) {
    if (!$date) return '-';
    $dateStr = date('d/m/Y', strtotime($date));
    $timeStr = $time ? date('H:i', strtotime($time)) : '';
    return $dateStr . ($timeStr ? ' ' . $timeStr : '');
}

// Đảm bảo biến tourId được định nghĩa và kiểm tra
if (!isset($tourId)) {
    $tourId = null;
}
// Nếu tourId là 0 hoặc rỗng, set về null
if ($tourId === 0 || $tourId === '0' || $tourId === '') {
    $tourId = null;
}

// Đảm bảo biến checklists được định nghĩa
if (!isset($checklists)) {
    $checklists = [];
}
?>

<style>
.departure-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
  flex-wrap: wrap;
  gap: 16px;
}

.departure-title {
  font-size: 28px;
  font-weight: 700;
  color: var(--text-dark);
  margin: 0;
}

.departure-actions {
  display: flex;
  gap: 12px;
  align-items: center;
}

.departure-card {
  background: white;
  border: 1px solid var(--border);
  border-radius: 12px;
  overflow: hidden;
}

.departure-table {
  width: 100%;
  border-collapse: collapse;
}

.departure-table thead {
  background: var(--bg-light);
}

.departure-table th {
  padding: 14px 16px;
  text-align: left;
  font-weight: 600;
  font-size: 13px;
  color: var(--text-dark);
  text-transform: uppercase;
  letter-spacing: 0.5px;
  border-bottom: 2px solid var(--border);
}

.departure-table td {
  padding: 16px;
  border-bottom: 1px solid var(--border);
  font-size: 14px;
  color: var(--text-dark);
}

.departure-table tbody tr:hover {
  background: var(--bg-light);
}

.status-badge {
  display: inline-flex;
  align-items: center;
  gap: 4px;
  padding: 6px 12px;
  border-radius: 20px;
  font-size: 12px;
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

.btn-action {
  padding: 6px 10px;
  border: none;
  border-radius: 6px;
  font-size: 12px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: 4px;
  margin: 2px;
}

.btn-action.edit {
  background: #fef3c7;
  color: #78350f;
}

.btn-action.edit:hover {
  background: #f59e0b;
  color: white;
}

.btn-action.delete {
  background: #fee2e2;
  color: #991b1b;
}

.btn-action.delete:hover {
  background: #ef4444;
  color: white;
}

.btn-action.success {
  background: #d1fae5;
  color: #065f46;
}

.btn-action.success:hover {
  background: #10b981;
  color: white;
}

.btn-action.warning {
  background: #fef3c7;
  color: #78350f;
}

.btn-action.warning:hover {
  background: #f59e0b;
  color: white;
}

.btn-action.info {
  background: #dbeafe;
  color: #1e40af;
}

.btn-action.info:hover {
  background: #3b82f6;
  color: white;
}

.btn-action.toggle {
  background: #dbeafe;
  color: #1e40af;
}

.btn-action.toggle:hover {
  background: #2563eb;
  color: white;
}

.btn-primary {
  background: var(--primary);
  color: white;
  padding: 10px 20px;
  border-radius: 8px;
  text-decoration: none;
  font-weight: 600;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  transition: all 0.2s;
}

.btn-primary:hover {
  background: var(--primary-dark);
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
}

.empty-state {
  text-align: center;
  padding: 60px 20px;
  color: var(--text-light);
}

.empty-state i {
  font-size: 64px;
  margin-bottom: 16px;
  opacity: 0.3;
}

.empty-state h3 {
  font-size: 20px;
  margin-bottom: 8px;
  color: var(--text-dark);
}

.empty-state p {
  font-size: 14px;
  margin-bottom: 24px;
}
</style>

<!-- Page Header -->
<div class="departure-header">
  <div>
    <?php if (!empty($tour) && isset($tour['id_goi'])): ?>
      <div style="margin-bottom: 8px;">
        <a href="<?= BASE_URL ?>?act=admin-tour-detail&id=<?= $tour['id_goi'] ?>" 
           style="color: var(--text-light); text-decoration: none; font-size: 14px;">
          <i class="fas fa-arrow-left"></i> Quay lại chi tiết tour
        </a>
      </div>
      <h1 class="departure-title">
        <i class="fas fa-calendar-alt" style="color: var(--primary);"></i>
        Lịch khởi hành: <?= safe_html($tour['tengoi'] ?? 'Tour') ?>
      </h1>
    <?php else: ?>
      <h1 class="departure-title">
        <i class="fas fa-calendar-alt" style="color: var(--primary);"></i>
        Quản lý Lịch khởi hành
      </h1>
    <?php endif; ?>
  </div>
  
  <div class="departure-actions">
    <a href="<?= BASE_URL ?>?act=admin-departure-plan-create<?= $tourId ? '&id_tour=' . $tourId : '' ?>" class="btn-primary">
      <i class="fas fa-plus"></i>
      Tạo lịch khởi hành
    </a>
  </div>
</div>

<!-- Departure Plans Table -->
<div class="departure-card">
  <?php if (!empty($departurePlans)): ?>
    <table class="departure-table">
      <thead>
        <tr>
          <th>STT</th>
          <?php if (!$tourId || $tourId === null): ?>
            <th>Tour</th>
          <?php endif; ?>
          <th>Ngày/Giờ khởi hành</th>
          <th>Điểm tập trung</th>
          <th>Ghi chú</th>
          <th>Checklist</th>
          <th>Trạng thái</th>
          <th>Thao tác</th>
        </tr>
      </thead>
      <tbody>
        <?php 
        $cnt = 1;
        foreach ($departurePlans as $plan): 
          $id = $plan['id'] ?? '';
          $tengoi = $plan['tengoi'] ?? 'Chưa chọn tour';
          $ngay_khoi_hanh = $plan['ngay_khoi_hanh'] ?? '';
          $gio_khoi_hanh = $plan['gio_khoi_hanh'] ?? '';
          $diem_tap_trung = $plan['diem_tap_trung'] ?? '-';
          $ghi_chu_van_hanh = $plan['ghi_chu_van_hanh'] ?? '';
          $trang_thai = $plan['trang_thai'] ?? 0;
        ?>
          <tr>
            <td><?= $cnt ?></td>
            <?php if (!$tourId || $tourId === null): ?>
              <td>
                <strong><?= safe_html($tengoi) ?></strong>
                <?php if (!empty($plan['id_goi'])): ?>
                  <br><small style="color: var(--text-light);">ID: <?= $plan['id_goi'] ?></small>
                <?php endif; ?>
              </td>
            <?php endif; ?>
            <td>
              <strong><?= formatDateTime($ngay_khoi_hanh, $gio_khoi_hanh) ?></strong>
            </td>
            <td><?= safe_html($diem_tap_trung) ?></td>
            <td>
              <?php if ($ghi_chu_van_hanh): ?>
                <span title="<?= safe_html($ghi_chu_van_hanh) ?>">
                  <?= mb_substr($ghi_chu_van_hanh, 0, 50) . (mb_strlen($ghi_chu_van_hanh) > 50 ? '...' : '') ?>
                </span>
              <?php else: ?>
                <span style="color: var(--text-light);">-</span>
              <?php endif; ?>
            </td>
            <td>
              <?php
              $checklist = $checklists[$id] ?? null;
              if ($checklist):
                $items = json_decode($checklist['checklist_items'], true);
                $allChecked = true;
                if ($items) {
                  foreach ($items as $item) {
                    if (!isset($item['checked']) || !$item['checked']) {
                      $allChecked = false;
                      break;
                    }
                  }
                }
              ?>
                <a href="<?= BASE_URL ?>?act=admin-pretrip-checklist-create&departure_plan_id=<?= $id ?><?= $tourId ? '&tour_id=' . $tourId : '' ?>" 
                   class="btn-action <?= $allChecked ? 'success' : 'warning' ?>" 
                   title="<?= $allChecked ? 'Ready - Xem/Sửa checklist' : 'Chưa ready - Xem/Sửa checklist' ?>">
                  <i class="fas fa-<?= $allChecked ? 'check-circle' : 'clipboard-check' ?>"></i>
                  <?= $allChecked ? 'Ready' : 'Chưa Ready' ?>
                </a>
              <?php else: ?>
                <a href="<?= BASE_URL ?>?act=admin-pretrip-checklist-create&departure_plan_id=<?= $id ?><?= $tourId ? '&tour_id=' . $tourId : '' ?>" 
                   class="btn-action info" 
                   title="Tạo checklist">
                  <i class="fas fa-plus-circle"></i>
                  Tạo checklist
                </a>
              <?php endif; ?>
            </td>
            <td><?= getTrangThaiText($trang_thai) ?></td>
            <td>
              <a href="<?= BASE_URL ?>?act=admin-departure-plan-edit&id=<?= $id ?><?= $tourId ? '&tour_id=' . $tourId : '' ?>" 
                 class="btn-action edit" 
                 title="Sửa">
                <i class="fas fa-edit"></i>
              </a>
              <a href="<?= BASE_URL ?>?act=admin-departure-plan-toggle&id=<?= $id ?><?= $tourId ? '&tour_id=' . $tourId : '' ?>" 
                 class="btn-action toggle" 
                 title="Đổi trạng thái"
                 onclick="return confirm('Bạn có chắc muốn đổi trạng thái?')">
                <i class="fas fa-toggle-<?= $trang_thai ? 'on' : 'off' ?>"></i>
              </a>
              <a href="<?= BASE_URL ?>?act=admin-departure-plan-delete&id=<?= $id ?><?= $tourId ? '&tour_id=' . $tourId : '' ?>" 
                 class="btn-action delete" 
                 title="Xóa"
                 onclick="return confirm('Bạn có chắc muốn xóa lịch khởi hành này?')">
                <i class="fas fa-trash"></i>
              </a>
            </td>
          </tr>
        <?php 
        $cnt++;
        endforeach; 
        ?>
      </tbody>
    </table>
  <?php else: ?>
    <div class="empty-state">
      <i class="fas fa-calendar-times"></i>
      <h3>Chưa có lịch khởi hành</h3>
      <p>
        <?php if ($tour): ?>
          Tour này chưa có lịch khởi hành. Hãy tạo lịch khởi hành đầu tiên.
        <?php else: ?>
          Bắt đầu tạo lịch khởi hành đầu tiên cho tour của bạn
        <?php endif; ?>
      </p>
      <a href="<?= BASE_URL ?>?act=admin-departure-plan-create<?= $tourId ? '&id_tour=' . $tourId : '' ?>" class="btn-primary">
        <i class="fas fa-plus"></i>
        Tạo lịch khởi hành
      </a>
    </div>
  <?php endif; ?>
</div>

