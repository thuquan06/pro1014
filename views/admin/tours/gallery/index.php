<?php
/**
 * Thư viện ảnh Tour - Modern Interface
 * Updated: 2025-11-25
 */

ob_start();
?>

<style>
.gallery-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
  flex-wrap: wrap;
  gap: 16px;
}

.gallery-title {
  font-size: 28px;
  font-weight: 700;
  color: var(--text-dark);
  margin: 0;
}

.gallery-subtitle {
  color: var(--text-light);
  font-size: 14px;
  margin-top: 4px;
}

.gallery-actions {
  display: flex;
  gap: 12px;
}

.gallery-grid {
  background: white;
  border: 1px solid var(--border);
  border-radius: 12px;
  padding: 24px;
}

.photos-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
  gap: 20px;
  margin-bottom: 24px;
}

.photo-card {
  background: white;
  border: 1px solid var(--border);
  border-radius: 12px;
  overflow: hidden;
  transition: all 0.3s;
  position: relative;
}

.photo-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
}

.featured-badge {
  position: absolute;
  top: 12px;
  right: 12px;
  background: #f59e0b;
  color: white;
  padding: 6px 12px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 4px;
  z-index: 10;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}

.photo-image {
  width: 100%;
  height: 200px;
  object-fit: cover;
  cursor: pointer;
  transition: all 0.3s;
}

.photo-card:hover .photo-image {
  transform: scale(1.05);
}

.photo-content {
  padding: 16px;
}

.photo-description {
  font-size: 13px;
  color: var(--text-dark);
  margin: 0 0 12px 0;
  line-height: 1.5;
  min-height: 40px;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.photo-actions {
  display: flex;
  gap: 8px;
}

.btn-photo {
  flex: 1;
  padding: 8px 12px;
  border: none;
  border-radius: 8px;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
  text-decoration: none;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
}

.btn-photo.featured {
  background: #fef3c7;
  color: #78350f;
}

.btn-photo.featured:hover {
  background: #f59e0b;
  color: white;
}

.btn-photo.delete {
  background: #fee2e2;
  color: #991b1b;
}

.btn-photo.delete:hover {
  background: #ef4444;
  color: white;
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

.stats-card {
  background: linear-gradient(135deg, var(--primary), #1e40af);
  border-radius: 12px;
  padding: 20px;
  color: white;
  text-align: center;
}

.stats-card h4 {
  margin: 0;
  font-size: 32px;
  font-weight: 700;
}

.stats-card p {
  margin: 8px 0 0;
  opacity: 0.9;
}

/* Lightbox */
.lightbox {
  display: none;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.9);
  z-index: 9999;
  align-items: center;
  justify-content: center;
}

.lightbox.active {
  display: flex;
}

.lightbox img {
  max-width: 90%;
  max-height: 90%;
  border-radius: 8px;
}

.lightbox-close {
  position: absolute;
  top: 20px;
  right: 30px;
  color: white;
  font-size: 40px;
  font-weight: bold;
  cursor: pointer;
}

@media (max-width: 768px) {
  .photos-grid {
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 16px;
  }
}
</style>

<!-- Page Header -->
<div class="gallery-header">
  <div>
    <h1 class="gallery-title">
      <i class="fas fa-images" style="color: var(--primary);"></i>
      Thư viện ảnh
    </h1>
    <p class="gallery-subtitle">Tour ID: <?= $idGoi ?></p>
  </div>
  
  <div class="gallery-actions">
    <a href="<?= BASE_URL ?>?act=tour-gallery-them&id_goi=<?= $idGoi ?>" class="btn btn-primary">
      <i class="fas fa-upload"></i>
      Upload ảnh
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

<?php if (isset($_SESSION['errors'])): ?>
  <div class="alert alert-error">
    <strong><i class="fas fa-exclamation-circle"></i> Có lỗi:</strong>
    <ul style="margin: 10px 0 0 20px;">
      <?php foreach ($_SESSION['errors'] as $error): ?>
        <li><?= $error ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
  <?php unset($_SESSION['errors']); ?>
<?php endif; ?>

<!-- Gallery Grid -->
<div class="gallery-grid">
  <?php if (empty($hinhanh)): ?>
    <div class="empty-state">
      <i class="fas fa-images"></i>
      <h3>Chưa có hình ảnh</h3>
      <p>Hãy upload ảnh đầu tiên cho tour này</p>
      <br>
      <a href="<?= BASE_URL ?>?act=tour-gallery-them&id_goi=<?= $idGoi ?>" class="btn btn-primary">
        <i class="fas fa-upload"></i>
        Upload ảnh ngay
      </a>
    </div>
  <?php else: ?>
    <div class="photos-grid">
      <?php foreach ($hinhanh as $anh): ?>
        <div class="photo-card">
          <?php if ($anh['anh_daodien'] == 1): ?>
            <div class="featured-badge">
              <i class="fas fa-star"></i>
              Ảnh đại diện
            </div>
          <?php endif; ?>
          
          <img 
            src="<?= BASE_URL . $anh['duongdan_anh'] ?>" 
            alt="<?= htmlspecialchars($anh['mota_anh']) ?>"
            class="photo-image"
            onclick="openLightbox('<?= BASE_URL . $anh['duongdan_anh'] ?>')"
          >
          
          <div class="photo-content">
            <?php if (!empty($anh['mota_anh'])): ?>
              <p class="photo-description">
                <?= htmlspecialchars($anh['mota_anh']) ?>
              </p>
            <?php else: ?>
              <p class="photo-description" style="color: var(--text-light); font-style: italic;">
                Chưa có mô tả
              </p>
            <?php endif; ?>
            
            <div class="photo-actions">
              <?php if ($anh['anh_daodien'] != 1): ?>
                <a href="<?= BASE_URL ?>?act=tour-gallery-dai-dien&id=<?= $anh['id'] ?>&id_goi=<?= $idGoi ?>" 
                   class="btn-photo featured" 
                   title="Đặt làm ảnh đại diện">
                  <i class="fas fa-star"></i>
                  Đại diện
                </a>
              <?php endif; ?>
              <a href="<?= BASE_URL ?>?act=tour-gallery-xoa&id=<?= $anh['id'] ?>&id_goi=<?= $idGoi ?>" 
                 class="btn-photo delete"
                 onclick="return confirm('Bạn có chắc muốn xóa ảnh này?')"
                 title="Xóa ảnh">
                <i class="fas fa-trash"></i>
                Xóa
              </a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Tổng kết -->
    <div class="stats-card">
      <h4><?= count($hinhanh) ?></h4>
      <p>Tổng số hình ảnh</p>
    </div>
  <?php endif; ?>
</div>

<!-- Lightbox -->
<div id="lightbox" class="lightbox" onclick="closeLightbox()">
  <span class="lightbox-close">&times;</span>
  <img id="lightbox-img" src="" alt="">
</div>

<script>
function openLightbox(src) {
  document.getElementById('lightbox').classList.add('active');
  document.getElementById('lightbox-img').src = src;
  document.body.style.overflow = 'hidden';
}

function closeLightbox() {
  document.getElementById('lightbox').classList.remove('active');
  document.body.style.overflow = 'auto';
}

// Close on Escape key
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') {
    closeLightbox();
  }
});
</script>

<?php
$content = ob_get_clean();
include './views/admin/layout.php';
?>
