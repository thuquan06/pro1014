<?php
/**
 * Tạo/Sửa Người Dùng - Admin View
 * UC-User-Management: Quản lý thông tin người dùng
 */

function safe_html($value) {
    return htmlentities($value ?? '', ENT_QUOTES, 'UTF-8');
}

$user = $user ?? null;
?>

<style>
.form-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
}

.form-title {
  font-size: 28px;
  font-weight: 700;
  color: var(--text-dark);
  display: flex;
  align-items: center;
  gap: 12px;
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

.form-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 20px;
}

.form-group-modern {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.form-group-modern label {
  font-weight: 600;
  font-size: 14px;
  color: var(--text-dark);
}

.form-group-modern label .required {
  color: #ef4444;
}

.form-group-modern input,
.form-group-modern textarea,
.form-group-modern select {
  padding: 12px 16px;
  border: 1px solid var(--border);
  border-radius: 8px;
  font-size: 14px;
  transition: all 0.2s;
  font-family: inherit;
}

.form-group-modern input:focus,
.form-group-modern textarea:focus,
.form-group-modern select:focus {
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

.checkbox-group {
  display: flex;
  align-items: center;
  gap: 8px;
}

.checkbox-group input[type="checkbox"] {
  width: 20px;
  height: 20px;
  cursor: pointer;
}

.checkbox-group label {
  font-weight: 500;
  cursor: pointer;
  margin: 0;
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
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: 8px;
}

.btn-cancel:hover {
  background: var(--bg-light);
  border-color: var(--text-light);
}
</style>

<!-- Page Header -->
<div class="form-header">
  <h1 class="form-title">
    <i class="fas fa-user-plus"></i>
    <?= $user ? 'Sửa' : 'Thêm' ?> Người Dùng
  </h1>
</div>

<!-- Form -->
<form method="POST" action="<?= BASE_URL ?>?act=admin-user-<?= $user ? 'update&id=' . $user['id_nguoidung'] : 'store' ?>">
  <?php if ($user): ?>
    <input type="hidden" name="id" value="<?= $user['id_nguoidung'] ?>">
  <?php endif; ?>

  <!-- Thông tin cơ bản -->
  <div class="form-card">
    <div class="card-header">
      <i class="fas fa-user"></i>
      <h3>Thông tin cơ bản</h3>
    </div>

    <div class="form-grid">
      <div class="form-group-modern">
        <label>
          Họ và tên <span class="required">*</span>
        </label>
        <input type="text" name="hoten" value="<?= safe_html($user['hoten'] ?? '') ?>" required>
      </div>

      <div class="form-group-modern">
        <label>
          Email <span class="required">*</span>
        </label>
        <input type="email" name="id_email" value="<?= safe_html($user['id_email'] ?? '') ?>" required>
        <span class="help-text">Email phải là duy nhất</span>
      </div>

      <div class="form-group-modern">
        <label>Số điện thoại <span class="required">*</span></label>
        <input type="tel" name="sdt_nd" value="<?= safe_html($user['sdt_nd'] ?? '') ?>" placeholder="0912345678" required maxlength="12">
      </div>

      <div class="form-group-modern">
        <label>Ngày sinh</label>
        <input type="text" name="ngaysinh" value="<?= safe_html($user['ngaysinh'] ?? '') ?>" placeholder="dd/mm/yyyy hoặc yyyy-mm-dd">
        <span class="help-text">Nhập ngày sinh (có thể là text hoặc date)</span>
      </div>

      <div class="form-group-modern">
        <label>Hình ảnh</label>
        <input type="text" name="hinhanh" value="<?= safe_html($user['hinhanh'] ?? '') ?>" placeholder="URL hoặc đường dẫn hình ảnh">
      </div>
    </div>

    <div class="form-group-modern">
      <label>Địa chỉ <span class="required">*</span></label>
      <input type="text" name="diachi" value="<?= safe_html($user['diachi'] ?? '') ?>" placeholder="Nhập địa chỉ..." required>
    </div>
  </div>

  <!-- Mật khẩu -->
  <div class="form-card">
    <div class="card-header">
      <i class="fas fa-lock"></i>
      <h3>Mật khẩu</h3>
    </div>

    <div class="form-group-modern">
      <label>
        <?= $user ? 'Mật khẩu mới' : 'Mật khẩu' ?> <?= $user ? '' : '<span class="required">*</span>' ?>
      </label>
      <input type="password" name="matkhau" <?= $user ? '' : 'required' ?>>
      <span class="help-text">
        <?= $user ? 'Để trống nếu không muốn đổi mật khẩu' : 'Mật khẩu sẽ được mã hóa an toàn' ?>
      </span>
    </div>
  </div>

  <!-- Form Actions -->
  <div class="form-actions">
    <a href="<?= BASE_URL ?>?act=admin-users" class="btn-cancel">
      <i class="fas fa-times"></i>
      Hủy
    </a>
    <button type="submit" class="btn-submit">
      <i class="fas fa-save"></i>
      <?= $user ? 'Cập nhật' : 'Tạo' ?> Người Dùng
    </button>
  </div>
</form>

