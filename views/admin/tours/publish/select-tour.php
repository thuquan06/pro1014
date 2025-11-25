<?php
/**
 * Select Tour to Publish - Modern Interface
 * Updated: 2025-11-25
 */

ob_start();
?>

<style>
.select-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
}

.select-title {
  font-size: 28px;
  font-weight: 700;
  color: var(--text-dark);
  margin: 0;
}

.select-card {
  background: white;
  border: 1px solid var(--border);
  border-radius: 12px;
  overflow: hidden;
}

.select-table {
  width: 100%;
  border-collapse: collapse;
}

.select-table thead {
  background: var(--bg-light);
}

.select-table th {
  padding: 14px 24px;
  text-align: left;
  font-weight: 600;
  font-size: 13px;
  color: var(--text-dark);
  text-transform: uppercase;
  letter-spacing: 0.5px;
  border-bottom: 2px solid var(--border);
}

.select-table td {
  padding: 16px 24px;
  border-bottom: 1px solid var(--border);
  font-size: 14px;
  color: var(--text-dark);
}

.select-table tbody tr:hover {
  background: var(--bg-light);
}

.status-badge {
  display: inline-flex;
  align-items: center;
  gap: 4px;
  padding: 6px 12px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 600;
}

.status-badge.draft {
  background: #f3f4f6;
  color: #6b7280;
}

.status-badge.internal {
  background: #fef3c7;
  color: #f59e0b;
}

.status-badge.public {
  background: #d1fae5;
  color: #10b981;
}

.btn-check {
  padding: 8px 16px;
  background: var(--primary);
  color: white;
  border: none;
  border-radius: 8px;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: 6px;
}

.btn-check:hover {
  background: #1e40af;
  transform: translateY(-2px);
  color: white;
  text-decoration: none;
}

.empty-state {
  text-align: center;
  padding: 80px 20px;
  color: var(--text-light);
}

.empty-state i {
  font-size: 80px;
  opacity: 0.3;
  margin-bottom: 20px;
}

.empty-state h3 {
  font-size: 20px;
  margin: 20px 0 12px;
  color: var(--text-dark);
}
</style>

<!-- Page Header -->
<div class="select-header">
  <h1 class="select-title">
    <i class="fas fa-rocket" style="color: var(--primary);"></i>
    Chọn tour để publish
  </h1>
  <a href="<?= BASE_URL ?>?act=admin-tours" class="btn btn-secondary">
    <i class="fas fa-arrow-left"></i>
    Quay lại
  </a>
</div>

<!-- Table -->
<div class="select-card">
  <?php if (!empty($allTours)): ?>
    <table class="select-table">
      <thead>
        <tr>
          <th width="80">ID</th>
          <th>Tên tour</th>
          <th width="150">Trạng thái</th>
          <th width="150">Thao tác</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($allTours as $tour): ?>
          <tr>
            <td><strong>#<?= $tour['id_goi'] ?? $tour['id'] ?></strong></td>
            <td><?= htmlspecialchars($tour['tengoi'] ?? $tour['ten_goi'] ?? 'N/A') ?></td>
            <td>
              <?php
              $status = $tour['publish_status'] ?? 'draft';
              $badges = [
                'draft' => '<span class="status-badge draft"><i class="fas fa-file-alt"></i> Draft</span>',
                'internal' => '<span class="status-badge internal"><i class="fas fa-lock"></i> Nội bộ</span>',
                'public' => '<span class="status-badge public"><i class="fas fa-globe"></i> Công khai</span>'
              ];
              echo $badges[$status] ?? $badges['draft'];
              ?>
            </td>
            <td>
              <a href="<?= BASE_URL ?>?act=tour-publish&id_goi=<?= $tour['id_goi'] ?? $tour['id'] ?>" 
                 class="btn-check">
                <i class="fas fa-check-square"></i>
                Kiểm tra
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <div class="empty-state">
      <i class="fas fa-inbox"></i>
      <h3>Chưa có tour nào</h3>
      <p>Hãy tạo tour đầu tiên</p>
      <br>
      <a href="<?= BASE_URL ?>?act=admin-tour-create" class="btn btn-primary">
        <i class="fas fa-plus-circle"></i>
        Tạo tour mới
      </a>
    </div>
  <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
include './views/admin/layout.php';
?>
