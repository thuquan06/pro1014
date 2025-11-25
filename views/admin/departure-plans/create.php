<?php
/**
 * Tạo Lịch khởi hành - Modern Interface
 * UC-Departure-Plan: Tạo lịch khởi hành mới
 */

function safe_html($value) {
    return htmlentities($value ?? '', ENT_QUOTES, 'UTF-8');
}
?>

<style>
.departure-form-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
}

.departure-form-title {
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

.form-group-modern label .required {
  color: #ef4444;
  margin-left: 4px;
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

.form-group-modern .help-text {
  font-size: 12px;
  color: var(--text-light);
  margin-top: -4px;
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

.alert {
  padding: 12px 16px;
  border-radius: 8px;
  margin-bottom: 20px;
  display: flex;
  align-items: center;
  gap: 8px;
}

.alert-error {
  background: #fee2e2;
  color: #991b1b;
  border: 1px solid #fecaca;
}

.alert-success {
  background: #d1fae5;
  color: #065f46;
  border: 1px solid #a7f3d0;
}

@media (max-width: 768px) {
  .form-row {
    grid-template-columns: 1fr;
  }
}
</style>

<!-- Page Header -->
<div class="departure-form-header">
  <h1 class="departure-form-title">
    <i class="fas fa-calendar-plus" style="color: var(--primary);"></i>
    Tạo Lịch khởi hành mới
  </h1>
</div>

<?php if (isset($error)): ?>
  <div class="alert alert-error">
    <i class="fas fa-exclamation-circle"></i>
    <?= safe_html($error) ?>
  </div>
<?php endif; ?>

<?php if (isset($msg)): ?>
  <div class="alert alert-success">
    <i class="fas fa-check-circle"></i>
    <?= safe_html($msg) ?>
  </div>
<?php endif; ?>

<!-- Form -->
<form method="POST" action="<?= BASE_URL ?>?act=admin-departure-plan-create">
  <div class="form-card">
    <div class="card-header">
      <i class="fas fa-info-circle"></i>
      <h3>Thông tin cơ bản</h3>
    </div>

    <div class="form-row">
      <div class="form-group-modern">
        <label>
          Tour <span class="required">*</span>
        </label>
        <select name="id_tour" required>
          <option value="">-- Chọn tour --</option>
          <?php 
          $selectedTourId = $_GET['id_tour'] ?? ($_POST['id_tour'] ?? null);
          foreach ($tours as $tour): 
          ?>
            <option value="<?= $tour['id_goi'] ?>" 
                    <?= ($selectedTourId && $selectedTourId == $tour['id_goi']) ? 'selected' : '' ?>>
              <?= safe_html($tour['tengoi']) ?> 
              <?php if (!empty($tour['ngayxuatphat'])): ?>
                (<?= date('d/m/Y', strtotime($tour['ngayxuatphat'])) ?>)
              <?php endif; ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-group-modern">
        <label>
          Trạng thái <span class="required">*</span>
        </label>
        <select name="trang_thai" required>
          <option value="1" <?= (!isset($_POST['trang_thai']) || $_POST['trang_thai'] == '1') ? 'selected' : '' ?>>
            Hoạt động
          </option>
          <option value="0" <?= (isset($_POST['trang_thai']) && $_POST['trang_thai'] == '0') ? 'selected' : '' ?>>
            Tạm dừng
          </option>
        </select>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group-modern">
        <label>
          Ngày khởi hành <span class="required">*</span>
        </label>
        <input type="date" 
               name="ngay_khoi_hanh" 
               value="<?= safe_html($_POST['ngay_khoi_hanh'] ?? '') ?>" 
               required>
        <span class="help-text">Chọn ngày khởi hành</span>
      </div>

      <div class="form-group-modern">
        <label>
          Giờ khởi hành <span class="required">*</span>
        </label>
        <input type="time" 
               name="gio_khoi_hanh" 
               value="<?= safe_html($_POST['gio_khoi_hanh'] ?? '') ?>" 
               required>
        <span class="help-text">Chọn giờ khởi hành (ví dụ: 08:00)</span>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group-modern">
        <label>
          Điểm tập trung <span class="required">*</span>
        </label>
        <input type="text" 
               name="diem_tap_trung" 
               value="<?= safe_html($_POST['diem_tap_trung'] ?? '') ?>" 
               placeholder="Ví dụ: Sân bay Nội Bài, Hà Nội"
               required>
        <span class="help-text">Địa điểm tập trung trước khi khởi hành</span>
      </div>

      <div class="form-group-modern">
        <label>
          Số chỗ dự kiến
        </label>
        <input type="number" 
               name="so_cho_du_kien" 
               value="<?= safe_html($_POST['so_cho_du_kien'] ?? '') ?>" 
               placeholder="Ví dụ: 30"
               min="1">
        <span class="help-text">Số lượng chỗ dự kiến (không bắt buộc)</span>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group-modern full-width">
        <label>
          Ghi chú vận hành
        </label>
        <textarea name="ghi_chu_van_hanh" 
                  placeholder="Nhập các ghi chú về vận hành, lưu ý đặc biệt..."><?= safe_html($_POST['ghi_chu_van_hanh'] ?? '') ?></textarea>
        <span class="help-text">Các ghi chú về vận hành, lưu ý đặc biệt cho tour này</span>
      </div>
    </div>
  </div>

  <!-- Form Actions -->
  <div class="form-actions">
    <a href="<?= BASE_URL ?>?act=admin-departure-plans" class="btn-cancel">
      <i class="fas fa-times"></i>
      Hủy
    </a>
    <button type="submit" class="btn-submit">
      <i class="fas fa-save"></i>
      Tạo lịch khởi hành
    </button>
  </div>
</form>

