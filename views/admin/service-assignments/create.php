<?php
/**
 * Gán Dịch vụ - Modern Interface
 * UC-Assign-Services: Gán dịch vụ cho lịch khởi hành
 */

function safe_html($value) {
    return htmlentities($value ?? '', ENT_QUOTES, 'UTF-8');
}

function formatDate($date) {
    return $date ? date('d/m/Y', strtotime($date)) : '-';
}

$error = $error ?? null;
$departurePlan = $departurePlan ?? null;
$departurePlanId = $departurePlanId ?? null;
$services = $services ?? [];
$departurePlans = $departurePlans ?? [];
$serviceTypes = $serviceTypes ?? [];
?>

<style>
.form-card {
  background: white;
  border: 1px solid var(--border);
  border-radius: 12px;
  padding: 24px;
  margin-bottom: 20px;
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

.form-group-modern input,
.form-group-modern select,
.form-group-modern textarea {
  padding: 12px 16px;
  border: 1px solid var(--border);
  border-radius: 8px;
  font-size: 14px;
  transition: all 0.2s;
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
  cursor: pointer;
}

.btn-cancel {
  padding: 12px 32px;
  background: white;
  color: var(--text-dark);
  border: 1px solid var(--border);
  border-radius: 8px;
  font-weight: 600;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
}
</style>

<h1 style="font-size: 28px; font-weight: 700; margin-bottom: 24px;">
  <i class="fas fa-tasks" style="color: var(--primary);"></i>
  Gán Dịch vụ mới
</h1>

<?php if ($error): ?>
  <div style="padding: 16px; background: #fee2e2; color: #991b1b; border-radius: 8px; margin-bottom: 20px;">
    <i class="fas fa-exclamation-circle"></i> <?= safe_html($error) ?>
  </div>
<?php endif; ?>

<form method="POST" action="<?= BASE_URL ?>?act=admin-service-assignment-create<?= $departurePlanId ? '&departure_plan_id=' . $departurePlanId : '' ?>">
  <div class="form-card">
    <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 20px;">Thông tin gán dịch vụ</h3>
    
    <?php if ($departurePlan): ?>
      <div style="background: var(--bg-light); padding: 16px; border-radius: 8px; margin-bottom: 20px;">
        <h4><?= safe_html($departurePlan['tengoi'] ?? 'Tour') ?></h4>
        <p><strong>Ngày khởi hành:</strong> <?= formatDate($departurePlan['ngay_khoi_hanh']) ?></p>
        <p><strong>Giờ khởi hành:</strong> <?= $departurePlan['gio_khoi_hanh'] ? date('H:i', strtotime($departurePlan['gio_khoi_hanh'])) : '-' ?></p>
      </div>
      <input type="hidden" name="id_lich_khoi_hanh" value="<?= $departurePlan['id'] ?>">
    <?php else: ?>
      <div class="form-group-modern">
        <label>Lịch khởi hành <span style="color: #ef4444;">*</span></label>
        <select name="id_lich_khoi_hanh" required>
          <option value="">-- Chọn lịch khởi hành --</option>
          <?php foreach ($departurePlans as $dp): 
            $ngay_gio = '';
            if ($dp['ngay_khoi_hanh']) {
              $ngay_gio = date('d/m/Y', strtotime($dp['ngay_khoi_hanh']));
              if ($dp['gio_khoi_hanh']) {
                $ngay_gio .= ' ' . date('H:i', strtotime($dp['gio_khoi_hanh']));
              }
            }
          ?>
            <option value="<?= $dp['id'] ?>">
              <?= safe_html($dp['tengoi'] ?? 'Tour') ?> - <?= $ngay_gio ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
    <?php endif; ?>

    <div class="form-group-modern">
      <label>Dịch vụ <span style="color: #ef4444;">*</span></label>
      <select name="id_dich_vu" id="id_dich_vu" required onchange="updateServiceInfo()">
        <option value="">-- Chọn dịch vụ --</option>
        <?php 
        $servicesByType = [];
        foreach ($services as $service) {
          $servicesByType[$service['loai_dich_vu']][] = $service;
        }
        foreach ($serviceTypes as $typeKey => $typeLabel): 
          if (isset($servicesByType[$typeKey])):
        ?>
          <optgroup label="<?= safe_html($typeLabel) ?>">
            <?php foreach ($servicesByType[$typeKey] as $service): ?>
              <option value="<?= $service['id'] ?>" 
                      data-gia="<?= $service['gia'] ?? '' ?>"
                      data-don-vi="<?= safe_html($service['don_vi'] ?? '') ?>">
                <?= safe_html($service['ten_dich_vu']) ?>
                <?php if ($service['gia']): ?>
                  - <?= number_format($service['gia'], 0, ',', '.') ?> VNĐ
                <?php endif; ?>
              </option>
            <?php endforeach; ?>
          </optgroup>
        <?php 
          endif;
        endforeach; ?>
      </select>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
      <div class="form-group-modern">
        <label>Số lượng <span style="color: #ef4444;">*</span></label>
        <input type="number" name="so_luong" value="1" min="1" required>
      </div>

      <div class="form-group-modern">
        <label>Ngày sử dụng</label>
        <input type="date" name="ngay_su_dung">
      </div>
    </div>

    <div class="form-group-modern">
      <label>Giá thực tế (VNĐ)</label>
      <input type="number" name="gia_thuc_te" id="gia_thuc_te" step="0.01" min="0" placeholder="Để trống nếu dùng giá mặc định">
      <small style="color: var(--text-light);">Giá sẽ tự động điền từ dịch vụ đã chọn</small>
    </div>

    <div class="form-group-modern">
      <label>Ghi chú</label>
      <textarea name="ghi_chu" placeholder="Ghi chú về việc gán dịch vụ..."></textarea>
    </div>

    <input type="hidden" name="trang_thai" value="cho">
  </div>

  <div class="form-actions">
    <a href="<?= BASE_URL ?>?act=admin-service-assignments" class="btn-cancel">
      <i class="fas fa-times"></i> Hủy
    </a>
    <button type="submit" class="btn-submit">
      <i class="fas fa-save"></i> Gán dịch vụ
    </button>
  </div>
</form>

<script>
function updateServiceInfo() {
  const select = document.getElementById('id_dich_vu');
  const selectedOption = select.options[select.selectedIndex];
  const gia = selectedOption.getAttribute('data-gia');
  const donVi = selectedOption.getAttribute('data-don-vi');
  
  if (gia) {
    document.getElementById('gia_thuc_te').value = gia;
  }
}
</script>



