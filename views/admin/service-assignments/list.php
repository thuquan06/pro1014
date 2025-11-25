<?php
/**
 * Danh sách Gán dịch vụ - Modern Interface
 * UC-Assign-Services: Quản lý gán dịch vụ với trạng thái xác nhận
 */

function safe_html($value) {
    return htmlentities($value ?? '', ENT_QUOTES, 'UTF-8');
}

function formatDate($date) {
    return $date ? date('d/m/Y', strtotime($date)) : '-';
}

$filters = $filters ?? [];
$serviceTypes = $serviceTypes ?? [];
$statuses = $statuses ?? [];
?>

<style>
.assignments-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
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
}

.filter-group {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.filter-group label {
  font-weight: 600;
  font-size: 14px;
}

.filter-group select {
  padding: 10px 14px;
  border: 1px solid var(--border);
  border-radius: 8px;
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
  border-bottom: 2px solid var(--border);
}

.assignments-table td {
  padding: 16px;
  border-bottom: 1px solid var(--border);
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

.status-badge.cho {
  background: #fef3c7;
  color: #78350f;
}

.status-badge.da_xac_nhan {
  background: #d1fae5;
  color: #065f46;
}

.status-badge.huy {
  background: #fee2e2;
  color: #991b1b;
}

.btn-action {
  padding: 6px 10px;
  border: none;
  border-radius: 6px;
  font-size: 12px;
  font-weight: 600;
  cursor: pointer;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: 4px;
  margin: 2px;
}

.btn-action.confirm {
  background: #d1fae5;
  color: #065f46;
}

.btn-action.cancel {
  background: #fee2e2;
  color: #991b1b;
}

.btn-action.edit {
  background: #fef3c7;
  color: #78350f;
}
</style>

<div class="assignments-header">
  <h1 class="assignments-title">
    <i class="fas fa-tasks" style="color: var(--primary);"></i>
    Gán Dịch vụ
  </h1>
  
  <a href="<?= BASE_URL ?>?act=admin-service-assignment-create" class="btn-primary">
    <i class="fas fa-plus"></i>
    Gán dịch vụ mới
  </a>
</div>

<div class="filter-card">
  <form method="GET" action="<?= BASE_URL ?>?act=admin-service-assignments">
    <div class="filter-row">
      <div class="filter-group">
        <label>Loại dịch vụ</label>
        <select name="loai_dich_vu">
          <option value="">Tất cả</option>
          <?php foreach ($serviceTypes as $key => $label): ?>
            <option value="<?= $key ?>" <?= (isset($filters['loai_dich_vu']) && $filters['loai_dich_vu'] == $key) ? 'selected' : '' ?>>
              <?= safe_html($label) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      
      <div class="filter-group">
        <label>Trạng thái</label>
        <select name="trang_thai">
          <option value="">Tất cả</option>
          <?php foreach ($statuses as $key => $label): ?>
            <option value="<?= $key ?>" <?= (isset($filters['trang_thai']) && $filters['trang_thai'] == $key) ? 'selected' : '' ?>>
              <?= safe_html($label) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>
    
    <div style="margin-top: 16px;">
      <button type="submit" class="btn-primary">
        <i class="fas fa-search"></i> Tìm kiếm
      </button>
      <a href="<?= BASE_URL ?>?act=admin-service-assignments" class="btn-cancel">
        <i class="fas fa-times"></i> Xóa bộ lọc
      </a>
    </div>
  </form>
</div>

<div class="assignments-card">
  <?php if (!empty($assignments)): ?>
    <table class="assignments-table">
      <thead>
        <tr>
          <th>STT</th>
          <th>Dịch vụ</th>
          <th>Tour</th>
          <th>Số lượng</th>
          <th>Ngày sử dụng</th>
          <th>Giá</th>
          <th>Trạng thái</th>
          <th>Thao tác</th>
        </tr>
      </thead>
      <tbody>
        <?php $cnt = 1; foreach ($assignments as $assignment): ?>
          <tr>
            <td><?= $cnt ?></td>
            <td>
              <strong><?= safe_html($assignment['ten_dich_vu'] ?? 'N/A') ?></strong>
              <br><small style="color: var(--text-light);"><?= safe_html($serviceTypes[$assignment['loai_dich_vu']] ?? $assignment['loai_dich_vu']) ?></small>
            </td>
            <td><?= safe_html($assignment['ten_tour'] ?? 'N/A') ?></td>
            <td><?= $assignment['so_luong'] ?? 1 ?> <?= safe_html($assignment['don_vi'] ?? '') ?></td>
            <td><?= formatDate($assignment['ngay_su_dung']) ?></td>
            <td>
              <?php if ($assignment['gia_thuc_te']): ?>
                <strong><?= number_format($assignment['gia_thuc_te'], 0, ',', '.') ?></strong> VNĐ
              <?php else: ?>
                <span style="color: var(--text-light);">-</span>
              <?php endif; ?>
            </td>
            <td>
              <span class="status-badge <?= $assignment['trang_thai'] ?>">
                <?= safe_html($statuses[$assignment['trang_thai']] ?? $assignment['trang_thai']) ?>
              </span>
            </td>
            <td>
              <?php if ($assignment['trang_thai'] == 'cho'): ?>
                <a href="<?= BASE_URL ?>?act=admin-service-assignment-confirm&id=<?= $assignment['id'] ?>" 
                   class="btn-action confirm"
                   onclick="return confirm('Xác nhận dịch vụ này?')">
                  <i class="fas fa-check"></i> Xác nhận
                </a>
              <?php endif; ?>
              <a href="<?= BASE_URL ?>?act=admin-service-assignment-edit&id=<?= $assignment['id'] ?>" class="btn-action edit">
                <i class="fas fa-edit"></i> Sửa
              </a>
              <?php if ($assignment['trang_thai'] != 'huy'): ?>
                <a href="<?= BASE_URL ?>?act=admin-service-assignment-cancel&id=<?= $assignment['id'] ?>" 
                   class="btn-action cancel"
                   onclick="return confirm('Hủy dịch vụ này?')">
                  <i class="fas fa-times"></i> Hủy
                </a>
              <?php endif; ?>
            </td>
          </tr>
        <?php $cnt++; endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <div style="padding: 60px; text-align: center; color: var(--text-light);">
      <i class="fas fa-inbox" style="font-size: 64px; opacity: 0.3;"></i>
      <p>Chưa có gán dịch vụ nào</p>
    </div>
  <?php endif; ?>
</div>



