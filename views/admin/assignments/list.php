<?php
/**
 * Danh sách Phân công HDV - Modern Interface
 * UC-Assign-Guide: Quản lý phân công HDV
 */

function safe_html($value) {
    return htmlentities($value ?? '', ENT_QUOTES, 'UTF-8');
}

function formatDate($date) {
    return $date ? date('d/m/Y', strtotime($date)) : '-';
}

$filters = $filters ?? [];
?>

<style>
.assignments-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
  flex-wrap: wrap;
  gap: 16px;
}

.assignments-title {
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
  display: flex;
  gap: 16px;
  align-items: flex-end;
  flex-wrap: nowrap;
  margin-bottom: 16px;
}

.filter-group {
  flex: 1;
  min-width: 0;
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
  background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
}

.btn-primary:active {
  transform: translateY(0);
  box-shadow: 0 2px 8px rgba(59, 130, 246, 0.25);
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
  background: #f9fafb;
  color: #374151;
  border-color: #d1d5db;
  transform: translateY(-1px);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.08);
}

.assignments-card {
  background: white;
  border: 1px solid var(--border);
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
}

.assignments-table-wrapper {
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
}

.assignments-table {
  width: 100%;
  border-collapse: collapse;
  table-layout: auto;
}

.assignments-table th,
.assignments-table td {
  overflow: hidden;
  text-overflow: ellipsis;
}

.assignments-table th:nth-child(1),
.assignments-table td:nth-child(1) {
  width: 40px;
  min-width: 40px;
  max-width: 40px;
}

.assignments-table th:nth-child(2),
.assignments-table td:nth-child(2) {
  width: 140px;
  min-width: 140px;
  white-space: normal;
}

.assignments-table th:nth-child(3),
.assignments-table td:nth-child(3) {
  width: 150px;
  min-width: 150px;
  white-space: normal;
}

.assignments-table th:nth-child(4),
.assignments-table td:nth-child(4) {
  width: 120px;
  min-width: 120px;
  white-space: normal;
}

.assignments-table th:nth-child(5),
.assignments-table td:nth-child(5) {
  width: 100px;
  min-width: 100px;
}

.assignments-table th:nth-child(6),
.assignments-table td:nth-child(6),
.assignments-table th:nth-child(7),
.assignments-table td:nth-child(7) {
  width: 100px;
  min-width: 100px;
  white-space: normal;
}

.assignments-table th:nth-child(8),
.assignments-table td:nth-child(8) {
  width: 120px;
  min-width: 120px;
}

.assignments-table th:nth-child(9),
.assignments-table td:nth-child(9) {
  width: 120px;
  min-width: 120px;
}

.assignments-table th:nth-child(10),
.assignments-table td:nth-child(10) {
  width: 140px;
  min-width: 140px;
}

.assignments-table thead {
  background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
  border-bottom: 2px solid #e5e7eb;
}

.assignments-table th {
  padding: 10px 6px;
  text-align: left;
  font-weight: 700;
  font-size: 12px;
  color: var(--text-dark);
  border-bottom: 2px solid var(--border);
}

.assignments-table td {
  padding: 10px 6px;
  border-bottom: 1px solid var(--border);
  font-size: 12px;
}

.assignments-table th:last-child {
  text-align: center;
}

.assignments-table td:last-child {
  text-align: center;
}

.assignments-table tbody tr {
  transition: all 0.2s ease;
}

.assignments-table tbody tr:hover {
  background: linear-gradient(90deg, #f8fafc 0%, #ffffff 100%);
  transform: scale(1.001);
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
}

.status-badge {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 6px 12px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 600;
}

.status-badge.success {
  background: #d1fae5;
  color: #065f46;
}

.status-badge.danger {
  background: #fee2e2;
  color: #991b1b;
}

.status-badge.warning {
  background: #fef3c7;
  color: #78350f;
}

.role-badge {
  display: inline-block;
  padding: 4px 12px;
  border-radius: 12px;
  font-size: 12px;
  font-weight: 600;
}

.role-badge.main {
  background: #dbeafe;
  color: #1e40af;
}

.role-badge.sub {
  background: #fef3c7;
  color: #78350f;
}

.role-badge.assistant {
  background: #e0e7ff;
  color: #4338ca;
}

.btn-action {
  padding: 6px 10px;
  border: none;
  border-radius: 6px;
  font-size: 12px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: 4px;
  margin: 2px;
}

.btn-action.edit {
  background: #fef3c7;
  color: #78350f;
}

.btn-action.edit:hover {
  background: #f59e0b;
  color: white;
}

.btn-action.delete {
  background: #fee2e2;
  color: #991b1b;
}

.btn-action.delete:hover {
  background: #ef4444;
  color: white;
}

.empty-state {
  padding: 60px 20px;
  text-align: center;
  color: var(--text-light);
}

.empty-state i {
  font-size: 64px;
  opacity: 0.3;
  margin-bottom: 16px;
}
</style>

<!-- Page Header -->
<div class="assignments-header">
  <h1 class="assignments-title">
    <i class="fas fa-user-tie" style="color: var(--primary);"></i>
    Danh sách Phân công HDV
  </h1>
</div>

<!-- Filter Section -->
<div class="filter-card">
  <form method="GET" action="<?= BASE_URL ?>">
    <input type="hidden" name="act" value="admin-assignments">
    <div class="filter-row">
      <div class="filter-group">
        <label>Tên tour</label>
        <input type="text" 
               name="ten_tour" 
               value="<?= htmlspecialchars($filters['ten_tour'] ?? '') ?>"
               placeholder="Nhập tên tour...">
      </div>
      
      <div class="filter-group">
        <label>Tên HDV</label>
        <input type="text" 
               name="ten_hdv" 
               value="<?= htmlspecialchars($filters['ten_hdv'] ?? '') ?>"
               placeholder="Nhập tên HDV...">
      </div>
      
      <div class="filter-group">
        <label>Lịch khởi hành</label>
        <select name="id_lich_khoi_hanh">
          <option value="">Tất cả</option>
          <?php foreach ($departurePlans ?? [] as $plan): ?>
            <option value="<?= $plan['id'] ?>" <?= (isset($filters['id_lich_khoi_hanh']) && $filters['id_lich_khoi_hanh'] == $plan['id']) ? 'selected' : '' ?>>
              <?= htmlspecialchars($plan['ten_tour'] ?? 'Tour #' . $plan['id']) ?> - <?= date('d/m/Y', strtotime($plan['ngay_khoi_hanh'] ?? 'now')) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>
    
    <div class="filter-actions">
      <button type="submit" class="btn-primary">
        <i class="fas fa-search"></i> Tìm kiếm
      </button>
      <a href="<?= BASE_URL ?>?act=admin-assignments" class="btn-cancel">
        <i class="fas fa-times"></i> Xóa bộ lọc
      </a>
    </div>
  </form>
</div>

<!-- Assignments Table -->
<div class="assignments-card">
  <?php if (!empty($assignments)): ?>
    <div class="assignments-table-wrapper">
    <table class="assignments-table">
      <thead>
        <tr>
          <th>STT</th>
          <th>Mã lịch</th>
          <th>HDV</th>
          <th>Tour</th>
          <th>Ngày khởi hành</th>
          <th>Vai trò</th>
          <th>Trạng thái</th>
          <th>Thao tác</th>
        </tr>
      </thead>
      <tbody>
        <?php 
        $cnt = 1;
        foreach ($assignments as $assignment): 
        ?>
          <tr>
            <td><?= $cnt ?></td>
            <?php
              $maLich = '';
              if (!empty($assignment['id_lich_khoi_hanh'])) {
                  $maLich = '#' . $assignment['id_lich_khoi_hanh'];
              } elseif (!empty($assignment['id'])) {
                  $maLich = '#' . $assignment['id'];
              } else {
                  $maLich = 'N/A';
              }
            ?>
            <td>
              <strong style="color: #3b82f6;"><?= safe_html($maLich) ?></strong>
            </td>
            <td>
              <strong><?= safe_html($assignment['ten_hdv'] ?? 'N/A') ?></strong>
              <?php if (!empty($assignment['email_hdv'])): ?>
                <br><small style="color: var(--text-light);"><?= safe_html($assignment['email_hdv']) ?></small>
              <?php endif; ?>
              <?php if (!empty($assignment['sdt_hdv'])): ?>
                <br><small style="color: var(--text-light);"><?= safe_html($assignment['sdt_hdv']) ?></small>
              <?php endif; ?>
            </td>
            <td>
              <strong><?= safe_html($assignment['ten_tour'] ?? 'N/A') ?></strong>
              <?php if (!empty($assignment['ma_tour'])): ?>
                <br><small style="color: var(--text-light);">Mã: <?= safe_html($assignment['ma_tour']) ?></small>
              <?php endif; ?>
            </td>
            <td>
              <?php if ($assignment['ngay_khoi_hanh_lich']): ?>
                <strong><?= formatDate($assignment['ngay_khoi_hanh_lich']) ?></strong>
                <?php if ($assignment['gio_khoi_hanh']): ?>
                  <br><small style="color: var(--text-light);"><?= date('H:i', strtotime($assignment['gio_khoi_hanh'])) ?></small>
                <?php endif; ?>
              <?php else: ?>
                <span style="color: var(--text-light);">-</span>
              <?php endif; ?>
            </td>
            <td>
              <?php
              $roleClass = 'main';
              if (strpos($assignment['vai_tro'] ?? '', 'phụ') !== false) {
                $roleClass = 'sub';
              } elseif (strpos($assignment['vai_tro'] ?? '', 'Trợ lý') !== false) {
                $roleClass = 'assistant';
              }
              ?>
              <span class="role-badge <?= $roleClass ?>">
                <?= safe_html($assignment['vai_tro'] ?? 'HDV chính') ?>
              </span>
            </td>
            <td>
              <?php
              // Hiển thị trạng thái phân công
              $trangThaiPC = (int)($assignment['trang_thai'] ?? 0);
              if ($trangThaiPC == 0) {
                  $statusText = 'Ready';
                  $statusClass = 'info';
              } elseif ($trangThaiPC == 1) {
                  $statusText = 'Đang diễn ra';
                  $statusClass = 'success';
              } elseif ($trangThaiPC == 2) {
                  $statusText = 'Hoàn thành';
                  $statusClass = 'success';
              } else {
                  $statusText = 'Tạm dừng';
                  $statusClass = 'warning';
              }
              
              // Nếu có booking, hiển thị thêm trạng thái booking
              if (!empty($assignment['trang_thai_booking'])) {
              $statusList = [
                0 => 'Chờ xử lý',
                1 => 'Đã liên hệ',
                2 => 'Đã đặt cọc',
                3 => 'Đã thanh toán',
                4 => 'Hoàn thành',
                5 => 'Hủy'
              ];
                  $trangThai = (int)$assignment['trang_thai_booking'];
                  $bookingStatusText = $statusList[$trangThai] ?? 'Không xác định';
              }
              ?>
              <span class="status-badge <?= $statusClass ?>">
                <i class="fas fa-circle"></i> <?= $statusText ?>
              </span>
              <?php if (!empty($bookingStatusText)): ?>
                <br><small style="color: var(--text-light); font-size: 11px;">Booking: <?= $bookingStatusText ?></small>
              <?php endif; ?>
            </td>
            <td style="text-align: center;">
              <?php if (!empty($assignment['id_booking'])): ?>
              <a href="<?= BASE_URL ?>?act=admin-booking-detail&id=<?= $assignment['id_booking'] ?>" 
                   class="btn-action edit" 
                   title="Xem chi tiết booking"
                   style="margin-right: 8px;">
                  <i class="fas fa-shopping-cart"></i>
                </a>
              <?php endif; ?>
              <a href="<?= BASE_URL ?>?act=admin-assignment-edit&id=<?= $assignment['id'] ?>" 
                 class="btn-action edit" 
                 title="Xem chi tiết phân công">
                <i class="fas fa-eye"></i>
              </a>
            </td>
          </tr>
        <?php $cnt++; endforeach; ?>
      </tbody>
    </table>
    </div>
  <?php else: ?>
    <div class="empty-state">
      <i class="fas fa-calendar-times"></i>
      <p>Không tìm thấy phân công nào</p>
      <p style="margin-top: 8px; color: var(--text-light); font-size: 14px;">
        Phân công HDV được quản lý từ lịch khởi hành. Vui lòng tạo lịch khởi hành và phân công HDV.
      </p>
    </div>
  <?php endif; ?>
</div>











