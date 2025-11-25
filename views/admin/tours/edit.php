<?php
/**
 * Tour Edit Page - Modern Interface
 * Updated: 2025-11-25
 */
function safe_value($value, $default = '') {
    if ($value === null || $value === '') return $default;
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

$id_goi=safe_value($tour['id_goi']??'');
$nuocngoai=isset($tour['nuocngoai'])?(int)$tour['nuocngoai']:0;
$khuyenmai=isset($tour['khuyenmai'])?(int)$tour['khuyenmai']:0;
$quocgia=safe_value($tour['quocgia']??'Việt Nam');
$ten_tinh=safe_value($tour['ten_tinh']??'');
$tengoi=safe_value($tour['tengoi']??'');
$noixuatphat=safe_value($tour['noixuatphat']??'');
$vitri=safe_value($tour['vitri']??'');
$giagoi=safe_value($tour['giagoi']??'');
$giatreem=safe_value($tour['giatreem']??'');
$giatrenho=safe_value($tour['giatrenho']??'');
$chitietgoi=safe_value($tour['chitietgoi']??'');
$chuongtrinh=safe_value($tour['chuongtrinh']??'');
$luuy=safe_value($tour['luuy']??'');
$songay=safe_value($tour['songay']??'');
$giodi=safe_value($tour['giodi']??'');
$ngayxuatphat=safe_value($tour['ngayxuatphat']??'');
$ngayve=safe_value($tour['ngayve']??'');
$phuongtien=safe_value($tour['phuongtien']??'');
$hinhanh=safe_value($tour['hinhanh']??'');
$ngaycapnhat=safe_value($tour['ngaycapnhat']??'');
$ngaydang=safe_value($tour['ngaydang']??date('Y-m-d'));
?>

<style>
.edit-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
}

.edit-title {
  font-size: 28px;
  font-weight: 700;
  color: var(--text-dark);
  margin: 0;
}

.edit-actions {
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

.image-preview {
  display: flex;
  align-items: center;
  gap: 16px;
  padding: 16px;
  background: var(--bg-light);
  border-radius: 8px;
}

.image-preview img {
  width: 150px;
  height: 150px;
  object-fit: cover;
  border-radius: 8px;
  border: 2px solid var(--border);
}

.image-preview-info {
  flex: 1;
}

.change-image-btn {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 10px 16px;
  background: white;
  border: 1px solid var(--border);
  border-radius: 8px;
  color: var(--text-dark);
  text-decoration: none;
  font-weight: 600;
  font-size: 14px;
  transition: all 0.2s;
}

.change-image-btn:hover {
  background: var(--primary);
  color: white;
  border-color: var(--primary);
}

.info-box {
  padding: 12px 16px;
  background: var(--bg-light);
  border-radius: 8px;
  font-size: 14px;
  color: var(--text-light);
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
<div class="edit-header">
  <div>
    <h1 class="edit-title">
      <i class="fas fa-edit" style="color: var(--primary);"></i>
      Chỉnh sửa Tour
    </h1>
    <div class="breadcrumb" style="margin-top: 8px;">
      <a href="<?=BASE_URL?>?act=admin" style="color: var(--text-light); text-decoration: none;">Trang chủ</a>
      <i class="fa fa-angle-right" style="margin: 0 8px; color: var(--text-light);"></i>
      <a href="<?=BASE_URL?>?act=admin-tours" style="color: var(--text-light); text-decoration: none;">Danh sách tour</a>
      <i class="fa fa-angle-right" style="margin: 0 8px; color: var(--text-light);"></i>
      <span style="color: var(--text-dark);">Chỉnh sửa</span>
    </div>
  </div>
</div>

<?php if(isset($error)&&$error): ?>
<div class="alert alert-error" style="margin-bottom: 20px;">
  <i class="fas fa-exclamation-circle"></i>
  <strong>LỖI:</strong> <?= safe_value($error) ?>
</div>
<?php endif; ?>

<?php if(isset($msg)&&$msg): ?>
<div class="alert alert-success" style="margin-bottom: 20px;">
  <i class="fas fa-check-circle"></i>
  <strong>THÀNH CÔNG:</strong> <?= safe_value($msg) ?>
</div>
<?php endif; ?>

<?php if($tour): ?>

<form method="post" action="<?=BASE_URL?>?act=admin-tour-update" class="form-container">
  <input type="hidden" name="id_goi" value="<?=$id_goi?>">

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
          <input type="radio" value="0" name="nuocngoai" id="tour_trongnuoc" <?=$nuocngoai==0?'checked':''?>>
          <label for="tour_trongnuoc">🇻🇳 Trong nước</label>
        </div>
        <div class="radio-option">
          <input type="radio" value="1" name="nuocngoai" id="tour_quocte" <?=$nuocngoai==1?'checked':''?>>
          <label for="tour_quocte">🌍 Quốc tế</label>
        </div>
      </div>
    </div>
    
    <div class="form-group-modern">
      <label>Khuyến mãi</label>
      <div class="radio-group">
        <div class="radio-option">
          <input type="radio" value="1" name="khuyenmai" id="km_co" <?=$khuyenmai==1?'checked':''?>>
          <label for="km_co">✅ Có khuyến mãi</label>
        </div>
        <div class="radio-option">
          <input type="radio" value="0" name="khuyenmai" id="km_khong" <?=$khuyenmai==0?'checked':''?>>
          <label for="km_khong">❌ Không</label>
        </div>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group-modern" id="field_quocgia" style="display:<?=$nuocngoai==1?'block':'none'?>">
        <label for="quocgia">Quốc gia <span class="required">*</span></label>
        <input type="text" name="quocgia" id="quocgia" value="<?=$quocgia?>" placeholder="Ví dụ: Thái Lan, Singapore...">
      </div>

      <div class="form-group-modern" id="field_tinh" style="display:<?=$nuocngoai==0?'block':'none'?>">
        <label for="ten_tinh">Tỉnh/Thành phố <span class="required">*</span></label>
        <select name="ten_tinh" id="ten_tinh">
          <option value="">-- Chọn tỉnh --</option>
          <?php if(!empty($provinces)) foreach($provinces as $p): 
              $pn = safe_value($p['ten_tinh']);
          ?>
              <option value="<?=$pn?>" <?=$pn==$ten_tinh?'selected':''?>><?=$pn?></option>
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
      <input type="text" name="tengoi" id="tengoi" value="<?=$tengoi?>" required placeholder="Ví dụ: Du lịch Hà Nội - Hạ Long 3 ngày 2 đêm">
    </div>

    <div class="form-row">
      <div class="form-group-modern">
        <label for="noixuatphat">Điểm khởi hành <span class="required">*</span></label>
        <input type="text" name="noixuatphat" id="noixuatphat" value="<?=$noixuatphat?>" required placeholder="Ví dụ: TP. Hồ Chí Minh">
      </div>

      <div class="form-group-modern">
        <label for="vitri">Điểm đến <span class="required">*</span></label>
        <input type="text" name="vitri" id="vitri" value="<?=$vitri?>" required placeholder="Ví dụ: Vịnh Hạ Long">
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
        <input type="text" name="giagoi" id="giagoi" value="<?=$giagoi?>" required placeholder="Ví dụ: 5000000">
      </div>

      <div class="form-group-modern">
        <label for="giatreem">Giá trẻ em (VNĐ) <span class="required">*</span></label>
        <input type="text" name="giatreem" id="giatreem" value="<?=$giatreem?>" required placeholder="Ví dụ: 3000000">
      </div>

      <div class="form-group-modern">
        <label for="giatrenho">Giá trẻ nhỏ (VNĐ) <span class="required">*</span></label>
        <input type="text" name="giatrenho" id="giatrenho" value="<?=$giatrenho?>" required placeholder="Ví dụ: 1000000">
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
      <textarea class="form-control" name="chitietgoi" id="packagedetails" required><?=$chitietgoi?></textarea>
    </div>

    <div class="form-group-modern" style="margin-bottom: 24px;">
      <label for="packagedetails1">Chương trình tour <span class="required">*</span></label>
      <textarea class="form-control" name="chuongtrinh" id="packagedetails1" required><?=$chuongtrinh?></textarea>
    </div>

    <div class="form-group-modern">
      <label for="packagedetails2">Lưu ý <span class="required">*</span></label>
      <textarea class="form-control" name="luuy" id="packagedetails2" required><?=$luuy?></textarea>
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
        <input type="number" name="songay" id="songay" value="<?=$songay?>" required placeholder="Ví dụ: 3">
      </div>

      <div class="form-group-modern">
        <label for="phuongtien">Phương tiện <span class="required">*</span></label>
        <input type="text" name="phuongtien" id="phuongtien" value="<?=$phuongtien?>" required placeholder="Ví dụ: Xe khách, Máy bay">
      </div>
    </div>

    <div class="form-row">
      <div class="form-group-modern">
        <label for="ngayxuatphat">Ngày xuất phát <span class="required">*</span></label>
        <input type="date" name="ngayxuatphat" id="ngayxuatphat" value="<?=$ngayxuatphat?>" required>
      </div>

      <div class="form-group-modern">
        <label for="giodi">Giờ xuất phát <span class="required">*</span></label>
        <input type="time" name="giodi" id="giodi" value="<?=$giodi?>" required>
      </div>

      <div class="form-group-modern">
        <label for="ngayve">Ngày về <span class="required">*</span></label>
        <input type="date" name="ngayve" id="ngayve" value="<?=$ngayve?>" required>
      </div>
    </div>

    <div class="form-group-modern">
      <label for="ngaydang">Ngày đăng <span class="required">*</span></label>
      <input type="date" name="ngaydang" id="ngaydang" value="<?=$ngaydang?>" required>
    </div>
  </div>

  <!-- Card 6: Hình ảnh -->
  <div class="form-card">
    <div class="card-header">
      <i class="fas fa-image"></i>
      <h3>Hình ảnh đại diện</h3>
    </div>

    <?php if($hinhanh): ?>
      <div class="image-preview">
        <img src="<?=$hinhanh?>" alt="Tour image">
        <div class="image-preview-info">
          <p style="margin: 0 0 12px; color: var(--text-dark); font-weight: 600;">Hình ảnh hiện tại</p>
          <a href="<?=BASE_URL?>?act=admin-tour-update-image&id=<?=$id_goi?>" class="change-image-btn">
            <i class="fas fa-camera"></i>
            Thay đổi ảnh
          </a>
        </div>
      </div>
    <?php else: ?>
      <div class="info-box">
        <i class="fas fa-info-circle"></i>
        Chưa có hình ảnh. 
        <a href="<?=BASE_URL?>?act=admin-tour-update-image&id=<?=$id_goi?>" style="color: var(--primary); font-weight: 600;">
          Thêm ảnh ngay
        </a>
      </div>
    <?php endif; ?>

    <?php if($ngaycapnhat): ?>
      <div class="info-box" style="margin-top: 16px;">
        <i class="fas fa-clock"></i>
        Cập nhật lần cuối: <strong><?=$ngaycapnhat?></strong>
      </div>
    <?php endif; ?>
  </div>

  <!-- Form Actions -->
  <div class="form-card">
    <div class="form-actions">
      <a href="<?=BASE_URL?>?act=admin-tours" class="btn-cancel">
        <i class="fas fa-times"></i>
        Hủy bỏ
      </a>
      <button type="submit" class="btn-submit">
        <i class="fas fa-save"></i>
        Cập nhật Tour
      </button>
    </div>
  </div>

</form>

<?php else: ?>
<div class="form-card">
  <div class="alert alert-error">
    <i class="fas fa-exclamation-circle"></i>
    <strong>LỖI:</strong> Không tìm thấy tour.
  </div>
</div>
<?php endif; ?>

<script src="assets/ckeditor/ckeditor.js"></script>

<script>
// Initialize CKEditor
const editorIDs = ['packagedetails', 'packagedetails1', 'packagedetails2'];

editorIDs.forEach(id => {
    CKEDITOR.replace(id, {
        height: 350,
        filebrowserBrowseUrl: 'ckfinder/ckfinder.html',
        filebrowserImageBrowseUrl: 'ckfinder/ckfinder.html?type=Images',
        filebrowserFlashBrowseUrl: 'ckfinder/ckfinder.html?type=Flash',
        filebrowserUploadUrl: 'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
        filebrowserImageUploadUrl: 'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
    });
});

// Toggle between domestic and international tour fields
document.addEventListener('DOMContentLoaded', function() {
    var r1 = document.getElementById('tour_trongnuoc'),
        r2 = document.getElementById('tour_quocte'),
        f1 = document.getElementById('field_tinh'),
        f2 = document.getElementById('field_quocgia'),
        iq = document.getElementById('quocgia');

    function toggle() {
        if (r2 && r2.checked) {
            f1.style.display = 'none';
            f2.style.display = 'block';
            if (iq.value === 'Việt Nam') iq.value = '';
        } else {
            f1.style.display = 'block';
            f2.style.display = 'none';
            iq.value = 'Việt Nam';
        }
    }

    r1.addEventListener('change', toggle);
    r2.addEventListener('change', toggle);
    toggle();
});
</script>
