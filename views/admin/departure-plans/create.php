<?php
/**
 * Tạo Lịch khởi hành - Modern Interface
 * UC-Departure-Plan: Tạo lịch khởi hành mới
 */

function safe_html($value) {
    return htmlentities($value ?? '', ENT_QUOTES, 'UTF-8');
}

$guides = $guides ?? [];
$tours = $tours ?? [];
$tourId = $tourId ?? null;
$error = $error ?? null;
?>

<style>
.departure-form-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
}

.departure-form-title {
  font-size: 28px;
  font-weight: 700;
  color: var(--text-dark);
  margin: 0;
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
  grid-template-columns: repeat(2, 1fr);
  gap: 20px;
  margin-bottom: 20px;
}

.form-group-modern {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.form-group-modern.full-width {
  grid-column: 1 / -1;
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

.form-group-modern input,
.form-group-modern select,
.form-group-modern textarea {
  padding: 12px 16px;
  border: 1px solid var(--border);
  border-radius: 8px;
  font-size: 14px;
  transition: all 0.2s;
  font-family: inherit;
}

.form-group-modern input:focus,
.form-group-modern select:focus,
.form-group-modern textarea:focus {
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
  border-color: var(--text-light);
}

.alert {
  padding: 12px 16px;
  border-radius: 8px;
  margin-bottom: 20px;
  display: flex;
  align-items: center;
  gap: 8px;
}

.alert-error {
  background: #fee2e2;
  color: #991b1b;
  border: 1px solid #fecaca;
}

.alert-success {
  background: #d1fae5;
  color: #065f46;
  border: 1px solid #a7f3d0;
}

@media (max-width: 768px) {
  .form-row {
    grid-template-columns: 1fr;
  }
}
</style>

<!-- Page Header -->
<div class="departure-form-header">
  <h1 class="departure-form-title">
    <i class="fas fa-calendar-plus" style="color: var(--primary);"></i>
    Tạo lịch trình mới
  </h1>
</div>

<?php if (isset($error)): ?>
  <div class="alert alert-error">
    <i class="fas fa-exclamation-circle"></i>
    <?= safe_html($error) ?>
  </div>
<?php endif; ?>

<?php if (isset($msg)): ?>
  <div class="alert alert-success">
    <i class="fas fa-check-circle"></i>
    <?= safe_html($msg) ?>
  </div>
<?php endif; ?>

<!-- Form -->
<form method="POST" action="<?= BASE_URL ?>?act=admin-departure-plan-create">
  <div class="form-card">
    <div class="card-header">
      <i class="fas fa-info-circle"></i>
      <h3>Thông tin cơ bản</h3>
    </div>

    <div class="form-row">
      <div class="form-group-modern">
        <label>
          Tour <span class="required">*</span>
        </label>
        <select name="id_tour" id="id_tour" required onchange="loadTourDepartureDate()">
          <option value="">-- Chọn tour --</option>
          <?php 
          $selectedTourId = $_GET['id_tour'] ?? ($_POST['id_tour'] ?? null);
          $selectedTour = null;
          foreach ($tours as $tour): 
            if ($selectedTourId && $selectedTourId == $tour['id_goi']) {
              $selectedTour = $tour;
            }
            // Format ngày xuất phát cho data attribute (YYYY-MM-DD)
            $ngayXuatPhatFormatted = '';
            if (!empty($tour['ngayxuatphat'])) {
              $timestamp = strtotime($tour['ngayxuatphat']);
              if ($timestamp) {
                $ngayXuatPhatFormatted = date('Y-m-d', $timestamp);
              }
            }
            // Format giờ xuất phát cho data attribute (HH:MM)
            $gioXuatPhatFormatted = '';
            if (!empty($tour['giodi'])) {
              // giodi có thể là TIME format (HH:MM:SS) hoặc string
              $gioXuatPhatFormatted = substr($tour['giodi'], 0, 5); // Lấy HH:MM
            }
            // Lấy phương tiện từ tour
            $phuongTien = !empty($tour['phuongtien']) ? htmlspecialchars($tour['phuongtien'], ENT_QUOTES, 'UTF-8') : '';
            // Lấy số ngày, số chỗ, giá từ tour
            $soNgay = !empty($tour['songay']) ? (int)$tour['songay'] : '';
            $soCho = !empty($tour['socho']) ? (int)$tour['socho'] : '';
            $giaNguoiLon = !empty($tour['giagoi']) ? (float)$tour['giagoi'] : '';
            $giaTreEm = !empty($tour['giatreem']) ? (float)$tour['giatreem'] : '';
            $giaTreNho = !empty($tour['giatrenho']) ? (float)$tour['giatrenho'] : '';
            // Ngày kết thúc (nếu có ngayve)
            $ngayKetThucFormatted = '';
            if (!empty($tour['ngayve'])) {
              $timestamp = strtotime($tour['ngayve']);
              if ($timestamp) {
                $ngayKetThucFormatted = date('Y-m-d', $timestamp);
              }
            }
          ?>
            <option value="<?= $tour['id_goi'] ?>" 
                    data-ngay-xuat-phat="<?= $ngayXuatPhatFormatted ?>"
                    data-ngay-ket-thuc="<?= $ngayKetThucFormatted ?>"
                    data-so-ngay="<?= $soNgay ?>"
                    data-gio-xuat-phat="<?= $gioXuatPhatFormatted ?>"
                    data-phuong-tien="<?= $phuongTien ?>"
                    data-so-cho="<?= $soCho ?>"
                    data-gia-nguoi-lon="<?= $giaNguoiLon ?>"
                    data-gia-tre-em="<?= $giaTreEm ?>"
                    data-gia-tre-nho="<?= $giaTreNho ?>"
                    <?= ($selectedTourId && $selectedTourId == $tour['id_goi']) ? 'selected' : '' ?>>
              <?= safe_html($tour['tengoi']) ?> 
              <?php if (!empty($tour['ngayxuatphat'])): ?>
                (<?= date('d/m/Y', strtotime($tour['ngayxuatphat'])) ?>)
              <?php endif; ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-group-modern">
        <label>
          Trạng thái <span class="required">*</span>
        </label>
        <select name="trang_thai" required>
          <option value="1" <?= (!isset($_POST['trang_thai']) || $_POST['trang_thai'] == '1') ? 'selected' : '' ?>>
            Mở bán
          </option>
          <option value="0" <?= (isset($_POST['trang_thai']) && $_POST['trang_thai'] == '0') ? 'selected' : '' ?>>
            Đóng
          </option>
          <option value="2" <?= (isset($_POST['trang_thai']) && $_POST['trang_thai'] == '2') ? 'selected' : '' ?>>
            Hết chỗ
          </option>
          <option value="3" <?= (isset($_POST['trang_thai']) && $_POST['trang_thai'] == '3') ? 'selected' : '' ?>>
            Gần đầy
          </option>
        </select>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group-modern">
        <label>
          Ngày khởi hành <span class="required">*</span>
        </label>
        <input type="date" 
               name="ngay_khoi_hanh" 
               id="ngay_khoi_hanh"
               value="<?= safe_html($_POST['ngay_khoi_hanh'] ?? '') ?>" 
               required>
      </div>

      <div class="form-group-modern">
        <label>
          Ngày kết thúc
        </label>
        <input type="date" 
               name="ngay_ket_thuc" 
               id="ngay_ket_thuc"
               value="<?= safe_html($_POST['ngay_ket_thuc'] ?? '') ?>">
      </div>
    </div>

    <div class="form-row">
      <div class="form-group-modern">
        <label>
          Giờ khởi hành <span class="required">*</span>
        </label>
        <input type="time" 
               name="gio_khoi_hanh" 
               id="gio_khoi_hanh"
               value="<?= safe_html($_POST['gio_khoi_hanh'] ?? '') ?>" 
               required>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group-modern">
        <label>
          Giờ tập trung <span class="required">*</span>
        </label>
        <input type="time" 
               name="gio_tap_trung" 
               value="<?= safe_html($_POST['gio_tap_trung'] ?? '') ?>" 
               required>
        <span class="help-text">Chọn giờ tập trung (ví dụ: 07:30)</span>
      </div>

      <div class="form-group-modern">
        <label>
          Điểm tập trung <span class="required">*</span>
        </label>
        <input type="text" 
               name="diem_tap_trung" 
               value="<?= safe_html($_POST['diem_tap_trung'] ?? '') ?>" 
               placeholder="Ví dụ: Sân bay Nội Bài, Hà Nội"
               required>
        <span class="help-text">Địa điểm tập trung trước khi khởi hành</span>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group-modern">
        <label>
          Số chỗ tối đa <span class="required">*</span>
        </label>
        <input type="number" 
               name="so_cho" 
               id="so_cho"
               value="<?= safe_html($_POST['so_cho'] ?? ($selectedTour && !empty($selectedTour['socho']) ? $selectedTour['socho'] : '')) ?>" 
               placeholder="Ví dụ: 30"
               min="1"
               required
               onchange="calculateRemainingSeats()">
        <span class="help-text">Tổng số chỗ tối đa cho lịch khởi hành này</span>
      </div>

      <div class="form-group-modern">
        <label>
          Số chỗ đã đặt
        </label>
        <input type="number" 
               name="so_cho_da_dat" 
               id="so_cho_da_dat"
               value="<?= safe_html($_POST['so_cho_da_dat'] ?? '0') ?>" 
               placeholder="0"
               min="0"
               onchange="calculateRemainingSeats()">
        <span class="help-text">Số chỗ đã có người đặt</span>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group-modern">
        <label>
          Số chỗ còn lại
        </label>
        <input type="number" 
               name="so_cho_con_lai" 
               id="so_cho_con_lai"
               value="<?= safe_html($_POST['so_cho_con_lai'] ?? '') ?>" 
               placeholder="Tự động tính"
               min="0"
               readonly>
        <span class="help-text">Số chỗ còn trống (tự động = Số chỗ tối đa - Số chỗ đã đặt)</span>
      </div>

      <div class="form-group-modern">
        <label>
          Phương tiện <span class="required">*</span>
        </label>
        <input type="text" 
               name="phuong_tien" 
               id="phuong_tien"
               value="<?= safe_html($_POST['phuong_tien'] ?? '') ?>" 
               placeholder="Ví dụ: Xe khách, Máy bay"
               required>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group-modern">
        <label>
          Giá người lớn
        </label>
        <input type="number" 
               name="gia_nguoi_lon" 
               id="gia_nguoi_lon"
               value="<?= safe_html($_POST['gia_nguoi_lon'] ?? ($selectedTour && !empty($selectedTour['giagoi']) ? $selectedTour['giagoi'] : '')) ?>" 
               placeholder="Ví dụ: 2000000"
               min="0"
               step="1000">
        <span class="help-text">Giá cho người lớn (sẽ tự động điền từ tour nếu có)</span>
      </div>

      <div class="form-group-modern">
        <label>
          Giá trẻ em
        </label>
        <input type="number" 
               name="gia_tre_em" 
               id="gia_tre_em"
               value="<?= safe_html($_POST['gia_tre_em'] ?? ($selectedTour && !empty($selectedTour['giatreem']) ? $selectedTour['giatreem'] : '')) ?>" 
               placeholder="Ví dụ: 1500000"
               min="0"
               step="1000">
        <span class="help-text">Giá cho trẻ em (sẽ tự động điền từ tour nếu có)</span>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group-modern">
        <label>
          Giá trẻ nhỏ
        </label>
        <input type="number" 
               name="gia_tre_nho" 
               id="gia_tre_nho"
               value="<?= safe_html($_POST['gia_tre_nho'] ?? ($selectedTour && !empty($selectedTour['giatrenho']) ? $selectedTour['giatrenho'] : '')) ?>" 
               placeholder="Ví dụ: 1000000"
               min="0"
               step="1000">
        <span class="help-text">Giá cho trẻ nhỏ (sẽ tự động điền từ tour nếu có)</span>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group-modern full-width">
        <label>
          Ghi chú nội bộ
        </label>
        <textarea name="ghi_chu" 
                  placeholder="Nhập các ghi chú nội bộ..."><?= safe_html($_POST['ghi_chu'] ?? '') ?></textarea>
        <span class="help-text">Ghi chú nội bộ cho nhân viên</span>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group-modern full-width">
        <label>
          Ghi chú vận hành
        </label>
        <textarea name="ghi_chu_van_hanh" 
                  placeholder="Nhập các ghi chú về vận hành, lưu ý đặc biệt..."><?= safe_html($_POST['ghi_chu_van_hanh'] ?? '') ?></textarea>
        <span class="help-text">Các ghi chú về vận hành, lưu ý đặc biệt cho tour này</span>
      </div>
      </div>
    </div>

  <!-- Phân công Hướng dẫn viên -->
  <div class="form-card">
    <div class="card-header">
      <i class="fas fa-user-tie"></i>
      <h3>Phân công Hướng dẫn viên</h3>
    </div>
    
    <div id="hdv-assignments-container">
      <!-- HDV assignments will be added here -->
    </div>
    
    <div style="margin-top: 16px;">
      <button type="button" id="add-hdv-btn" class="btn btn-primary" style="padding: 10px 20px;">
        <i class="fas fa-plus"></i> Thêm HDV
      </button>
    </div>
  </div>

  <!-- Lịch trình tour đã gỡ, vẫn gửi chuongtrinh rỗng để tránh lỗi backend -->
  <input type="hidden" name="chuongtrinh" value="">

  <!-- Form Actions -->
  <div class="form-actions">
    <a href="<?= BASE_URL ?>?act=admin-departure-plans" class="btn-cancel">
      <i class="fas fa-times"></i>
      Hủy
    </a>
    <button type="submit" class="btn-submit">
      <i class="fas fa-save"></i>
      Tạo lịch trình
    </button>
  </div>
</form>

<script>
function loadTourDepartureDate() {
  const tourSelect = document.getElementById('id_tour');
  const soChoInput = document.getElementById('so_cho');
  const giaNguoiLonInput = document.getElementById('gia_nguoi_lon');
  const giaTreEmInput = document.getElementById('gia_tre_em');
  const giaTreNhoInput = document.getElementById('gia_tre_nho');
  
  if (!tourSelect) {
    return;
  }
  
  const selectedOption = tourSelect.options[tourSelect.selectedIndex];
  const soCho = selectedOption.getAttribute('data-so-cho');
  const giaNguoiLon = selectedOption.getAttribute('data-gia-nguoi-lon');
  const giaTreEm = selectedOption.getAttribute('data-gia-tre-em');
  const giaTreNho = selectedOption.getAttribute('data-gia-tre-nho');
  
  


  // Xử lý số chỗ
  if (soChoInput && soCho && soCho !== '') {
    soChoInput.value = soCho;
    calculateRemainingSeats();
  }

  // Xử lý giá người lớn
  if (giaNguoiLonInput && giaNguoiLon && giaNguoiLon !== '') {
    giaNguoiLonInput.value = giaNguoiLon;
  }

  // Xử lý giá trẻ em
  if (giaTreEmInput && giaTreEm && giaTreEm !== '') {
    giaTreEmInput.value = giaTreEm;
  }

  // Xử lý giá trẻ nhỏ
  if (giaTreNhoInput && giaTreNho && giaTreNho !== '') {
    giaTreNhoInput.value = giaTreNho;
  }
}

// Tính số chỗ còn lại
function calculateRemainingSeats() {
  const soChoInput = document.getElementById('so_cho');
  const soChoDaDatInput = document.getElementById('so_cho_da_dat');
  const soChoConLaiInput = document.getElementById('so_cho_con_lai');
  
  if (!soChoInput || !soChoDaDatInput || !soChoConLaiInput) {
    return;
  }
  
  const soCho = parseInt(soChoInput.value) || 0;
  const soChoDaDat = parseInt(soChoDaDatInput.value) || 0;
  const soChoConLai = Math.max(0, soCho - soChoDaDat);
  
  soChoConLaiInput.value = soChoConLai > 0 ? soChoConLai : '';
}

</script>

<script>
// HDV Assignment Management
let hdvAssignmentCount = 0;
const selectedHdvIds = new Set();

// Generate HDV options HTML from PHP
const hdvOptionsHtml = <?php 
$options = [];
if (!empty($guides)) {
    foreach ($guides as $guide) {
        $options[] = '<option value="' . $guide['id'] . '" data-name="' . safe_html($guide['ho_ten']) . '">' . 
                     safe_html($guide['ho_ten']) . ' - ' . safe_html($guide['so_dien_thoai'] ?? '') . 
                     '</option>';
    }
}
echo json_encode(implode('', $options), JSON_HEX_QUOT | JSON_HEX_APOS | JSON_UNESCAPED_UNICODE);
?>;

function addHdvAssignment() {
    hdvAssignmentCount++;
    const container = document.getElementById('hdv-assignments-container');
    
    const hdvRow = document.createElement('div');
    hdvRow.className = 'hdv-assignment-row';
    hdvRow.style.cssText = 'display: grid; grid-template-columns: 2fr 1.5fr 1fr 1fr auto; gap: 12px; align-items: end; margin-bottom: 16px; padding: 16px; background: #f9fafb; border-radius: 8px;';
    hdvRow.id = `hdv-row-${hdvAssignmentCount}`;
    
    hdvRow.innerHTML = `
        <div class="form-group-modern">
            <label>Hướng dẫn viên <span class="required">*</span></label>
            <select name="hdv_assignments[${hdvAssignmentCount}][id_hdv]" class="hdv-select" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                <option value="">Chọn HDV</option>
                ${hdvOptionsHtml}
            </select>
        </div>
        <div class="form-group-modern">
            <label>Vai trò <span class="required">*</span></label>
            <select name="hdv_assignments[${hdvAssignmentCount}][vai_tro]" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                <option value="HDV chính">HDV chính</option>
                <option value="HDV phụ">HDV phụ</option>
                <option value="Trợ lý">Trợ lý</option>
            </select>
        </div>
        <div class="form-group-modern">
            <label>Lương</label>
            <input type="number" name="hdv_assignments[${hdvAssignmentCount}][luong]" placeholder="VNĐ" min="0" step="1000" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
        </div>
        <div class="form-group-modern">
            <label>Ghi chú</label>
            <input type="text" name="hdv_assignments[${hdvAssignmentCount}][ghi_chu]" placeholder="Ghi chú..." style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
        </div>
        <div>
            <button type="button" class="btn-remove-hdv" onclick="removeHdvAssignment(${hdvAssignmentCount})" style="padding: 10px 15px; background: #ef4444; color: white; border: none; border-radius: 6px; cursor: pointer;">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    `;
    
    container.appendChild(hdvRow);
    
    // Update selected HDV tracking
    const select = hdvRow.querySelector('.hdv-select');
    select.addEventListener('change', function() {
        updateHdvDropdowns();
    });
    
    updateHdvDropdowns();
}

function removeHdvAssignment(rowId) {
    const row = document.getElementById(`hdv-row-${rowId}`);
    if (row) {
        const select = row.querySelector('.hdv-select');
        if (select && select.value) {
            selectedHdvIds.delete(parseInt(select.value));
        }
        row.remove();
        updateHdvDropdowns();
    }
}

function updateHdvDropdowns() {
    // Collect all selected HDV IDs
    selectedHdvIds.clear();
    document.querySelectorAll('.hdv-select').forEach(select => {
        if (select.value) {
            selectedHdvIds.add(parseInt(select.value));
        }
    });
    
    // Update all dropdowns to disable selected HDVs
    document.querySelectorAll('.hdv-select').forEach(select => {
        const currentValue = select.value;
        Array.from(select.options).forEach(option => {
            if (option.value && option.value !== currentValue) {
                const hdvId = parseInt(option.value);
                if (selectedHdvIds.has(hdvId)) {
                    option.disabled = true;
                    const originalName = option.getAttribute('data-name') || '';
                    option.textContent = originalName + ' (Đã chọn)';
                } else {
                    option.disabled = false;
                    const originalName = option.getAttribute('data-name') || '';
                    option.textContent = originalName;
                }
            }
        });
    });
}

// Khởi tạo khi DOM ready
document.addEventListener('DOMContentLoaded', function() {
    // HDV Assignment - Thêm HDV
    const addHdvBtn = document.getElementById('add-hdv-btn');
    if (addHdvBtn) {
        addHdvBtn.addEventListener('click', function() {
            addHdvAssignment();
        });
    }
    
    // Itinerary Builder - Thêm ngày
    const addDayBtn = document.getElementById('add-day-btn');
    if (addDayBtn) {
        addDayBtn.addEventListener('click', function() {
            addDay();
        });
    }
    
    // Load dữ liệu cũ nếu có (khi có lỗi validation)
    <?php if (!empty($_POST['chuongtrinh'])): ?>
    const existingItinerary = <?= json_encode($_POST['chuongtrinh'], JSON_HEX_QUOT | JSON_HEX_APOS | JSON_UNESCAPED_UNICODE) ?>;
    if (existingItinerary && existingItinerary.trim()) {
        parseAndLoadExistingItinerary(existingItinerary);
    }
    <?php endif; ?>
    
    // Nếu không có dữ liệu cũ, thêm 1 ngày mặc định
    const daysContainer = document.getElementById('days-container');
    if (daysContainer && daysContainer.children.length === 0) {
        addDay();
    }
    
    // Cập nhật hidden field trước khi submit
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            // Cập nhật tất cả editor instances
            for (var instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }
            
            // Build itinerary HTML từ các ngày
            const itineraryHTML = buildItineraryHTML();
            const hiddenField = document.getElementById('chuongtrinh-hidden');
            if (hiddenField) {
                hiddenField.value = itineraryHTML;
            }
        });
    }
});
</script>

    