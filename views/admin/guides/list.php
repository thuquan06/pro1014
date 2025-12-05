<?php
/**
 * Danh sách Hướng dẫn viên - Modern Interface
 * UC-Assign-Guide: Quản lý HDV với filter theo kỹ năng/tuyến/ngôn ngữ
 */

function safe_html($value) {
    return htmlentities($value ?? '', ENT_QUOTES, 'UTF-8');
}

function parseJsonArray($jsonString) {
    if (empty($jsonString)) return [];
    $decoded = json_decode($jsonString, true);
    return $decoded ?: [];
}

$filters = $filters ?? [];
?>

<style>
.guides-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
  flex-wrap: wrap;
  gap: 16px;
}

.guides-title {
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

.filter-group select,
.filter-group input {
  padding: 10px 14px;
  border: 1px solid var(--border);
  border-radius: 8px;
  font-size: 14px;
}

.filter-actions {
  display: flex;
  gap: 12px;
}

.btn-primary {
  background: var(--primary);
  color: white;
  padding: 10px 20px;
  border-radius: 8px;
  text-decoration: none;
  font-weight: 600;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  transition: all 0.2s;
  border: none;
  cursor: pointer;
  font-size: 14px;
}

.btn-primary:hover {
  background: var(--primary-dark);
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
}

.btn-cancel {
  background: #f3f4f6;
  color: #6b7280;
  padding: 10px 20px;
  border-radius: 8px;
  text-decoration: none;
  font-weight: 600;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  transition: all 0.2s;
  font-size: 14px;
}

.btn-cancel:hover {
  background: #e5e7eb;
  color: #374151;
}

.guides-card {
  background: white;
  border: 1px solid var(--border);
  border-radius: 12px;
  overflow: hidden;
}

.guides-table-wrapper {
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
}

.guides-table {
  width: 100%;
  border-collapse: collapse;
  table-layout: auto;
}

.guides-table th,
.guides-table td {
  overflow: hidden;
  text-overflow: ellipsis;
}

.guides-table th:nth-child(1),
.guides-table td:nth-child(1) {
  width: 40px;
  min-width: 40px;
  max-width: 40px;
}

.guides-table th:nth-child(2),
.guides-table td:nth-child(2) {
  width: 120px;
  min-width: 120px;
  white-space: normal;
}

.guides-table th:nth-child(3),
.guides-table td:nth-child(3) {
  width: 140px;
  min-width: 140px;
  white-space: normal;
  font-size: 12px;
}

.guides-table th:nth-child(4),
.guides-table td:nth-child(4),
.guides-table th:nth-child(5),
.guides-table td:nth-child(5),
.guides-table th:nth-child(6),
.guides-table td:nth-child(6) {
  width: 100px;
  min-width: 100px;
  white-space: normal;
}

.guides-table th:nth-child(7),
.guides-table td:nth-child(7),
.guides-table th:nth-child(8),
.guides-table td:nth-child(8) {
  width: 80px;
  min-width: 80px;
  text-align: center;
}

.guides-table th:nth-child(9),
.guides-table td:nth-child(9) {
  width: 100px;
  min-width: 100px;
}

.guides-table th:nth-child(10),
.guides-table td:nth-child(10) {
  width: 140px;
  min-width: 140px;
}

.guides-table thead {
  background: var(--bg-light);
}

.guides-table th {
  padding: 10px 6px;
  text-align: left;
  font-weight: 700;
  font-size: 12px;
  color: var(--text-dark);
  border-bottom: 2px solid var(--border);
}

.guides-table th:last-child {
  text-align: center;
}

.guides-table td:last-child {
  text-align: center;
}

.guides-table td {
  padding: 10px 6px;
  border-bottom: 1px solid var(--border);
  font-size: 12px;
}

.guides-table tbody tr:hover {
  background: var(--bg-light);
}

.badge-tag {
  display: inline-block;
  padding: 4px 12px;
  border-radius: 12px;
  font-size: 12px;
  font-weight: 600;
  margin: 2px;
}

.badge-skill {
  background: #dbeafe;
  color: #1e40af;
}

.badge-route {
  background: #d1fae5;
  color: #065f46;
}

.badge-language {
  background: #fef3c7;
  color: #78350f;
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

.btn-action.assign {
  background: #dbeafe;
  color: #1e40af;
}

.btn-action.assign:hover {
  background: #3b82f6;
  color: white;
}

.btn-action.success {
  background: #d1fae5;
  color: #065f46;
}

.btn-action.success:hover {
  background: #10b981;
  color: white;
}

.btn-action.danger {
  background: #fee2e2;
  color: #991b1b;
}

.btn-action.danger:hover {
  background: #ef4444;
  color: white;
}

.btn-action.info {
  background: #dbeafe;
  color: #1e40af;
}

.btn-action.info:hover {
  background: #3b82f6;
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
<div class="guides-header">
  <h1 class="guides-title">
    <i class="fas fa-user-tie" style="color: var(--primary);"></i>
    Quản lý Hướng dẫn viên
  </h1>
  
  <a href="<?= BASE_URL ?>?act=admin-guide-create" class="btn-primary">
    <i class="fas fa-plus"></i>
    Thêm HDV mới
  </a>
</div>

<!-- Filter Section -->
<div class="filter-card">
  <form method="GET" action="<?= BASE_URL ?>?act=admin-guides">
    <div class="filter-row">
      <div class="filter-group">
        <label>Kỹ năng</label>
        <input type="text" name="ky_nang" value="<?= safe_html($filters['ky_nang'] ?? '') ?>" placeholder="VD: Hiking, Swimming">
      </div>
      
      <div class="filter-group">
        <label>Tuyến chuyên</label>
        <input type="text" name="tuyen_chuyen" value="<?= safe_html($filters['tuyen_chuyen'] ?? '') ?>" placeholder="VD: Miền Bắc, Miền Trung">
      </div>
      
      <div class="filter-group">
        <label>Ngôn ngữ</label>
        <input type="text" name="ngon_ngu" value="<?= safe_html($filters['ngon_ngu'] ?? '') ?>" placeholder="VD: English, 中文">
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
      <a href="<?= BASE_URL ?>?act=admin-guides" class="btn-cancel">
        <i class="fas fa-times"></i> Xóa bộ lọc
      </a>
    </div>
  </form>
</div>

<!-- Guides Table -->
<div class="guides-card">
  <?php if (!empty($guides)): ?>
    <div class="guides-table-wrapper">
    <table class="guides-table">
      <thead>
        <tr>
          <th>STT</th>
          <th>Họ tên</th>
          <th>Liên hệ</th>
          <th>Kỹ năng</th>
          <th>Tuyến chuyên</th>
          <th>Ngôn ngữ</th>
          <th>Kinh nghiệm</th>
          <th>Đánh giá</th>
          <th>Trạng thái</th>
          <th>Thao tác</th>
        </tr>
      </thead>
      <tbody>
        <?php 
        $cnt = 1;
        foreach ($guides as $guide): 
          $kyNang = parseJsonArray($guide['ky_nang'] ?? '[]');
          $tuyenChuyen = parseJsonArray($guide['tuyen_chuyen'] ?? '[]');
          $ngonNgu = parseJsonArray($guide['ngon_ngu'] ?? '[]');
        ?>
          <tr>
            <td><?= $cnt ?></td>
            <td><strong><?= safe_html($guide['ho_ten']) ?></strong></td>
            <td>
              <?php if ($guide['email']): ?>
                <div><i class="fas fa-envelope"></i> <?= safe_html($guide['email']) ?></div>
              <?php endif; ?>
              <?php if ($guide['so_dien_thoai']): ?>
                <div><i class="fas fa-phone"></i> <?= safe_html($guide['so_dien_thoai']) ?></div>
              <?php endif; ?>
            </td>
            <td>
              <?php foreach ($kyNang as $skill): ?>
                <span class="badge-tag badge-skill"><?= safe_html($skill) ?></span>
              <?php endforeach; ?>
              <?php if (empty($kyNang)): ?>
                <span style="color: var(--text-light);">-</span>
              <?php endif; ?>
            </td>
            <td>
              <?php foreach ($tuyenChuyen as $route): ?>
                <span class="badge-tag badge-route"><?= safe_html($route) ?></span>
              <?php endforeach; ?>
              <?php if (empty($tuyenChuyen)): ?>
                <span style="color: var(--text-light);">-</span>
              <?php endif; ?>
            </td>
            <td>
              <?php foreach ($ngonNgu as $lang): ?>
                <span class="badge-tag badge-language"><?= safe_html($lang) ?></span>
              <?php endforeach; ?>
              <?php if (empty($ngonNgu)): ?>
                <span style="color: var(--text-light);">-</span>
              <?php endif; ?>
            </td>
            <td><?= $guide['kinh_nghiem'] ?? 0 ?> năm</td>
            <td>
              <strong><?= number_format($guide['danh_gia'] ?? 0, 1) ?></strong>
              <i class="fas fa-star" style="color: #fbbf24;"></i>
            </td>
            <td>
              <?= ($guide['trang_thai'] == 1) 
                  ? '<span class="status-badge success"><i class="fas fa-check-circle"></i> Hoạt động</span>'
                  : '<span class="status-badge danger"><i class="fas fa-ban"></i> Tạm dừng</span>' ?>
            </td>
            <td style="text-align: center;">
              <div style="display: inline-flex; gap: 4px; align-items: center; justify-content: center; flex-wrap: nowrap;">
                <a href="<?= BASE_URL ?>?act=admin-guide-edit&id=<?= $guide['id'] ?>" 
                   class="btn-action edit" 
                   title="Sửa">
                  <i class="fas fa-edit"></i>
                </a>
                <a href="<?= BASE_URL ?>?act=admin-assignment-create&id_hdv=<?= $guide['id'] ?>" 
                   class="btn-action assign" 
                   title="Phân công">
                  <i class="fas fa-calendar-plus"></i>
                </a>
                <a href="<?= BASE_URL ?>?act=admin-guide-toggle&id=<?= $guide['id'] ?>" 
                   class="btn-action <?= $guide['trang_thai'] == 1 ? 'danger' : 'success' ?>" 
                   title="Đổi trạng thái"
                   onclick="return confirm('Bạn có chắc muốn đổi trạng thái?')">
                  <i class="fas fa-toggle-<?= $guide['trang_thai'] == 1 ? 'on' : 'off' ?>"></i>
                </a>
                <a href="<?= BASE_URL ?>?act=admin-guide-delete&id=<?= $guide['id'] ?>" 
                   class="btn-action delete" 
                   title="Xóa"
                   onclick="return confirm('Bạn có chắc muốn xóa HDV này?')">
                  <i class="fas fa-trash"></i>
                </a>
              </div>
            </td>
          </tr>
        <?php $cnt++; endforeach; ?>
      </tbody>
    </table>
    </div>
  <?php else: ?>
    <div class="empty-state">
      <i class="fas fa-user-slash"></i>
      <p>Không tìm thấy HDV nào</p>
    </div>
  <?php endif; ?>
</div>



