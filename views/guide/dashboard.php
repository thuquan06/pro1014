<?php
// views/guide/dashboard.php
$todayCount = 0;
$weekCount = 0;
$now = strtotime('today');
$endWeek = strtotime('+6 day', $now);
foreach ($recentAssignments ?? [] as $a) {
  if (!empty($a['ngay_khoi_hanh'])) {
    $d = strtotime($a['ngay_khoi_hanh']);
    if ($d === $now) $todayCount++;
    if ($d >= $now && $d <= $endWeek) $weekCount++;
  }
}
?>

<div class="dashboard-container">
  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-icon green"><i class="fas fa-calendar-check"></i></div>
      <div class="stat-details">
        <h4><?= $stats['total'] ?? 0 ?></h4>
        <p>Tổng phân công</p>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon blue"><i class="fas fa-sun"></i></div>
      <div class="stat-details">
        <h4><?= $todayCount ?></h4>
        <p>Lịch hôm nay</p>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon orange"><i class="fas fa-calendar-week"></i></div>
      <div class="stat-details">
        <h4><?= $weekCount ?></h4>
        <p>Trong tuần</p>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon purple"><i class="fas fa-user-check"></i></div>
      <div class="stat-details">
        <h4><?= $stats['active'] ?? 0 ?></h4>
        <p>Đang phụ trách</p>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-header">
      <h3><i class="fas fa-bullhorn"></i> Thông báo từ điều hành</h3>
    </div>
    <div class="card-body">
      <?php if (!empty($announcements)): ?>
        <div style="display: flex; flex-direction: column; gap: 12px;">
          <?php foreach ($announcements as $announcement): ?>
            <div style="background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%); border-left: 4px solid #3b82f6; padding: 16px; border-radius: 8px; display: flex; align-items: start; gap: 12px; animation: slideIn 0.3s ease-out;">
              <div style="flex-shrink: 0; width: 40px; height: 40px; background: #3b82f6; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 18px;">
                <i class="fas fa-bell"></i>
              </div>
              <div style="flex: 1;">
                <p style="margin: 0; color: #1e40af; font-weight: 600; font-size: 14px; line-height: 1.5;">
                  <?= htmlspecialchars($announcement['message']) ?>
                </p>
                <?php if (!empty($announcement['assignment_id'])): ?>
                  <a href="?act=guide-assignment-detail&id=<?= $announcement['assignment_id'] ?>" 
                     style="display: inline-block; margin-top: 8px; color: #3b82f6; font-size: 13px; font-weight: 600; text-decoration: none;">
                    <i class="fas fa-arrow-right"></i> Xem chi tiết
                  </a>
                <?php endif; ?>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <p style="color: var(--text-light); margin:0;">Chưa có thông báo.</p>
      <?php endif; ?>
    </div>
  </div>

  <style>
    @keyframes slideIn {
      from {
        opacity: 0;
        transform: translateX(-10px);
      }
      to {
        opacity: 1;
        transform: translateX(0);
      }
    }
  </style>

  <div class="card">
    <div class="card-header">
      <h3><i class="fas fa-list"></i> Phân công sắp tới</h3>
      <a href="?act=guide-assignments" class="btn btn-primary btn-sm">
        <i class="fas fa-eye"></i> Xem tất cả
      </a>
    </div>
    <div class="card-body">
      <?php if (empty($recentAssignments)): ?>
        <p style="color: var(--text-light); text-align: center; padding: 20px;">
          <i class="fas fa-inbox" style="font-size: 48px; opacity: 0.3; margin-bottom: 12px; display: block;"></i>
          Chưa có phân công nào sắp tới
        </p>
      <?php else: ?>
        <div class="table-responsive">
          <table>
            <thead>
              <tr>
                <th>Tour</th>
                <th>Ngày khởi hành</th>
                <th>Vai trò</th>
                <th>Thời gian</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($recentAssignments as $assignment): ?>
                <tr>
                  <td>
                    <strong><?= htmlspecialchars($assignment['ten_tour'] ?? 'N/A') ?></strong>
                  </td>
                  <td>
                    <?php if ($assignment['ngay_khoi_hanh']): ?>
                      <?= date('d/m/Y', strtotime($assignment['ngay_khoi_hanh'])) ?>
                      <?php if ($assignment['gio_khoi_hanh']): ?>
                        <br><small style="color: var(--text-light);"><?= htmlspecialchars($assignment['gio_khoi_hanh']) ?></small>
                      <?php endif; ?>
                    <?php else: ?>
                      N/A
                    <?php endif; ?>
                  </td>
                  <td><?= htmlspecialchars($assignment['vai_tro'] ?? 'HDV chính') ?></td>
                  <td>
                    <?php if ($assignment['ngay_bat_dau'] && $assignment['ngay_ket_thuc']): ?>
                      <?= date('d/m/Y', strtotime($assignment['ngay_bat_dau'])) ?>
                      <br><small style="color: var(--text-light);">đến</small><br>
                      <?= date('d/m/Y', strtotime($assignment['ngay_ket_thuc'])) ?>
                    <?php else: ?>
                      N/A
                    <?php endif; ?>
                  </td>
                  <td>
                    <?php if ($assignment['trang_thai'] == 1): ?>
                      <span style="background: #d1fae5; color: #065f46; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                        <i class="fas fa-check-circle"></i> Hoạt động
                      </span>
                    <?php else: ?>
                      <span style="background: #fee2e2; color: #991b1b; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                        <i class="fas fa-times-circle"></i> Tạm dừng
                      </span>
                    <?php endif; ?>
                  </td>
                  <td>
                    <a href="?act=guide-assignment-detail&id=<?= $assignment['id'] ?>" class="btn btn-primary btn-sm">
                      <i class="fas fa-eye"></i> Chi tiết
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <div class="card">
    <div class="card-header">
      <h3><i class="fas fa-user"></i> Thông tin cá nhân</h3>
      <a href="?act=guide-profile" class="btn btn-primary btn-sm">
        <i class="fas fa-edit"></i> Cập nhật
      </a>
    </div>
    <div class="card-body">
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
        <div>
          <strong style="color: var(--text-light); font-size: 12px; text-transform: uppercase;">Họ tên</strong>
          <p style="font-size: 16px; font-weight: 600; margin-top: 4px;"><?= htmlspecialchars($guide['ho_ten'] ?? 'N/A') ?></p>
        </div>
        <div>
          <strong style="color: var(--text-light); font-size: 12px; text-transform: uppercase;">Email</strong>
          <p style="font-size: 16px; font-weight: 600; margin-top: 4px;"><?= htmlspecialchars($guide['email'] ?? 'N/A') ?></p>
        </div>
        <div>
          <strong style="color: var(--text-light); font-size: 12px; text-transform: uppercase;">Số điện thoại</strong>
          <p style="font-size: 16px; font-weight: 600; margin-top: 4px;"><?= htmlspecialchars($guide['so_dien_thoai'] ?? 'N/A') ?></p>
        </div>
        <div>
          <strong style="color: var(--text-light); font-size: 12px; text-transform: uppercase;">Kinh nghiệm</strong>
          <p style="font-size: 16px; font-weight: 600; margin-top: 4px;"><?= htmlspecialchars($guide['kinh_nghiem'] ?? 0) ?> năm</p>
        </div>
        <div>
          <strong style="color: var(--text-light); font-size: 12px; text-transform: uppercase;">Đánh giá</strong>
          <p style="font-size: 16px; font-weight: 600; margin-top: 4px;">
            <?php if (!empty($guide['danh_gia'])): ?>
              <?= number_format($guide['danh_gia'], 1) ?> <i class="fas fa-star" style="color: #f59e0b;"></i>
            <?php else: ?>
              Chưa có đánh giá
            <?php endif; ?>
          </p>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
.dashboard-container {
  max-width: 1400px;
  margin: 0 auto;
}
</style>


