<?php
/**
 * Sửa Dịch vụ - Modern Interface
 * UC-Assign-Services: Cập nhật dịch vụ
 */

function safe_html($value) {
    return htmlentities($value ?? '', ENT_QUOTES, 'UTF-8');
}

$serviceTypes = $serviceTypes ?? [];
$error = $error ?? null;

if (!$service) {
    echo '<div style="padding: 20px; background: #fee2e2; color: #991b1b; border-radius: 8px;">Không tìm thấy dịch vụ</div>';
    exit;
}
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
  <i class="fas fa-concierge-bell" style="color: var(--primary);"></i>
  Sửa Dịch vụ
</h1>

<?php if ($error): ?>
  <div style="padding: 16px; background: #fee2e2; color: #991b1b; border-radius: 8px; margin-bottom: 20px;">
    <i class="fas fa-exclamation-circle"></i> <?= safe_html($error) ?>
  </div>
<?php endif; ?>

<form method="POST" action="<?= BASE_URL ?>?act=admin-service-edit&id=<?= $service['id'] ?>">
  <div class="form-card">
    <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 20px;">Thông tin cơ bản</h3>
    
    <div class="form-group-modern">
      <label>Tên dịch vụ <span style="color: #ef4444;">*</span></label>
      <input type="text" name="ten_dich_vu" value="<?= safe_html($service['ten_dich_vu']) ?>" required>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
      <div class="form-group-modern">
        <label>Loại dịch vụ <span style="color: #ef4444;">*</span></label>
        <select name="loai_dich_vu" required>
          <option value="">-- Chọn loại --</option>
          <?php foreach ($serviceTypes as $key => $label): 
            $selected = ($service['loai_dich_vu'] == $key) ? 'selected' : '';
          ?>
            <option value="<?= $key ?>" <?= $selected ?>><?= safe_html($label) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-group-modern">
        <label>Nhà cung cấp</label>
        <input type="text" name="nha_cung_cap" value="<?= safe_html($service['nha_cung_cap'] ?? '') ?>" placeholder="Tên đối tác/nhà cung cấp">
      </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
      <div class="form-group-modern">
        <label>Giá (VNĐ)</label>
        <input type="number" name="gia" step="0.01" min="0" value="<?= $service['gia'] ?? '' ?>" placeholder="VD: 5000000">
      </div>

      <div class="form-group-modern">
        <label>Đơn vị tính</label>
        <input type="text" name="don_vi" value="<?= safe_html($service['don_vi'] ?? '') ?>" placeholder="VD: chuyến, đêm, bữa, vé">
      </div>
    </div>

    <div class="form-group-modern">
      <label>Liên hệ</label>
      <input type="text" name="lien_he" value="<?= safe_html($service['lien_he'] ?? '') ?>" placeholder="SĐT, Email, Địa chỉ...">
    </div>

    <div class="form-group-modern">
      <label>Mô tả</label>
      <textarea name="mo_ta" placeholder="Mô tả chi tiết về dịch vụ..."><?= safe_html($service['mo_ta'] ?? '') ?></textarea>
    </div>

    <div class="form-group-modern">
      <label>Trạng thái</label>
      <select name="trang_thai">
        <option value="1" <?= ($service['trang_thai'] == 1) ? 'selected' : '' ?>>Hoạt động</option>
        <option value="0" <?= ($service['trang_thai'] == 0) ? 'selected' : '' ?>>Tạm dừng</option>
      </select>
    </div>

    <div class="form-group-modern">
      <label>Ghi chú</label>
      <textarea name="ghi_chu" placeholder="Ghi chú bổ sung..."><?= safe_html($service['ghi_chu'] ?? '') ?></textarea>
    </div>
  </div>

  <div class="form-actions">
    <a href="<?= BASE_URL ?>?act=admin-services" class="btn-cancel">
      <i class="fas fa-times"></i> Hủy
    </a>
    <button type="submit" class="btn-submit">
      <i class="fas fa-save"></i> Cập nhật dịch vụ
    </button>
  </div>
</form>

