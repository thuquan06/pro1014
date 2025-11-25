<?php
/**
 * Thêm/Sửa Lịch trình - Modern Interface
 * Updated: 2025-11-25
 */

$isEdit = isset($lichtrinh);
$title = $isEdit ? 'Sửa lịch trình' : 'Thêm lịch trình mới';
$action = $isEdit ? BASE_URL . "?act=tour-lichtrinh-sua&id={$lichtrinh['id']}&id_goi=$idGoi" : BASE_URL . "?act=tour-lichtrinh-them&id_goi=$idGoi";

ob_start();
?>

<style>
.form-page-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
}

.form-page-title {
  font-size: 28px;
  font-weight: 700;
  color: var(--text-dark);
  margin: 0;
}

.form-page-subtitle {
  color: var(--text-light);
  font-size: 14px;
  margin-top: 4px;
}

.form-card {
  background: white;
  border: 1px solid var(--border);
  border-radius: 12px;
  padding: 32px;
  margin-bottom: 20px;
}

.form-section {
  margin-bottom: 32px;
}

.form-section:last-child {
  margin-bottom: 0;
}

.section-title {
  font-size: 18px;
  font-weight: 700;
  color: var(--text-dark);
  margin: 0 0 20px 0;
  padding-bottom: 12px;
  border-bottom: 2px solid var(--bg-light);
  display: flex;
  align-items: center;
  gap: 10px;
}

.section-title i {
  color: var(--primary);
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
.form-textarea,
.form-select {
  width: 100%;
  padding: 12px 16px;
  border: 1px solid var(--border);
  border-radius: 8px;
  font-size: 14px;
  transition: all 0.2s;
  font-family: inherit;
}

.form-input:focus,
.form-textarea:focus,
.form-select:focus {
  outline: none;
  border-color: var(--primary);
  box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.form-textarea {
  resize: vertical;
  line-height: 1.6;
}

.form-help {
  display: block;
  margin-top: 6px;
  font-size: 12px;
  color: var(--text-light);
}

.form-help i {
  margin-right: 4px;
}

.checkbox-group {
  display: flex;
  gap: 24px;
  padding: 12px 0;
}

.checkbox-item {
  display: flex;
  align-items: center;
  gap: 8px;
}

.checkbox-item input[type="checkbox"] {
  width: 18px;
  height: 18px;
  cursor: pointer;
}

.checkbox-item label {
  font-size: 14px;
  color: var(--text-dark);
  cursor: pointer;
  margin: 0;
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
  .form-card {
    padding: 20px;
  }
  
  .form-actions {
    flex-direction: column;
  }
  
  .btn-submit,
  .btn-cancel {
    width: 100%;
    justify-content: center;
  }
  
  .checkbox-group {
    flex-direction: column;
    gap: 12px;
  }
}
</style>

<!-- Page Header -->
<div class="form-page-header">
  <div>
    <h1 class="form-page-title">
      <i class="fas <?= $isEdit ? 'fa-edit' : 'fa-plus-circle' ?>" style="color: var(--primary);"></i>
      <?= $title ?>
    </h1>
    <p class="form-page-subtitle">Tour ID: <?= $idGoi ?></p>
  </div>
  <a href="<?= BASE_URL ?>?act=tour-lichtrinh&id_goi=<?= $idGoi ?>" class="btn btn-secondary">
    <i class="fas fa-arrow-left"></i>
    Quay lại
  </a>
</div>

<!-- Thông báo lỗi -->
<?php if (isset($_SESSION['errors'])): ?>
  <div class="alert alert-error" style="margin-bottom: 20px;">
    <strong><i class="fas fa-exclamation-circle"></i> Có lỗi xảy ra:</strong>
    <ul style="margin: 10px 0 0 20px;">
      <?php foreach ($_SESSION['errors'] as $error): ?>
        <li><?= $error ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
  <?php unset($_SESSION['errors']); ?>
<?php endif; ?>

<!-- Form -->
<form action="<?= $action ?>" method="POST">
  <div class="form-card">
    
    <!-- Section 1: Thông tin cơ bản -->
    <div class="form-section">
      <h3 class="section-title">
        <i class="fas fa-info-circle"></i>
        Thông tin cơ bản
      </h3>
      
      <div class="form-group">
        <label class="form-label">
          Ngày thứ <span class="required">*</span>
        </label>
        <input type="number" 
               name="ngay_thu" 
               class="form-input" 
               min="1" 
               max="30"
               value="<?= $isEdit ? $lichtrinh['ngay_thu'] : '' ?>"
               placeholder="VD: 1, 2, 3..."
               required>
        <small class="form-help">
          <i class="fas fa-info-circle"></i>
          Nhập số thứ tự ngày trong hành trình
        </small>
      </div>

      <div class="form-group">
        <label class="form-label">
          Tiêu đề <span class="required">*</span>
        </label>
        <input type="text" 
               name="tieude" 
               class="form-input" 
               value="<?= $isEdit ? htmlspecialchars($lichtrinh['tieude']) : '' ?>"
               placeholder="VD: Ngày 1: Khởi hành - TP.HCM → Đà Lạt"
               required>
      </div>

      <div class="form-group">
        <label class="form-label">
          Mô tả chi tiết <span class="required">*</span>
        </label>
        <textarea name="mota" 
                  class="form-textarea" 
                  rows="5"
                  placeholder="Mô tả tổng quan về lịch trình trong ngày..."
                  required><?= $isEdit ? htmlspecialchars($lichtrinh['mota']) : '' ?></textarea>
      </div>
    </div>

    <!-- Section 2: Điểm đến & Thời gian -->
    <div class="form-section">
      <h3 class="section-title">
        <i class="fas fa-map-marker-alt"></i>
        Điểm đến & Thời gian
      </h3>
      
      <div class="form-group">
        <label class="form-label" for="diemden">
          Điểm đến
        </label>
        <textarea 
          class="form-textarea" 
          id="diemden" 
          name="diemden" 
          rows="2"
          placeholder="VD: Hồ Gươm, Văn Miếu, Chùa Một Cột..."
        ><?= $isEdit ? htmlspecialchars($lichtrinh['diemden'] ?? '') : '' ?></textarea>
        <small class="form-help">
          <i class="fas fa-info-circle"></i> 
          Liệt kê các điểm đến trong ngày, cách nhau bởi dấu phẩy
        </small>
      </div>

      <div class="form-group">
        <label class="form-label" for="thoiluong">
          Thời lượng
        </label>
        <input 
          type="text" 
          class="form-input" 
          id="thoiluong" 
          name="thoiluong" 
          value="<?= $isEdit ? htmlspecialchars($lichtrinh['thoiluong'] ?? '') : '' ?>"
          placeholder="VD: 2 giờ, Cả ngày, 3-4 tiếng..."
        >
        <small class="form-help">
          <i class="fas fa-info-circle"></i> 
          Thời gian ước tính cho lịch trình ngày này
        </small>
      </div>
    </div>

    <!-- Section 3: Hoạt động -->
    <div class="form-section">
      <h3 class="section-title">
        <i class="fas fa-list-ul"></i>
        Hoạt động trong ngày
      </h3>
      
      <div class="form-group">
        <label class="form-label">
          Chi tiết hoạt động
        </label>
        <textarea name="hoatdong" 
                  class="form-textarea" 
                  rows="8"
                  placeholder="06:00 - Tập trung sân bay&#10;09:00 - Bay đến Đà Lạt&#10;12:00 - Ăn trưa..."><?= $isEdit ? htmlspecialchars($lichtrinh['hoatdong']) : '' ?></textarea>
        <small class="form-help">
          <i class="fas fa-info-circle"></i>
          Liệt kê các hoạt động theo giờ (mỗi hoạt động 1 dòng)
        </small>
      </div>
    </div>

    <!-- Section 4: Ghi chú HDV -->
    <div class="form-section">
      <h3 class="section-title">
        <i class="fas fa-user-secret"></i>
        Ghi chú nội bộ
      </h3>
      
      <div class="form-group">
        <label class="form-label" for="ghichu_hdv">
          Ghi chú cho Hướng dẫn viên
        </label>
        <textarea 
          class="form-textarea" 
          id="ghichu_hdv" 
          name="ghichu_hdv" 
          rows="3"
          placeholder="Lưu ý đặc biệt cho hướng dẫn viên..."
          style="border-left: 4px solid #f59e0b;"
        ><?= $isEdit ? htmlspecialchars($lichtrinh['ghichu_hdv'] ?? '') : '' ?></textarea>
        <small class="form-help">
          <i class="fas fa-user-secret"></i> 
          Thông tin nội bộ cho HDV (không hiển thị cho khách)
        </small>
      </div>
    </div>

    <!-- Section 5: Bữa ăn & Nơi nghỉ -->
    <div class="form-section">
      <h3 class="section-title">
        <i class="fas fa-utensils"></i>
        Bữa ăn & Nơi nghỉ
      </h3>
      
      <div class="form-group">
        <label class="form-label">
          Bữa ăn
        </label>
        <div class="checkbox-group">
          <div class="checkbox-item">
            <input type="checkbox" 
                   id="buaan_sang" 
                   value="Sang"
                   <?= ($isEdit && strpos($lichtrinh['buaan'], 'Sang') !== false) ? 'checked' : '' ?>>
            <label for="buaan_sang">🌅 Sáng</label>
          </div>
          <div class="checkbox-item">
            <input type="checkbox" 
                   id="buaan_trua" 
                   value="Trua"
                   <?= ($isEdit && strpos($lichtrinh['buaan'], 'Trua') !== false) ? 'checked' : '' ?>>
            <label for="buaan_trua">☀️ Trưa</label>
          </div>
          <div class="checkbox-item">
            <input type="checkbox" 
                   id="buaan_toi" 
                   value="Toi"
                   <?= ($isEdit && strpos($lichtrinh['buaan'], 'Toi') !== false) ? 'checked' : '' ?>>
            <label for="buaan_toi">🌙 Tối</label>
          </div>
        </div>
        <input type="hidden" name="buaan" id="buaan_input" value="<?= $isEdit ? $lichtrinh['buaan'] : '' ?>">
      </div>

      <div class="form-group">
        <label class="form-label">
          Nơi nghỉ đêm
        </label>
        <input type="text" 
               name="noinghi" 
               class="form-input" 
               value="<?= $isEdit ? htmlspecialchars($lichtrinh['noinghi']) : '' ?>"
               placeholder="VD: Khách sạn 4* trung tâm Đà Lạt">
        <small class="form-help">
          <i class="fas fa-info-circle"></i>
          Để trống nếu ngày cuối không nghỉ đêm
        </small>
      </div>
    </div>

    <!-- Form Actions -->
    <div class="form-actions">
      <a href="<?= BASE_URL ?>?act=tour-lichtrinh&id_goi=<?= $idGoi ?>" class="btn-cancel">
        <i class="fas fa-times"></i>
        Hủy bỏ
      </a>
      <button type="submit" class="btn-submit">
        <i class="fas fa-<?= $isEdit ? 'check' : 'plus-circle' ?>"></i>
        <?= $isEdit ? 'Cập nhật' : 'Thêm mới' ?>
      </button>
    </div>

  </div>
</form>

<script>
// Xử lý checkbox bữa ăn
document.addEventListener('DOMContentLoaded', function() {
  function updateBuaAn() {
    var checked = [];
    document.querySelectorAll('[id^="buaan_"]:checked').forEach(function(cb) {
      checked.push(cb.value);
    });
    document.getElementById('buaan_input').value = checked.join(', ');
  }
  
  document.querySelectorAll('[id^="buaan_"]').forEach(function(checkbox) {
    checkbox.addEventListener('change', updateBuaAn);
  });
  
  // Load giá trị ban đầu
  updateBuaAn();
});
</script>

<?php
$content = ob_get_clean();
include './views/admin/layout.php';
?>
