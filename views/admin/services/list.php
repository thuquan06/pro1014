<?php
/**
 * Danh sách Dịch vụ - Modern Interface
 * UC-Assign-Services: Quản lý dịch vụ nội bộ/đối tác
 */

function safe_html($value) {
    return htmlentities($value ?? '', ENT_QUOTES, 'UTF-8');
}

$filters = $filters ?? [];
$serviceTypes = $serviceTypes ?? [];
?>

<style>
.services-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
  flex-wrap: wrap;
  gap: 16px;
}

.services-title {
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

.services-card {
  background: white;
  border: 1px solid var(--border);
  border-radius: 12px;
  overflow: hidden;
}

.services-table {
  width: 100%;
  border-collapse: collapse;
}

.services-table thead {
  background: var(--bg-light);
}

.services-table th {
  padding: 16px;
  text-align: left;
  font-weight: 700;
  font-size: 14px;
  border-bottom: 2px solid var(--border);
}

.services-table th:last-child {
  text-align: center;
}

.services-table td:last-child {
  text-align: center;
}

.services-table td {
  padding: 16px;
  border-bottom: 1px solid var(--border);
}

.services-table tbody tr:hover {
  background: var(--bg-light);
}

.type-badge {
  display: inline-block;
  padding: 4px 12px;
  border-radius: 12px;
  font-size: 12px;
  font-weight: 600;
}

.type-badge.xe { background: #dbeafe; color: #1e40af; }
.type-badge.khach_san { background: #d1fae5; color: #065f46; }
.type-badge.nha_hang { background: #fef3c7; color: #78350f; }
.type-badge.ve_tham_quan { background: #e0e7ff; color: #4338ca; }

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

.btn-action.edit {
  background: #fef3c7;
  color: #78350f;
}

.btn-action.delete {
  background: #fee2e2;
  color: #991b1b;
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

.filter-actions {
  display: flex;
  gap: 12px;
  align-items: center;
}
</style>

<div class="services-header">
  <h1 class="services-title">
    <i class="fas fa-concierge-bell" style="color: var(--primary);"></i>
    Quản lý Dịch vụ
  </h1>
  
  <a href="<?= BASE_URL ?>?act=admin-service-create" class="btn-primary">
    <i class="fas fa-plus"></i>
    Thêm dịch vụ mới
  </a>
</div>

<div class="filter-card">
  <form method="GET" action="<?= BASE_URL ?>">
    <input type="hidden" name="act" value="admin-services">
    <div class="filter-row">
      <div class="filter-group">
        <label>Tên dịch vụ</label>
        <input type="text" 
               name="ten_dich_vu" 
               value="<?= htmlspecialchars($filters['ten_dich_vu'] ?? '') ?>"
               placeholder="Nhập tên dịch vụ...">
      </div>
      
      <div class="filter-group">
        <label>Nhà cung cấp</label>
        <input type="text" 
               name="nha_cung_cap" 
               value="<?= htmlspecialchars($filters['nha_cung_cap'] ?? '') ?>"
               placeholder="Nhập nhà cung cấp...">
      </div>
      
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
          <option value="1" <?= (isset($filters['trang_thai']) && $filters['trang_thai'] == 1) ? 'selected' : '' ?>>Hoạt động</option>
          <option value="0" <?= (isset($filters['trang_thai']) && $filters['trang_thai'] == 0) ? 'selected' : '' ?>>Tạm dừng</option>
        </select>
      </div>
    </div>
    
    <div class="filter-actions">
      <button type="submit" class="btn-primary">
        <i class="fas fa-search"></i> Tìm kiếm
      </button>
      <a href="<?= BASE_URL ?>?act=admin-services" class="btn-cancel">
        <i class="fas fa-times"></i> Xóa bộ lọc
      </a>
    </div>
  </form>
</div>

<div class="services-card">
  <?php if (!empty($services)): ?>
    <table class="services-table">
      <thead>
        <tr>
          <th>STT</th>
          <th>Tên dịch vụ</th>
          <th>Loại</th>
          <th>Nhà cung cấp</th>
          <th>Giá</th>
          <th>Trạng thái</th>
          <th>Thao tác</th>
        </tr>
      </thead>
      <tbody>
        <?php $cnt = 1; foreach ($services as $service): ?>
          <tr>
            <td><?= $cnt ?></td>
            <td><strong><?= safe_html($service['ten_dich_vu']) ?></strong></td>
            <td>
              <span class="type-badge <?= $service['loai_dich_vu'] ?>">
                <?= safe_html($serviceTypes[$service['loai_dich_vu']] ?? $service['loai_dich_vu']) ?>
              </span>
            </td>
            <td><?= safe_html($service['nha_cung_cap'] ?? '-') ?></td>
            <td>
              <?php if ($service['gia']): ?>
                <strong><?= number_format($service['gia'], 0, ',', '.') ?></strong> VNĐ
                <?php if ($service['don_vi']): ?>
                  /<?= safe_html($service['don_vi']) ?>
                <?php endif; ?>
              <?php else: ?>
                <span style="color: var(--text-light);">-</span>
              <?php endif; ?>
            </td>
            <td>
              <?= ($service['trang_thai'] == 1) 
                  ? '<span class="status-badge success"><i class="fas fa-check-circle"></i> Hoạt động</span>'
                  : '<span class="status-badge danger"><i class="fas fa-ban"></i> Tạm dừng</span>' ?>
            </td>
            <td style="text-align: center;">
              <div style="display: inline-flex; gap: 4px; align-items: center; justify-content: center; flex-wrap: nowrap;">
                <a href="<?= BASE_URL ?>?act=admin-service-edit&id=<?= $service['id'] ?>" class="btn-action edit">
                  <i class="fas fa-edit"></i> Sửa
                </a>
                <a href="<?= BASE_URL ?>?act=admin-service-delete&id=<?= $service['id'] ?>" class="btn-action delete"
                   onclick="return confirm('Bạn có chắc muốn xóa?')">
                  <i class="fas fa-trash"></i> Xóa
                </a>
              </div>
            </td>
          </tr>
        <?php $cnt++; endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <div style="padding: 60px; text-align: center; color: var(--text-light);">
      <i class="fas fa-inbox" style="font-size: 64px; opacity: 0.3;"></i>
      <p>Chưa có dịch vụ nào</p>
    </div>
  <?php endif; ?>
</div>



