<?php
/**
 * Publish Dashboard - Modern Interface
 * Updated: 2025-11-25
 */

ob_start();
?>

<style>
.publish-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
}

.publish-title {
  font-size: 28px;
  font-weight: 700;
  color: var(--text-dark);
  margin: 0;
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 20px;
  margin-bottom: 24px;
}

.stat-card {
  background: white;
  border: 1px solid var(--border);
  border-radius: 12px;
  padding: 24px;
  transition: all 0.3s;
  cursor: pointer;
  text-decoration: none;
  display: block;
}

.stat-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
  text-decoration: none;
}

.stat-card.draft {
  border-left: 4px solid #9ca3af;
}

.stat-card.internal {
  border-left: 4px solid #f59e0b;
}

.stat-card.public {
  border-left: 4px solid #10b981;
}

.stat-icon {
  width: 50px;
  height: 50px;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 24px;
  margin-bottom: 16px;
}

.stat-icon.draft {
  background: #f3f4f6;
  color: #6b7280;
}

.stat-icon.internal {
  background: #fef3c7;
  color: #f59e0b;
}

.stat-icon.public {
  background: #d1fae5;
  color: #10b981;
}

.stat-number {
  font-size: 36px;
  font-weight: 700;
  color: var(--text-dark);
  margin: 0;
}

.stat-label {
  font-size: 14px;
  color: var(--text-light);
  margin-top: 4px;
}

.review-card {
  background: white;
  border: 1px solid var(--border);
  border-radius: 12px;
  overflow: hidden;
}

.review-header {
  padding: 20px 24px;
  background: var(--bg-light);
  border-bottom: 2px solid var(--border);
  display: flex;
  align-items: center;
  gap: 12px;
}

.review-header h3 {
  margin: 0;
  font-size: 18px;
  font-weight: 700;
  color: var(--text-dark);
}

.review-badge {
  background: var(--primary);
  color: white;
  padding: 4px 12px;
  border-radius: 12px;
  font-size: 12px;
  font-weight: 700;
}

.review-table {
  width: 100%;
  border-collapse: collapse;
}

.review-table thead {
  background: var(--bg-light);
}

.review-table th {
  padding: 12px 24px;
  text-align: left;
  font-weight: 600;
  font-size: 13px;
  color: var(--text-dark);
  text-transform: uppercase;
  letter-spacing: 0.5px;
  border-bottom: 2px solid var(--border);
}

.review-table td {
  padding: 16px 24px;
  border-bottom: 1px solid var(--border);
  font-size: 14px;
  color: var(--text-dark);
}

.review-table tbody tr:hover {
  background: var(--bg-light);
}

.empty-state {
  text-align: center;
  padding: 60px 20px;
  color: var(--text-light);
}

.empty-state i {
  font-size: 64px;
  opacity: 0.3;
  margin-bottom: 16px;
  color: #10b981;
}

.empty-state h4 {
  font-size: 18px;
  margin: 16px 0 8px;
  color: var(--text-dark);
}

.btn-review {
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

.btn-review:hover {
  background: #1e40af;
  transform: translateY(-2px);
  color: white;
  text-decoration: none;
}
</style>

<!-- Page Header -->
<div class="publish-header">
  <h1 class="publish-title">
    <i class="fas fa-rocket" style="color: var(--primary);"></i>
    Publish Dashboard
  </h1>
  <a href="<?= BASE_URL ?>?act=admin-tours" class="btn btn-secondary">
    <i class="fas fa-arrow-left"></i>
    Quay lại
  </a>
</div>

<!-- Stats Grid -->
<div class="stats-grid">
  <?php
  $stats = [
    'draft' => ['count' => 0, 'icon' => 'fa-file-alt', 'label' => 'Draft', 'class' => 'draft'],
    'internal' => ['count' => 0, 'icon' => 'fa-lock', 'label' => 'Nội bộ', 'class' => 'internal'],
    'public' => ['count' => 0, 'icon' => 'fa-globe', 'label' => 'Công khai', 'class' => 'public']
  ];
  
  foreach ($thongke as $tk) {
    if (isset($stats[$tk['publish_status']])) {
      $stats[$tk['publish_status']]['count'] = $tk['total'];
    }
  }
  ?>
  
  <?php foreach ($stats as $key => $stat): ?>
    <a href="<?= BASE_URL ?>?act=tour-publish-list&status=<?= $key ?>" class="stat-card <?= $stat['class'] ?>">
      <div class="stat-icon <?= $stat['class'] ?>">
        <i class="fas <?= $stat['icon'] ?>"></i>
      </div>
      <h2 class="stat-number"><?= $stat['count'] ?></h2>
      <p class="stat-label"><?= $stat['label'] ?></p>
    </a>
  <?php endforeach; ?>
</div>

<!-- Tours Need Review -->
<div class="review-card">
  <div class="review-header">
    <i class="fas fa-tasks" style="color: var(--primary); font-size: 20px;"></i>
    <h3>Tour cần review</h3>
    <span class="review-badge"><?= count($tourCanReview) ?></span>
  </div>
  
  <?php if (empty($tourCanReview)): ?>
    <div class="empty-state">
      <i class="fas fa-check-circle"></i>
      <h4>Tuyệt vời!</h4>
      <p>Không có tour nào cần review</p>
    </div>
  <?php else: ?>
    <table class="review-table">
      <thead>
        <tr>
          <th width="80">ID</th>
          <th>Tên tour</th>
          <th width="150">Ngày tạo</th>
          <th width="150">Thao tác</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($tourCanReview as $tour): ?>
          <tr>
            <td><strong>#<?= $tour['id_goi'] ?></strong></td>
            <td><?= htmlspecialchars($tour['tengoi']) ?></td>
            <td><?= date('d/m/Y', strtotime($tour['ngaydang'])) ?></td>
            <td>
              <a href="<?= BASE_URL ?>?act=tour-publish&id_goi=<?= $tour['id_goi'] ?>" 
                 class="btn-review">
                <i class="fas fa-check-square"></i>
                Review
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
include './views/admin/layout.php';
?>
