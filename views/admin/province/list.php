<?php
// Helper function
function h($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

$page   = $page   ?? 1;
$limit  = $limit  ?? 10;
$total  = $total  ?? 0;
$keyword = $keyword ?? '';

$totalPages = $limit > 0 ? ceil($total / $limit) : 1;
if ($totalPages < 1) $totalPages = 1;
$startIndex = ($page - 1) * $limit;
?>

<style>
.province-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
  flex-wrap: wrap;
  gap: 16px;
}

.province-title {
  font-size: 28px;
  font-weight: 700;
  color: var(--text-dark);
  margin: 0;
}

.province-actions {
  display: flex;
  gap: 12px;
  align-items: center;
}

.search-form {
  display: flex;
  gap: 8px;
}

.search-input {
  padding: 10px 16px;
  border: 1px solid var(--border);
  border-radius: 8px;
  font-size: 14px;
  min-width: 260px;
  transition: all 0.2s;
}

.search-input:focus {
  outline: none;
  border-color: var(--primary);
  box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.province-card {
  background: white;
  border: 1px solid var(--border);
  border-radius: 12px;
  overflow: hidden;
}

.province-table {
  width: 100%;
  border-collapse: collapse;
}

.province-table thead {
  background: var(--bg-light);
}

.province-table th {
  padding: 14px 16px;
  text-align: center;
  font-weight: 600;
  font-size: 13px;
  color: var(--text-dark);
  text-transform: uppercase;
  letter-spacing: 0.5px;
  border-bottom: 2px solid var(--border);
}

.province-table td {
  padding: 16px;
  border-bottom: 1px solid var(--border);
  font-size: 14px;
  color: var(--text-dark);
  text-align: center;
}

.province-table tbody tr:hover {
  background: var(--bg-light);
}

.btn-action {
  padding: 6px 12px;
  border: none;
  border-radius: 6px;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: 4px;
  margin-right: 4px;
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

.pagination {
  display: flex;
  justify-content: center;
  gap: 8px;
  padding: 20px;
  list-style: none;
  margin: 0;
}

.pagination li {
  display: inline-block;
}

.pagination a {
  padding: 8px 14px;
  border: 1px solid var(--border);
  border-radius: 6px;
  color: var(--text-dark);
  text-decoration: none;
  transition: all 0.2s;
}

.pagination a:hover {
  background: var(--primary);
  color: white;
  border-color: var(--primary);
}

.pagination .active a {
  background: var(--primary);
  color: white;
  border-color: var(--primary);
}

.pagination .disabled a {
  background: var(--bg-light);
  color: var(--text-light);
  cursor: not-allowed;
  border-color: var(--border);
}

.pagination .disabled a:hover {
  background: var(--bg-light);
  color: var(--text-light);
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
<div class="province-header">
  <h1 class="province-title">
    <i class="fas fa-map-marked-alt" style="color: var(--primary);"></i>
    Quản lý Tỉnh/Thành phố
  </h1>
  
  <div class="province-actions">
    <form class="search-form" method="get" action="<?= BASE_URL ?>">
      <input type="hidden" name="act" value="province-list">
      <input type="text" 
             name="keyword" 
             value="<?= h($keyword) ?>" 
             class="search-input"
             placeholder="Tìm kiếm tỉnh...">
      <button type="submit" class="btn btn-secondary">
        <i class="fas fa-search"></i>
        Tìm
      </button>
    </form>
    
    <a href="<?= BASE_URL ?>?act=province-create" class="btn btn-primary">
      <i class="fas fa-plus-circle"></i>
      Thêm tỉnh mới
    </a>
  </div>
</div>

<!-- Table -->
<div class="province-card">
  <?php if (empty($provinces)): ?>
    <div class="empty-state">
      <i class="fas fa-map-marked-alt"></i>
      <h3>Không tìm thấy tỉnh/thành phố</h3>
      <p>Hãy thêm tỉnh/thành phố mới</p>
      <br>
      <a href="<?= BASE_URL ?>?act=province-create" class="btn btn-primary">
        <i class="fas fa-plus-circle"></i>
        Thêm tỉnh mới
      </a>
    </div>
  <?php else: ?>
    <table class="province-table">
      <thead>
        <tr>
          <th width="100">STT</th>
          <th>Tên tỉnh/thành phố</th>
          <th width="200">Hoạt động</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($provinces as $idx => $p): ?>
          <tr>
            <td><?= $startIndex + $idx + 1 ?></td>
            <td style="text-align: left; padding-left: 24px;">
              <strong><?= htmlspecialchars($p['ten_tinh']) ?></strong>
            </td>
            <td>
              <a href="<?= BASE_URL ?>?act=province-edit&id=<?= $p['id_tinh'] ?>" 
                 class="btn-action edit">
                <i class="fas fa-edit"></i>
                Sửa
              </a>
              <a onclick="return confirm('Xóa tỉnh này?')"
                 href="<?= BASE_URL ?>?act=province-delete&id=<?= $p['id_tinh'] ?>"
                 class="btn-action delete">
                <i class="fas fa-trash"></i>
                Xóa
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
      <nav aria-label="Page navigation">
        <ul class="pagination">
          <?php
          $baseQuery = 'act=province-list';
          if ($keyword !== '') {
            $baseQuery .= '&keyword=' . urlencode($keyword);
          }
          ?>

          <li class="<?= $page <= 1 ? 'disabled' : '' ?>">
            <a href="<?= $page <= 1 ? '#' : BASE_URL . '?' . $baseQuery . '&page=' . ($page - 1) ?>">
              <i class="fas fa-chevron-left"></i>
            </a>
          </li>

          <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="<?= $i == $page ? 'active' : '' ?>">
              <a href="<?= BASE_URL . '?' . $baseQuery . '&page=' . $i ?>">
                <?= $i ?>
              </a>
            </li>
          <?php endfor; ?>

          <li class="<?= $page >= $totalPages ? 'disabled' : '' ?>">
            <a href="<?= $page >= $totalPages ? '#' : BASE_URL . '?' . $baseQuery . '&page=' . ($page + 1) ?>">
              <i class="fas fa-chevron-right"></i>
            </a>
          </li>
        </ul>
      </nav>
    <?php endif; ?>
  <?php endif; ?>
</div>
