<?php
// views/guide/journals/create.php
?>

<div class="card">
  <div class="card-header">
    <h3><i class="fas fa-plus-circle"></i> Tạo nhật ký mới</h3>
    <a href="?act=guide-assignment-detail&id=<?= $assignment['id'] ?>" class="btn btn-sm" style="background: var(--bg-light); color: var(--text-dark);">
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
          <input type="date" name="ngay" value="<?= date('Y-m-d') ?>" required style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px;">
        </div>

        <!-- Diễn biến -->
        <div>
          <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: var(--text-dark);">
            Diễn biến
          </label>
          <textarea name="dien_bien" rows="6" placeholder="Ghi lại các sự kiện, hoạt động trong ngày..." style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px; font-family: inherit; resize: vertical;"></textarea>
        </div>

        <!-- Sự cố -->
        <div>
          <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: var(--text-dark);">
            Sự cố
          </label>
          <textarea name="su_co" rows="4" placeholder="Ghi lại các sự cố, vấn đề phát sinh (nếu có)..." style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px; font-family: inherit; resize: vertical;"></textarea>
        </div>

        <!-- Thời tiết -->
        <div>
          <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: var(--text-dark);">
            Thời tiết
          </label>
          <input type="text" name="thoi_tiet" placeholder="Ví dụ: Nắng đẹp, 28°C" style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px;">
        </div>

        <!-- Điểm nhấn -->
        <div>
          <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: var(--text-dark);">
            Điểm nhấn
          </label>
          <textarea name="diem_nhan" rows="4" placeholder="Ghi lại các điểm nhấn, highlight của ngày..." style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px; font-family: inherit; resize: vertical;"></textarea>
        </div>

        <!-- Ảnh -->
        <div>
          <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: var(--text-dark);">
            Ảnh đính kèm
          </label>
          <input type="file" name="hinh_anh[]" multiple accept="image/*" style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px;">
          <small style="color: var(--text-light); margin-top: 4px; display: block;">Có thể chọn nhiều ảnh (JPG, PNG, GIF, tối đa 5MB/ảnh)</small>
        </div>

        <!-- Buttons -->
        <div style="display: flex; gap: 12px; margin-top: 8px;">
          <button type="submit" class="btn btn-primary" style="flex: 1;">
            <i class="fas fa-save"></i> Lưu nhật ký
          </button>
          <a href="?act=guide-assignment-detail&id=<?= $assignment['id'] ?>" class="btn" style="background: var(--bg-light); color: var(--text-dark); padding: 10px 20px;">
            Hủy
          </a>
        </div>
      </div>
    </form>
  </div>
</div>

