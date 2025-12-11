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
            <th>Mã lịch</th>
            <th>Tour</th>
            <th>Ngày khởi hành</th>
            <th>Vai trò</th>
            <th>Số khách</th>
            <th>Điểm tập trung</th>
            <th>Nhận</th>
            <th>Trạng thái</th>
            <th>Thao tác</th>
          </tr>
          </thead>
          <tbody>
            <?php foreach ($assignments as $assignment): ?>
              <tr>
                <td><strong><?= htmlspecialchars($assignment['id_lich_khoi_hanh'] ?? $assignment['id'] ?? 'N/A') ?></strong></td>
                <td>
                  <strong><?= htmlspecialchars($assignment['ten_tour'] ?? 'N/A') ?></strong>
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
                <td><?= htmlspecialchars($assignment['vai_tro'] ?? 'HDV chính') ?></td>
                <td>
                  <?php if (!empty($assignment['tong_khach'])): ?>
                    <strong><?= (int)$assignment['tong_khach'] ?></strong>
                  <?php else: ?>
                    <span style="color: var(--text-light);">0</span>
                  <?php endif; ?>
                </td>
                <td>
                  <?php if ($assignment['diem_tap_trung']): ?>
                    <small style="color: var(--text-dark);"><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($assignment['diem_tap_trung']) ?></small>
                  <?php else: ?>
                    <span style="color: var(--text-light);">Chưa cập nhật</span>
                  <?php endif; ?>
                </td>
                <td>
                  <span style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); color: #92400e; padding: 8px 16px; border-radius: 8px; font-weight: 600; font-size: 13px; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 4px rgba(146, 64, 14, 0.1);">
                    <i class="fas fa-hourglass-half"></i> Chưa xác nhận
                  </span>
                </td>
                <td>
                  <?php
                    $st = $assignment['trang_thai_hien_thi'] ?? 'Chưa xác định';
                    $map = [
                      'Ready' => ['bg' => 'linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%)', 'color' => '#1d4ed8', 'icon' => 'clock', 'shadow' => 'rgba(29, 78, 216, 0.1)'],
                      'Đang diễn ra' => ['bg' => 'linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%)', 'color' => '#065f46', 'icon' => 'play', 'shadow' => 'rgba(6, 95, 70, 0.1)'],
                      'Hoàn thành' => ['bg' => 'linear-gradient(135deg, #e5e7eb 0%, #d1d5db 100%)', 'color' => '#374151', 'icon' => 'check', 'shadow' => 'rgba(55, 65, 81, 0.1)'],
                      'Chưa xác định' => ['bg' => 'linear-gradient(135deg, #fee2e2 0%, #fecaca 100%)', 'color' => '#991b1b', 'icon' => 'question', 'shadow' => 'rgba(153, 27, 27, 0.1)']
                    ];
                    $cfg = $map[$st] ?? $map['Chưa xác định'];
                  ?>
                  <span style="background: <?= $cfg['bg'] ?>; color: <?= $cfg['color'] ?>; padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 4px <?= $cfg['shadow'] ?>;">
                    <i class="fas fa-<?= $cfg['icon'] ?>"></i> <?= htmlspecialchars($st) ?>
                  </span>
                </td>
                <td>
                  <div style="display: flex; gap: 8px;">
                    <a href="?act=guide-assignment-confirm&id=<?= $assignment['id'] ?>" class="btn btn-sm" style="background: var(--success); color: white; padding: 6px 12px;" onclick="return confirm('Bạn có chắc muốn xác nhận nhận tour này?')">
                      <i class="fas fa-check"></i> Xác nhận
                    </a>
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
