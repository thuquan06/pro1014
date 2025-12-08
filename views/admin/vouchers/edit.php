<?php
function safe_html($v){return htmlentities($v??'',ENT_QUOTES,'UTF-8');}
$old = $oldData ?? $voucher ?? [];
?>
<div class="page-header">
  <h2>Sửa voucher</h2>
  <a class="btn btn-secondary" href="<?= BASE_URL ?>?act=admin-vouchers"><i class="fas fa-arrow-left"></i> Quay lại</a>
</div>

<?php if (!empty($error)): ?>
  <div class="alert alert-danger"><?= safe_html($error) ?></div>
<?php endif; ?>

<form method="post" class="form-container">
  <div class="form-card">
    <div class="card-header"><h3>Thông tin voucher</h3></div>
    <div class="form-row">
      <div class="form-group-modern">
        <label>Mã voucher <span class="required">*</span></label>
        <input type="text" name="code" value="<?= safe_html($old['code'] ?? '') ?>" required>
      </div>
      <div class="form-group-modern">
        <label>Loại giảm</label>
        <select name="discount_type">
          <option value="percent" <?= (($old['discount_type'] ?? '')==='amount')?'':'selected'; ?>>Giảm %</option>
          <option value="amount" <?= (($old['discount_type'] ?? '')==='amount')?'selected':''; ?>>Giảm số tiền</option>
        </select>
      </div>
      <div class="form-group-modern">
        <label>Giá trị giảm</label>
        <input type="number" name="discount_value" min="0" step="0.01" value="<?= safe_html($old['discount_value'] ?? '0') ?>" required>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group-modern">
        <label>Ngày bắt đầu</label>
        <input type="date" name="start_date" value="<?= safe_html($old['start_date'] ?? '') ?>">
      </div>
      <div class="form-group-modern">
        <label>Ngày kết thúc</label>
        <input type="date" name="end_date" value="<?= safe_html($old['end_date'] ?? '') ?>">
      </div>
      <div class="form-group-modern">
        <label>Giới hạn lượt dùng</label>
        <input type="number" name="usage_limit" min="0" step="1" placeholder="Để trống = không giới hạn" value="<?= safe_html($old['usage_limit'] ?? '') ?>">
      </div>
    </div>

    <div class="form-row">
      <div class="form-group-modern">
        <label>Trạng thái</label>
        <select name="is_active">
          <option value="1" <?= (($old['is_active'] ?? '1')=='1')?'selected':''; ?>>Hoạt động</option>
          <option value="0" <?= (($old['is_active'] ?? '1')=='0')?'selected':''; ?>>Không hoạt động</option>
        </select>
      </div>
    </div>
  </div>

  <div class="form-actions">
    <a href="<?= BASE_URL ?>?act=admin-vouchers" class="btn-cancel"><i class="fas fa-times"></i> Hủy</a>
    <button type="submit" class="btn-submit"><i class="fas fa-save"></i> Lưu</button>
  </div>
</form>

