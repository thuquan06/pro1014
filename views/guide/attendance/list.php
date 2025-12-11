<?php
// views/guide/attendance/list.php
?>

<div class="card">
  <div class="card-header">
    <h3><i class="fas fa-user-check"></i> Điểm danh tại điểm dừng nghỉ</h3>
    <div style="display: flex; gap: 12px;">
      <a href="?act=guide-attendance-create&assignment_id=<?= $assignment['id'] ?>" class="btn btn-sm btn-primary">
        <i class="fas fa-plus"></i> Điểm danh mới
      </a>
      <a href="?act=guide-assignment-detail&id=<?= $assignment['id'] ?>" class="btn btn-sm" style="background: var(--bg-light); color: var(--text-dark);">
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

    <!-- Schedule Days with Rest Stops -->
    <?php if (!empty($scheduleDays)): ?>
      <div style="margin-bottom: 32px;">
        <h4 style="margin-bottom: 16px; color: var(--text-dark);">
          <i class="fas fa-route"></i> Các điểm dừng nghỉ trong tour
        </h4>
        <div style="display: grid; gap: 16px;">
          <?php foreach ($scheduleDays as $day): ?>
            <?php if (!empty($day['noinghi'])): ?>
              <div style="background: white; border: 1px solid var(--border); border-radius: 10px; padding: 20px;">
                <div style="display: flex; justify-content: space-between; align-items: start; flex-wrap: wrap; gap: 16px;">
                  <div style="flex: 1;">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                      <span style="background: var(--primary); color: white; padding: 6px 12px; border-radius: 8px; font-weight: 600; font-size: 14px;">
                        Ngày <?= $day['ngay_thu'] ?>
                      </span>
                      <?php if (!empty($day['tieude'])): ?>
                        <strong style="font-size: 16px; color: var(--text-dark);">
                          <?= htmlspecialchars($day['tieude']) ?>
                        </strong>
                      <?php endif; ?>
                    </div>
                    <p style="margin: 0; color: var(--text-dark); font-size: 15px;">
                      <i class="fas fa-bed" style="color: var(--primary);"></i> 
                      <strong>Điểm nghỉ:</strong> <?= htmlspecialchars($day['noinghi']) ?>
                    </p>
                  </div>
                  <div style="display: flex; gap: 12px; align-items: center;">
                    <?php 
                    $attendance = $attendanceMap[$day['id']] ?? null;
                    if ($attendance): 
                    ?>
                      <span style="background: #d1fae5; color: #065f46; padding: 8px 16px; border-radius: 8px; font-size: 14px; font-weight: 600;">
                        <i class="fas fa-check-circle"></i> Đã điểm danh
                      </span>
                      <span style="color: var(--text-light); font-size: 13px;">
                        <?= date('d/m/Y H:i', strtotime($attendance['ngay_diem_danh'] . ' ' . $attendance['gio_diem_danh'])) ?>
                      </span>
                      <a href="?act=guide-attendance-detail&id=<?= $attendance['id'] ?>" class="btn btn-sm" style="background: var(--info); color: white;">
                        <i class="fas fa-eye"></i> Xem
                      </a>
                      <a href="?act=guide-attendance-edit&id=<?= $attendance['id'] ?>" class="btn btn-sm" style="background: var(--warning); color: white;">
                        <i class="fas fa-edit"></i> Sửa
                      </a>
                    <?php else: ?>
                      <a href="?act=guide-attendance-create&assignment_id=<?= $assignment['id'] ?>&schedule_id=<?= $day['id'] ?>" class="btn btn-sm btn-primary">
                        <i class="fas fa-user-check"></i> Điểm danh
                      </a>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            <?php endif; ?>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endif; ?>

    <!-- Attendance Records List -->
    <?php if (empty($attendances)): ?>
      <div style="text-align: center; padding: 60px 20px; color: var(--text-light);">
        <i class="fas fa-user-check" style="font-size: 64px; margin-bottom: 16px; opacity: 0.3;"></i>
        <p style="font-size: 16px; margin-bottom: 24px;">Chưa có điểm danh nào</p>
        <a href="?act=guide-attendance-create&assignment_id=<?= $assignment['id'] ?>" class="btn btn-primary">
          <i class="fas fa-plus"></i> Tạo điểm danh đầu tiên
        </a>
      </div>
    <?php else: ?>
      <div>
        <h4 style="margin-bottom: 16px; color: var(--text-dark);">
          <i class="fas fa-history"></i> Lịch sử điểm danh
        </h4>
        <div class="table-responsive">
          <table>
            <thead>
              <tr>
                <th>Ngày/giờ</th>
                <th>Điểm nghỉ</th>
                <th>Ngày thứ</th>
                <th>Có mặt</th>
                <th>Vắng mặt</th>
                <th>Thao tác</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($attendances as $attendance): ?>
                <tr>
                  <td>
                    <strong><?= date('d/m/Y', strtotime($attendance['ngay_diem_danh'])) ?></strong>
                    <br><small style="color: var(--text-light);"><?= date('H:i', strtotime($attendance['gio_diem_danh'])) ?></small>
                  </td>
                  <td>
                    <strong><?= htmlspecialchars($attendance['diem_nghi'] ?? 'N/A') ?></strong>
                  </td>
                  <td>
                    <span style="background: var(--primary); color: white; padding: 4px 12px; border-radius: 8px; font-size: 13px;">
                      Ngày <?= $attendance['ngay_thu'] ?? 'N/A' ?>
                    </span>
                  </td>
                  <td>
                    <span style="background: #d1fae5; color: #065f46; padding: 4px 12px; border-radius: 8px; font-size: 13px; font-weight: 600;">
                      <i class="fas fa-check"></i> <?= $attendance['so_nguoi_co_mat'] ?? 0 ?>
                    </span>
                  </td>
                  <td>
                    <?php if (($attendance['so_nguoi_vang_mat'] ?? 0) > 0): ?>
                      <span style="background: #fee2e2; color: #991b1b; padding: 4px 12px; border-radius: 8px; font-size: 13px; font-weight: 600;">
                        <i class="fas fa-times"></i> <?= $attendance['so_nguoi_vang_mat'] ?>
                      </span>
                    <?php else: ?>
                      <span style="color: var(--text-light);">-</span>
                    <?php endif; ?>
                  </td>
                  <td>
                    <div style="display: flex; gap: 8px;">
                      <a href="?act=guide-attendance-detail&id=<?= $attendance['id'] ?>" class="btn btn-sm" style="background: var(--info); color: white; padding: 6px 12px;">
                        <i class="fas fa-eye"></i>
                      </a>
                      <a href="?act=guide-attendance-edit&id=<?= $attendance['id'] ?>" class="btn btn-sm" style="background: var(--warning); color: white; padding: 6px 12px;">
                        <i class="fas fa-edit"></i>
                      </a>
                      <a href="?act=guide-attendance-delete&id=<?= $attendance['id'] ?>" class="btn btn-sm" style="background: var(--danger); color: white; padding: 6px 12px;" onclick="return confirm('Bạn có chắc muốn xóa điểm danh này?')">
                        <i class="fas fa-trash"></i>
                      </a>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    <?php endif; ?>
  </div>
</div>

