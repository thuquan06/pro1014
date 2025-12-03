<?php
/**
 * Tour List - Danh sách tour hiện đại
 * Updated: 2025-11-25
 */

// Helper function
function safe_html($value) {
    return htmlentities($value ?? '', ENT_QUOTES, 'UTF-8');
}
?>

<style>
.tours-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
}

.tours-title {
  font-size: 28px;
  font-weight: 700;
  color: var(--text-dark);
  margin: 0;
}

.tours-actions {
  display: flex;
  gap: 12px;
}

.tours-stats {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 20px;
  margin-bottom: 24px;
}

.stat-box {
  background: white;
  border: 1px solid var(--border);
  border-radius: 12px;
  padding: 20px;
  display: flex;
  align-items: center;
  gap: 16px;
}

.stat-icon {
  width: 50px;
  height: 50px;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 24px;
  color: white;
}

.stat-icon.blue { background: #2563eb; }
.stat-icon.green { background: #10b981; }
.stat-icon.orange { background: #f59e0b; }

.stat-info h4 {
  font-size: 28px;
  font-weight: 700;
  color: var(--text-dark);
  margin: 0;
}

.stat-info p {
  font-size: 14px;
  color: var(--text-light);
  margin: 0;
}

.tours-table-card {
  background: white;
  border: 1px solid var(--border);
  border-radius: 12px;
  overflow: hidden;
}

.table-header {
  padding: 20px 24px;
  border-bottom: 1px solid var(--border);
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.table-header h3 {
  font-size: 18px;
  font-weight: 700;
  color: var(--text-dark);
  margin: 0;
}

.search-box {
  position: relative;
  width: 300px;
}

.search-box input {
  width: 100%;
  padding: 10px 14px 10px 40px;
  border: 1px solid var(--border);
  border-radius: 8px;
  font-size: 14px;
}

.search-box i {
  position: absolute;
  left: 14px;
  top: 50%;
  transform: translateY(-50%);
  color: var(--text-light);
}

.modern-table {
  width: 100%;
  border-collapse: collapse;
}

.modern-table thead {
  background: var(--bg-light);
}

.modern-table th {
  padding: 14px 16px;
  text-align: left;
  font-weight: 600;
  font-size: 12px;
  color: var(--text-dark);
  text-transform: uppercase;
  letter-spacing: 0.5px;
  border-bottom: 2px solid var(--border);
  white-space: nowrap;
}

.modern-table td {
  padding: 16px;
  border-bottom: 1px solid var(--border);
  font-size: 14px;
  color: var(--text-dark);
  vertical-align: middle;
}

.modern-table tbody tr:hover {
  background: var(--bg-light);
}

.tour-name {
  font-weight: 600;
  color: var(--primary);
  max-width: 250px;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.tour-image-cell {
  width: 60px;
  height: 60px;
  border-radius: 8px;
  object-fit: cover;
}

.price-cell {
  font-weight: 600;
  color: var(--text-dark);
}

.price-breakdown {
  display: flex;
  flex-direction: column;
  gap: 4px;
  font-size: 13px;
}

.price-breakdown .price-row {
  display: flex;
  align-items: center;
  gap: 6px;
}

.price-breakdown .price-label {
  font-size: 11px;
  color: var(--text-light);
  min-width: 60px;
}

.price-breakdown .price-value {
  font-weight: 600;
  color: var(--text-dark);
}

.price-breakdown .price-main {
  color: var(--primary);
  font-size: 14px;
}

.price-discounted {
  color: #e74c3c;
  font-weight: 600;
  font-size: 13px;
}

.price-original-struck {
  text-decoration: line-through;
  color: #999;
  font-size: 11px;
  font-weight: 400;
}

.promotion-badge-small {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  padding: 2px 6px;
  border-radius: 6px;
  font-size: 10px;
  font-weight: 600;
  margin-bottom: 4px;
  display: inline-block;
}

.status-badge {
  display: inline-block;
  padding: 6px 12px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 600;
}

.status-badge.active {
  background: #d1fae5;
  color: #065f46;
}

.status-badge.inactive {
  background: #fee2e2;
  color: #991b1b;
}

.action-buttons {
  display: flex;
  gap: 8px;
  flex-wrap: wrap;
}

.btn-action {
  padding: 8px 14px;
  border: none;
  border-radius: 8px;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
  display: inline-flex;
  align-items: center;
  gap: 6px;
  text-decoration: none;
  white-space: nowrap;
}

.btn-action:hover {
  transform: translateY(-2px);
}

.btn-action.view {
  background: #dbeafe;
  color: #1e40af;
}

.btn-action.view:hover {
  background: #2563eb;
  color: white;
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

.toggle-btn {
  padding: 6px 12px;
  border: none;
  border-radius: 6px;
  font-size: 12px;
  font-weight: 600;
  cursor: pointer;
  text-decoration: none;
  display: inline-block;
}

.toggle-btn.active {
  background: #d1fae5;
  color: #065f46;
}

.toggle-btn.inactive {
  background: #fee2e2;
  color: #991b1b;
}

.pagination {
  display: flex;
  justify-content: center;
  gap: 8px;
  padding: 20px;
  list-style: none;
  margin: 0;
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
}

.empty-state h3 {
  font-size: 18px;
  margin: 16px 0 8px;
  color: var(--text-dark);
}

.empty-state p {
  margin: 0;
  font-size: 14px;
}
</style>

<!-- Page Header -->
<div class="tours-header">
  <h1 class="tours-title">
    <i class="fas fa-map-marked-alt" style="color: var(--primary);"></i>
    Quản lý Tour
  </h1>
  <div class="tours-actions">
    <a href="<?= BASE_URL ?>?act=admin-tour-create" class="btn btn-primary">
      <i class="fas fa-plus-circle"></i>
      Thêm Tour mới
    </a>
  </div>
</div>

<!-- Statistics -->
<div class="tours-stats">
  <div class="stat-box">
    <div class="stat-icon blue">
      <i class="fas fa-map-marked-alt"></i>
    </div>
    <div class="stat-info">
      <h4><?= count($tours ?? []) ?></h4>
      <p>Tổng số tour</p>
    </div>
  </div>
  
  <div class="stat-box">
    <div class="stat-icon green">
      <i class="fas fa-check-circle"></i>
    </div>
    <div class="stat-info">
      <h4><?= count(array_filter($tours ?? [], fn($t) => !empty($t['trangthai']))) ?></h4>
      <p>Tour đang hiển thị</p>
    </div>
  </div>
  
  <div class="stat-box">
    <div class="stat-icon orange">
      <i class="fas fa-eye-slash"></i>
    </div>
    <div class="stat-info">
      <h4><?= count(array_filter($tours ?? [], fn($t) => empty($t['trangthai']))) ?></h4>
      <p>Tour đang ẩn</p>
    </div>
  </div>
</div>

<!-- Tours Table -->
<div class="tours-table-card">
  <div class="table-header">
    <h3>Danh sách tour</h3>
    <div class="search-box">
      <i class="fas fa-search"></i>
      <input type="text" id="searchInput" placeholder="Tìm kiếm tour...">
    </div>
  </div>
  
  <div class="table-responsive">
    <?php if (!empty($tours)): ?>
      <table class="modern-table" id="toursTable">
        <thead>
          <tr>
            <th>STT</th>
            <th>Tên Tour</th>
            <th>Địa điểm</th>
            <th>Số ngày</th>
            <th>Giá (VNĐ)</th>
            <th>Ngày đi</th>
            <th>Trạng thái</th>
            <th>Hoạt động</th>
          </tr>
        </thead>
        <tbody>
          <?php
          // Load price helper để tính giá khuyến mãi
          require_once './commons/price_helper.php';

          $cnt = 1;
          foreach ($tours as $tour):
            $id_goi = $tour['id_goi'] ?? '';
            $tengoi = $tour['tengoi'] ?? '';
            $ten_tinh = $tour['ten_tinh'] ?? '';
            $vitri = $tour['vitri'] ?? '';
            $songay = $tour['songay'] ?? '';
            $giagoi = $tour['giagoi'] ?? 0;
            $giatreem = $tour['giatreem'] ?? 0;
            $giatrenho = $tour['giatrenho'] ?? 0;
            $ngayxuatphat = $tour['ngayxuatphat'] ?? '';
            $trangthai = $tour['trangthai'] ?? 0;

            // Tính giá sau khuyến mãi
            $coKhuyenMai = isPromotionActive($tour);
            $giaNguoiLonSauKM = calculatePromotionPrice($giagoi, $tour);
            $giaTreEmSauKM = calculatePromotionPrice($giatreem, $tour);
            $giaTreNhoSauKM = calculatePromotionPrice($giatrenho, $tour);
          ?>
            <tr>
              <td><?= $cnt ?></td>
              <td>
                <div class="tour-name" title="<?= safe_html($tengoi) ?>">
                  <?= safe_html($tengoi) ?>
                </div>
              </td>
              <td>
                <div style="font-weight: 500;"><?= safe_html($ten_tinh) ?></div>
                <div style="font-size: 12px; color: var(--text-light);"><?= safe_html($vitri) ?></div>
              </td>
              <td>
                <strong><?= safe_html($songay) ?></strong> ngày
              </td>
              <td class="price-cell">
                <?php if ($coKhuyenMai): ?>
                  <span class="promotion-badge-small">🔥 -<?= (int)($tour['khuyenmai_phantram'] ?? 0) ?>%</span>
                <?php endif; ?>
                <div class="price-breakdown">
                  <div class="price-row">
                    <span class="price-label">Người lớn:</span>
                    <span>
                      <?php if ($coKhuyenMai && $giaNguoiLonSauKM < $giagoi): ?>
                        <div class="price-original-struck"><?= number_format($giagoi, 0, ',', '.') ?></div>
                        <div class="price-discounted"><?= number_format($giaNguoiLonSauKM, 0, ',', '.') ?></div>
                      <?php else: ?>
                        <span class="price-value price-main"><?= number_format($giagoi, 0, ',', '.') ?></span>
                      <?php endif; ?>
                    </span>
                  </div>
                  <div class="price-row">
                    <span class="price-label">Trẻ em:</span>
                    <span>
                      <?php if ($coKhuyenMai && $giaTreEmSauKM < $giatreem): ?>
                        <div class="price-original-struck"><?= number_format($giatreem, 0, ',', '.') ?></div>
                        <div class="price-discounted"><?= number_format($giaTreEmSauKM, 0, ',', '.') ?></div>
                      <?php else: ?>
                        <span class="price-value"><?= number_format($giatreem, 0, ',', '.') ?></span>
                      <?php endif; ?>
                    </span>
                  </div>
                  <div class="price-row">
                    <span class="price-label">Trẻ nhỏ:</span>
                    <span>
                      <?php if ($coKhuyenMai && $giaTreNhoSauKM < $giatrenho): ?>
                        <div class="price-original-struck"><?= number_format($giatrenho, 0, ',', '.') ?></div>
                        <div class="price-discounted"><?= number_format($giaTreNhoSauKM, 0, ',', '.') ?></div>
                      <?php else: ?>
                        <span class="price-value"><?= number_format($giatrenho, 0, ',', '.') ?></span>
                      <?php endif; ?>
                    </span>
                  </div>
                </div>
              </td>
              <td>
                <?= $ngayxuatphat ? date('d/m/Y', strtotime($ngayxuatphat)) : '-' ?>
              </td>
              <td>
                <a href="<?= BASE_URL ?>?act=admin-tour-toggle&id=<?= $id_goi ?>" 
                   class="toggle-btn <?= $trangthai == 1 ? 'active' : 'inactive' ?>">
                  <?php if ($trangthai == 1): ?>
                    <i class="fas fa-check-circle"></i> Hiển thị
                  <?php else: ?>
                    <i class="fas fa-eye-slash"></i> Ẩn
                  <?php endif; ?>
                </a>
              </td>
              <td>
                <div class="action-buttons">
                  <a href="<?= BASE_URL ?>?act=admin-tour-detail&id=<?= $id_goi ?>" 
                     class="btn-action view" title="Xem chi tiết">
                    <i class="fas fa-eye"></i>
                  </a>
                  <a href="<?= BASE_URL ?>?act=admin-tour-edit&id=<?= $id_goi ?>" 
                     class="btn-action edit" title="Chỉnh sửa">
                    <i class="fas fa-edit"></i>
                  </a>
                  <a href="<?= BASE_URL ?>?act=admin-tour-delete&id=<?= $id_goi ?>" 
                     class="btn-action delete" 
                     onclick="return confirm('Bạn có chắc chắn muốn xóa tour này?')"
                     title="Xóa">
                    <i class="fas fa-trash"></i>
                  </a>
                </div>
              </td>
            </tr>
          <?php 
            $cnt++;
          endforeach; 
          ?>
        </tbody>
      </table>
    <?php else: ?>
      <div class="empty-state">
        <i class="fas fa-inbox"></i>
        <h3>Chưa có tour nào</h3>
        <p>Hãy thêm tour đầu tiên của bạn</p>
        <br>
        <a href="<?= BASE_URL ?>?act=admin-tour-create" class="btn btn-primary">
          <i class="fas fa-plus-circle"></i>
          Thêm Tour mới
        </a>
      </div>
    <?php endif; ?>
  </div>
</div>

<script>
// Simple search functionality
document.getElementById('searchInput')?.addEventListener('keyup', function() {
  const searchValue = this.value.toLowerCase();
  const table = document.getElementById('toursTable');
  const rows = table?.getElementsByTagName('tr');
  
  if (!rows) return;
  
  for (let i = 1; i < rows.length; i++) {
    const row = rows[i];
    const text = row.textContent.toLowerCase();
    
    if (text.indexOf(searchValue) > -1) {
      row.style.display = '';
    } else {
      row.style.display = 'none';
    }
  }
});
</script>
