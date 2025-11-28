<?php
// views/guide/incidents/list.php
?>

<div class="card">
  <div class="card-header">
    <h3><i class="fas fa-exclamation-triangle"></i> Báo cáo sự cố</h3>
    <div style="display: flex; gap: 12px;">
      <a href="?act=guide-assignments" class="btn btn-sm" style="background: var(--bg-light); color: var(--text-dark);">
        <i class="fas fa-calendar-check"></i> Phân công
      </a>
      <a href="?act=guide-incident-create" class="btn btn-primary btn-sm">
        <i class="fas fa-plus"></i> Tạo báo cáo
      </a>
    </div>
  </div>
  <div class="card-body">
    <!-- Filters -->
    <form method="GET" action="?act=guide-incidents" style="margin-bottom: 24px; padding: 20px; background: var(--bg-light); border-radius: 10px;">
      <input type="hidden" name="act" value="guide-incidents">
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; align-items: end;">
        <div>
          <label style="display: block; margin-bottom: 8px; font-size: 13px; font-weight: 600; color: var(--text-dark);">Phân công</label>
          <select name="id_phan_cong" style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px;">
            <option value="">Tất cả</option>
            <?php foreach ($assignments as $ass): ?>
              <option value="<?= $ass['id'] ?>" <?= (isset($filters['id_phan_cong']) && $filters['id_phan_cong'] == $ass['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($ass['ten_tour'] ?? 'Tour #' . $ass['id']) ?> - <?= date('d/m/Y', strtotime($ass['ngay_khoi_hanh'] ?? 'now')) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <label style="display: block; margin-bottom: 8px; font-size: 13px; font-weight: 600; color: var(--text-dark);">Loại sự cố</label>
          <select name="loai_su_co" style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px;">
            <option value="">Tất cả</option>
            <?php foreach ($incidentTypes as $key => $label): ?>
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
            <?php foreach ($severityLevels as $key => $level): ?>
              <option value="<?= $key ?>" <?= (isset($filters['muc_do']) && $filters['muc_do'] == $key) ? 'selected' : '' ?>>
                <?= htmlspecialchars($level['label']) ?>
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
        <a href="?act=guide-assignments" class="btn btn-primary">
          <i class="fas fa-plus"></i> Tạo báo cáo từ phân công
        </a>
      </div>
    <?php else: ?>
      <div class="table-responsive">
        <table>
          <thead>
            <tr>
              <th>Ngày xảy ra</th>
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
                  <br><small style="color: var(--text-light);"><?= date('H:i', strtotime($incident['ngay_tao'])) ?></small>
                </td>
                <td>
                  <strong><?= htmlspecialchars($incident['ten_tour'] ?? 'N/A') ?></strong>
                  <?php if ($incident['ngay_khoi_hanh']): ?>
                    <br><small style="color: var(--text-light);">KH: <?= date('d/m/Y', strtotime($incident['ngay_khoi_hanh'])) ?></small>
                  <?php endif; ?>
                </td>
                <td>
                  <?php if (!empty($incident['loai_su_co']) && isset($incidentTypes[$incident['loai_su_co']])): ?>
                    <span style="background: #dbeafe; color: #1e40af; padding: 4px 12px; border-radius: 8px; font-size: 13px;">
                      <?= htmlspecialchars($incidentTypes[$incident['loai_su_co']]) ?>
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
                  <?php if (!empty($incident['muc_do']) && isset($severityLevels[$incident['muc_do']])): ?>
                    <?php $level = $severityLevels[$incident['muc_do']]; ?>
                    <span style="background: <?= $level['color'] ?>20; color: <?= $level['color'] ?>; padding: 4px 12px; border-radius: 8px; font-size: 13px; font-weight: 600;">
                      <?= htmlspecialchars($level['label']) ?>
                    </span>
                  <?php else: ?>
                    <span style="color: var(--text-light);">-</span>
                  <?php endif; ?>
                </td>
                <td>
                  <div style="display: flex; gap: 8px;">
                    <a href="?act=guide-incident-detail&id=<?= $incident['id'] ?>" class="btn btn-sm" style="background: var(--info); color: white; padding: 6px 12px;">
                      <i class="fas fa-eye"></i>
                    </a>
                    <a href="?act=guide-incident-edit&id=<?= $incident['id'] ?>" class="btn btn-sm" style="background: var(--warning); color: white; padding: 6px 12px;">
                      <i class="fas fa-edit"></i>
                    </a>
                    <a href="?act=guide-incident-delete&id=<?= $incident['id'] ?>" class="btn btn-sm" style="background: var(--danger); color: white; padding: 6px 12px;" onclick="return confirm('Bạn có chắc muốn xóa báo cáo sự cố này?')">
                      <i class="fas fa-trash"></i>
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

