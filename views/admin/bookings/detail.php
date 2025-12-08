<?php
/**
 * Booking Detail View
 * UC-View-Booking: Chi tiết đơn Booking
 */

function safe_html($value) {
    return htmlentities($value ?? '', ENT_QUOTES, 'UTF-8');
}

function formatDate($date) {
    return $date ? date('d/m/Y', strtotime($date)) : 'N/A';
}

function formatDateTime($datetime) {
    return $datetime ? date('d/m/Y H:i', strtotime($datetime)) : 'N/A';
}

function formatPrice($price) {
    return number_format($price, 0, ',', '.') . ' đ';
}

function getStatusBadge($status, $statusList) {
    $statusText = $statusList[$status] ?? 'Không xác định';
    $badgeClass = '';
    $icon = '';
    
    switch($status) {
        case 0: $badgeClass = 'warning'; $icon = 'fa-clock'; break;
        case 1: $badgeClass = 'info'; $icon = 'fa-phone'; break;
        case 2: $badgeClass = 'primary'; $icon = 'fa-money-bill'; break;
        case 3: $badgeClass = 'success'; $icon = 'fa-check-circle'; break;
        case 4: $badgeClass = 'success'; $icon = 'fa-check-double'; break;
        case 5: $badgeClass = 'danger'; $icon = 'fa-times-circle'; break;
        default: $badgeClass = 'secondary'; $icon = 'fa-question';
    }
    
    return "<span class=\"status-badge {$badgeClass}\"><i class=\"fas {$icon}\"></i> {$statusText}</span>";
}

$booking = $booking ?? null;
$statusList = $statusList ?? [];

if (!$booking) {
    echo '<div class="alert alert-danger">Không tìm thấy booking</div>';
    return;
}
?>

<style>
.detail-container {
  max-width: 1400px;
  margin: 0 auto;
  padding: 20px;
}

.detail-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
  flex-wrap: wrap;
  gap: 16px;
}

.detail-title {
  font-size: 28px;
  font-weight: 700;
  color: #1f2937;
  margin: 0;
}

.detail-actions {
  display: flex;
  gap: 12px;
  align-items: center;
}

.card {
  background: white;
  border-radius: 12px;
  box-shadow: 0 1px 3px rgba(0,0,0,0.1);
  margin-bottom: 24px;
  overflow: hidden;
}

.card-header {
  padding: 20px 24px;
  border-bottom: 1px solid #e5e7eb;
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
}

.card-title {
  font-size: 18px;
  font-weight: 700;
  color: #1f2937;
  display: flex;
  align-items: center;
  gap: 8px;
}

.card-body {
  padding: 24px;
}

.grid-2 {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 24px;
}

.info-group {
  margin-bottom: 20px;
}

.info-label {
  font-size: 13px;
  font-weight: 600;
  color: #6b7280;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  margin-bottom: 8px;
}

.info-value {
  font-size: 16px;
  color: #1f2937;
  font-weight: 500;
}

.info-value strong {
  color: #3b82f6;
}

.status-badge {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 8px 16px;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 600;
}

.status-badge.warning {
  background: #fef3c7;
  color: #78350f;
}

.status-badge.info {
  background: #dbeafe;
  color: #1e40af;
}

.status-badge.primary {
  background: #e0e7ff;
  color: #3730a3;
}

.status-badge.success {
  background: #d1fae5;
  color: #065f46;
}

.status-badge.danger {
  background: #fee2e2;
  color: #991b1b;
}

.btn {
  padding: 12px 24px;
  border-radius: 10px;
  text-decoration: none;
  font-weight: 600;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  transition: all 0.3s;
  border: none;
  cursor: pointer;
  font-size: 14px;
}

.btn-primary {
  background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
  color: white;
  box-shadow: 0 2px 8px rgba(59, 130, 246, 0.25);
}

.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 16px rgba(59, 130, 246, 0.4);
}

.btn-secondary {
  background: #ffffff;
  color: #6b7280;
  border: 1.5px solid #e5e7eb;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}

.btn-secondary:hover {
  border-color: #9ca3af;
  color: #374151;
  transform: translateY(-1px);
}

.price-highlight {
  font-size: 32px;
  font-weight: 700;
  color: #059669;
  margin: 16px 0;
}

.quantity-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 16px;
  margin-top: 16px;
}

.quantity-item {
  text-align: center;
  padding: 16px;
  background: #f9fafb;
  border-radius: 8px;
  border: 1px solid #e5e7eb;
}

.quantity-item .label {
  font-size: 12px;
  color: #6b7280;
  margin-bottom: 8px;
  text-transform: uppercase;
  font-weight: 600;
}

.quantity-item .value {
  font-size: 24px;
  font-weight: 700;
  color: #3b82f6;
}

@media (max-width: 768px) {
  .grid-2 {
    grid-template-columns: 1fr;
  }
  
  .quantity-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}
</style>

<div class="detail-container">
  <div class="detail-header">
    <h1 class="detail-title">
      <i class="fas fa-calendar-check"></i> Chi tiết Booking: <?= safe_html($booking['ma_booking']) ?>
    </h1>
    <div class="detail-actions">
      <a href="<?= BASE_URL ?>?act=admin-bookings" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Quay lại
      </a>
      <a href="<?= BASE_URL ?>?act=admin-booking-edit&id=<?= $booking['id'] ?>" class="btn btn-primary">
        <i class="fas fa-edit"></i> Cập nhật
      </a>
      <a href="<?= BASE_URL ?>?act=admin-booking-delete&id=<?= $booking['id'] ?>" 
         class="btn" 
         style="background: #fee2e2; color: #991b1b; border: 1px solid #fecaca;"
         onclick="return confirm('Bạn có chắc muốn xóa booking này? Số chỗ sẽ được cộng lại vào lịch khởi hành.')">
        <i class="fas fa-trash"></i> Xóa
      </a>
    </div>
  </div>

  <!-- Thông tin khách -->
  <div class="card">
    <div class="card-header">
      <h2 class="card-title">
        <i class="fas fa-user"></i> Thông tin khách hàng
      </h2>
      <?= getStatusBadge($booking['trang_thai'], $statusList) ?>
    </div>
    <div class="card-body">
      <div class="grid-2">
        <div class="info-group">
          <div class="info-label">Họ tên</div>
          <div class="info-value">
            <i class="fas fa-user"></i> <strong><?= safe_html($booking['ho_ten']) ?></strong>
          </div>
        </div>
        <div class="info-group">
          <div class="info-label">Số điện thoại</div>
          <div class="info-value">
            <i class="fas fa-phone"></i> <?= safe_html($booking['so_dien_thoai']) ?>
          </div>
        </div>
        <div class="info-group">
          <div class="info-label">Email</div>
          <div class="info-value">
            <i class="fas fa-envelope"></i> <?= safe_html($booking['email'] ?? 'N/A') ?>
          </div>
        </div>
        <?php if (!empty($booking['dia_chi'])): ?>
        <div class="info-group">
          <div class="info-label">Địa chỉ</div>
          <div class="info-value">
            <i class="fas fa-map-marker-alt"></i> <?= safe_html($booking['dia_chi']) ?>
          </div>
        </div>
        <?php endif; ?>
        <div class="info-group">
          <div class="info-label">Ngày đặt</div>
          <div class="info-value">
            <i class="fas fa-calendar"></i> <?= formatDateTime($booking['ngay_dat']) ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Thông tin tour & lịch khởi hành -->
  <div class="card">
    <div class="card-header">
      <h2 class="card-title">
        <i class="fas fa-map-marked-alt"></i> Thông tin Tour & Lịch khởi hành
      </h2>
    </div>
    <div class="card-body">
      <div class="grid-2">
        <div>
          <div class="info-group">
            <div class="info-label">Tour</div>
            <div class="info-value">
              <strong><?= safe_html($booking['ten_tour'] ?? 'N/A') ?></strong>
              <?php if ($booking['ma_tour']): ?>
                <br><small style="color: #6b7280;">Mã: <?= safe_html($booking['ma_tour']) ?></small>
              <?php endif; ?>
            </div>
          </div>
          <div class="info-group">
            <div class="info-label">Nơi xuất phát</div>
            <div class="info-value">
              <i class="fas fa-map-marker-alt"></i> <?= safe_html($booking['noi_xuat_phat'] ?? 'N/A') ?>
            </div>
          </div>
        </div>
        <div>
          <div class="info-group">
            <div class="info-label">Ngày khởi hành</div>
            <div class="info-value">
              <i class="fas fa-calendar-day"></i> <strong><?= formatDate($booking['ngay_khoi_hanh']) ?></strong>
              <?php if ($booking['ngay_ket_thuc']): ?>
                <br><small style="color: #6b7280;">Đến: <?= formatDate($booking['ngay_ket_thuc']) ?></small>
              <?php endif; ?>
            </div>
          </div>
          <?php if ($booking['gio_khoi_hanh']): ?>
          <div class="info-group">
            <div class="info-label">Giờ khởi hành</div>
            <div class="info-value">
              <i class="fas fa-clock"></i> <?= date('H:i', strtotime($booking['gio_khoi_hanh'])) ?>
            </div>
          </div>
          <?php endif; ?>
          <?php if ($booking['diem_tap_trung']): ?>
          <div class="info-group">
            <div class="info-label">Điểm tập trung</div>
            <div class="info-value">
              <i class="fas fa-map-pin"></i> <?= safe_html($booking['diem_tap_trung']) ?>
            </div>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <!-- Số lượng khách & Tổng tiền -->
  <div class="card">
    <div class="card-header">
      <h2 class="card-title">
        <i class="fas fa-users"></i> Số lượng khách & Tổng tiền
      </h2>
    </div>
    <div class="card-body">
      <div class="quantity-grid">
        <div class="quantity-item">
          <div class="label">Người lớn</div>
          <div class="value"><?= $booking['so_nguoi_lon'] ?? 0 ?></div>
        </div>
        <div class="quantity-item">
          <div class="label">Trẻ em</div>
          <div class="value"><?= $booking['so_tre_em'] ?? 0 ?></div>
        </div>
        <div class="quantity-item">
          <div class="label">Trẻ nhỏ</div>
          <div class="value"><?= $booking['so_tre_nho'] ?? 0 ?></div>
        </div>
      </div>
      <div style="text-align: center; margin-top: 32px; padding-top: 32px; border-top: 2px solid #e5e7eb;">
        <div class="info-label">Tổng tiền</div>
        <div class="price-highlight"><?= formatPrice($booking['tong_tien']) ?></div>
        <?php if ($booking['tien_dat_coc'] > 0): ?>
          <div style="margin-top: 12px; color: #059669; font-size: 16px; font-weight: 600;">
            <i class="fas fa-money-check-alt"></i> Đã đặt cọc: <?= formatPrice($booking['tien_dat_coc']) ?>
          </div>
        <?php endif; ?>
        <?php if ($booking['ngay_thanh_toan']): ?>
          <div style="margin-top: 12px; color: #2563eb; font-size: 14px;">
            <i class="fas fa-calendar-check"></i> Ngày thanh toán: <?= formatDateTime($booking['ngay_thanh_toan']) ?>
          </div>
        <?php endif; ?>
        <?php if ($booking['gia_nguoi_lon']): ?>
          <div style="margin-top: 16px; color: #6b7280; font-size: 14px;">
            <small>
              Giá NL: <?= formatPrice($booking['gia_nguoi_lon']) ?> | 
              Giá TE: <?= formatPrice($booking['gia_tre_em'] ?? 0) ?> | 
              Giá TN: <?= formatPrice($booking['gia_tre_nho'] ?? 0) ?>
            </small>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Ghi chú -->
  <?php if ($booking['ghi_chu']): ?>
  <div class="card">
    <div class="card-header">
      <h2 class="card-title">
        <i class="fas fa-sticky-note"></i> Ghi chú
      </h2>
    </div>
    <div class="card-body">
      <div class="info-value" style="white-space: pre-wrap; line-height: 1.6;">
        <?= nl2br(safe_html($booking['ghi_chu'])) ?>
      </div>
    </div>
  </div>
  <?php endif; ?>
</div>

