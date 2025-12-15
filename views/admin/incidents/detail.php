<?php
// views/admin/incidents/detail.php
?>

<div class="card">
  <div class="card-header">
    <h3><i class="fas fa-exclamation-triangle"></i> Chi tiết báo cáo sự cố</h3>
    <div style="display: flex; gap: 12px;">
      <a href="?act=admin-incidents" class="btn btn-sm" style="background: var(--bg-light); color: var(--text-dark);">
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
            <?= htmlspecialchars($incident['ten_hdv'] ?? 'N/A') ?>
          </p>
          <?php if (!empty($incident['email_hdv'])): ?>
            <small style="color: var(--text-light);">
              <?= htmlspecialchars($incident['email_hdv']) ?>
            </small>
          <?php endif; ?>

        </div>
      </div>
    </div>

    <div style="display: grid; gap: 24px; max-width: 800px;">
      <!-- Ngày giờ xảy ra -->
      <div>
        <label style="display: block; margin-bottom: 8px; font-size: 13px; font-weight: 600; color: var(--text-light); text-transform: uppercase;">
          Ngày giờ xảy ra
        </label>
        <p style="font-size: 16px; font-weight: 600; margin: 0; color: var(--text-dark);">
          <i class="fas fa-calendar-alt" style="color: var(--primary); margin-right: 8px;"></i>
          <?= date('d/m/Y', strtotime($incident['ngay_xay_ra'])) ?>
          <?php if (!empty($incident['gio_xay_ra'])): ?>
            lúc <?= date('H:i', strtotime($incident['gio_xay_ra'])) ?>
          <?php endif; ?>
        </p>
      </div>
      
      <!-- Vị trí GPS -->
      <?php if (!empty($incident['vi_tri_gps'])): ?>
      <div>
        <label style="display: block; margin-bottom: 8px; font-size: 13px; font-weight: 600; color: var(--text-light); text-transform: uppercase;">
          Vị trí xảy ra
        </label>
        <p style="font-size: 16px; margin: 0; color: var(--text-dark);">
          <i class="fas fa-map-marker-alt" style="color: var(--danger); margin-right: 8px;"></i>
          <?= htmlspecialchars($incident['vi_tri_gps']) ?>
          <?php 
          // Nếu là tọa độ GPS (lat,lng), có thể tạo link Google Maps
          if (preg_match('/^-?\d+\.?\d*,-?\d+\.?\d*$/', $incident['vi_tri_gps'])) {
              $coords = explode(',', $incident['vi_tri_gps']);
              $googleMapsUrl = "https://www.google.com/maps?q={$coords[0]},{$coords[1]}";
              echo ' <a href="' . $googleMapsUrl . '" target="_blank" style="color: var(--primary); text-decoration: none;"><i class="fas fa-external-link-alt"></i> Xem trên bản đồ</a>';
          }
          ?>
        </p>
      </div>
      <?php endif; ?>
      
      <!-- Thông tin khách liên quan -->
      <?php if (!empty($incident['thong_tin_khach'])): ?>
      <div>
        <label style="display: block; margin-bottom: 8px; font-size: 13px; font-weight: 600; color: var(--text-light); text-transform: uppercase;">
          Thông tin khách liên quan
        </label>
        <div style="background: white; padding: 16px; border: 1px solid var(--border); border-radius: 8px;">
          <p style="margin: 0; white-space: pre-wrap; line-height: 1.6;"><?= htmlspecialchars($incident['thong_tin_khach']) ?></p>
        </div>
      </div>
      <?php endif; ?>

      <!-- Loại sự cố -->
      <div>
        <label style="display: block; margin-bottom: 8px; font-size: 13px; font-weight: 600; color: var(--text-light); text-transform: uppercase;">
          Loại sự cố
        </label>
        <p style="margin: 0;">
          <?php if (!empty($incident['loai_su_co']) && isset($incidentTypes[$incident['loai_su_co']])): ?>
            <span style="background: #dbeafe; color: #1e40af; padding: 8px 16px; border-radius: 8px; font-size: 14px; font-weight: 600;">
              <i class="fas fa-tag" style="margin-right: 8px;"></i>
              <?= htmlspecialchars($incidentTypes[$incident['loai_su_co']]) ?>
            </span>
          <?php else: ?>
            <span style="color: var(--text-light);">-</span>
          <?php endif; ?>
        </p>
      </div>

      <!-- Mức độ -->
      <div>
        <label style="display: block; margin-bottom: 8px; font-size: 13px; font-weight: 600; color: var(--text-light); text-transform: uppercase;">
          Mức độ nghiêm trọng
        </label>
        <p style="margin: 0;">
          <?php if (!empty($incident['muc_do']) && isset($severityLevels[$incident['muc_do']])): ?>
            <?php $level = $severityLevels[$incident['muc_do']]; ?>
            <span style="background: <?= $level['color'] ?>20; color: <?= $level['color'] ?>; padding: 8px 16px; border-radius: 8px; font-size: 14px; font-weight: 600;">
              <i class="fas fa-exclamation-circle" style="margin-right: 8px;"></i>
              <?= htmlspecialchars($level['label']) ?>
            </span>
          <?php else: ?>
            <span style="color: var(--text-light);">-</span>
          <?php endif; ?>
        </p>
      </div>

      <!-- Mô tả -->
      <div>
        <label style="display: block; margin-bottom: 8px; font-size: 13px; font-weight: 600; color: var(--text-light); text-transform: uppercase;">
          Mô tả sự cố
        </label>
        <div style="background: white; padding: 16px; border: 1px solid var(--border); border-radius: 8px; min-height: 100px;">
          <?php if (!empty($incident['mo_ta'])): ?>
            <p style="margin: 0; white-space: pre-wrap; line-height: 1.6;"><?= htmlspecialchars($incident['mo_ta']) ?></p>
          <?php else: ?>
            <p style="margin: 0; color: var(--text-light);">-</p>
          <?php endif; ?>
        </div>
      </div>

      <!-- Gợi ý xử lý từ hệ thống -->
      <?php if (!empty($incident['goi_y_xu_ly'])): ?>
        <?php 
        $suggestion = json_decode($incident['goi_y_xu_ly'], true);
        if ($suggestion): 
        ?>
        <div>
          <label style="display: block; margin-bottom: 8px; font-size: 13px; font-weight: 600; color: var(--text-light); text-transform: uppercase;">
            Gợi ý xử lý từ hệ thống
          </label>
          <div style="background: #dbeafe; padding: 16px; border-left: 4px solid #3b82f6; border-radius: 8px;">
            <h4 style="margin: 0 0 12px 0; color: #1e40af;">
              <i class="fas fa-lightbulb"></i> <?= htmlspecialchars($suggestion['title'] ?? 'Gợi ý xử lý') ?>
            </h4>
            <ol style="margin: 10px 0; padding-left: 20px; line-height: 1.8;">
              <?php foreach ($suggestion['steps'] ?? [] as $step): ?>
                <li style="margin-bottom: 8px;"><?= htmlspecialchars($step) ?></li>
              <?php endforeach; ?>
            </ol>
            <?php if (!empty($suggestion['contact'])): ?>
              <p style="margin: 12px 0 0 0; font-weight: 600; color: #1e40af;">
                <i class="fas fa-phone"></i> Liên hệ: <?= htmlspecialchars($suggestion['contact']) ?>
              </p>
            <?php endif; ?>
            <?php if (!empty($suggestion['note'])): ?>
              <p style="margin: 8px 0 0 0; font-style: italic; color: #6b7280;">
                <?= htmlspecialchars($suggestion['note']) ?>
              </p>
            <?php endif; ?>
          </div>
        </div>
        <?php endif; ?>
      <?php endif; ?>
      
      <!-- Cách xử lý -->
      <div>
        <label style="display: block; margin-bottom: 8px; font-size: 13px; font-weight: 600; color: var(--text-light); text-transform: uppercase;">
          Cách xử lý thực tế
        </label>
        <div style="background: #d1fae5; padding: 16px; border: 1px solid var(--success); border-radius: 8px; min-height: 100px;">
          <?php if (!empty($incident['cach_xu_ly'])): ?>
            <p style="margin: 0; white-space: pre-wrap; line-height: 1.6;"><?= htmlspecialchars($incident['cach_xu_ly']) ?></p>
          <?php else: ?>
            <p style="margin: 0; color: var(--text-light);">-</p>
          <?php endif; ?>
        </div>
      </div>
      
      <!-- Hình ảnh đính kèm -->
      <?php 
      $images = [];
      if (!empty($incident['hinh_anh'])) {
          $images = is_array($incident['hinh_anh']) ? $incident['hinh_anh'] : json_decode($incident['hinh_anh'], true);
      }
      if (!empty($images) && count($images) > 0): 
      ?>
      <div>
        <label style="display: block; margin-bottom: 8px; font-size: 13px; font-weight: 600; color: var(--text-light); text-transform: uppercase;">
          Hình ảnh đính kèm (<?= count($images) ?> ảnh)
        </label>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 12px;">
          <?php foreach ($images as $image): ?>
            <div style="position: relative; aspect-ratio: 1; border-radius: 8px; overflow: hidden; border: 1px solid var(--border);">
              <img src="<?= BASE_URL . htmlspecialchars($image) ?>" alt="Hình ảnh sự cố" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;" onclick="openImageModal('<?= BASE_URL . htmlspecialchars($image) ?>')">
            </div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>
      
      <!-- Trạng thái gửi báo cáo -->
      <?php if (!empty($incident['da_gui_bao_cao'])): ?>
      <div style="background: #d1fae5; padding: 12px; border-radius: 8px; margin-top: 16px;">
        <p style="margin: 0; font-size: 13px; color: #065f46;">
          <i class="fas fa-check-circle"></i> 
          Đã gửi báo cáo cho công ty 
          <?php if (!empty($incident['ngay_gui_bao_cao'])): ?>
            vào <?= date('d/m/Y H:i', strtotime($incident['ngay_gui_bao_cao'])) ?>
          <?php endif; ?>
          <?php if (!empty($incident['nguoi_nhan_bao_cao'])): ?>
            (<?= htmlspecialchars($incident['nguoi_nhan_bao_cao']) ?>)
          <?php endif; ?>
        </p>
      </div>
      <?php endif; ?>

      <!-- Thông tin bổ sung -->
      <div style="background: var(--bg-light); padding: 16px; border-radius: 8px; margin-top: 8px;">
        <p style="margin: 0; font-size: 13px; color: var(--text-light);">
          <i class="fas fa-clock" style="margin-right: 8px;"></i>
          Tạo lúc: <?= date('d/m/Y H:i', strtotime($incident['ngay_tao'])) ?>
          <?php if (!empty($incident['ngay_cap_nhat']) && $incident['ngay_cap_nhat'] != $incident['ngay_tao']): ?>
            <br><i class="fas fa-edit" style="margin-right: 8px;"></i>
            Cập nhật lúc: <?= date('d/m/Y H:i', strtotime($incident['ngay_cap_nhat'])) ?>
          <?php endif; ?>
        </p>
      </div>
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

