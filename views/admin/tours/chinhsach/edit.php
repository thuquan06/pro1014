<?php
/**
 * Sửa Chính sách Tour - Modern Interface
 * Updated: 2025-11-25
 */

ob_start();
?>

<style>
.policy-form-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
}

.policy-form-title {
  font-size: 28px;
  font-weight: 700;
  color: var(--text-dark);
  margin: 0;
}

.policy-form-subtitle {
  color: var(--text-light);
  font-size: 14px;
  margin-top: 4px;
}

.form-card {
  background: white;
  border: 1px solid var(--border);
  border-radius: 12px;
  padding: 32px;
}

.form-section {
  margin-bottom: 24px;
}

.form-group {
  margin-bottom: 20px;
}

.form-label {
  display: block;
  font-weight: 600;
  font-size: 14px;
  color: var(--text-dark);
  margin-bottom: 8px;
}

.form-label .required {
  color: #ef4444;
  margin-left: 4px;
}

.form-input,
.form-select,
.form-textarea {
  width: 100%;
  padding: 12px 16px;
  border: 1px solid var(--border);
  border-radius: 8px;
  font-size: 14px;
  transition: all 0.2s;
  font-family: inherit;
}

.form-input:focus,
.form-select:focus,
.form-textarea:focus {
  outline: none;
  border-color: var(--primary);
  box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.form-textarea {
  resize: vertical;
  line-height: 1.6;
}

.form-hint {
  display: block;
  margin-top: 6px;
  font-size: 12px;
  color: var(--text-light);
}

.form-hint i {
  margin-right: 4px;
}

.form-row {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 20px;
}

.conditional-fields {
  background: #fffbeb;
  border: 1px solid #fbbf24;
  border-radius: 8px;
  padding: 20px;
  margin-bottom: 20px;
}

.conditional-fields-title {
  font-size: 14px;
  font-weight: 600;
  color: #78350f;
  margin: 0 0 16px 0;
  display: flex;
  align-items: center;
  gap: 8px;
}

.form-actions {
  display: flex;
  gap: 12px;
  justify-content: flex-end;
  padding-top: 24px;
  border-top: 2px solid var(--bg-light);
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
}

@media (max-width: 768px) {
  .form-row {
    grid-template-columns: 1fr;
  }
  
  .form-actions {
    flex-direction: column;
  }
  
  .btn-submit,
  .btn-cancel {
    width: 100%;
    justify-content: center;
  }
}
</style>

<!-- Page Header -->
<div class="policy-form-header">
  <div>
    <h1 class="policy-form-title">
      <i class="fas fa-edit" style="color: var(--primary);"></i>
      Sửa Chính sách
    </h1>
    <p class="policy-form-subtitle">Tour ID: <?= $idGoi ?> | Policy ID: <?= $chinhsach['id'] ?></p>
  </div>
  <a href="<?= BASE_URL ?>?act=tour-chinhsach&id_goi=<?= $idGoi ?>" class="btn btn-secondary">
    <i class="fas fa-arrow-left"></i>
    Quay lại
  </a>
</div>

<!-- Thông báo lỗi -->
<?php if (isset($_SESSION['errors']) && !empty($_SESSION['errors'])): ?>
  <div class="alert alert-error" style="margin-bottom: 20px;">
    <strong><i class="fas fa-exclamation-circle"></i> Có lỗi xảy ra:</strong>
    <ul style="margin: 10px 0 0 20px;">
      <?php foreach ($_SESSION['errors'] as $error): ?>
        <li><?= htmlspecialchars($error) ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
  <?php unset($_SESSION['errors']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
  <div class="alert alert-error" style="margin-bottom: 20px;">
    <i class="fas fa-exclamation-circle"></i>
    <?= $_SESSION['error'] ?>
  </div>
  <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<!-- Form -->
<form method="POST" action="">
  <input type="hidden" name="id" value="<?= $chinhsach['id'] ?>">
  <input type="hidden" name="id_goi" value="<?= $idGoi ?>">

  <div class="form-card">
    <!-- Loại chính sách -->
    <div class="form-group">
      <label class="form-label" for="loai_chinhsach">
        Loại chính sách <span class="required">*</span>
      </label>
      <select class="form-select" id="loai_chinhsach" name="loai_chinhsach" required onchange="toggleFields()">
        <option value="">-- Chọn loại chính sách --</option>
        <option value="huy_doi" <?= $chinhsach['loai_chinhsach'] == 'huy_doi' ? 'selected' : '' ?>>🔄 Hủy/Đổi Tour</option>
        <option value="suc_khoe" <?= $chinhsach['loai_chinhsach'] == 'suc_khoe' ? 'selected' : '' ?>>💊 Sức khỏe</option>
        <option value="hanh_ly" <?= $chinhsach['loai_chinhsach'] == 'hanh_ly' ? 'selected' : '' ?>>🎒 Hành lý</option>
        <option value="thanh_toan" <?= $chinhsach['loai_chinhsach'] == 'thanh_toan' ? 'selected' : '' ?>>💳 Thanh toán</option>
        <option value="visa" <?= $chinhsach['loai_chinhsach'] == 'visa' ? 'selected' : '' ?>>🛂 Visa</option>
        <option value="bao_hiem" <?= $chinhsach['loai_chinhsach'] == 'bao_hiem' ? 'selected' : '' ?>>🛡️ Bảo hiểm</option>
        <option value="khac" <?= $chinhsach['loai_chinhsach'] == 'khac' ? 'selected' : '' ?>>📝 Khác</option>
      </select>
    </div>

    <!-- Nội dung -->
    <div class="form-group">
      <label class="form-label" for="noidung">
        Nội dung <span class="required">*</span>
      </label>
      <textarea 
        class="form-textarea" 
        id="noidung" 
        name="noidung" 
        rows="8"
        placeholder="Nhập nội dung chính sách chi tiết..."
        required
      ><?= htmlspecialchars($chinhsach['noidung']) ?></textarea>
      <small class="form-hint">
        <i class="fas fa-info-circle"></i>
        Mô tả chi tiết chính sách
      </small>
    </div>

    <!-- Các trường đặc biệt cho Hủy/Đổi -->
    <div id="huy_doi_fields" style="<?= $chinhsach['loai_chinhsach'] == 'huy_doi' ? 'display: block;' : 'display: none;' ?>">
      <div class="conditional-fields">
        <h4 class="conditional-fields-title">
          <i class="fas fa-info-circle"></i>
          Thông tin hoàn tiền (cho chính sách Hủy/Đổi)
        </h4>
        
        <div class="form-row">
          <div class="form-group">
            <label class="form-label" for="so_ngay_truoc">
              Hủy trước (ngày)
            </label>
            <input 
              type="number" 
              class="form-input" 
              id="so_ngay_truoc" 
              name="so_ngay_truoc" 
              min="0"
              value="<?= $chinhsach['so_ngay_truoc'] ?? '' ?>"
              placeholder="VD: 30"
            >
            <small class="form-hint">Số ngày trước khi khởi hành</small>
          </div>
          
          <div class="form-group">
            <label class="form-label" for="phantram_hoantien">
              Phần trăm hoàn tiền (%)
            </label>
            <input 
              type="number" 
              class="form-input" 
              id="phantram_hoantien" 
              name="phantram_hoantien" 
              min="0" 
              max="100" 
              step="0.01"
              value="<?= $chinhsach['phantram_hoantien'] ?? '' ?>"
              placeholder="VD: 100"
            >
            <small class="form-hint">% tiền được hoàn lại</small>
          </div>
        </div>
      </div>
    </div>

    <!-- Thứ tự hiển thị -->
    <div class="form-group">
      <label class="form-label" for="thutu_hienthi">
        Thứ tự hiển thị
      </label>
      <input 
        type="number" 
        class="form-input" 
        id="thutu_hienthi" 
        name="thutu_hienthi" 
        value="<?= $chinhsach['thutu_hienthi'] ?? 0 ?>"
        min="0"
        placeholder="0"
      >
      <small class="form-hint">
        <i class="fas fa-info-circle"></i>
        Số thứ tự để sắp xếp (0 = mặc định)
      </small>
    </div>

    <!-- Buttons -->
    <div class="form-actions">
      <a href="<?= BASE_URL ?>?act=tour-chinhsach&id_goi=<?= $idGoi ?>" class="btn-cancel">
        <i class="fas fa-times"></i>
        Hủy bỏ
      </a>
      <button type="submit" class="btn-submit">
        <i class="fas fa-save"></i>
        Cập nhật
      </button>
    </div>
  </div>
</form>

<!-- CKEditor -->
<script src="<?= BASE_URL ?>assets/ckeditor/ckeditor.js"></script>
<script>
  CKEDITOR.replace('noidung', {
    height: 300
  });
</script>

<!-- JavaScript -->
<script>
function toggleFields() {
  var loai = document.getElementById('loai_chinhsach').value;
  var huyDoiFields = document.getElementById('huy_doi_fields');
  
  if (loai === 'huy_doi') {
    huyDoiFields.style.display = 'block';
  } else {
    huyDoiFields.style.display = 'none';
    document.getElementById('so_ngay_truoc').value = '';
    document.getElementById('phantram_hoantien').value = '';
  }
}
</script>

<?php
$content = ob_get_clean();
include './views/admin/layout.php';
?>
