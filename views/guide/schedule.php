<?php
// views/guide/schedule.php
?>

<div class="card">
  <div class="card-header">
    <h3><i class="fas fa-calendar-alt"></i> Lịch làm việc</h3>
  </div>
  <div class="card-body">
    <?php if (empty($assignments)): ?>
      <p style="color: var(--text-light); text-align: center; padding: 40px;">
        <i class="fas fa-calendar-times" style="font-size: 64px; opacity: 0.3; margin-bottom: 16px; display: block;"></i>
        Chưa có lịch làm việc nào
      </p>
    <?php else: ?>
      <div style="display: grid; gap: 16px;">
        <?php foreach ($assignments as $assignment): ?>
          <div style="background: white; border: 2px solid var(--border); border-radius: 12px; padding: 20px; transition: all 0.2s;">
            <div style="display: flex; justify-content: space-between; align-items: start; flex-wrap: wrap; gap: 16px;">
              <div style="flex: 1; min-width: 250px;">
                <h4 style="font-size: 18px; font-weight: 700; color: var(--primary); margin-bottom: 8px;">
                  <?= htmlspecialchars($assignment['ten_tour'] ?? 'N/A') ?>
                </h4>
                <div style="display: flex; flex-direction: column; gap: 8px; color: var(--text-dark);">
                  <div>
                    <i class="fas fa-calendar" style="color: var(--text-light); width: 20px;"></i>
                    <strong>Ngày khởi hành:</strong> 
                    <?php if ($assignment['ngay_khoi_hanh']): ?>
                      <?= date('d/m/Y', strtotime($assignment['ngay_khoi_hanh'])) ?>
                      <?php if ($assignment['gio_khoi_hanh']): ?>
                        - <?= htmlspecialchars($assignment['gio_khoi_hanh']) ?>
                      <?php endif; ?>
                    <?php else: ?>
                      Chưa cập nhật
                    <?php endif; ?>
                  </div>
                  
                  <div>
                    <i class="fas fa-map-marker-alt" style="color: var(--text-light); width: 20px;"></i>
                    <strong>Điểm tập trung:</strong> 
                    <?= htmlspecialchars($assignment['diem_tap_trung'] ?? 'Chưa cập nhật') ?>
                  </div>
                  
                  <div>
                    <i class="fas fa-user-tag" style="color: var(--text-light); width: 20px;"></i>
                    <strong>Vai trò:</strong> <?= htmlspecialchars($assignment['vai_tro'] ?? 'HDV chính') ?>
                  </div>
                  
                  <?php if ($assignment['ngay_bat_dau'] && $assignment['ngay_ket_thuc']): ?>
                    <div>
                      <i class="fas fa-clock" style="color: var(--text-light); width: 20px;"></i>
                      <strong>Thời gian làm việc:</strong> 
                      Từ <?= date('d/m/Y', strtotime($assignment['ngay_bat_dau'])) ?> 
                      đến <?= date('d/m/Y', strtotime($assignment['ngay_ket_thuc'])) ?>
                      <?php
                      $days = (strtotime($assignment['ngay_ket_thuc']) - strtotime($assignment['ngay_bat_dau'])) / (60 * 60 * 24);
                      if ($days > 0) {
                        echo '<span style="color: var(--text-light);">(' . ($days + 1) . ' ngày)</span>';
                      }
                      ?>
                    </div>
                  <?php endif; ?>
                  
                  <?php if (!empty($assignment['luong'])): ?>
                    <div>
                      <i class="fas fa-money-bill-wave" style="color: var(--text-light); width: 20px;"></i>
                      <strong>Lương:</strong> 
                      <span style="color: var(--success); font-weight: 700;">
                        <?= number_format($assignment['luong'], 0, ',', '.') ?> đ
                      </span>
                    </div>
                  <?php endif; ?>
                </div>
              </div>
              
              <div style="display: flex; flex-direction: column; gap: 12px; align-items: end;">
                  <?php
                    $st = $assignment['trang_thai_hien_thi'] ?? 'Chưa xác định';
                    $map = [
                      'Ready' => ['bg' => 'linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%)', 'color' => '#1d4ed8', 'icon' => 'clock', 'shadow' => 'rgba(29, 78, 216, 0.1)'],
                      'Đang diễn ra' => ['bg' => 'linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%)', 'color' => '#065f46', 'icon' => 'play-circle', 'shadow' => 'rgba(6, 95, 70, 0.1)'],
                      'Hoàn thành' => ['bg' => 'linear-gradient(135deg, #e5e7eb 0%, #d1d5db 100%)', 'color' => '#374151', 'icon' => 'check-circle', 'shadow' => 'rgba(55, 65, 81, 0.1)'],
                      'Chưa xác định' => ['bg' => 'linear-gradient(135deg, #fee2e2 0%, #fecaca 100%)', 'color' => '#991b1b', 'icon' => 'question-circle', 'shadow' => 'rgba(153, 27, 27, 0.1)']
                    ];
                    $cfg = $map[$st] ?? $map['Chưa xác định'];
                  ?>
                  <span style="background: <?= $cfg['bg'] ?>; color: <?= $cfg['color'] ?>; padding: 8px 16px; border-radius: 12px; font-size: 13px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 4px <?= $cfg['shadow'] ?>;">
                    <i class="fas fa-<?= $cfg['icon'] ?>"></i> <?= htmlspecialchars($st) ?>
                  </span>
                
                <a href="?act=guide-assignment-detail&id=<?= $assignment['id'] ?>" class="btn btn-primary btn-sm">
                  <i class="fas fa-eye"></i> Xem chi tiết
                </a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</div>

<style>
.card:hover {
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}
</style>


