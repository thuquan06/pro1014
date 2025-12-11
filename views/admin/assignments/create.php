<?php
/**
 * Tạo Phân công HDV - Modern Interface
 * UC-Assign-Guide: Phân công HDV cho lịch khởi hành
 */

function safe_html($value) {
    return htmlentities($value ?? '', ENT_QUOTES, 'UTF-8');
}

$guides = $guides ?? [];
$departurePlans = $departurePlans ?? [];
$departurePlan = $departurePlan ?? null;
$departurePlanId = $departurePlanId ?? null;
$error = $error ?? null;
$conflictDetails = $conflictDetails ?? [];
?>

<style>
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

.form-row {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 20px;
  margin-bottom: 20px;
}

.form-group-modern {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.form-group-modern.full-width {
  grid-column: 1 / -1;
}

.form-group-modern label {
  font-weight: 600;
  font-size: 14px;
  color: var(--text-dark);
}

.form-group-modern input,
.form-group-modern select,
.form-group-modern textarea {
  padding: 12px 16px;
  border: 1px solid var(--border);
  border-radius: 8px;
  font-size: 14px;
  transition: all 0.2s;
  font-family: inherit;
}

.form-group-modern input:focus,
.form-group-modern select:focus,
.form-group-modern textarea:focus {
  outline: none;
  border-color: var(--primary);
  box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.form-group-modern textarea {
  resize: vertical;
  min-height: 100px;
}

.alert {
  padding: 16px;
  border-radius: 8px;
  margin-bottom: 20px;
}

.alert-danger {
  background: #fee;
  border: 1px solid #fcc;
  color: #c33;
}

.alert-warning {
  background: #fff8e1;
  border: 1px solid #ffcc02;
  color: #856404;
}

.conflict-list {
  margin-top: 12px;
  padding: 12px;
  background: #fff;
  border-radius: 6px;
  border: 1px solid #ffcc02;
}

.conflict-item {
  padding: 8px;
  border-bottom: 1px solid #eee;
}

.conflict-item:last-child {
  border-bottom: none;
}

.btn-group {
  display: flex;
  gap: 12px;
  margin-top: 24px;
}

.btn {
  padding: 12px 24px;
  border: none;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
}

.btn-primary {
  background: var(--primary);
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
</style>

<div class="form-card">
  <div class="card-header">
    <i class="fas fa-user-plus"></i>
    <h3>Tạo Phân công HDV</h3>
  </div>

  <?php if ($error): ?>
    <div class="alert alert-danger">
      <i class="fas fa-exclamation-circle"></i> <?= safe_html($error) ?>
    </div>
  <?php endif; ?>

  <?php if (!empty($conflictDetails)): ?>
    <div class="alert alert-warning">
      <strong><i class="fas fa-exclamation-triangle"></i> Cảnh báo trùng lịch:</strong>
      <p>HDV này đã có phân công trùng thời gian:</p>
      <div class="conflict-list">
        <?php foreach ($conflictDetails as $conflict): ?>
          <div class="conflict-item">
            <strong><?= safe_html($conflict['ten_tour'] ?? 'N/A') ?></strong><br>
            Từ <?= date('d/m/Y', strtotime($conflict['ngay_bat_dau'])) ?> 
            đến <?= date('d/m/Y', strtotime($conflict['ngay_ket_thuc'])) ?>
          </div>
        <?php endforeach; ?>
      </div>
      <p style="margin-top: 12px;">
        <label>
          <input type="checkbox" name="force_assign" id="force_assign" value="1">
          Vẫn tiếp tục phân công (bỏ qua cảnh báo)
        </label>
      </p>
    </div>
  <?php endif; ?>

  <form method="POST" action="?act=admin-assignment-create<?= $departurePlanId ? '&departure_plan_id=' . $departurePlanId : '' ?>">
    <div class="form-row">
      <div class="form-group-modern">
        <label for="id_lich_khoi_hanh">Lịch khởi hành <span style="color: red;">*</span></label>
        <select name="id_lich_khoi_hanh" id="id_lich_khoi_hanh" required>
          <option value="">-- Chọn lịch khởi hành --</option>
          <?php foreach ($departurePlans as $plan): ?>
            <?php
            $tourName = '';
            if ($plan['id_tour']) {
              $tourModel = new TourModel();
              $tour = $tourModel->getTourByID($plan['id_tour']);
              $tourName = $tour ? $tour['tengoi'] : '';
            }
            $selected = ($departurePlanId && $plan['id'] == $departurePlanId) ? 'selected' : '';
            ?>
            <option value="<?= $plan['id'] ?>" <?= $selected ?>>
              <?= safe_html($tourName) ?> - 
              <?= date('d/m/Y', strtotime($plan['ngay_khoi_hanh'])) ?>
              <?php if ($plan['gio_khoi_hanh']): ?>
                (<?= date('H:i', strtotime($plan['gio_khoi_hanh'])) ?>)
              <?php endif; ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-group-modern">
        <label for="id_hdv">Hướng dẫn viên <span style="color: red;">*</span></label>
        <select name="id_hdv" id="id_hdv" required>
          <option value="">-- Chọn HDV --</option>
          <?php foreach ($guides as $guide): ?>
            <option value="<?= $guide['id'] ?>">
              <?= safe_html($guide['ho_ten']) ?> 
              <?php if ($guide['so_dien_thoai']): ?>
                - <?= safe_html($guide['so_dien_thoai']) ?>
              <?php endif; ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group-modern">
        <label for="vai_tro">Vai trò</label>
        <select name="vai_tro" id="vai_tro">
          <option value="HDV chính">HDV chính</option>
          <option value="HDV phụ">HDV phụ</option>
          <option value="Trợ lý">Trợ lý</option>
        </select>
      </div>

      <div class="form-group-modern">
        <label for="luong">Lương (VNĐ)</label>
        <input type="number" name="luong" id="luong" min="0" step="1000" placeholder="Nhập lương">
      </div>
    </div>

    <div class="form-row">
      <div class="form-group-modern">
        <label for="ngay_bat_dau">Ngày bắt đầu <span style="color: red;">*</span></label>
        <input type="date" name="ngay_bat_dau" id="ngay_bat_dau" required 
               value="<?= $departurePlan ? date('Y-m-d', strtotime($departurePlan['ngay_khoi_hanh'])) : '' ?>">
      </div>

      <div class="form-group-modern">
        <label for="ngay_ket_thuc">Ngày kết thúc <span style="color: red;">*</span></label>
        <input type="date" name="ngay_ket_thuc" id="ngay_ket_thuc" required
               value="<?= $departurePlan ? date('Y-m-d', strtotime($departurePlan['ngay_ket_thuc'])) : '' ?>">
      </div>
    </div>

    <div class="form-group-modern full-width">
      <label for="ghi_chu">Ghi chú</label>
      <textarea name="ghi_chu" id="ghi_chu" placeholder="Ghi chú về phân công này..."></textarea>
    </div>

    <?php if (!empty($conflictDetails)): ?>
      <input type="hidden" name="force_assign" id="force_assign_hidden" value="0">
    <?php endif; ?>

    <div class="btn-group">
      <button type="submit" class="btn btn-primary">
        <i class="fas fa-save"></i> Lưu phân công
      </button>
      <a href="?act=admin-assignments<?= $departurePlanId ? '&id_lich_khoi_hanh=' . $departurePlanId : '' ?>" class="btn btn-secondary">
        <i class="fas fa-times"></i> Hủy
      </a>
    </div>
  </form>
</div>

<script>
// Auto-fill dates from departure plan
document.addEventListener('DOMContentLoaded', function() {
  const departurePlanSelect = document.getElementById('id_lich_khoi_hanh');
  const ngayBatDau = document.getElementById('ngay_bat_dau');
  const ngayKetThuc = document.getElementById('ngay_ket_thuc');
  
  // Store departure plan data
  const departurePlansData = {
    <?php foreach ($departurePlans as $plan): ?>
    '<?= $plan['id'] ?>': {
      ngay_khoi_hanh: '<?= $plan['ngay_khoi_hanh'] ?>',
      ngay_ket_thuc: '<?= $plan['ngay_ket_thuc'] ?? $plan['ngay_khoi_hanh'] ?>'
    },
    <?php endforeach; ?>
  };
  
  if (departurePlanSelect && ngayBatDau && ngayKetThuc) {
    departurePlanSelect.addEventListener('change', function() {
      const planId = this.value;
      if (planId && departurePlansData[planId]) {
        const plan = departurePlansData[planId];
        if (plan.ngay_khoi_hanh) {
          ngayBatDau.value = plan.ngay_khoi_hanh.split(' ')[0]; // Extract date part
        }
        if (plan.ngay_ket_thuc) {
          ngayKetThuc.value = plan.ngay_ket_thuc.split(' ')[0]; // Extract date part
        }
      }
    });
  }

  // Handle force_assign checkbox
  const forceAssignCheckbox = document.getElementById('force_assign');
  const forceAssignHidden = document.getElementById('force_assign_hidden');
  if (forceAssignCheckbox && forceAssignHidden) {
    forceAssignCheckbox.addEventListener('change', function() {
      forceAssignHidden.value = this.checked ? '1' : '0';
    });
  }
});
</script>

