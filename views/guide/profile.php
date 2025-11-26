<?php
// views/guide/profile.php
?>

<div class="card">
  <div class="card-header">
    <h3><i class="fas fa-user"></i> Thông tin cá nhân</h3>
  </div>
  <div class="card-body">
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px; margin-bottom: 32px;">
      <div>
        <strong style="color: var(--text-light); font-size: 12px; text-transform: uppercase;">Họ tên</strong>
        <p style="font-size: 18px; font-weight: 700; margin-top: 4px; color: var(--primary);">
          <?= htmlspecialchars($guide['ho_ten'] ?? 'N/A') ?>
        </p>
      </div>
      
      <div>
        <strong style="color: var(--text-light); font-size: 12px; text-transform: uppercase;">Email</strong>
        <p style="font-size: 16px; font-weight: 600; margin-top: 4px;">
          <?= htmlspecialchars($guide['email'] ?? 'N/A') ?>
        </p>
      </div>
      
      <div>
        <strong style="color: var(--text-light); font-size: 12px; text-transform: uppercase;">Số điện thoại</strong>
        <p style="font-size: 16px; font-weight: 600; margin-top: 4px;">
          <?= htmlspecialchars($guide['so_dien_thoai'] ?? 'Chưa cập nhật') ?>
        </p>
      </div>
      
      <div>
        <strong style="color: var(--text-light); font-size: 12px; text-transform: uppercase;">CMND/CCCD</strong>
        <p style="font-size: 16px; font-weight: 600; margin-top: 4px;">
          <?= htmlspecialchars($guide['cmnd_cccd'] ?? 'Chưa cập nhật') ?>
        </p>
      </div>
      
      <div>
        <strong style="color: var(--text-light); font-size: 12px; text-transform: uppercase;">Địa chỉ</strong>
        <p style="font-size: 16px; font-weight: 600; margin-top: 4px;">
          <?= htmlspecialchars($guide['dia_chi'] ?? 'Chưa cập nhật') ?>
        </p>
      </div>
      
      <div>
        <strong style="color: var(--text-light); font-size: 12px; text-transform: uppercase;">Kinh nghiệm</strong>
        <p style="font-size: 16px; font-weight: 600; margin-top: 4px;">
          <?= htmlspecialchars($guide['kinh_nghiem'] ?? 0) ?> năm
        </p>
      </div>
      
      <div>
        <strong style="color: var(--text-light); font-size: 12px; text-transform: uppercase;">Đánh giá</strong>
        <p style="font-size: 18px; font-weight: 700; margin-top: 4px; color: var(--warning);">
          <?php if (!empty($guide['danh_gia'])): ?>
            <?= number_format($guide['danh_gia'], 1) ?> 
            <i class="fas fa-star" style="color: #f59e0b;"></i>
          <?php else: ?>
            Chưa có đánh giá
          <?php endif; ?>
        </p>
      </div>
      
      <div>
        <strong style="color: var(--text-light); font-size: 12px; text-transform: uppercase;">Trạng thái</strong>
        <p style="margin-top: 4px;">
          <?php if ($guide['trang_thai'] == 1): ?>
            <span style="background: #d1fae5; color: #065f46; padding: 6px 16px; border-radius: 12px; font-size: 14px; font-weight: 600;">
              <i class="fas fa-check-circle"></i> Hoạt động
            </span>
          <?php else: ?>
            <span style="background: #fee2e2; color: #991b1b; padding: 6px 16px; border-radius: 12px; font-size: 14px; font-weight: 600;">
              <i class="fas fa-times-circle"></i> Tạm dừng
            </span>
          <?php endif; ?>
        </p>
      </div>
    </div>

    <?php if (!empty($guide['ky_nang']) && is_array($guide['ky_nang']) && count($guide['ky_nang']) > 0): ?>
      <div style="margin-top: 32px; padding-top: 32px; border-top: 1px solid var(--border);">
        <h4 style="font-size: 16px; font-weight: 700; margin-bottom: 16px; color: var(--text-dark);">
          <i class="fas fa-star" style="color: var(--primary);"></i> Kỹ năng
        </h4>
        <div style="display: flex; flex-wrap: wrap; gap: 8px;">
          <?php foreach ($guide['ky_nang'] as $skill): ?>
            <span style="background: #d1fae5; color: #065f46; padding: 6px 14px; border-radius: 20px; font-size: 13px; font-weight: 500;">
              <?= htmlspecialchars($skill) ?>
            </span>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endif; ?>

    <?php if (!empty($guide['tuyen_chuyen']) && is_array($guide['tuyen_chuyen']) && count($guide['tuyen_chuyen']) > 0): ?>
      <div style="margin-top: 24px; padding-top: 24px; border-top: 1px solid var(--border);">
        <h4 style="font-size: 16px; font-weight: 700; margin-bottom: 16px; color: var(--text-dark);">
          <i class="fas fa-route" style="color: var(--primary);"></i> Tuyến chuyên
        </h4>
        <div style="display: flex; flex-wrap: wrap; gap: 8px;">
          <?php foreach ($guide['tuyen_chuyen'] as $route): ?>
            <span style="background: #dbeafe; color: #1e40af; padding: 6px 14px; border-radius: 20px; font-size: 13px; font-weight: 500;">
              <?= htmlspecialchars($route) ?>
            </span>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endif; ?>

    <?php if (!empty($guide['ngon_ngu']) && is_array($guide['ngon_ngu']) && count($guide['ngon_ngu']) > 0): ?>
      <div style="margin-top: 24px; padding-top: 24px; border-top: 1px solid var(--border);">
        <h4 style="font-size: 16px; font-weight: 700; margin-bottom: 16px; color: var(--text-dark);">
          <i class="fas fa-language" style="color: var(--primary);"></i> Ngôn ngữ
        </h4>
        <div style="display: flex; flex-wrap: wrap; gap: 8px;">
          <?php foreach ($guide['ngon_ngu'] as $lang): ?>
            <span style="background: #e9d5ff; color: #9333ea; padding: 6px 14px; border-radius: 20px; font-size: 13px; font-weight: 500;">
              <?= htmlspecialchars($lang) ?>
            </span>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endif; ?>

    <?php if (!empty($guide['ghi_chu'])): ?>
      <div style="margin-top: 32px; padding-top: 32px; border-top: 1px solid var(--border);">
        <h4 style="font-size: 16px; font-weight: 700; margin-bottom: 16px; color: var(--text-dark);">
          <i class="fas fa-sticky-note" style="color: var(--primary);"></i> Ghi chú
        </h4>
        <p style="color: var(--text-dark); line-height: 1.6; padding: 16px; background: var(--bg-light); border-radius: 8px;">
          <?= nl2br(htmlspecialchars($guide['ghi_chu'])) ?>
        </p>
      </div>
    <?php endif; ?>
  </div>
</div>


