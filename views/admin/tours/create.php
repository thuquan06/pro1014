<?php
/**
 * Tour Create Page - Modern Interface
 * Updated: 2025-11-25
 */
function safe_html($value) {
    return htmlentities($value ?? '', ENT_QUOTES, 'UTF-8');
}

// Khởi tạo biến
$oldData = $oldData ?? [];
$errors = $errors ?? [];

// Helper function để lấy giá trị từ oldData
function old($key, $default = '') {
    global $oldData;
    return isset($oldData[$key]) ? safe_html($oldData[$key]) : $default;
}

// Helper function để kiểm tra field có lỗi không
function hasError($field) {
    global $errors;
    return isset($errors[$field]);
}

// Helper function để lấy error message
function getError($field) {
    global $errors;
    return isset($errors[$field]) ? safe_html($errors[$field]) : '';
}
?>

<style>
.create-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
}

.create-title {
  font-size: 28px;
  font-weight: 700;
  color: var(--text-dark);
  margin: 0;
}

.create-actions {
  display: flex;
  gap: 12px;
}

.form-container {
  max-width: 1200px;
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
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 20px;
  margin-bottom: 20px;
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
  margin-left: 4px;
}

.form-group-modern input[type="text"],
.form-group-modern input[type="number"],
.form-group-modern input[type="date"],
.form-group-modern input[type="time"],
.form-group-modern input[type="file"],
.form-group-modern select {
  padding: 12px 16px;
  border: 1px solid var(--border);
  border-radius: 8px;
  font-size: 14px;
  transition: all 0.2s;
}

.form-group-modern input:focus,
.form-group-modern select:focus {
  outline: none;
  border-color: var(--primary);
  box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.form-group-modern input.error-field,
.form-group-modern select.error-field,
.form-group-modern textarea.error-field {
  border-color: #ef4444;
  box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
}

.form-group-modern .field-error {
  color: #ef4444;
  font-size: 12px;
  margin-top: 4px;
  display: flex;
  align-items: center;
  gap: 4px;
}

.radio-group {
  display: flex;
  gap: 24px;
  padding: 12px 0;
}

.radio-option {
  display: flex;
  align-items: center;
  gap: 8px;
  cursor: pointer;
}

.radio-option input[type="radio"] {
  width: 18px;
  height: 18px;
  cursor: pointer;
}

.radio-option label {
  font-size: 14px;
  color: var(--text-dark);
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

.btn-reset {
  padding: 12px 32px;
  background: white;
  color: var(--text-dark);
  border: 1px solid var(--border);
  border-radius: 8px;
  font-weight: 600;
  font-size: 14px;
  cursor: pointer;
  transition: all 0.2s;
  display: inline-flex;
  align-items: center;
  gap: 8px;
}

.btn-reset:hover {
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
  .btn-reset {
    width: 100%;
    justify-content: center;
  }
}

/* Discounted Price Display - Inline */
.price-input-wrapper {
  display: flex;
  align-items: center;
  gap: 8px;
}

.price-input-wrapper input {
  flex: 1;
}

.price-preview {
  display: none;
  padding: 4px 10px;
  background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
  border-radius: 6px;
  white-space: nowrap;
  animation: slideIn 0.3s ease;
  min-width: 160px;
}

.price-preview.active {
  display: flex;
  align-items: center;
  gap: 6px;
}

.price-preview-text {
  display: flex;
  flex-direction: column;
  gap: 1px;
}

.price-original {
  text-decoration: line-through;
  color: rgba(255, 255, 255, 0.7);
  font-size: 11px;
}

.price-discounted {
  color: white;
  font-size: 14px;
  font-weight: 700;
}

.discount-badge {
  background: rgba(255, 255, 255, 0.3);
  color: white;
  padding: 2px 6px;
  border-radius: 8px;
  font-size: 10px;
  font-weight: 600;
}

@keyframes slideIn {
  from {
    opacity: 0;
    transform: translateX(-10px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}
</style>

<!-- Page Header -->
<div class="create-header">
  <div>
    <h1 class="create-title">
      <i class="fas fa-plus-circle" style="color: var(--primary);"></i>
      Tạo Tour mới
    </h1>
    <div class="breadcrumb" style="margin-top: 8px;">
      <a href="<?=BASE_URL?>?act=admin" style="color: var(--text-light); text-decoration: none;">Trang chủ</a>
      <i class="fa fa-angle-right" style="margin: 0 8px; color: var(--text-light);"></i>
      <a href="<?=BASE_URL?>?act=admin-tours" style="color: var(--text-light); text-decoration: none;">Danh sách tour</a>
      <i class="fa fa-angle-right" style="margin: 0 8px; color: var(--text-light);"></i>
      <span style="color: var(--text-dark);">Tạo mới</span>
    </div>
  </div>
</div>

<?php if(isset($error)&&$error): ?>
<div class="alert alert-error" style="margin-bottom: 20px;">
  <i class="fas fa-exclamation-circle"></i>
  <strong>LỖI:</strong> <?= safe_html($error) ?>
</div>
<?php endif; ?>

<?php if(isset($msg)&&$msg): ?>
<div class="alert alert-success" style="margin-bottom: 20px;">
  <i class="fas fa-check-circle"></i>
  <strong>THÀNH CÔNG:</strong> <?= safe_html($msg) ?>
</div>
<?php endif; ?>

<form class="form-container" method="post" action="<?=BASE_URL?>?act=admin-tour-store" enctype="multipart/form-data" onsubmit="updateCKEditorBeforeSubmit()">

  <!-- Card 1: Cấu hình Tour -->
  <div class="form-card">
    <div class="card-header">
      <i class="fas fa-cog"></i>
      <h3>Cấu hình Tour</h3>
    </div>
    
    <div class="form-group-modern">
      <label>Loại tour</label>
      <div class="radio-group">
        <div class="radio-option">
          <input type="radio" value="0" name="nuocngoai" id="tour_trongnuoc" <?= (!isset($oldData['nuocngoai']) || $oldData['nuocngoai'] == '0' || $oldData['nuocngoai'] == 0) ? 'checked' : '' ?>>
          <label for="tour_trongnuoc">🇻🇳 Trong nước</label>
        </div>
        <div class="radio-option">
          <input type="radio" value="1" name="nuocngoai" id="tour_quocte" <?= (isset($oldData['nuocngoai']) && ($oldData['nuocngoai'] == '1' || $oldData['nuocngoai'] == 1)) ? 'checked' : '' ?>>
          <label for="tour_quocte">🌍 Quốc tế</label>
        </div>
      </div>
    </div>
    
    <div class="form-row">
      <div class="form-group-modern" id="field_quocgia" style="display:none">
        <label for="quocgia">Quốc gia <span class="required">*</span></label>
        <input type="text" 
               name="quocgia" 
               id="quocgia" 
               value="<?= old('quocgia', 'Việt Nam') ?>"
               class="<?= hasError('quocgia') ? 'error-field' : '' ?>"
               placeholder="Ví dụ: Thái Lan, Singapore...">
        <?php if (hasError('quocgia')): ?>
          <span class="field-error"><i class="fas fa-exclamation-circle"></i> <?= getError('quocgia') ?></span>
        <?php endif; ?>
      </div>

    </div>
  </div>

  <!-- Card 2: Thông tin cơ bản -->
  <div class="form-card">
    <div class="card-header">
      <i class="fas fa-info-circle"></i>
      <h3>Thông tin cơ bản</h3>
    </div>

    <div class="form-group-modern">
      <label for="mato">Mã tour <span class="required">*</span></label>
      <input type="text" 
             name="mato" 
             id="mato" 
             value="<?= old('mato') ?>"
             class="<?= hasError('mato') ? 'error-field' : '' ?>"
             required 
             placeholder="Ví dụ: TOUR-HL-001">
      <?php if (hasError('mato')): ?>
        <span class="field-error"><i class="fas fa-exclamation-circle"></i> <?= getError('mato') ?></span>
      <?php endif; ?>
    </div>

    <div class="form-group-modern">
      <label for="tengoi">Tên tour <span class="required">*</span></label>
      <input type="text" 
             name="tengoi" 
             id="tengoi" 
             value="<?= old('tengoi') ?>"
             class="<?= hasError('tengoi') ? 'error-field' : '' ?>"
             required 
             placeholder="Ví dụ: Du lịch Hà Nội - Hạ Long 3 ngày 2 đêm">
      <?php if (hasError('tengoi')): ?>
        <span class="field-error"><i class="fas fa-exclamation-circle"></i> <?= getError('tengoi') ?></span>
      <?php endif; ?>
    </div>

    <div class="form-group-modern">
      <label for="noixuatphat">Điểm khởi hành <span class="required">*</span></label>
      <input type="text" 
             name="noixuatphat" 
             id="noixuatphat" 
             value="<?= old('noixuatphat') ?>"
             class="<?= hasError('noixuatphat') ? 'error-field' : '' ?>"
             required 
             placeholder="Ví dụ: TP. Hồ Chí Minh">
      <?php if (hasError('noixuatphat')): ?>
        <span class="field-error"><i class="fas fa-exclamation-circle"></i> <?= getError('noixuatphat') ?></span>
      <?php endif; ?>
    </div>

    <div class="form-row">
      <div class="form-group-modern">
        <label for="songay">Số ngày <span class="required">*</span></label>
        <input type="text" 
               name="songay" 
               id="songay" 
               value="<?= old('songay') ?>"
               class="<?= hasError('songay') ? 'error-field' : '' ?>"
               required 
               placeholder="Ví dụ: 3 ngày 2 đêm">
        <?php if (hasError('songay')): ?>
          <span class="field-error"><i class="fas fa-exclamation-circle"></i> <?= getError('songay') ?></span>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Card 3: Giá tour -->
  <div class="form-card">
    <div class="card-header">
      <i class="fas fa-money-bill-wave"></i>
      <h3>Giá tour</h3>
    </div>

    <div class="form-row">
      <div class="form-group-modern">
        <label for="giagoi">Giá người lớn (VNĐ) <span class="required">*</span></label>
        <div class="price-input-wrapper">
          <input type="text"
                 name="giagoi"
                 id="giagoi"
                 value="<?= old('giagoi') ?>"
                 class="<?= hasError('giagoi') ? 'error-field' : '' ?>"
                 required
                 placeholder="Ví dụ: 5000000">
          <div id="preview_giagoi" class="price-preview">
            <div class="price-preview-text">
              <div class="price-original" id="original_giagoi"></div>
              <div class="price-discounted" id="discounted_giagoi"></div>
            </div>
            <div class="discount-badge" id="badge_giagoi"></div>
          </div>
        </div>
        <?php if (hasError('giagoi')): ?>
          <span class="field-error"><i class="fas fa-exclamation-circle"></i> <?= getError('giagoi') ?></span>
        <?php endif; ?>
      </div>

      <div class="form-group-modern">
        <label for="giatreem">Giá trẻ em (VNĐ) <span class="required">*</span></label>
        <div class="price-input-wrapper">
          <input type="text"
                 name="giatreem"
                 id="giatreem"
                 value="<?= old('giatreem') ?>"
                 class="<?= hasError('giatreem') ? 'error-field' : '' ?>"
                 required
                 placeholder="Ví dụ: 3000000">
          <div id="preview_giatreem" class="price-preview">
            <div class="price-preview-text">
              <div class="price-original" id="original_giatreem"></div>
              <div class="price-discounted" id="discounted_giatreem"></div>
            </div>
            <div class="discount-badge" id="badge_giatreem"></div>
          </div>
        </div>
        <?php if (hasError('giatreem')): ?>
          <span class="field-error"><i class="fas fa-exclamation-circle"></i> <?= getError('giatreem') ?></span>
        <?php endif; ?>
      </div>

      <div class="form-group-modern">
        <label for="giatrenho">Giá trẻ nhỏ (VNĐ) <span class="required">*</span></label>
        <div class="price-input-wrapper">
          <input type="text"
                 name="giatrenho"
                 id="giatrenho"
                 value="<?= old('giatrenho') ?>"
                 class="<?= hasError('giatrenho') ? 'error-field' : '' ?>"
                 required
                 placeholder="Ví dụ: 1000000">
          <div id="preview_giatrenho" class="price-preview">
            <div class="price-preview-text">
              <div class="price-original" id="original_giatrenho"></div>
              <div class="price-discounted" id="discounted_giatrenho"></div>
            </div>
            <div class="discount-badge" id="badge_giatrenho"></div>
          </div>
        </div>
        <?php if (hasError('giatrenho')): ?>
          <span class="field-error"><i class="fas fa-exclamation-circle"></i> <?= getError('giatrenho') ?></span>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Card 4: Dịch vụ -->
  <div class="form-card">
    <div class="card-header">
      <i class="fas fa-concierge-bell"></i>
      <h3>Dịch vụ (Tùy chọn)</h3>
    </div>
    <div class="form-group-modern">
      <label>Chọn dịch vụ cho tour</label>
      <p style="font-size: 13px; color: var(--text-light); margin-bottom: 16px;">
        Chọn các dịch vụ sẽ được sử dụng trong tour này. Có thể chọn nhiều dịch vụ.
      </p>
      
      <?php if (!empty($serviceTypes)): ?>
        <?php foreach ($serviceTypes as $typeKey => $typeName): ?>
          <?php 
          $servicesByType = array_filter($services ?? [], function($s) use ($typeKey) {
            return ($s['loai_dich_vu'] ?? '') === $typeKey;
          });
          ?>
          <?php if (!empty($servicesByType)): ?>
            <div style="margin-bottom: 24px;">
              <h4 style="font-size: 14px; font-weight: 600; color: var(--text-dark); margin-bottom: 12px; padding-bottom: 8px; border-bottom: 1px solid var(--border);">
                <i class="fas fa-tag"></i> <?= htmlspecialchars($typeName) ?>
              </h4>
              <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 12px;">
                <?php foreach ($servicesByType as $service): ?>
                  <label style="display: flex; align-items: center; gap: 8px; padding: 12px; background: var(--bg-light); border-radius: 8px; cursor: pointer; transition: all 0.2s; border: 2px solid transparent;" 
                         onmouseover="this.style.borderColor='var(--primary)'; this.style.background='#f0f7ff';" 
                         onmouseout="this.style.borderColor='transparent'; this.style.background='var(--bg-light)';">
                    <input type="checkbox" 
                           name="dich_vu[]" 
                           value="<?= $service['id'] ?>" 
                           style="width: 18px; height: 18px; cursor: pointer;"
                           <?= (isset($oldData['dich_vu']) && in_array($service['id'], $oldData['dich_vu'])) ? 'checked' : '' ?>>
                    <div style="flex: 1;">
                      <div style="font-weight: 600; font-size: 14px; color: var(--text-dark);">
                        <?= htmlspecialchars($service['ten_dich_vu'] ?? '') ?>
                      </div>
                      <?php if (!empty($service['nha_cung_cap'])): ?>
                        <div style="font-size: 12px; color: var(--text-light); margin-top: 2px;">
                          <i class="fas fa-building"></i> <?= htmlspecialchars($service['nha_cung_cap']) ?>
                        </div>
                      <?php endif; ?>
                      <?php if (!empty($service['gia'])): ?>
                        <div style="font-size: 12px; color: var(--primary); font-weight: 600; margin-top: 2px;">
                          <?= number_format($service['gia'], 0, ',', '.') ?> đ
                          <?php if (!empty($service['don_vi'])): ?>
                            / <?= htmlspecialchars($service['don_vi']) ?>
                          <?php endif; ?>
                        </div>
                      <?php endif; ?>
                    </div>
                  </label>
                <?php endforeach; ?>
              </div>
            </div>
          <?php endif; ?>
        <?php endforeach; ?>
      <?php else: ?>
        <div style="padding: 20px; text-align: center; color: var(--text-light); background: var(--bg-light); border-radius: 8px;">
          <i class="fas fa-info-circle" style="font-size: 24px; margin-bottom: 8px;"></i>
          <p>Chưa có dịch vụ nào. <a href="<?= BASE_URL ?>?act=admin-service-create" style="color: var(--primary);">Tạo dịch vụ mới</a></p>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Card 5: Nội dung tour -->
  <div class="form-card">
    <div class="card-header">
      <i class="fas fa-file-alt"></i>
      <h3>Nội dung tour</h3>
    </div>



    <div class="form-group-modern">
      <label for="packagedetails2">Lưu ý <span class="required">*</span></label>
      <textarea class="form-control <?= hasError('luuy') ? 'error-field' : '' ?>" 
                name="luuy" 
                id="packagedetails2" 
                required><?= old('luuy') ?></textarea>
      <?php if (hasError('luuy')): ?>
        <span class="field-error"><i class="fas fa-exclamation-circle"></i> <?= getError('luuy') ?></span>
      <?php endif; ?>
    </div>
  </div>

  <!-- Card 5: Phân loại & Tags -->
  <div class="form-card">
    <div class="card-header">
      <i class="fas fa-tags"></i>
      <h3>Phân loại & Tags</h3>
    </div>

    <div class="form-row">
      <div class="form-group-modern">
        <label>Loại tour</label>
        <div style="max-height: 200px; overflow-y: auto; border: 1px solid var(--border); border-radius: 8px; padding: 12px; background: var(--bg-light);">
          <?php if (!empty($categories)): ?>
            <?php foreach ($categories as $cat): ?>
              <label style="display: block; padding: 8px; margin: 4px 0; background: white; border-radius: 6px; cursor: pointer; transition: all 0.2s;">
                <input type="checkbox" name="loai_ids[]" value="<?= $cat['id'] ?>" style="margin-right: 8px;">
                <i class="fas fa-folder"></i> <?= htmlspecialchars($cat['ten_loai']) ?>
              </label>
            <?php endforeach; ?>
          <?php else: ?>
            <p style="color: var(--text-light); text-align: center; padding: 20px;">Chưa có loại tour nào</p>
          <?php endif; ?>
        </div>
      </div>

      <div class="form-group-modern">
        <label>Tags</label>
        <div style="max-height: 200px; overflow-y: auto; border: 1px solid var(--border); border-radius: 8px; padding: 12px; background: var(--bg-light);">
          <?php if (!empty($tags)): ?>
            <?php foreach ($tags as $tag): ?>
              <label style="display: block; padding: 8px; margin: 4px 0; background: white; border-radius: 6px; cursor: pointer; transition: all 0.2s;">
                <input type="checkbox" name="tag_ids[]" value="<?= $tag['id'] ?>" style="margin-right: 8px;">
                <i class="fas fa-hashtag"></i><?= htmlspecialchars($tag['ten_tag']) ?>
              </label>
            <?php endforeach; ?>
          <?php else: ?>
            <p style="color: var(--text-light); text-align: center; padding: 20px;">Chưa có tag nào</p>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <!-- Card 6: Hình ảnh -->
  <div class="form-card">
    <div class="card-header">
      <i class="fas fa-image"></i>
      <h3>Hình ảnh đại diện</h3>
    </div>

    <div class="form-group-modern">
      <label for="packageimage">Chọn ảnh <span class="required">*</span></label>
      <input type="file" 
             name="packageimage" 
             id="packageimage" 
             class="<?= hasError('packageimage') ? 'error-field' : '' ?>"
             required>
      <?php if (hasError('packageimage')): ?>
        <span class="field-error"><i class="fas fa-exclamation-circle"></i> <?= getError('packageimage') ?></span>
      <?php else: ?>
        <small style="color: var(--text-light); font-size: 12px; margin-top: 4px; display: block;">
          <i class="fas fa-info-circle"></i> Hỗ trợ: JPG, PNG, GIF (Tối đa 5MB)
        </small>
      <?php endif; ?>
    </div>
  </div>

  <!-- Form Actions -->
  <div class="form-card">
    <div class="form-actions">
      <button type="reset" class="btn-reset">
        <i class="fas fa-redo"></i>
        Làm mới
      </button>
      <button type="submit" name="submit" class="btn-submit">
        <i class="fas fa-plus-circle"></i>
        Tạo Tour
      </button>
    </div>
  </div>

</form>

<script src="assets/ckeditor/ckeditor.js"></script>

<script>
// Initialize CKEditor
const ckConfig = {
    filebrowserBrowseUrl: 'assets/ckfinder/ckfinder.html',
    filebrowserImageBrowseUrl: 'assets/ckfinder/ckfinder.html?type=Images',
    filebrowserUploadUrl: 'assets/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
    filebrowserImageUploadUrl: 'assets/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
    height: 350
};


// Khởi tạo CKEditor cho "Lưu ý"
CKEDITOR.replace('packagedetails2', ckConfig);

// Xử lý khi submit form
function updateCKEditorBeforeSubmit() {
    // Cập nhật tất cả editor instances
    for (var instance in CKEDITOR.instances) {
        CKEDITOR.instances[instance].updateElement();
    }
    
    return true;
}

// Toggle between domestic and international tour fields
document.addEventListener('DOMContentLoaded', function() {
    var radioTrongNuoc = document.getElementById('tour_trongnuoc');
    var radioQuocTe    = document.getElementById('tour_quocte');
    var fieldQuocGia   = document.getElementById('field_quocgia');
    var inputQuocGia   = document.getElementById('quocgia');

    function toggleFields() {
        if (radioQuocTe.checked) {
            fieldQuocGia.style.display = 'block';
            // Không xóa giá trị nếu đã có
            if (!inputQuocGia.value) {
                inputQuocGia.value = '';
            }
        } else {
            fieldQuocGia.style.display = 'none';
            // Không xóa giá trị nếu đã có
            if (!inputQuocGia.value) {
                inputQuocGia.value = 'Việt Nam';
            }
        }
    }

    toggleFields();
    radioTrongNuoc.addEventListener('change', toggleFields);
    radioQuocTe.addEventListener('change', toggleFields);

    // Khuyến mãi đã được thay thế bằng voucher, không cần toggle thêm

    // Scroll đến trường bị lỗi đầu tiên nếu có
    <?php if (!empty($errors)): ?>
    var firstError = document.querySelector('.error-field');
    if (firstError) {
        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
        firstError.focus();
    }
    <?php endif; ?>
});
</script>
