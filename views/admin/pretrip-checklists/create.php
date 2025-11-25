<?php
/**
 * Tạo/Sửa Checklist trước ngày đi - Modern Interface
 * UC-Pretrip-Checklist: Checklist trước ngày đi
 */

function safe_html($value) {
    return htmlentities($value ?? '', ENT_QUOTES, 'UTF-8');
}

// Parse checklist items từ JSON
$checklistItems = [];
if ($checklist && !empty($checklist['checklist_items'])) {
    $items = json_decode($checklist['checklist_items'], true);
    $checklistItems = $items ?: [];
} else {
    // Default items
    $checklistItems = [
        'tai_lieu' => ['label' => 'Tài liệu', 'checked' => false],
        'bang_ten' => ['label' => 'Bảng tên', 'checked' => false],
        'dung_cu_y_te' => ['label' => 'Dụng cụ y tế', 'checked' => false],
        've' => ['label' => 'Vé', 'checked' => false],
        'phong' => ['label' => 'Phòng', 'checked' => false],
        'xe' => ['label' => 'Xe/Phương tiện', 'checked' => false],
        'huong_dan_vien' => ['label' => 'Hướng dẫn viên', 'checked' => false],
        'thuc_an' => ['label' => 'Thức ăn/Đồ uống', 'checked' => false],
    ];
}
?>

<style>
.checklist-form-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
}

.checklist-form-title {
  font-size: 28px;
  font-weight: 700;
  color: var(--text-dark);
  margin: 0;
}

.form-card {
  background: white;
  border: 1px solid var(--border);
  border-radius: 12px;
  padding: 24px;
  margin-bottom: 20px;
}

.card-header {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 20px;
  padding-bottom: 16px;
  border-bottom: 2px solid var(--bg-light);
}

.card-header i {
  font-size: 20px;
  color: var(--primary);
}

.card-header h3 {
  font-size: 18px;
  font-weight: 700;
  color: var(--text-dark);
  margin: 0;
}

.checklist-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 16px;
  margin-bottom: 20px;
}

.checklist-item {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 16px;
  background: var(--bg-light);
  border-radius: 10px;
  border: 2px solid transparent;
  transition: all 0.2s;
  cursor: pointer;
}

.checklist-item:hover {
  border-color: var(--primary);
  background: white;
}

.checklist-item input[type="checkbox"] {
  width: 20px;
  height: 20px;
  cursor: pointer;
  accent-color: var(--primary);
}

.checklist-item label {
  font-weight: 600;
  color: var(--text-dark);
  cursor: pointer;
  flex: 1;
  margin: 0;
}

.checklist-item.checked {
  background: #d1fae5;
  border-color: #10b981;
}

.checklist-item.checked label {
  color: #065f46;
  text-decoration: line-through;
}

.form-group-modern {
  display: flex;
  flex-direction: column;
  gap: 8px;
  margin-bottom: 20px;
}

.form-group-modern label {
  font-weight: 600;
  font-size: 14px;
  color: var(--text-dark);
}

.form-group-modern textarea {
  padding: 12px 16px;
  border: 1px solid var(--border);
  border-radius: 8px;
  font-size: 14px;
  transition: all 0.2s;
  font-family: inherit;
  resize: vertical;
  min-height: 100px;
}

.form-group-modern textarea:focus {
  outline: none;
  border-color: var(--primary);
  box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.form-group-modern select {
  padding: 12px 16px;
  border: 1px solid var(--border);
  border-radius: 8px;
  font-size: 14px;
  transition: all 0.2s;
}

.form-group-modern select:focus {
  outline: none;
  border-color: var(--primary);
  box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.form-actions {
  display: flex;
  gap: 12px;
  justify-content: flex-end;
  padding-top: 20px;
}

.btn-submit {
  padding: 12px 32px;
  background: var(--primary);
  color: white;
  border: none;
  border-radius: 8px;
  font-weight: 600;
  font-size: 14px;
  cursor: pointer;
  transition: all 0.2s;
  display: inline-flex;
  align-items: center;
  gap: 8px;
}

.btn-submit:hover {
  background: #1e40af;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
}

.btn-cancel {
  padding: 12px 32px;
  background: white;
  color: var(--text-dark);
  border: 1px solid var(--border);
  border-radius: 8px;
  font-weight: 600;
  font-size: 14px;
  cursor: pointer;
  transition: all 0.2s;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: 8px;
}

.btn-cancel:hover {
  background: var(--bg-light);
  border-color: var(--text-light);
}

.ready-badge {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 12px 20px;
  background: linear-gradient(135deg, #10b981, #059669);
  color: white;
  border-radius: 10px;
  font-weight: 700;
  font-size: 16px;
  margin-top: 20px;
}

.departure-info {
  background: var(--bg-light);
  padding: 16px;
  border-radius: 10px;
  margin-bottom: 20px;
}

.departure-info h4 {
  margin: 0 0 8px 0;
  color: var(--text-dark);
  font-size: 16px;
}

.departure-info p {
  margin: 4px 0;
  color: var(--text-light);
  font-size: 14px;
}
</style>

<!-- Page Header -->
<div class="checklist-form-header">
  <h1 class="checklist-form-title">
    <i class="fas fa-clipboard-check" style="color: var(--primary);"></i>
    <?= $checklist ? 'Sửa' : 'Tạo' ?> Checklist Trước Ngày Đi
  </h1>
</div>

<!-- Form -->
<form method="POST" action="<?= BASE_URL ?>?act=admin-pretrip-checklist-create<?= $departurePlanId ? '&departure_plan_id=' . $departurePlanId : '' ?>">
  <?php if ($checklist): ?>
    <input type="hidden" name="id" value="<?= $checklist['id'] ?>">
  <?php endif; ?>
  
  <div class="form-card">
    <div class="card-header">
      <i class="fas fa-info-circle"></i>
      <h3>Thông tin lịch khởi hành</h3>
    </div>
    
    <?php if ($departurePlan): ?>
      <div class="departure-info">
        <h4><?= safe_html($departurePlan['tengoi'] ?? 'Tour') ?></h4>
        <p><strong>Ngày khởi hành:</strong> <?= $departurePlan['ngay_khoi_hanh'] ? date('d/m/Y', strtotime($departurePlan['ngay_khoi_hanh'])) : '-' ?></p>
        <p><strong>Giờ khởi hành:</strong> <?= $departurePlan['gio_khoi_hanh'] ? date('H:i', strtotime($departurePlan['gio_khoi_hanh'])) : '-' ?></p>
        <p><strong>Điểm tập trung:</strong> <?= safe_html($departurePlan['diem_tap_trung'] ?? '-') ?></p>
      </div>
      <input type="hidden" name="id_lich_khoi_hanh" value="<?= $departurePlan['id'] ?>">
    <?php else: ?>
      <div class="form-group-modern">
        <label>Lịch khởi hành <span style="color: #ef4444;">*</span></label>
        <select name="id_lich_khoi_hanh" required>
          <option value="">-- Chọn lịch khởi hành --</option>
          <?php
          // Sử dụng danh sách từ controller
          if (isset($allDeparturePlans) && !empty($allDeparturePlans)):
            foreach ($allDeparturePlans as $dp):
            $selected = ($checklist && $checklist['id_lich_khoi_hanh'] == $dp['id']) ? 'selected' : '';
            $ngay_gio = '';
            if ($dp['ngay_khoi_hanh']) {
              $ngay_gio = date('d/m/Y', strtotime($dp['ngay_khoi_hanh']));
              if ($dp['gio_khoi_hanh']) {
                $ngay_gio .= ' ' . date('H:i', strtotime($dp['gio_khoi_hanh']));
              }
            }
          ?>
            <option value="<?= $dp['id'] ?>" <?= $selected ?>>
              <?= safe_html($dp['tengoi'] ?? 'Tour') ?> - <?= $ngay_gio ?>
            </option>
          <?php endforeach; ?>
          <?php else: ?>
            <option value="">Không có lịch khởi hành nào</option>
          <?php endif; ?>
        </select>
      </div>
    <?php endif; ?>
  </div>

  <div class="form-card">
    <div class="card-header">
      <i class="fas fa-tasks"></i>
      <h3>Checklist Trước Ngày Đi</h3>
    </div>

    <div class="checklist-grid">
      <?php foreach ($checklistItems as $key => $item): 
        $checked = isset($item['checked']) && $item['checked'];
      ?>
        <div class="checklist-item <?= $checked ? 'checked' : '' ?>">
          <input type="checkbox" 
                 id="checklist_<?= $key ?>" 
                 name="checklist_<?= $key ?>" 
                 value="1"
                 <?= $checked ? 'checked' : '' ?>
                 onchange="this.closest('.checklist-item').classList.toggle('checked', this.checked)">
          <label for="checklist_<?= $key ?>"><?= safe_html($item['label']) ?></label>
        </div>
      <?php endforeach; ?>
    </div>

    <?php
    // Kiểm tra xem tất cả đã được tick chưa
    $allChecked = true;
    foreach ($checklistItems as $item) {
      if (!isset($item['checked']) || !$item['checked']) {
        $allChecked = false;
        break;
      }
    }
    ?>

    <?php if ($allChecked): ?>
      <div class="ready-badge">
        <i class="fas fa-check-circle"></i>
        READY - Tất cả đã hoàn thành!
      </div>
    <?php endif; ?>
  </div>

  <div class="form-card">
    <div class="card-header">
      <i class="fas fa-comment"></i>
      <h3>Ghi chú</h3>
    </div>

    <div class="form-group-modern">
      <textarea name="ghi_chu" placeholder="Nhập các ghi chú bổ sung..."><?= safe_html($checklist['ghi_chu'] ?? '') ?></textarea>
    </div>
  </div>

  <!-- Form Actions -->
  <div class="form-actions">
    <?php
    $cancelUrl = BASE_URL . '?act=admin-departure-plans';
    if ($departurePlan && $departurePlan['id_tour']) {
      $cancelUrl .= '&tour_id=' . $departurePlan['id_tour'];
    }
    ?>
    <a href="<?= $cancelUrl ?>" class="btn-cancel">
      <i class="fas fa-times"></i>
      Hủy
    </a>
    <button type="submit" class="btn-submit">
      <i class="fas fa-save"></i>
      <?= $checklist ? 'Cập nhật' : 'Tạo' ?> Checklist
    </button>
  </div>
</form>

<script>
// Auto update ready status khi check/uncheck
document.querySelectorAll('.checklist-item input[type="checkbox"]').forEach(function(checkbox) {
  checkbox.addEventListener('change', function() {
    updateReadyStatus();
  });
});

function updateReadyStatus() {
  const checkboxes = document.querySelectorAll('.checklist-item input[type="checkbox"]');
  const allChecked = Array.from(checkboxes).every(cb => cb.checked);
  
  let readyBadge = document.querySelector('.ready-badge');
  if (allChecked && !readyBadge) {
    const formCard = document.querySelector('.form-card:has(.checklist-grid)');
    readyBadge = document.createElement('div');
    readyBadge.className = 'ready-badge';
    readyBadge.innerHTML = '<i class="fas fa-check-circle"></i> READY - Tất cả đã hoàn thành!';
    formCard.appendChild(readyBadge);
  } else if (!allChecked && readyBadge) {
    readyBadge.remove();
  }
}
</script>

