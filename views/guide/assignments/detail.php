<?php
// views/guide/assignments/detail.php
?>

<div class="card" style="margin-bottom: 24px;">
  <div class="card-header">
    <h3><i class="fas fa-info-circle"></i> Chi tiết phân công</h3>
    <div style="display: flex; gap: 12px;">
      <a href="?act=guide-attendance&assignment_id=<?= $assignment['id'] ?>" class="btn btn-sm" style="background: #8b5cf6; color: white;">
        <i class="fas fa-clipboard-check"></i> Điểm danh
      </a>
      <?php if (!empty($checklist)): ?>
        <a href="?act=guide-checklist&assignment_id=<?= $assignment['id'] ?>" class="btn btn-sm" style="background: #10b981; color: white;">
          <i class="fas fa-clipboard-check"></i> Checklist (<?= $completionPercentage ?>%)
        </a>
      <?php endif; ?>
      <a href="?act=guide-journal-create&assignment_id=<?= $assignment['id'] ?>" class="btn btn-sm btn-primary">
        <i class="fas fa-book"></i> Tạo nhật ký
      </a>
      <a href="?act=guide-incident-create&assignment_id=<?= $assignment['id'] ?>" class="btn btn-sm" style="background: var(--warning); color: white;">
        <i class="fas fa-exclamation-triangle"></i> Báo cáo sự cố
      </a>
      <a href="?act=guide-assignments" class="btn btn-sm" style="background: var(--bg-light); color: var(--text-dark);">
        <i class="fas fa-arrow-left"></i> Quay lại
      </a>
    </div>
  </div>
  <div class="card-body">
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px;">
      <div>
        <strong style="color: var(--text-light); font-size: 12px; text-transform: uppercase;">Tour</strong>
        <p style="font-size: 18px; font-weight: 700; margin-top: 4px; color: var(--primary);">
          <?php if (!empty($assignment['ten_tour'])): ?>
            <?= htmlspecialchars($assignment['ten_tour']) ?>
          <?php elseif (!empty($assignment['id_lich_khoi_hanh'])): ?>
            Tour #<?= htmlspecialchars($assignment['id_lich_khoi_hanh']) ?>
            <br><small style="font-size: 14px; font-weight: 400; color: var(--text-light); font-style: italic;">Chưa có thông tin tour</small>
          <?php else: ?>
            Phân công #<?= htmlspecialchars($assignment['id']) ?>
            <br><small style="font-size: 14px; font-weight: 400; color: var(--text-light); font-style: italic;">Chưa có lịch khởi hành</small>
          <?php endif; ?>
        </p>
      </div>
      
      <div>
        <strong style="color: var(--text-light); font-size: 12px; text-transform: uppercase;">Vai trò</strong>
        <p style="font-size: 16px; font-weight: 600; margin-top: 4px;">
          <?= htmlspecialchars($assignment['vai_tro'] ?? 'HDV chính') ?>
        </p>
      </div>
      
      <div>
        <strong style="color: var(--text-light); font-size: 12px; text-transform: uppercase;">Ngày khởi hành</strong>
        <p style="font-size: 16px; font-weight: 600; margin-top: 4px;">
          <?php if ($assignment['ngay_khoi_hanh']): ?>
            <?= date('d/m/Y', strtotime($assignment['ngay_khoi_hanh'])) ?>
            <?php if ($assignment['gio_khoi_hanh']): ?>
              <br><small style="color: var(--text-light);"><?= htmlspecialchars($assignment['gio_khoi_hanh']) ?></small>
            <?php endif; ?>
          <?php else: ?>
            N/A
          <?php endif; ?>
        </p>
      </div>
      
      <div>
        <strong style="color: var(--text-light); font-size: 12px; text-transform: uppercase;">Điểm tập trung</strong>
        <p style="font-size: 16px; font-weight: 600; margin-top: 4px;">
          <?= htmlspecialchars($assignment['diem_tap_trung'] ?? 'Chưa cập nhật') ?>
        </p>
      </div>
      
      <div>
        <strong style="color: var(--text-light); font-size: 12px; text-transform: uppercase;">Thời gian làm việc</strong>
        <p style="font-size: 16px; font-weight: 600; margin-top: 4px;">
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
            Chưa cập nhật
          <?php endif; ?>
        </p>
      </div>
      
      <div>
        <strong style="color: var(--text-light); font-size: 12px; text-transform: uppercase;">Lương</strong>
        <p style="font-size: 18px; font-weight: 700; margin-top: 4px; color: var(--success);">
          <?php if (!empty($assignment['luong'])): ?>
            <?= number_format($assignment['luong'], 0, ',', '.') ?> đ
          <?php else: ?>
            Chưa cập nhật
          <?php endif; ?>
        </p>
      </div>
      
      <div>
        <strong style="color: var(--text-light); font-size: 12px; text-transform: uppercase;">Trạng thái</strong>
        <p style="margin-top: 4px;">
          <?php if ($assignment['trang_thai'] == 1): ?>
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
    
    <?php if (!empty($assignment['ghi_chu'])): ?>
      <div style="margin-top: 24px; padding-top: 24px; border-top: 1px solid var(--border);">
        <strong style="color: var(--text-light); font-size: 12px; text-transform: uppercase;">Ghi chú</strong>
        <p style="margin-top: 8px; color: var(--text-dark); line-height: 1.6;">
          <?= nl2br(htmlspecialchars($assignment['ghi_chu'])) ?>
        </p>
      </div>
    <?php endif; ?>
  </div>
</div>

<?php if ($tour): ?>
<div class="card" style="margin-bottom: 24px;">
  <div class="card-header">
    <h3><i class="fas fa-map-marked-alt"></i> Thông tin Tour</h3>
  </div>
  <div class="card-body">
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
      <div>
        <strong style="color: var(--text-light); font-size: 12px; text-transform: uppercase;">Tên tour</strong>
        <p style="font-size: 16px; font-weight: 600; margin-top: 4px; color: var(--primary);">
          <?= htmlspecialchars($tour['tengoi'] ?? 'N/A') ?>
        </p>
      </div>
      <div>
        <strong style="color: var(--text-light); font-size: 12px; text-transform: uppercase;">Số ngày</strong>
        <p style="font-size: 16px; font-weight: 600; margin-top: 4px;">
          <?= htmlspecialchars($tour['songay'] ?? 'N/A') ?> ngày
        </p>
      </div>
      <div>
        <strong style="color: var(--text-light); font-size: 12px; text-transform: uppercase;">Giá tour (người lớn)</strong>
        <p style="font-size: 16px; font-weight: 600; margin-top: 4px; color: var(--success);">
          <?php if (!empty($tour['giagoi'])): ?>
            <?= number_format($tour['giagoi'], 0, ',', '.') ?> đ
          <?php else: ?>
            Chưa cập nhật
          <?php endif; ?>
        </p>
      </div>
      <?php if (!empty($tour['giatreem'])): ?>
      <div>
        <strong style="color: var(--text-light); font-size: 12px; text-transform: uppercase;">Giá trẻ em</strong>
        <p style="font-size: 16px; font-weight: 600; margin-top: 4px; color: var(--info);">
          <?= number_format($tour['giatreem'], 0, ',', '.') ?> đ
        </p>
      </div>
      <?php endif; ?>
      <?php if (!empty($tour['giatrenho'])): ?>
      <div>
        <strong style="color: var(--text-light); font-size: 12px; text-transform: uppercase;">Giá trẻ nhỏ</strong>
        <p style="font-size: 16px; font-weight: 600; margin-top: 4px; color: var(--info);">
          <?= number_format($tour['giatrenho'], 0, ',', '.') ?> đ
        </p>
      </div>
      <?php endif; ?>
      <?php if (!empty($tour['noixuatphat'])): ?>
      <div>
        <strong style="color: var(--text-light); font-size: 12px; text-transform: uppercase;">Nơi xuất phát</strong>
        <p style="font-size: 16px; font-weight: 600; margin-top: 4px;">
          <i class="fas fa-map-marker-alt" style="color: var(--primary);"></i>
          <?= htmlspecialchars($tour['noixuatphat']) ?>
        </p>
      </div>
      <?php endif; ?>
      <?php if (!empty($tour['diadiem'])): ?>
      <div>
        <strong style="color: var(--text-light); font-size: 12px; text-transform: uppercase;">Địa điểm</strong>
        <p style="font-size: 16px; font-weight: 600; margin-top: 4px;">
          <i class="fas fa-location-dot" style="color: var(--primary);"></i>
          <?= htmlspecialchars($tour['diadiem']) ?>
        </p>
      </div>
      <?php endif; ?>
      <?php if (!empty($tour['quocgia'])): ?>
      <div>
        <strong style="color: var(--text-light); font-size: 12px; text-transform: uppercase;">Quốc gia</strong>
        <p style="font-size: 16px; font-weight: 600; margin-top: 4px;">
          <i class="fas fa-globe" style="color: var(--primary);"></i>
          <?= htmlspecialchars($tour['quocgia']) ?>
        </p>
      </div>
      <?php endif; ?>
      <?php if (!empty($departurePlan['diem_tap_trung'])): ?>
      <div>
        <strong style="color: var(--text-light); font-size: 12px; text-transform: uppercase;">Điểm tập trung</strong>
        <p style="font-size: 16px; font-weight: 600; margin-top: 4px;">
          <i class="fas fa-map-pin" style="color: var(--primary);"></i>
          <?= htmlspecialchars($departurePlan['diem_tap_trung']) ?>
        </p>
      </div>
      <?php endif; ?>
    </div>
    
    <?php if (!empty($tour['mota'])): ?>
    <div style="margin-top: 24px; padding-top: 24px; border-top: 1px solid var(--border);">
      <strong style="color: var(--text-light); font-size: 12px; text-transform: uppercase;">Mô tả tour</strong>
      <p style="margin-top: 8px; color: var(--text-dark); line-height: 1.6;">
        <?= nl2br(htmlspecialchars($tour['mota'])) ?>
      </p>
    </div>
    <?php endif; ?>
    
    <?php if ($tour): ?>
    <div style="margin-top: 24px; padding-top: 24px; border-top: 1px solid var(--border);">
      <strong style="color: var(--text-light); font-size: 12px; text-transform: uppercase; display: block; margin-bottom: 16px;">
        <i class="fas fa-route" style="color: var(--primary);"></i> Lịch trình tour
      </strong>
      <?php 
      // Debug: Kiểm tra dữ liệu
      $itinerary = $itinerary ?? [];
      if (!empty($itinerary) && is_array($itinerary) && count($itinerary) > 0): 
      ?>
      <div style="display: flex; flex-direction: column; gap: 16px;">
        <?php foreach ($itinerary as $day): ?>
          <div style="background: var(--bg-light); border-left: 4px solid var(--primary); padding: 16px; border-radius: 8px;">
            <div style="display: flex; align-items: start; gap: 16px;">
              <div style="background: var(--primary); color: white; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px; flex-shrink: 0;">
                <?= htmlspecialchars($day['ngay_thu']) ?>
              </div>
              <div style="flex: 1;">
                <h4 style="margin: 0 0 8px 0; font-size: 16px; font-weight: 700; color: var(--primary);">
                  <?= htmlspecialchars($day['tieude'] ?? 'Ngày ' . $day['ngay_thu']) ?>
                </h4>
                <?php if (!empty($day['content_html']) || !empty($day['mota'])): ?>
                  <div style="margin: 0 0 8px 0; color: var(--text-dark); line-height: 1.6;">
                    <?php if (!empty($day['content_html'])): ?>
                      <?php 
                      // Decode HTML entities và hiển thị HTML
                      $content = html_entity_decode($day['content_html'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
                      echo $content;
                      ?>
                    <?php elseif (!empty($day['mota'])): ?>
                      <?= nl2br(htmlspecialchars($day['mota'])) ?>
                    <?php endif; ?>
                  </div>
                <?php endif; ?>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; margin-top: 12px;">
                  <?php if (!empty($day['diemden'])): ?>
                    <div>
                      <small style="color: var(--text-light); font-weight: 600;">Điểm đến:</small>
                      <p style="margin: 4px 0 0 0; font-size: 14px;"><?= htmlspecialchars($day['diemden']) ?></p>
                    </div>
                  <?php endif; ?>
                  <?php if (!empty($day['thoiluong'])): ?>
                    <div>
                      <small style="color: var(--text-light); font-weight: 600;">Thời lượng:</small>
                      <p style="margin: 4px 0 0 0; font-size: 14px;"><?= htmlspecialchars($day['thoiluong']) ?></p>
                    </div>
                  <?php endif; ?>
                  <?php if (!empty($day['buaan'])): ?>
                    <div>
                      <small style="color: var(--text-light); font-weight: 600;">Bữa ăn:</small>
                      <p style="margin: 4px 0 0 0; font-size: 14px;"><?= htmlspecialchars($day['buaan']) ?></p>
                    </div>
                  <?php endif; ?>
                  <?php if (!empty($day['noinghi'])): ?>
                    <div>
                      <small style="color: var(--text-light); font-weight: 600;">Nơi nghỉ:</small>
                      <p style="margin: 4px 0 0 0; font-size: 14px;"><?= htmlspecialchars($day['noinghi']) ?></p>
                    </div>
                  <?php endif; ?>
                </div>
                <?php if (!empty($day['hoatdong'])): ?>
                  <div style="margin-top: 12px; padding-top: 12px; border-top: 1px solid var(--border);">
                    <small style="color: var(--text-light); font-weight: 600;">Hoạt động:</small>
                    <p style="margin: 4px 0 0 0; font-size: 14px; color: var(--text-dark);">
                      <?= nl2br(htmlspecialchars($day['hoatdong'])) ?>
                    </p>
                  </div>
                <?php endif; ?>
                <?php if (!empty($day['ghichu_hdv'])): ?>
                  <div style="margin-top: 12px; padding: 12px; background: #fef3c7; border-radius: 6px; border-left: 3px solid #f59e0b;">
                    <small style="color: #78350f; font-weight: 600;">
                      <i class="fas fa-info-circle"></i> Ghi chú cho HDV:
                    </small>
                    <p style="margin: 4px 0 0 0; font-size: 14px; color: #78350f;">
                      <?= nl2br(htmlspecialchars($day['ghichu_hdv'])) ?>
                    </p>
                  </div>
                <?php endif; ?>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
      <?php else: ?>
      <div style="text-align: center; padding: 40px 20px; color: var(--text-light);">
        <i class="fas fa-route" style="font-size: 48px; opacity: 0.3; margin-bottom: 12px; display: block;"></i>
        <p style="margin: 0; font-size: 14px;">Chưa có lịch trình cho tour này</p>
      </div>
      <?php endif; ?>
    </div>
    <?php endif; ?>
  </div>
</div>
<?php endif; ?>

<?php if (!empty($services)): ?>
<div class="card">
  <div class="card-header">
    <h3><i class="fas fa-concierge-bell"></i> Dịch vụ được gán</h3>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table>
        <thead>
          <tr>
            <th>Dịch vụ</th>
            <th>Loại</th>
            <th>Số lượng</th>
            <th>Ngày sử dụng</th>
            <th>Giá thực tế</th>
            <th>Trạng thái</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($services as $service): ?>
            <tr>
              <td><strong><?= htmlspecialchars($service['ten_dich_vu'] ?? 'N/A') ?></strong></td>
              <td><?= htmlspecialchars($service['loai_dich_vu'] ?? 'N/A') ?></td>
              <td><?= htmlspecialchars($service['so_luong'] ?? 1) ?> <?= htmlspecialchars($service['don_vi'] ?? '') ?></td>
              <td>
                <?php if ($service['ngay_su_dung']): ?>
                  <?= date('d/m/Y', strtotime($service['ngay_su_dung'])) ?>
                <?php else: ?>
                  Chưa xác định
                <?php endif; ?>
              </td>
              <td>
                <?php if (!empty($service['gia_thuc_te'])): ?>
                  <?= number_format($service['gia_thuc_te'], 0, ',', '.') ?> đ
                <?php else: ?>
                  Chưa cập nhật
                <?php endif; ?>
              </td>
              <td>
                <?php
                $statusLabels = [
                  'cho' => ['label' => 'Chờ xác nhận', 'color' => '#f59e0b', 'bg' => '#fed7aa'],
                  'da_xac_nhan' => ['label' => 'Đã xác nhận', 'color' => '#065f46', 'bg' => '#d1fae5'],
                  'huy' => ['label' => 'Đã hủy', 'color' => '#991b1b', 'bg' => '#fee2e2']
                ];
                $status = $statusLabels[$service['trang_thai']] ?? $statusLabels['cho'];
                ?>
                <span style="background: <?= $status['bg'] ?>; color: <?= $status['color'] ?>; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                  <?= $status['label'] ?>
                </span>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php endif; ?>


