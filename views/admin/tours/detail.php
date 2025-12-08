<?php
// Không còn khuyến mãi, chỉ hiển thị giá gốc
?>

<style>
* { box-sizing: border-box; }

.detail-container {
  max-width: 1400px;
  margin: 0 auto;
  padding: 20px;
}

.detail-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
  flex-wrap: wrap;
  gap: 16px;
}

.detail-title {
  font-size: 24px;
  font-weight: 700;
  color: #1f2937;
  margin: 0;
}

.actions {
  display: flex;
  gap: 8px;
}

.header-left-actions {
  display: flex;
  gap: 8px;
  align-items: center;
}

.btn-outline {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  border: 2px solid #667eea;
  font-weight: 600;
  box-shadow: 0 2px 4px rgba(102, 126, 234, 0.3);
  transition: all 0.3s ease;
}

.btn-outline:hover {
  background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
  border-color: #764ba2;
  color: white;
  box-shadow: 0 4px 8px rgba(102, 126, 234, 0.4);
  transform: translateY(-2px);
}

.card {
  background: white;
  border-radius: 12px;
  padding: 20px;
  margin-bottom: 20px;
  border: 1px solid #e5e7eb;
}

.card-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 16px;
  padding-bottom: 12px;
  border-bottom: 2px solid #f3f4f6;
}

.card-title {
  font-size: 18px;
  font-weight: 700;
  color: #1f2937;
  margin: 0;
  display: flex;
  align-items: center;
  gap: 8px;
}

.card-title i {
  color: #3b82f6;
}

.btn {
  padding: 8px 16px;
  border-radius: 6px;
  font-size: 14px;
  font-weight: 600;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: 6px;
  transition: all 0.2s;
  border: none;
  cursor: pointer;
}

.btn-sm {
  padding: 6px 12px;
  font-size: 13px;
}

.btn-primary {
  background: #3b82f6;
  color: white;
}

.btn-primary:hover {
  background: #2563eb;
}

.btn-secondary {
  background: #6b7280;
  color: white;
}

.btn-secondary:hover {
  background: #4b5563;
}

.grid-2 {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 0;
}

.info-section {
  padding-right: 24px;
  border-right: 2px solid #3b82f6;
}

.info-group {
  margin-bottom: 16px;
}

.info-group:last-child {
  margin-bottom: 0;
}

.info-label {
  font-size: 13px;
  font-weight: 600;
  color: #6b7280;
  margin-bottom: 6px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.info-value {
  font-size: 15px;
  color: #1f2937;
  font-weight: 500;
  line-height: 1.5;
}

.price-section {
  background: #f9fafb;
  padding: 16px 20px;
  border-radius: 8px;
  margin-left: 24px;
}

.price-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 8px 0;
  border-bottom: 1px solid #e5e7eb;
}

.price-row:last-child { border-bottom: none; }

.price-label {
  font-size: 14px;
  color: #4b5563;
}

.price-value {
  font-size: 15px;
  font-weight: 700;
  color: #1f2937;
}

.price-sale {
  color: #ef4444;
  font-weight: 700;
  margin-right: 8px;
}

.price-original {
  color: #9ca3af;
  text-decoration: line-through;
  font-size: 13px;
}

.tour-image {
  width: 100%;
  height: 350px;
  object-fit: cover;
  border-radius: 8px;
}

.quick-links {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 12px;
}

.quick-link {
  text-align: center;
  padding: 16px;
  background: #f9fafb;
  border-radius: 8px;
  text-decoration: none;
  color: #1f2937;
  font-weight: 600;
  font-size: 14px;
  transition: all 0.2s;
  border: 1px solid #e5e7eb;
}

.quick-link:hover {
  background: #3b82f6;
  color: white;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

.quick-link i {
  display: block;
  font-size: 24px;
  margin-bottom: 8px;
}

.departure-table-wrap {
  overflow-x: auto;
}

.departure-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 14px;
}

.departure-table th {
  background: #f9fafb;
  padding: 12px;
  text-align: left;
  font-weight: 600;
  border-bottom: 2px solid #e5e7eb;
}

.departure-table td {
  padding: 12px;
  border-bottom: 1px solid #e5e7eb;
}

.status-active {
  background: #d1fae5;
  color: #065f46;
  padding: 4px 12px;
  border-radius: 12px;
  font-size: 12px;
  font-weight: 600;
}

.status-inactive {
  background: #fee2e2;
  color: #991b1b;
  padding: 4px 12px;
  border-radius: 12px;
  font-size: 12px;
  font-weight: 600;
}

.service-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 12px;
}

.service-item {
  padding: 12px;
  background: #f9fafb;
  border-radius: 6px;
  border-left: 3px solid #10b981;
}

.service-name {
  font-weight: 600;
  color: #1f2937;
  margin-bottom: 4px;
}

.service-provider {
  font-size: 12px;
  color: #6b7280;
}


.content-scrollable {
  max-height: 400px;
  overflow-y: auto;
  padding-right: 8px;
}

.content-scrollable::-webkit-scrollbar {
  width: 6px;
}

.content-scrollable::-webkit-scrollbar-track {
  background: #f1f5f9;
  border-radius: 10px;
}

.content-scrollable::-webkit-scrollbar-thumb {
  background: #3b82f6;
  border-radius: 10px;
}

.empty-state {
  text-align: center;
  padding: 40px;
  color: #9ca3af;
}

@media (max-width: 768px) {
  .header-left-actions {
    flex-wrap: wrap;
  }
  
  .grid-2, .quick-links {
    grid-template-columns: 1fr;
  }
  
  .service-grid {
    grid-template-columns: 1fr;
  }
}
</style>

<div class="detail-container">
  <!-- Header -->
  <div class="detail-header">
    <h1 class="detail-title">
      <i class="fas fa-map-marked-alt"></i>
      <?= htmlspecialchars($tour['tengoi'] ?? 'Chi tiết tour') ?>
    </h1>
    <div class="header-left-actions">
      <a href="<?= BASE_URL ?>?act=tour-gallery&id_goi=<?= $tour['id_goi'] ?>" class="btn btn-outline" style="margin-right: 12px; padding: 10px 20px; font-size: 14px; display: inline-flex; align-items: center; gap: 8px;">
        <i class="fas fa-images" style="font-size: 16px;"></i> <strong>Thư viện</strong>
      </a>
      <a href="<?= BASE_URL ?>?act=tour-chinhsach&id_goi=<?= $tour['id_goi'] ?>" class="btn btn-outline" style="margin-right: 12px; padding: 10px 20px; font-size: 14px; display: inline-flex; align-items: center; gap: 8px;">
        <i class="fas fa-file-contract" style="font-size: 16px;"></i> <strong>Chính sách</strong>
      </a>
      <a href="<?= BASE_URL ?>?act=admin-tour-edit&id=<?= $tour['id_goi'] ?>" class="btn btn-primary">
        <i class="fas fa-edit"></i> Sửa
      </a>
      <a href="<?= BASE_URL ?>?act=admin-tours" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Quay lại
      </a>
    </div>
  </div>

  <!-- Tour Image -->
  <?php if (!empty($tour['hinhanh'])): ?>
    <div class="card">
      <img src="<?= BASE_URL . $tour['hinhanh'] ?>" alt="<?= htmlspecialchars($tour['tengoi'] ?? '') ?>" class="tour-image">
    </div>
  <?php endif; ?>

  <!-- Basic Info & Price (Full Width) -->
  <div class="card">
        <div class="card-header">
          <div class="card-title">
            <i class="fas fa-info-circle"></i> Thông tin & Giá tour
          </div>
        </div>
        
        <div class="grid-2">
          <div class="info-section">
            <h3 style="margin: 0 0 16px 0; font-size: 18px; font-weight: 700; color: #1f2937; padding-bottom: 12px; border-bottom: 2px solid #e5e7eb;">
              <i class="fas fa-info-circle" style="color: #3b82f6; margin-right: 8px;"></i>Thông tin
            </h3>
            <div class="info-group">
              <div class="info-label">Mã tour</div>
              <div class="info-value"><?= htmlspecialchars($tour['mato'] ?? 'N/A') ?></div>
            </div>
            <div class="info-group">
              <div class="info-label">Nơi xuất phát</div>
              <div class="info-value"><?= htmlspecialchars($tour['noixuatphat'] ?? 'N/A') ?></div>
            </div>
            <div class="info-group">
              <div class="info-label">Điểm đến</div>
              <div class="info-value">
                <?php
                $diemDen = [];
                if (!empty($tour['quocgia']) && $tour['quocgia'] !== 'Việt Nam') {
                  $diemDen[] = $tour['quocgia'];
                } elseif (isset($tour['nuocngoai']) && $tour['nuocngoai'] == 1) {
                  $diemDen[] = 'Nước ngoài';
                }
                echo !empty($diemDen) ? htmlspecialchars(implode(' - ', $diemDen)) : 'N/A';
                ?>
              </div>
            </div>
            <div class="info-group">
              <div class="info-label">Số ngày</div>
              <div class="info-value"><?= htmlspecialchars($tour['songay'] ?? 'N/A') ?></div>
            </div>
            <div class="info-group">
              <div class="info-label">Ngày đăng</div>
              <div class="info-value">
                <?= !empty($tour['ngaydang']) ? date('d/m/Y', strtotime($tour['ngaydang'])) : 'N/A' ?>
              </div>
            </div>
          </div>
          
          <div class="price-section">
            <h3 style="margin: 0 0 16px 0; font-size: 18px; font-weight: 700; color: #1f2937; padding-bottom: 12px; border-bottom: 2px solid #e5e7eb;">
              <i class="fas fa-money-bill-wave" style="color: #3b82f6; margin-right: 8px;"></i>Giá
            </h3>
            
            <div class="price-row">
              <span class="price-label"><i class="fas fa-user"></i> Người lớn</span>
              <span class="price-value" style="color: #1f2937; font-size: 16px; font-weight: 700;">
                <?= number_format((float)($tour['giagoi'] ?? 0), 0, ',', '.') ?> đ
              </span>
            </div>
            
            <div class="price-row">
              <span class="price-label"><i class="fas fa-child"></i> Trẻ em (6-11 tuổi)</span>
              <span class="price-value" style="color: #1f2937; font-size: 16px; font-weight: 700;">
                <?= number_format((float)($tour['giatreem'] ?? 0), 0, ',', '.') ?> đ
              </span>
            </div>
            
            <div class="price-row">
              <span class="price-label"><i class="fas fa-baby"></i> Trẻ nhỏ (2-5 tuổi)</span>
              <span class="price-value" style="color: #1f2937; font-size: 16px; font-weight: 700;">
                <?= number_format((float)($tour['giatrenho'] ?? 0), 0, ',', '.') ?> đ
              </span>
            </div>
          </div>
        </div>
  </div>

  <!-- Departure Plans -->
  <?php if (!empty($departurePlans)): ?>
  <div class="card">
    <div class="card-header">
      <div class="card-title">
        <i class="fas fa-calendar-alt"></i> Lịch khởi hành
      </div>
      <a href="<?= BASE_URL ?>?act=admin-departure-plan-create&tour_id=<?= $tour['id_goi'] ?>" class="btn btn-sm btn-primary">
        <i class="fas fa-plus"></i> Thêm lịch
      </a>
    </div>
    <div class="departure-table-wrap">
      <table class="departure-table">
        <thead>
          <tr>
            <th>Ngày giờ</th>
            <th>Điểm tập trung</th>
            <th>Số chỗ</th>
            <th>Trạng thái</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($departurePlans as $plan): ?>
            <tr>
              <td>
                <?php 
                $date = !empty($plan['ngay_khoi_hanh']) ? date('d/m/Y', strtotime($plan['ngay_khoi_hanh'])) : '';
                $time = !empty($plan['gio_khoi_hanh']) ? date('H:i', strtotime($plan['gio_khoi_hanh'])) : '';
                echo $date . ($time ? ' ' . $time : '');
                ?>
              </td>
              <td><?= htmlspecialchars($plan['diem_tap_trung'] ?? 'N/A') ?></td>
              <td><?= htmlspecialchars($plan['so_cho'] ?? $plan['so_cho_du_kien'] ?? 'N/A') ?></td>
              <td>
                <?php if ($plan['trang_thai'] == 1): ?>
                  <span class="status-active">Hoạt động</span>
                <?php else: ?>
                  <span class="status-inactive">Tạm dừng</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
  <?php endif; ?>

  <!-- Hướng dẫn viên -->
  <div class="card">
    <div class="card-header">
      <div class="card-title">
        <i class="fas fa-user-tie"></i> Hướng dẫn viên
      </div>
      <a href="<?= BASE_URL ?>?act=admin-assignment-create&tour_id=<?= $tour['id_goi'] ?>" class="btn btn-sm btn-primary">
        <i class="fas fa-plus"></i> Thêm phân công
      </a>
    </div>
    <div style="padding: 4px 0;">
      <?php if (!empty($assignments)): ?>
        <div style="display: grid; gap: 12px;">
          <?php foreach ($assignments as $assignment): ?>
            <div style="background: #f9fafb; padding: 16px; border-radius: 8px; border: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center;">
              <div style="flex: 1;">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                  <i class="fas fa-user" style="color: #3b82f6; font-size: 16px;"></i>
                  <strong style="font-size: 15px; color: #1f2937;"><?= htmlspecialchars($assignment['ho_ten'] ?? 'N/A') ?></strong>
                  <?php if (isset($assignment['vai_tro'])): ?>
                    <span style="background: #e0e7ff; color: #3730a3; padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 600;">
                      <?= htmlspecialchars($assignment['vai_tro']) ?>
                    </span>
                  <?php endif; ?>
                </div>
                <div style="display: flex; gap: 20px; flex-wrap: wrap; color: #6b7280; font-size: 13px;">
                  <?php if (!empty($assignment['so_dien_thoai'])): ?>
                    <span><i class="fas fa-phone"></i> <?= htmlspecialchars($assignment['so_dien_thoai']) ?></span>
                  <?php endif; ?>
                  <?php if (!empty($assignment['email'])): ?>
                    <span><i class="fas fa-envelope"></i> <?= htmlspecialchars($assignment['email']) ?></span>
                  <?php endif; ?>
                  <?php if (!empty($assignment['ngay_khoi_hanh'])): ?>
                    <span><i class="fas fa-calendar"></i> <?= date('d/m/Y', strtotime($assignment['ngay_khoi_hanh'])) ?></span>
                  <?php endif; ?>
                </div>
              </div>
              <div style="display: flex; gap: 8px;">
                <a href="<?= BASE_URL ?>?act=admin-assignment-edit&id=<?= $assignment['id'] ?>" class="btn btn-sm btn-secondary">
                  <i class="fas fa-edit"></i> Sửa
                </a>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <div style="text-align: center; padding: 30px; color: #9ca3af;">
          <i class="fas fa-user-tie" style="font-size: 36px; margin-bottom: 12px; opacity: 0.5;"></i>
          <p style="margin: 0; font-size: 14px;">Chưa có phân công HDV cho tour này</p>
          <a href="<?= BASE_URL ?>?act=admin-assignment-create&tour_id=<?= $tour['id_goi'] ?>" class="btn btn-sm btn-primary" style="margin-top: 12px;">
            <i class="fas fa-plus"></i> Tạo phân công đầu tiên
          </a>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Categories & Tags -->
  <div class="card">
    <div class="card-header">
      <div class="card-title">
        <i class="fas fa-tags"></i> Phân loại & Tags
      </div>
      <a href="<?= BASE_URL ?>?act=tour-phanloai&id_goi=<?= $tour['id_goi'] ?>" class="btn btn-sm btn-primary">
        <i class="fas fa-edit"></i> Sửa
      </a>
    </div>
    <div style="padding: 4px 0;">
      <?php if (!empty($tourCategories)): ?>
        <div style="margin-bottom: 12px;">
          <strong style="font-size: 13px; color: #6b7280; display: block; margin-bottom: 8px;">Loại tour:</strong>
          <div style="display: flex; flex-wrap: wrap; gap: 8px;">
            <?php foreach ($tourCategories as $cat): ?>
              <span style="background: #dbeafe; color: #1e40af; padding: 6px 12px; border-radius: 16px; font-size: 13px; font-weight: 600;">
                <i class="fas fa-folder"></i> <?= htmlspecialchars($cat['ten_loai']) ?>
              </span>
            <?php endforeach; ?>
          </div>
        </div>
      <?php else: ?>
        <div style="margin-bottom: 12px;">
          <strong style="font-size: 13px; color: #6b7280; display: block; margin-bottom: 8px;">Loại tour:</strong>
          <div style="color: #9ca3af; font-style: italic; font-size: 13px;">Chưa chọn loại tour</div>
        </div>
      <?php endif; ?>
      
      <?php if (!empty($tourTags)): ?>
        <div>
          <strong style="font-size: 13px; color: #6b7280; display: block; margin-bottom: 8px;">Tags:</strong>
          <div style="display: flex; flex-wrap: wrap; gap: 8px;">
            <?php foreach ($tourTags as $tag): ?>
              <span style="background: #d1fae5; color: #065f46; padding: 6px 12px; border-radius: 16px; font-size: 13px; font-weight: 600;">
                <i class="fas fa-hashtag"></i><?= htmlspecialchars($tag['ten_tag']) ?>
              </span>
            <?php endforeach; ?>
          </div>
        </div>
      <?php else: ?>
        <div>
          <strong style="font-size: 13px; color: #6b7280; display: block; margin-bottom: 8px;">Tags:</strong>
          <div style="color: #9ca3af; font-style: italic; font-size: 13px;">Chưa có tags</div>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Services -->
  <?php if (!empty($tourServices)): ?>
  <div class="card">
    <div class="card-header">
      <div class="card-title">
        <i class="fas fa-concierge-bell"></i> Dịch vụ
      </div>
    </div>
    <div class="service-grid">
      <?php foreach ($tourServices as $service): ?>
        <div class="service-item">
          <div class="service-name">
            <i class="fas fa-check-circle"></i>
            <?= htmlspecialchars($service['ten_dich_vu'] ?? 'N/A') ?>
          </div>
          <?php if (!empty($service['nha_cung_cap'])): ?>
            <div class="service-provider">
              <?= htmlspecialchars($service['nha_cung_cap']) ?>
            </div>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>



  <!-- Notes -->
  <?php if (!empty($tour['luuy'])): ?>
  <div class="card">
    <div class="card-header">
      <div class="card-title">
        <i class="fas fa-exclamation-triangle"></i> Lưu ý
      </div>
    </div>
    <div class="content-scrollable">
      <?= html_entity_decode($tour['luuy']) ?>
    </div>
  </div>
  <?php endif; ?>
</div>

