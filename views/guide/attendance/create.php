<?php
// views/guide/attendance/create.php
?>

<div class="card">
  <div class="card-header">
    <h3><i class="fas fa-plus-circle"></i> Điểm danh tại điểm dừng nghỉ</h3>
    <a href="?act=guide-attendance&assignment_id=<?= $assignment['id'] ?>" class="btn btn-sm" style="background: var(--bg-light); color: var(--text-dark);">
      <i class="fas fa-arrow-left"></i> Quay lại
    </a>
  </div>
  <div class="card-body">
    <form method="POST" style="max-width: 800px;">
      <!-- Tour Info -->
      <?php if ($tour): ?>
      <div style="background: var(--bg-light); padding: 20px; border-radius: 10px; margin-bottom: 24px;">
        <h4 style="margin: 0 0 12px 0; color: var(--primary);">
          <i class="fas fa-map-marked-alt"></i> <?= htmlspecialchars($tour['tengoi'] ?? 'Tour') ?>
        </h4>
        <p style="margin: 0; color: var(--text-light); font-size: 14px;">
          Ngày khởi hành: <?= $departurePlan['ngay_khoi_hanh'] ? date('d/m/Y', strtotime($departurePlan['ngay_khoi_hanh'])) : 'N/A' ?>
        </p>
      </div>
      <?php endif; ?>

      <div style="display: grid; gap: 24px;">
        <!-- Chọn lịch trình -->
        <?php if (!empty($scheduleDays)): ?>
        <div>
          <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: var(--text-dark);">
            Chọn điểm dừng nghỉ <span style="color: var(--danger);">*</span>
          </label>
          <select name="id_lich_trinh" required style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px;" onchange="updateRestStopInfo(this)">
            <option value="">-- Chọn điểm nghỉ --</option>
            <?php foreach ($scheduleDays as $day): ?>
              <option value="<?= $day['id'] ?>" 
                      data-ngay-thu="<?= $day['ngay_thu'] ?>" 
                      data-diem-nghi="<?= htmlspecialchars($day['noinghi']) ?>"
                      <?= ($schedule && $schedule['id'] == $day['id']) ? 'selected' : '' ?>>
                Ngày <?= $day['ngay_thu'] ?> - <?= htmlspecialchars($day['noinghi']) ?>
                <?php if (!empty($day['tieude'])): ?>
                  (<?= htmlspecialchars($day['tieude']) ?>)
                <?php endif; ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <?php else: ?>
          <input type="hidden" name="id_lich_trinh" value="<?= $schedule['id'] ?? '' ?>">
        <?php endif; ?>

        <!-- Điểm nghỉ -->
        <div>
          <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: var(--text-dark);">
            Điểm nghỉ <span style="color: var(--danger);">*</span>
          </label>
          <input type="text" name="diem_nghi" id="diem_nghi" 
                 value="<?= htmlspecialchars($schedule['noinghi'] ?? '') ?>" 
                 required 
                 style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px;">
        </div>

        <!-- Ngày thứ -->
        <div>
          <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: var(--text-dark);">
            Ngày thứ <span style="color: var(--danger);">*</span>
          </label>
          <input type="number" name="ngay_thu" id="ngay_thu" 
                 value="<?= htmlspecialchars($schedule['ngay_thu'] ?? '') ?>" 
                 required min="1"
                 style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px;">
        </div>

        <!-- Ngày điểm danh -->
        <div>
          <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: var(--text-dark);">
            Ngày điểm danh <span style="color: var(--danger);">*</span>
          </label>
          <input type="date" name="ngay_diem_danh" value="<?= date('Y-m-d') ?>" required style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px;">
        </div>

        <!-- Giờ điểm danh -->
        <div>
          <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: var(--text-dark);">
            Giờ điểm danh <span style="color: var(--danger);">*</span>
          </label>
          <input type="time" name="gio_diem_danh" value="<?= date('H:i') ?>" required style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px;">
        </div>

        <!-- Danh sách có mặt -->
        <div>
          <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: var(--text-dark);">
            Danh sách có mặt
          </label>
          <textarea name="danh_sach_co_mat" rows="6" placeholder="Nhập danh sách người có mặt, mỗi người một dòng...&#10;Ví dụ:&#10;Nguyễn Văn A&#10;Trần Thị B&#10;Lê Văn C" style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px; font-family: inherit; resize: vertical;"></textarea>
          <small style="color: var(--text-light); margin-top: 4px; display: block;">Mỗi người một dòng</small>
        </div>

        <!-- Danh sách vắng mặt -->
        <div>
          <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: var(--text-dark);">
            Danh sách vắng mặt
          </label>
          <textarea name="danh_sach_vang_mat" rows="4" placeholder="Nhập danh sách người vắng mặt, mỗi người một dòng...&#10;(Nếu có)" style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px; font-family: inherit; resize: vertical;"></textarea>
          <small style="color: var(--text-light); margin-top: 4px; display: block;">Mỗi người một dòng (nếu có)</small>
        </div>

        <!-- Ghi chú -->
        <div>
          <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: var(--text-dark);">
            Ghi chú
          </label>
          <textarea name="ghi_chu" rows="3" placeholder="Ghi chú thêm về điểm danh (nếu có)..." style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px; font-family: inherit; resize: vertical;"></textarea>
        </div>

        <!-- Buttons -->
        <div style="display: flex; gap: 12px; margin-top: 8px;">
          <button type="submit" class="btn btn-primary" style="flex: 1;">
            <i class="fas fa-save"></i> Lưu điểm danh
          </button>
          <a href="?act=guide-attendance&assignment_id=<?= $assignment['id'] ?>" class="btn" style="background: var(--bg-light); color: var(--text-dark); padding: 10px 20px;">
            Hủy
          </a>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
function updateRestStopInfo(select) {
  const option = select.options[select.selectedIndex];
  if (option.value) {
    document.getElementById('diem_nghi').value = option.getAttribute('data-diem-nghi') || '';
    document.getElementById('ngay_thu').value = option.getAttribute('data-ngay-thu') || '';
  }
}
</script>

