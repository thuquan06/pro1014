<?php
// views/guide/journals/edit.php
?>

<div class="card">
  <div class="card-header">
    <h3><i class="fas fa-edit"></i> Chỉnh sửa nhật ký</h3>
    <a href="?act=guide-journal-detail&id=<?= $journal['id'] ?>" class="btn btn-sm" style="background: var(--bg-light); color: var(--text-dark);">
      <i class="fas fa-arrow-left"></i> Quay lại
    </a>
  </div>
  <div class="card-body">
    <form method="POST" enctype="multipart/form-data" style="max-width: 800px;">
      <!-- Tour Info -->
      <div style="background: var(--bg-light); padding: 20px; border-radius: 10px; margin-bottom: 24px;">
        <h4 style="margin: 0 0 12px 0; color: var(--primary);">
          <i class="fas fa-map-marked-alt"></i> <?= htmlspecialchars($tour['tengoi'] ?? 'Tour') ?>
        </h4>
        <p style="margin: 0; color: var(--text-light); font-size: 14px;">
          Ngày khởi hành: <?= $departurePlan['ngay_khoi_hanh'] ? date('d/m/Y', strtotime($departurePlan['ngay_khoi_hanh'])) : 'N/A' ?>
        </p>
      </div>

      <div style="display: grid; gap: 24px;">
        <!-- Ngày -->
        <div>
          <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: var(--text-dark);">
            Ngày <span style="color: var(--danger);">*</span>
          </label>
          <input type="date" name="ngay" value="<?= htmlspecialchars($journal['ngay']) ?>" required style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px;">
        </div>

        <!-- Diễn biến -->
        <div>
          <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: var(--text-dark);">
            Diễn biến
          </label>
          <textarea name="dien_bien" rows="6" placeholder="Ghi lại các sự kiện, hoạt động trong ngày..." style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px; font-family: inherit; resize: vertical;"><?= htmlspecialchars($journal['dien_bien'] ?? '') ?></textarea>
        </div>

        <!-- Sự cố -->
        <div>
          <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: var(--text-dark);">
            Sự cố
          </label>
          <textarea name="su_co" rows="4" placeholder="Ghi lại các sự cố, vấn đề phát sinh (nếu có)..." style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px; font-family: inherit; resize: vertical;"><?= htmlspecialchars($journal['su_co'] ?? '') ?></textarea>
        </div>

        <!-- Thời tiết -->
        <div>
          <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: var(--text-dark);">
            Thời tiết
          </label>
          <input type="text" name="thoi_tiet" value="<?= htmlspecialchars($journal['thoi_tiet'] ?? '') ?>" placeholder="Ví dụ: Nắng đẹp, 28°C" style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px;">
        </div>

        <!-- Điểm nhấn -->
        <div>
          <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: var(--text-dark);">
            Điểm nhấn
          </label>
          <textarea name="diem_nhan" rows="4" placeholder="Ghi lại các điểm nhấn, highlight của ngày..." style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px; font-family: inherit; resize: vertical;"><?= htmlspecialchars($journal['diem_nhan'] ?? '') ?></textarea>
        </div>

        <!-- Existing Images -->
        <?php if (!empty($journal['hinh_anh']) && count($journal['hinh_anh']) > 0): ?>
          <div>
            <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: var(--text-dark);">
              Ảnh hiện tại
            </label>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 12px;">
              <?php foreach ($journal['hinh_anh'] as $index => $image): ?>
                <?php if (!empty($image)): ?>
                  <div style="position: relative; aspect-ratio: 1; border-radius: 8px; overflow: hidden; border: 2px solid var(--border);">
                    <img src="<?= BASE_URL . htmlspecialchars($image) ?>" alt="Journal image" style="width: 100%; height: 100%; object-fit: cover;">
                    <label style="position: absolute; top: 8px; right: 8px; background: rgba(239, 68, 68, 0.9); color: white; padding: 4px 8px; border-radius: 6px; cursor: pointer; font-size: 12px;">
                      <input type="checkbox" name="delete_images[]" value="<?= htmlspecialchars($image) ?>" style="display: none;" onchange="this.parentElement.style.opacity = this.checked ? '1' : '0.5'">
                      <i class="fas fa-trash"></i> Xóa
                    </label>
                  </div>
                <?php endif; ?>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endif; ?>

        <!-- New Images -->
        <div>
          <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: var(--text-dark);">
            Thêm ảnh mới
          </label>
          <input type="file" name="hinh_anh[]" multiple accept="image/*" style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px;">
          <small style="color: var(--text-light); margin-top: 4px; display: block;">Có thể chọn nhiều ảnh (JPG, PNG, GIF, tối đa 5MB/ảnh)</small>
        </div>

        <!-- Buttons -->
        <div style="display: flex; gap: 12px; margin-top: 8px;">
          <button type="submit" class="btn btn-primary" style="flex: 1;">
            <i class="fas fa-save"></i> Cập nhật nhật ký
          </button>
          <a href="?act=guide-journal-detail&id=<?= $journal['id'] ?>" class="btn" style="background: var(--bg-light); color: var(--text-dark); padding: 10px 20px;">
            Hủy
          </a>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
// Update checkbox label style on change
document.querySelectorAll('input[name="delete_images[]"]').forEach(checkbox => {
  checkbox.addEventListener('change', function() {
    const label = this.parentElement;
    if (this.checked) {
      label.style.opacity = '1';
      label.style.background = 'rgba(239, 68, 68, 1)';
    } else {
      label.style.opacity = '0.5';
      label.style.background = 'rgba(239, 68, 68, 0.9)';
    }
  });
});
</script>

