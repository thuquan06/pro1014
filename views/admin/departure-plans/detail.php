<?php
/**
 * Departure Plan Detail View
 * Hiển thị chi tiết lịch khởi hành
 */

function formatDateTime($date, $time = null) {
    if (!$date) return 'N/A';
    $dateStr = date('d/m/Y', strtotime($date));
    $timeStr = $time ? date('H:i', strtotime($time)) : '';
    return $dateStr . ($timeStr ? ' ' . $timeStr : '');
}

function getTrangThaiText($status) {
    $statuses = [
        0 => ['text' => 'Đóng/Khóa', 'class' => 'danger', 'icon' => 'ban'],
        1 => ['text' => 'Đang mở', 'class' => 'success', 'icon' => 'check-circle'],
        2 => ['text' => 'Hết chỗ', 'class' => 'warning', 'icon' => 'exclamation-triangle'],
        3 => ['text' => 'Gần đầy', 'class' => 'info', 'icon' => 'info-circle']
    ];
    $statusInfo = $statuses[$status] ?? $statuses[1];
    return '<span class="status-badge ' . $statusInfo['class'] . '"><i class="fas fa-' . $statusInfo['icon'] . '"></i> ' . $statusInfo['text'] . '</span>';
}
?>

<style>
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
  font-size: 28px;
  font-weight: 700;
  color: #1f2937;
  margin: 0;
}

.detail-actions {
  display: flex;
  gap: 12px;
  align-items: center;
}

.card {
  background: white;
  border-radius: 12px;
  box-shadow: 0 1px 3px rgba(0,0,0,0.1);
  margin-bottom: 24px;
  overflow: hidden;
}

.card-header {
  padding: 20px 24px;
  border-bottom: 1px solid #e5e7eb;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.card-title {
  font-size: 18px;
  font-weight: 700;
  color: #1f2937;
  display: flex;
  align-items: center;
  gap: 8px;
}

.card-body {
  padding: 24px;
}

.grid-2 {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 24px;
}

.info-section {
  border-right: 2px solid #3b82f6;
  padding-right: 24px;
}

.price-section {
  padding-left: 24px;
}

.info-group {
  margin-bottom: 20px;
}

.info-label {
  font-size: 12px;
  font-weight: 600;
  color: #6b7280;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  margin-bottom: 6px;
}

.info-value {
  font-size: 16px;
  color: #1f2937;
  font-weight: 500;
}

.price-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 12px 0;
  border-bottom: 1px solid #f3f4f6;
}

.price-row:last-child {
  border-bottom: none;
}

.price-label {
  font-size: 14px;
  color: #6b7280;
  display: flex;
  align-items: center;
  gap: 8px;
}

.price-value {
  font-size: 16px;
  color: #1f2937;
  font-weight: 600;
}

.status-badge {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 6px 12px;
  border-radius: 6px;
  font-size: 13px;
  font-weight: 600;
}

.status-badge.success {
  background: #d1fae5;
  color: #065f46;
}

.status-badge.danger {
  background: #fee2e2;
  color: #991b1b;
}

.status-badge.warning {
  background: #fef3c7;
  color: #92400e;
}

.status-badge.info {
  background: #dbeafe;
  color: #1e40af;
}

.btn {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 10px 20px;
  border-radius: 8px;
  font-weight: 600;
  text-decoration: none;
  transition: all 0.2s;
  border: none;
  cursor: pointer;
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

.checklist-section {
  margin-top: 24px;
}

.progress-bar {
  width: 100%;
  height: 24px;
  background: #e5e7eb;
  border-radius: 12px;
  overflow: hidden;
  margin-bottom: 16px;
}

.progress-fill {
  height: 100%;
  background: linear-gradient(90deg, #3b82f6, #2563eb);
  transition: width 0.3s;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 12px;
  font-weight: 600;
}

.checklist-items {
  list-style: none;
  padding: 0;
  margin: 0;
}

.checklist-item {
  padding: 12px;
  border-bottom: 1px solid #f3f4f6;
  display: flex;
  align-items: center;
  gap: 12px;
}

.checklist-item:last-child {
  border-bottom: none;
}

.checklist-item i {
  color: #10b981;
  font-size: 18px;
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

.content-scrollable {
  max-height: 500px;
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
  background: #cbd5e1;
  border-radius: 10px;
}

.content-scrollable::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}
</style>

<div class="detail-container">
  <!-- Header -->
  <div class="detail-header">
    <h1 class="detail-title">
      <i class="fas fa-calendar-alt" style="color: #3b82f6;"></i>
      Chi tiết lịch trình
    </h1>
    <div class="detail-actions">
      <a href="<?= BASE_URL ?>?act=admin-attendance&id_lich_khoi_hanh=<?= $departurePlan['id'] ?>" class="btn btn-primary" style="background: #10b981;">
        <i class="fas fa-clipboard-check"></i> Điểm danh
      </a>
      <a href="<?= BASE_URL ?>?act=admin-departure-plan-edit&id=<?= $departurePlan['id'] ?>" class="btn btn-primary">
        <i class="fas fa-edit"></i> Sửa
      </a>
      <a href="<?= BASE_URL ?>?act=admin-departure-plans<?= $departurePlan['id_tour'] ? '&tour_id=' . $departurePlan['id_tour'] : '' ?>" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Quay lại
      </a>
    </div>
  </div>

  <!-- Basic Info & Price -->
  <div class="card">
    <div class="card-header">
      <div class="card-title">
        <i class="fas fa-info-circle"></i> Thông tin & Giá
      </div>
    </div>
    
    <div class="card-body">
      <div class="grid-2">
        <div class="info-section">
          <h3 style="margin: 0 0 16px 0; font-size: 18px; font-weight: 700; color: #1f2937; padding-bottom: 12px; border-bottom: 2px solid #e5e7eb;">
            <i class="fas fa-info-circle" style="color: #3b82f6; margin-right: 8px;"></i>Thông tin
          </h3>
          
          <?php if ($tour): ?>
          <div class="info-group">
            <div class="info-label">Tour</div>
            <div class="info-value">
              <strong><?= htmlspecialchars($tour['tengoi'] ?? 'N/A') ?></strong>
              <?php if ($tour['id_goi']): ?>
                <br><small style="color: #6b7280;">ID: <?= $tour['id_goi'] ?></small>
              <?php endif; ?>
            </div>
          </div>
          <?php endif; ?>
          
          <div class="info-group">
            <div class="info-label">Ngày khởi hành</div>
            <div class="info-value">
              <i class="fas fa-calendar"></i> <?= formatDateTime($departurePlan['ngay_khoi_hanh'], $departurePlan['gio_khoi_hanh']) ?>
            </div>
          </div>
          
          <?php if ($departurePlan['ngay_ket_thuc']): ?>
          <div class="info-group">
            <div class="info-label">Ngày kết thúc</div>
            <div class="info-value">
              <i class="fas fa-calendar-check"></i> <?= formatDateTime($departurePlan['ngay_ket_thuc']) ?>
            </div>
          </div>
          <?php endif; ?>
          
          <?php if ($departurePlan['gio_tap_trung']): ?>
          <div class="info-group">
            <div class="info-label">Giờ tập trung</div>
            <div class="info-value">
              <i class="fas fa-clock"></i> <?= date('H:i', strtotime($departurePlan['gio_tap_trung'])) ?>
            </div>
          </div>
          <?php endif; ?>
          
          <div class="info-group">
            <div class="info-label">Điểm tập trung</div>
            <div class="info-value">
              <i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($departurePlan['diem_tap_trung'] ?? 'N/A') ?>
            </div>
          </div>
          
          <?php if ($departurePlan['phuong_tien']): ?>
          <div class="info-group">
            <div class="info-label">Phương tiện</div>
            <div class="info-value">
              <i class="fas fa-bus"></i> <?= htmlspecialchars($departurePlan['phuong_tien']) ?>
            </div>
          </div>
          <?php endif; ?>
          
          <div class="info-group">
            <div class="info-label">Số chỗ</div>
            <div class="info-value">
              Tối đa: <strong><?= $departurePlan['so_cho'] ?? 'N/A' ?></strong>
              <?php if (isset($departurePlan['so_cho_da_dat'])): ?>
                <br>Đã đặt: <strong><?= $departurePlan['so_cho_da_dat'] ?></strong>
              <?php endif; ?>
              <?php if (isset($departurePlan['so_cho_con_lai'])): ?>
                <br>Còn lại: <strong style="color: #10b981;"><?= $departurePlan['so_cho_con_lai'] ?></strong>
              <?php endif; ?>
            </div>
          </div>
          
          <div class="info-group">
            <div class="info-label">Trạng thái</div>
            <div class="info-value">
              <?= getTrangThaiText($departurePlan['trang_thai'] ?? 1) ?>
            </div>
          </div>
          
          <?php if ($departurePlan['ghi_chu']): ?>
          <div class="info-group">
            <div class="info-label">Ghi chú nội bộ</div>
            <div class="info-value" style="white-space: pre-wrap;"><?= htmlspecialchars($departurePlan['ghi_chu']) ?></div>
          </div>
          <?php endif; ?>
          
          <?php if ($departurePlan['ghi_chu_van_hanh']): ?>
          <div class="info-group">
            <div class="info-label">Ghi chú vận hành</div>
            <div class="info-value" style="white-space: pre-wrap;"><?= htmlspecialchars($departurePlan['ghi_chu_van_hanh']) ?></div>
          </div>
          <?php endif; ?>
        </div>
        
        <div class="price-section">
          <h3 style="margin: 0 0 16px 0; font-size: 18px; font-weight: 700; color: #1f2937; padding-bottom: 12px; border-bottom: 2px solid #e5e7eb;">
            <i class="fas fa-money-bill-wave" style="color: #3b82f6; margin-right: 8px;"></i>Giá
          </h3>
          
          <?php if ($departurePlan['gia_nguoi_lon']): ?>
          <div class="price-row">
            <span class="price-label"><i class="fas fa-user"></i> Người lớn</span>
            <span class="price-value"><?= number_format($departurePlan['gia_nguoi_lon'], 0, ',', '.') ?> đ</span>
          </div>
          <?php endif; ?>
          
          <?php if ($departurePlan['gia_tre_em']): ?>
          <div class="price-row">
            <span class="price-label"><i class="fas fa-child"></i> Trẻ em</span>
            <span class="price-value"><?= number_format($departurePlan['gia_tre_em'], 0, ',', '.') ?> đ</span>
          </div>
          <?php endif; ?>
          
          <?php if ($departurePlan['gia_tre_nho']): ?>
          <div class="price-row">
            <span class="price-label"><i class="fas fa-baby"></i> Trẻ nhỏ</span>
            <span class="price-value"><?= number_format($departurePlan['gia_tre_nho'], 0, ',', '.') ?> đ</span>
          </div>
          <?php endif; ?>
          
          <?php if ($departurePlan['uu_dai_giam_gia']): ?>
          <div class="price-row">
            <span class="price-label"><i class="fas fa-tag"></i> Ưu đãi giảm giá</span>
            <span class="price-value" style="color: #ef4444; font-weight: 700;">-<?= number_format($departurePlan['uu_dai_giam_gia'], 0) ?>%</span>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <!-- Itinerary Section -->
  <?php if (!empty($departurePlan['chuongtrinh'])): ?>
  <div class="card">
    <div class="card-header">
      <div class="card-title">
        <i class="fas fa-route"></i> Lịch trình tour
      </div>
    </div>
    <div class="card-body">
      <?php
      // Parse lịch trình theo ngày
      $chuongtrinh = html_entity_decode($departurePlan['chuongtrinh'], ENT_QUOTES, 'UTF-8');
      $days = [];
      
      if (!empty($chuongtrinh)) {
        // Tìm tất cả các vị trí có "NGÀY X"
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
  </div>
  <?php endif; ?>

  <!-- Phân công Hướng dẫn viên -->
  <?php if (!empty($assignments)): ?>
  <div class="card">
    <div class="card-header">
      <div class="card-title">
        <i class="fas fa-user-tie"></i> Phân công Hướng dẫn viên
      </div>
    </div>
    <div class="card-body">
      <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
          <thead>
            <tr style="background: #f9fafb; border-bottom: 2px solid #e5e7eb;">
              <th style="padding: 12px; text-align: left; font-weight: 600; color: #6b7280;">STT</th>
              <th style="padding: 12px; text-align: left; font-weight: 600; color: #6b7280;">Họ tên</th>
              <th style="padding: 12px; text-align: left; font-weight: 600; color: #6b7280;">Số điện thoại</th>
              <th style="padding: 12px; text-align: left; font-weight: 600; color: #6b7280;">Vai trò</th>
              <th style="padding: 12px; text-align: left; font-weight: 600; color: #6b7280;">Lương</th>
              <th style="padding: 12px; text-align: left; font-weight: 600; color: #6b7280;">Ghi chú</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($assignments as $index => $assignment): ?>
            <tr style="border-bottom: 1px solid #e5e7eb;">
              <td style="padding: 12px;"><?= $index + 1 ?></td>
              <td style="padding: 12px; font-weight: 600;"><?= htmlspecialchars($assignment['ten_hdv'] ?? 'N/A', ENT_QUOTES, 'UTF-8') ?></td>
              <td style="padding: 12px;"><?= htmlspecialchars($assignment['sdt_hdv'] ?? 'N/A', ENT_QUOTES, 'UTF-8') ?></td>
              <td style="padding: 12px;">
                <?php
                $vaiTro = $assignment['vai_tro'] ?? 'HDV chính';
                $badgeClass = $vaiTro == 'HDV chính' ? 'success' : ($vaiTro == 'HDV phụ' ? 'info' : 'secondary');
                ?>
                <span class="status-badge <?= $badgeClass ?>"><?= htmlspecialchars($vaiTro, ENT_QUOTES, 'UTF-8') ?></span>
              </td>
              <td style="padding: 12px;">
                <?php if (!empty($assignment['luong'])): ?>
                  <?= number_format($assignment['luong'], 0, ',', '.') ?> đ
                <?php else: ?>
                  -
                <?php endif; ?>
              </td>
              <td style="padding: 12px;"><?= htmlspecialchars($assignment['ghi_chu'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <?php endif; ?>

  <!-- Checklist Section -->
  <?php if ($checklist): ?>
  <div class="card">
    <div class="card-header">
      <div class="card-title">
        <i class="fas fa-clipboard-check"></i> Pretrip Checklist
      </div>
      <a href="<?= BASE_URL ?>?act=admin-pretrip-checklist-items&checklist_id=<?= $checklist['id'] ?>" class="btn btn-primary" style="padding: 8px 16px; font-size: 14px;">
        <i class="fas fa-cog"></i> Quản lý
      </a>
    </div>
    <div class="card-body checklist-section">
      <div class="progress-bar">
        <div class="progress-fill" style="width: <?= $completionPercentage ?>%;">
          <?= $completionPercentage ?>%
        </div>
      </div>
      
      <?php if (!empty($checklistItems)): ?>
      <ul class="checklist-items">
        <?php foreach ($checklistItems as $item): ?>
        <li class="checklist-item">
          <i class="fas fa-<?= ($item['checked'] ?? false) ? 'check-circle' : 'circle' ?>"></i>
          <span style="flex: 1;">
            <strong><?= htmlspecialchars($item['ten_muc'] ?? '') ?></strong>
            <?php if (!empty($item['mo_ta'])): ?>
              <br><small style="color: #6b7280;"><?= htmlspecialchars($item['mo_ta']) ?></small>
            <?php endif; ?>
          </span>
          <?php if (!empty($item['nguoi_tick'])): ?>
            <small style="color: #6b7280;"><?= htmlspecialchars($item['nguoi_tick']) ?></small>
          <?php endif; ?>
        </li>
        <?php endforeach; ?>
      </ul>
      <?php else: ?>
      <p style="color: #6b7280; text-align: center; padding: 20px;">Chưa có mục checklist nào</p>
      <?php endif; ?>
    </div>
  </div>
  <?php else: ?>
  <div class="card">
    <div class="card-body" style="text-align: center; padding: 40px;">
      <i class="fas fa-clipboard" style="font-size: 48px; color: #9ca3af; margin-bottom: 16px;"></i>
      <p style="color: #6b7280; margin-bottom: 16px;">Chưa có checklist cho lịch khởi hành này</p>
      <a href="<?= BASE_URL ?>?act=admin-pretrip-checklist-create&departure_plan_id=<?= $departurePlan['id'] ?>" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tạo checklist
      </a>
    </div>
  </div>
  <?php endif; ?>

</div>

