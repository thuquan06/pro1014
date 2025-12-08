<?php
/**
 * Cập nhật Booking - Modern Interface
 * UC-Update-Booking: Cập nhật trạng thái và thông tin booking
 */

function safe_html($value) {
    return htmlentities($value ?? '', ENT_QUOTES, 'UTF-8');
}

function formatPrice($price) {
    return number_format($price, 0, ',', '.') . ' đ';
}

$booking = $booking ?? null;
$statusList = $statusList ?? [];
$guides = $guides ?? [];

if (!$booking) {
    echo '<div class="alert alert-danger">Không tìm thấy booking</div>';
    return;
}
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

.info-display {
  background: #f9fafb;
  padding: 16px;
  border-radius: 8px;
  border-left: 4px solid #3b82f6;
  margin-bottom: 16px;
}

.info-display .label {
  font-size: 12px;
  color: #6b7280;
  font-weight: 600;
  margin-bottom: 4px;
}

.info-display .value {
  font-size: 16px;
  color: #1f2937;
  font-weight: 500;
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
    <i class="fas fa-edit"></i> Cập nhật Booking: <?= safe_html($booking['ma_booking']) ?>
  </h1>
  <a href="<?= BASE_URL ?>?act=admin-booking-detail&id=<?= $booking['id'] ?>" class="btn-cancel">
    <i class="fas fa-arrow-left"></i> Quay lại
  </a>
</div>

<form method="POST" action="<?= BASE_URL ?>?act=admin-booking-edit&id=<?= $booking['id'] ?>" id="bookingForm">
  <!-- Thông tin hiện tại -->
  <div class="form-card">
    <div class="card-header">
      <i class="fas fa-info-circle"></i>
      <h3>Thông tin hiện tại</h3>
    </div>
    
    <div class="form-row">
      <div class="form-group-modern">
        <div class="info-display">
          <div class="label">Tour</div>
          <div class="value"><?= safe_html($booking['ten_tour'] ?? 'N/A') ?></div>
        </div>
      </div>
      
      <div class="form-group-modern">
        <div class="info-display">
          <div class="label">Ngày khởi hành</div>
          <div class="value"><?= $booking['ngay_khoi_hanh'] ? date('d/m/Y', strtotime($booking['ngay_khoi_hanh'])) : 'N/A' ?></div>
        </div>
      </div>
    </div>
    
    <div class="form-row">
      <div class="form-group-modern full-width">
        <label>Hướng dẫn viên</label>
        <div id="hdv-list" style="display: grid; gap: 12px;">
          <!-- HDV sẽ được thêm vào đây -->
        </div>
        <button type="button" onclick="addHdvRow()" class="btn btn-sm btn-secondary" style="margin-top: 8px;">
          <i class="fas fa-plus"></i> Thêm HDV
        </button>
        <div class="help-text">Thêm hướng dẫn viên được phân công cho booking này</div>
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
               value="<?= safe_html($booking['ho_ten']) ?>"
               required>
      </div>
      
      <div class="form-group-modern">
        <label>Số điện thoại <span class="required">*</span></label>
        <input type="tel" 
               name="so_dien_thoai" 
               id="so_dien_thoai"
               value="<?= safe_html($booking['so_dien_thoai']) ?>"
               placeholder="Nhập số điện thoại (10 số, bắt đầu bằng 0)"
               pattern="[0-9]{10}"
               maxlength="10"
               required>
        <div class="help-text">Ví dụ: 0912345678</div>
      </div>
    </div>
    
    <div class="form-row">
      <div class="form-group-modern full-width">
        <label>Email</label>
        <input type="email" 
               name="email" 
               id="email"
               value="<?= safe_html($booking['email'] ?? '') ?>"
               placeholder="Nhập email (không bắt buộc)">
        <div class="help-text">Ví dụ: example@email.com</div>
      </div>
    </div>
    
    <div class="form-row">
      <div class="form-group-modern full-width">
        <label>Địa chỉ</label>
        <textarea name="dia_chi" 
                  rows="3"
                  placeholder="Nhập địa chỉ khách hàng (không bắt buộc)"><?= safe_html($booking['dia_chi'] ?? '') ?></textarea>
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
               value="<?= $booking['so_nguoi_lon'] ?? 0 ?>"
               min="0">
      </div>
      
      <div class="form-group-modern">
        <label>Trẻ em</label>
        <input type="number" 
               name="so_tre_em" 
               value="<?= $booking['so_tre_em'] ?? 0 ?>"
               min="0">
      </div>
      
      <div class="form-group-modern">
        <label>Trẻ nhỏ</label>
        <input type="number" 
               name="so_tre_nho" 
               value="<?= $booking['so_tre_nho'] ?? 0 ?>"
               min="0">
      </div>
    </div>
  </div>

  <!-- Trạng thái -->
  <div class="form-card">
    <div class="card-header">
      <i class="fas fa-toggle-on"></i>
      <h3>Trạng thái booking</h3>
    </div>
    
    <div class="form-row">
      <div class="form-group-modern full-width">
        <label>Trạng thái <span class="required">*</span></label>
        <select name="trang_thai" required>
          <?php 
          // Quy trình: 0 (Chờ xử lý) -> 2 (Đã đặt cọc) -> 3 (Đã thanh toán) -> 4 (Đã hoàn thành)
          // Có thể hủy (5) từ bất kỳ trạng thái nào
          // Có thể quay lại từ hủy (5) về các trạng thái hợp lệ
          $currentStatus = (int)($booking['trang_thai'] ?? 0);
          $allowedTransitions = [
            0 => [0, 2, 5], // Chờ xử lý -> Đã đặt cọc hoặc Hủy
            2 => [2, 3, 5], // Đã đặt cọc -> Đã thanh toán hoặc Hủy
            3 => [3, 4],    // Đã thanh toán -> chỉ có thể Đã hoàn thành (không cho hủy)
            4 => [4],       // Đã hoàn thành -> không thể thay đổi trạng thái
            5 => [0, 2, 5], // Hủy -> có thể quay lại Chờ xử lý hoặc Đã đặt cọc
          ];
          $allowedStatuses = $allowedTransitions[$currentStatus] ?? array_keys($statusList);
          foreach ($statusList as $key => $label): 
            if (in_array($key, $allowedStatuses)):
          ?>
            <option value="<?= $key ?>" <?= ($booking['trang_thai'] == $key) ? 'selected' : '' ?>>
              <?= safe_html($label) ?>
            </option>
          <?php 
            endif;
          endforeach; 
          ?>
        </select>
        <div class="help-text">
          <strong>Lưu ý:</strong> Khi hủy booking (trạng thái = Hủy), số chỗ sẽ được cộng lại vào lịch khởi hành. 
          Khi xác nhận booking (từ Hủy sang trạng thái khác), số chỗ sẽ bị trừ lại.
        </div>
      </div>
    </div>
  </div>

  <!-- Mã giảm giá -->
  <div class="form-card">
    <div class="card-header">
      <i class="fas fa-ticket-alt"></i>
      <h3>Mã giảm giá</h3>
    </div>
    
    <div class="form-row">
      <div class="form-group-modern full-width">
        <label><i class="fas fa-ticket-alt"></i> Mã voucher</label>
        <div style="display:flex;gap:8px;">
          <input type="text" 
                 name="voucher_code" 
                 id="voucher_code" 
                 placeholder="Nhập mã voucher" 
                 value="<?= safe_html($booking['voucher_code'] ?? '') ?>"
                 style="flex:1; padding: 12px 16px; border: 1px solid var(--border); border-radius: 8px;">
          <button type="button" class="btn btn-secondary" onclick="applyVoucher()" style="padding: 12px 24px;">Áp dụng</button>
          <?php if (!empty($booking['voucher_code'])): ?>
            <button type="button" class="btn btn-danger" onclick="removeVoucher()" style="padding: 12px 24px;">Xóa voucher</button>
          <?php endif; ?>
        </div>
        <div id="voucherMessage" class="help-text">
          <?php if (!empty($booking['voucher_code'])): ?>
            <span style="color: #059669;">
              <i class="fas fa-check-circle"></i> Đã áp dụng voucher: <strong><?= safe_html($booking['voucher_code']) ?></strong>
              <?php if (!empty($booking['voucher_discount']) && $booking['voucher_discount'] > 0): ?>
                - Giảm: <?= formatPrice($booking['voucher_discount']) ?>
              <?php endif; ?>
            </span>
          <?php else: ?>
            <span style="color: #6b7280;">Nhập mã voucher để áp dụng giảm giá</span>
          <?php endif; ?>
        </div>
        <input type="hidden" name="voucher_id" id="voucher_id" value="<?= safe_html($booking['voucher_id'] ?? '') ?>">
        <input type="hidden" name="voucher_discount" id="voucher_discount" value="<?= safe_html($booking['voucher_discount'] ?? '0') ?>">
      </div>
    </div>
  </div>

  <!-- Thông tin thanh toán -->
  <div class="form-card payment-info-card">
    <div class="card-header">
      <i class="fas fa-money-bill-wave"></i>
      <h3>Thông tin thanh toán</h3>
    </div>
    
    <div class="form-row">
      <div class="form-group-modern">
        <label><i class="fas fa-calculator"></i> Tổng tiền</label>
        <div class="total-price-display-modern">
          <span class="price-value" id="tongTienDisplay"><?= formatPrice($booking['tong_tien']) ?></span>
        </div>
        <?php if (!empty($booking['voucher_discount']) && $booking['voucher_discount'] > 0): ?>
          <div class="help-text" style="color: #059669;">
            <i class="fas fa-info-circle"></i> Tổng tiền sau khi giảm giá
          </div>
        <?php endif; ?>
      </div>
      
      <div class="form-group-modern">
        <label><i class="fas fa-wallet"></i> Số tiền đặt cọc</label>
        <input type="number" 
               name="tien_dat_coc" 
               id="tien_dat_coc"
               class="deposit-input"
               value="<?= safe_html($booking['tien_dat_coc'] ?? '0') ?>"
               min="0"
               step="1000"
               placeholder="Nhập số tiền đặt cọc">
        <div class="help-text">
          <i class="fas fa-info-circle"></i> Nhập số tiền đặt cọc (nếu có)
        </div>
      </div>
    </div>
    
    <div class="form-row">
      <div class="form-group-modern full-width">
        <label><i class="fas fa-calendar-check"></i> Ngày thanh toán</label>
        <input type="datetime-local" 
               name="ngay_thanh_toan" 
               id="ngay_thanh_toan"
               class="deposit-input"
               value="<?= $booking['ngay_thanh_toan'] ? date('Y-m-d\TH:i', strtotime($booking['ngay_thanh_toan'])) : '' ?>"
               placeholder="Chọn ngày thanh toán">
        <div class="help-text">
          <i class="fas fa-info-circle"></i> Chỉ điền khi trạng thái là "Đã thanh toán" hoặc "Hoàn thành"
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
                placeholder="Nhập ghi chú nội bộ (nếu có)"><?= safe_html($booking['ghi_chu'] ?? '') ?></textarea>
    </div>
  </div>

  <div class="form-actions">
    <a href="<?= BASE_URL ?>?act=admin-booking-detail&id=<?= $booking['id'] ?>" class="btn-cancel">
      <i class="fas fa-times"></i> Hủy
    </a>
    <button type="submit" class="btn-submit">
      <i class="fas fa-save"></i> Cập nhật booking
    </button>
  </div>
</form>

<script>
// Giá trị tổng tiền gốc (trước khi giảm giá)
const tongTienGoc = <?= $booking['tong_tien'] + ($booking['voucher_discount'] ?? 0) ?>;

function applyVoucher() {
  const code = (document.getElementById('voucher_code')?.value || '').trim().toUpperCase();
  const msg = document.getElementById('voucherMessage');
  
  if (!code) {
    msg.innerHTML = '<span style="color: #ef4444;"><i class="fas fa-exclamation-circle"></i> Vui lòng nhập mã voucher.</span>';
    return;
  }
  
  // Gọi API kiểm tra voucher
  fetch('<?= BASE_URL ?>?act=admin-check-voucher&code=' + encodeURIComponent(code))
    .then(response => response.json())
    .then(data => {
      if (data.success && data.voucher) {
        const voucher = data.voucher;
        const discount = parseFloat(voucher.discount_value || 0);
        let discountAmount = 0;
        
        if (voucher.discount_type === 'percent') {
          discountAmount = (tongTienGoc * discount) / 100;
        } else {
          discountAmount = discount;
        }
        
        // Cập nhật thông tin voucher
        document.getElementById('voucher_id').value = voucher.id;
        document.getElementById('voucher_discount').value = discountAmount;
        
        // Cập nhật tổng tiền
        const tongTienMoi = Math.max(0, tongTienGoc - discountAmount);
        document.getElementById('tongTienDisplay').textContent = formatPrice(tongTienMoi);
        
        // Hiển thị thông báo
        msg.innerHTML = '<span style="color: #059669;"><i class="fas fa-check-circle"></i> Đã áp dụng voucher: <strong>' + code + '</strong> - Giảm: ' + formatPrice(discountAmount) + '</span>';
        
        // Hiển thị nút xóa voucher
        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className = 'btn btn-danger';
        removeBtn.style.cssText = 'padding: 12px 24px;';
        removeBtn.textContent = 'Xóa voucher';
        removeBtn.onclick = removeVoucher;
        
        const voucherInput = document.getElementById('voucher_code');
        const applyBtn = voucherInput.nextElementSibling;
        if (!applyBtn.nextElementSibling || applyBtn.nextElementSibling.textContent !== 'Xóa voucher') {
          applyBtn.parentNode.insertBefore(removeBtn, applyBtn.nextSibling);
        }
      } else {
        msg.innerHTML = '<span style="color: #ef4444;"><i class="fas fa-times-circle"></i> ' + (data.message || 'Mã voucher không hợp lệ hoặc đã hết hạn') + '</span>';
        document.getElementById('voucher_id').value = '';
        document.getElementById('voucher_discount').value = '0';
        document.getElementById('tongTienDisplay').textContent = formatPrice(tongTienGoc);
      }
    })
    .catch(error => {
      console.error('Error:', error);
      msg.innerHTML = '<span style="color: #ef4444;"><i class="fas fa-exclamation-circle"></i> Có lỗi xảy ra. Vui lòng thử lại.</span>';
    });
}

function removeVoucher() {
  document.getElementById('voucher_code').value = '';
  document.getElementById('voucher_id').value = '';
  document.getElementById('voucher_discount').value = '0';
  document.getElementById('tongTienDisplay').textContent = formatPrice(tongTienGoc);
  document.getElementById('voucherMessage').innerHTML = '<span style="color: #6b7280;">Nhập mã voucher để áp dụng giảm giá</span>';
  
  // Xóa nút xóa voucher
  const removeBtn = document.querySelector('button.btn-danger');
  if (removeBtn && removeBtn.textContent === 'Xóa voucher') {
    removeBtn.remove();
  }
}

function formatPrice(price) {
  return new Intl.NumberFormat('vi-VN').format(Math.round(price)) + ' đ';
}

// Quản lý danh sách HDV
let hdvRowIndex = 0;

// Đảm bảo hàm được định nghĩa trong scope global
// Lấy danh sách HDV đã được chọn trong các row hiện tại
function getSelectedHdvIds(excludeRow = null) {
  const selectedIds = [];
  const hdvRows = document.querySelectorAll('.hdv-row');
  hdvRows.forEach(function(row) {
    if (row === excludeRow) return; // Bỏ qua row đang xét
    const hdvSelect = row.querySelector('select[name*="[id_hdv]"]');
    if (hdvSelect && hdvSelect.value && hdvSelect.value !== '') {
      const hdvId = parseInt(hdvSelect.value);
      if (!isNaN(hdvId) && hdvId > 0) {
        selectedIds.push(hdvId);
      }
    }
  });
  return selectedIds;
}

// Lấy danh sách vai trò đã được chọn trong các row hiện tại
function getSelectedRoles(excludeRow = null) {
  const selectedRoles = [];
  const hdvRows = document.querySelectorAll('.hdv-row');
  hdvRows.forEach(function(row) {
    if (row === excludeRow) return; // Bỏ qua row đang xét
    const roleSelect = row.querySelector('select[name*="[vai_tro]"]');
    if (roleSelect && roleSelect.value && roleSelect.value !== '') {
      selectedRoles.push(roleSelect.value);
    }
  });
  return selectedRoles;
}

// Cập nhật các dropdown HDV để disable các HDV đã chọn (nhưng vẫn hiển thị)
function updateHdvDropdowns() {
  const selectedIds = getSelectedHdvIds();
  const hdvRows = document.querySelectorAll('.hdv-row');
  const guides = <?= json_encode(array_map(function($g) { return ['id' => $g['id'], 'ho_ten' => htmlspecialchars($g['ho_ten'] ?? '', ENT_QUOTES, 'UTF-8')]; }, $guides ?? [])) ?>;
  
  hdvRows.forEach(function(row) {
    const hdvSelect = row.querySelector('select[name*="[id_hdv]"]');
    if (!hdvSelect) return;
    
    const currentValue = hdvSelect.value;
    const currentSelectedId = currentValue && currentValue !== '' ? parseInt(currentValue) : null;
    
    // Xóa tất cả options trừ option đầu tiên
    while (hdvSelect.options.length > 1) {
      hdvSelect.remove(1);
    }
    
    // Thêm lại tất cả các HDV, disable các HDV đã chọn ở row khác (nhưng vẫn hiển thị)
    if (guides && guides.length > 0) {
      guides.forEach(function(guide) {
        const guideId = parseInt(guide.id);
        if (isNaN(guideId) || guideId <= 0) return;
        
        const option = document.createElement('option');
        option.value = guide.id;
        option.setAttribute('data-guide-id', guide.id);
        option.textContent = guide.ho_ten;
        
        // Disable nếu đã được chọn ở row khác (nhưng không phải row hiện tại)
        const isSelected = selectedIds.includes(guideId);
        const isCurrentRow = currentSelectedId !== null && guideId === currentSelectedId;
        
        if (isSelected && !isCurrentRow) {
          option.disabled = true;
          option.textContent += ' (Đã chọn)';
        } else {
          option.disabled = false;
        }
        
        if (isCurrentRow) {
          option.selected = true;
        }
        
        hdvSelect.appendChild(option);
      });
    }
  });
  
  // Cập nhật cả dropdown vai trò
  updateRoleDropdowns();
}

// Cập nhật các dropdown vai trò để disable các vai trò đã chọn (nhưng vẫn hiển thị)
function updateRoleDropdowns() {
  const selectedRoles = getSelectedRoles();
  const hdvRows = document.querySelectorAll('.hdv-row');
  const roleOptions = [
    { value: 'HDV chính', label: 'HDV chính' },
    { value: 'HDV phụ', label: 'HDV phụ' },
    { value: 'Trợ lý', label: 'Trợ lý' }
  ];
  
  hdvRows.forEach(function(row) {
    const roleSelect = row.querySelector('select[name*="[vai_tro]"]');
    if (!roleSelect) return;
    
    const currentValue = roleSelect.value;
    
    // Xóa tất cả options trừ option đầu tiên
    while (roleSelect.options.length > 1) {
      roleSelect.remove(1);
    }
    
    // Thêm lại tất cả các vai trò, disable các vai trò đã chọn ở row khác (nhưng vẫn hiển thị)
    roleOptions.forEach(function(role) {
      const option = document.createElement('option');
      option.value = role.value;
      option.textContent = role.label;
      
      // Disable nếu đã được chọn ở row khác (nhưng không phải row hiện tại)
      const isSelected = selectedRoles.includes(role.value);
      const isCurrentRow = currentValue === role.value;
      
      if (isSelected && !isCurrentRow) {
        option.disabled = true;
        option.textContent += ' (Đã chọn)';
      } else {
        option.disabled = false;
      }
      
      if (isCurrentRow) {
        option.selected = true;
      }
      
      roleSelect.appendChild(option);
    });
  });
}

window.addHdvRow = function(hdvId = '', vaiTro = '') {
  const hdvList = document.getElementById('hdv-list');
  if (!hdvList) {
    console.error('Không tìm thấy element hdv-list');
    alert('Lỗi: Không tìm thấy phần tử hdv-list');
    return;
  }
  
  const row = document.createElement('div');
  row.className = 'hdv-row';
  row.style.cssText = 'display: grid; grid-template-columns: 1fr 1fr auto; gap: 12px; align-items: end; padding: 12px; background: #f9fafb; border-radius: 8px; border: 1px solid #e5e7eb;';
  row.dataset.index = hdvRowIndex++;
  
  const hdvSelect = document.createElement('select');
  hdvSelect.name = `danh_sach_hdv[${row.dataset.index}][id_hdv]`;
  hdvSelect.className = 'form-control';
  hdvSelect.style.cssText = 'padding: 10px; border: 1px solid #e5e7eb; border-radius: 6px;';
  hdvSelect.innerHTML = '<option value="">-- Chọn HDV --</option>';
  
  // Lấy danh sách HDV đã được chọn (trừ row hiện tại)
  const selectedIds = getSelectedHdvIds(row);
  
  // Thêm tất cả các option HDV từ danh sách guides, disable các HDV đã chọn (nhưng vẫn hiển thị)
  const guides = <?= json_encode(array_map(function($g) { return ['id' => $g['id'], 'ho_ten' => htmlspecialchars($g['ho_ten'] ?? '', ENT_QUOTES, 'UTF-8')]; }, $guides ?? [])) ?>;
  if (guides && guides.length > 0) {
    guides.forEach(function(guide) {
      const guideId = parseInt(guide.id);
      if (isNaN(guideId) || guideId <= 0) return;
      
      const option = document.createElement('option');
      option.value = guide.id;
      option.setAttribute('data-guide-id', guide.id);
      option.textContent = guide.ho_ten;
      
      // Disable nếu đã được chọn ở row khác (nhưng không phải HDV đang set cho row này)
      const isSelected = selectedIds.includes(guideId);
      const isCurrentHdv = hdvId && parseInt(hdvId) === guideId;
      
      if (isSelected && !isCurrentHdv) {
        option.disabled = true;
        option.textContent += ' (Đã chọn)';
      } else {
        option.disabled = false;
      }
      
      hdvSelect.appendChild(option);
    });
  }
  
  if (hdvId) hdvSelect.value = hdvId;
  hdvSelect.onchange = function() { 
    updateVaiTroForRow(this);
    updateHdvDropdowns(); // Cập nhật lại các dropdown khi chọn HDV
  };
  
  const vaiTroSelect = document.createElement('select');
  vaiTroSelect.name = `danh_sach_hdv[${row.dataset.index}][vai_tro]`;
  vaiTroSelect.className = 'form-control';
  vaiTroSelect.style.cssText = 'padding: 10px; border: 1px solid #e5e7eb; border-radius: 6px;';
  vaiTroSelect.innerHTML = '<option value="">-- Chọn vai trò --</option>';
  
  // Vai trò sẽ được thêm bởi updateRoleDropdowns
  if (vaiTro) vaiTroSelect.value = vaiTro;
  vaiTroSelect.onchange = function() {
    updateRoleDropdowns(); // Cập nhật lại các dropdown vai trò khi thay đổi
  };
  
  const removeBtn = document.createElement('button');
  removeBtn.type = 'button';
  removeBtn.className = 'btn btn-sm btn-danger';
  removeBtn.style.cssText = 'padding: 10px 16px;';
  removeBtn.innerHTML = '<i class="fas fa-trash"></i>';
  removeBtn.onclick = function() { 
    row.remove();
    updateHdvDropdowns(); // Cập nhật lại các dropdown khi xóa row (bao gồm cả vai trò)
  };
  
  row.appendChild(hdvSelect);
  row.appendChild(vaiTroSelect);
  row.appendChild(removeBtn);
  hdvList.appendChild(row);
  
  // Cập nhật lại tất cả dropdown sau khi thêm row mới
  // Sử dụng setTimeout để đảm bảo DOM đã được cập nhật
  setTimeout(function() {
    updateHdvDropdowns();
  }, 10);
  
  console.log('Đã thêm hàng HDV:', row);
};

function updateVaiTroForRow(selectElement) {
  const row = selectElement.closest('.hdv-row');
  const vaiTroSelect = row.querySelector('select[name*="[vai_tro]"]');
  const lichKhoiHanhSelect = document.getElementById('id_lich_khoi_hanh');
  if (!lichKhoiHanhSelect) return;
  
  const departurePlanId = lichKhoiHanhSelect.value || '<?= $booking['id_lich_khoi_hanh'] ?? '' ?>';
  const guideId = selectElement.value;
  
  if (!departurePlanId || !guideId) {
    return;
  }
  
  fetch(`<?= BASE_URL ?>?act=admin-get-guide-roles&departure_plan_id=${departurePlanId}`)
    .then(response => response.json())
    .then(data => {
      if (data.success && data.roles) {
        const role = data.roles[parseInt(guideId)];
        if (role) {
          vaiTroSelect.value = role;
        }
      }
    })
    .catch(error => {
      console.error('Error loading guide role:', error);
    });
}

// Validate form trước khi submit
const bookingForm = document.querySelector('form[method="POST"]');
if (bookingForm) {
  bookingForm.addEventListener('submit', function(e) {
    const hdvRows = document.querySelectorAll('.hdv-row');
    const hdvIds = [];
    let hasError = false;
    let errorMessage = '';

    hdvRows.forEach(function(row, index) {
      const hdvSelect = row.querySelector('select[name*="[id_hdv]"]');
      const vaiTroSelect = row.querySelector('select[name*="[vai_tro]"]');
      
      if (hdvSelect && hdvSelect.value) {
        if (!vaiTroSelect || !vaiTroSelect.value) {
          hasError = true;
          errorMessage = `Vui lòng chọn vai trò cho HDV ở hàng thứ ${index + 1}`;
          return;
        }
        
        // Kiểm tra trùng HDV
        const hdvId = parseInt(hdvSelect.value);
        if (hdvIds.includes(hdvId)) {
          hasError = true;
          errorMessage = 'HDV đã được chọn ở nhiều hàng. Mỗi HDV chỉ có thể được chọn một lần.';
          return;
        }
        hdvIds.push(hdvId);
      } else if (vaiTroSelect && vaiTroSelect.value) {
        // Nếu có vai trò nhưng không có HDV
        hasError = true;
        errorMessage = `Vui lòng chọn HDV cho hàng thứ ${index + 1}`;
        return;
      }
    });

    if (hasError) {
      e.preventDefault();
      alert(errorMessage);
      return false;
    }
  });
}

// Khởi tạo danh sách HDV khi trang load
document.addEventListener('DOMContentLoaded', function() {
  <?php if (!empty($bookingGuides) && is_array($bookingGuides)): ?>
    <?php foreach ($bookingGuides as $hdv): ?>
      addHdvRow('<?= $hdv['id_hdv'] ?>', '<?= addslashes($hdv['vai_tro'] ?? '') ?>');
    <?php endforeach; ?>
  <?php endif; ?>
  
  // Cập nhật dropdown sau khi tất cả các row đã được thêm vào
  setTimeout(function() {
    updateHdvDropdowns();
  }, 200);
});
</script>