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
  <form method="GET" action="<?= BASE_URL ?>?act=admin-services">
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
          <option value="1" <?= (isset($filters['trang_thai']) && $filters['trang_thai'] == 1) ? 'selected' : '' ?>>Hoạt động</option>
          <option value="0" <?= (isset($filters['trang_thai']) && $filters['trang_thai'] == 0) ? 'selected' : '' ?>>Tạm dừng</option>
        </select>
      </div>
    </div>
    
    <div style="margin-top: 16px;">
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
            <td>
              <a href="<?= BASE_URL ?>?act=admin-service-edit&id=<?= $service['id'] ?>" class="btn-action edit">
                <i class="fas fa-edit"></i> Sửa
              </a>
              <a href="<?= BASE_URL ?>?act=admin-service-delete&id=<?= $service['id'] ?>" class="btn-action delete"
                 onclick="return confirm('Bạn có chắc muốn xóa?')">
                <i class="fas fa-trash"></i> Xóa
              </a>
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

