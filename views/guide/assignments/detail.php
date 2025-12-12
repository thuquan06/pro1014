<?php
// views/guide/assignments/detail.php
?>

<style>
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
  margin: 8px 0;
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

<div class="card" style="margin-bottom: 24px;">
  <div class="card-header">
    <h3><i class="fas fa-info-circle"></i> Chi tiết phân công</h3>
    <div style="display: flex; gap: 12px; flex-wrap: wrap;">
      <span style="padding:6px 12px; border-radius:10px; font-weight:700; color:#065f46; background:#d1fae5; display:flex; align-items:center; gap:6px;">
        <i class="fas <?= !empty($assignment['da_nhan']) ? 'fa-check-circle' : 'fa-hourglass-half' ?>"></i>
        <?= !empty($assignment['da_nhan']) ? 'Đã nhận' : 'Chưa nhận' ?>
      </span>
      <?php if (empty($assignment['da_nhan'])): ?>
        <a href="?act=guide-assignment-confirm&id=<?= $assignment['id'] ?>" class="btn btn-sm" style="background: #4b5563; color: white;">
          <i class="fas fa-check"></i> Nhận tour
        </a>
      <?php endif; ?>
      <form method="post" action="?act=guide-assignment-status" style="display:flex; gap:8px; align-items:center; flex-wrap: wrap;">
        <input type="hidden" name="assignment_id" value="<?= $assignment['id'] ?>">
        <select name="trang_thai" style="padding:6px 10px; border:1px solid var(--border); border-radius:8px;">
          <option value="0" <?= isset($assignment['trang_thai']) && $assignment['trang_thai']==0 ? 'selected' : '' ?>>Ready</option>
          <option value="1" <?= isset($assignment['trang_thai']) && $assignment['trang_thai']==1 ? 'selected' : '' ?>>Đang diễn ra</option>
          <option value="2" <?= isset($assignment['trang_thai']) && $assignment['trang_thai']==2 ? 'selected' : '' ?>>Hoàn thành</option>
        </select>
        <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-save"></i> Lưu trạng thái</button>
      </form>
      <a href="?act=guide-attendance&assignment_id=<?= $assignment['id'] ?>" class="btn btn-sm" style="background: #8b5cf6; color: white;">
        <i class="fas fa-clipboard-check"></i> Điểm danh
      </a>
      <?php if (!empty($checklist)): ?>
        <a href="?act=guide-checklist&assignment_id=<?= $assignment['id'] ?>" class="btn btn-sm" style="background: #10b981; color: white;">
          <i class="fas fa-clipboard-check"></i> Checklist (<?= $completionPercentage ?>%)
        </a>
      <?php endif; ?>
      <a href="?act=guide-journal-create&assignment_id=<?= $assignment['id'] ?>" class="btn btn-sm btn-primary">
        <i class="fas fa-book"></i> Tạo nhật ký
      </a>
      <a href="?act=guide-incident-create&assignment_id=<?= $assignment['id'] ?>" class="btn btn-sm" style="background: var(--warning); color: white;">
        <i class="fas fa-exclamation-triangle"></i> Báo cáo sự cố
      </a>
      <a href="?act=guide-assignments" class="btn btn-sm" style="background: var(--bg-light); color: var(--text-dark);">
        <i class="fas fa-arrow-left"></i> Quay lại
      </a>
    </div>
  </div>
  <div class="card-body">
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px;">
      <div>
        <strong style="color: var(--text-light); font-size: 12px; text-transform: uppercase;">Tour</strong>
        <p style="font-size: 18px; font-weight: 700; margin-top: 4px; color: var(--primary);">
          <?= htmlspecialchars($assignment['ten_tour'] ?? 'N/A') ?>
        </p>
      </div>
      
      <div>
        <strong style="color: var(--text-light); font-size: 12px; text-transform: uppercase;">Vai trò</strong>
        <p style="font-size: 16px; font-weight: 600; margin-top: 4px;">
          <?= htmlspecialchars($assignment['vai_tro'] ?? 'HDV chính') ?>
        </p>
      </div>
      
      <div>
        <strong style="color: var(--text-light); font-size: 12px; text-transform: uppercase;">Ngày khởi hành</strong>
        <p style="font-size: 16px; font-weight: 600; margin-top: 4px;">
          <?php if ($assignment['ngay_khoi_hanh']): ?>
            <?= date('d/m/Y', strtotime($assignment['ngay_khoi_hanh'])) ?>
            <?php if ($assignment['gio_khoi_hanh']): ?>
              <br><small style="color: var(--text-light);"><?= htmlspecialchars($assignment['gio_khoi_hanh']) ?></small>
            <?php endif; ?>
          <?php else: ?>
            N/A
          <?php endif; ?>
        </p>
      </div>
      
      <div>
        <strong style="color: var(--text-light); font-size: 12px; text-transform: uppercase;">Điểm tập trung</strong>
        <p style="font-size: 16px; font-weight: 600; margin-top: 4px;">
          <?= htmlspecialchars($assignment['diem_tap_trung'] ?? 'Chưa cập nhật') ?>
        </p>
      </div>
      
      <div>
        <strong style="color: var(--text-light); font-size: 12px; text-transform: uppercase;">Thời gian làm việc</strong>
        <p style="font-size: 16px; font-weight: 600; margin-top: 4px;">
          <?php if ($assignment['ngay_bat_dau'] && $assignment['ngay_ket_thuc']): ?>
            Từ <strong><?= date('d/m/Y', strtotime($assignment['ngay_bat_dau'])) ?></strong><br>
            Đến <strong><?= date('d/m/Y', strtotime($assignment['ngay_ket_thuc'])) ?></strong>
            <?php
            $days = (strtotime($assignment['ngay_ket_thuc']) - strtotime($assignment['ngay_bat_dau'])) / (60 * 60 * 24);
            if ($days > 0) {
              echo '<br><small style="color: var(--text-light);">(' . ($days + 1) . ' ngày)</small>';
            }
            ?>
          <?php else: ?>
            Chưa cập nhật
          <?php endif; ?>
        </p>
      </div>
      
      <div>
        <strong style="color: var(--text-light); font-size: 12px; text-transform: uppercase;">Lương</strong>
        <p style="font-size: 18px; font-weight: 700; margin-top: 4px; color: var(--success);">
          <?php if (!empty($assignment['luong'])): ?>
            <?= number_format($assignment['luong'], 0, ',', '.') ?> đ
          <?php else: ?>
            Chưa cập nhật
          <?php endif; ?>
        </p>
      </div>
      
      <div>
        <strong style="color: var(--text-light); font-size: 12px; text-transform: uppercase;">Trạng thái</strong>
        <p style="margin-top: 4px;">
          <?php
            $st = $assignment['trang_thai_hien_thi'] ?? 'Chưa xác định';
            $map = [
              'Ready' => ['bg' => '#dbeafe', 'color' => '#1d4ed8', 'icon' => 'clock'],
              'Đang diễn ra' => ['bg' => '#d1fae5', 'color' => '#065f46', 'icon' => 'play'],
              'Hoàn thành' => ['bg' => '#e5e7eb', 'color' => '#374151', 'icon' => 'check'],
              'Chưa xác định' => ['bg' => '#fee2e2', 'color' => '#991b1b', 'icon' => 'question']
            ];
            $cfg = $map[$st] ?? $map['Chưa xác định'];
          ?>
          <span style="background: <?= $cfg['bg'] ?>; color: <?= $cfg['color'] ?>; padding: 6px 16px; border-radius: 12px; font-size: 14px; font-weight: 600;">
            <i class="fas fa-<?= $cfg['icon'] ?>"></i> <?= htmlspecialchars($st) ?>
            </span>
        </p>
      </div>
    </div>
    
    <?php if (!empty($assignment['ghi_chu'])): ?>
      <div style="margin-top: 24px; padding-top: 24px; border-top: 1px solid var(--border);">
        <strong style="color: var(--text-light); font-size: 12px; text-transform: uppercase;">Ghi chú</strong>
        <p style="margin-top: 8px; color: var(--text-dark); line-height: 1.6;">
          <?= nl2br(htmlspecialchars($assignment['ghi_chu'])) ?>
        </p>
      </div>
    <?php endif; ?>
  </div>
</div>

<?php if ($tour): ?>
<div class="card" style="margin-bottom: 24px;">
  <div class="card-header">
    <h3><i class="fas fa-map-marked-alt"></i> Thông tin Tour</h3>
  </div>
  <div class="card-body">
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
      <div>
        <strong style="color: var(--text-light); font-size: 12px; text-transform: uppercase;">Tên tour</strong>
        <p style="font-size: 16px; font-weight: 600; margin-top: 4px;"><?= htmlspecialchars($tour['tengoi'] ?? 'N/A') ?></p>
      </div>
      <div>
        <strong style="color: var(--text-light); font-size: 12px; text-transform: uppercase;">Giá tour</strong>
        <div style="margin-top: 4px;">
          <?php 
          // Hiển thị tổng tiền từ tất cả bookings của lịch trình này
          if (!empty($tongTienTour) && $tongTienTour > 0): ?>
            <p style="font-size: 18px; font-weight: 700; color: var(--success); margin: 0;">
              <?= number_format($tongTienTour, 0, ',', '.') ?> đ
            </p>
            <p style="font-size: 12px; color: var(--text-light); margin: 4px 0 0 0;">
              (Tổng từ <?= count($bookings) ?> booking<?= count($bookings) > 1 ? 's' : '' ?>)
            </p>
          <?php else: ?>
            <p style="font-size: 16px; font-weight: 600; margin: 0; color: var(--success);">Chưa có booking</p>
            <?php 
            // Fallback: Hiển thị giá đơn lẻ nếu chưa có booking
            $giaNL = !empty($departurePlan['gia_nguoi_lon']) ? $departurePlan['gia_nguoi_lon'] : (!empty($tour['giagoi']) ? $tour['giagoi'] : null);
            if ($giaNL && $giaNL > 0): ?>
              <p style="font-size: 12px; color: var(--text-light); margin: 4px 0 0 0;">
                Giá người lớn: <?= number_format($giaNL, 0, ',', '.') ?> đ
              </p>
            <?php endif; ?>
          <?php endif; ?>
        </div>
      </div>
      <div>
        <strong style="color: var(--text-light); font-size: 12px; text-transform: uppercase;">Số ngày</strong>
        <p style="font-size: 16px; font-weight: 600; margin-top: 4px;"><?= htmlspecialchars($tour['songay'] ?? 'N/A') ?> ngày</p>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

<div class="card" style="margin-bottom: 24px;">
  <div class="card-header">
    <h3><i class="fas fa-route"></i> Lịch trình theo ngày</h3>
  </div>
  <div class="card-body">
    <?php if (!empty($itineraryDays)): ?>
      <div class="content-scrollable">
        <?php foreach ($itineraryDays as $dayNum => $day): ?>
          <div class="itinerary-day-card">
            <div class="day-header">
              <div class="day-number">
                <i class="fas fa-calendar-day"></i>
                <?= $day['title'] ?>
              </div>
            </div>
            <div class="day-content">
              <?php 
              // Làm sạch nội dung trước khi hiển thị
              $content = trim($day['content'] ?? '');
              // Loại bỏ các ký tự JSON thừa nếu có
              $content = preg_replace('/[\s]*[\}\]\"]+[\s]*$/', '', $content);
              $content = preg_replace('/^[\s]*[\{\[\"]+[\s]*/', '', $content);
              echo $content;
              ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div style="color: #6b7280;">Chưa có lịch trình theo ngày.</div>
    <?php endif; ?>
  </div>
</div>

<div class="card" style="margin-bottom: 24px;">
  <div class="card-header">
    <h3><i class="fas fa-users"></i> Khách & Điểm danh</h3>
    <div style="display:flex; gap:8px;">
      <a href="?act=guide-attendance&assignment_id=<?= $assignment['id'] ?>" class="btn btn-sm btn-primary"><i class="fas fa-clipboard-check"></i> Điểm danh</a>
    </div>
  </div>
  <div class="card-body">
    <!-- Thống kê điểm danh hôm nay -->
    <?php if (!empty($attendanceStats)): ?>
      <div style="background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); border-left: 4px solid #3b82f6; padding: 16px; border-radius: 8px; margin-bottom: 20px;">
        <h4 style="margin: 0 0 12px 0; color: #1e40af; display: flex; align-items: center; gap: 8px;">
          <i class="fas fa-clipboard-check"></i> Trạng thái điểm danh hôm nay (<?= date('d/m/Y') ?>)
        </h4>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 16px;">
          <div style="background: white; padding: 12px; border-radius: 8px; text-align: center;">
            <div style="font-size: 24px; font-weight: 700; color: #3b82f6;"><?= $attendanceStats['co_mat'] ?? 0 ?></div>
            <div style="font-size: 13px; color: #6b7280; margin-top: 4px;">Có mặt</div>
          </div>
          <div style="background: white; padding: 12px; border-radius: 8px; text-align: center;">
            <div style="font-size: 24px; font-weight: 700; color: #ef4444;"><?= $attendanceStats['vang_mat'] ?? 0 ?></div>
            <div style="font-size: 13px; color: #6b7280; margin-top: 4px;">Vắng mặt</div>
          </div>
          <div style="background: white; padding: 12px; border-radius: 8px; text-align: center;">
            <div style="font-size: 24px; font-weight: 700; color: #9ca3af;"><?= $attendanceStats['chua_diem_danh'] ?? 0 ?></div>
            <div style="font-size: 13px; color: #6b7280; margin-top: 4px;">Chưa điểm danh</div>
          </div>
          <div style="background: white; padding: 12px; border-radius: 8px; text-align: center;">
            <div style="font-size: 24px; font-weight: 700; color: #1f2937;"><?= $attendanceStats['total'] ?? 0 ?></div>
            <div style="font-size: 13px; color: #6b7280; margin-top: 4px;">Tổng số</div>
          </div>
        </div>
      </div>
    <?php endif; ?>
    
    <div style="display:grid; grid-template-columns: repeat(auto-fit,minmax(200px,1fr)); gap:12px; margin-bottom:12px;">
      <div><strong>Người lớn</strong><br><span style="color:var(--text-dark);"><?= (int)($guestStats['NL'] ?? 0) ?></span></div>
      <div><strong>Trẻ em</strong><br><span style="color:var(--text-dark);"><?= (int)($guestStats['TE'] ?? 0) ?></span></div>
      <div><strong>Em bé</strong><br><span style="color:var(--text-dark);"><?= (int)($guestStats['EB'] ?? 0) ?></span></div>
    </div>
    <?php if (!empty($members)): ?>
      <div class="table-responsive">
        <table>
          <thead>
            <tr>
              <th>STT</th>
              <th>Mã booking</th>
              <th>Họ tên</th>
              <th>Loại khách</th>
              <th>Điện thoại</th>
              <th>Trạng thái điểm danh</th>
            </tr>
          </thead>
          <tbody>
            <?php 
            $i=1; 
            foreach ($members as $m): 
              $attendanceData = $todayAttendanceMap[$m['id']] ?? null;
              $attendanceStatus = 'Chưa điểm danh';
              $statusColor = '#9ca3af';
              $statusIcon = 'fa-circle';
              if ($attendanceData) {
                if ($attendanceData['trang_thai'] == 1) {
                  $attendanceStatus = 'Có mặt';
                  $statusColor = '#10b981';
                  $statusIcon = 'fa-check-circle';
                } elseif ($attendanceData['trang_thai'] == 0) {
                  $attendanceStatus = 'Vắng mặt';
                  $statusColor = '#ef4444';
                  $statusIcon = 'fa-times-circle';
                }
              }
            ?>
              <tr>
                <td><?= $i++ ?></td>
                <td><strong style="color: var(--primary);"><?= htmlspecialchars($m['ma_booking']) ?></strong></td>
                <td><?= htmlspecialchars($m['ho_ten']) ?></td>
                <td><?= htmlspecialchars($m['loai_khach'] ?? '') ?></td>
                <td><?= htmlspecialchars($m['so_dien_thoai'] ?? '-') ?></td>
                <td>
                  <span style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; border-radius: 8px; font-size: 13px; font-weight: 600; background: <?= $statusColor ?>20; color: <?= $statusColor ?>;">
                    <i class="fas <?= $statusIcon ?>"></i> <?= $attendanceStatus ?>
                  </span>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <div style="padding:16px; border:1px dashed var(--border); border-radius:10px; color:var(--text-light);">
        Chưa có danh sách khách cho lịch này.
      </div>
    <?php endif; ?>
  </div>
</div>

<?php if (!empty($services)): ?>
<div class="card">
  <div class="card-header">
    <h3><i class="fas fa-concierge-bell"></i> Dịch vụ được gán</h3>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table>
        <thead>
          <tr>
            <th>Dịch vụ</th>
            <th>Loại</th>
            <th>Số lượng</th>
            <th>Ngày sử dụng</th>
            <th>Giá thực tế</th>
            <th>Trạng thái</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($services as $service): ?>
            <tr>
              <td><strong><?= htmlspecialchars($service['ten_dich_vu'] ?? 'N/A') ?></strong></td>
              <td><?= htmlspecialchars($service['loai_dich_vu'] ?? 'N/A') ?></td>
              <td><?= htmlspecialchars($service['so_luong'] ?? 1) ?> <?= htmlspecialchars($service['don_vi'] ?? '') ?></td>
              <td>
                <?php if ($service['ngay_su_dung']): ?>
                  <?= date('d/m/Y', strtotime($service['ngay_su_dung'])) ?>
                <?php else: ?>
                  Chưa xác định
                <?php endif; ?>
              </td>
              <td>
                <?php if (!empty($service['gia_thuc_te'])): ?>
                  <?= number_format($service['gia_thuc_te'], 0, ',', '.') ?> đ
                <?php else: ?>
                  Chưa cập nhật
                <?php endif; ?>
              </td>
              <td>
                <?php
                $statusLabels = [
                  'cho' => ['label' => 'Chờ xác nhận', 'color' => '#f59e0b', 'bg' => '#fed7aa'],
                  'da_xac_nhan' => ['label' => 'Đã xác nhận', 'color' => '#065f46', 'bg' => '#d1fae5'],
                  'huy' => ['label' => 'Đã hủy', 'color' => '#991b1b', 'bg' => '#fee2e2']
                ];
                $status = $statusLabels[$service['trang_thai']] ?? $statusLabels['cho'];
                ?>
                <span style="background: <?= $status['bg'] ?>; color: <?= $status['color'] ?>; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                  <?= $status['label'] ?>
                </span>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php endif; ?>

<div class="card" style="margin-top:24px;">
  <div class="card-header">
    <h3><i class="fas fa-phone-alt"></i> Liên hệ & Tài liệu</h3>
  </div>
  <div class="card-body" style="display:grid; grid-template-columns: repeat(auto-fit,minmax(240px,1fr)); gap:16px;">
    <div>
      <strong>Số điều hành</strong><br>
      <?php if (empty($assignment['so_dieu_hanh'])): ?>
        <span style="color:var(--text-light); display: block; margin-top: 8px;">Chưa cập nhật</span>
      <?php else: ?>
        <a href="tel:<?= htmlspecialchars($assignment['so_dieu_hanh']) ?>" style="color: var(--primary); text-decoration: none; display: block; margin-top: 8px;">
          <i class="fas fa-phone"></i> <?= htmlspecialchars($assignment['so_dieu_hanh']) ?>
        </a>
      <?php endif; ?>
    </div>
    <div>
      <strong>Tài liệu tour</strong><br>
      <a href="#" onclick="alert('Chưa có tài liệu'); return false;" style="color: var(--primary); text-decoration:none; display: block; margin-top: 8px;"><i class="fas fa-file-pdf"></i> Tải lịch trình (PDF)</a>
      <a href="#" onclick="alert('Chưa có danh sách khách'); return false;" style="color: var(--primary); text-decoration:none; display: block; margin-top: 4px;"><i class="fas fa-users"></i> Tải danh sách khách</a>
      <a href="#" onclick="alert('Chưa có checklist'); return false;" style="color: var(--primary); text-decoration:none; display: block; margin-top: 4px;"><i class="fas fa-list-check"></i> Tải checklist</a>
    </div>
  </div>
</div>


