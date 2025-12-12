<?php
// views/admin/incidents/list.php
?>

<div class="card">
  <div class="card-header">
    <h3><i class="fas fa-exclamation-triangle"></i> Báo cáo sự cố của HDV</h3>
  </div>
  <div class="card-body">
    <!-- Filters -->
    <form method="GET" action="?act=admin-incidents" style="margin-bottom: 24px; padding: 20px; background: var(--bg-light); border-radius: 10px;">
      <input type="hidden" name="act" value="admin-incidents">
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; align-items: end;">
        <div>
          <label style="display: block; margin-bottom: 8px; font-size: 13px; font-weight: 600; color: var(--text-dark);">Hướng dẫn viên</label>
          <select name="id_hdv" style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px;">
            <option value="">Tất cả</option>
            <?php foreach ($guides ?? [] as $guide): ?>
              <option value="<?= $guide['id'] ?>" <?= (isset($filters['id_hdv']) && $filters['id_hdv'] == $guide['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($guide['ho_ten'] ?? 'N/A') ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <label style="display: block; margin-bottom: 8px; font-size: 13px; font-weight: 600; color: var(--text-dark);">Tour</label>
          <select name="id_tour" style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px;">
            <option value="">Tất cả</option>
            <?php foreach ($tours ?? [] as $tour): ?>
              <option value="<?= $tour['id_goi'] ?>" <?= (isset($filters['id_tour']) && $filters['id_tour'] == $tour['id_goi']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($tour['tengoi'] ?? 'N/A') ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <label style="display: block; margin-bottom: 8px; font-size: 13px; font-weight: 600; color: var(--text-dark);">Loại sự cố</label>
          <select name="loai_su_co" style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px;">
            <option value="">Tất cả</option>
            <?php foreach ($incidentTypes ?? [] as $key => $label): ?>
              <option value="<?= $key ?>" <?= (isset($filters['loai_su_co']) && $filters['loai_su_co'] == $key) ? 'selected' : '' ?>>
                <?= htmlspecialchars($label) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <label style="display: block; margin-bottom: 8px; font-size: 13px; font-weight: 600; color: var(--text-dark);">Mức độ</label>
          <select name="muc_do" style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px;">
            <option value="">Tất cả</option>
            <?php foreach ($severityLevels ?? [] as $key => $level): ?>
              <option value="<?= $key ?>" <?= (isset($filters['muc_do']) && $filters['muc_do'] == $key) ? 'selected' : '' ?>>
                <?= htmlspecialchars($level['label'] ?? $key) ?>
              </option>
            <?php endforeach; ?>
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

    <!-- Incidents List -->
    <?php if (empty($incidents)): ?>
      <div style="text-align: center; padding: 60px 20px; color: var(--text-light);">
        <i class="fas fa-exclamation-triangle" style="font-size: 64px; margin-bottom: 16px; opacity: 0.3;"></i>
        <p style="font-size: 16px; margin-bottom: 24px;">Chưa có báo cáo sự cố nào</p>
      </div>
    <?php else: ?>
      <div class="table-responsive">
        <table>
          <thead>
            <tr>
              <th>Ngày xảy ra</th>
              <th>HDV</th>
              <th>Tour</th>
              <th>Loại sự cố</th>
              <th>Mô tả</th>
              <th>Mức độ</th>
              <th>Thao tác</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($incidents as $incident): ?>
              <tr>
                <td>
                  <strong><?= date('d/m/Y', strtotime($incident['ngay_xay_ra'])) ?></strong>
                  <?php if ($incident['gio_xay_ra']): ?>
                    <br><small style="color: var(--text-light);"><?= date('H:i', strtotime($incident['gio_xay_ra'])) ?></small>
                  <?php endif; ?>
                  <br><small style="color: var(--text-light);">Báo cáo: <?= date('d/m/Y H:i', strtotime($incident['ngay_tao'])) ?></small>
                </td>
                <td>
                  <strong><?= htmlspecialchars($incident['ten_hdv'] ?? 'N/A') ?></strong>
                  <?php if ($incident['email_hdv']): ?>
                    <br><small style="color: var(--text-light);"><?= htmlspecialchars($incident['email_hdv']) ?></small>
                  <?php endif; ?>
                </td>
                <td>
                  <strong><?= htmlspecialchars($incident['ten_tour'] ?? 'N/A') ?></strong>
                  <?php if ($incident['ngay_khoi_hanh']): ?>
                    <br><small style="color: var(--text-light);">KH: <?= date('d/m/Y', strtotime($incident['ngay_khoi_hanh'])) ?></small>
                  <?php endif; ?>
                </td>
                <td>
                  <?php if (!empty($incident['loai_su_co'])): ?>
                    <span style="background: #fef3c7; color: #92400e; padding: 4px 12px; border-radius: 8px; font-size: 13px; font-weight: 600;">
                      <?= htmlspecialchars($incidentTypes[$incident['loai_su_co']] ?? $incident['loai_su_co']) ?>
                    </span>
                  <?php else: ?>
                    <span style="color: var(--text-light);">-</span>
                  <?php endif; ?>
                </td>
                <td>
                  <?php if (!empty($incident['mo_ta'])): ?>
                    <?= htmlspecialchars(mb_substr($incident['mo_ta'], 0, 100)) ?><?= mb_strlen($incident['mo_ta']) > 100 ? '...' : '' ?>
                  <?php else: ?>
                    <span style="color: var(--text-light);">-</span>
                  <?php endif; ?>
                </td>
                <td>
                  <?php
                    $mucDo = $incident['muc_do'] ?? 'thap';
                    $severity = $severityLevels[$mucDo] ?? ['label' => 'Thấp', 'color' => '#10b981'];
                    $color = $severity['color'] ?? '#10b981';
                  ?>
                  <span style="background: <?= $color ?>20; color: <?= $color ?>; padding: 4px 12px; border-radius: 8px; font-size: 13px; font-weight: 600;">
                    <?= htmlspecialchars($severity['label'] ?? ucfirst($mucDo)) ?>
                  </span>
                </td>
                <td>
                  <a href="?act=admin-incident-detail&id=<?= $incident['id'] ?>" class="btn btn-sm btn-primary">
                    <i class="fas fa-eye"></i> Xem chi tiết
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

