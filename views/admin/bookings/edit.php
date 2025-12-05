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
          <?php foreach ($statusList as $key => $label): ?>
            <option value="<?= $key ?>" <?= ($booking['trang_thai'] == $key) ? 'selected' : '' ?>>
              <?= safe_html($label) ?>
            </option>
          <?php endforeach; ?>
        </select>
        <div class="help-text">
          <strong>Lưu ý:</strong> Khi hủy booking (trạng thái = Hủy), số chỗ sẽ được cộng lại vào lịch khởi hành. 
          Khi xác nhận booking (từ Hủy sang trạng thái khác), số chỗ sẽ bị trừ lại.
        </div>
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
          <span class="price-value"><?= formatPrice($booking['tong_tien']) ?></span>
        </div>
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

