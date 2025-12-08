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
$bookingTypeList = $bookingTypeList ?? [];
$bookingDetails = $bookingDetails ?? [];
$bookingGuides = $bookingGuides ?? [];
$departurePlan = $departurePlan ?? null;

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
  font-size: 20px;
  font-weight: 700;
  color: #059669;
  margin: 8px 0;
}

.quantity-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 8px;
  margin-top: 8px;
}

.quantity-item {
  text-align: center;
  padding: 8px;
  background: #f9fafb;
  border-radius: 6px;
  border: 1px solid #e5e7eb;
}

.quantity-item .label {
  font-size: 10px;
  color: #6b7280;
  margin-bottom: 4px;
  text-transform: uppercase;
  font-weight: 600;
}

.quantity-item .value {
  font-size: 18px;
  font-weight: 700;
  color: #3b82f6;
}

.content-scrollable {
  max-height: 500px;
  overflow-y: auto;
  padding-right: 8px;
}

.content-scrollable::-webkit-scrollbar {
  width: 6px;
}

.content-scrollable::-webkit-scrollbar-track {
  background: #f1f5f9;
  border-radius: 10px;
}

.content-scrollable::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 10px;
}

.content-scrollable::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}

.itinerary-day-card .day-content img {
  max-width: 100%;
  height: auto;
  border-radius: 8px;
  margin: 12px 0;
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
      <?php 
      // Chỉ hiển thị nút xóa nếu booking ở trạng thái "Chờ xử lý" (0) hoặc "Đã hủy" (5)
      $trangThai = (int)($booking['trang_thai'] ?? 0);
      if ($trangThai == 0 || $trangThai == 5): 
      ?>
      <a href="<?= BASE_URL ?>?act=admin-booking-delete&id=<?= $booking['id'] ?>" 
         class="btn" 
         style="background: #fee2e2; color: #991b1b; border: 1px solid #fecaca;"
         onclick="return confirm('Bạn có chắc muốn xóa booking này? Số chỗ sẽ được cộng lại vào lịch khởi hành.')">
        <i class="fas fa-trash"></i> Xóa
      </a>
      <?php endif; ?>
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
        <div class="info-group">
          <div class="info-label">Loại booking</div>
          <div class="info-value">
            <i class="fas fa-users"></i> <?= safe_html($bookingTypeList[$booking['loai_booking'] ?? 1] ?? 'N/A') ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Thông tin tour -->
  <div class="card">
    <div class="card-header">
      <h2 class="card-title">
        <i class="fas fa-map-marked-alt"></i> Thông tin tour
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
          <?php if (!empty($bookingGuides) && is_array($bookingGuides)): ?>
            <div class="info-group">
              <div class="info-label">Hướng dẫn viên</div>
              <div style="display: grid; gap: 12px; margin-top: 8px;">
                <?php foreach ($bookingGuides as $hdv): ?>
                  <div style="background: #f9fafb; padding: 16px; border-radius: 8px; border: 1px solid #e5e7eb;">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                      <i class="fas fa-user-tie" style="color: #3b82f6;"></i>
                      <strong style="font-size: 15px;"><?= safe_html($hdv['ho_ten'] ?? 'N/A') ?></strong>
                      <span style="background: #e0e7ff; color: #3730a3; padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 600;">
                        <?= safe_html($hdv['vai_tro'] ?? 'HDV chính') ?>
                      </span>
                    </div>
                    <?php if (!empty($hdv['so_dien_thoai'])): ?>
                      <div style="color: #6b7280; font-size: 13px; margin-left: 28px;">
                        <i class="fas fa-phone"></i> <?= safe_html($hdv['so_dien_thoai']) ?>
                      </div>
                    <?php endif; ?>
                    <?php if (!empty($hdv['email'])): ?>
                      <div style="color: #6b7280; font-size: 13px; margin-left: 28px;">
                        <i class="fas fa-envelope"></i> <?= safe_html($hdv['email']) ?>
                      </div>
                    <?php endif; ?>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>
          <?php else: ?>
            <div class="info-group">
              <div class="info-label">Hướng dẫn viên</div>
              <div class="info-value" style="color: #9ca3af;">
                <i class="fas fa-user-tie"></i> Chưa phân công
              </div>
            </div>
          <?php endif; ?>
          
          <?php if (!empty($departurePlan['ghi_chu']) || !empty($departurePlan['ghi_chu_van_hanh'])): ?>
          <div class="info-group" style="grid-column: 1; margin-top: 16px; padding-top: 16px; border-top: 2px solid #e5e7eb;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
              <?php if (!empty($departurePlan['ghi_chu'])): ?>
              <div>
                <div class="info-label">Ghi chú nội bộ</div>
                <div class="info-value" style="white-space: normal; word-wrap: break-word; word-break: break-word; background: #f9fafb; padding: 12px; border-radius: 6px; line-height: 1.6; color: #374151;">
                  <?= nl2br(safe_html(trim($departurePlan['ghi_chu']))) ?>
                </div>
              </div>
              <?php endif; ?>
              
              <?php if (!empty($departurePlan['ghi_chu_van_hanh'])): ?>
              <div>
                <div class="info-label">Ghi chú vận hành</div>
                <div class="info-value" style="white-space: normal; word-wrap: break-word; word-break: break-word; background: #f9fafb; padding: 12px; border-radius: 6px; line-height: 1.6; color: #374151;">
                  <?= nl2br(safe_html(trim($departurePlan['ghi_chu_van_hanh']))) ?>
                </div>
              </div>
              <?php endif; ?>
            </div>
          </div>
          <?php endif; ?>
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
          <?php if ($booking['gio_tap_trung']): ?>
          <div class="info-group">
            <div class="info-label">Giờ tập trung</div>
            <div class="info-value">
              <i class="fas fa-clock"></i> <?= date('H:i', strtotime($booking['gio_tap_trung'])) ?>
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
          
          <?php if (!empty($departurePlan['phuong_tien'])): ?>
          <div class="info-group">
            <div class="info-label">Phương tiện</div>
            <div class="info-value">
              <i class="fas fa-bus"></i> <?= safe_html($departurePlan['phuong_tien']) ?>
            </div>
          </div>
          <?php endif; ?>
          
          <?php if (isset($departurePlan['so_cho'])): ?>
          <div class="info-group">
            <div class="info-label">Số chỗ</div>
            <div class="info-value">
              Tối đa: <strong><?= $departurePlan['so_cho'] ?? 'N/A' ?></strong>
              <?php if (isset($departurePlan['so_cho_da_dat'])): ?>
                <br>Đã đặt: <strong><?= $departurePlan['so_cho_da_dat'] ?></strong>
              <?php endif; ?>
              <?php if (isset($departurePlan['so_cho_con_lai'])): ?>
                <br>Còn lại: <strong style="color: #10b981;"><?= $departurePlan['so_cho_con_lai'] ?></strong>
              <?php endif; ?>
            </div>
          </div>
          <?php endif; ?>
          
          <?php if (isset($departurePlan['trang_thai'])): ?>
          <div class="info-group">
            <div class="info-label">Trạng thái lịch trình</div>
            <div class="info-value">
              <?php
              $statuses = [
                0 => ['text' => 'Đóng/Khóa', 'class' => 'danger', 'icon' => 'ban'],
                1 => ['text' => 'Đang mở', 'class' => 'success', 'icon' => 'check-circle'],
                2 => ['text' => 'Hết chỗ', 'class' => 'warning', 'icon' => 'exclamation-triangle'],
                3 => ['text' => 'Gần đầy', 'class' => 'info', 'icon' => 'info-circle']
              ];
              $statusInfo = $statuses[$departurePlan['trang_thai']] ?? $statuses[1];
              ?>
              <span class="status-badge <?= $statusInfo['class'] ?>"><i class="fas fa-<?= $statusInfo['icon'] ?>"></i> <?= $statusInfo['text'] ?></span>
            </div>
          </div>
          <?php endif; ?>
        </div>
        <div>
      </div>
    </div>
  </div>
  
  <!-- Lịch trình tour -->
  <?php if (!empty($departurePlan['chuongtrinh'])): ?>
  <div class="card">
    <div class="card-header">
      <h2 class="card-title">
        <i class="fas fa-route"></i> Lịch trình tour
      </h2>
    </div>
    <div class="card-body">
      <?php
      // Parse lịch trình theo ngày
      $chuongtrinh = html_entity_decode($departurePlan['chuongtrinh'], ENT_QUOTES, 'UTF-8');
      $days = [];
      
      if (!empty($chuongtrinh)) {
        // Tìm tất cả các vị trí có "NGÀY X"
        preg_match_all('/(?:NGÀY|Day|Ngày)\s*(\d+)(?:\s*:\s*([^<\n]+))?/i', $chuongtrinh, $matches, PREG_OFFSET_CAPTURE);
        
        if (!empty($matches[0])) {
          $markers = [];
          
          // Lấy tất cả các marker
          for ($i = 0; $i < count($matches[0]); $i++) {
            $dayNum = (int)$matches[1][$i][0];
            $pos = $matches[0][$i][1];
            $fullMatch = $matches[0][$i][0];
            $title = isset($matches[2][$i]) ? trim(strip_tags($matches[2][$i][0])) : '';
            
            // Tìm vị trí kết thúc của tag HTML chứa marker (nếu có)
            $afterText = substr($chuongtrinh, $pos, 500);
            $endPos = $pos + strlen($fullMatch);
            
            // Tìm tag đóng sau marker
            if (preg_match('/<\/[^>]+>/', $afterText, $closeTag, PREG_OFFSET_CAPTURE)) {
              $tagEnd = $pos + $closeTag[0][1] + strlen($closeTag[0][0]);
              if ($tagEnd > $endPos) {
                $endPos = $tagEnd;
              }
            }
            
            // Chỉ giữ marker đầu tiên của mỗi ngày
            if (!isset($markers[$dayNum]) || $markers[$dayNum]['pos'] > $pos) {
              $markers[$dayNum] = [
                'day' => $dayNum,
                'pos' => $pos,
                'end_pos' => $endPos,
                'title' => $title
              ];
            }
          }
          
          // Sắp xếp theo vị trí
          uasort($markers, function($a, $b) {
            return $a['pos'] - $b['pos'];
          });
          
          // Chia nội dung theo các marker
          $markerList = array_values($markers);
          
          for ($i = 0; $i < count($markerList); $i++) {
            $marker = $markerList[$i];
            $dayNum = $marker['day'];
            
            // Vị trí bắt đầu nội dung (sau marker)
            $contentStart = $marker['end_pos'];
            
            // Vị trí kết thúc (trước marker tiếp theo hoặc cuối chuỗi)
            $contentEnd = ($i < count($markerList) - 1) 
              ? $markerList[$i + 1]['pos'] 
              : strlen($chuongtrinh);
            
            // Lấy nội dung của ngày này
            $dayContent = substr($chuongtrinh, $contentStart, $contentEnd - $contentStart);
            $dayContent = trim($dayContent);
            
            // Loại bỏ header "NGÀY X" khỏi content nếu còn sót
            $dayContent = preg_replace('/<[^>]*>\s*(?:NGÀY|Day|Ngày)\s*\d+[^<]*\s*<\/[^>]*>/is', '', $dayContent);
            $dayContent = trim($dayContent);
            
            // Tạo title
            $dayTitle = 'Ngày ' . $dayNum;
            if (!empty($marker['title'])) {
              $dayTitle .= ': ' . htmlspecialchars($marker['title']);
            }
            
            // Thêm vào mảng days
            $days[$dayNum] = [
              'title' => $dayTitle,
              'content' => $dayContent
            ];
          }
        }
      }
      
      // Nếu không tìm thấy marker, hiển thị toàn bộ trong 1 ngày
      if (empty($days)) {
        $days[1] = [
          'title' => 'Ngày 1',
          'content' => $chuongtrinh
        ];
      }
      
      // Sắp xếp theo số ngày
      ksort($days);
      ?>
      
      <div class="content-scrollable" style="max-height: 500px; overflow-y: auto; padding-right: 8px;">
        <?php foreach ($days as $dayNum => $day): ?>
          <div class="itinerary-day-card" style="background: #f9fafb; border-radius: 8px; border-left: 4px solid #3b82f6; margin-bottom: 16px; overflow: hidden;">
            <div class="day-header" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); padding: 12px 16px; color: white;">
              <div class="day-number" style="font-size: 16px; font-weight: 700; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-calendar-day"></i>
                <?= $day['title'] ?>
              </div>
            </div>
            <div class="day-content" style="padding: 16px; background: white; line-height: 1.8; color: #374151;">
              <?= $day['content'] ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
  <?php endif; ?>

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
      <div style="text-align: center; margin-top: 16px; padding-top: 16px; border-top: 1px solid #e5e7eb;">
        <?php 
        $tongTienGoc = $booking['tong_tien'] + ($booking['voucher_discount'] ?? 0);
        $hasVoucher = !empty($booking['voucher_code']) && ($booking['voucher_discount'] ?? 0) > 0;
        ?>
        
        <?php if ($hasVoucher): ?>
          <div style="margin-bottom: 8px;">
            <div class="info-label" style="font-size: 11px;">Tổng tiền gốc</div>
            <div style="font-size: 14px; color: #6b7280; text-decoration: line-through;">
              <?= formatPrice($tongTienGoc) ?>
            </div>
            <div style="margin-top: 4px; color: #059669; font-size: 11px; font-weight: 600;">
              <i class="fas fa-tag"></i> Voucher: <?= safe_html($booking['voucher_code']) ?> - Giảm <?= formatPrice($booking['voucher_discount']) ?>
            </div>
          </div>
        <?php endif; ?>
        
        <div class="info-label" style="font-size: 11px;">Tổng tiền</div>
        <div class="price-highlight"><?= formatPrice($booking['tong_tien']) ?></div>
        
        <?php 
        $trangThai = (int)($booking['trang_thai'] ?? 0);
        $daThanhToan = ($trangThai == 3 || $trangThai == 4); // Đã thanh toán hoặc đã hoàn thành
        ?>
        
        <?php if ($daThanhToan): ?>
          <!-- Đã thanh toán hoặc đã hoàn thành -->
          <div style="margin-top: 8px; color: #059669; font-size: 13px; font-weight: 600;">
            <i class="fas fa-check-circle"></i> Đã thanh toán
          </div>
        <?php elseif ($booking['tien_dat_coc'] > 0): ?>
          <!-- Chỉ đặt cọc, chưa thanh toán -->
          <div style="margin-top: 8px; color: #059669; font-size: 13px; font-weight: 600;">
            <i class="fas fa-money-check-alt"></i> Đã đặt cọc: <?= formatPrice($booking['tien_dat_coc']) ?>
          </div>
          <?php 
          $conLai = $booking['tong_tien'] - $booking['tien_dat_coc'];
          if ($conLai > 0):
          ?>
            <div style="margin-top: 4px; color: #dc2626; font-size: 13px; font-weight: 600;">
              <i class="fas fa-exclamation-circle"></i> Còn lại: <?= formatPrice($conLai) ?>
            </div>
          <?php elseif ($conLai == 0): ?>
            <div style="margin-top: 4px; color: #059669; font-size: 11px;">
              <i class="fas fa-check-circle"></i> Đã thanh toán đủ
            </div>
          <?php endif; ?>
        <?php endif; ?>
        
        <?php if ($booking['ngay_thanh_toan']): ?>
          <div style="margin-top: 8px; color: #2563eb; font-size: 11px;">
            <i class="fas fa-calendar-check"></i> Ngày thanh toán: <?= formatDateTime($booking['ngay_thanh_toan']) ?>
          </div>
        <?php endif; ?>
        
        <?php if ($booking['gia_nguoi_lon']): ?>
          <div style="margin-top: 10px; padding-top: 10px; border-top: 1px solid #e5e7eb; color: #6b7280; font-size: 10px;">
            <i class="fas fa-info-circle"></i>
            Giá NL: <?= formatPrice($booking['gia_nguoi_lon']) ?> | 
            Giá TE: <?= formatPrice($booking['gia_tre_em'] ?? 0) ?> | 
            Giá TN: <?= formatPrice($booking['gia_tre_nho'] ?? 0) ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Danh sách khách chi tiết (nếu là nhóm/đoàn) -->
  <?php if (!empty($bookingDetails)): ?>
  <div class="card">
    <div class="card-header">
      <h2 class="card-title">
        <i class="fas fa-list"></i> Danh sách khách
      </h2>
    </div>
    <div class="card-body">
      <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
          <thead>
            <tr style="background: #f9fafb; border-bottom: 2px solid #e5e7eb;">
              <th style="padding: 12px; text-align: left; font-weight: 600; color: #6b7280;">STT</th>
              <th style="padding: 12px; text-align: left; font-weight: 600; color: #6b7280;">Họ tên</th>
              <th style="padding: 12px; text-align: left; font-weight: 600; color: #6b7280;">Giới tính</th>
              <th style="padding: 12px; text-align: left; font-weight: 600; color: #6b7280;">Ngày sinh</th>
              <th style="padding: 12px; text-align: left; font-weight: 600; color: #6b7280;">CMND/CCCD</th>
              <th style="padding: 12px; text-align: left; font-weight: 600; color: #6b7280;">SĐT</th>
              <th style="padding: 12px; text-align: left; font-weight: 600; color: #6b7280;">Loại khách</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($bookingDetails as $index => $detail): ?>
            <tr style="border-bottom: 1px solid #e5e7eb;">
              <td style="padding: 12px;"><?= $index + 1 ?></td>
              <td style="padding: 12px; font-weight: 600;"><?= safe_html($detail['ho_ten']) ?></td>
              <td style="padding: 12px;"><?= $detail['gioi_tinh'] === 1 ? 'Nam' : ($detail['gioi_tinh'] === 0 ? 'Nữ' : 'N/A') ?></td>
              <td style="padding: 12px;"><?= $detail['ngay_sinh'] ? formatDate($detail['ngay_sinh']) : 'N/A' ?></td>
              <td style="padding: 12px;"><?= safe_html($detail['so_cmnd_cccd'] ?? 'N/A') ?></td>
              <td style="padding: 12px;"><?= safe_html($detail['so_dien_thoai'] ?? 'N/A') ?></td>
              <td style="padding: 12px;">
                <?php
                $loaiKhachLabels = [1 => 'Người lớn', 2 => 'Trẻ em', 3 => 'Trẻ nhỏ', 4 => 'Em bé'];
                echo safe_html($loaiKhachLabels[$detail['loai_khach'] ?? 1] ?? 'N/A');
                ?>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <?php endif; ?>

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