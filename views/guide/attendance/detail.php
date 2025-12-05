<?php
// views/guide/attendance/detail.php
?>

<div class="card">
  <div class="card-header">
    <h3><i class="fas fa-user-check"></i> Chi tiết điểm danh</h3>
    <div style="display: flex; gap: 12px;">
      <a href="?act=guide-attendance-edit&id=<?= $attendance['id'] ?>" class="btn btn-sm" style="background: var(--warning); color: white;">
        <i class="fas fa-edit"></i> Sửa
      </a>
      <a href="?act=guide-attendance&assignment_id=<?= $assignment['id'] ?>" class="btn btn-sm" style="background: var(--bg-light); color: var(--text-dark);">
        <i class="fas fa-arrow-left"></i> Quay lại
      </a>
    </div>
  </div>
  <div class="card-body">
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

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px; margin-bottom: 24px;">
      <div>
        <strong style="color: var(--text-light); font-size: 12px; text-transform: uppercase;">Điểm nghỉ</strong>
        <p style="font-size: 18px; font-weight: 700; margin-top: 4px; color: var(--primary);">
          <?= htmlspecialchars($attendance['diem_nghi'] ?? 'N/A') ?>
        </p>
      </div>
      
      <div>
        <strong style="color: var(--text-light); font-size: 12px; text-transform: uppercase;">Ngày thứ</strong>
        <p style="font-size: 16px; font-weight: 600; margin-top: 4px;">
          <span style="background: var(--primary); color: white; padding: 6px 12px; border-radius: 8px; font-size: 14px;">
            Ngày <?= $attendance['ngay_thu'] ?? 'N/A' ?>
          </span>
        </p>
      </div>
      
      <div>
        <strong style="color: var(--text-light); font-size: 12px; text-transform: uppercase;">Ngày điểm danh</strong>
        <p style="font-size: 16px; font-weight: 600; margin-top: 4px;">
          <?= date('d/m/Y', strtotime($attendance['ngay_diem_danh'])) ?>
        </p>
      </div>
      
      <div>
        <strong style="color: var(--text-light); font-size: 12px; text-transform: uppercase;">Giờ điểm danh</strong>
        <p style="font-size: 16px; font-weight: 600; margin-top: 4px;">
          <?= date('H:i', strtotime($attendance['gio_diem_danh'])) ?>
        </p>
      </div>
      
      <div>
        <strong style="color: var(--text-light); font-size: 12px; text-transform: uppercase;">Số người có mặt</strong>
        <p style="font-size: 18px; font-weight: 700; margin-top: 4px; color: var(--success);">
          <span style="background: #d1fae5; color: #065f46; padding: 6px 16px; border-radius: 12px; font-size: 14px; font-weight: 600;">
            <i class="fas fa-check-circle"></i> <?= $attendance['so_nguoi_co_mat'] ?? 0 ?> người
          </span>
        </p>
      </div>
      
      <div>
        <strong style="color: var(--text-light); font-size: 12px; text-transform: uppercase;">Số người vắng mặt</strong>
        <p style="font-size: 18px; font-weight: 700; margin-top: 4px;">
          <?php if (($attendance['so_nguoi_vang_mat'] ?? 0) > 0): ?>
            <span style="background: #fee2e2; color: #991b1b; padding: 6px 16px; border-radius: 12px; font-size: 14px; font-weight: 600;">
              <i class="fas fa-times-circle"></i> <?= $attendance['so_nguoi_vang_mat'] ?> người
            </span>
          <?php else: ?>
            <span style="color: var(--text-light);">0 người</span>
          <?php endif; ?>
        </p>
      </div>
    </div>

    <!-- Danh sách có mặt -->
    <?php if (!empty($attendance['danh_sach_co_mat']) && count($attendance['danh_sach_co_mat']) > 0): ?>
    <div style="margin-bottom: 24px; padding-top: 24px; border-top: 1px solid var(--border);">
      <strong style="color: var(--text-dark); font-size: 14px; text-transform: uppercase; margin-bottom: 12px; display: block;">
        <i class="fas fa-check-circle" style="color: var(--success);"></i> Danh sách có mặt (<?= count($attendance['danh_sach_co_mat']) ?> người)
      </strong>
      <div style="background: #f0fdf4; padding: 16px; border-radius: 8px; border-left: 4px solid var(--success);">
        <ul style="margin: 0; padding-left: 20px; columns: 2; column-gap: 24px;">
          <?php foreach ($attendance['danh_sach_co_mat'] as $person): ?>
            <li style="margin-bottom: 8px; color: var(--text-dark);">
              <?= htmlspecialchars($person) ?>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
    <?php endif; ?>

    <!-- Danh sách vắng mặt -->
    <?php if (!empty($attendance['danh_sach_vang_mat']) && count($attendance['danh_sach_vang_mat']) > 0): ?>
    <div style="margin-bottom: 24px; padding-top: 24px; border-top: 1px solid var(--border);">
      <strong style="color: var(--text-dark); font-size: 14px; text-transform: uppercase; margin-bottom: 12px; display: block;">
        <i class="fas fa-times-circle" style="color: var(--danger);"></i> Danh sách vắng mặt (<?= count($attendance['danh_sach_vang_mat']) ?> người)
      </strong>
      <div style="background: #fef2f2; padding: 16px; border-radius: 8px; border-left: 4px solid var(--danger);">
        <ul style="margin: 0; padding-left: 20px; columns: 2; column-gap: 24px;">
          <?php foreach ($attendance['danh_sach_vang_mat'] as $person): ?>
            <li style="margin-bottom: 8px; color: var(--text-dark);">
              <?= htmlspecialchars($person) ?>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
    <?php endif; ?>

    <!-- Ghi chú -->
    <?php if (!empty($attendance['ghi_chu'])): ?>
    <div style="margin-top: 24px; padding-top: 24px; border-top: 1px solid var(--border);">
      <strong style="color: var(--text-light); font-size: 12px; text-transform: uppercase;">Ghi chú</strong>
      <p style="margin-top: 8px; color: var(--text-dark); line-height: 1.6;">
        <?= nl2br(htmlspecialchars($attendance['ghi_chu'])) ?>
      </p>
    </div>
    <?php endif; ?>
  </div>
</div>

