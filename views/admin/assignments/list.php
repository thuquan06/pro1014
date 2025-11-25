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
  background: white;
  border: 1px solid var(--border);
  border-radius: 12px;
  padding: 20px;
  margin-bottom: 20px;
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
  font-size: 14px;
  color: var(--text-dark);
}

.filter-group select {
  padding: 10px 14px;
  border: 1px solid var(--border);
  border-radius: 8px;
  font-size: 14px;
}

.filter-actions {
  display: flex;
  gap: 12px;
}

.assignments-card {
  background: white;
  border: 1px solid var(--border);
  border-radius: 12px;
  overflow: hidden;
}

.assignments-table {
  width: 100%;
  border-collapse: collapse;
}

.assignments-table thead {
  background: var(--bg-light);
}

.assignments-table th {
  padding: 16px;
  text-align: left;
  font-weight: 700;
  font-size: 14px;
  color: var(--text-dark);
  border-bottom: 2px solid var(--border);
}

.assignments-table td {
  padding: 16px;
  border-bottom: 1px solid var(--border);
  font-size: 14px;
}

.assignments-table tbody tr:hover {
  background: var(--bg-light);
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
    <i class="fas fa-calendar-plus" style="color: var(--primary);"></i>
    Quản lý Phân công HDV
  </h1>
  
  <a href="<?= BASE_URL ?>?act=admin-assignment-create" class="btn-primary">
    <i class="fas fa-plus"></i>
    Phân công HDV mới
  </a>
</div>

<!-- Filter Section -->
<div class="filter-card">
  <form method="GET" action="<?= BASE_URL ?>?act=admin-assignments">
    <div class="filter-row">
      <div class="filter-group">
        <label>Lịch khởi hành</label>
        <select name="id_lich_khoi_hanh">
          <option value="">Tất cả</option>
          <?php
          $departurePlanModel = new DeparturePlanModel();
          $allDeparturePlans = $departurePlanModel->getAllDeparturePlans();
          foreach ($allDeparturePlans as $dp):
            $selected = (isset($filters['id_lich_khoi_hanh']) && $filters['id_lich_khoi_hanh'] == $dp['id']) ? 'selected' : '';
            $ngay_gio = '';
            if ($dp['ngay_khoi_hanh']) {
              $ngay_gio = date('d/m/Y', strtotime($dp['ngay_khoi_hanh']));
              if ($dp['gio_khoi_hanh']) {
                $ngay_gio .= ' ' . date('H:i', strtotime($dp['gio_khoi_hanh']));
              }
            }
          ?>
            <option value="<?= $dp['id'] ?>" <?= $selected ?>>
              <?= safe_html($dp['tengoi'] ?? 'Tour') ?> - <?= $ngay_gio ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      
      <div class="filter-group">
        <label>Hướng dẫn viên</label>
        <select name="id_hdv">
          <option value="">Tất cả</option>
          <?php
          $guideModel = new GuideModel();
          $allGuides = $guideModel->getAllGuides(['trang_thai' => 1]);
          foreach ($allGuides as $guide):
            $selected = (isset($filters['id_hdv']) && $filters['id_hdv'] == $guide['id']) ? 'selected' : '';
          ?>
            <option value="<?= $guide['id'] ?>" <?= $selected ?>>
              <?= safe_html($guide['ho_ten']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      
      <div class="filter-group">
        <label>Trạng thái</label>
        <select name="trang_thai">
          <option value="">Tất cả</option>
          <option value="1" <?= (isset($filters['trang_thai']) && $filters['trang_thai'] == 1) ? 'selected' : '' ?>>Đã phân công</option>
          <option value="0" <?= (isset($filters['trang_thai']) && $filters['trang_thai'] == 0) ? 'selected' : '' ?>>Đã hủy</option>
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
    <table class="assignments-table">
      <thead>
        <tr>
          <th>STT</th>
          <th>HDV</th>
          <th>Tour</th>
          <th>Lịch khởi hành</th>
          <th>Vai trò</th>
          <th>Ngày bắt đầu</th>
          <th>Ngày kết thúc</th>
          <th>Lương</th>
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
            <td>
              <strong><?= safe_html($assignment['ho_ten'] ?? 'N/A') ?></strong>
              <?php if ($assignment['email']): ?>
                <br><small style="color: var(--text-light);"><?= safe_html($assignment['email']) ?></small>
              <?php endif; ?>
            </td>
            <td>
              <strong><?= safe_html($assignment['ten_tour'] ?? 'N/A') ?></strong>
              <?php if ($assignment['id_tour']): ?>
                <br><small style="color: var(--text-light);">ID: <?= $assignment['id_tour'] ?></small>
              <?php endif; ?>
            </td>
            <td>
              <?php if ($assignment['ngay_khoi_hanh']): ?>
                <?= formatDate($assignment['ngay_khoi_hanh']) ?>
                <?php if ($assignment['gio_khoi_hanh']): ?>
                  <br><small><?= date('H:i', strtotime($assignment['gio_khoi_hanh'])) ?></small>
                <?php endif; ?>
              <?php else: ?>
                <span style="color: var(--text-light);">-</span>
              <?php endif; ?>
            </td>
            <td>
              <?php
              $roleClass = 'main';
              if (strpos($assignment['vai_tro'], 'phụ') !== false) {
                $roleClass = 'sub';
              } elseif (strpos($assignment['vai_tro'], 'Trợ lý') !== false) {
                $roleClass = 'assistant';
              }
              ?>
              <span class="role-badge <?= $roleClass ?>">
                <?= safe_html($assignment['vai_tro'] ?? 'HDV chính') ?>
              </span>
            </td>
            <td><strong><?= formatDate($assignment['ngay_bat_dau']) ?></strong></td>
            <td><strong><?= formatDate($assignment['ngay_ket_thuc']) ?></strong></td>
            <td>
              <?php if ($assignment['luong']): ?>
                <strong><?= number_format($assignment['luong'], 0, ',', '.') ?></strong> VNĐ
              <?php else: ?>
                <span style="color: var(--text-light);">-</span>
              <?php endif; ?>
            </td>
            <td>
              <?= ($assignment['trang_thai'] == 1) 
                  ? '<span class="status-badge success"><i class="fas fa-check-circle"></i> Đã phân công</span>'
                  : '<span class="status-badge danger"><i class="fas fa-ban"></i> Đã hủy</span>' ?>
            </td>
            <td>
              <a href="<?= BASE_URL ?>?act=admin-assignment-edit&id=<?= $assignment['id'] ?>" 
                 class="btn-action edit" 
                 title="Sửa">
                <i class="fas fa-edit"></i>
              </a>
              <a href="<?= BASE_URL ?>?act=admin-assignment-toggle&id=<?= $assignment['id'] ?>" 
                 class="btn-action <?= $assignment['trang_thai'] == 1 ? 'danger' : 'success' ?>" 
                 title="Đổi trạng thái"
                 onclick="return confirm('Bạn có chắc muốn đổi trạng thái?')">
                <i class="fas fa-toggle-<?= $assignment['trang_thai'] == 1 ? 'on' : 'off' ?>"></i>
              </a>
              <a href="<?= BASE_URL ?>?act=admin-assignment-delete&id=<?= $assignment['id'] ?>" 
                 class="btn-action delete" 
                 title="Xóa"
                 onclick="return confirm('Bạn có chắc muốn xóa phân công này?')">
                <i class="fas fa-trash"></i>
              </a>
            </td>
          </tr>
        <?php $cnt++; endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <div class="empty-state">
      <i class="fas fa-calendar-times"></i>
      <p>Không tìm thấy phân công nào</p>
      <a href="<?= BASE_URL ?>?act=admin-assignment-create" class="btn-primary" style="margin-top: 16px; display: inline-block;">
        <i class="fas fa-plus"></i> Tạo phân công đầu tiên
      </a>
    </div>
  <?php endif; ?>
</div>



