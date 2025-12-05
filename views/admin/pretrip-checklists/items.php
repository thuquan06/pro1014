<?php
/**
 * Quản lý Checklist Items - Admin
 * UC-Pretrip-Checklist: Quản lý các mục checklist
 */

function safe_html($value) {
    return htmlentities($value ?? '', ENT_QUOTES, 'UTF-8');
}
?>

<style>
.checklist-items-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
  flex-wrap: wrap;
  gap: 16px;
}

.checklist-items-title {
  font-size: 28px;
  font-weight: 700;
  color: #1f2937;
  margin: 0;
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

.items-section {
  background: white;
  border: 1px solid #e5e7eb;
  border-radius: 12px;
  padding: 24px;
  margin-bottom: 24px;
}

.section-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
  padding-bottom: 12px;
  border-bottom: 2px solid #f3f4f6;
}

.section-title {
  font-size: 20px;
  font-weight: 700;
  color: #1f2937;
  margin: 0;
}

.checklist-item-card {
  background: #f9fafb;
  border: 1px solid #e5e7eb;
  border-radius: 8px;
  padding: 16px;
  margin-bottom: 12px;
  transition: all 0.2s;
}

.checklist-item-card:hover {
  border-color: #d1d5db;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.checklist-item-card.completed {
  background: #d1fae5;
  border-color: #10b981;
}

.item-header {
  display: flex;
  justify-content: space-between;
  align-items: start;
  margin-bottom: 8px;
}

.item-name {
  font-weight: 600;
  color: #1f2937;
  font-size: 15px;
  flex: 1;
}

.item-status {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 13px;
}

.item-status.completed {
  color: #10b981;
}

.item-status.pending {
  color: #9ca3af;
}

.item-description {
  font-size: 13px;
  color: #6b7280;
  margin-top: 8px;
}

.item-meta {
  font-size: 11px;
  color: #9ca3af;
  margin-top: 8px;
  display: flex;
  gap: 16px;
}

.modal {
  display: none;
  position: fixed;
  z-index: 1000;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  background: rgba(0,0,0,0.5);
  align-items: center;
  justify-content: center;
}

.modal-content {
  background: white;
  border-radius: 12px;
  padding: 24px;
  max-width: 500px;
  width: 90%;
  max-height: 90vh;
  overflow-y: auto;
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
  padding-bottom: 12px;
  border-bottom: 2px solid #f3f4f6;
}

.modal-title {
  font-size: 20px;
  font-weight: 700;
  color: #1f2937;
  margin: 0;
}

.close {
  font-size: 28px;
  font-weight: 300;
  color: #9ca3af;
  cursor: pointer;
  line-height: 1;
}

.close:hover {
  color: #1f2937;
}

.form-group {
  margin-bottom: 16px;
}

.form-label {
  display: block;
  font-size: 14px;
  font-weight: 600;
  color: #374151;
  margin-bottom: 6px;
}

.form-input, .form-textarea {
  width: 100%;
  padding: 10px 12px;
  border: 1px solid #d1d5db;
  border-radius: 6px;
  font-size: 14px;
  transition: border-color 0.2s;
}

.form-input:focus, .form-textarea:focus {
  outline: none;
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-textarea {
  min-height: 80px;
  resize: vertical;
}

.btn-group {
  display: flex;
  gap: 8px;
  justify-content: flex-end;
  margin-top: 20px;
}

.btn {
  padding: 10px 20px;
  border-radius: 6px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  border: none;
  transition: all 0.2s;
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

.btn-danger {
  background: #ef4444;
  color: white;
}

.btn-danger:hover {
  background: #dc2626;
}

.btn-sm {
  padding: 6px 12px;
  font-size: 13px;
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

.history-icon.create {
  background: #dbeafe;
  color: #3b82f6;
}

.history-icon.approve {
  background: #dbeafe;
  color: #3b82f6;
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
</style>

<div style="max-width: 1200px; margin: 0 auto; padding: 20px;">
  <div class="checklist-items-header">
    <h1 class="checklist-items-title">
      <i class="fas fa-clipboard-check"></i> Quản lý Checklist Items
    </h1>
    <a href="<?= BASE_URL ?>?act=admin-departure-plans<?= $departurePlan && $departurePlan['id_tour'] ? '&tour_id=' . $departurePlan['id_tour'] : '' ?>" class="btn btn-secondary">
      <i class="fas fa-arrow-left"></i> Quay lại
    </a>
  </div>

  <?php if ($departurePlan): ?>
    <div style="background: #f9fafb; padding: 16px; border-radius: 8px; margin-bottom: 24px;">
      <h3 style="margin: 0 0 8px 0; color: #1f2937;"><?= safe_html($departurePlan['tengoi'] ?? 'Tour') ?></h3>
      <p style="margin: 0; color: #6b7280; font-size: 14px;">
        <strong>Ngày khởi hành:</strong> <?= $departurePlan['ngay_khoi_hanh'] ? date('d/m/Y', strtotime($departurePlan['ngay_khoi_hanh'])) : '-' ?>
        <?php if ($departurePlan['gio_khoi_hanh']): ?>
          | <strong>Giờ:</strong> <?= date('H:i', strtotime($departurePlan['gio_khoi_hanh'])) ?>
        <?php endif; ?>
      </p>
    </div>
  <?php endif; ?>

  <!-- Progress Section -->
  <div class="progress-section">
    <div class="progress-header">
      <span class="progress-label">Tiến độ hoàn thành</span>
      <span class="progress-value" style="color: <?= $completionPercentage == 100 ? '#10b981' : '#3b82f6' ?>;">
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
      <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
          <strong style="color: #065f46;"><i class="fas fa-check-circle"></i> Checklist đã hoàn thành!</strong>
          <p style="margin: 4px 0 0 0; color: #047857; font-size: 13px;">Tất cả các mục đã được tick. Bạn có thể duyệt trạng thái Ready.</p>
        </div>
        <a href="<?= BASE_URL ?>?act=admin-pretrip-checklist-approve-ready&checklist_id=<?= $checklist['id'] ?>" 
           class="btn btn-primary"
           onclick="return confirm('Xác nhận duyệt trạng thái Ready cho tour này?')">
          <i class="fas fa-check-double"></i> Duyệt Ready
        </a>
      </div>
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

  <!-- Checklist Items Section -->
  <div class="items-section">
    <div class="section-header">
      <h2 class="section-title">
        <i class="fas fa-list-check"></i> Danh sách mục checklist
      </h2>
      <button onclick="openAddModal()" class="btn btn-primary btn-sm">
        <i class="fas fa-plus"></i> Thêm mục
      </button>
    </div>

    <?php if (!empty($items)): ?>
      <?php foreach ($items as $item): ?>
        <div class="checklist-item-card <?= $item['da_hoan_thanh'] ? 'completed' : '' ?>">
          <div class="item-header">
            <div class="item-name">
              <?php if ($item['da_hoan_thanh']): ?>
                <i class="fas fa-check-circle" style="color: #10b981;"></i>
              <?php else: ?>
                <i class="far fa-circle" style="color: #9ca3af;"></i>
              <?php endif; ?>
              <?= safe_html($item['ten_muc']) ?>
            </div>
            <div class="item-status <?= $item['da_hoan_thanh'] ? 'completed' : 'pending' ?>">
              <?= $item['da_hoan_thanh'] ? 'Đã hoàn thành' : 'Chưa hoàn thành' ?>
            </div>
          </div>
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
          <div style="margin-top: 12px; display: flex; gap: 8px;">
            <button onclick="openEditModal(<?= $item['id'] ?>, '<?= htmlspecialchars($item['ten_muc'], ENT_QUOTES) ?>', '<?= htmlspecialchars($item['mo_ta'] ?? '', ENT_QUOTES) ?>')" class="btn btn-secondary btn-sm">
              <i class="fas fa-edit"></i> Sửa
            </button>
            <button onclick="deleteItem(<?= $item['id'] ?>)" class="btn btn-danger btn-sm">
              <i class="fas fa-trash"></i> Xóa
            </button>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div style="text-align: center; padding: 40px; color: #9ca3af;">
        <i class="fas fa-clipboard-list" style="font-size: 48px; margin-bottom: 16px;"></i>
        <p>Chưa có mục checklist nào. Vui lòng thêm mục mới.</p>
      </div>
    <?php endif; ?>
  </div>

  <!-- History Section -->
  <?php if (!empty($history)): ?>
    <div class="history-section">
      <div class="section-header">
        <h2 class="section-title">
          <i class="fas fa-history"></i> Lịch sử thay đổi
        </h2>
      </div>
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

<!-- Add/Edit Modal -->
<div id="itemModal" class="modal">
  <div class="modal-content">
    <div class="modal-header">
      <h3 class="modal-title" id="modalTitle">Thêm mục checklist</h3>
      <span class="close" onclick="closeModal()">&times;</span>
    </div>
    <form id="itemForm" method="POST">
      <input type="hidden" name="action" id="formAction" value="add_item">
      <input type="hidden" name="item_id" id="formItemId">
      
      <div class="form-group">
        <label class="form-label">Tên mục *</label>
        <input type="text" name="ten_muc" id="formTenMuc" class="form-input" required>
      </div>
      
      <div class="form-group">
        <label class="form-label">Mô tả</label>
        <textarea name="mo_ta" id="formMoTa" class="form-textarea"></textarea>
      </div>
      
      <div class="btn-group">
        <button type="button" onclick="closeModal()" class="btn btn-secondary">Hủy</button>
        <button type="submit" class="btn btn-primary">Lưu</button>
      </div>
    </form>
  </div>
</div>

<script>
function openAddModal() {
  document.getElementById('modalTitle').textContent = 'Thêm mục checklist';
  document.getElementById('formAction').value = 'add_item';
  document.getElementById('formItemId').value = '';
  document.getElementById('formTenMuc').value = '';
  document.getElementById('formMoTa').value = '';
  document.getElementById('itemModal').style.display = 'flex';
}

function openEditModal(id, tenMuc, moTa) {
  document.getElementById('modalTitle').textContent = 'Sửa mục checklist';
  document.getElementById('formAction').value = 'update_item';
  document.getElementById('formItemId').value = id;
  document.getElementById('formTenMuc').value = tenMuc;
  document.getElementById('formMoTa').value = moTa;
  document.getElementById('itemModal').style.display = 'flex';
}

function closeModal() {
  document.getElementById('itemModal').style.display = 'none';
}

function deleteItem(id) {
  if (confirm('Bạn có chắc chắn muốn xóa mục này?')) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.innerHTML = `
      <input type="hidden" name="action" value="delete_item">
      <input type="hidden" name="item_id" value="${id}">
    `;
    document.body.appendChild(form);
    form.submit();
  }
}

// Close modal when clicking outside
window.onclick = function(event) {
  const modal = document.getElementById('itemModal');
  if (event.target == modal) {
    closeModal();
  }
}
</script>

