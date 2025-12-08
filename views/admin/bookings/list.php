<?php
/**
 * Danh sách Bookings - Modern Interface
 * UC-View-Booking: Xem danh sách và chi tiết đơn Booking
 */

function safe_html($value) {
    return htmlentities($value ?? '', ENT_QUOTES, 'UTF-8');
}

function formatDate($date) {
    return $date ? date('d/m/Y', strtotime($date)) : '-';
}

function formatDateTime($datetime) {
    return $datetime ? date('d/m/Y H:i', strtotime($datetime)) : '-';
}

function formatPrice($price) {
    return number_format($price, 0, ',', '.') . ' đ';
}

function getStatusBadge($status, $statusList) {
    $statusText = $statusList[$status] ?? 'Không xác định';
    $badgeClass = '';
    $icon = '';
    
    switch($status) {
        case 0: // Chờ xử lý
            $badgeClass = 'warning';
            $icon = 'fa-clock';
            break;
        case 1: // Đã liên hệ
            $badgeClass = 'info';
            $icon = 'fa-phone';
            break;
        case 2: // Đã đặt cọc
            $badgeClass = 'primary';
            $icon = 'fa-money-bill';
            break;
        case 3: // Đã thanh toán
            $badgeClass = 'success';
            $icon = 'fa-check-circle';
            break;
        case 4: // Hoàn thành
            $badgeClass = 'success';
            $icon = 'fa-check-double';
            break;
        case 5: // Hủy
            $badgeClass = 'danger';
            $icon = 'fa-times-circle';
            break;
        default:
            $badgeClass = 'secondary';
            $icon = 'fa-question';
    }
    
    return "<span class=\"status-badge {$badgeClass}\"><i class=\"fas {$icon}\"></i> {$statusText}</span>";
}

$filters = $filters ?? [];
$bookings = $bookings ?? [];
$tours = $tours ?? [];
$statusList = $statusList ?? [];
?>

<style>
.bookings-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
  flex-wrap: wrap;
  gap: 16px;
}

.bookings-title {
  font-size: 28px;
  font-weight: 700;
  color: var(--text-dark);
  margin: 0;
}

.filter-card {
  background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
  border: 1px solid var(--border);
  border-radius: 16px;
  padding: 24px;
  margin-bottom: 24px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
}

.filter-row {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 16px;
  margin-bottom: 16px;
}

.filter-group {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.filter-group label {
  font-weight: 600;
  font-size: 13px;
  color: #374151;
  margin-bottom: 6px;
  letter-spacing: 0.3px;
}

.filter-group select,
.filter-group input {
  padding: 12px 16px;
  border: 1.5px solid #e5e7eb;
  border-radius: 10px;
  font-size: 14px;
  background: white;
  color: var(--text-dark);
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  width: 100%;
  box-sizing: border-box;
  font-family: inherit;
}

.filter-group select {
  cursor: pointer;
  appearance: none;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236b7280' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right 14px center;
  padding-right: 40px;
}

.filter-group input {
  cursor: text;
}

.filter-group input::placeholder {
  color: #9ca3af;
  opacity: 1;
}

.filter-group select:hover,
.filter-group input:hover {
  border-color: #3b82f6;
  background-color: #f8fafc;
}

.filter-group select:focus,
.filter-group input:focus {
  outline: none;
  border-color: #3b82f6;
  box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
  background-color: white;
  transform: translateY(-1px);
}

.filter-actions {
  display: flex;
  gap: 12px;
  align-items: center;
}

.btn-primary {
  background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
  color: white;
  padding: 12px 24px;
  border-radius: 10px;
  text-decoration: none;
  font-weight: 600;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  border: none;
  cursor: pointer;
  font-size: 14px;
  box-shadow: 0 2px 8px rgba(59, 130, 246, 0.25);
}

.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 16px rgba(59, 130, 246, 0.4);
}

.btn-primary:active {
  transform: translateY(0);
  box-shadow: 0 1px 4px rgba(59, 130, 246, 0.2);
}

.btn-cancel {
  background: #ffffff;
  color: #6b7280;
  padding: 12px 24px;
  border-radius: 10px;
  text-decoration: none;
  font-weight: 600;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  border: 1.5px solid #e5e7eb;
  cursor: pointer;
  font-size: 14px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}

.btn-cancel:hover {
  border-color: #9ca3af;
  color: #374151;
  transform: translateY(-1px);
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.btn-cancel:active {
  transform: translateY(0);
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}

.btn-add {
  background: linear-gradient(135deg, #10b981 0%, #059669 100%);
  color: white;
  padding: 12px 24px;
  border-radius: 10px;
  text-decoration: none;
  font-weight: 600;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  border: none;
  cursor: pointer;
  font-size: 14px;
  box-shadow: 0 2px 8px rgba(16, 185, 129, 0.25);
}

.btn-add:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 16px rgba(16, 185, 129, 0.4);
}

.bookings-table-wrapper {
  overflow-x: auto;
  background: white;
  border-radius: 12px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.bookings-table {
  width: 100%;
  border-collapse: collapse;
  min-width: 1200px;
}

.bookings-table th {
  background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
  padding: 16px;
  text-align: left;
  font-weight: 600;
  font-size: 13px;
  color: #374151;
  border-bottom: 2px solid #e5e7eb;
  white-space: nowrap;
}

.bookings-table td {
  padding: 16px;
  border-bottom: 1px solid #f3f4f6;
  font-size: 14px;
  color: #6b7280;
}

.bookings-table tr:hover {
  background-color: #f9fafb;
}

.status-badge {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 6px 12px;
  border-radius: 8px;
  font-size: 12px;
  font-weight: 600;
  white-space: nowrap;
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

.status-badge.secondary {
  background: #f3f4f6;
  color: #6b7280;
}

.action-buttons {
  display: flex;
  flex-direction: column;
  gap: 8px;
  align-items: center;
  justify-content: center;
}

.action-buttons-row {
  display: flex;
  gap: 8px;
  align-items: center;
  justify-content: center;
}

.btn-action {
  padding: 8px 16px;
  border-radius: 8px;
  text-decoration: none;
  font-size: 13px;
  font-weight: 600;
  display: inline-flex;
  align-items: center;
  gap: 6px;
  transition: all 0.2s;
  border: none;
  cursor: pointer;
}

.btn-action.view {
  background: #dbeafe;
  color: #1e40af;
}

.btn-action.view:hover {
  background: #bfdbfe;
  transform: translateY(-1px);
}

.btn-action.edit {
  background: #fef3c7;
  color: #78350f;
}

.btn-action.edit:hover {
  background: #fde68a;
  transform: translateY(-1px);
}

.empty-state {
  text-align: center;
  padding: 60px 20px;
  color: #6b7280;
}

.empty-state i {
  font-size: 64px;
  color: #d1d5db;
  margin-bottom: 16px;
}

.empty-state h3 {
  font-size: 20px;
  margin-bottom: 8px;
  color: #374151;
}

.empty-state p {
  font-size: 14px;
  color: #6b7280;
}

.btn-action.delete {
  background: #fee2e2;
  color: #991b1b;
}

.btn-action.delete:hover {
  background: #fecaca;
  transform: translateY(-1px);
}

.btn-action.status {
  background: #e0e7ff;
  color: #3730a3;
}

.btn-action.status:hover {
  background: #c7d2fe;
  transform: translateY(-1px);
}

.status-dropdown {
  position: relative;
  display: inline-block;
}

.status-dropdown select {
  padding: 8px 32px 8px 12px;
  border: 1px solid #e5e7eb;
  border-radius: 8px;
  font-size: 13px;
  background: white;
  color: #374151;
  cursor: pointer;
  appearance: none;
  min-width: 140px;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236b7280' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right 8px center;
  transition: all 0.2s;
}

.status-dropdown select:hover {
  border-color: #3b82f6;
  background-color: #f8fafc;
}

.status-dropdown select:focus {
  outline: none;
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.action-buttons-row-bottom {
  display: flex;
  gap: 8px;
  align-items: center;
  justify-content: center;
}
</style>

<div class="bookings-header">
  <h1 class="bookings-title">
    <i class="fas fa-calendar-check"></i> Quản lý Booking
  </h1>
  <a href="<?= BASE_URL ?>?act=admin-booking-create" class="btn-add">
    <i class="fas fa-plus"></i> Thêm mới Booking
  </a>
</div>

<div class="filter-card">
  <form method="GET" action="<?= BASE_URL ?>">
    <input type="hidden" name="act" value="admin-bookings">
    <div class="filter-row">
      <div class="filter-group">
        <label>Tên khách</label>
        <input type="text" 
               name="ho_ten" 
               value="<?= htmlspecialchars($filters['ho_ten'] ?? '') ?>"
               placeholder="Nhập tên khách...">
      </div>
      
      <div class="filter-group">
        <label>Tour</label>
        <select name="id_tour">
          <option value="">Tất cả</option>
          <?php foreach ($tours as $tour): ?>
            <option value="<?= $tour['id_goi'] ?>" <?= (isset($filters['id_tour']) && $filters['id_tour'] == $tour['id_goi']) ? 'selected' : '' ?>>
              <?= safe_html($tour['tengoi'] ?? '') ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      
      <div class="filter-group">
        <label>Trạng thái</label>
        <select name="trang_thai">
          <option value="">Tất cả</option>
          <?php foreach ($statusList as $key => $label): ?>
            <option value="<?= $key ?>" <?= (isset($filters['trang_thai']) && $filters['trang_thai'] == $key) ? 'selected' : '' ?>>
              <?= safe_html($label) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>
    
    <div class="filter-actions">
      <button type="submit" class="btn-primary">
        <i class="fas fa-search"></i> Tìm kiếm
      </button>
      <a href="<?= BASE_URL ?>?act=admin-bookings" class="btn-cancel">
        <i class="fas fa-times"></i> Xóa bộ lọc
      </a>
    </div>
  </form>
</div>

<?php if (empty($bookings)): ?>
  <div class="empty-state">
    <i class="fas fa-inbox"></i>
    <h3>Chưa có đơn nào</h3>
    <p>Chưa có booking nào trong hệ thống.</p>
  </div>
<?php else: ?>
  <div class="bookings-table-wrapper">
    <table class="bookings-table">
      <thead>
        <tr>
          <th style="width: 120px;">Mã booking</th>
          <th style="width: 150px;">Tên khách</th>
          <th style="width: 200px;">Tour</th>
          <th style="width: 120px;">Ngày đi</th>
          <th style="width: 150px;">Tổng tiền</th>
          <th style="width: 150px;">Trạng thái</th>
          <th style="width: 150px;">Ngày đặt</th>
          <th style="width: 150px; text-align: center;">Thao tác</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($bookings as $booking): ?>
          <tr>
            <td>
              <strong style="color: #3b82f6;"><?= safe_html($booking['ma_booking']) ?></strong>
            </td>
            <td>
              <div><?= safe_html($booking['ho_ten']) ?></div>
              <small style="color: #9ca3af;"><?= safe_html($booking['so_dien_thoai']) ?></small>
            </td>
            <td>
              <div><?= safe_html($booking['ten_tour'] ?? 'N/A') ?></div>
              <?php if ($booking['ma_tour']): ?>
                <small style="color: #9ca3af;"><?= safe_html($booking['ma_tour']) ?></small>
              <?php endif; ?>
            </td>
            <td><?= formatDate($booking['ngay_khoi_hanh']) ?></td>
            <td>
              <strong style="color: #059669;"><?= formatPrice($booking['tong_tien']) ?></strong>
            </td>
            <td><?= getStatusBadge($booking['trang_thai'], $statusList) ?></td>
            <td><?= formatDateTime($booking['ngay_dat']) ?></td>
            <td>
              <div class="action-buttons">
                <div class="action-buttons-row">
                  <a href="<?= BASE_URL ?>?act=admin-booking-detail&id=<?= $booking['id'] ?>" class="btn-action view" title="Chi tiết">
                    <i class="fas fa-eye"></i>
                  </a>
                  <a href="<?= BASE_URL ?>?act=admin-booking-edit&id=<?= $booking['id'] ?>" class="btn-action edit" title="Sửa">
                    <i class="fas fa-edit"></i>
                  </a>
                  <?php 
                  // Chỉ hiển thị nút xóa nếu booking ở trạng thái "Chờ xử lý" (0) hoặc "Đã hủy" (5)
                  $trangThai = (int)($booking['trang_thai'] ?? 0);
                  if ($trangThai == 0 || $trangThai == 5): 
                  ?>
                  <a href="<?= BASE_URL ?>?act=admin-booking-delete&id=<?= $booking['id'] ?>" 
                     class="btn-action delete" 
                     title="Xóa"
                     onclick="return confirm('Bạn có chắc muốn xóa booking này? Số chỗ sẽ được cộng lại vào lịch khởi hành.')">
                    <i class="fas fa-trash"></i>
                  </a>
                  <?php endif; ?>
                </div>
                <div class="action-buttons-row-bottom">
                  <div class="status-dropdown">
                    <select onchange="quickChangeStatus(<?= $booking['id'] ?>, this.value)" title="Đổi trạng thái">
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
                  </div>
                </div>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>

<script>
function quickChangeStatus(bookingId, newStatus) {
  // Nếu chọn "Đã đặt cọc" (status = 2), hỏi số tiền đặt cọc
  let tienDatCoc = null;
  if (newStatus == 2) {
    tienDatCoc = prompt('Nhập số tiền đặt cọc (VNĐ):\n\nNhập 0 hoặc để trống nếu chưa có số tiền.', '0');
    if (tienDatCoc === null) {
      // Người dùng bấm Cancel
      location.reload(); // Reload để reset dropdown
      return;
    }
    // Chuyển đổi sang số (loại bỏ dấu phẩy, chấm)
    tienDatCoc = tienDatCoc.replace(/[.,\s]/g, '');
    if (tienDatCoc === '' || isNaN(tienDatCoc) || parseFloat(tienDatCoc) < 0) {
      alert('Số tiền đặt cọc không hợp lệ!');
      location.reload();
      return;
    }
  }

  if (!confirm('Bạn có chắc muốn đổi trạng thái booking này?')) {
    location.reload(); // Reload để reset dropdown
    return;
  }

  let body = 'id=' + bookingId + '&trang_thai=' + newStatus;
  if (tienDatCoc !== null) {
    body += '&tien_dat_coc=' + tienDatCoc;
  }

  fetch('<?= BASE_URL ?>?act=admin-booking-quick-change-status', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: body
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      alert('Cập nhật trạng thái thành công!');
      location.reload();
    } else {
      alert('Lỗi: ' + (data.message || 'Không thể cập nhật trạng thái'));
      location.reload();
    }
  })
  .catch(error => {
    console.error('Error:', error);
    alert('Có lỗi xảy ra. Vui lòng thử lại.');
    location.reload();
  });
}
</script>