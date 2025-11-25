<?php
/**
 * Danh sách Người Dùng - Admin View
 * UC-User-Management: Quản lý danh sách người dùng
 */

function safe_html($value) {
    return htmlentities($value ?? '', ENT_QUOTES, 'UTF-8');
}
?>

<style>
.users-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
  flex-wrap: wrap;
  gap: 16px;
}

.users-title {
  font-size: 28px;
  font-weight: 700;
  color: var(--text-dark);
  display: flex;
  align-items: center;
  gap: 12px;
}

.stats-cards {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 16px;
  margin-bottom: 24px;
}

.stat-card {
  background: white;
  border: 1px solid var(--border);
  border-radius: 12px;
  padding: 20px;
  display: flex;
  align-items: center;
  gap: 16px;
}

.stat-icon {
  width: 48px;
  height: 48px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 24px;
}

.stat-icon.total { background: #dbeafe; color: #1e40af; }
.stat-icon.active { background: #d1fae5; color: #065f46; }
.stat-icon.locked { background: #fee2e2; color: #991b1b; }

.stat-info h3 {
  font-size: 24px;
  font-weight: 700;
  color: var(--text-dark);
  margin: 0;
}

.stat-info p {
  font-size: 14px;
  color: var(--text-light);
  margin: 4px 0 0 0;
}

.search-bar {
  background: white;
  border: 1px solid var(--border);
  border-radius: 12px;
  padding: 20px;
  margin-bottom: 20px;
}

.search-form {
  display: flex;
  gap: 12px;
}

.search-form input {
  flex: 1;
  padding: 12px 16px;
  border: 1px solid var(--border);
  border-radius: 8px;
  font-size: 14px;
}

.search-form button {
  padding: 12px 24px;
  background: var(--primary);
  color: white;
  border: none;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 8px;
}

.search-form button:hover {
  background: #1e40af;
}

.users-card {
  background: white;
  border: 1px solid var(--border);
  border-radius: 12px;
  overflow: hidden;
}

.users-table {
  width: 100%;
  border-collapse: collapse;
}

.users-table thead {
  background: var(--bg-light);
}

.users-table th {
  padding: 16px;
  text-align: left;
  font-weight: 700;
  font-size: 14px;
  color: var(--text-dark);
  border-bottom: 2px solid var(--border);
}

.users-table td {
  padding: 16px;
  border-bottom: 1px solid var(--border);
}

.users-table tbody tr:hover {
  background: var(--bg-light);
}

.badge {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 6px 12px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 600;
}

.badge-success {
  background: #d1fae5;
  color: #065f46;
}

.badge-danger {
  background: #fee2e2;
  color: #991b1b;
}

.btn-group {
  display: flex;
  gap: 8px;
}

.btn-action {
  padding: 8px 16px;
  border-radius: 6px;
  font-size: 14px;
  font-weight: 600;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: 6px;
  transition: all 0.2s;
  border: none;
  cursor: pointer;
}

.btn-edit {
  background: #fef3c7;
  color: #92400e;
}

.btn-edit:hover {
  background: #fde68a;
}

.btn-delete {
  background: #fee2e2;
  color: #991b1b;
}

.btn-delete:hover {
  background: #fecaca;
}

.btn-toggle {
  background: #dbeafe;
  color: #1e40af;
}

.btn-toggle:hover {
  background: #bfdbfe;
}

.empty-state {
  text-align: center;
  padding: 60px 20px;
  color: var(--text-light);
}

.empty-state i {
  font-size: 64px;
  color: var(--border);
  margin-bottom: 16px;
}

.empty-state h3 {
  font-size: 20px;
  font-weight: 600;
  color: var(--text-dark);
  margin-bottom: 8px;
}
</style>

<!-- Page Header -->
<div class="users-header">
  <h1 class="users-title">
    <i class="fas fa-users"></i>
    Quản Lý Người Dùng
  </h1>
  <a href="<?= BASE_URL ?>?act=admin-user-create" class="btn-submit">
    <i class="fas fa-plus"></i>
    Thêm Người Dùng
  </a>
</div>

<!-- Stats Cards -->
<div class="stats-cards">
  <div class="stat-card">
    <div class="stat-icon total">
      <i class="fas fa-users"></i>
    </div>
    <div class="stat-info">
      <h3><?= $totalUsers ?? 0 ?></h3>
      <p>Tổng người dùng</p>
    </div>
  </div>
  
</div>

<!-- Search Bar -->
<div class="search-bar">
  <form method="GET" action="<?= BASE_URL ?>?act=admin-users" class="search-form">
    <input type="text" name="search" placeholder="Tìm kiếm theo tên, email, số điện thoại..." value="<?= safe_html($keyword ?? '') ?>">
    <button type="submit">
      <i class="fas fa-search"></i>
      Tìm kiếm
    </button>
    <?php if (!empty($keyword)): ?>
      <a href="<?= BASE_URL ?>?act=admin-users" class="btn-cancel" style="padding: 12px 24px;">
        <i class="fas fa-times"></i>
        Xóa bộ lọc
      </a>
    <?php endif; ?>
  </form>
</div>

<!-- Users Table -->
<?php if (empty($users)): ?>
  <div class="empty-state">
    <i class="fas fa-user-slash"></i>
    <h3>Chưa có người dùng nào</h3>
    <p><?= !empty($keyword) ? 'Không tìm thấy kết quả' : 'Thêm người dùng đầu tiên để bắt đầu' ?></p>
  </div>
<?php else: ?>
  <div class="users-card">
    <table class="users-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Họ Tên</th>
          <th>Email</th>
          <th>Số Điện Thoại</th>
          <th>Ngày Sinh</th>
          <th>Địa Chỉ</th>
          <th>Ngày Tạo</th>
          <th>Hành Động</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($users as $user): ?>
          <tr>
            <td><strong>#<?= $user['id_nguoidung'] ?></strong></td>
            <td>
              <strong><?= safe_html($user['hoten']) ?></strong>
            </td>
            <td><?= safe_html($user['id_email']) ?></td>
            <td><?= safe_html($user['sdt_nd'] ?? '-') ?></td>
            <td>
              <?php if (!empty($user['ngaysinh'])): ?>
                <?= safe_html($user['ngaysinh']) ?>
              <?php else: ?>
                -
              <?php endif; ?>
            </td>
            <td><?= safe_html($user['diachi'] ?? '-') ?></td>
            <td>
              <?= date('d/m/Y H:i', strtotime($user['ngaytao'])) ?>
            </td>
            <td>
              <div class="btn-group">
                <a href="<?= BASE_URL ?>?act=admin-user-edit&id=<?= $user['id_nguoidung'] ?>" class="btn-action btn-edit">
                  <i class="fas fa-edit"></i>
                  Sửa
                </a>
                <a href="<?= BASE_URL ?>?act=admin-user-delete&id=<?= $user['id_nguoidung'] ?>" 
                   class="btn-action btn-delete"
                   onclick="return confirm('Bạn có chắc muốn xóa người dùng này? Hành động này không thể hoàn tác!')">
                  <i class="fas fa-trash"></i>
                  Xóa
                </a>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>

