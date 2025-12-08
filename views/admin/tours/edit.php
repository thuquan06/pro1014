<?php
/**
 * Tour Edit Page - Modern Interface
 * Updated: 2025-11-25
 */
function safe_value($value, $default = '') {
    if ($value === null || $value === '') return $default;
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

// Kiểm tra biến $tour có tồn tại không
if (!isset($tour) || !$tour) {
    die('Lỗi: Không tìm thấy thông tin tour');
}

$id_goi=safe_value($tour['id_goi']??'');
$nuocngoai=isset($tour['nuocngoai'])?(int)$tour['nuocngoai']:0;
$khuyenmai=isset($tour['khuyenmai'])?(int)$tour['khuyenmai']:0;
$khuyenmai_phantram=safe_value($tour['khuyenmai_phantram']??'0');
$khuyenmai_tungay=safe_value($tour['khuyenmai_tungay']??'');
$khuyenmai_denngay=safe_value($tour['khuyenmai_denngay']??'');
$khuyenmai_mota=safe_value($tour['khuyenmai_mota']??'');
$quocgia=safe_value($tour['quocgia']??'Việt Nam');
$mato=safe_value($tour['mato']??'');
$tengoi=safe_value($tour['tengoi']??'');
$noixuatphat=safe_value($tour['noixuatphat']??'');
$giagoi=safe_value($tour['giagoi']??'');
$giatreem=safe_value($tour['giatreem']??'');
$giatrenho=safe_value($tour['giatrenho']??'');
// Không mã hóa HTML cho các trường dùng CKEditor
$chuongtrinh=html_entity_decode($tour['chuongtrinh']??'', ENT_QUOTES, 'UTF-8');
$luuy=html_entity_decode($tour['luuy']??'', ENT_QUOTES, 'UTF-8');
$songay=safe_value($tour['songay']??'');
$giodi=safe_value($tour['giodi']??'');
$ngayxuatphat=safe_value($tour['ngayxuatphat']??'');
$ngayve=safe_value($tour['ngayve']??'');
$phuongtien=safe_value($tour['phuongtien']??'');
$socho=safe_value($tour['socho']??'');
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

<form method="post" action="<?=BASE_URL?>?act=admin-tour-update" class="form-container" onsubmit="return updateCKEditorBeforeSubmit()">
  <input type="hidden" name="id_goi" value="<?=$id_goi?>">
  <input type="hidden" name="chitietgoi" value="">

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
    

    <div class="form-row">
      <div class="form-group-modern" id="field_quocgia" style="display:<?=$nuocngoai==1?'block':'none'?>">
        <label for="quocgia">Quốc gia <span class="required">*</span></label>
        <input type="text" name="quocgia" id="quocgia" value="<?=$quocgia?>" placeholder="Ví dụ: Thái Lan, Singapore...">
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
      <input type="text" name="mato" id="mato" value="<?=$mato?>" required placeholder="Ví dụ: TOUR-HL-001">
    </div>

    <div class="form-group-modern">
      <label for="tengoi">Tên tour <span class="required">*</span></label>
      <input type="text" name="tengoi" id="tengoi" value="<?=$tengoi?>" required placeholder="Ví dụ: Du lịch Hà Nội - Hạ Long 3 ngày 2 đêm">
    </div>

    <div class="form-group-modern">
      <label for="noixuatphat">Điểm khởi hành <span class="required">*</span></label>
      <input type="text" name="noixuatphat" id="noixuatphat" value="<?=$noixuatphat?>" required placeholder="Ví dụ: TP. Hồ Chí Minh">
    </div>

    <div class="form-row">
      <div class="form-group-modern">
        <label for="songay">Số ngày <span class="required">*</span></label>
        <input type="text" name="songay" id="songay" value="<?=$songay?>" required placeholder="Ví dụ: 3 ngày 2 đêm">
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
        <div id="preview_giagoi" class="price-preview">
          <div class="price-preview-content">
            <div>
              <div class="price-original" id="original_giagoi"></div>
              <div class="price-discounted" id="discounted_giagoi"></div>
            </div>
            <div class="discount-badge" id="badge_giagoi"></div>
          </div>
        </div>
      </div>

      <div class="form-group-modern">
        <label for="giatreem">Giá trẻ em (VNĐ) <span class="required">*</span></label>
        <input type="text" name="giatreem" id="giatreem" value="<?=$giatreem?>" required placeholder="Ví dụ: 3000000">
        <div id="preview_giatreem" class="price-preview">
          <div class="price-preview-content">
            <div>
              <div class="price-original" id="original_giatreem"></div>
              <div class="price-discounted" id="discounted_giatreem"></div>
            </div>
            <div class="discount-badge" id="badge_giatreem"></div>
          </div>
        </div>
      </div>

      <div class="form-group-modern">
        <label for="giatrenho">Giá trẻ nhỏ (VNĐ) <span class="required">*</span></label>
        <input type="text" name="giatrenho" id="giatrenho" value="<?=$giatrenho?>" required placeholder="Ví dụ: 1000000">
        <div id="preview_giatrenho" class="price-preview">
          <div class="price-preview-content">
            <div>
              <div class="price-original" id="original_giatrenho"></div>
              <div class="price-discounted" id="discounted_giatrenho"></div>
            </div>
            <div class="discount-badge" id="badge_giatrenho"></div>
          </div>
        </div>
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
                           <?= (isset($selectedServiceIds) && in_array($service['id'], $selectedServiceIds)) ? 'checked' : '' ?>>
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
      <textarea class="form-control" name="luuy" id="packagedetails2" required><?= str_replace('</textarea>', '&lt;/textarea&gt;', $luuy) ?></textarea>
    </div>
  </div>

  <!-- Card 6: Phân loại & Tags -->
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
                <input type="checkbox" name="loai_ids[]" value="<?= $cat['id'] ?>" 
                       <?= in_array($cat['id'], $selectedCategoryIds) ? 'checked' : '' ?>
                       style="margin-right: 8px;">
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
                <input type="checkbox" name="tag_ids[]" value="<?= $tag['id'] ?>"
                       <?= in_array($tag['id'], $selectedTagIds) ? 'checked' : '' ?>
                       style="margin-right: 8px;">
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

  <!-- Card 7: Hình ảnh -->
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
// Initialize CKEditor Config
const ckConfig = {
    height: 350,
    filebrowserBrowseUrl: 'assets/ckfinder/ckfinder.html',
    filebrowserImageBrowseUrl: 'assets/ckfinder/ckfinder.html?type=Images',
    filebrowserUploadUrl: 'assets/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
    filebrowserImageUploadUrl: 'assets/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
};

const dayEditorConfig = {
    height: 300,
    filebrowserBrowseUrl: 'assets/ckfinder/ckfinder.html',
    filebrowserImageBrowseUrl: 'assets/ckfinder/ckfinder.html?type=Images',
    filebrowserUploadUrl: 'assets/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
    filebrowserImageUploadUrl: 'assets/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
};

// Khởi tạo CKEditor cho "Lưu ý"
CKEDITOR.replace('packagedetails2', ckConfig);

// Xử lý khi submit form
function updateCKEditorBeforeSubmit() {
    try {
        // Cập nhật tất cả editor instances
        for (var instance in CKEDITOR.instances) {
            CKEDITOR.instances[instance].updateElement();
        }
        
        return true;
    } catch (error) {
        console.error('Error in updateCKEditorBeforeSubmit:', error);
        alert('Có lỗi xảy ra khi chuẩn bị dữ liệu. Vui lòng thử lại.');
        return false;
    }
}

// Toggle between domestic and international tour fields
document.addEventListener('DOMContentLoaded', function() {
    var r1 = document.getElementById('tour_trongnuoc'),
        r2 = document.getElementById('tour_quocte'),
        f2 = document.getElementById('field_quocgia'),
        iq = document.getElementById('quocgia');

    function toggle() {
        if (r2 && r2.checked) {
            f2.style.display = 'block';
            if (iq.value === 'Việt Nam') iq.value = '';
        } else {
            f2.style.display = 'none';
            iq.value = 'Việt Nam';
        }
    }

    r1.addEventListener('change', toggle);
    r2.addEventListener('change', toggle);
    toggle();


});
</script>
