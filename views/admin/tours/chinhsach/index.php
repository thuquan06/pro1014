<?php
/**
 * Chính sách Tour - Modern Interface
 * Updated: 2025-11-25
 */

ob_start();
?>

<style>
.policy-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
  flex-wrap: wrap;
  gap: 16px;
}

.policy-title-section {
  flex: 1;
  min-width: 300px;
}

.policy-title {
  font-size: 28px;
  font-weight: 700;
  color: var(--text-dark);
  margin: 0 0 12px 0;
}

.tour-selector {
  background: white;
  border: 1px solid var(--border);
  border-radius: 8px;
  padding: 12px 16px;
  font-size: 14px;
  width: 100%;
  max-width: 400px;
  transition: all 0.2s;
}

.tour-selector:focus {
  outline: none;
  border-color: var(--primary);
  box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.policy-actions {
  display: flex;
  gap: 12px;
  flex-wrap: wrap;
}

.tabs-card {
  background: white;
  border: 1px solid var(--border);
  border-radius: 12px;
  overflow: hidden;
}

.modern-tabs {
  display: flex;
  background: var(--bg-light);
  border-bottom: 2px solid var(--border);
  overflow-x: auto;
  padding: 0;
}

.modern-tabs::-webkit-scrollbar {
  height: 4px;
}

.modern-tabs::-webkit-scrollbar-thumb {
  background: var(--border);
  border-radius: 4px;
}

.tab-button {
  flex: 1;
  min-width: 140px;
  padding: 16px 20px;
  border: none;
  background: transparent;
  font-size: 14px;
  font-weight: 600;
  color: var(--text-light);
  cursor: pointer;
  transition: all 0.2s;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  border-bottom: 3px solid transparent;
}

.tab-button:hover {
  background: white;
  color: var(--text-dark);
}

.tab-button.active {
  color: var(--primary);
  background: white;
  border-bottom-color: var(--primary);
}

.tab-badge {
  background: var(--border);
  color: var(--text-dark);
  padding: 2px 8px;
  border-radius: 12px;
  font-size: 12px;
  font-weight: 700;
}

.tab-button.active .tab-badge {
  background: var(--primary);
  color: white;
}

.tab-content-wrapper {
  padding: 24px;
}

.tab-pane {
  display: none;
}

.tab-pane.active {
  display: block;
}

.policy-table {
  width: 100%;
  border-collapse: collapse;
}

.policy-table thead {
  background: var(--bg-light);
}

.policy-table th {
  padding: 12px 16px;
  text-align: left;
  font-weight: 600;
  font-size: 13px;
  color: var(--text-dark);
  text-transform: uppercase;
  letter-spacing: 0.5px;
  border-bottom: 2px solid var(--border);
}

.policy-table td {
  padding: 14px 16px;
  border-bottom: 1px solid var(--border);
  font-size: 14px;
  color: var(--text-dark);
  vertical-align: middle;
}

.policy-table tbody tr:hover {
  background: var(--bg-light);
}

.policy-card {
  background: white;
  border: 1px solid var(--border);
  border-radius: 8px;
  padding: 20px;
  margin-bottom: 16px;
  transition: all 0.2s;
}

.policy-card:hover {
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.policy-card-content {
  flex: 1;
  line-height: 1.6;
  color: var(--text-dark);
}

.policy-card-actions {
  display: flex;
  gap: 8px;
  margin-top: 12px;
}

.label-badge {
  display: inline-block;
  padding: 6px 12px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 600;
}

.label-badge.warning {
  background: #fef3c7;
  color: #78350f;
}

.label-badge.success {
  background: #d1fae5;
  color: #065f46;
}

.btn-action {
  padding: 8px 14px;
  border: none;
  border-radius: 6px;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: 6px;
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

.empty-notice {
  text-align: center;
  padding: 60px 20px;
  color: var(--text-light);
}

.empty-notice i {
  font-size: 48px;
  opacity: 0.3;
  margin-bottom: 16px;
}

@media (max-width: 768px) {
  .policy-table {
    font-size: 12px;
  }
  
  .policy-table th,
  .policy-table td {
    padding: 10px 12px;
  }
}
</style>

<!-- Page Header -->
<div class="policy-header">
  <div class="policy-title-section">
    <h1 class="policy-title">
      <i class="fas fa-file-contract" style="color: var(--primary);"></i>
      Quản lý Chính sách Tour
    </h1>
    <select id="select-tour" class="tour-selector" onchange="if(this.value) window.location.href='<?= BASE_URL ?>?act=tour-chinhsach&id_goi=' + this.value">
      <option value="">-- Chọn tour để xem chính sách --</option>
      <?php if (!empty($allTours)): ?>
        <?php foreach ($allTours as $tour): ?>
          <option value="<?= $tour['id'] ?>" <?= $tour['id'] == $idGoi ? 'selected' : '' ?>>
            #<?= $tour['id'] ?> - <?= htmlspecialchars($tour['ten_goi']) ?>
          </option>
        <?php endforeach; ?>
      <?php endif; ?>
    </select>
  </div>
  
  <div class="policy-actions">
    <a href="<?= BASE_URL ?>?act=tour-chinhsach-them&id_goi=<?= $idGoi ?>" class="btn btn-primary">
      <i class="fas fa-plus-circle"></i>
      Thêm chính sách
    </a>
    <a href="<?= BASE_URL ?>?act=admin-tours" class="btn btn-secondary">
      <i class="fas fa-arrow-left"></i>
      Quay lại
    </a>
  </div>
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

<!-- Tabs Chính sách -->
<div class="tabs-card">
  <!-- Tab Buttons -->
  <div class="modern-tabs">
    <button class="tab-button active" onclick="switchTab(event, 'huy_doi')">
      <i class="fas fa-exchange-alt"></i>
      Hủy/Đổi
      <span class="tab-badge"><?= count($chinhsach['huy_doi']) ?></span>
    </button>
    <button class="tab-button" onclick="switchTab(event, 'suc_khoe')">
      <i class="fas fa-heartbeat"></i>
      Sức khỏe
      <span class="tab-badge"><?= count($chinhsach['suc_khoe']) ?></span>
    </button>
    <button class="tab-button" onclick="switchTab(event, 'hanh_ly')">
      <i class="fas fa-suitcase"></i>
      Hành lý
      <span class="tab-badge"><?= count($chinhsach['hanh_ly']) ?></span>
    </button>
    <button class="tab-button" onclick="switchTab(event, 'thanh_toan')">
      <i class="fas fa-credit-card"></i>
      Thanh toán
      <span class="tab-badge"><?= count($chinhsach['thanh_toan']) ?></span>
    </button>
    <button class="tab-button" onclick="switchTab(event, 'visa')">
      <i class="fas fa-passport"></i>
      Visa
      <span class="tab-badge"><?= count($chinhsach['visa']) ?></span>
    </button>
    <button class="tab-button" onclick="switchTab(event, 'bao_hiem')">
      <i class="fas fa-shield-alt"></i>
      Bảo hiểm
      <span class="tab-badge"><?= count($chinhsach['bao_hiem']) ?></span>
    </button>
    <button class="tab-button" onclick="switchTab(event, 'khac')">
      <i class="fas fa-ellipsis-h"></i>
      Khác
      <span class="tab-badge"><?= count($chinhsach['khac']) ?></span>
    </button>
  </div>

  <!-- Tab Content -->
  <div class="tab-content-wrapper">
    <!-- HỦY/ĐỔI TOUR -->
    <div id="huy_doi" class="tab-pane active">
      <?php if (empty($chinhsach['huy_doi'])): ?>
        <div class="empty-notice">
          <i class="fas fa-inbox"></i>
          <p>Chưa có chính sách hủy/đổi tour</p>
        </div>
      <?php else: ?>
        <table class="policy-table">
          <thead>
            <tr>
              <th width="60">STT</th>
              <th>Nội dung</th>
              <th width="120">Trước (ngày)</th>
              <th width="120">Hoàn tiền</th>
              <th width="150">Thao tác</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($chinhsach['huy_doi'] as $index => $cs): ?>
              <tr>
                <td><?= $index + 1 ?></td>
                <td><?= nl2br(htmlspecialchars($cs['noidung'])) ?></td>
                <td class="text-center">
                  <?php if ($cs['so_ngay_truoc']): ?>
                    <span class="label-badge warning"><?= $cs['so_ngay_truoc'] ?> ngày</span>
                  <?php else: ?>
                    -
                  <?php endif; ?>
                </td>
                <td class="text-center">
                  <?php if ($cs['phantram_hoantien']): ?>
                    <span class="label-badge success"><?= $cs['phantram_hoantien'] ?>%</span>
                  <?php else: ?>
                    -
                  <?php endif; ?>
                </td>
                <td>
                  <div style="display: flex; gap: 8px;">
                    <a href="<?= BASE_URL ?>?act=tour-chinhsach-sua&id=<?= $cs['id'] ?>&id_goi=<?= $idGoi ?>" 
                       class="btn-action edit">
                      <i class="fas fa-edit"></i>
                    </a>
                    <a href="<?= BASE_URL ?>?act=tour-chinhsach-xoa&id=<?= $cs['id'] ?>&id_goi=<?= $idGoi ?>" 
                       class="btn-action delete"
                       onclick="return confirm('Bạn có chắc muốn xóa?')">
                      <i class="fas fa-trash"></i>
                    </a>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </div>

    <!-- Các tab khác (tương tự) -->
    <?php 
    $tabs = ['suc_khoe', 'hanh_ly', 'thanh_toan', 'visa', 'bao_hiem', 'khac'];
    $tabLabels = [
      'suc_khoe' => 'sức khỏe',
      'hanh_ly' => 'hành lý', 
      'thanh_toan' => 'thanh toán',
      'visa' => 'visa',
      'bao_hiem' => 'bảo hiểm',
      'khac' => 'khác'
    ];
    
    foreach ($tabs as $tab): 
    ?>
      <div id="<?= $tab ?>" class="tab-pane">
        <?php if (empty($chinhsach[$tab])): ?>
          <div class="empty-notice">
            <i class="fas fa-inbox"></i>
            <p>Chưa có chính sách <?= $tabLabels[$tab] ?></p>
          </div>
        <?php else: ?>
          <?php foreach ($chinhsach[$tab] as $cs): ?>
            <div class="policy-card">
              <div class="policy-card-content">
                <?= nl2br(htmlspecialchars($cs['noidung'])) ?>
              </div>
              <div class="policy-card-actions">
                <a href="<?= BASE_URL ?>?act=tour-chinhsach-sua&id=<?= $cs['id'] ?>&id_goi=<?= $idGoi ?>" 
                   class="btn-action edit">
                  <i class="fas fa-edit"></i> Sửa
                </a>
                <a href="<?= BASE_URL ?>?act=tour-chinhsach-xoa&id=<?= $cs['id'] ?>&id_goi=<?= $idGoi ?>" 
                   class="btn-action delete"
                   onclick="return confirm('Bạn có chắc muốn xóa?')">
                  <i class="fas fa-trash"></i> Xóa
                </a>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<script>
function switchTab(event, tabName) {
  // Hide all tabs
  const tabs = document.querySelectorAll('.tab-pane');
  tabs.forEach(tab => tab.classList.remove('active'));
  
  // Remove active from all buttons
  const buttons = document.querySelectorAll('.tab-button');
  buttons.forEach(btn => btn.classList.remove('active'));
  
  // Show selected tab
  document.getElementById(tabName).classList.add('active');
  event.currentTarget.classList.add('active');
}
</script>

<?php
$content = ob_get_clean();
include './views/admin/layout.php';
?>
