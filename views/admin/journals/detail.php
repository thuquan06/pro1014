<?php
// views/admin/journals/detail.php
?>

<div class="card" style="margin-bottom: 24px;">
  <div class="card-header">
    <h3><i class="fas fa-book-open"></i> Chi tiết nhật ký tour</h3>
    <div style="display: flex; gap: 12px;">
      <a href="?act=admin-journals" class="btn btn-sm" style="background: var(--bg-light); color: var(--text-dark);">
        <i class="fas fa-arrow-left"></i> Quay lại
      </a>
    </div>
  </div>
  <div class="card-body">
    <!-- Tour Info -->
    <div style="background: var(--bg-light); padding: 20px; border-radius: 10px; margin-bottom: 24px;">
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px;">
        <div>
          <h4 style="margin: 0 0 8px 0; color: var(--primary);">
            <i class="fas fa-map-marked-alt"></i> <?= htmlspecialchars($tour['tengoi'] ?? 'Tour') ?>
          </h4>
          <p style="margin: 0; color: var(--text-light); font-size: 14px;">
            Ngày khởi hành: <?= $departurePlan['ngay_khoi_hanh'] ? date('d/m/Y', strtotime($departurePlan['ngay_khoi_hanh'])) : 'N/A' ?>
          </p>
        </div>
        <div>
          <strong style="color: var(--text-light); font-size: 12px; text-transform: uppercase;">Hướng dẫn viên</strong>
          <p style="margin: 4px 0 0 0; font-size: 16px; font-weight: 600;">
            <?= htmlspecialchars($journal['ten_hdv'] ?? 'N/A') ?>
          </p>
          <?php if ($journal['email_hdv']): ?>
            <small style="color: var(--text-light);"><?= htmlspecialchars($journal['email_hdv']) ?></small>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- Journal Details -->
    <div style="display: grid; gap: 24px;">
      <!-- Ngày -->
      <div>
        <strong style="color: var(--text-light); font-size: 12px; text-transform: uppercase;">Ngày</strong>
        <p style="font-size: 20px; font-weight: 700; margin-top: 4px; color: var(--primary);">
          <?= date('d/m/Y', strtotime($journal['ngay'])) ?>
        </p>
        <small style="color: var(--text-light);">
          Tạo lúc: <?= date('d/m/Y H:i', strtotime($journal['ngay_tao'])) ?>
          <?php if ($journal['ngay_cap_nhat'] && $journal['ngay_cap_nhat'] != $journal['ngay_tao']): ?>
            | Cập nhật: <?= date('d/m/Y H:i', strtotime($journal['ngay_cap_nhat'])) ?>
          <?php endif; ?>
        </small>
      </div>

      <!-- Diễn biến -->
      <?php if (!empty($journal['dien_bien'])): ?>
        <div>
          <strong style="color: var(--text-light); font-size: 12px; text-transform: uppercase;">Diễn biến</strong>
          <div style="margin-top: 8px; padding: 16px; background: var(--bg-light); border-radius: 8px; line-height: 1.8; color: var(--text-dark);">
            <?= nl2br(htmlspecialchars($journal['dien_bien'])) ?>
          </div>
        </div>
      <?php endif; ?>

      <!-- Sự cố -->
      <?php if (!empty($journal['su_co'])): ?>
        <div>
          <strong style="color: var(--text-light); font-size: 12px; text-transform: uppercase;">Sự cố</strong>
          <div style="margin-top: 8px; padding: 16px; background: #fee2e2; border-left: 4px solid var(--danger); border-radius: 8px; line-height: 1.8; color: #991b1b;">
            <?= nl2br(htmlspecialchars($journal['su_co'])) ?>
          </div>
        </div>
      <?php endif; ?>

      <!-- Thời tiết -->
      <?php if (!empty($journal['thoi_tiet'])): ?>
        <div>
          <strong style="color: var(--text-light); font-size: 12px; text-transform: uppercase;">Thời tiết</strong>
          <p style="margin-top: 8px; padding: 12px 20px; background: #dbeafe; color: #1e40af; border-radius: 8px; font-size: 16px; font-weight: 600; display: inline-block;">
            <i class="fas fa-cloud-sun"></i> <?= htmlspecialchars($journal['thoi_tiet']) ?>
          </p>
        </div>
      <?php endif; ?>

      <!-- Điểm nhấn -->
      <?php if (!empty($journal['diem_nhan'])): ?>
        <div>
          <strong style="color: var(--text-light); font-size: 12px; text-transform: uppercase;">Điểm nhấn</strong>
          <div style="margin-top: 8px; padding: 16px; background: #fef3c7; border-left: 4px solid var(--warning); border-radius: 8px; line-height: 1.8; color: #92400e;">
            <?= nl2br(htmlspecialchars($journal['diem_nhan'])) ?>
          </div>
        </div>
      <?php endif; ?>

      <!-- Ảnh -->
      <?php if (!empty($journal['hinh_anh']) && count($journal['hinh_anh']) > 0): ?>
        <div>
          <strong style="color: var(--text-light); font-size: 12px; text-transform: uppercase;">Ảnh đính kèm (<?= count($journal['hinh_anh']) ?>)</strong>
          <div style="margin-top: 16px; display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 16px;">
            <?php foreach ($journal['hinh_anh'] as $image): ?>
              <?php if (!empty($image)): ?>
                <div style="position: relative; aspect-ratio: 1; border-radius: 8px; overflow: hidden; border: 1px solid var(--border);">
                  <img src="<?= BASE_URL . htmlspecialchars($image) ?>" alt="Journal image" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;" onclick="openImageModal('<?= BASE_URL . htmlspecialchars($image) ?>')">
                </div>
              <?php endif; ?>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- Image Modal -->
<div id="imageModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.9); z-index: 10000; align-items: center; justify-content: center;">
  <div style="position: relative; max-width: 90%; max-height: 90%;">
    <img id="modalImage" src="" alt="Full size" style="max-width: 100%; max-height: 90vh; border-radius: 8px;">
    <button onclick="closeImageModal()" style="position: absolute; top: -40px; right: 0; background: white; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer; font-size: 18px; color: var(--text-dark);">
      <i class="fas fa-times"></i> Đóng
    </button>
  </div>
</div>

<script>
function openImageModal(imageSrc) {
  document.getElementById('modalImage').src = imageSrc;
  document.getElementById('imageModal').style.display = 'flex';
}

function closeImageModal() {
  document.getElementById('imageModal').style.display = 'none';
}

// Close on ESC key
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') {
    closeImageModal();
  }
});

// Close on click outside
document.getElementById('imageModal').addEventListener('click', function(e) {
  if (e.target === this) {
    closeImageModal();
  }
});
</script>

