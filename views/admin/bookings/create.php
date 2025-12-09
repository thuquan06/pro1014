<?php
/**
 * Tạo Booking - Modern Interface
 * UC-Create-Booking: Admin tạo đơn booking
 */

function safe_html($value) {
    return htmlentities($value ?? '', ENT_QUOTES, 'UTF-8');
}

$tours = $tours ?? [];
$departurePlans = $departurePlans ?? [];
$selectedTourId = $selectedTourId ?? null;
$guides = $guides ?? [];
$errors = $errors ?? [];
?>

<style>
.booking-form-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
}

.booking-form-title {
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

.form-group-modern .error-text {
  font-size: 12px;
  color: #ef4444;
  margin-top: 4px;
}

.total-price-display {
  background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
  padding: 24px;
  border-radius: 12px;
  text-align: center;
  margin-top: 20px;
}

.total-price-display .label {
  font-size: 14px;
  color: #065f46;
  font-weight: 600;
  margin-bottom: 8px;
}

.total-price-display .value {
  font-size: 36px;
  font-weight: 700;
  color: #059669;
}

/* Payment Info Card Styling */
.payment-info-card {
  background: white;
  border: 1px solid var(--border);
}

.payment-info-card .card-header {
  margin: 0;
  padding: 0;
  padding-bottom: 16px;
  border-bottom: 2px solid var(--bg-light);
}

.payment-info-card .card-header i {
  color: var(--primary);
  font-size: 20px;
  background: none;
  padding: 0;
  border-radius: 0;
}

.payment-info-card .card-header h3 {
  color: var(--text-dark);
  font-size: 18px;
  font-weight: 700;
}

.total-price-display-modern {
  padding: 14px 18px;
  border: 2px solid #e5e7eb;
  border-radius: 10px;
  text-align: left;
  background: #ffffff;
  transition: all 0.3s ease;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
  min-height: 52px;
  display: flex;
  align-items: center;
}

.total-price-display-modern:hover {
  border-color: #93c5fd;
}

.total-price-display-modern .price-value {
  color: #1f2937;
  font-size: 16px;
  font-weight: 600;
  letter-spacing: 0.5px;
  width: 100%;
}

.deposit-input {
  padding: 14px 18px !important;
  border: 2px solid #e5e7eb !important;
  border-radius: 10px !important;
  font-size: 16px !important;
  font-weight: 600 !important;
  background: #ffffff !important;
  transition: all 0.3s ease !important;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05) !important;
  min-height: 52px;
  box-sizing: border-box;
}

.deposit-input:focus {
  border-color: #3b82f6 !important;
  box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1), 0 4px 12px rgba(59, 130, 246, 0.15) !important;
  outline: none !important;
  transform: translateY(-1px);
}

.deposit-input:hover {
  border-color: #93c5fd !important;
}

.payment-info-card .form-group-modern label {
  color: #1e40af;
  font-weight: 700;
  font-size: 15px;
  margin-bottom: 10px;
  display: flex;
  align-items: center;
  gap: 8px;
}

.payment-info-card .form-group-modern label i {
  color: #3b82f6;
  font-size: 16px;
}

.payment-info-card .help-text {
  color: #6b7280;
  font-size: 13px;
  margin-top: 6px;
  display: flex;
  align-items: center;
  gap: 6px;
}

.payment-info-card .help-text i {
  color: #9ca3af;
  font-size: 12px;
}

.seat-info {
  background: #f0f9ff;
  padding: 12px 16px;
  border-radius: 8px;
  border-left: 4px solid #3b82f6;
  margin-top: 8px;
  font-size: 13px;
  color: #1e40af;
}

.seat-info.warning {
  background: #fef3c7;
  border-left-color: #f59e0b;
  color: #78350f;
}

.seat-info.danger {
  background: #fee2e2;
  border-left-color: #ef4444;
  color: #991b1b;
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
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  transition: all 0.2s;
}

.btn-cancel:hover {
  background: #f9fafb;
  border-color: #9ca3af;
}
</style>

<div class="booking-form-header">
  <h1 class="booking-form-title">
    <i class="fas fa-plus-circle"></i> Tạo Booking mới
  </h1>
  <a href="<?= BASE_URL ?>?act=admin-bookings" class="btn-cancel">
    <i class="fas fa-arrow-left"></i> Quay lại
  </a>
</div>

<form method="POST" action="<?= BASE_URL ?>?act=admin-booking-create" id="bookingForm">
  <!-- Chọn Tour & Lịch khởi hành -->
  <div class="form-card">
    <div class="card-header">
      <i class="fas fa-map-marked-alt"></i>
      <h3>Chọn Tour & Lịch khởi hành</h3>
    </div>
    
    <div class="form-row">
      <div class="form-group-modern">
        <label>Tour <span class="required">*</span></label>
        <select name="id_tour" id="id_tour" required onchange="loadDeparturePlans()">
          <option value="">-- Chọn tour --</option>
          <?php foreach ($tours as $tour): ?>
            <option value="<?= $tour['id_goi'] ?>" <?= ($selectedTourId == $tour['id_goi']) ? 'selected' : '' ?>>
              <?= safe_html($tour['tengoi'] ?? '') ?> (<?= safe_html($tour['mato'] ?? '') ?>)
            </option>
          <?php endforeach; ?>
        </select>
        <?php if (isset($errors['id_tour'])): ?>
          <div class="error-text"><?= safe_html($errors['id_tour']) ?></div>
        <?php endif; ?>
      </div>
      
      <div class="form-group-modern">
        <label>Lịch khởi hành <span class="required">*</span></label>
        <select name="id_lich_khoi_hanh" id="id_lich_khoi_hanh" required onchange="calculateTotal();">
          <option value="">-- Chọn lịch khởi hành --</option>
          <?php foreach ($departurePlans as $plan): ?>
            <option value="<?= $plan['id'] ?>" 
                    data-gia-nguoi-lon="<?= $plan['gia_nguoi_lon'] ?? 0 ?>"
                    data-gia-tre-em="<?= $plan['gia_tre_em'] ?? 0 ?>"
                    data-gia-tre-nho="<?= $plan['gia_tre_nho'] ?? 0 ?>"
                    data-so-cho-con-lai="<?= $plan['so_cho_con_lai'] ?? 0 ?>">
              <?= safe_html($plan['ngay_khoi_hanh'] ? date('d/m/Y', strtotime($plan['ngay_khoi_hanh'])) : 'N/A') ?>
              <?php if ($plan['so_cho_con_lai'] !== null): ?>
                (Còn <?= $plan['so_cho_con_lai'] ?> chỗ)
              <?php endif; ?>
            </option>
          <?php endforeach; ?>
        </select>
        <div id="seatInfo" class="seat-info" style="display: none;"></div>
        <?php if (isset($errors['id_lich_khoi_hanh'])): ?>
          <div class="error-text"><?= safe_html($errors['id_lich_khoi_hanh']) ?></div>
        <?php endif; ?>
      </div>
      
    </div>
    
    <div class="form-row">
      <div class="form-group-modern full-width">
        <label>Loại booking <span class="required">*</span></label>
        <select name="loai_booking" id="loai_booking" required onchange="toggleGuestList()">
          <option value="">-- Chọn loại booking --</option>
          <?php 
          $bookingTypes = [
            1 => 'Cá nhân',
            2 => 'Gia đình',
            3 => 'Nhóm',
            4 => 'Đoàn'
          ];
          foreach ($bookingTypes as $key => $label): ?>
            <option value="<?= $key ?>" <?= (isset($_POST['loai_booking']) && $_POST['loai_booking'] == $key) ? 'selected' : ($key == 1 ? 'selected' : '') ?>>
              <?= safe_html($label) ?>
            </option>
          <?php endforeach; ?>
        </select>
        <div class="help-text">Chọn loại booking để xác định cách nhập thông tin khách</div>
      </div>
    </div>
  </div>

  <!-- Thông tin khách hàng -->
  <div class="form-card">
    <div class="card-header">
      <i class="fas fa-user"></i>
      <h3>Thông tin khách hàng</h3>
    </div>
    
    <div class="form-row">
      <div class="form-group-modern">
        <label>Họ tên <span class="required">*</span></label>
        <input type="text" 
               name="ho_ten" 
               value="<?= safe_html($_POST['ho_ten'] ?? '') ?>"
               placeholder="Nhập họ tên khách hàng"
               required>
        <?php if (isset($errors['ho_ten'])): ?>
          <div class="error-text"><?= safe_html($errors['ho_ten']) ?></div>
        <?php endif; ?>
      </div>
      
      <div class="form-group-modern">
        <label>Số điện thoại <span class="required">*</span></label>
        <input type="tel" 
               name="so_dien_thoai" 
               id="so_dien_thoai"
               value="<?= safe_html($_POST['so_dien_thoai'] ?? '') ?>"
               placeholder="Nhập số điện thoại (10 số, bắt đầu bằng 0)"
               pattern="[0-9]{10}"
               maxlength="10"
               required>
        <div class="help-text">Ví dụ: 0912345678</div>
        <?php if (isset($errors['so_dien_thoai'])): ?>
          <div class="error-text"><?= safe_html($errors['so_dien_thoai']) ?></div>
        <?php endif; ?>
      </div>
    </div>
    
    <div class="form-row">
      <div class="form-group-modern full-width">
        <label>Email</label>
        <input type="email" 
               name="email" 
               id="email"
               value="<?= safe_html($_POST['email'] ?? '') ?>"
               placeholder="Nhập email (không bắt buộc)">
        <div class="help-text">Ví dụ: example@email.com</div>
      </div>
    </div>
    
    <div class="form-row">
      <div class="form-group-modern full-width">
        <label>Địa chỉ</label>
        <textarea name="dia_chi" 
                  rows="3"
                  placeholder="Nhập địa chỉ khách hàng (không bắt buộc)"><?= safe_html($_POST['dia_chi'] ?? '') ?></textarea>
      </div>
    </div>
  </div>

  <!-- Số lượng khách -->
  <div class="form-card">
    <div class="card-header">
      <i class="fas fa-users"></i>
      <h3>Số lượng khách</h3>
    </div>
    
    <div class="form-row" style="grid-template-columns: repeat(3, 1fr);">
      <div class="form-group-modern">
        <label>Người lớn</label>
        <input type="number" 
               name="so_nguoi_lon" 
               id="so_nguoi_lon"
               value="<?= safe_html($_POST['so_nguoi_lon'] ?? '1') ?>"
               min="0"
               onchange="calculateTotal()">
      </div>
      
      <div class="form-group-modern">
        <label>Trẻ em</label>
        <input type="number" 
               name="so_tre_em" 
               id="so_tre_em"
               value="<?= safe_html($_POST['so_tre_em'] ?? '0') ?>"
               min="0"
               onchange="calculateTotal()">
      </div>
      
      <div class="form-group-modern">
        <label>Trẻ nhỏ</label>
        <input type="number" 
               name="so_tre_nho" 
               id="so_tre_nho"
               value="<?= safe_html($_POST['so_tre_nho'] ?? '0') ?>"
               min="0"
               onchange="calculateTotal()">
      </div>
    </div>
  </div>

  <!-- Danh sách khách (chỉ hiển thị khi chọn Nhóm hoặc Đoàn) -->
  <div class="form-card" id="guestListCard" style="display: none;">
    <div class="card-header">
      <i class="fas fa-list"></i>
      <h3>Danh sách khách</h3>
    </div>
    
    <div class="form-row">
      <div class="form-group-modern full-width">
        <div class="help-text" style="margin-bottom: 16px; padding: 12px; background: #f0f9ff; border-left: 4px solid #3b82f6; border-radius: 4px;">
          <i class="fas fa-info-circle"></i> <strong>Lưu ý:</strong> Vui lòng nhập đầy đủ thông tin cho từng khách. Số lượng khách trong danh sách phải khớp với tổng số khách đã nhập ở trên.
        </div>
      </div>
    </div>
    
    <div id="guestListContainer">
      <!-- Danh sách khách sẽ được tạo động bằng JavaScript -->
    </div>
  </div>

  <!-- Tổng tiền & Số tiền đặt cọc -->
  <div class="form-card payment-info-card">
    <div class="card-header">
      <i class="fas fa-money-bill-wave"></i>
      <h3>Thông tin thanh toán</h3>
    </div>
    
    <div class="form-row">
      <div class="form-group-modern">
        <label><i class="fas fa-ticket-alt"></i> Mã voucher</label>
        <div style="display:flex;gap:8px;">
          <input type="text" name="voucher_code" id="voucher_code" placeholder="Nhập mã voucher" value="<?= safe_html($_POST['voucher_code'] ?? '') ?>" style="flex:1;" onkeypress="if(event.key === 'Enter') { event.preventDefault(); applyVoucher(); }">
          <button type="button" class="btn btn-secondary" onclick="applyVoucher()" id="applyVoucherBtn">Áp dụng</button>
        </div>
        <div id="voucherMessage" class="help-text"></div>
      </div>
      
      <div class="form-group-modern">
        <label><i class="fas fa-calculator"></i> Tổng tiền</label>
        <div class="total-price-display-modern">
          <div id="tongTienContainer">
            <div id="tongTienGoc" style="display: none;">
              <span style="font-size: 14px; color: #6b7280; text-decoration: line-through;">0 đ</span>
            </div>
            <span id="tongTien" class="price-value">0 đ</span>
            <div id="voucherDiscount" style="display: none; margin-top: 8px;">
              <span style="color: #059669; font-size: 14px;">
                <i class="fas fa-tag"></i> Giảm: <span id="soTienGiam">0 đ</span>
              </span>
            </div>
          </div>
        </div>
        <div class="help-text" id="tongTienNote">Tổng tiền sau khi áp voucher (nếu có)</div>
        <input type="hidden" name="voucher_id" id="voucher_id" value="">
        <input type="hidden" name="voucher_discount" id="voucher_discount" value="0">
      </div>
      
      <div class="form-group-modern">
        <label><i class="fas fa-wallet"></i> Số tiền đặt cọc</label>
        <input type="number" 
               name="tien_dat_coc" 
               id="tien_dat_coc"
               class="deposit-input"
               value="<?= safe_html($_POST['tien_dat_coc'] ?? '0') ?>"
               min="0"
               step="1000"
               placeholder="Nhập số tiền đặt cọc (nếu có)">
        <div class="help-text">
          <i class="fas fa-info-circle"></i> Nhập số tiền đặt cọc (không bắt buộc)
        </div>
      </div>
    </div>
  </div>

  <!-- Ghi chú -->
  <div class="form-card">
    <div class="card-header">
      <i class="fas fa-sticky-note"></i>
      <h3>Ghi chú</h3>
    </div>
    
    <div class="form-group-modern full-width">
      <textarea name="ghi_chu" 
                placeholder="Nhập ghi chú (nếu có)"><?= safe_html($_POST['ghi_chu'] ?? '') ?></textarea>
    </div>
  </div>

  <div class="form-actions">
    <a href="<?= BASE_URL ?>?act=admin-bookings" class="btn-cancel">
      <i class="fas fa-times"></i> Hủy
    </a>
    <button type="submit" class="btn-submit">
      <i class="fas fa-save"></i> Tạo booking
    </button>
  </div>
</form>

<script>
function loadDeparturePlans() {
  const tourId = document.getElementById('id_tour').value;
  const lichKhoiHanhSelect = document.getElementById('id_lich_khoi_hanh');
  const seatInfo = document.getElementById('seatInfo');
  
  lichKhoiHanhSelect.innerHTML = '<option value="">-- Đang tải --</option>';
  seatInfo.style.display = 'none';
  
  if (!tourId) {
    lichKhoiHanhSelect.innerHTML = '<option value="">-- Chọn tour trước --</option>';
    return;
  }
  
  fetch(`<?= BASE_URL ?>?act=admin-get-departure-plans&tour_id=${tourId}`)
    .then(response => response.json())
    .then(data => {
      if (data.success && data.plans) {
        lichKhoiHanhSelect.innerHTML = '<option value="">-- Chọn lịch khởi hành --</option>';
        data.plans.forEach(plan => {
          const option = document.createElement('option');
          option.value = plan.id;
          option.textContent = plan.ngay_khoi_hanh ? 
            new Date(plan.ngay_khoi_hanh).toLocaleDateString('vi-VN') + 
            (plan.so_cho_con_lai !== null ? ` (Còn ${plan.so_cho_con_lai} chỗ)` : '') : 
            'N/A';
          option.setAttribute('data-gia-nguoi-lon', plan.gia_nguoi_lon || 0);
          option.setAttribute('data-gia-tre-em', plan.gia_tre_em || 0);
          option.setAttribute('data-gia-tre-nho', plan.gia_tre_nho || 0);
          option.setAttribute('data-so-cho-con-lai', plan.so_cho_con_lai || 0);
          lichKhoiHanhSelect.appendChild(option);
        });
      } else {
        lichKhoiHanhSelect.innerHTML = '<option value="">-- Không có lịch khởi hành --</option>';
      }
    })
    .catch(error => {
      console.error('Error:', error);
      lichKhoiHanhSelect.innerHTML = '<option value="">-- Lỗi tải dữ liệu --</option>';
    });
}

let currentVoucher = null;

function calculateTotal() {
  const lichKhoiHanhSelect = document.getElementById('id_lich_khoi_hanh');
  const selectedOption = lichKhoiHanhSelect.options[lichKhoiHanhSelect.selectedIndex];
  const seatInfo = document.getElementById('seatInfo');
  
  if (!selectedOption || !selectedOption.value) {
    document.getElementById('tongTien').textContent = '0 đ';
    document.getElementById('tongTienGoc').style.display = 'none';
    document.getElementById('voucherDiscount').style.display = 'none';
    seatInfo.style.display = 'none';
    return;
  }
  
  const giaNguoiLon = parseFloat(selectedOption.getAttribute('data-gia-nguoi-lon')) || 0;
  const giaTreEm = parseFloat(selectedOption.getAttribute('data-gia-tre-em')) || 0;
  const giaTreNho = parseFloat(selectedOption.getAttribute('data-gia-tre-nho')) || 0;
  const soChoConLai = parseInt(selectedOption.getAttribute('data-so-cho-con-lai')) || 0;
  
  const soNguoiLon = parseInt(document.getElementById('so_nguoi_lon').value) || 0;
  const soTreEm = parseInt(document.getElementById('so_tre_em').value) || 0;
  const soTreNho = parseInt(document.getElementById('so_tre_nho').value) || 0;
  
  const tongSoNguoi = soNguoiLon + soTreEm + soTreNho;
  const tongTienGoc = (soNguoiLon * giaNguoiLon) + (soTreEm * giaTreEm) + (soTreNho * giaTreNho);
  
  // Áp dụng voucher nếu có
  let tongTienCuoi = tongTienGoc;
  let soTienGiam = 0;
  
  if (currentVoucher && tongTienGoc > 0) {
    // Kiểm tra min_order_amount
    const minOrder = parseFloat(currentVoucher.min_order_amount) || 0;
    if (tongTienGoc >= minOrder) {
      if (currentVoucher.discount_type === 'percent') {
        soTienGiam = tongTienGoc * (parseFloat(currentVoucher.discount_value) / 100);
      } else {
        soTienGiam = parseFloat(currentVoucher.discount_value);
      }
      soTienGiam = Math.min(soTienGiam, tongTienGoc); // Không giảm quá tổng tiền
      tongTienCuoi = Math.max(0, tongTienGoc - soTienGiam);
      
      // Hiển thị tổng tiền gốc và số tiền giảm
      document.getElementById('tongTienGoc').style.display = 'block';
      document.getElementById('tongTienGoc').querySelector('span').textContent = tongTienGoc.toLocaleString('vi-VN') + ' đ';
      document.getElementById('voucherDiscount').style.display = 'block';
      document.getElementById('soTienGiam').textContent = soTienGiam.toLocaleString('vi-VN') + ' đ';
      document.getElementById('tongTien').style.color = '#059669';
      document.getElementById('tongTien').style.fontWeight = '700';
    } else {
      // Tổng tiền chưa đạt mức tối thiểu
      currentVoucher = null;
      document.getElementById('voucher_id').value = '';
      document.getElementById('voucher_discount').value = '0';
      document.getElementById('voucherMessage').textContent = 'Tổng tiền chưa đạt mức tối thiểu để áp dụng voucher';
      document.getElementById('voucherMessage').style.color = '#ef4444';
    }
  } else {
    // Không có voucher hoặc tổng tiền = 0
    document.getElementById('tongTienGoc').style.display = 'none';
    document.getElementById('voucherDiscount').style.display = 'none';
    document.getElementById('tongTien').style.color = '';
    document.getElementById('tongTien').style.fontWeight = '';
  }
  
  document.getElementById('tongTien').textContent = tongTienCuoi.toLocaleString('vi-VN') + ' đ';
  document.getElementById('voucher_discount').value = soTienGiam;
  
  // Hiển thị thông tin số chỗ
  if (tongSoNguoi > 0) {
    seatInfo.style.display = 'block';
    if (tongSoNguoi > soChoConLai) {
      seatInfo.className = 'seat-info danger';
      seatInfo.textContent = `⚠️ Chỉ còn ${soChoConLai} chỗ. Không đủ số chỗ yêu cầu (${tongSoNguoi} chỗ).`;
    } else if (tongSoNguoi > soChoConLai * 0.8) {
      seatInfo.className = 'seat-info warning';
      seatInfo.textContent = `⚠️ Còn ${soChoConLai} chỗ. Số chỗ yêu cầu: ${tongSoNguoi} chỗ.`;
    } else {
      seatInfo.className = 'seat-info';
      seatInfo.textContent = `✓ Còn ${soChoConLai} chỗ. Số chỗ yêu cầu: ${tongSoNguoi} chỗ.`;
    }
  } else {
    seatInfo.style.display = 'none';
  }
}

function applyVoucher() {
  const code = (document.getElementById('voucher_code')?.value || '').trim();
  const msg = document.getElementById('voucherMessage');
  
  if (!code) {
    msg.textContent = 'Vui lòng nhập mã voucher.';
    msg.style.color = '#ef4444';
    currentVoucher = null;
    document.getElementById('voucher_id').value = '';
    document.getElementById('voucher_discount').value = '0';
    calculateTotal(); // Tính lại tổng tiền
    return;
  }
  
  // Hiển thị loading
  msg.textContent = 'Đang kiểm tra voucher...';
  msg.style.color = '#6b7280';
  
  // Gọi AJAX để kiểm tra voucher
  const baseUrl = '<?= BASE_URL ?>';
  fetch(`${baseUrl}?act=admin-check-voucher&code=${encodeURIComponent(code)}`)
    .then(response => {
      if (!response.ok) {
        throw new Error('Network response was not ok');
      }
      return response.json();
    })
    .then(data => {
      if (data.success) {
        currentVoucher = data.voucher;
        document.getElementById('voucher_id').value = data.voucher.id;
        const discountText = data.voucher.discount_type === 'percent' 
          ? data.voucher.discount_value + '%' 
          : parseFloat(data.voucher.discount_value).toLocaleString('vi-VN') + ' đ';
        msg.textContent = `✓ Voucher hợp lệ! Giảm ${discountText}`;
        msg.style.color = '#059669';
        calculateTotal(); // Tính lại tổng tiền với voucher
      } else {
        currentVoucher = null;
        document.getElementById('voucher_id').value = '';
        document.getElementById('voucher_discount').value = '0';
        msg.textContent = data.message || 'Voucher không hợp lệ';
        msg.style.color = '#ef4444';
        calculateTotal(); // Tính lại tổng tiền không có voucher
      }
    })
    .catch(error => {
      console.error('Error:', error);
      currentVoucher = null;
      document.getElementById('voucher_id').value = '';
      document.getElementById('voucher_discount').value = '0';
      msg.textContent = 'Có lỗi xảy ra khi kiểm tra voucher. Vui lòng thử lại.';
      msg.style.color = '#ef4444';
      calculateTotal();
    });
}

// Tính tổng tiền khi trang được tải
document.addEventListener('DOMContentLoaded', function() {
  calculateTotal();
  
  // Gọi toggleGuestList khi thay đổi số lượng khách
  ['so_nguoi_lon', 'so_tre_em', 'so_tre_nho'].forEach(id => {
    const el = document.getElementById(id);
    if (el) {
      el.addEventListener('change', function() {
        updateGuestList();
      });
    }
  });
  
  // Gọi lần đầu để kiểm tra loại booking hiện tại
  toggleGuestList();
});

// Toggle hiển thị danh sách khách
function toggleGuestList() {
  const loaiBooking = document.getElementById('loai_booking').value;
  const guestListCard = document.getElementById('guestListCard');
  const soNguoiLon = parseInt(document.getElementById('so_nguoi_lon').value) || 0;
  const soTreEm = parseInt(document.getElementById('so_tre_em').value) || 0;
  const soTreNho = parseInt(document.getElementById('so_tre_nho').value) || 0;
  const tongSoKhach = soNguoiLon + soTreEm + soTreNho;
  
  // Chỉ hiển thị danh sách khách khi chọn Nhóm (3) hoặc Đoàn (4)
  if (loaiBooking == '3' || loaiBooking == '4') {
    guestListCard.style.display = 'block';
    generateGuestList(tongSoKhach, soNguoiLon, soTreEm, soTreNho);
  } else {
    guestListCard.style.display = 'none';
    document.getElementById('guestListContainer').innerHTML = '';
  }
}

// Tạo danh sách khách
function generateGuestList(tongSo, nguoiLon, treEm, treNho) {
  const container = document.getElementById('guestListContainer');
  container.innerHTML = '';
  
  let index = 0;
  
  // Người lớn
  for (let i = 0; i < nguoiLon; i++) {
    container.appendChild(createGuestRow(index++, 'Người lớn', 1));
  }
  
  // Trẻ em
  for (let i = 0; i < treEm; i++) {
    container.appendChild(createGuestRow(index++, 'Trẻ em', 2));
  }
  
  // Trẻ nhỏ
  for (let i = 0; i < treNho; i++) {
    container.appendChild(createGuestRow(index++, 'Trẻ nhỏ', 3));
  }
}

// Tạo một hàng thông tin khách
function createGuestRow(index, loaiKhachLabel, loaiKhach) {
  const row = document.createElement('div');
  row.className = 'guest-row';
  row.style.cssText = 'background: #f9fafb; padding: 20px; margin-bottom: 16px; border-radius: 8px; border: 1px solid #e5e7eb;';
  
  row.innerHTML = `
    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
      <div style="background: #3b82f6; color: white; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700;">
        ${index + 1}
      </div>
      <div style="font-weight: 700; color: #1e40af;">${loaiKhachLabel}</div>
    </div>
    <div class="form-row">
      <div class="form-group-modern">
        <label>Họ tên <span class="required">*</span></label>
        <input type="text" name="danh_sach_khach[${index}][ho_ten]" required placeholder="Nhập họ tên">
      </div>
      <div class="form-group-modern">
        <label>Giới tính</label>
        <select name="danh_sach_khach[${index}][gioi_tinh]">
          <option value="">-- Chọn --</option>
          <option value="1">Nam</option>
          <option value="0">Nữ</option>
        </select>
      </div>
    </div>
    <div class="form-row">
      <div class="form-group-modern">
        <label>Ngày sinh</label>
        <input type="date" name="danh_sach_khach[${index}][ngay_sinh]">
      </div>
      <div class="form-group-modern">
    </div>
    <div class="form-row">
      <div class="form-group-modern">
        <label>Số điện thoại</label>
        <input type="tel" name="danh_sach_khach[${index}][so_dien_thoai]" placeholder="Nhập số điện thoại">
      </div>
      <div class="form-group-modern">
        <input type="hidden" name="danh_sach_khach[${index}][loai_khach]" value="${loaiKhach}">
      </div>
    </div>
  `;
  
  return row;
}

// Cập nhật danh sách khách khi thay đổi số lượng
function updateGuestList() {
  const loaiBooking = document.getElementById('loai_booking').value;
  if (loaiBooking == '3' || loaiBooking == '4') {
    toggleGuestList();
  }
}

</script>