<?php
// views/guide/attendance.php - Trang điểm danh thành viên
$assignment = $assignment ?? null;
$departurePlan = $departurePlan ?? null;
$members = $members ?? [];
$attendance = $attendance ?? [];
$ngay_diem_danh = $ngay_diem_danh ?? date('Y-m-d');
?>

<div class="card">
  <div class="card-header">
    <h3><i class="fas fa-clipboard-check"></i> Điểm danh thành viên</h3>
    <div style="display: flex; gap: 12px;">
      <a href="?act=guide-assignment-detail&id=<?= $assignment['id'] ?? '' ?>" class="btn btn-sm" style="background: var(--bg-light); color: var(--text-dark);">
        <i class="fas fa-arrow-left"></i> Quay lại
      </a>
    </div>
  </div>
  <div class="card-body">
    <?php if ($departurePlan): ?>
      <div style="background: #f0f9ff; border-left: 4px solid #3b82f6; padding: 16px; border-radius: 8px; margin-bottom: 24px;">
        <h4 style="margin: 0 0 8px 0; color: #1e40af;">
          <?= htmlspecialchars($departurePlan['ten_tour'] ?? 'N/A') ?>
        </h4>
        <p style="margin: 0; color: #64748b;">
          <i class="fas fa-calendar"></i> Ngày khởi hành: <?= date('d/m/Y', strtotime($departurePlan['ngay_khoi_hanh'])) ?>
          <?php if ($departurePlan['gio_khoi_hanh']): ?>
            - <?= htmlspecialchars($departurePlan['gio_khoi_hanh']) ?>
          <?php endif; ?>
        </p>
      </div>

      <!-- Form chọn ngày điểm danh -->
      <div style="background: white; padding: 20px; border-radius: 12px; border: 1px solid #e5e7eb; margin-bottom: 24px;">
        <form method="GET" action="" style="display: flex; gap: 12px; align-items: end;">
          <input type="hidden" name="act" value="guide-attendance">
          <input type="hidden" name="assignment_id" value="<?= $assignment['id'] ?? '' ?>">
          <div style="flex: 1;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">Ngày điểm danh</label>
            <input type="date" name="ngay_diem_danh" value="<?= htmlspecialchars($ngay_diem_danh) ?>" 
                   style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px;" required>
          </div>
          <button type="submit" class="btn btn-primary" style="padding: 10px 24px;">
            <i class="fas fa-search"></i> Xem danh sách
          </button>
        </form>
      </div>

      <?php if (!empty($members)): ?>
        <form id="attendanceForm" style="background: white; padding: 24px; border-radius: 12px; border: 1px solid #e5e7eb;">
          <h4 style="margin: 0 0 20px 0; color: #1f2937;">Danh sách thành viên</h4>
          
          <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
              <thead>
                <tr style="background: #f9fafb; border-bottom: 2px solid #e5e7eb;">
                  <th style="padding: 12px; text-align: left; font-weight: 600; color: #374151;">STT</th>
                  <th style="padding: 12px; text-align: left; font-weight: 600; color: #374151;">Mã booking</th>
                  <th style="padding: 12px; text-align: left; font-weight: 600; color: #374151;">Họ tên</th>
                  <th style="padding: 12px; text-align: left; font-weight: 600; color: #374151;">Giới tính</th>
                  <th style="padding: 12px; text-align: left; font-weight: 600; color: #374151;">Ngày sinh</th>
                  <th style="padding: 12px; text-align: left; font-weight: 600; color: #374151;">Số điện thoại</th>
                  <th style="padding: 12px; text-align: center; font-weight: 600; color: #374151;">Trạng thái điểm danh</th>
                  <th style="padding: 12px; text-align: center; font-weight: 600; color: #374151;">Điểm danh</th>
                  <th style="padding: 12px; text-align: left; font-weight: 600; color: #374151;">Ghi chú</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                $cnt = 1;
                foreach ($members as $member): 
                  $attendanceData = null;
                  foreach ($attendance as $att) {
                    if ($att['id_booking_detail'] == $member['id']) {
                      $attendanceData = $att;
                      break;
                    }
                  }
                  $isPresent = $attendanceData ? ($attendanceData['trang_thai'] == 1) : true;
                  
                  // Format giới tính
                  $gioiTinhText = '-';
                  $gioiTinhIcon = '';
                  if (isset($member['gioi_tinh'])) {
                    if ($member['gioi_tinh'] == 1) {
                      $gioiTinhText = 'Nam';
                      $gioiTinhIcon = 'fa-mars';
                    } elseif ($member['gioi_tinh'] == 0) {
                      $gioiTinhText = 'Nữ';
                      $gioiTinhIcon = 'fa-venus';
                    }
                  }
                  
                  // Format ngày sinh
                  $ngaySinhText = '-';
                  if (!empty($member['ngay_sinh'])) {
                    $ngaySinhText = date('d/m/Y', strtotime($member['ngay_sinh']));
                  }
                  
                  // Trạng thái điểm danh
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
                  <tr style="border-bottom: 1px solid #e5e7eb;">
                    <td style="padding: 12px;"><?= $cnt ?></td>
                    <td style="padding: 12px;">
                      <strong style="color: #3b82f6;"><?= htmlspecialchars($member['ma_booking'] ?? 'N/A') ?></strong>
                    </td>
                    <td style="padding: 12px;">
                      <strong><?= htmlspecialchars($member['ho_ten'] ?? 'N/A') ?></strong>
                    </td>
                    <td style="padding: 12px;">
                      <?php if ($gioiTinhIcon): ?>
                        <span style="display: inline-flex; align-items: center; gap: 6px;">
                          <i class="fas <?= $gioiTinhIcon ?>" style="color: <?= $member['gioi_tinh'] == 1 ? '#3b82f6' : '#ec4899' ?>;"></i>
                          <?= htmlspecialchars($gioiTinhText) ?>
                        </span>
                      <?php else: ?>
                        <?= htmlspecialchars($gioiTinhText) ?>
                      <?php endif; ?>
                    </td>
                    <td style="padding: 12px;"><?= htmlspecialchars($ngaySinhText) ?></td>
                    <td style="padding: 12px;"><?= htmlspecialchars($member['so_dien_thoai'] ?? '-') ?></td>
                    <td style="padding: 12px; text-align: center;">
                      <span style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; border-radius: 8px; font-size: 13px; font-weight: 600; background: <?= $statusColor ?>20; color: <?= $statusColor ?>;">
                        <i class="fas <?= $statusIcon ?>"></i> <?= $attendanceStatus ?>
                      </span>
                    </td>
                    <td style="padding: 12px; text-align: center;">
                      <div style="display: flex; gap: 12px; justify-content: center; align-items: center;">
                        <label style="display: flex; align-items: center; gap: 6px; cursor: pointer;">
                          <input type="radio" 
                                 name="attendance[<?= $member['id'] ?>][trang_thai]" 
                                 value="1" 
                                 <?= $isPresent ? 'checked' : '' ?>
                                 required>
                          <span style="color: #10b981; font-weight: 600;">Có mặt</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 6px; cursor: pointer;">
                          <input type="radio" 
                                 name="attendance[<?= $member['id'] ?>][trang_thai]" 
                                 value="0" 
                                 <?= !$isPresent ? 'checked' : '' ?>>
                          <span style="color: #ef4444; font-weight: 600;">Vắng mặt</span>
                        </label>
                      </div>
                      <input type="hidden" name="attendance[<?= $member['id'] ?>][id_booking]" value="<?= $member['id_booking'] ?>">
                      <input type="hidden" name="attendance[<?= $member['id'] ?>][id_booking_detail]" value="<?= $member['id'] ?>">
                    </td>
                    <td style="padding: 12px;">
                      <input type="text" 
                             name="attendance[<?= $member['id'] ?>][ghi_chu]" 
                             value="<?= htmlspecialchars($attendanceData['ghi_chu'] ?? '') ?>"
                             placeholder="Ghi chú (nếu có)"
                             style="width: 100%; padding: 8px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px;">
                    </td>
                  </tr>
                <?php $cnt++; endforeach; ?>
              </tbody>
            </table>
          </div>

          <div style="margin-top: 24px; padding-top: 20px; border-top: 2px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center;">
            <div style="color: #6b7280; font-size: 14px;">
              <i class="fas fa-info-circle"></i> Tổng số: <strong><?= count($members) ?></strong> thành viên
            </div>
            <div style="display: flex; gap: 12px;">
              <button type="button" onclick="markAllPresent()" class="btn" style="background: #10b981; color: white;">
                <i class="fas fa-check-double"></i> Tất cả có mặt
              </button>
              <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Lưu điểm danh
              </button>
            </div>
          </div>
        </form>
      <?php else: ?>
        <div style="text-align: center; padding: 60px 20px; color: #6b7280;">
          <i class="fas fa-users" style="font-size: 64px; opacity: 0.3; margin-bottom: 16px;"></i>
          <p style="font-size: 18px; margin: 0;">Chưa có thành viên nào trong lịch trình này</p>
        </div>
      <?php endif; ?>
    <?php else: ?>
      <div style="text-align: center; padding: 60px 20px; color: #6b7280;">
        <i class="fas fa-exclamation-triangle" style="font-size: 64px; opacity: 0.3; margin-bottom: 16px;"></i>
        <p style="font-size: 18px; margin: 0;">Không tìm thấy thông tin lịch trình</p>
      </div>
    <?php endif; ?>
  </div>
</div>

<script>
function markAllPresent() {
  document.querySelectorAll('input[type="radio"][value="1"]').forEach(radio => {
    radio.checked = true;
  });
}

document.getElementById('attendanceForm')?.addEventListener('submit', async function(e) {
  e.preventDefault();
  
  const formData = new FormData(this);
  const attendanceData = {};
  
  // Chuyển đổi FormData thành object
  for (let [key, value] of formData.entries()) {
    const match = key.match(/attendance\[(\d+)\]\[(\w+)\]/);
    if (match) {
      const memberId = match[1];
      const field = match[2];
      if (!attendanceData[memberId]) {
        attendanceData[memberId] = {};
      }
      attendanceData[memberId][field] = value;
    }
  }
  
  // Chuyển đổi thành array
  const attendanceList = Object.values(attendanceData);
  
  try {
    const response = await fetch('?act=guide-attendance-save', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        id_lich_khoi_hanh: <?= $departurePlan['id'] ?? 0 ?>,
        id_hdv: <?= isset($_SESSION['guide_id']) ? (int)$_SESSION['guide_id'] : 0 ?>,
        ngay_diem_danh: '<?= htmlspecialchars($ngay_diem_danh) ?>',
        attendance: attendanceList
      })
    });
    
    if (!response.ok) {
      const errorText = await response.text();
      console.error('HTTP Error:', response.status, errorText);
      throw new Error(`HTTP ${response.status}: ${errorText}`);
    }
    
    const result = await response.json();
    
    if (result.success) {
      alert('Điểm danh thành công!');
      location.reload();
    } else {
      alert('Lỗi: ' + (result.message || 'Không thể lưu điểm danh'));
    }
  } catch (error) {
    console.error('Error:', error);
    console.error('Error details:', error.message);
    alert('Lỗi kết nối. Vui lòng thử lại.\nChi tiết: ' + error.message);
  }
});
</script>

