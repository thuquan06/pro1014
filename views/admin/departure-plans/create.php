<?php
/**
 * Tạo Lịch khởi hành - Modern Interface
 * UC-Departure-Plan: Tạo lịch khởi hành mới
 */

function safe_html($value) {
    return htmlentities($value ?? '', ENT_QUOTES, 'UTF-8');
}
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
    Tạo Lịch khởi hành mới
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
               value="<?= safe_html($_POST['ngay_khoi_hanh'] ?? ($selectedTour && !empty($selectedTour['ngayxuatphat']) ? date('Y-m-d', strtotime($selectedTour['ngayxuatphat'])) : '')) ?>" 
               required>
        <span class="help-text">Chọn ngày khởi hành (sẽ tự động điền từ ngày xuất phát của tour)</span>
      </div>

      <div class="form-group-modern">
        <label>
          Ngày kết thúc
        </label>
        <input type="date" 
               name="ngay_ket_thuc" 
               id="ngay_ket_thuc"
               value="<?= safe_html($_POST['ngay_ket_thuc'] ?? ($selectedTour && !empty($selectedTour['ngayve']) ? date('Y-m-d', strtotime($selectedTour['ngayve'])) : '')) ?>">
        <span class="help-text">Ngày kết thúc tour (sẽ tự động tính nếu có số ngày)</span>
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
               value="<?= safe_html($_POST['gio_khoi_hanh'] ?? ($selectedTour && !empty($selectedTour['giodi']) ? substr($selectedTour['giodi'], 0, 5) : '')) ?>" 
               required>
        <span class="help-text">Chọn giờ khởi hành (sẽ tự động điền từ giờ xuất phát của tour)</span>
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
               value="<?= safe_html($_POST['phuong_tien'] ?? ($selectedTour && !empty($selectedTour['phuongtien']) ? $selectedTour['phuongtien'] : '')) ?>" 
               placeholder="Ví dụ: Xe khách, Máy bay"
               required>
        <span class="help-text">Phương tiện di chuyển (sẽ tự động điền từ tour nếu có)</span>
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

  <!-- Form Actions -->
  <div class="form-actions">
    <a href="<?= BASE_URL ?>?act=admin-departure-plans" class="btn-cancel">
      <i class="fas fa-times"></i>
      Hủy
    </a>
    <button type="submit" class="btn-submit">
      <i class="fas fa-save"></i>
      Tạo lịch khởi hành
    </button>
  </div>
</form>

<script>
function loadTourDepartureDate() {
  const tourSelect = document.getElementById('id_tour');
  const ngayKhoiHanhInput = document.getElementById('ngay_khoi_hanh');
  const ngayKetThucInput = document.getElementById('ngay_ket_thuc');
  const gioKhoiHanhInput = document.getElementById('gio_khoi_hanh');
  const phuongTienInput = document.getElementById('phuong_tien');
  const soChoInput = document.getElementById('so_cho');
  const giaNguoiLonInput = document.getElementById('gia_nguoi_lon');
  const giaTreEmInput = document.getElementById('gia_tre_em');
  const giaTreNhoInput = document.getElementById('gia_tre_nho');
  
  if (!tourSelect || !ngayKhoiHanhInput) {
    return;
  }
  
  const selectedOption = tourSelect.options[tourSelect.selectedIndex];
  const ngayXuatPhat = selectedOption.getAttribute('data-ngay-xuat-phat');
  const ngayKetThuc = selectedOption.getAttribute('data-ngay-ket-thuc');
  const soNgay = selectedOption.getAttribute('data-so-ngay');
  const gioXuatPhat = selectedOption.getAttribute('data-gio-xuat-phat');
  const phuongTien = selectedOption.getAttribute('data-phuong-tien');
  const soCho = selectedOption.getAttribute('data-so-cho');
  const giaNguoiLon = selectedOption.getAttribute('data-gia-nguoi-lon');
  const giaTreEm = selectedOption.getAttribute('data-gia-tre-em');
  const giaTreNho = selectedOption.getAttribute('data-gia-tre-nho');
  
  // Xử lý ngày khởi hành
  if (ngayXuatPhat && ngayXuatPhat.trim() !== '') {
    // Tự động điền ngày xuất phát vào field ngày khởi hành
    ngayKhoiHanhInput.value = ngayXuatPhat;
    
    // Cập nhật help text để thông báo đã tự động điền
    const helpTextNgay = ngayKhoiHanhInput.nextElementSibling;
    if (helpTextNgay && helpTextNgay.classList.contains('help-text')) {
      const originalText = helpTextNgay.textContent || 'Chọn ngày khởi hành';
      helpTextNgay.textContent = 'Đã tự động điền từ ngày xuất phát của tour';
      helpTextNgay.style.color = '#10b981';
      
      // Reset về text gốc sau 3 giây
      setTimeout(() => {
        helpTextNgay.textContent = originalText;
        helpTextNgay.style.color = '';
      }, 3000);
    }
  } else {
    // Nếu tour không có ngày xuất phát, xóa giá trị
    ngayKhoiHanhInput.value = '';
    
    // Cập nhật help text để cảnh báo
    const helpTextNgay = ngayKhoiHanhInput.nextElementSibling;
    if (helpTextNgay && helpTextNgay.classList.contains('help-text')) {
      helpTextNgay.textContent = 'Tour này chưa có ngày xuất phát. Vui lòng nhập thủ công.';
      helpTextNgay.style.color = '#ef4444';
      
      setTimeout(() => {
        helpTextNgay.textContent = 'Chọn ngày khởi hành';
        helpTextNgay.style.color = '';
      }, 3000);
    }
  }
  
  // Xử lý giờ khởi hành
  if (gioKhoiHanhInput) {
    if (gioXuatPhat && gioXuatPhat.trim() !== '') {
      // Tự động điền giờ xuất phát vào field giờ khởi hành
      gioKhoiHanhInput.value = gioXuatPhat;
      
      // Cập nhật help text để thông báo đã tự động điền
      const helpTextGio = gioKhoiHanhInput.nextElementSibling;
      if (helpTextGio && helpTextGio.classList.contains('help-text')) {
        const originalText = helpTextGio.textContent || 'Chọn giờ khởi hành';
        helpTextGio.textContent = 'Đã tự động điền từ giờ xuất phát của tour';
        helpTextGio.style.color = '#10b981';
        
        // Reset về text gốc sau 3 giây
        setTimeout(() => {
          helpTextGio.textContent = originalText;
          helpTextGio.style.color = '';
        }, 3000);
      }
    } else {
      // Nếu tour không có giờ xuất phát, xóa giá trị
      gioKhoiHanhInput.value = '';
      
      // Cập nhật help text để cảnh báo
      const helpTextGio = gioKhoiHanhInput.nextElementSibling;
      if (helpTextGio && helpTextGio.classList.contains('help-text')) {
        helpTextGio.textContent = 'Tour này chưa có giờ xuất phát. Vui lòng nhập thủ công.';
        helpTextGio.style.color = '#ef4444';
        
        setTimeout(() => {
          helpTextGio.textContent = 'Chọn giờ khởi hành';
          helpTextGio.style.color = '';
        }, 3000);
      }
    }
  }
  
  // Xử lý phương tiện
  if (phuongTienInput) {
    if (phuongTien && phuongTien.trim() !== '') {
      // Tự động điền phương tiện từ tour
      phuongTienInput.value = phuongTien;
      
      // Cập nhật help text để thông báo đã tự động điền
      const helpTextPhuongTien = phuongTienInput.nextElementSibling;
      if (helpTextPhuongTien && helpTextPhuongTien.classList.contains('help-text')) {
        const originalText = helpTextPhuongTien.textContent || 'Phương tiện di chuyển';
        helpTextPhuongTien.textContent = 'Đã tự động điền từ phương tiện của tour';
        helpTextPhuongTien.style.color = '#10b981';
        
        // Reset về text gốc sau 3 giây
        setTimeout(() => {
          helpTextPhuongTien.textContent = originalText;
          helpTextPhuongTien.style.color = '';
        }, 3000);
      }
    } else {
      // Nếu tour không có phương tiện, xóa giá trị
      phuongTienInput.value = '';
      
      // Cập nhật help text để cảnh báo
      const helpTextPhuongTien = phuongTienInput.nextElementSibling;
      if (helpTextPhuongTien && helpTextPhuongTien.classList.contains('help-text')) {
        helpTextPhuongTien.textContent = 'Tour này chưa có phương tiện. Vui lòng nhập thủ công.';
        helpTextPhuongTien.style.color = '#ef4444';
        
        setTimeout(() => {
          helpTextPhuongTien.textContent = 'Phương tiện di chuyển (sẽ tự động điền từ tour nếu có)';
          helpTextPhuongTien.style.color = '';
        }, 3000);
      }
    }
  }

  // Xử lý ngày kết thúc
  if (ngayKetThucInput) {
    if (ngayKetThuc && ngayKetThuc.trim() !== '') {
      ngayKetThucInput.value = ngayKetThuc;
    } else if (ngayXuatPhat && ngayXuatPhat.trim() !== '' && soNgay && parseInt(soNgay) > 0) {
      // Tính ngày kết thúc từ ngày khởi hành + số ngày
      const startDate = new Date(ngayXuatPhat);
      startDate.setDate(startDate.getDate() + parseInt(soNgay) - 1);
      const endDateStr = startDate.toISOString().split('T')[0];
      ngayKetThucInput.value = endDateStr;
    }
  }

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

// Tự động load ngày xuất phát khi trang được tải (nếu đã có tour được chọn)
document.addEventListener('DOMContentLoaded', function() {
  const tourSelect = document.getElementById('id_tour');
  if (tourSelect && tourSelect.value) {
    loadTourDepartureDate();
  }
  
  // Lắng nghe sự kiện thay đổi ngày khởi hành để tự động tính ngày kết thúc
  const ngayKhoiHanhInput = document.getElementById('ngay_khoi_hanh');
  if (ngayKhoiHanhInput) {
    ngayKhoiHanhInput.addEventListener('change', function() {
      const tourSelect = document.getElementById('id_tour');
      const ngayKetThucInput = document.getElementById('ngay_ket_thuc');
      if (tourSelect && tourSelect.value && ngayKetThucInput) {
        const selectedOption = tourSelect.options[tourSelect.selectedIndex];
        const soNgay = selectedOption.getAttribute('data-so-ngay');
        const ngayKhoiHanh = this.value;
        
        if (soNgay && parseInt(soNgay) > 0 && ngayKhoiHanh) {
          const startDate = new Date(ngayKhoiHanh);
          startDate.setDate(startDate.getDate() + parseInt(soNgay) - 1);
          const endDateStr = startDate.toISOString().split('T')[0];
          if (!ngayKetThucInput.value || ngayKetThucInput.value === '') {
            ngayKetThucInput.value = endDateStr;
          }
        }
      }
    });
  }
});
</script>
