<?php
/**
 * Tours List by Status - Modern Interface
 * Updated: 2025-11-25
 */

ob_start();
?>

<style>
.list-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
}

.list-title {
  font-size: 28px;
  font-weight: 700;
  color: var(--text-dark);
  margin: 0;
  display: flex;
  align-items: center;
  gap: 12px;
}

.list-subtitle {
  font-size: 14px;
  color: var(--text-light);
  margin-top: 4px;
}

.list-card {
  background: white;
  border: 1px solid var(--border);
  border-radius: 12px;
  overflow: hidden;
  margin-bottom: 24px;
}

.list-table {
  width: 100%;
  border-collapse: collapse;
}

.list-table thead {
  background: var(--bg-light);
}

.list-table th {
  padding: 14px 24px;
  text-align: left;
  font-weight: 600;
  font-size: 13px;
  color: var(--text-dark);
  text-transform: uppercase;
  letter-spacing: 0.5px;
  border-bottom: 2px solid var(--border);
}

.list-table td {
  padding: 16px 24px;
  border-bottom: 1px solid var(--border);
  font-size: 14px;
  color: var(--text-dark);
}

.list-table tbody tr:hover {
  background: var(--bg-light);
}

.tour-name {
  font-weight: 600;
  color: var(--text-dark);
}

.tour-location {
  font-size: 12px;
  color: var(--text-light);
  margin-top: 4px;
}

.price-text {
  font-weight: 600;
  color: #10b981;
}

.btn-action-small {
  padding: 6px 12px;
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
  margin-right: 4px;
}

.btn-action-small.primary {
  background: #dbeafe;
  color: #1e40af;
}

.btn-action-small.primary:hover {
  background: var(--primary);
  color: white;
}

.btn-action-small.warning {
  background: #fef3c7;
  color: #78350f;
}

.btn-action-small.warning:hover {
  background: #f59e0b;
  color: white;
}

.btn-action-small.info {
  background: #e0f2fe;
  color: #075985;
}

.btn-action-small.info:hover {
  background: #0ea5e9;
  color: white;
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 20px;
}

.stat-box {
  background: white;
  border: 1px solid var(--border);
  border-radius: 12px;
  padding: 20px;
  text-align: center;
}

.stat-icon {
  font-size: 40px;
  color: var(--primary);
  margin-bottom: 12px;
}

.stat-value {
  font-size: 32px;
  font-weight: 700;
  color: var(--text-dark);
  margin: 0;
}

.stat-label {
  font-size: 14px;
  color: var(--text-light);
  margin-top: 4px;
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
<div class="list-header">
  <div>
    <h1 class="list-title">
      <?php
      $icons = [
        'draft' => '<i class="fas fa-file-alt"></i>',
        'internal' => '<i class="fas fa-lock"></i>',
        'public' => '<i class="fas fa-globe"></i>'
      ];
      echo $icons[$status] ?? '';
      ?>
      Tour <?= $statusName ?>
    </h1>
    <p class="list-subtitle">Tổng: <?= count($tours) ?> tour</p>
  </div>
  <div style="display: flex; gap: 12px;">
    <a href="<?= BASE_URL ?>?act=tour-publish-dashboard" class="btn btn-secondary">
      <i class="fas fa-arrow-left"></i>
      Quay lại Dashboard
    </a>
  </div>
</div>

<!-- Table -->
<div class="list-card">
  <?php if (empty($tours)): ?>
    <div class="empty-state">
      <i class="fas fa-inbox"></i>
      <h3>Không có tour nào</h3>
      <p>Chưa có tour <?= $statusName ?></p>
    </div>
  <?php else: ?>
    <table class="list-table">
      <thead>
        <tr>
          <th width="80">ID</th>
          <th>Tên tour</th>
          <th width="120">Giá</th>
          <th width="100">Số ngày</th>
          <th width="150">Ngày tạo</th>
          <th width="220">Thao tác</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($tours as $tour): ?>
          <tr>
            <td><strong>#<?= $tour['id_goi'] ?></strong></td>
            <td>
              <div class="tour-name"><?= htmlspecialchars($tour['tengoi']) ?></div>
              <?php if (!empty($tour['vitri'])): ?>
                <div class="tour-location">
                  <i class="fas fa-map-marker-alt"></i>
                  <?= htmlspecialchars($tour['vitri']) ?>
                </div>
              <?php endif; ?>
            </td>
            <td>
              <?php if ($tour['giagoi'] > 0): ?>
                <span class="price-text"><?= number_format($tour['giagoi']) ?>đ</span>
              <?php else: ?>
                <span style="color: var(--text-light);">Chưa có</span>
              <?php endif; ?>
            </td>
            <td>
              <?php if ($tour['songay'] > 0): ?>
                <strong><?= $tour['songay'] ?></strong>N<?= ($tour['songay']-1) ?>Đ
              <?php else: ?>
                <span style="color: var(--text-light);">-</span>
              <?php endif; ?>
            </td>
            <td><?= date('d/m/Y', strtotime($tour['ngaydang'])) ?></td>
            <td>
              <a href="<?= BASE_URL ?>?act=tour-publish&id_goi=<?= $tour['id_goi'] ?>" 
                 class="btn-action-small primary"
                 title="Publish">
                <i class="fas fa-rocket"></i>
              </a>
              
              <a href="<?= BASE_URL ?>?act=admin-tour-edit&id=<?= $tour['id_goi'] ?>" 
                 class="btn-action-small warning"
                 title="Sửa">
                <i class="fas fa-edit"></i>
              </a>
              
              <a href="<?= BASE_URL ?>?act=tour-view&id=<?= $tour['id_goi'] ?>" 
                 class="btn-action-small info"
                 title="Xem"
                 target="_blank">
                <i class="fas fa-eye"></i>
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>

<!-- Stats -->
<div class="stats-grid">
  <div class="stat-box">
    <div class="stat-icon">
      <i class="fas fa-list"></i>
    </div>
    <h2 class="stat-value"><?= count($tours) ?></h2>
    <p class="stat-label">Tổng tour</p>
  </div>
  
  <div class="stat-box">
    <div class="stat-icon" style="color: #10b981;">
      <i class="fas fa-check"></i>
    </div>
    <h2 class="stat-value">
      <?php
      $coGia = 0;
      foreach ($tours as $t) {
        if ($t['giagoi'] > 0) $coGia++;
      }
      echo $coGia;
      ?>
    </h2>
    <p class="stat-label">Đã có giá</p>
  </div>
  
  <div class="stat-box">
    <div class="stat-icon" style="color: #f59e0b;">
      <i class="fas fa-calendar-alt"></i>
    </div>
    <h2 class="stat-value">
      <?php
      $coNgay = 0;
      foreach ($tours as $t) {
        if ($t['songay'] > 0) $coNgay++;
      }
      echo $coNgay;
      ?>
    </h2>
    <p class="stat-label">Có lịch trình</p>
  </div>
</div>

<?php
$content = ob_get_clean();
include './views/admin/layout.php';
?>
