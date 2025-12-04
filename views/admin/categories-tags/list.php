<style>
  .categories-tags-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
    margin-bottom: 24px;
  }

  @media (max-width: 968px) {
    .categories-tags-container {
      grid-template-columns: 1fr;
    }
  }

  .category-list-item {
    padding: 16px;
    background: var(--bg-light);
    border: 1px solid var(--border);
    border-left: 4px solid var(--primary);
    border-radius: 8px;
    margin-bottom: 12px;
    transition: all 0.2s ease;
  }

  .category-list-item:hover {
    background: rgba(37, 99, 235, 0.05);
    border-left-color: var(--primary-light);
    transform: translateX(4px);
  }

  .category-list-item:last-child {
    margin-bottom: 0;
  }

  .category-list-item {
    position: relative;
  }

  .category-list-item h4 {
    margin: 0 0 6px 0;
    font-size: 15px;
    font-weight: 600;
    color: var(--text-dark);
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .category-list-item .delete-btn {
    position: absolute;
    top: 12px;
    right: 12px;
    background: var(--danger);
    color: white;
    border: none;
    border-radius: 6px;
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    opacity: 0;
    transition: all 0.2s;
    font-size: 12px;
  }

  .category-list-item:hover .delete-btn {
    opacity: 1;
  }

  .category-list-item .delete-btn:hover {
    background: #dc2626;
    transform: scale(1.1);
  }

  .category-list-item h4 i {
    color: var(--primary);
    font-size: 14px;
  }

  .category-list-item p {
    margin: 0;
    font-size: 13px;
    color: var(--text-light);
    padding-left: 22px;
  }

  .tags-wrapper {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
  }

  .tag-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 14px;
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(16, 185, 129, 0.05) 100%);
    border: 1px solid rgba(16, 185, 129, 0.2);
    border-radius: 16px;
    font-size: 13px;
    font-weight: 500;
    color: #065f46;
    position: relative;
  }

  .tag-badge .delete-btn {
    margin-left: 6px;
    background: var(--danger);
    color: white;
    border: none;
    border-radius: 50%;
    width: 18px;
    height: 18px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    opacity: 0.7;
    transition: all 0.2s;
    font-size: 10px;
    padding: 0;
  }

  .tag-badge:hover .delete-btn {
    opacity: 1;
  }

  .tag-badge .delete-btn:hover {
    background: #dc2626;
    transform: scale(1.2);
  }

  .tag-badge i {
    font-size: 11px;
    opacity: 0.7;
  }

  .manage-btn {
    margin-top: 16px;
  }

  .add-form {
    margin-top: 20px;
    padding: 16px;
    background: var(--bg-light);
    border-radius: 8px;
    border: 1px solid var(--border);
  }

  .add-form-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 12px;
  }

  .add-form-header h4 {
    margin: 0;
    font-size: 15px;
    font-weight: 600;
    color: var(--text-dark);
  }

  .add-form-body {
    display: none;
  }

  .add-form-body.show {
    display: block;
  }

  .form-group {
    margin-bottom: 12px;
  }

  .form-group label {
    display: block;
    margin-bottom: 6px;
    font-size: 13px;
    font-weight: 500;
    color: var(--text-dark);
  }

  .form-group input,
  .form-group textarea {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid var(--border);
    border-radius: 6px;
    font-size: 14px;
    font-family: inherit;
  }

  .form-group textarea {
    resize: vertical;
    min-height: 60px;
  }

  .form-actions {
    display: flex;
    gap: 8px;
    margin-top: 12px;
  }

  .btn-sm {
    padding: 8px 16px;
    font-size: 13px;
  }
</style>

<div class="categories-tags-container">
  <!-- CATEGORIES -->
  <div class="card">
    <div class="card-header">
      <h3>
        <i class="fas fa-folder"></i>
        Loại Tour
      </h3>
      <button type="button" onclick="toggleAddCategoryForm()" class="btn btn-sm btn-primary">
        <i class="fas fa-plus"></i> Thêm mới
      </button>
    </div>
    <div class="card-body">
      <?php if (empty($categories) || !is_array($categories)): ?>
        <div style="text-align: center; padding: 40px 20px; color: var(--text-light);">
          <i class="fas fa-folder-open" style="font-size: 48px; opacity: 0.3; margin-bottom: 12px; display: block;"></i>
          <p style="margin: 0; font-size: 14px;">Chưa có loại tour nào</p>
        </div>
      <?php else: ?>
        <div>
          <?php foreach ($categories as $cat): ?>
            <div class="category-list-item">
              <button type="button" class="delete-btn" onclick="deleteCategory(<?= $cat['id'] ?>, '<?= htmlspecialchars(addslashes($cat['ten_loai'])) ?>')" title="Xóa">
                <i class="fas fa-times"></i>
              </button>
              <h4>
                <i class="fas fa-folder"></i>
                <?= htmlspecialchars($cat['ten_loai']) ?>
              </h4>
              <?php if (!empty($cat['mota'])): ?>
                <p><?= htmlspecialchars($cat['mota']) ?></p>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        </div>
        <div class="manage-btn">
          <button type="button" onclick="showTourSelector()" class="btn btn-primary" style="width: 100%;">
            <i class="fas fa-cog"></i> Quản lý Phân loại
          </button>
        </div>
      <?php endif; ?>
      
      <!-- Form thêm category -->
      <div class="add-form">
        <div class="add-form-header">
          <h4><i class="fas fa-plus-circle"></i> Thêm Loại Tour mới</h4>
          <button type="button" onclick="toggleAddCategoryForm()" class="btn btn-sm btn-outline">
            <i class="fas fa-times"></i>
          </button>
        </div>
        <div class="add-form-body" id="addCategoryForm">
          <form method="POST" action="<?= BASE_URL ?>?act=admin-categories-tags-create">
            <input type="hidden" name="type" value="category">
            <div class="form-group">
              <label>Tên loại tour *</label>
              <input type="text" name="ten_loai" required placeholder="Ví dụ: Tour Biển">
            </div>
            <div class="form-group">
              <label>Mô tả</label>
              <textarea name="mota" placeholder="Mô tả ngắn về loại tour này"></textarea>
            </div>
            <div class="form-actions">
              <button type="submit" class="btn btn-sm btn-primary">
                <i class="fas fa-save"></i> Lưu
              </button>
              <button type="button" onclick="toggleAddCategoryForm()" class="btn btn-sm btn-outline">
                Hủy
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- TAGS -->
  <div class="card">
    <div class="card-header">
      <h3>
        <i class="fas fa-hashtag"></i>
        Tags
      </h3>
      <button type="button" onclick="toggleAddTagForm()" class="btn btn-sm btn-primary">
        <i class="fas fa-plus"></i> Thêm mới
      </button>
    </div>
    <div class="card-body">
      <?php if (empty($tags) || !is_array($tags)): ?>
        <div style="text-align: center; padding: 40px 20px; color: var(--text-light);">
          <i class="fas fa-hashtag" style="font-size: 48px; opacity: 0.3; margin-bottom: 12px; display: block;"></i>
          <p style="margin: 0; font-size: 14px;">Chưa có tag nào</p>
        </div>
      <?php else: ?>
        <div class="tags-wrapper">
          <?php foreach ($tags as $tag): ?>
            <span class="tag-badge">
              <i class="fas fa-hashtag"></i>
              <?= htmlspecialchars($tag['ten_tag']) ?>
              <button type="button" class="delete-btn" onclick="deleteTag(<?= $tag['id'] ?>, '<?= htmlspecialchars(addslashes($tag['ten_tag'])) ?>')" title="Xóa">
                <i class="fas fa-times"></i>
              </button>
            </span>
          <?php endforeach; ?>
        </div>
        <div class="manage-btn">
          <button type="button" onclick="showTourSelector()" class="btn btn-primary" style="width: 100%;">
            <i class="fas fa-cog"></i> Quản lý Tags
          </button>
        </div>
      <?php endif; ?>
      
      <!-- Form thêm tag -->
      <div class="add-form">
        <div class="add-form-header">
          <h4><i class="fas fa-plus-circle"></i> Thêm Tag mới</h4>
          <button type="button" onclick="toggleAddTagForm()" class="btn btn-sm btn-outline">
            <i class="fas fa-times"></i>
          </button>
        </div>
        <div class="add-form-body" id="addTagForm">
          <form method="POST" action="<?= BASE_URL ?>?act=admin-categories-tags-create">
            <input type="hidden" name="type" value="tag">
            <div class="form-group">
              <label>Tên tag *</label>
              <input type="text" name="ten_tag" required placeholder="Ví dụ: Hot, Khuyến mãi, Mới">
            </div>
            <div class="form-actions">
              <button type="submit" class="btn btn-sm btn-primary">
                <i class="fas fa-save"></i> Lưu
              </button>
              <button type="button" onclick="toggleAddTagForm()" class="btn btn-sm btn-outline">
                Hủy
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Tour Selector Modal -->
<div id="tourSelectorModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
  <div style="background: white; border-radius: 12px; padding: 32px; max-width: 500px; width: 90%; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);">
    <h3 style="margin: 0 0 20px 0; font-size: 20px; font-weight: 700; color: var(--text-dark); display: flex; align-items: center; gap: 8px;">
      <i class="fas fa-map-marked-alt" style="color: var(--primary);"></i>
      Chọn Tour để Quản lý
    </h3>
    <form method="GET" action="<?= BASE_URL ?>">
      <input type="hidden" name="act" value="tour-phanloai">
      <div style="margin-bottom: 20px;">
        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-dark); font-size: 14px;">Chọn Tour</label>
        <select name="id_goi" id="id_goi" class="form-control" required style="width: 100%; padding: 12px 16px; border: 2px solid var(--border); border-radius: 10px; font-size: 14px;">
          <option value="">-- Chọn tour --</option>
          <?php
          require_once './models/TourModel.php';
          $tourModel = new TourModel();
          $allTours = $tourModel->getAllTours();
          foreach ($allTours as $tour): ?>
            <option value="<?= $tour['id_goi'] ?>">
              <?= htmlspecialchars($tour['mato'] ?? '') ?> - <?= htmlspecialchars($tour['tengoi'] ?? 'N/A') ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div style="display: flex; gap: 12px; justify-content: flex-end;">
        <button type="button" onclick="hideTourSelector()" class="btn btn-outline">
          Hủy
        </button>
        <button type="submit" class="btn btn-primary">
          <i class="fas fa-arrow-right"></i> Đi đến Quản lý
        </button>
      </div>
    </form>
  </div>
</div>

<script>
function showTourSelector() {
  document.getElementById('tourSelectorModal').style.display = 'flex';
}

function hideTourSelector() {
  document.getElementById('tourSelectorModal').style.display = 'none';
}

// Đóng modal khi click bên ngoài
document.getElementById('tourSelectorModal').addEventListener('click', function(e) {
  if (e.target === this) {
    hideTourSelector();
  }
});

// Toggle form thêm category
function toggleAddCategoryForm() {
  const form = document.getElementById('addCategoryForm');
  form.classList.toggle('show');
}

// Toggle form thêm tag
function toggleAddTagForm() {
  const form = document.getElementById('addTagForm');
  form.classList.toggle('show');
}

// Xóa category
function deleteCategory(id, name) {
  if (confirm('Bạn có chắc muốn xóa loại tour "' + name + '"?\n\nLưu ý: Tất cả các tour đã gán loại này sẽ bị gỡ bỏ.')) {
    window.location.href = '<?= BASE_URL ?>?act=admin-categories-tags-delete&type=category&id=' + id;
  }
}

// Xóa tag
function deleteTag(id, name) {
  if (confirm('Bạn có chắc muốn xóa tag "' + name + '"?\n\nLưu ý: Tất cả các tour đã gán tag này sẽ bị gỡ bỏ.')) {
    window.location.href = '<?= BASE_URL ?>?act=admin-categories-tags-delete&type=tag&id=' + id;
  }
}
</script>
