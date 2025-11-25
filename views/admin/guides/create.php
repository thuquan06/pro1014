<?php
/**
 * Tạo/Sửa Hướng dẫn viên - Modern Interface
 * UC-Assign-Guide: Quản lý thông tin HDV
 */

function safe_html($value) {
    return htmlentities($value ?? '', ENT_QUOTES, 'UTF-8');
}

$guide = $guide ?? null;
$error = $error ?? null;

// Parse JSON arrays từ guide nếu có
$kyNang = [];
$tuyenChuyen = [];
$ngonNgu = [];

if ($guide) {
    $guideModel = new GuideModel();
    $kyNang = $guideModel->parseJsonArray($guide['ky_nang'] ?? '[]');
    $tuyenChuyen = $guideModel->parseJsonArray($guide['tuyen_chuyen'] ?? '[]');
    $ngonNgu = $guideModel->parseJsonArray($guide['ngon_ngu'] ?? '[]');
}
?>

<style>
.form-card {
  background: white;
  border: 1px solid var(--border);
  border-radius: 12px;
  padding: 24px;
  margin-bottom: 20px;
}

.form-group-modern {
  display: flex;
  flex-direction: column;
  gap: 8px;
  margin-bottom: 20px;
}

.form-group-modern label {
  font-weight: 600;
  font-size: 14px;
  color: var(--text-dark);
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

.tag-input-container {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
  padding: 12px;
  border: 1px solid var(--border);
  border-radius: 8px;
  min-height: 50px;
}

.tag-item {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 6px 12px;
  background: var(--primary);
  color: white;
  border-radius: 20px;
  font-size: 13px;
  font-weight: 600;
}

.tag-item .remove-tag {
  cursor: pointer;
  font-weight: bold;
}

.tag-input {
  border: none;
  outline: none;
  flex: 1;
  min-width: 100px;
  padding: 0;
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
}

.btn-submit:hover {
  background: #1e40af;
  transform: translateY(-2px);
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
}
</style>

<h1 style="font-size: 28px; font-weight: 700; margin-bottom: 24px;">
  <i class="fas fa-user-tie" style="color: var(--primary);"></i>
  <?= $guide ? 'Sửa' : 'Tạo' ?> Hướng dẫn viên
</h1>

<?php if ($error): ?>
  <div style="padding: 16px; background: #fee2e2; color: #991b1b; border-radius: 8px; margin-bottom: 20px;">
    <i class="fas fa-exclamation-circle"></i> <?= safe_html($error) ?>
  </div>
<?php endif; ?>

<form method="POST" action="<?= BASE_URL ?>?act=admin-guide-<?= $guide ? 'edit&id=' . $guide['id'] : 'create' ?>">
  <?php if ($guide): ?>
    <input type="hidden" name="id" value="<?= $guide['id'] ?>">
  <?php endif; ?>
  <div class="form-card">
    <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 20px;">Thông tin cơ bản</h3>
    
    <div class="form-group-modern">
      <label>Họ tên <span style="color: #ef4444;">*</span></label>
      <input type="text" name="ho_ten" value="<?= safe_html($guide['ho_ten'] ?? '') ?>" required>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
      <div class="form-group-modern">
        <label>Email</label>
        <input type="email" name="email" value="<?= safe_html($guide['email'] ?? '') ?>">
      </div>

      <div class="form-group-modern">
        <label>Số điện thoại</label>
        <input type="tel" name="so_dien_thoai" value="<?= safe_html($guide['so_dien_thoai'] ?? '') ?>">
      </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
      <div class="form-group-modern">
        <label>CMND/CCCD</label>
        <input type="text" name="cmnd_cccd" value="<?= safe_html($guide['cmnd_cccd'] ?? '') ?>">
      </div>

      <div class="form-group-modern">
        <label>Kinh nghiệm (năm)</label>
        <input type="number" name="kinh_nghiem" value="<?= $guide['kinh_nghiem'] ?? 0 ?>" min="0">
      </div>
    </div>

    <div class="form-group-modern">
      <label>Địa chỉ</label>
      <textarea name="dia_chi"><?= safe_html($guide['dia_chi'] ?? '') ?></textarea>
    </div>
  </div>

  <div class="form-card">
    <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 20px;">Kỹ năng & Chuyên môn</h3>
    
    <div class="form-group-modern">
      <label>Kỹ năng (Nhấn Enter để thêm)</label>
      <div class="tag-input-container" id="kyNangContainer">
        <?php foreach ($kyNang as $skill): ?>
          <span class="tag-item">
            <?= safe_html($skill) ?>
            <span class="remove-tag" onclick="removeTag(this, 'ky_nang')">×</span>
          </span>
        <?php endforeach; ?>
        <input type="text" class="tag-input" id="kyNangInput" placeholder="VD: Hiking, Swimming">
      </div>
      <input type="hidden" name="ky_nang" id="kyNangHidden" value="<?= htmlspecialchars(json_encode($kyNang, JSON_UNESCAPED_UNICODE)) ?>">
    </div>

    <div class="form-group-modern">
      <label>Tuyến chuyên (Nhấn Enter để thêm)</label>
      <div class="tag-input-container" id="tuyenChuyenContainer">
        <?php foreach ($tuyenChuyen as $route): ?>
          <span class="tag-item">
            <?= safe_html($route) ?>
            <span class="remove-tag" onclick="removeTag(this, 'tuyen_chuyen')">×</span>
          </span>
        <?php endforeach; ?>
        <input type="text" class="tag-input" id="tuyenChuyenInput" placeholder="VD: Miền Bắc, Miền Trung">
      </div>
      <input type="hidden" name="tuyen_chuyen" id="tuyenChuyenHidden" value="<?= htmlspecialchars(json_encode($tuyenChuyen, JSON_UNESCAPED_UNICODE)) ?>">
    </div>

    <div class="form-group-modern">
      <label>Ngôn ngữ (Nhấn Enter để thêm)</label>
      <div class="tag-input-container" id="ngonNguContainer">
        <?php foreach ($ngonNgu as $lang): ?>
          <span class="tag-item">
            <?= safe_html($lang) ?>
            <span class="remove-tag" onclick="removeTag(this, 'ngon_ngu')">×</span>
          </span>
        <?php endforeach; ?>
        <input type="text" class="tag-input" id="ngonNguInput" placeholder="VD: Tiếng Việt, English, 中文">
      </div>
      <input type="hidden" name="ngon_ngu" id="ngonNguHidden" value="<?= htmlspecialchars(json_encode($ngonNgu, JSON_UNESCAPED_UNICODE)) ?>">
    </div>
  </div>

  <div class="form-card">
    <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 20px;">Khác</h3>
    
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
      <div class="form-group-modern">
        <label>Đánh giá (0-5)</label>
        <input type="number" name="danh_gia" value="<?= $guide['danh_gia'] ?? 0 ?>" step="0.1" min="0" max="5">
      </div>

      <div class="form-group-modern">
        <label>Trạng thái</label>
        <select name="trang_thai">
          <option value="1" <?= (!isset($guide['trang_thai']) || $guide['trang_thai'] == 1) ? 'selected' : '' ?>>Hoạt động</option>
          <option value="0" <?= (isset($guide['trang_thai']) && $guide['trang_thai'] == 0) ? 'selected' : '' ?>>Tạm dừng</option>
        </select>
      </div>
    </div>

    <div class="form-group-modern">
      <label>Ghi chú</label>
      <textarea name="ghi_chu"><?= safe_html($guide['ghi_chu'] ?? '') ?></textarea>
    </div>
  </div>

  <div class="form-actions">
    <a href="<?= BASE_URL ?>?act=admin-guides" class="btn-cancel">
      <i class="fas fa-times"></i> Hủy
    </a>
    <button type="submit" class="btn-submit">
      <i class="fas fa-save"></i> <?= $guide ? 'Cập nhật' : 'Tạo' ?> HDV
    </button>
  </div>
</form>

<script>
function addTag(inputId, containerId, hiddenId) {
  const input = document.getElementById(inputId);
  const container = document.getElementById(containerId);
  const hidden = document.getElementById(hiddenId);
  const value = input.value.trim();
  
  if (value) {
    const tags = JSON.parse(hidden.value || '[]');
    if (!tags.includes(value)) {
      tags.push(value);
      hidden.value = JSON.stringify(tags);
      
      const tagElement = document.createElement('span');
      tagElement.className = 'tag-item';
      tagElement.innerHTML = value + ' <span class="remove-tag" onclick="removeTag(this, \'' + containerId.replace('Container', '') + '\')">×</span>';
      container.insertBefore(tagElement, input);
    }
    input.value = '';
  }
}

function removeTag(element, type) {
  const tag = element.parentElement;
  const hidden = document.getElementById(type + 'Hidden');
  const tags = JSON.parse(hidden.value || '[]');
  const value = tag.textContent.replace('×', '').trim();
  const index = tags.indexOf(value);
  if (index > -1) {
    tags.splice(index, 1);
    hidden.value = JSON.stringify(tags);
  }
  tag.remove();
}

document.getElementById('kyNangInput').addEventListener('keypress', function(e) {
  if (e.key === 'Enter') {
    e.preventDefault();
    addTag('kyNangInput', 'kyNangContainer', 'kyNangHidden');
  }
});

document.getElementById('tuyenChuyenInput').addEventListener('keypress', function(e) {
  if (e.key === 'Enter') {
    e.preventDefault();
    addTag('tuyenChuyenInput', 'tuyenChuyenContainer', 'tuyenChuyenHidden');
  }
});

document.getElementById('ngonNguInput').addEventListener('keypress', function(e) {
  if (e.key === 'Enter') {
    e.preventDefault();
    addTag('ngonNguInput', 'ngonNguContainer', 'ngonNguHidden');
  }
});
</script>

