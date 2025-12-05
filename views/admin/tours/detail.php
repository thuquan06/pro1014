<?php
// Check for active promotion
$coKhuyenMai = false;
$phantram = 0;
if ($tour['khuyenmai'] == 1 && !empty($tour['khuyenmai_phantram'])) {
    $tungay = $tour['khuyenmai_tungay'] ?? null;
    $denngay = $tour['khuyenmai_denngay'] ?? null;
    $today = date('Y-m-d');
    
    if (($tungay === null || $today >= $tungay) && ($denngay === null || $today <= $denngay)) {
        $coKhuyenMai = true;
        $phantram = (float)$tour['khuyenmai_phantram'];
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
  grid-template-columns: 1fr 1fr 1fr;
  gap: 20px;
}

.info-group {
  margin-bottom: 12px;
}

.info-label {
  font-size: 13px;
  font-weight: 600;
  color: #6b7280;
  margin-bottom: 4px;
}

.info-value {
  font-size: 15px;
  color: #1f2937;
  font-weight: 500;
}

.price-section {
  background: #f9fafb;
  padding: 16px;
  border-radius: 8px;
  border-left: 3px solid #3b82f6;
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
          <div>
            <div class="info-group">
              <div class="info-label">Mã tour</div>
              <div class="info-value"><?= htmlspecialchars($tour['mato'] ?? 'N/A') ?></div>
            </div>
            <div class="info-group">
              <div class="info-label">Nơi xuất phát</div>
              <div class="info-value"><?= htmlspecialchars($tour['noixuatphat'] ?? 'N/A') ?></div>
            </div>
            <div class="info-group">
              <div class="info-label">Số ngày</div>
              <div class="info-value"><?= htmlspecialchars($tour['songay'] ?? 'N/A') ?></div>
            </div>
            <div class="info-group">
              <div class="info-label">Vị trí</div>
              <div class="info-value"><?= htmlspecialchars($tour['vitri'] ?? 'N/A') ?></div>
            </div>
            <div class="info-group">
              <div class="info-label">Điểm đến</div>
              <div class="info-value">
                <?php
                $diemDen = [];
                if (!empty($tour['ten_tinh'])) {
                  $diemDen[] = $tour['ten_tinh'];
                }
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
              <div class="info-label">Ngày đăng</div>
              <div class="info-value">
                <?= !empty($tour['ngaydang']) ? date('d/m/Y', strtotime($tour['ngaydang'])) : 'N/A' ?>
              </div>
            </div>
            <?php if ($coKhuyenMai): ?>
            <div class="info-group">
              <div class="info-label">Khuyến mãi</div>
              <div class="info-value" style="color: #ef4444; font-weight: 700;">
                <i class="fas fa-tag"></i> Giảm <?= $phantram ?>%
                <?php if (!empty($tour['khuyenmai_denngay'])): ?>
                  <br><small style="font-weight: 400; color: #6b7280;">
                    Đến: <?= date('d/m/Y', strtotime($tour['khuyenmai_denngay'])) ?>
                  </small>
                <?php endif; ?>
              </div>
            </div>
            <?php endif; ?>
          </div>
          
          <div class="price-section">
            <div class="price-row">
              <span class="price-label"><i class="fas fa-user"></i> Người lớn</span>
              <span class="price-value">
                <?php 
                $giaNguoiLon = $tour['giagoi'] ?? 0;
                if ($coKhuyenMai):
                  $giaSauGiam = $giaNguoiLon * (100 - $phantram) / 100;
                ?>
                  <span class="price-sale"><?= number_format($giaSauGiam, 0, ',', '.') ?> đ</span>
                  <span class="price-original"><?= number_format($giaNguoiLon, 0, ',', '.') ?> đ</span>
                <?php else: ?>
                  <?= number_format($giaNguoiLon, 0, ',', '.') ?> đ
                <?php endif; ?>
              </span>
            </div>
            
            <div class="price-row">
              <span class="price-label"><i class="fas fa-child"></i> Trẻ em (6-11 tuổi)</span>
              <span class="price-value">
                <?php 
                $giaTreEm = $tour['giatreem'] ?? 0;
                if ($coKhuyenMai):
                  $giaSauGiam = $giaTreEm * (100 - $phantram) / 100;
                ?>
                  <span class="price-sale"><?= number_format($giaSauGiam, 0, ',', '.') ?> đ</span>
                  <span class="price-original"><?= number_format($giaTreEm, 0, ',', '.') ?> đ</span>
                <?php else: ?>
                  <?= number_format($giaTreEm, 0, ',', '.') ?> đ
                <?php endif; ?>
              </span>
            </div>
            
            <div class="price-row">
              <span class="price-label"><i class="fas fa-baby"></i> Trẻ nhỏ (2-5 tuổi)</span>
              <span class="price-value">
                <?php 
                $giaTreNho = $tour['giatrenho'] ?? 0;
                if ($coKhuyenMai):
                  $giaSauGiam = $giaTreNho * (100 - $phantram) / 100;
                ?>
                  <span class="price-sale"><?= number_format($giaSauGiam, 0, ',', '.') ?> đ</span>
                  <span class="price-original"><?= number_format($giaTreNho, 0, ',', '.') ?> đ</span>
                <?php else: ?>
                  <?= number_format($giaTreNho, 0, ',', '.') ?> đ
                <?php endif; ?>
              </span>
            </div>
          </div>
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

  <!-- Pretrip Checklist -->
  <?php if (!empty($departurePlans)): ?>
  <div class="card">
    <div class="card-header">
      <div class="card-title">
        <i class="fas fa-clipboard-check"></i> Checklist Trước Ngày Khởi Hành
      </div>
      <?php if (!empty($departurePlans[0])): ?>
        <?php if (!empty($checklist)): ?>
          <a href="<?= BASE_URL ?>?act=admin-pretrip-checklist-items&checklist_id=<?= $checklist['id'] ?>" class="btn btn-sm btn-primary">
            <i class="fas fa-edit"></i> Quản lý Checklist
          </a>
        <?php else: ?>
          <a href="<?= BASE_URL ?>?act=admin-pretrip-checklist-create&departure_plan_id=<?= $departurePlans[0]['id'] ?>" class="btn btn-sm btn-primary">
            <i class="fas fa-plus"></i> Tạo Checklist
          </a>
        <?php endif; ?>
      <?php endif; ?>
    </div>
    
    <?php if (!empty($checklist)): ?>
      <?php
      $items = $checklistItems ?? [];
      $completionPercentage = $completionPercentage ?? 0;
      $allCompleted = ($completionPercentage == 100);
      ?>
      
      <div style="margin-bottom: 16px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
          <span class="info-label">Tiến độ hoàn thành</span>
          <span class="info-value" style="font-weight: 700; color: <?= $allCompleted ? '#10b981' : '#3b82f6' ?>;">
            <?= $completionPercentage ?>%
          </span>
        </div>
        <div style="background: #e5e7eb; height: 8px; border-radius: 4px; overflow: hidden;">
          <div style="background: <?= $allCompleted ? '#10b981' : '#3b82f6' ?>; height: 100%; width: <?= $completionPercentage ?>%; transition: width 0.3s;"></div>
        </div>
      </div>

      <?php if ($allCompleted && $checklist['trang_thai_ready'] == 0): ?>
        <div style="background: #d1fae5; border-left: 4px solid #10b981; padding: 12px; border-radius: 6px; margin-bottom: 16px;">
          <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
              <strong style="color: #065f46;"><i class="fas fa-check-circle"></i> Checklist đã hoàn thành!</strong>
              <p style="margin: 4px 0 0 0; color: #047857; font-size: 13px;">Tất cả các mục đã được tick. Bạn có thể duyệt trạng thái Ready.</p>
            </div>
            <a href="<?= BASE_URL ?>?act=admin-pretrip-checklist-approve-ready&checklist_id=<?= $checklist['id'] ?>" 
               class="btn btn-sm" 
               style="background: #10b981; color: white;"
               onclick="return confirm('Xác nhận duyệt trạng thái Ready cho tour này?')">
              <i class="fas fa-check-double"></i> Duyệt Ready
            </a>
          </div>
        </div>
      <?php elseif ($checklist['trang_thai_ready'] == 1): ?>
        <div style="background: #dbeafe; border-left: 4px solid #3b82f6; padding: 12px; border-radius: 6px; margin-bottom: 16px;">
          <strong style="color: #1e40af;"><i class="fas fa-check-double"></i> Tour đã được duyệt Ready</strong>
          <?php if ($checklist['ngay_duyet_ready']): ?>
            <p style="margin: 4px 0 0 0; color: #1e3a8a; font-size: 13px;">
              Ngày duyệt: <?= date('d/m/Y H:i', strtotime($checklist['ngay_duyet_ready'])) ?>
            </p>
          <?php endif; ?>
        </div>
      <?php endif; ?>

      <?php if (!empty($items)): ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 12px;">
          <?php foreach ($items as $item): ?>
            <div style="padding: 12px; background: <?= $item['da_hoan_thanh'] ? '#d1fae5' : '#f9fafb' ?>; border-radius: 6px; border-left: 3px solid <?= $item['da_hoan_thanh'] ? '#10b981' : '#e5e7eb' ?>;">
              <div style="display: flex; align-items: start; gap: 8px;">
                <div style="flex: 1;">
                  <div style="font-weight: 600; color: #1f2937; margin-bottom: 4px;">
                    <?php if ($item['da_hoan_thanh']): ?>
                      <i class="fas fa-check-circle" style="color: #10b981;"></i>
                    <?php else: ?>
                      <i class="far fa-circle" style="color: #9ca3af;"></i>
                    <?php endif; ?>
                    <?= htmlspecialchars($item['ten_muc']) ?>
                  </div>
                  <?php if (!empty($item['mo_ta'])): ?>
                    <div style="font-size: 12px; color: #6b7280; margin-top: 4px;">
                      <?= htmlspecialchars($item['mo_ta']) ?>
                    </div>
                  <?php endif; ?>
                  <?php if ($item['da_hoan_thanh'] && $item['nguoi_tick']): ?>
                    <div style="font-size: 11px; color: #9ca3af; margin-top: 4px;">
                      <i class="fas fa-user"></i> 
                      <?= htmlspecialchars($item['ten_hdv'] ?? $item['ten_admin'] ?? 'N/A') ?>
                      <?php if ($item['ngay_tick']): ?>
                        - <?= date('d/m/Y H:i', strtotime($item['ngay_tick'])) ?>
                      <?php endif; ?>
                    </div>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <div class="empty-state">
          <i class="fas fa-clipboard-list" style="font-size: 48px; color: #d1d5db; margin-bottom: 16px;"></i>
          <p>Chưa có mục checklist nào. Vui lòng tạo mới.</p>
        </div>
      <?php endif; ?>
    <?php else: ?>
      <div class="empty-state">
        <i class="fas fa-clipboard-list" style="font-size: 48px; color: #d1d5db; margin-bottom: 16px;"></i>
        <p>Chưa có checklist cho tour này.</p>
        <?php if (!empty($departurePlans[0])): ?>
          <a href="<?= BASE_URL ?>?act=admin-pretrip-checklist-create&departure_plan_id=<?= $departurePlans[0]['id'] ?>" class="btn btn-primary" style="margin-top: 16px;">
            <i class="fas fa-plus"></i> Tạo Checklist
          </a>
        <?php endif; ?>
      </div>
    <?php endif; ?>
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
