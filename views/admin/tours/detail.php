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

/* Departure Plans Section */
.departure-plans-section {
  margin-top: 24px;
}

.departure-plans-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 16px;
}

.departure-plans-title {
  font-size: 18px;
  font-weight: 700;
  color: var(--text-dark);
  margin: 0;
  display: flex;
  align-items: center;
  gap: 8px;
}

.departure-plans-title i {
  color: var(--primary);
}

.departure-plans-table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 12px;
}

.departure-plans-table th {
  background: var(--bg-light);
  padding: 12px;
  text-align: left;
  font-weight: 600;
  font-size: 13px;
  color: var(--text-dark);
  border-bottom: 2px solid var(--border);
}

.departure-plans-table td {
  padding: 12px;
  border-bottom: 1px solid var(--border);
  font-size: 14px;
  color: var(--text-dark);
}

.departure-plans-table tbody tr:hover {
  background: var(--bg-light);
}

.departure-plans-table tbody tr:last-child td {
  border-bottom: none;
}

.departure-status-badge {
  display: inline-flex;
  align-items: center;
  gap: 4px;
  padding: 4px 10px;
  border-radius: 12px;
  font-size: 12px;
  font-weight: 600;
}

.departure-status-badge.active {
  background: #d1fae5;
  color: #065f46;
}

.departure-status-badge.inactive {
  background: #fee2e2;
  color: #991b1b;
}

.departure-action-btn {
  padding: 4px 8px;
  border: none;
  border-radius: 6px;
  font-size: 12px;
  cursor: pointer;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: 4px;
  margin: 2px;
  transition: all 0.2s;
}

.departure-action-btn.edit {
  background: #fef3c7;
  color: #78350f;
}

.departure-action-btn.edit:hover {
  background: #f59e0b;
  color: white;
}

.departure-action-btn.delete {
  background: #fee2e2;
  color: #991b1b;
}

.departure-action-btn.delete:hover {
  background: #ef4444;
  color: white;
}

.empty-departure-plans {
  text-align: center;
  padding: 40px 20px;
  color: var(--text-light);
}

.empty-departure-plans i {
  font-size: 48px;
  opacity: 0.3;
  margin-bottom: 12px;
}

.empty-departure-plans p {
  margin-bottom: 16px;
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

          <!-- Departure Plans Section -->
          <div style="margin-top: 24px; padding-top: 24px; border-top: 2px solid var(--border);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
              <h4 style="font-size: 16px; font-weight: 700; margin: 0; color: var(--text-dark);">
                <i class="fas fa-calendar-alt" style="color: var(--primary);"></i> Lịch khởi hành
              </h4>
              <a href="<?= BASE_URL ?>?act=admin-departure-plan-create&id_tour=<?= $tour['id_goi'] ?>" 
                 class="btn btn-primary" 
                 style="padding: 6px 12px; font-size: 12px;">
                <i class="fas fa-plus"></i> Thêm
              </a>
            </div>
            
            <?php if (!empty($departurePlans) && is_array($departurePlans)): ?>
              <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
                  <thead>
                    <tr style="background: var(--bg-light); border-bottom: 2px solid var(--border);">
                      <th style="padding: 10px; text-align: left; font-weight: 600;">Ngày/Giờ</th>
                      <th style="padding: 10px; text-align: left; font-weight: 600;">Điểm tập trung</th>
                      <th style="padding: 10px; text-align: center; font-weight: 600;">Số chỗ</th>
                      <th style="padding: 10px; text-align: left; font-weight: 600;">Ghi chú</th>
                      <th style="padding: 10px; text-align: center; font-weight: 600;">Trạng thái</th>
                      <th style="padding: 10px; text-align: center; font-weight: 600;">Thao tác</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($departurePlans as $plan): 
                      $ngay_khoi_hanh = $plan['ngay_khoi_hanh'] ?? '';
                      $gio_khoi_hanh = $plan['gio_khoi_hanh'] ?? '';
                      $diem_tap_trung = $plan['diem_tap_trung'] ?? '-';
                      $so_cho_du_kien = $plan['so_cho_du_kien'] ?? null;
                      $ghi_chu_van_hanh = $plan['ghi_chu_van_hanh'] ?? '';
                      $trang_thai = $plan['trang_thai'] ?? 0;
                      
                      // Format ngày giờ
                      $ngay_gio = '';
                      if ($ngay_khoi_hanh) {
                        $ngay_gio = date('d/m/Y', strtotime($ngay_khoi_hanh));
                        if ($gio_khoi_hanh) {
                          $ngay_gio .= ' ' . date('H:i', strtotime($gio_khoi_hanh));
                        }
                      }
                    ?>
                      <tr style="border-bottom: 1px solid var(--border);">
                        <td style="padding: 10px;">
                          <strong><?= safe_html($ngay_gio ?: '-') ?></strong>
                        </td>
                        <td style="padding: 10px;"><?= safe_html($diem_tap_trung) ?></td>
                        <td style="padding: 10px; text-align: center;">
                          <?php if ($so_cho_du_kien): ?>
                            <strong><?= number_format($so_cho_du_kien) ?></strong>
                          <?php else: ?>
                            <span style="color: var(--text-light);">-</span>
                          <?php endif; ?>
                        </td>
                        <td style="padding: 10px;">
                          <?php if ($ghi_chu_van_hanh): ?>
                            <span title="<?= safe_html($ghi_chu_van_hanh) ?>">
                              <?= mb_substr($ghi_chu_van_hanh, 0, 30) . (mb_strlen($ghi_chu_van_hanh) > 30 ? '...' : '') ?>
                            </span>
                          <?php else: ?>
                            <span style="color: var(--text-light);">-</span>
                          <?php endif; ?>
                        </td>
                        <td style="padding: 10px; text-align: center;">
                          <?php if ($trang_thai == 1): ?>
                            <span class="badge-status active" style="display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: 600; background: #d1fae5; color: #065f46;">
                              <i class="fas fa-check-circle"></i> Hoạt động
                            </span>
                          <?php else: ?>
                            <span class="badge-status inactive" style="display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: 600; background: #fee2e2; color: #991b1b;">
                              <i class="fas fa-ban"></i> Tạm dừng
                            </span>
                          <?php endif; ?>
                        </td>
                        <td style="padding: 10px; text-align: center;">
                          <a href="<?= BASE_URL ?>?act=admin-departure-plan-edit&id=<?= $plan['id'] ?>&tour_id=<?= $tour['id_goi'] ?>" 
                             title="Sửa"
                             style="padding: 4px 8px; border: none; border-radius: 6px; font-size: 11px; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; background: #fef3c7; color: #78350f; margin: 2px;">
                            <i class="fas fa-edit"></i>
                          </a>
                          <a href="<?= BASE_URL ?>?act=admin-departure-plan-delete&id=<?= $plan['id'] ?>&tour_id=<?= $tour['id_goi'] ?>" 
                             title="Xóa"
                             onclick="return confirm('Bạn có chắc muốn xóa lịch khởi hành này?')"
                             style="padding: 4px 8px; border: none; border-radius: 6px; font-size: 11px; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; background: #fee2e2; color: #991b1b; margin: 2px;">
                            <i class="fas fa-trash"></i>
                          </a>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            <?php else: ?>
              <div style="text-align: center; padding: 30px 20px; color: var(--text-light);">
                <i class="fas fa-calendar-times" style="font-size: 36px; opacity: 0.3; margin-bottom: 8px;"></i>
                <p style="margin-bottom: 12px; font-size: 14px;">Chưa có lịch khởi hành</p>
                <a href="<?= BASE_URL ?>?act=admin-departure-plan-create&id_tour=<?= $tour['id_goi'] ?>" 
                   class="btn btn-primary" 
                   style="padding: 8px 16px; font-size: 13px;">
                  <i class="fas fa-plus"></i> Tạo lịch khởi hành
                </a>
              </div>
            <?php endif; ?>
          </div>

          <!-- Management Links in Info Card -->
          <div style="margin-top: 24px; padding-top: 24px; border-top: 2px solid var(--border);">
            <h4 style="font-size: 16px; font-weight: 700; margin-bottom: 16px; color: var(--text-dark);">
              <i class="fas fa-cog" style="color: var(--primary);"></i> Quản lý chi tiết
            </h4>
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
    </div>
  </div>

</div>
