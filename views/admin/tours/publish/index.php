<?php
/**
 * Tour Publish Page - Modern Interface  
 * Updated: 2025-11-25
 */

ob_start();
?>

<style>
.publish-page-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
}

.publish-page-title {
  font-size: 28px;
  font-weight: 700;
  color: var(--text-dark);
  margin: 0;
}

.publish-page-subtitle {
  font-size: 16px;
  color: var(--text-light);
  margin-top: 4px;
}

.status-card {
  background: white;
  border: 1px solid var(--border);
  border-radius: 12px;
  padding: 24px;
  margin-bottom: 24px;
}

.status-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 20px;
}

.status-item {
  text-align: center;
}

.status-item-label {
  font-weight: 600;
  font-size: 14px;
  color: var(--text-dark);
  margin-bottom: 8px;
}

.status-badge {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 8px 16px;
  border-radius: 20px;
  font-size: 14px;
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

.progress-bar-wrapper {
  margin-top: 8px;
}

.progress-custom {
  height: 30px;
  border-radius: 15px;
  background: var(--bg-light);
  overflow: hidden;
  position: relative;
}

.progress-fill {
  height: 100%;
  border-radius: 15px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-weight: 700;
  font-size: 14px;
  transition: width 0.3s;
}

.progress-fill.danger {
  background: linear-gradient(90deg, #ef4444, #dc2626);
}

.progress-fill.warning {
  background: linear-gradient(90deg, #f59e0b, #d97706);
}

.progress-fill.success {
  background: linear-gradient(90deg, #10b981, #059669);
}

.checklist-card {
  background: white;
  border: 1px solid var(--border);
  border-radius: 12px;
  padding: 24px;
}

.checklist-section {
  margin-bottom: 24px;
}

.checklist-section:last-child {
  margin-bottom: 0;
}

.checklist-section-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 12px;
  padding-bottom: 12px;
  border-bottom: 2px solid var(--bg-light);
}

.checklist-section-title {
  font-size: 16px;
  font-weight: 700;
  color: var(--text-dark);
  margin: 0;
  display: flex;
  align-items: center;
  gap: 8px;
}

.checklist-section-progress {
  font-size: 12px;
  color: var(--text-light);
}

.checklist-item {
  padding: 12px 16px;
  margin: 8px 0;
  background: var(--bg-light);
  border-radius: 8px;
  display: flex;
  align-items: center;
  gap: 12px;
  transition: all 0.2s;
}

.checklist-item:hover {
  background: #e5e7eb;
}

.checklist-item.completed {
  background: #d1fae5;
}

.checklist-icon {
  font-size: 20px;
}

.checklist-icon.completed {
  color: #10b981;
}

.checklist-icon.incomplete {
  color: #9ca3af;
}

.checklist-text {
  flex: 1;
  font-size: 14px;
  color: var(--text-dark);
}

.checklist-text.incomplete {
  color: var(--text-light);
}

.actions-card {
  background: white;
  border: 1px solid var(--border);
  border-radius: 12px;
  padding: 24px;
  position: sticky;
  top: 24px;
}

.actions-title {
  font-size: 18px;
  font-weight: 700;
  color: var(--text-dark);
  margin: 0 0 20px 0;
  display: flex;
  align-items: center;
  gap: 8px;
}

.btn-action-large {
  width: 100%;
  padding: 14px 24px;
  border: none;
  border-radius: 8px;
  font-weight: 600;
  font-size: 14px;
  cursor: pointer;
  transition: all 0.2s;
  text-decoration: none;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  margin-bottom: 12px;
}

.btn-action-large.primary {
  background: var(--primary);
  color: white;
}

.btn-action-large.primary:hover {
  background: #1e40af;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
}

.btn-action-large.success {
  background: #10b981;
  color: white;
}

.btn-action-large.success:hover {
  background: #059669;
  transform: translateY(-2px);
}

.btn-action-large.warning {
  background: #f59e0b;
  color: white;
}

.btn-action-large.warning:hover {
  background: #d97706;
}

.btn-action-large.secondary {
  background: white;
  color: var(--text-dark);
  border: 1px solid var(--border);
}

.btn-action-large.secondary:hover {
  background: var(--bg-light);
}

.btn-action-large:disabled {
  background: var(--border);
  color: var(--text-light);
  cursor: not-allowed;
}

.quick-links {
  margin-top: 24px;
  padding-top: 24px;
  border-top: 2px solid var(--bg-light);
}

.quick-links-title {
  font-size: 14px;
  font-weight: 700;
  color: var(--text-dark);
  margin-bottom: 12px;
}

.btn-quick {
  width: 100%;
  padding: 10px 16px;
  background: white;
  color: var(--text-dark);
  border: 1px solid var(--border);
  border-radius: 8px;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
  text-decoration: none;
  display: flex;
  align-items: center;
  gap: 8px;
  margin-bottom: 8px;
}

.btn-quick:hover {
  background: var(--bg-light);
  border-color: var(--primary);
  color: var(--primary);
}
</style>

<!-- Page Header -->
<div class="publish-page-header">
  <div>
    <h1 class="publish-page-title">
      <i class="fas fa-rocket" style="color: var(--primary);"></i>
      Publish Tour
    </h1>
    <p class="publish-page-subtitle"><?= htmlspecialchars($tour['tengoi']) ?></p>
  </div>
  <a href="<?= BASE_URL ?>?act=admin-tours" class="btn btn-secondary">
    <i class="fas fa-arrow-left"></i>
    Quay lại
  </a>
</div>

<!-- Thông báo -->
<?php if (isset($_SESSION['success'])): ?>
  <div class="alert alert-success">
    <i class="fas fa-check-circle"></i>
    <?= $_SESSION['success'] ?>
  </div>
  <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
  <div class="alert alert-error">
    <i class="fas fa-exclamation-circle"></i>
    <?= $_SESSION['error'] ?>
  </div>
  <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<!-- Status Card -->
<div class="status-card">
  <div class="status-grid">
    <div class="status-item">
      <p class="status-item-label">Trạng thái publish</p>
      <?php
      $badges = [
        'draft' => '<span class="status-badge draft"><i class="fas fa-file-alt"></i> Draft</span>',
        'internal' => '<span class="status-badge internal"><i class="fas fa-lock"></i> Nội bộ</span>',
        'public' => '<span class="status-badge public"><i class="fas fa-globe"></i> Công khai</span>'
      ];
      echo $badges[$tour['publish_status']] ?? $badges['draft'];
      ?>
    </div>
    
    <div class="status-item">
      <p class="status-item-label">Độ hoàn thiện</p>
      <div class="progress-bar-wrapper">
        <div class="progress-custom">
          <div class="progress-fill <?= $tyLeHoanThanh >= 80 ? 'success' : ($tyLeHoanThanh >= 50 ? 'warning' : 'danger') ?>" 
               style="width: <?= $tyLeHoanThanh ?>%">
            <?= $tyLeHoanThanh ?>%
          </div>
        </div>
      </div>
    </div>
    
    <div class="status-item">
      <p class="status-item-label">Publish lần cuối</p>
      <p style="color: var(--text-dark); font-weight: 600; margin: 0;">
        <?= $tour['published_at'] ? date('d/m/Y H:i', strtotime($tour['published_at'])) : 'Chưa publish' ?>
      </p>
    </div>
    
    <div class="status-item">
      <p class="status-item-label">Trạng thái</p>
      <?php if ($coThePublish): ?>
        <span style="color: #10b981; font-size: 32px;">
          <i class="fas fa-check-circle"></i>
        </span>
        <p style="color: #10b981; font-weight: 600; margin: 4px 0 0;">Sẵn sàng!</p>
      <?php else: ?>
        <span style="color: #ef4444; font-size: 32px;">
          <i class="fas fa-exclamation-circle"></i>
        </span>
        <p style="color: #ef4444; font-weight: 600; margin: 4px 0 0;">Chưa đủ</p>
      <?php endif; ?>
    </div>
  </div>
</div>

<div class="row">
  <!-- Checklist -->
  <div class="col-md-8">
    <div class="checklist-card">
      <?php foreach ($checklist as $categoryKey => $category): ?>
        <?php
        $totalItems = count($category['items']);
        $passedItems = 0;
        foreach ($category['items'] as $item) {
          if ($item['status']) $passedItems++;
        }
        ?>
        
        <div class="checklist-section">
          <div class="checklist-section-header">
            <h3 class="checklist-section-title">
              <?php if ($passedItems === $totalItems): ?>
                <i class="fas fa-check-circle" style="color: #10b981;"></i>
              <?php else: ?>
                <i class="fas fa-circle" style="color: #9ca3af;"></i>
              <?php endif; ?>
              <?= $category['name'] ?>
            </h3>
            <span class="checklist-section-progress"><?= $passedItems ?>/<?= $totalItems ?></span>
          </div>
          
          <div class="checklist-items">
            <?php foreach ($category['items'] as $itemKey => $item): ?>
              <div class="checklist-item <?= $item['status'] ? 'completed' : '' ?>">
                <i class="fas <?= $item['status'] ? 'fa-check-circle' : 'fa-circle' ?> checklist-icon <?= $item['status'] ? 'completed' : 'incomplete' ?>"></i>
                <span class="checklist-text <?= $item['status'] ? '' : 'incomplete' ?>">
                  <?= $item['label'] ?>
                </span>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
  
  <!-- Actions -->
  <div class="col-md-4">
    <div class="actions-card">
      <h3 class="actions-title">
        <i class="fas fa-cog"></i>
        Thao tác
      </h3>
      
      <?php if ($tour['publish_status'] === 'draft'): ?>
        <?php if ($coThePublish): ?>
          <a href="<?= BASE_URL ?>?act=tour-publish-change&id_goi=<?= $tour['id_goi'] ?>&status=public" 
             class="btn-action-large success"
             onclick="return confirm('Publish công khai tour này?')">
            <i class="fas fa-globe"></i>
            Publish công khai
          </a>
          
          <a href="<?= BASE_URL ?>?act=tour-publish-change&id_goi=<?= $tour['id_goi'] ?>&status=internal" 
             class="btn-action-large warning"
             onclick="return confirm('Publish nội bộ để xem trước?')">
            <i class="fas fa-lock"></i>
            Publish nội bộ
          </a>
        <?php else: ?>
          <button class="btn-action-large secondary" disabled>
            <i class="fas fa-exclamation-triangle"></i>
            Chưa đủ điều kiện
          </button>
          <div class="alert alert-error" style="margin-top: 12px; padding: 12px; font-size: 13px;">
            Vui lòng hoàn thiện các mục bắt buộc trước khi publish
          </div>
        <?php endif; ?>
        
      <?php elseif ($tour['publish_status'] === 'internal'): ?>
        <a href="<?= BASE_URL ?>?act=tour-publish-change&id_goi=<?= $tour['id_goi'] ?>&status=public" 
           class="btn-action-large success"
           onclick="return confirm('Publish công khai tour này?')">
          <i class="fas fa-globe"></i>
          Publish công khai
        </a>
        
        <a href="<?= BASE_URL ?>?act=tour-publish-change&id_goi=<?= $tour['id_goi'] ?>&status=draft" 
           class="btn-action-large secondary"
           onclick="return confirm('Chuyển về Draft?')">
          <i class="fas fa-undo"></i>
          Chuyển về Draft
        </a>
        
      <?php elseif ($tour['publish_status'] === 'public'): ?>
        <div class="alert alert-success" style="margin-bottom: 16px;">
          <i class="fas fa-check-circle"></i>
          Tour đang công khai!
        </div>
        
        <a href="<?= BASE_URL ?>?act=tour-publish-change&id_goi=<?= $tour['id_goi'] ?>&status=internal" 
           class="btn-action-large warning"
           onclick="return confirm('Gỡ xuống nội bộ?')">
          <i class="fas fa-eye-slash"></i>
          Chuyển về nội bộ
        </a>
        
        <a href="<?= BASE_URL ?>?act=tour-publish-change&id_goi=<?= $tour['id_goi'] ?>&status=draft" 
           class="btn-action-large secondary"
           onclick="return confirm('Chuyển về Draft?')">
          <i class="fas fa-undo"></i>
          Chuyển về Draft
        </a>
      <?php endif; ?>
      
      <div class="quick-links">
        <p class="quick-links-title">Liên kết nhanh</p>
        <a href="<?= BASE_URL ?>?act=admin-tour-edit&id=<?= $tour['id_goi'] ?>" class="btn-quick">
          <i class="fas fa-edit"></i>
          Sửa thông tin tour
        </a>
        <a href="<?= BASE_URL ?>?act=tour-lichtrinh&id_goi=<?= $tour['id_goi'] ?>" class="btn-quick">
          <i class="fas fa-calendar-alt"></i>
          Quản lý lịch trình
        </a>
      </div>
    </div>
  </div>
</div>

<?php
$content = ob_get_clean();
include './views/admin/layout.php';
?>
