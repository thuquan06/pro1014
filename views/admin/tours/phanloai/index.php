<?php
/**
 * Phân loại & Tags Tour - Modern Interface
 * Updated: 2025-11-25
 */
ob_start();
?>

<style>
.category-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
}

.category-title {
  font-size: 28px;
  font-weight: 700;
  color: var(--text-dark);
  margin: 0;
}

.category-subtitle {
  color: var(--text-light);
  font-size: 14px;
  margin-top: 4px;
}

.category-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
  gap: 24px;
  margin-bottom: 24px;
}

.category-card {
  background: white;
  border: 1px solid var(--border);
  border-radius: 12px;
  overflow: hidden;
}

.card-header {
  padding: 20px 24px;
  background: linear-gradient(135deg, var(--primary), #1e40af);
  color: white;
  display: flex;
  align-items: center;
  gap: 12px;
}

.card-header.green {
  background: linear-gradient(135deg, #10b981, #059669);
}

.card-header h3 {
  margin: 0;
  font-size: 18px;
  font-weight: 700;
}

.card-body {
  padding: 24px;
}

.card-description {
  color: var(--text-light);
  font-size: 14px;
  margin-bottom: 20px;
  line-height: 1.6;
}

.selection-box {
  max-height: 300px;
  overflow-y: auto;
  border: 1px solid var(--border);
  border-radius: 8px;
  padding: 16px;
  background: var(--bg-light);
  margin-bottom: 20px;
}

.selection-box::-webkit-scrollbar {
  width: 8px;
}

.selection-box::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 10px;
}

.selection-box::-webkit-scrollbar-thumb {
  background: #888;
  border-radius: 10px;
}

.checkbox-item {
  display: block;
  padding: 12px;
  margin: 8px 0;
  background: white;
  border-radius: 8px;
  transition: all 0.2s;
  cursor: pointer;
}

.checkbox-item:hover {
  background: #eff6ff;
  transform: translateX(4px);
}

.checkbox-item input[type="checkbox"] {
  width: 18px;
  height: 18px;
  margin-right: 12px;
  cursor: pointer;
}

.checkbox-item label {
  font-size: 15px;
  font-weight: 600;
  color: var(--text-dark);
  cursor: pointer;
  margin: 0;
}

.checkbox-item small {
  display: block;
  margin-left: 30px;
  margin-top: 4px;
  color: var(--text-light);
  font-size: 13px;
}

.tag-badge {
  display: inline-flex;
  align-items: center;
  gap: 4px;
  padding: 6px 12px;
  background: #d1fae5;
  color: #065f46;
  border-radius: 20px;
  font-size: 13px;
  font-weight: 600;
}

.divider {
  margin: 24px 0;
  padding-top: 24px;
  border-top: 2px dashed var(--border);
}

.divider-title {
  font-size: 16px;
  font-weight: 700;
  color: var(--text-dark);
  margin-bottom: 16px;
  display: flex;
  align-items: center;
  gap: 8px;
}

.form-group {
  margin-bottom: 16px;
}

.form-label {
  display: block;
  font-weight: 600;
  font-size: 14px;
  color: var(--text-dark);
  margin-bottom: 8px;
}

.form-input,
.form-textarea {
  width: 100%;
  padding: 12px 16px;
  border: 1px solid var(--border);
  border-radius: 8px;
  font-size: 14px;
  transition: all 0.2s;
  font-family: inherit;
}

.form-input:focus,
.form-textarea:focus {
  outline: none;
  border-color: var(--primary);
  box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.form-textarea {
  resize: vertical;
}

.input-group {
  display: flex;
}

.input-group-addon {
  background: #10b981;
  color: white;
  padding: 12px 16px;
  border: 1px solid #10b981;
  border-right: none;
  border-radius: 8px 0 0 8px;
  font-weight: 700;
  font-size: 16px;
}

.input-group .form-input {
  border-radius: 0 8px 8px 0;
}

.form-hint {
  display: block;
  margin-top: 6px;
  font-size: 12px;
  color: var(--text-light);
}

.btn-block {
  width: 100%;
  padding: 12px 24px;
  border: none;
  border-radius: 8px;
  font-weight: 600;
  font-size: 14px;
  cursor: pointer;
  transition: all 0.2s;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
}

.btn-block.primary {
  background: var(--primary);
  color: white;
}

.btn-block.primary:hover {
  background: #1e40af;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
}

.btn-block.success {
  background: #10b981;
  color: white;
}

.btn-block.success:hover {
  background: #059669;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

.btn-block.info {
  background: #f59e0b;
  color: white;
}

.btn-block.info:hover {
  background: #d97706;
}

.info-alert {
  background: #fef3c7;
  border-left: 4px solid #f59e0b;
  padding: 16px 20px;
  border-radius: 8px;
  margin-top: 24px;
}

.info-alert h4 {
  margin: 0 0 12px 0;
  color: var(--text-dark);
  font-size: 16px;
  display: flex;
  align-items: center;
  gap: 8px;
}

.info-alert ul {
  margin: 0;
  padding-left: 20px;
  color: var(--text-dark);
  line-height: 1.8;
}

@media (max-width: 768px) {
  .category-grid {
    grid-template-columns: 1fr;
  }
}
</style>

<!-- Page Header -->
<div class="category-header">
  <div>
    <h1 class="category-title">
      <i class="fas fa-tags" style="color: var(--primary);"></i>
      Quản lý Phân loại & Tags
    </h1>
    <p class="category-subtitle">Tour ID: <?= $idGoi ?></p>
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

<!-- Grid 2 cột -->
<div class="category-grid">
  <!-- LOẠI TOUR -->
  <div class="category-card">
    <div class="card-header">
      <i class="fas fa-list"></i>
      <h3>Loại Tour</h3>
    </div>
    <div class="card-body">
      <p class="card-description">
        Chọn các loại tour phù hợp để phân loại tour này
      </p>
      
      <form action="<?= BASE_URL ?>?act=tour-capnhat-loai" method="POST">
        <input type="hidden" name="id_goi" value="<?= $idGoi ?>">
        
        <?php if (empty($tatCaLoai)): ?>
          <div class="empty-notice" style="padding: 40px 20px;">
            <i class="fas fa-inbox" style="font-size: 48px; opacity: 0.3;"></i>
            <p style="margin-top: 12px; color: var(--text-light);">Chưa có loại tour nào</p>
          </div>
        <?php else: ?>
          <div class="selection-box">
            <?php foreach ($tatCaLoai as $loai): ?>
              <label class="checkbox-item">
                <input 
                  type="checkbox" 
                  name="loai_ids[]" 
                  value="<?= $loai['id'] ?>"
                  <?= in_array($loai['id'], $loaiIds) ? 'checked' : '' ?>
                >
                <label style="display: inline;">
                  <?= htmlspecialchars($loai['ten_loai']) ?>
                </label>
                <?php if (!empty($loai['mota'])): ?>
                  <small><?= htmlspecialchars($loai['mota']) ?></small>
                <?php endif; ?>
              </label>
            <?php endforeach; ?>
          </div>
          
          <button type="submit" class="btn-block primary">
            <i class="fas fa-save"></i>
            Cập nhật Loại Tour
          </button>
        <?php endif; ?>
      </form>
    </div>
  </div>

  <!-- TAGS -->
  <div class="category-card">
    <div class="card-header green">
      <i class="fas fa-hashtag"></i>
      <h3>Tags</h3>
    </div>
    <div class="card-body">
      <p class="card-description">
        Thêm các từ khóa để dễ tìm kiếm tour
      </p>
      
      <form action="<?= BASE_URL ?>?act=tour-capnhat-tags" method="POST">
        <input type="hidden" name="id_goi" value="<?= $idGoi ?>">
        
        <?php if (empty($tatCaTags)): ?>
          <div class="empty-notice" style="padding: 40px 20px;">
            <i class="fas fa-inbox" style="font-size: 48px; opacity: 0.3;"></i>
            <p style="margin-top: 12px; color: var(--text-light);">Chưa có tag nào</p>
          </div>
        <?php else: ?>
          <div class="selection-box">
            <?php foreach ($tatCaTags as $tag): ?>
              <label class="checkbox-item">
                <input 
                  type="checkbox" 
                  name="tag_ids[]" 
                  value="<?= $tag['id'] ?>"
                  <?= in_array($tag['id'], $tagIds) ? 'checked' : '' ?>
                >
                <span class="tag-badge">
                  #<?= htmlspecialchars($tag['ten_tag']) ?>
                </span>
              </label>
            <?php endforeach; ?>
          </div>
          
          <button type="submit" class="btn-block success">
            <i class="fas fa-save"></i>
            Cập nhật Tags
          </button>
        <?php endif; ?>
      </form>
    </div>
  </div>
</div>

<!-- Hướng dẫn -->
<div class="info-alert">
  <h4>
    <i class="fas fa-lightbulb"></i>
    Hướng dẫn sử dụng
  </h4>
  <ul>
    <li><strong>Loại Tour:</strong> Phân loại tour theo khu vực hoặc đặc điểm (VD: Tour trong nước, Tour nước ngoài, Tour nghỉ dưỡng...)</li>
    <li><strong>Tags:</strong> Thêm các từ khóa ngắn gọn để dễ tìm kiếm (VD: #Biển, #Núi, #Phượt, #GiaDinh...)</li>
    <li><strong>Tạo mới:</strong> Để tạo loại tour hoặc tag mới, vui lòng vào trang <a href="<?= BASE_URL ?>?act=admin-categories-tags" style="color: var(--primary); font-weight: 600;">Danh sách Phân loại & Tags</a></li>
  </ul>
</div>

<?php
$content = ob_get_clean();
include './views/admin/layout.php';
?>
