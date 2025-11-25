<?php
/**
 * Tour Detail Page - Trang chi tiết tour
 * Variables: $tour
 */

// Helper function
function safe_html($value) {
    return htmlentities($value ?? '', ENT_QUOTES, 'UTF-8');
}
?>

<style>
.tour-detail-page {
  max-width: 1200px;
  margin: 0 auto;
}

.page-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 32px;
}

.page-title {
  font-size: 28px;
  font-weight: 700;
  color: var(--text-dark);
  margin: 0;
  display: flex;
  align-items: center;
  gap: 12px;
}

.page-title i {
  color: var(--primary);
}

.action-buttons {
  display: flex;
  gap: 12px;
}

.tour-image-section {
  margin-bottom: 24px;
}

.tour-image {
  width: 100%;
  max-width: 800px;
  height: 400px;
  object-fit: cover;
  border-radius: 12px;
  display: block;
  margin: 0 auto;
}

.image-placeholder {
  width: 100%;
  height: 400px;
  background: var(--bg-light);
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--text-light);
  font-size: 48px;
  margin-bottom: 16px;
}

.quick-stats {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 12px;
  margin-top: 16px;
}

.quick-stat {
  text-align: center;
  padding: 16px;
  background: var(--bg-light);
  border-radius: 10px;
}

.quick-stat-value {
  font-size: 24px;
  font-weight: 700;
  color: var(--primary);
  margin: 0;
}

.quick-stat-label {
  font-size: 12px;
  color: var(--text-light);
  margin: 4px 0 0 0;
  text-transform: uppercase;
  font-weight: 600;
}

.info-section {
  margin-bottom: 24px;
}

.info-row {
  display: grid;
  grid-template-columns: 180px 1fr;
  gap: 16px;
  padding: 16px 0;
  border-bottom: 1px solid var(--border);
}

.info-row:last-child {
  border-bottom: none;
}

.info-label {
  font-weight: 600;
  color: var(--text-dark);
  display: flex;
  align-items: center;
  gap: 8px;
}

.info-label i {
  width: 20px;
  text-align: center;
  color: var(--primary);
}

.info-value {
  color: var(--text-dark);
  line-height: 1.6;
}

.price-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 16px;
  margin-top: 16px;
}

.price-card {
  background: var(--bg-light);
  border-radius: 10px;
  padding: 20px;
  text-align: center;
  border: 2px solid var(--border);
  transition: all 0.2s;
}

.price-card:hover {
  border-color: var(--primary);
  box-shadow: 0 4px 12px rgba(37, 99, 235, 0.1);
}

.price-label {
  font-size: 13px;
  color: var(--text-light);
  margin: 0 0 8px 0;
  font-weight: 600;
  text-transform: uppercase;
}

.price-value {
  font-size: 28px;
  font-weight: 800;
  color: var(--primary);
  margin: 0;
}

.price-unit {
  font-size: 14px;
  color: var(--text-light);
  margin: 4px 0 0 0;
}

.badge-status {
  display: inline-block;
  padding: 6px 14px;
  border-radius: 20px;
  font-size: 13px;
  font-weight: 600;
}

.badge-status.active {
  background: #d1fae5;
  color: #065f46;
}

.badge-status.inactive {
  background: #fee2e2;
  color: #991b1b;
}

.content-box {
  background: var(--bg-light);
  padding: 20px;
  border-radius: 10px;
  margin-top: 12px;
  line-height: 1.8;
  color: var(--text-dark);
}

.content-box p {
  margin-bottom: 12px;
}

.content-box ul,
.content-box ol {
  margin-left: 20px;
  margin-bottom: 12px;
}

.empty-content {
  text-align: center;
  padding: 40px 20px;
  color: var(--text-light);
  font-style: italic;
}

.management-links {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 12px;
  margin-top: 20px;
}

.management-link {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 14px 18px;
  background: var(--bg-white);
  border: 1px solid var(--border);
  border-radius: 10px;
  text-decoration: none;
  color: var(--text-dark);
  font-weight: 600;
  transition: all 0.2s;
}

.management-link:hover {
  border-color: var(--primary);
  background: var(--primary);
  color: white;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
}

.management-link i {
  font-size: 18px;
}
</style>

<div class="tour-detail-page">
  <!-- Page Header -->
  <div class="page-header">
    <h1 class="page-title">
      <i class="fas fa-map-marked-alt"></i>
      Chi tiết Tour
    </h1>
    <div class="action-buttons">
      <a href="<?= BASE_URL ?>?act=admin-tour-edit&id=<?= $tour['id_goi'] ?>" class="btn btn-primary">
        <i class="fas fa-edit"></i>
        Chỉnh sửa
      </a>
      <a href="<?= BASE_URL ?>?act=admin-tours" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i>
        Quay lại
      </a>
    </div>
  </div>

  <!-- Tour Image -->
  <div class="card tour-image-section">
    <div class="card-body" style="padding: 0;">
      <?php if (!empty($tour['hinhanh'])): ?>
        <img src="<?= BASE_URL ?>/<?= safe_html($tour['hinhanh']) ?>" 
             alt="<?= safe_html($tour['tengoi']) ?>" 
             class="tour-image">
      <?php else: ?>
        <div class="image-placeholder">
          <i class="fas fa-image"></i>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Tour Information -->
  <div>
      <!-- Basic Information -->
      <div class="card info-section">
        <div class="card-header">
          <h3><i class="fas fa-info-circle"></i> Thông tin tour</h3>
        </div>
        <div class="card-body">
          <div class="info-row">
            <div class="info-label">
              <i class="fas fa-tag"></i>
              Tên tour
            </div>
            <div class="info-value">
              <strong style="font-size: 18px; color: var(--primary);">
                <?= safe_html($tour['tengoi']) ?>
              </strong>
            </div>
          </div>

          <div class="info-row">
            <div class="info-label">
              <i class="fas fa-globe"></i>
              Quốc gia
            </div>
            <div class="info-value">
              <?= safe_html($tour['quocgia']) ?>
              <?php if (!empty($tour['nuocngoai']) && $tour['nuocngoai'] == 1): ?>
                <span class="badge badge-info" style="margin-left: 8px;">Tour nước ngoài</span>
              <?php endif; ?>
            </div>
          </div>

          <div class="info-row">
            <div class="info-label">
              <i class="fas fa-map-marker-alt"></i>
              Tỉnh/Thành phố
            </div>
            <div class="info-value"><?= safe_html($tour['ten_tinh']) ?></div>
          </div>

          <div class="info-row">
            <div class="info-label">
              <i class="fas fa-map-pin"></i>
              Điểm xuất phát
            </div>
            <div class="info-value"><?= safe_html($tour['noixuatphat']) ?></div>
          </div>

          <div class="info-row">
            <div class="info-label">
              <i class="fas fa-location-dot"></i>
              Điểm đến
            </div>
            <div class="info-value"><?= safe_html($tour['vitri']) ?></div>
          </div>

          <div class="info-row">
            <div class="info-label">
              <i class="fas fa-bus"></i>
              Phương tiện
            </div>
            <div class="info-value"><?= safe_html($tour['phuongtien']) ?></div>
          </div>

          <div class="info-row">
            <div class="info-label">
              <i class="fas fa-calendar-days"></i>
              Số ngày
            </div>
            <div class="info-value">
              <strong style="font-size: 18px; color: var(--primary);">
                <?= safe_html($tour['songay']) ?> ngày
              </strong>
            </div>
          </div>

          <div class="info-row">
            <div class="info-label">
              <i class="fas fa-clock"></i>
              Giờ đi
            </div>
            <div class="info-value"><?= safe_html($tour['giodi']) ?></div>
          </div>

          <div class="info-row">
            <div class="info-label">
              <i class="fas fa-calendar-day"></i>
              Ngày xuất phát
            </div>
            <div class="info-value">
              <?= !empty($tour['ngayxuatphat']) ? date('d/m/Y', strtotime($tour['ngayxuatphat'])) : 'Chưa xác định' ?>
            </div>
          </div>

          <div class="info-row">
            <div class="info-label">
              <i class="fas fa-calendar-check"></i>
              Ngày về
            </div>
            <div class="info-value">
              <?= !empty($tour['ngayve']) ? date('d/m/Y', strtotime($tour['ngayve'])) : 'Chưa xác định' ?>
            </div>
          </div>

          <div class="info-row">
            <div class="info-label">
              <i class="fas fa-calendar-plus"></i>
              Ngày đăng
            </div>
            <div class="info-value">
              <?= !empty($tour['ngaydang']) ? date('d/m/Y H:i', strtotime($tour['ngaydang'])) : 'Chưa xác định' ?>
            </div>
          </div>

          <div class="info-row">
            <div class="info-label">
              <i class="fas fa-toggle-on"></i>
              Trạng thái
            </div>
            <div class="info-value">
              <?php if (!empty($tour['trangthai']) && $tour['trangthai'] == 1): ?>
                <span class="badge-status active">
                  <i class="fas fa-check-circle"></i> Đang hiển thị
                </span>
              <?php else: ?>
                <span class="badge-status inactive">
                  <i class="fas fa-times-circle"></i> Đang ẩn
                </span>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>

      <!-- Pricing Information -->
      <div class="card info-section">
        <div class="card-header">
          <h3><i class="fas fa-dollar-sign"></i> Bảng giá</h3>
        </div>
        <div class="card-body">
          <?php if (!empty($tour['khuyenmai']) && $tour['khuyenmai'] > 0): ?>
            <div style="background: #fef3c7; padding: 12px 16px; border-radius: 8px; margin-bottom: 16px; display: flex; align-items: center; gap: 10px;">
              <i class="fas fa-gift" style="color: #f59e0b; font-size: 20px;"></i>
              <span style="color: #78350f; font-weight: 600;">
                Đang có khuyến mãi: <?= safe_html($tour['khuyenmai']) ?>%
              </span>
            </div>
          <?php endif; ?>

          <div class="price-grid">
            <div class="price-card">
              <p class="price-label">
                <i class="fas fa-user"></i> Người lớn
              </p>
              <h3 class="price-value"><?= number_format($tour['giagoi'] ?? 0, 0, ',', '.') ?></h3>
              <p class="price-unit">VNĐ</p>
            </div>

            <div class="price-card">
              <p class="price-label">
                <i class="fas fa-child"></i> Trẻ em
              </p>
              <h3 class="price-value"><?= number_format($tour['giatreem'] ?? 0, 0, ',', '.') ?></h3>
              <p class="price-unit">VNĐ</p>
            </div>

            <div class="price-card">
              <p class="price-label">
                <i class="fas fa-baby"></i> Trẻ nhỏ
              </p>
              <h3 class="price-value"><?= number_format($tour['giatrenho'] ?? 0, 0, ',', '.') ?></h3>
              <p class="price-unit">VNĐ</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Tour Details -->
      <div class="card info-section">
        <div class="card-header">
          <h3><i class="fas fa-file-alt"></i> Chi tiết gói tour</h3>
        </div>
        <div class="card-body">
          <?php if (!empty($tour['chitietgoi'])): ?>
            <div class="content-box">
              <?= $tour['chitietgoi'] ?>
            </div>
          <?php else: ?>
            <div class="empty-content">
              <i class="fas fa-inbox" style="font-size: 48px; opacity: 0.3;"></i>
              <p>Chưa có chi tiết</p>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- Schedule & Program Details (Merged) -->
      <div class="card info-section">
        <div class="card-header">
          <h3><i class="fas fa-route"></i> Lịch trình & Chương trình tour</h3>
        </div>
        <div class="card-body">
          <?php if (!empty($tour['chuongtrinh'])): ?>
            <div class="content-box">
              <?= $tour['chuongtrinh'] ?>
            </div>
          <?php else: ?>
            <div class="empty-content">
              <i class="fas fa-inbox" style="font-size: 48px; opacity: 0.3;"></i>
              <p>Chưa có lịch trình & chương trình</p>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- Notes -->
      <div class="card info-section">
        <div class="card-header">
          <h3><i class="fas fa-exclamation-triangle"></i> Lưu ý</h3>
        </div>
        <div class="card-body">
          <?php if (!empty($tour['luuy'])): ?>
            <div class="content-box">
              <?= $tour['luuy'] ?>
            </div>
          <?php else: ?>
            <div class="empty-content">
              <i class="fas fa-inbox" style="font-size: 48px; opacity: 0.3;"></i>
              <p>Chưa có lưu ý</p>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- Management Links -->
      <div class="card">
        <div class="card-header">
          <h3><i class="fas fa-cog"></i> Quản lý chi tiết</h3>
        </div>
        <div class="card-body">
          <div class="management-links">
            <a href="<?= BASE_URL ?>?act=tour-lichtrinh&id_goi=<?= $tour['id_goi'] ?>" class="management-link">
              <i class="fas fa-route"></i>
              Lịch trình
            </a>
            
            <a href="<?= BASE_URL ?>?act=tour-gallery&id_goi=<?= $tour['id_goi'] ?>" class="management-link">
              <i class="fas fa-images"></i>
              Thư viện ảnh
            </a>
            
            <a href="<?= BASE_URL ?>?act=tour-chinhsach&id_goi=<?= $tour['id_goi'] ?>" class="management-link">
              <i class="fas fa-file-contract"></i>
              Chính sách
            </a>
            
            <a href="<?= BASE_URL ?>?act=tour-phanloai&id_goi=<?= $tour['id_goi'] ?>" class="management-link">
              <i class="fas fa-tags"></i>
              Phân loại
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>
