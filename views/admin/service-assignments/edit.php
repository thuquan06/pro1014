<?php
/**
 * Sửa Gán dịch vụ - Modern Interface
 * UC-Assign-Services: Cập nhật gán dịch vụ
 */

function safe_html($value) {
    return htmlentities($value ?? '', ENT_QUOTES, 'UTF-8');
}

function formatDate($date) {
    return $date ? date('d/m/Y', strtotime($date)) : '-';
}

$error = $error ?? null;

if (!$assignment) {
    echo '<div style="padding: 20px; background: #fee2e2; color: #991b1b; border-radius: 8px;">Không tìm thấy gán dịch vụ</div>';
    exit;
}

$services = $services ?? [];
$departurePlans = $departurePlans ?? [];
$serviceTypes = $serviceTypes ?? [];
$statuses = $statuses ?? [];
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
  Sửa Gán dịch vụ
</h1>

<?php if ($error): ?>
  <div style="padding: 16px; background: #fee2e2; color: #991b1b; border-radius: 8px; margin-bottom: 20px;">
    <i class="fas fa-exclamation-circle"></i> <?= safe_html($error) ?>
  </div>
<?php endif; ?>

<form method="POST" action="<?= BASE_URL ?>?act=admin-service-assignment-edit&id=<?= $assignment['id'] ?>">
  <div class="form-card">
    <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 20px;">Thông tin gán dịch vụ</h3>
    
    <div class="form-group-modern">
      <label>Lịch khởi hành <span style="color: #ef4444;">*</span></label>
      <select name="id_lich_khoi_hanh" required>
        <option value="">-- Chọn lịch khởi hành --</option>
        <?php foreach ($departurePlans as $dp): 
          $selected = ($assignment['id_lich_khoi_hanh'] == $dp['id']) ? 'selected' : '';
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
      </select>
    </div>

    <div class="form-group-modern">
      <label>Dịch vụ <span style="color: #ef4444;">*</span></label>
      <select name="id_dich_vu" required>
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
            <?php foreach ($servicesByType[$typeKey] as $service): 
              $selected = ($assignment['id_dich_vu'] == $service['id']) ? 'selected' : '';
            ?>
              <option value="<?= $service['id'] ?>" <?= $selected ?>>
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
        <input type="number" name="so_luong" value="<?= $assignment['so_luong'] ?? 1 ?>" min="1" required>
      </div>

      <div class="form-group-modern">
        <label>Ngày sử dụng</label>
        <input type="date" name="ngay_su_dung" value="<?= $assignment['ngay_su_dung'] ?? '' ?>">
      </div>
    </div>

    <div class="form-group-modern">
      <label>Giá thực tế (VNĐ)</label>
      <input type="number" name="gia_thuc_te" value="<?= $assignment['gia_thuc_te'] ?? '' ?>" step="0.01" min="0" placeholder="Để trống nếu dùng giá mặc định">
    </div>

    <div class="form-group-modern">
      <label>Trạng thái</label>
      <select name="trang_thai">
        <?php foreach ($statuses as $key => $label): 
          $selected = ($assignment['trang_thai'] == $key) ? 'selected' : '';
        ?>
          <option value="<?= $key ?>" <?= $selected ?>><?= safe_html($label) ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="form-group-modern">
      <label>Ghi chú</label>
      <textarea name="ghi_chu" placeholder="Ghi chú về việc gán dịch vụ..."><?= safe_html($assignment['ghi_chu'] ?? '') ?></textarea>
    </div>
  </div>

  <div class="form-actions">
    <a href="<?= BASE_URL ?>?act=admin-service-assignments" class="btn-cancel">
      <i class="fas fa-times"></i> Hủy
    </a>
    <button type="submit" class="btn-submit">
      <i class="fas fa-save"></i> Cập nhật gán dịch vụ
    </button>
  </div>
</form>



