<?php
// Check for active promotion - Logic đơn giản và chắc chắn
$coKhuyenMai = false;
$phantram = 0;

// Lấy giá trị từ tour
$khuyenmai = $tour['khuyenmai'] ?? 0;
$khuyenmai_phantram = isset($tour['khuyenmai_phantram']) ? (float)$tour['khuyenmai_phantram'] : 0;

// Kiểm tra khuyến mãi - chấp nhận nhiều format
// Chuyển về int để so sánh chắc chắn
$khuyenmaiInt = (int)$khuyenmai;

// Biến để đánh dấu khuyến mãi đã hết hạn
$khuyenMaiHetHan = false;

// Nếu khuyến mãi = 1 và có phần trăm > 0
if ($khuyenmaiInt == 1 && $khuyenmai_phantram > 0) {
    // Kiểm tra thời gian (nếu có)
    $tungay = isset($tour['khuyenmai_tungay']) ? trim($tour['khuyenmai_tungay']) : '';
    $denngay = isset($tour['khuyenmai_denngay']) ? trim($tour['khuyenmai_denngay']) : '';
    $today = date('Y-m-d');
    
    // Nếu không có ngày, khuyến mãi luôn hiệu lực
    if (empty($tungay) && empty($denngay)) {
        $coKhuyenMai = true;
        $phantram = $khuyenmai_phantram;
    } else {
        // Kiểm tra ngày - chỉ kiểm tra nếu có giá trị
        $checkStart = empty($tungay) || $tungay === '' || $today >= $tungay;
        // Cho phép hiển thị nếu ngày kết thúc >= hôm nay (bao gồm cả hôm nay)
        $checkEnd = empty($denngay) || $denngay === '' || $today <= $denngay;
        
        // Nếu cả hai điều kiện đều đúng
        if ($checkStart && $checkEnd) {
            $coKhuyenMai = true;
            $phantram = $khuyenmai_phantram;
        } else {
            // Khuyến mãi đã hết hạn
            $khuyenMaiHetHan = true;
            // Vẫn hiển thị giá giảm nhưng có cảnh báo
            $coKhuyenMai = true;
            $phantram = $khuyenmai_phantram;
        }
    }
}
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
  background: #f9fafb;
  color: #1f2937;
  border: 1px solid #e5e7eb;
}

.btn-outline:hover {
  background: #f3f4f6;
  border-color: #d1d5db;
  color: #1f2937;
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

.itinerary-day-card {
  background: #f9fafb;
  border-radius: 8px;
  border-left: 4px solid #3b82f6;
  margin-bottom: 16px;
  overflow: hidden;
}

.day-header {
  background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
  padding: 12px 16px;
  color: white;
}

.day-number {
  font-size: 16px;
  font-weight: 700;
  display: flex;
  align-items: center;
  gap: 8px;
}

.day-content {
  padding: 16px;
  background: white;
  line-height: 1.8;
  color: #374151;
}

.day-content img {
  max-width: 100%;
  height: auto;
  border-radius: 8px;
  margin: 12px 0;
}

.day-content h3 {
  color: #3b82f6;
  font-size: 15px;
  margin: 12px 0 8px 0;
}

.day-content p {
  margin-bottom: 12px;
}

.day-content ul, .day-content ol {
  margin-left: 20px;
  margin-bottom: 12px;
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
      <a href="<?= BASE_URL ?>?act=tour-gallery&id_goi=<?= $tour['id_goi'] ?>" class="btn btn-sm btn-outline">
        <i class="fas fa-images"></i> Thư viện
      </a>
      <a href="<?= BASE_URL ?>?act=tour-chinhsach&id_goi=<?= $tour['id_goi'] ?>" class="btn btn-sm btn-outline">
        <i class="fas fa-file-contract"></i> Chính sách
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
            
            <?php if ($coKhuyenMai): ?>
            <div class="price-row" style="border-bottom: 2px solid #e5e7eb; padding-bottom: 16px; margin-bottom: 16px;">
              <span class="price-label">Khuyến mãi</span>
              <span class="price-value" style="color: <?= $khuyenMaiHetHan ? '#9ca3af' : '#ef4444' ?>; font-weight: 700;">
                <i class="fas fa-tag"></i> Giảm <?= $phantram ?>%
                <?php if ($khuyenMaiHetHan): ?>
                  <br><span style="background: #fee2e2; color: #991b1b; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; display: inline-block; margin-top: 4px;">
                    <i class="fas fa-exclamation-triangle"></i> Đã hết hạn (<?= !empty($tour['khuyenmai_denngay']) ? date('d/m/Y', strtotime($tour['khuyenmai_denngay'])) : 'N/A' ?>)
                  </span>
                <?php elseif (!empty($tour['khuyenmai_denngay'])): ?>
                  <br><small style="font-weight: 400; color: #6b7280;">
                    Đến: <?= date('d/m/Y', strtotime($tour['khuyenmai_denngay'])) ?>
                  </small>
                <?php endif; ?>
              </span>
            </div>
            <?php endif; ?>
            
            <?php 
            // Debug info - hiển thị tạm thời để kiểm tra
            // Thêm &debug=1 vào URL để xem thông tin debug
            if (isset($_GET['debug']) && $_GET['debug'] == '1'): 
            ?>
              <div style="background: #fff3cd; border: 2px solid #ffc107; padding: 15px; margin-bottom: 15px; border-radius: 6px; font-size: 13px; font-family: monospace;">
                <strong style="color: #856404; font-size: 14px;">🔍 DEBUG INFO - Kiểm tra khuyến mãi:</strong><br><br>
                <table style="width: 100%; border-collapse: collapse;">
                  <tr style="background: #fff3cd;">
                    <td style="padding: 5px; border: 1px solid #ffc107;"><strong>Field</strong></td>
                    <td style="padding: 5px; border: 1px solid #ffc107;"><strong>Giá trị</strong></td>
                    <td style="padding: 5px; border: 1px solid #ffc107;"><strong>Type</strong></td>
                  </tr>
                  <tr>
                    <td style="padding: 5px; border: 1px solid #ffc107;">khuyenmai</td>
                    <td style="padding: 5px; border: 1px solid #ffc107;"><?= var_export($tour['khuyenmai'] ?? 'null', true) ?></td>
                    <td style="padding: 5px; border: 1px solid #ffc107;"><?= gettype($tour['khuyenmai'] ?? null) ?></td>
                  </tr>
                  <tr>
                    <td style="padding: 5px; border: 1px solid #ffc107;">khuyenmai_phantram</td>
                    <td style="padding: 5px; border: 1px solid #ffc107;"><?= var_export($tour['khuyenmai_phantram'] ?? 'null', true) ?></td>
                    <td style="padding: 5px; border: 1px solid #ffc107;"><?= gettype($tour['khuyenmai_phantram'] ?? null) ?></td>
                  </tr>
                  <tr>
                    <td style="padding: 5px; border: 1px solid #ffc107;">khuyenmai_tungay</td>
                    <td style="padding: 5px; border: 1px solid #ffc107;"><?= var_export($tour['khuyenmai_tungay'] ?? 'null', true) ?></td>
                    <td style="padding: 5px; border: 1px solid #ffc107;"><?= gettype($tour['khuyenmai_tungay'] ?? null) ?></td>
                  </tr>
                  <tr>
                    <td style="padding: 5px; border: 1px solid #ffc107;">khuyenmai_denngay</td>
                    <td style="padding: 5px; border: 1px solid #ffc107;"><?= var_export($tour['khuyenmai_denngay'] ?? 'null', true) ?></td>
                    <td style="padding: 5px; border: 1px solid #ffc107;"><?= gettype($tour['khuyenmai_denngay'] ?? null) ?></td>
                  </tr>
                  <tr style="background: #d4edda;">
                    <td style="padding: 5px; border: 1px solid #ffc107;"><strong>Kết quả kiểm tra:</strong></td>
                    <td style="padding: 5px; border: 1px solid #ffc107;" colspan="2">
                      <strong>coKhuyenMai:</strong> <?= $coKhuyenMai ? '<span style="color: green;">TRUE ✓</span>' : '<span style="color: red;">FALSE ✗</span>' ?><br>
                      <strong>phantram:</strong> <?= $phantram ?><br>
                      <strong>Today:</strong> <?= date('Y-m-d') ?>
                    </td>
                  </tr>
                </table>
                <br>
                <div style="background: #d1ecf1; padding: 10px; border-radius: 4px; margin-top: 10px;">
                  <strong>📝 SQL Query để kiểm tra trong phpMyAdmin:</strong><br>
                  <code style="background: white; padding: 5px; display: block; margin-top: 5px;">
                    SELECT id_goi, tengoi, khuyenmai, khuyenmai_phantram, khuyenmai_tungay, khuyenmai_denngay, giagoi, giatreem, giatrenho<br>
                    FROM goidulich<br>
                    WHERE id_goi = <?= $tour['id_goi'] ?? 'YOUR_TOUR_ID' ?>;
                  </code>
                </div>
              </div>
            <?php endif; ?>
            
            <div class="price-row">
              <span class="price-label"><i class="fas fa-user"></i> Người lớn</span>
              <span class="price-value">
                <?php 
                $giaNguoiLon = (float)($tour['giagoi'] ?? 0);
                
                // Tính giá sau giảm nếu có khuyến mãi
                if ($coKhuyenMai && $phantram > 0 && $giaNguoiLon > 0) {
                  $giaSauGiam = round($giaNguoiLon * (100 - $phantram) / 100);
                  
                  // Luôn hiển thị giá giảm nếu có khuyến mãi
                  if ($giaSauGiam < $giaNguoiLon) {
                    if ($khuyenMaiHetHan): 
                      // Khi hết hạn: giá gốc trước, giá sau giảm bị gạch sau
                ?>
                  <span class="price-original" style="color: #1f2937; font-size: 16px; font-weight: 700; text-decoration: none;"><?= number_format($giaNguoiLon, 0, ',', '.') ?> đ</span>
                  <span class="price-sale" style="color: #9ca3af; text-decoration: line-through; font-size: 14px; margin-left: 8px;"><?= number_format($giaSauGiam, 0, ',', '.') ?> đ</span>
                  <span style="background: #fee2e2; color: #991b1b; padding: 2px 6px; border-radius: 4px; font-size: 10px; margin-left: 8px; font-weight: 600;">
                    <i class="fas fa-exclamation-circle"></i> Hết hạn
                  </span>
                <?php else: 
                      // Khi còn hiệu lực: chỉ hiển thị giá sau giảm nổi bật
                ?>
                  <span class="price-sale" style="color: #ef4444; font-size: 16px; font-weight: 700;"><?= number_format($giaSauGiam, 0, ',', '.') ?> đ</span>
                  <span style="background: #ef4444; color: white; padding: 3px 8px; border-radius: 4px; font-size: 11px; margin-left: 8px; font-weight: 700;">-<?= number_format($phantram, 0) ?>%</span>
                <?php endif; ?>
                <?php 
                  } else {
                    // Nếu giá sau giảm >= giá gốc (không hợp lý), vẫn hiển thị giá gốc
                    echo number_format($giaNguoiLon, 0, ',', '.') . ' đ';
                  }
                } else {
                  // Không có khuyến mãi hoặc phần trăm = 0
                  echo number_format($giaNguoiLon, 0, ',', '.') . ' đ';
                }
                ?>
              </span>
            </div>
            
            <div class="price-row">
              <span class="price-label"><i class="fas fa-child"></i> Trẻ em (6-11 tuổi)</span>
              <span class="price-value">
                <?php 
                $giaTreEm = (float)($tour['giatreem'] ?? 0);
                // Tính giá sau giảm nếu có khuyến mãi
                if ($coKhuyenMai && $phantram > 0 && $giaTreEm > 0) {
                  $giaSauGiam = round($giaTreEm * (100 - $phantram) / 100);
                  if ($giaSauGiam < $giaTreEm) {
                    if ($khuyenMaiHetHan): 
                      // Khi hết hạn: giá gốc trước, giá sau giảm bị gạch sau
                ?>
                  <span class="price-original" style="color: #1f2937; font-size: 16px; font-weight: 700; text-decoration: none;"><?= number_format($giaTreEm, 0, ',', '.') ?> đ</span>
                  <span class="price-sale" style="color: #9ca3af; text-decoration: line-through; font-size: 14px; margin-left: 8px;"><?= number_format($giaSauGiam, 0, ',', '.') ?> đ</span>
                  <span style="background: #fee2e2; color: #991b1b; padding: 2px 6px; border-radius: 4px; font-size: 10px; margin-left: 8px; font-weight: 600;">
                    <i class="fas fa-exclamation-circle"></i> Hết hạn
                  </span>
                <?php else: 
                      // Khi còn hiệu lực: chỉ hiển thị giá sau giảm nổi bật
                ?>
                  <span class="price-sale" style="color: #ef4444; font-size: 16px; font-weight: 700;"><?= number_format($giaSauGiam, 0, ',', '.') ?> đ</span>
                  <span style="background: #ef4444; color: white; padding: 3px 8px; border-radius: 4px; font-size: 11px; margin-left: 8px; font-weight: 700;">-<?= number_format($phantram, 0) ?>%</span>
                <?php endif; ?>
                <?php 
                    } else {
                      echo number_format($giaTreEm, 0, ',', '.') . ' đ';
                    }
                } else {
                  echo number_format($giaTreEm, 0, ',', '.') . ' đ';
                }
                ?>
              </span>
            </div>
            
            <div class="price-row">
              <span class="price-label"><i class="fas fa-baby"></i> Trẻ nhỏ (2-5 tuổi)</span>
              <span class="price-value">
                <?php 
                $giaTreNho = (float)($tour['giatrenho'] ?? 0);
                // Tính giá sau giảm nếu có khuyến mãi
                if ($coKhuyenMai && $phantram > 0 && $giaTreNho > 0) {
                  $giaSauGiam = round($giaTreNho * (100 - $phantram) / 100);
                  if ($giaSauGiam < $giaTreNho) {
                    if ($khuyenMaiHetHan): 
                      // Khi hết hạn: giá gốc trước, giá sau giảm bị gạch sau
                ?>
                  <span class="price-original" style="color: #1f2937; font-size: 16px; font-weight: 700; text-decoration: none;"><?= number_format($giaTreNho, 0, ',', '.') ?> đ</span>
                  <span class="price-sale" style="color: #9ca3af; text-decoration: line-through; font-size: 14px; margin-left: 8px;"><?= number_format($giaSauGiam, 0, ',', '.') ?> đ</span>
                  <span style="background: #fee2e2; color: #991b1b; padding: 2px 6px; border-radius: 4px; font-size: 10px; margin-left: 8px; font-weight: 600;">
                    <i class="fas fa-exclamation-circle"></i> Hết hạn
                  </span>
                <?php else: 
                      // Khi còn hiệu lực: chỉ hiển thị giá sau giảm nổi bật
                ?>
                  <span class="price-sale" style="color: #ef4444; font-size: 16px; font-weight: 700;"><?= number_format($giaSauGiam, 0, ',', '.') ?> đ</span>
                  <span style="background: #ef4444; color: white; padding: 3px 8px; border-radius: 4px; font-size: 11px; margin-left: 8px; font-weight: 700;">-<?= number_format($phantram, 0) ?>%</span>
                <?php endif; ?>
                <?php 
                    } else {
                      echo number_format($giaTreNho, 0, ',', '.') . ' đ';
                    }
                } else {
                  echo number_format($giaTreNho, 0, ',', '.') . ' đ';
                }
                ?>
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

  <!-- Itinerary -->
  <?php if (!empty($tour['chuongtrinh'])): ?>
  <div class="card">
    <div class="card-header">
      <div class="card-title">
        <i class="fas fa-route"></i> Lịch trình tour
      </div>
    </div>
    <?php
    // Parse lịch trình theo ngày - tách thành các ngày riêng biệt
    $chuongtrinh = html_entity_decode($tour['chuongtrinh'], ENT_QUOTES, 'UTF-8');
    $days = [];
    
    if (!empty($chuongtrinh)) {
      // Tìm tất cả các vị trí có "NGÀY X" (không phân biệt format)
      preg_match_all('/(?:NGÀY|Day|Ngày)\s*(\d+)(?:\s*:\s*([^<\n]+))?/i', $chuongtrinh, $matches, PREG_OFFSET_CAPTURE);
      
      if (!empty($matches[0])) {
        $markers = [];
        
        // Lấy tất cả các marker
        for ($i = 0; $i < count($matches[0]); $i++) {
          $dayNum = (int)$matches[1][$i][0];
          $pos = $matches[0][$i][1];
          $fullMatch = $matches[0][$i][0];
          $title = isset($matches[2][$i]) ? trim(strip_tags($matches[2][$i][0])) : '';
          
          // Tìm vị trí kết thúc của tag HTML chứa marker (nếu có)
          $afterText = substr($chuongtrinh, $pos, 500);
          $endPos = $pos + strlen($fullMatch);
          
          // Tìm tag đóng sau marker
          if (preg_match('/<\/[^>]+>/', $afterText, $closeTag, PREG_OFFSET_CAPTURE)) {
            $tagEnd = $pos + $closeTag[0][1] + strlen($closeTag[0][0]);
            if ($tagEnd > $endPos) {
              $endPos = $tagEnd;
            }
          }
          
          // Chỉ giữ marker đầu tiên của mỗi ngày
          if (!isset($markers[$dayNum]) || $markers[$dayNum]['pos'] > $pos) {
            $markers[$dayNum] = [
              'day' => $dayNum,
              'pos' => $pos,
              'end_pos' => $endPos,
              'title' => $title
            ];
          }
        }
        
        // Sắp xếp theo vị trí
        uasort($markers, function($a, $b) {
          return $a['pos'] - $b['pos'];
        });
        
        // Chia nội dung theo các marker
        $markerList = array_values($markers);
        
        for ($i = 0; $i < count($markerList); $i++) {
          $marker = $markerList[$i];
          $dayNum = $marker['day'];
          
          // Vị trí bắt đầu nội dung (sau marker)
          $contentStart = $marker['end_pos'];
          
          // Vị trí kết thúc (trước marker tiếp theo hoặc cuối chuỗi)
          $contentEnd = ($i < count($markerList) - 1) 
            ? $markerList[$i + 1]['pos'] 
            : strlen($chuongtrinh);
          
          // Lấy nội dung của ngày này
          $dayContent = substr($chuongtrinh, $contentStart, $contentEnd - $contentStart);
          $dayContent = trim($dayContent);
          
          // Loại bỏ header "NGÀY X" khỏi content nếu còn sót
          $dayContent = preg_replace('/<[^>]*>\s*(?:NGÀY|Day|Ngày)\s*\d+[^<]*\s*<\/[^>]*>/is', '', $dayContent);
          $dayContent = trim($dayContent);
          
          // Tạo title
          $dayTitle = 'Ngày ' . $dayNum;
          if (!empty($marker['title'])) {
            $dayTitle .= ': ' . htmlspecialchars($marker['title']);
          }
          
          // Thêm vào mảng days
          $days[$dayNum] = [
            'title' => $dayTitle,
            'content' => $dayContent
          ];
        }
      }
    }
    
    // Nếu không tìm thấy marker, hiển thị toàn bộ trong 1 ngày
    if (empty($days)) {
      $days[1] = [
        'title' => 'Ngày 1',
        'content' => $chuongtrinh
      ];
    }
    
    // Sắp xếp theo số ngày
    ksort($days);
    
    // Hiển thị các ngày - mỗi ngày một card riêng
    ?>
    <div class="content-scrollable">
      <?php foreach ($days as $dayNum => $day): ?>
        <div class="itinerary-day-card">
          <div class="day-header">
            <div class="day-number">
              <i class="fas fa-calendar-day"></i>
              <?= $day['title'] ?>
            </div>
          </div>
          <div class="day-content">
            <?= $day['content'] ?>
          </div>
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
