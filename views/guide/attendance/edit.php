<?php
// views/guide/attendance/edit.php
?>

<div class="card">
  <div class="card-header">
    <h3><i class="fas fa-edit"></i> Sửa điểm danh</h3>
    <a href="?act=guide-attendance-detail&id=<?= $attendance['id'] ?>" class="btn btn-sm" style="background: var(--bg-light); color: var(--text-dark);">
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
        <!-- Điểm nghỉ (readonly) -->
        <div>
          <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: var(--text-dark);">
            Điểm nghỉ
          </label>
          <input type="text" value="<?= htmlspecialchars($attendance['diem_nghi'] ?? '') ?>" disabled style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px; background: var(--bg-light);">
        </div>

        <!-- Ngày điểm danh -->
        <div>
          <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: var(--text-dark);">
            Ngày điểm danh <span style="color: var(--danger);">*</span>
          </label>
          <input type="date" name="ngay_diem_danh" value="<?= date('Y-m-d', strtotime($attendance['ngay_diem_danh'])) ?>" required style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px;">
        </div>

        <!-- Giờ điểm danh -->
        <div>
          <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: var(--text-dark);">
            Giờ điểm danh <span style="color: var(--danger);">*</span>
          </label>
          <input type="time" name="gio_diem_danh" value="<?= date('H:i', strtotime($attendance['gio_diem_danh'])) ?>" required style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px;">
        </div>

        <!-- Danh sách có mặt -->
        <div>
          <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: var(--text-dark);">
            Danh sách có mặt
          </label>
          <textarea name="danh_sach_co_mat" rows="6" placeholder="Nhập danh sách người có mặt, mỗi người một dòng..." style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px; font-family: inherit; resize: vertical;"><?= !empty($attendance['danh_sach_co_mat']) ? implode("\n", array_map('htmlspecialchars', $attendance['danh_sach_co_mat'])) : '' ?></textarea>
          <small style="color: var(--text-light); margin-top: 4px; display: block;">Mỗi người một dòng</small>
        </div>

        <!-- Danh sách vắng mặt -->
        <div>
          <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: var(--text-dark);">
            Danh sách vắng mặt
          </label>
          <textarea name="danh_sach_vang_mat" rows="4" placeholder="Nhập danh sách người vắng mặt, mỗi người một dòng...&#10;(Nếu có)" style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px; font-family: inherit; resize: vertical;"><?= !empty($attendance['danh_sach_vang_mat']) ? implode("\n", array_map('htmlspecialchars', $attendance['danh_sach_vang_mat'])) : '' ?></textarea>
          <small style="color: var(--text-light); margin-top: 4px; display: block;">Mỗi người một dòng (nếu có)</small>
        </div>

        <!-- Ghi chú -->
        <div>
          <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: var(--text-dark);">
            Ghi chú
          </label>
          <textarea name="ghi_chu" rows="3" placeholder="Ghi chú thêm về điểm danh (nếu có)..." style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px; font-family: inherit; resize: vertical;"><?= htmlspecialchars($attendance['ghi_chu'] ?? '') ?></textarea>
        </div>

        <!-- Buttons -->
        <div style="display: flex; gap: 12px; margin-top: 8px;">
          <button type="submit" class="btn btn-primary" style="flex: 1;">
            <i class="fas fa-save"></i> Cập nhật điểm danh
          </button>
          <a href="?act=guide-attendance-detail&id=<?= $attendance['id'] ?>" class="btn" style="background: var(--bg-light); color: var(--text-dark); padding: 10px 20px;">
            Hủy
          </a>
        </div>
      </div>
    </form>
  </div>
</div>

