<?php
/**
 * Tour Create Page - Modern Interface
 * Updated: 2025-11-25
 */
function safe_html($value) {
    return htmlentities($value ?? '', ENT_QUOTES, 'UTF-8');
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

<form class="form-container" method="post" action="<?=BASE_URL?>?act=admin-tour-store" enctype="multipart/form-data">

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
          <input type="radio" value="0" name="nuocngoai" id="tour_trongnuoc" checked>
          <label for="tour_trongnuoc">🇻🇳 Trong nước</label>
        </div>
        <div class="radio-option">
          <input type="radio" value="1" name="nuocngoai" id="tour_quocte">
          <label for="tour_quocte">🌍 Quốc tế</label>
        </div>
      </div>
    </div>
    
    <div class="form-group-modern">
      <label>Khuyến mãi</label>
      <div class="radio-group">
        <div class="radio-option">
          <input type="radio" value="1" name="khuyenmai" id="km_co">
          <label for="km_co">✅ Có khuyến mãi</label>
        </div>
        <div class="radio-option">
          <input type="radio" value="0" name="khuyenmai" id="km_khong" checked>
          <label for="km_khong">❌ Không</label>
        </div>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group-modern" id="field_quocgia" style="display:none">
        <label for="quocgia">Quốc gia <span class="required">*</span></label>
        <input type="text" name="quocgia" id="quocgia" placeholder="Ví dụ: Thái Lan, Singapore...">
      </div>

      <div class="form-group-modern" id="field_tinh">
        <label for="ten_tinh">Tỉnh/Thành phố <span class="required">*</span></label>
        <select name="ten_tinh" id="ten_tinh">
          <option value="">-- Chọn tỉnh --</option>
          <?php if(!empty($provinces)) foreach($provinces as $p): 
              $pn = safe_html($p['ten_tinh']);
          ?>
              <option value="<?=$pn?>"><?=$pn?></option>
          <?php endforeach; ?>
        </select>
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
      <label for="tengoi">Tên tour <span class="required">*</span></label>
      <input type="text" name="tengoi" id="tengoi" required placeholder="Ví dụ: Du lịch Hà Nội - Hạ Long 3 ngày 2 đêm">
    </div>

    <div class="form-row">
      <div class="form-group-modern">
        <label for="noixuatphat">Điểm khởi hành <span class="required">*</span></label>
        <input type="text" name="noixuatphat" id="noixuatphat" required placeholder="Ví dụ: TP. Hồ Chí Minh">
      </div>

      <div class="form-group-modern">
        <label for="vitri">Điểm đến <span class="required">*</span></label>
        <input type="text" name="vitri" id="vitri" required placeholder="Ví dụ: Vịnh Hạ Long">
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
        <input type="text" name="giagoi" id="giagoi" required placeholder="Ví dụ: 5000000">
      </div>

      <div class="form-group-modern">
        <label for="giatreem">Giá trẻ em (VNĐ) <span class="required">*</span></label>
        <input type="text" name="giatreem" id="giatreem" required placeholder="Ví dụ: 3000000">
      </div>

      <div class="form-group-modern">
        <label for="giatrenho">Giá trẻ nhỏ (VNĐ) <span class="required">*</span></label>
        <input type="text" name="giatrenho" id="giatrenho" required placeholder="Ví dụ: 1000000">
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
      <textarea class="form-control" name="chitietgoi" id="packagedetails" required></textarea>
    </div>

    <div class="form-group-modern" style="margin-bottom: 24px;">
      <label for="packagedetails1">Chương trình tour <span class="required">*</span></label>
      <textarea class="form-control" name="chuongtrinh" id="packagedetails1" required></textarea>
    </div>

    <div class="form-group-modern">
      <label for="packagedetails2">Lưu ý <span class="required">*</span></label>
      <textarea class="form-control" name="luuy" id="packagedetails2" required></textarea>
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
        <input type="number" name="songay" id="songay" required placeholder="Ví dụ: 3">
      </div>

      <div class="form-group-modern">
        <label for="phuongtien">Phương tiện <span class="required">*</span></label>
        <input type="text" name="phuongtien" id="phuongtien" required placeholder="Ví dụ: Xe khách, Máy bay">
      </div>
    </div>

    <div class="form-row">
      <div class="form-group-modern">
        <label for="ngayxuatphat">Ngày xuất phát <span class="required">*</span></label>
        <input type="date" name="ngayxuatphat" id="ngayxuatphat" required>
      </div>

      <div class="form-group-modern">
        <label for="giodi">Giờ xuất phát <span class="required">*</span></label>
        <input type="time" name="giodi" id="giodi" required>
      </div>

      <div class="form-group-modern">
        <label for="ngayve">Ngày về <span class="required">*</span></label>
        <input type="date" name="ngayve" id="ngayve" required>
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
      <input type="file" name="packageimage" id="packageimage" required>
      <small style="color: var(--text-light); font-size: 12px; margin-top: 4px; display: block;">
        <i class="fas fa-info-circle"></i> Hỗ trợ: JPG, PNG, GIF (Tối đa 5MB)
      </small>
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

CKEDITOR.replace('packagedetails', ckConfig);
CKEDITOR.replace('packagedetails1', ckConfig);
CKEDITOR.replace('packagedetails2', ckConfig);

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
            selectTinh.value = '';
            inputQuocGia.value = '';
        } else {
            fieldTinh.style.display = 'block';
            fieldQuocGia.style.display = 'none';
            selectTinh.value = '';
            inputQuocGia.value = 'Việt Nam';
        }
    }

    toggleFields();
    radioTrongNuoc.addEventListener('change', toggleFields);
    radioQuocTe.addEventListener('change', toggleFields);
});
</script>
