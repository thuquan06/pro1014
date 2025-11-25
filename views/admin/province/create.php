<style>
.province-form-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
}

.province-form-title {
  font-size: 28px;
  font-weight: 700;
  color: var(--text-dark);
  margin: 0;
}

.form-card {
  background: white;
  border: 1px solid var(--border);
  border-radius: 12px;
  padding: 32px;
  max-width: 600px;
}

.form-group {
  margin-bottom: 24px;
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

.form-input {
  width: 100%;
  padding: 12px 16px;
  border: 1px solid var(--border);
  border-radius: 8px;
  font-size: 14px;
  transition: all 0.2s;
}

.form-input:focus {
  outline: none;
  border-color: var(--primary);
  box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
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
</style>

<!-- Page Header -->
<div class="province-form-header">
  <div>
    <h1 class="province-form-title">
      <i class="fas fa-plus-circle" style="color: var(--primary);"></i>
      Thêm Tỉnh/Thành phố
    </h1>
    <div class="breadcrumb" style="margin-top: 8px;">
      <a href="<?=BASE_URL?>?act=admin" style="color: var(--text-light); text-decoration: none;">Trang chủ</a>
      <i class="fa fa-angle-right" style="margin: 0 8px; color: var(--text-light);"></i>
      <a href="<?=BASE_URL?>?act=province-list" style="color: var(--text-light); text-decoration: none;">Danh sách tỉnh</a>
      <i class="fa fa-angle-right" style="margin: 0 8px; color: var(--text-light);"></i>
      <span style="color: var(--text-dark);">Thêm mới</span>
    </div>
  </div>
  <a href="<?= BASE_URL ?>?act=province-list" class="btn btn-secondary">
    <i class="fas fa-arrow-left"></i>
    Quay lại
  </a>
</div>

<!-- Error Message -->
<?php if (isset($_SESSION['error'])): ?>
  <div class="alert alert-error" style="margin-bottom: 20px;">
    <i class="fas fa-exclamation-circle"></i>
    <?= $_SESSION['error']; unset($_SESSION['error']); ?>
  </div>
<?php endif; ?>

<!-- Form -->
<form action="<?= BASE_URL ?>?act=province-store" method="POST">
  <div class="form-card">
    
    <div class="form-group">
      <label class="form-label" for="ten_tinh">
        Tên tỉnh/thành phố <span class="required">*</span>
      </label>
      <input type="text" 
             name="ten_tinh" 
             id="ten_tinh" 
             class="form-input" 
             required 
             value="<?= $_SESSION['old_data']['ten_tinh'] ?? '' ?>"
             placeholder="VD: Hà Nội, Thành phố Hồ Chí Minh...">
    </div>

    <div class="form-actions">
      <a href="<?= BASE_URL ?>?act=province-list" class="btn-cancel">
        <i class="fas fa-times"></i>
        Hủy bỏ
      </a>
      <button type="submit" class="btn-submit">
        <i class="fas fa-save"></i>
        Thêm mới
      </button>
    </div>

  </div>
</form>

<?php unset($_SESSION['old_data']); ?>
