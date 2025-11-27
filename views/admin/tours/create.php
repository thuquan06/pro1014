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
    
    <div class="form-group-modern">
      <label>Khuyến mãi</label>
      <div class="radio-group">
        <div class="radio-option">
          <input type="radio" value="1" name="khuyenmai" id="km_co" <?= (isset($oldData['khuyenmai']) && ($oldData['khuyenmai'] == '1' || $oldData['khuyenmai'] == 1)) ? 'checked' : '' ?>>
          <label for="km_co">✅ Có khuyến mãi</label>
        </div>
        <div class="radio-option">
          <input type="radio" value="0" name="khuyenmai" id="km_khong" <?= (!isset($oldData['khuyenmai']) || $oldData['khuyenmai'] == '0' || $oldData['khuyenmai'] == 0) ? 'checked' : '' ?>>
          <label for="km_khong">❌ Không</label>
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

      <div class="form-group-modern" id="field_tinh">
        <label for="ten_tinh">Tỉnh/Thành phố <span class="required">*</span></label>
        <select name="ten_tinh" 
                id="ten_tinh"
                class="<?= hasError('ten_tinh') ? 'error-field' : '' ?>">
          <option value="">-- Chọn tỉnh --</option>
          <?php if(!empty($provinces)) foreach($provinces as $p): 
              $pn = safe_html($p['ten_tinh']);
              $selected = old('ten_tinh') == $pn ? 'selected' : '';
          ?>
              <option value="<?=$pn?>" <?= $selected ?>><?=$pn?></option>
          <?php endforeach; ?>
        </select>
        <?php if (hasError('ten_tinh')): ?>
          <span class="field-error"><i class="fas fa-exclamation-circle"></i> <?= getError('ten_tinh') ?></span>
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

    <div class="form-row">
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

      <div class="form-group-modern">
        <label for="vitri">Điểm đến <span class="required">*</span></label>
        <input type="text" 
               name="vitri" 
               id="vitri" 
               value="<?= old('vitri') ?>"
               class="<?= hasError('vitri') ? 'error-field' : '' ?>"
               required 
               placeholder="Ví dụ: Vịnh Hạ Long">
        <?php if (hasError('vitri')): ?>
          <span class="field-error"><i class="fas fa-exclamation-circle"></i> <?= getError('vitri') ?></span>
        <?php endif; ?>
      </div>
    </div>

    <div class="form-group-modern">
      <label for="tuyendiem">Tuyến điểm <span class="required">*</span></label>
      <input type="text" 
             name="tuyendiem" 
             id="tuyendiem" 
             value="<?= old('tuyendiem') ?>"
             class="<?= hasError('tuyendiem') ? 'error-field' : '' ?>"
             required 
             placeholder="Ví dụ: Hà Nội - Hạ Long - Cát Bà">
      <?php if (hasError('tuyendiem')): ?>
        <span class="field-error"><i class="fas fa-exclamation-circle"></i> <?= getError('tuyendiem') ?></span>
      <?php endif; ?>
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
        <input type="text" 
               name="giagoi" 
               id="giagoi" 
               value="<?= old('giagoi') ?>"
               class="<?= hasError('giagoi') ? 'error-field' : '' ?>"
               required 
               placeholder="Ví dụ: 5000000">
        <?php if (hasError('giagoi')): ?>
          <span class="field-error"><i class="fas fa-exclamation-circle"></i> <?= getError('giagoi') ?></span>
        <?php endif; ?>
      </div>

      <div class="form-group-modern">
        <label for="giatreem">Giá trẻ em (VNĐ) <span class="required">*</span></label>
        <input type="text" 
               name="giatreem" 
               id="giatreem" 
               value="<?= old('giatreem') ?>"
               class="<?= hasError('giatreem') ? 'error-field' : '' ?>"
               required 
               placeholder="Ví dụ: 3000000">
        <?php if (hasError('giatreem')): ?>
          <span class="field-error"><i class="fas fa-exclamation-circle"></i> <?= getError('giatreem') ?></span>
        <?php endif; ?>
      </div>

      <div class="form-group-modern">
        <label for="giatrenho">Giá trẻ nhỏ (VNĐ) <span class="required">*</span></label>
        <input type="text" 
               name="giatrenho" 
               id="giatrenho" 
               value="<?= old('giatrenho') ?>"
               class="<?= hasError('giatrenho') ? 'error-field' : '' ?>"
               required 
               placeholder="Ví dụ: 1000000">
        <?php if (hasError('giatrenho')): ?>
          <span class="field-error"><i class="fas fa-exclamation-circle"></i> <?= getError('giatrenho') ?></span>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Card 4: Nội dung tour -->
  <div class="form-card">
    <div class="card-header">
      <i class="fas fa-file-alt"></i>
      <h3>Nội dung tour</h3>
    </div>

    <div class="form-group-modern" style="margin-bottom: 24px;">
      <label for="packagedetails">Chi tiết tour <span class="required">*</span></label>
      <textarea class="form-control <?= hasError('chitietgoi') ? 'error-field' : '' ?>" 
                name="chitietgoi" 
                id="packagedetails" 
                required><?= old('chitietgoi') ?></textarea>
      <?php if (hasError('chitietgoi')): ?>
        <span class="field-error"><i class="fas fa-exclamation-circle"></i> <?= getError('chitietgoi') ?></span>
      <?php endif; ?>
    </div>

    <div class="form-group-modern" style="margin-bottom: 24px;">
      <label for="packagedetails1">Chương trình tour <span class="required">*</span></label>
      <textarea class="form-control <?= hasError('chuongtrinh') ? 'error-field' : '' ?>" 
                name="chuongtrinh" 
                id="packagedetails1" 
                required><?= old('chuongtrinh') ?></textarea>
      <?php if (hasError('chuongtrinh')): ?>
        <span class="field-error"><i class="fas fa-exclamation-circle"></i> <?= getError('chuongtrinh') ?></span>
      <?php endif; ?>
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

  <!-- Card 5: Thời gian & Lịch trình -->
  <div class="form-card">
    <div class="card-header">
      <i class="fas fa-calendar-alt"></i>
      <h3>Thời gian & Lịch trình</h3>
    </div>

    <div class="form-row">
      <div class="form-group-modern">
        <label for="songay">Số ngày <span class="required">*</span></label>
        <input type="number" 
               name="songay" 
               id="songay" 
               value="<?= old('songay') ?>"
               class="<?= hasError('songay') ? 'error-field' : '' ?>"
               required 
               placeholder="Ví dụ: 3">
        <?php if (hasError('songay')): ?>
          <span class="field-error"><i class="fas fa-exclamation-circle"></i> <?= getError('songay') ?></span>
        <?php endif; ?>
      </div>

      <div class="form-group-modern">
        <label for="phuongtien">Phương tiện <span class="required">*</span></label>
        <input type="text" 
               name="phuongtien" 
               id="phuongtien" 
               value="<?= old('phuongtien') ?>"
               class="<?= hasError('phuongtien') ? 'error-field' : '' ?>"
               required 
               placeholder="Ví dụ: Xe khách, Máy bay">
        <?php if (hasError('phuongtien')): ?>
          <span class="field-error"><i class="fas fa-exclamation-circle"></i> <?= getError('phuongtien') ?></span>
        <?php endif; ?>
      </div>

      <div class="form-group-modern">
        <label for="socho">Số chỗ <span class="required">*</span></label>
        <input type="number" 
               name="socho" 
               id="socho" 
               value="<?= old('socho') ?>"
               class="<?= hasError('socho') ? 'error-field' : '' ?>"
               required 
               min="1"
               placeholder="Ví dụ: 30">
        <?php if (hasError('socho')): ?>
          <span class="field-error"><i class="fas fa-exclamation-circle"></i> <?= getError('socho') ?></span>
        <?php endif; ?>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group-modern">
        <label for="ngayxuatphat">Ngày xuất phát <span class="required">*</span></label>
        <input type="date" 
               name="ngayxuatphat" 
               id="ngayxuatphat" 
               value="<?= old('ngayxuatphat') ?>"
               class="<?= hasError('ngayxuatphat') ? 'error-field' : '' ?>"
               required>
        <?php if (hasError('ngayxuatphat')): ?>
          <span class="field-error"><i class="fas fa-exclamation-circle"></i> <?= getError('ngayxuatphat') ?></span>
        <?php endif; ?>
      </div>

      <div class="form-group-modern">
        <label for="giodi">Giờ xuất phát <span class="required">*</span></label>
        <input type="time" 
               name="giodi" 
               id="giodi" 
               value="<?= old('giodi') ?>"
               class="<?= hasError('giodi') ? 'error-field' : '' ?>"
               required>
        <?php if (hasError('giodi')): ?>
          <span class="field-error"><i class="fas fa-exclamation-circle"></i> <?= getError('giodi') ?></span>
        <?php endif; ?>
      </div>

      <div class="form-group-modern">
        <label for="ngayve">Ngày về <span class="required">*</span></label>
        <input type="date" 
               name="ngayve" 
               id="ngayve" 
               value="<?= old('ngayve') ?>"
               class="<?= hasError('ngayve') ? 'error-field' : '' ?>"
               required>
        <?php if (hasError('ngayve')): ?>
          <span class="field-error"><i class="fas fa-exclamation-circle"></i> <?= getError('ngayve') ?></span>
        <?php endif; ?>
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

// Khởi tạo CKEditor và giữ lại giá trị nếu có lỗi
var editor1 = null;
var editor2 = null;
var editor3 = null;

// Khởi tạo CKEditor
CKEDITOR.on('instanceReady', function(ev) {
    var editor = ev.editor;
    
    if (editor.name === 'packagedetails') {
        editor1 = editor;
    } else if (editor.name === 'packagedetails1') {
        editor2 = editor;
    } else if (editor.name === 'packagedetails2') {
        editor3 = editor;
    }
    
    // Giữ lại giá trị từ oldData nếu có lỗi
    <?php if (!empty($oldData)): ?>
    setTimeout(function() {
        <?php if (!empty($oldData['chitietgoi'])): ?>
        if (editor.name === 'packagedetails') {
            editor.setData(<?= json_encode($oldData['chitietgoi'], JSON_HEX_QUOT | JSON_HEX_APOS | JSON_UNESCAPED_UNICODE) ?>);
        }
        <?php endif; ?>
        
        <?php if (!empty($oldData['chuongtrinh'])): ?>
        if (editor.name === 'packagedetails1') {
            editor.setData(<?= json_encode($oldData['chuongtrinh'], JSON_HEX_QUOT | JSON_HEX_APOS | JSON_UNESCAPED_UNICODE) ?>);
        }
        <?php endif; ?>
        
        <?php if (!empty($oldData['luuy'])): ?>
        if (editor.name === 'packagedetails2') {
            editor.setData(<?= json_encode($oldData['luuy'], JSON_HEX_QUOT | JSON_HEX_APOS | JSON_UNESCAPED_UNICODE) ?>);
        }
        <?php endif; ?>
    }, 100);
    <?php endif; ?>
});

// Khởi tạo CKEditor sau khi instance ready handler đã được đăng ký
CKEDITOR.replace('packagedetails', ckConfig);
CKEDITOR.replace('packagedetails1', ckConfig);
CKEDITOR.replace('packagedetails2', ckConfig);

// Cập nhật textarea trước khi submit form để đảm bảo dữ liệu không bị mất
function updateCKEditorBeforeSubmit() {
    // Cập nhật tất cả editor instances
    for (var instance in CKEDITOR.instances) {
        CKEDITOR.instances[instance].updateElement();
    }
    return true; // Cho phép form submit
}

// Toggle between domestic and international tour fields
document.addEventListener('DOMContentLoaded', function() {
    var radioTrongNuoc = document.getElementById('tour_trongnuoc');
    var radioQuocTe    = document.getElementById('tour_quocte');
    var fieldTinh      = document.getElementById('field_tinh');
    var fieldQuocGia   = document.getElementById('field_quocgia');
    var inputQuocGia   = document.getElementById('quocgia');
    var selectTinh     = document.getElementById('ten_tinh');

    function toggleFields() {
        if (radioQuocTe.checked) {
            fieldTinh.style.display = 'none';
            fieldQuocGia.style.display = 'block';
            // Không xóa giá trị nếu đã có
            if (!inputQuocGia.value) {
                inputQuocGia.value = '';
            }
        } else {
            fieldTinh.style.display = 'block';
            fieldQuocGia.style.display = 'none';
            // Không xóa giá trị nếu đã có
            if (!selectTinh.value) {
                selectTinh.value = '';
            }
            if (!inputQuocGia.value) {
                inputQuocGia.value = 'Việt Nam';
            }
        }
    }

    toggleFields();
    radioTrongNuoc.addEventListener('change', toggleFields);
    radioQuocTe.addEventListener('change', toggleFields);
    
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
