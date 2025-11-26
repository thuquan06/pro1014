<?php
// views/guide/assignments/list.php
?>

<div class="card">
  <div class="card-header">
    <h3><i class="fas fa-calendar-check"></i> Danh sách phân công</h3>
  </div>
  <div class="card-body">
    <!-- Filters -->
    <form method="GET" action="?act=guide-assignments" style="margin-bottom: 24px; padding: 20px; background: var(--bg-light); border-radius: 10px;">
      <input type="hidden" name="act" value="guide-assignments">
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; align-items: end;">
        <div>
          <label style="display: block; margin-bottom: 8px; font-size: 13px; font-weight: 600; color: var(--text-dark);">Trạng thái</label>
          <select name="trang_thai" style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px;">
            <option value="">Tất cả</option>
            <option value="1" <?= (isset($filters['trang_thai']) && $filters['trang_thai'] == 1) ? 'selected' : '' ?>>Hoạt động</option>
            <option value="0" <?= (isset($filters['trang_thai']) && $filters['trang_thai'] == 0) ? 'selected' : '' ?>>Tạm dừng</option>
          </select>
        </div>
        <div>
          <label style="display: block; margin-bottom: 8px; font-size: 13px; font-weight: 600; color: var(--text-dark);">Từ ngày</label>
          <input type="date" name="from_date" value="<?= htmlspecialchars($filters['from_date'] ?? '') ?>" style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px;">
        </div>
        <div>
          <label style="display: block; margin-bottom: 8px; font-size: 13px; font-weight: 600; color: var(--text-dark);">Đến ngày</label>
          <input type="date" name="to_date" value="<?= htmlspecialchars($filters['to_date'] ?? '') ?>" style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px;">
        </div>
        <div>
          <button type="submit" class="btn btn-primary" style="width: 100%;">
            <i class="fas fa-search"></i> Tìm kiếm
          </button>
        </div>
      </div>
    </form>

    <!-- Assignments List -->
    <?php if (empty($assignments)): ?>
      <div style="text-align: center; padding: 60px 20px; color: var(--text-light);">
        <i class="fas fa-calendar-times" style="font-size: 64px; margin-bottom: 16px; opacity: 0.3;"></i>
        <p style="font-size: 16px; margin-bottom: 24px;">Chưa có phân công nào</p>
      </div>
    <?php else: ?>
      <div class="table-responsive">
        <table>
          <thead>
            <tr>
              <th>Tour</th>
              <th>Ngày khởi hành</th>
              <th>Vai trò</th>
              <th>Thời gian làm việc</th>
              <th>Lương</th>
              <th>Trạng thái</th>
              <th>Thao tác</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($assignments as $assignment): ?>
              <tr>
                <td>
                  <strong><?= htmlspecialchars($assignment['ten_tour'] ?? 'N/A') ?></strong>
                  <?php if ($assignment['diem_tap_trung']): ?>
                    <br><small style="color: var(--text-light);"><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($assignment['diem_tap_trung']) ?></small>
                  <?php endif; ?>
                </td>
                <td>
                  <?php if ($assignment['ngay_khoi_hanh']): ?>
                    <strong><?= date('d/m/Y', strtotime($assignment['ngay_khoi_hanh'])) ?></strong>
                    <?php if ($assignment['gio_khoi_hanh']): ?>
                      <br><small style="color: var(--text-light);"><?= htmlspecialchars($assignment['gio_khoi_hanh']) ?></small>
                    <?php endif; ?>
                  <?php else: ?>
                    <span style="color: var(--text-light);">Chưa xác định</span>
                  <?php endif; ?>
                </td>
                <td>
                  <?= htmlspecialchars($assignment['vai_tro'] ?? 'HDV chính') ?>
                </td>
                <td>
                  <?php if ($assignment['ngay_bat_dau'] && $assignment['ngay_ket_thuc']): ?>
                    Từ <strong><?= date('d/m/Y', strtotime($assignment['ngay_bat_dau'])) ?></strong><br>
                    Đến <strong><?= date('d/m/Y', strtotime($assignment['ngay_ket_thuc'])) ?></strong>
                    <?php
                    $days = (strtotime($assignment['ngay_ket_thuc']) - strtotime($assignment['ngay_bat_dau'])) / (60 * 60 * 24);
                    if ($days > 0) {
                      echo '<br><small style="color: var(--text-light);">(' . ($days + 1) . ' ngày)</small>';
                    }
                    ?>
                  <?php else: ?>
                    <span style="color: var(--text-light);">Chưa cập nhật</span>
                  <?php endif; ?>
                </td>
                <td>
                  <?php if (!empty($assignment['luong'])): ?>
                    <strong style="color: var(--success);"><?= number_format($assignment['luong'], 0, ',', '.') ?> đ</strong>
                  <?php else: ?>
                    <span style="color: var(--text-light);">Chưa cập nhật</span>
                  <?php endif; ?>
                </td>
                <td>
                  <?php if ($assignment['trang_thai'] == 1): ?>
                    <span style="background: #d1fae5; color: #065f46; padding: 6px 16px; border-radius: 12px; font-size: 13px; font-weight: 600;">
                      <i class="fas fa-check-circle"></i> Hoạt động
                    </span>
                  <?php else: ?>
                    <span style="background: #fee2e2; color: #991b1b; padding: 6px 16px; border-radius: 12px; font-size: 13px; font-weight: 600;">
                      <i class="fas fa-times-circle"></i> Tạm dừng
                    </span>
                  <?php endif; ?>
                </td>
                <td>
                  <div style="display: flex; gap: 8px;">
                    <a href="?act=guide-assignment-detail&id=<?= $assignment['id'] ?>" class="btn btn-sm" style="background: var(--info); color: white; padding: 6px 12px;">
                      <i class="fas fa-eye"></i> Chi tiết
                    </a>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
</div>
