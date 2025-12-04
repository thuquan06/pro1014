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
$khuyenmai_phantram=safe_value($tour['khuyenmai_phantram']??'0');
$khuyenmai_tungay=safe_value($tour['khuyenmai_tungay']??'');
$khuyenmai_denngay=safe_value($tour['khuyenmai_denngay']??'');
$khuyenmai_mota=safe_value($tour['khuyenmai_mota']??'');
$quocgia=safe_value($tour['quocgia']??'Việt Nam');
$ten_tinh=safe_value($tour['ten_tinh']??'');
$mato=safe_value($tour['mato']??'');
$tengoi=safe_value($tour['tengoi']??'');
$noixuatphat=safe_value($tour['noixuatphat']??'');
$vitri=safe_value($tour['vitri']??'');
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

    <!-- Chi tiết khuyến mãi (hiện khi chọn Có khuyến mãi) -->
    <div id="promotion_details" style="display: <?=$khuyenmai==1?'block':'none'?>;">
      <div class="form-row">
        <div class="form-group-modern">
          <label for="khuyenmai_phantram">Phần trăm giảm giá (%) <span class="required">*</span></label>
          <input type="number"
                 name="khuyenmai_phantram"
                 id="khuyenmai_phantram"
                 value="<?=$khuyenmai_phantram?>"
                 min="0"
                 max="100"
                 placeholder="Ví dụ: 20">
          <small style="color: var(--text-light); font-size: 12px; margin-top: 4px; display: block;">
            <i class="fas fa-info-circle"></i> Nhập từ 0-100%
          </small>
        </div>

        <div class="form-group-modern">
          <label for="khuyenmai_mota">Mô tả khuyến mãi</label>
          <input type="text"
                 name="khuyenmai_mota"
                 id="khuyenmai_mota"
                 value="<?=$khuyenmai_mota?>"
                 placeholder="Ví dụ: Ưu đãi mùa hè, Flash Sale...">
        </div>
      </div>

      <div class="form-row">
        <div class="form-group-modern">
          <label for="khuyenmai_tungay">Ngày bắt đầu khuyến mãi <span class="required">*</span></label>
          <input type="date"
                 name="khuyenmai_tungay"
                 id="khuyenmai_tungay"
                 value="<?=$khuyenmai_tungay?>">
        </div>

        <div class="form-group-modern">
          <label for="khuyenmai_denngay">Ngày kết thúc khuyến mãi <span class="required">*</span></label>
          <input type="date"
                 name="khuyenmai_denngay"
                 id="khuyenmai_denngay"
                 value="<?=$khuyenmai_denngay?>">
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
      <label for="mato">Mã tour <span class="required">*</span></label>
      <input type="text" name="mato" id="mato" value="<?=$mato?>" required placeholder="Ví dụ: TOUR-HL-001">
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


    <div class="form-group-modern" style="margin-bottom: 24px;">
      <label>Lịch trình tour <span class="required">*</span></label>
      
      <!-- Day Builder Interface -->
      <div id="itinerary-builder" style="margin-bottom: 16px;">
        <div style="margin-bottom: 16px;">
          <button type="button" id="add-day-btn" class="btn btn-primary" style="padding: 10px 20px;">
            <i class="fas fa-plus"></i> Thêm ngày
          </button>
        </div>
        <div id="days-container">
          <!-- Days will be added here -->
        </div>
      </div>
      
      <!-- Hidden textarea để lưu HTML cuối cùng -->
      <textarea name="chuongtrinh" id="chuongtrinh-hidden" style="display: none;"></textarea>
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

// Itinerary Day Builder
let dayCounter = 0;
let dayEditors = {};

// Hàm thêm ngày mới
function addDay(dayTitle = '', dayContent = '') {
    dayCounter++;
    const dayId = 'day_' + dayCounter;
    const editorId = 'day_editor_' + dayCounter;
    
    const dayHtml = `
        <div class="day-item" id="${dayId}" style="margin-bottom: 20px; padding: 20px; border: 2px solid var(--border); border-radius: 8px; background: #f9fafb;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <h4 style="margin: 0; color: var(--primary); font-size: 16px;">
                    <i class="fas fa-calendar-day"></i> Ngày ${dayCounter}
                </h4>
                <button type="button" onclick="removeDay(${dayCounter})" class="btn btn-sm" style="background: #ef4444; color: white; padding: 6px 12px;">
                    <i class="fas fa-times"></i> Xóa
                </button>
            </div>
            <div style="margin-bottom: 16px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; font-size: 14px; color: var(--text-dark);">Tiêu đề ngày (tùy chọn)</label>
                <input type="text" class="day-title-input" data-day="${dayCounter}" placeholder="Ví dụ: Khởi hành, Tham quan thành phố..." 
                       value="${dayTitle.replace(/"/g, '&quot;')}"
                       style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 6px; font-size: 14px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; font-size: 14px; color: var(--text-dark);">Nội dung</label>
                <textarea class="day-content-editor" id="${editorId}" data-day="${dayCounter}" style="width: 100%; min-height: 250px;">${dayContent.replace(/</g, '&lt;').replace(/>/g, '&gt;')}</textarea>
            </div>
        </div>
    `;
    
    document.getElementById('days-container').insertAdjacentHTML('beforeend', dayHtml);
    
    // Khởi tạo CKEditor cho ngày này
    setTimeout(() => {
        dayEditors[dayCounter] = CKEDITOR.replace(editorId, dayEditorConfig);
        if (dayContent) {
            dayEditors[dayCounter].on('instanceReady', function() {
                this.setData(dayContent);
            });
        }
    }, 200);
}

// Hàm xóa ngày
function removeDay(dayNum) {
    if (confirm('Bạn có chắc chắn muốn xóa ngày này?')) {
        const dayId = 'day_' + dayNum;
        const dayElement = document.getElementById(dayId);
        
        if (dayElement) {
            // Xóa CKEditor instance
            if (dayEditors[dayNum]) {
                dayEditors[dayNum].destroy();
                delete dayEditors[dayNum];
            }
            
            dayElement.remove();
            updateDayNumbers();
        }
    }
}

// Cập nhật số ngày sau khi xóa
function updateDayNumbers() {
    const dayItems = document.querySelectorAll('.day-item');
    dayItems.forEach((item, index) => {
        const newDayNum = index + 1;
        const dayNumAttr = item.getAttribute('id').replace('day_', '');
        const titleInput = item.querySelector('.day-title-input');
        const contentTextarea = item.querySelector('.day-content-editor');
        const header = item.querySelector('h4');
        
        if (header) {
            header.innerHTML = `<i class="fas fa-calendar-day"></i> Ngày ${newDayNum}`;
        }
        
        if (titleInput) {
            titleInput.dataset.day = newDayNum;
        }
        
        if (contentTextarea) {
            contentTextarea.dataset.day = newDayNum;
        }
        
        // Cập nhật onclick của nút xóa
        const removeBtn = item.querySelector('button');
        if (removeBtn) {
            removeBtn.setAttribute('onclick', `removeDay(${newDayNum})`);
        }
    });
    dayCounter = dayItems.length;
}

// Hàm build HTML từ các ngày
function buildItineraryHTML() {
    let html = '';
    const dayItems = document.querySelectorAll('.day-item');
    
    dayItems.forEach((item, index) => {
        const dayNum = index + 1;
        const titleInput = item.querySelector('.day-title-input');
        const contentTextarea = item.querySelector('.day-content-editor');
        const dayNumAttr = contentTextarea ? parseInt(contentTextarea.dataset.day) : dayNum;
        
        const title = titleInput ? titleInput.value.trim() : '';
        let content = '';
        
        // Lấy nội dung từ CKEditor
        if (dayEditors[dayNumAttr]) {
            content = dayEditors[dayNumAttr].getData();
        } else if (contentTextarea) {
            content = contentTextarea.value;
        }
        
        if (content.trim()) {
            let dayHeader = '';
            if (title) {
                dayHeader = `<h3><strong>NGÀY ${dayNum}: ${title.replace(/</g, '&lt;').replace(/>/g, '&gt;')}</strong></h3>`;
            } else {
                dayHeader = `<h3><strong>NGÀY ${dayNum}</strong></h3>`;
            }
            
            html += dayHeader + content;
        }
    });
    
    return html;
}

// Khởi tạo CKEditor cho "Lưu ý"
CKEDITOR.replace('packagedetails2', ckConfig);

// Hàm parse và load itinerary cũ
function parseAndLoadExistingItinerary(html) {
    if (!html || !html.trim()) return;
    
    // Tìm tất cả các marker "NGÀY X"
    const regex = /<h[1-6][^>]*>\s*<strong[^>]*>\s*NGÀY\s*(\d+)(?::\s*([^<]+))?\s*<\/strong>\s*<\/h[1-6]>/gi;
    const daySections = [];
    let match;
    let lastIndex = 0;
    
    while ((match = regex.exec(html)) !== null) {
        const dayNum = parseInt(match[1]);
        const title = match[2] ? match[2].trim() : '';
        const startPos = match.index;
        
        // Lấy nội dung của ngày này (từ sau heading đến heading tiếp theo)
        const nextMatch = html.substring(startPos + match[0].length).match(/<h[1-6][^>]*>\s*<strong[^>]*>\s*NGÀY\s*\d+/i);
        const endPos = nextMatch ? startPos + match[0].length + nextMatch.index : html.length;
        const content = html.substring(startPos + match[0].length, endPos).trim();
        
        daySections.push({
            day: dayNum,
            title: title,
            content: content
        });
        
        lastIndex = endPos;
    }
    
    // Nếu không tìm thấy marker, thử tìm trong text thuần
    if (daySections.length === 0) {
        const textRegex = /(?:NGÀY|Day|Ngày)\s*(\d+)(?::\s*([^\n<]+))?/gi;
        let textMatch;
        while ((textMatch = textRegex.exec(html)) !== null) {
            const dayNum = parseInt(textMatch[1]);
            const title = textMatch[2] ? textMatch[2].trim() : '';
            const startPos = textMatch.index;
            
            const nextTextMatch = html.substring(startPos + textMatch[0].length).match(/(?:NGÀY|Day|Ngày)\s*\d+/i);
            const endPos = nextTextMatch ? startPos + textMatch[0].length + nextTextMatch.index : html.length;
            const content = html.substring(startPos + textMatch[0].length, endPos).trim();
            
            daySections.push({
                day: dayNum,
                title: title,
                content: content
            });
        }
    }
    
    // Nếu vẫn không tìm thấy, thêm toàn bộ nội dung vào ngày 1
    if (daySections.length === 0) {
        daySections.push({
            day: 1,
            title: '',
            content: html
        });
    }
    
    // Sắp xếp và load các ngày
    daySections.sort((a, b) => a.day - b.day);
    
    setTimeout(() => {
        daySections.forEach(section => {
            addDay(section.title, section.content);
        });
    }, 500);
}

// Xử lý khi submit form
function updateCKEditorBeforeSubmit() {
    try {
        // Cập nhật tất cả editor instances
        for (var instance in CKEDITOR.instances) {
            CKEDITOR.instances[instance].updateElement();
        }
        
        // Build itinerary HTML từ các ngày
        const itineraryHTML = buildItineraryHTML();
        const hiddenField = document.getElementById('chuongtrinh-hidden');
        
        if (hiddenField) {
            hiddenField.value = itineraryHTML || '';
        }
        
        // Kiểm tra nếu không có nội dung
        if (!itineraryHTML || itineraryHTML.trim() === '') {
            alert('Vui lòng nhập lịch trình tour (ít nhất 1 ngày)');
            return false;
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

    // Toggle promotion details
    var radioKmCo = document.getElementById('km_co');
    var radioKmKhong = document.getElementById('km_khong');
    var promotionDetails = document.getElementById('promotion_details');
    var promotionInputs = promotionDetails.querySelectorAll('input');

    function togglePromotionDetails() {
        if (radioKmCo.checked) {
            promotionDetails.style.display = 'block';
            // Bật required cho các trường bắt buộc
            document.getElementById('khuyenmai_phantram').required = true;
            document.getElementById('khuyenmai_tungay').required = true;
            document.getElementById('khuyenmai_denngay').required = true;
        } else {
            promotionDetails.style.display = 'none';
            // Tắt required khi không có khuyến mãi
            promotionInputs.forEach(function(input) {
                input.required = false;
            });
        }
    }

    togglePromotionDetails();
    radioKmCo.addEventListener('change', togglePromotionDetails);
    radioKmKhong.addEventListener('change', togglePromotionDetails);
    
    // Form submit handler - không cần event listener vì đã có onsubmit trên form tag
    
    // Itinerary Builder - Thêm ngày
    const addDayBtn = document.getElementById('add-day-btn');
    if (addDayBtn) {
        addDayBtn.addEventListener('click', function() {
            addDay();
        });
    }
    
    // Load dữ liệu cũ khi edit tour
    <?php if (!empty($chuongtrinh)): ?>
    const existingItinerary = <?= json_encode($chuongtrinh, JSON_HEX_QUOT | JSON_HEX_APOS | JSON_UNESCAPED_UNICODE) ?>;
    if (existingItinerary && existingItinerary.trim()) {
        setTimeout(() => {
            parseAndLoadExistingItinerary(existingItinerary);
        }, 800);
    }
    <?php else: ?>
    // Nếu không có dữ liệu cũ, thêm 1 ngày mặc định
    const daysContainer = document.getElementById('days-container');
    if (daysContainer && daysContainer.children.length === 0) {
        addDay();
    }
    <?php endif; ?>

    // Price discount calculator
    var inputGiaNguoiLon = document.getElementById('giagoi');
    var inputGiaTreEm = document.getElementById('giatreem');
    var inputGiaTreNho = document.getElementById('giatrenho');
    var inputPhanTram = document.getElementById('khuyenmai_phantram');

    function formatCurrency(value) {
        return new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND'
        }).format(value);
    }

    function calculateDiscountedPrice(original, percent) {
        var discount = (original * percent) / 100;
        return original - discount;
    }

    function updatePricePreview(inputId, previewId, originalId, discountedId, badgeId) {
        var input = document.getElementById(inputId);
        var preview = document.getElementById(previewId);
        var originalSpan = document.getElementById(originalId);
        var discountedSpan = document.getElementById(discountedId);
        var badge = document.getElementById(badgeId);

        if (!input || !preview) return;

        var originalPrice = parseFloat(input.value) || 0;
        var percent = parseFloat(inputPhanTram?.value) || 0;

        // Kiểm tra có khuyến mãi không
        if (radioKmCo.checked && percent > 0 && originalPrice > 0) {
            var discountedPrice = calculateDiscountedPrice(originalPrice, percent);

            originalSpan.textContent = formatCurrency(originalPrice);
            discountedSpan.textContent = formatCurrency(discountedPrice);
            badge.textContent = '🔥 Giảm ' + percent + '%';

            preview.classList.add('active');
        } else {
            preview.classList.remove('active');
        }
    }

    function updateAllPrices() {
        updatePricePreview('giagoi', 'preview_giagoi', 'original_giagoi', 'discounted_giagoi', 'badge_giagoi');
        updatePricePreview('giatreem', 'preview_giatreem', 'original_giatreem', 'discounted_giatreem', 'badge_giatreem');
        updatePricePreview('giatrenho', 'preview_giatrenho', 'original_giatrenho', 'discounted_giatrenho', 'badge_giatrenho');
    }

    // Event listeners cho các input giá
    inputGiaNguoiLon?.addEventListener('input', updateAllPrices);
    inputGiaTreEm?.addEventListener('input', updateAllPrices);
    inputGiaTreNho?.addEventListener('input', updateAllPrices);
    inputPhanTram?.addEventListener('input', updateAllPrices);

    // Cập nhật khi toggle khuyến mãi
    radioKmCo?.addEventListener('change', updateAllPrices);
    radioKmKhong?.addEventListener('change', updateAllPrices);

    // Tính lần đầu nếu có dữ liệu cũ
    updateAllPrices();
});
</script>
