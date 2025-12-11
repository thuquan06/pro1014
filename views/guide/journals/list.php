<?php
// views/guide/journals/list.php
?>

<div class="card">
  <div class="card-header">
    <h3><i class="fas fa-book"></i> Nhật ký tour</h3>
    <div style="display: flex; gap: 12px;">
      <a href="?act=guide-assignments" class="btn btn-sm" style="background: var(--bg-light); color: var(--text-dark);">
        <i class="fas fa-calendar-check"></i> Phân công
      </a>
    </div>
  </div>
  <div class="card-body">
    <!-- Filters -->
    <form method="GET" action="?act=guide-journals" style="margin-bottom: 24px; padding: 20px; background: var(--bg-light); border-radius: 10px;">
      <input type="hidden" name="act" value="guide-journals">
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

    <!-- Journals List -->
    <?php if (empty($journals)): ?>
      <div style="text-align: center; padding: 60px 20px; color: var(--text-light);">
        <i class="fas fa-book-open" style="font-size: 64px; margin-bottom: 16px; opacity: 0.3;"></i>
        <p style="font-size: 16px; margin-bottom: 24px;">Chưa có nhật ký nào</p>
        <a href="?act=guide-journal-create" class="btn btn-primary">
          <i class="fas fa-plus"></i> Tạo nhật ký từ phân công
        </a>
      </div>
    <?php else: ?>
      <div class="table-responsive">
        <table>
          <thead>
            <tr>
              <th>Ngày</th>
              <th>Tour</th>
              <th>Diễn biến</th>
              <th>Thời tiết</th>
              <th>Ảnh</th>
              <th>Thao tác</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($journals as $journal): ?>
              <tr>
                <td>
                  <strong><?= date('d/m/Y', strtotime($journal['ngay'])) ?></strong>
                  <br><small style="color: var(--text-light);"><?= date('H:i', strtotime($journal['ngay_tao'])) ?></small>
                </td>
                <td>
                  <strong><?= htmlspecialchars($journal['ten_tour'] ?? 'N/A') ?></strong>
                  <?php if ($journal['ngay_khoi_hanh']): ?>
                    <br><small style="color: var(--text-light);">KH: <?= date('d/m/Y', strtotime($journal['ngay_khoi_hanh'])) ?></small>
                  <?php endif; ?>
                </td>
                <td>
                  <?php if (!empty($journal['dien_bien'])): ?>
                    <?= htmlspecialchars(mb_substr($journal['dien_bien'], 0, 100)) ?><?= mb_strlen($journal['dien_bien']) > 100 ? '...' : '' ?>
                  <?php else: ?>
                    <span style="color: var(--text-light);">-</span>
                  <?php endif; ?>
                </td>
                <td>
                  <?php if (!empty($journal['thoi_tiet'])): ?>
                    <span style="background: #dbeafe; color: #1e40af; padding: 4px 12px; border-radius: 8px; font-size: 13px;">
                      <i class="fas fa-cloud-sun"></i> <?= htmlspecialchars($journal['thoi_tiet']) ?>
                    </span>
                  <?php else: ?>
                    <span style="color: var(--text-light);">-</span>
                  <?php endif; ?>
                </td>
                <td>
                  <?php if (!empty($journal['hinh_anh']) && count($journal['hinh_anh']) > 0): ?>
                    <span style="background: #d1fae5; color: #065f46; padding: 4px 12px; border-radius: 8px; font-size: 13px;">
                      <i class="fas fa-images"></i> <?= count($journal['hinh_anh']) ?> ảnh
                    </span>
                  <?php else: ?>
                    <span style="color: var(--text-light);">-</span>
                  <?php endif; ?>
                </td>
                <td>
                  <div style="display: flex; gap: 8px;">
                    <a href="?act=guide-journal-detail&id=<?= $journal['id'] ?>" class="btn btn-sm" style="background: var(--info); color: white; padding: 6px 12px;">
                      <i class="fas fa-eye"></i>
                    </a>
                    <a href="?act=guide-journal-edit&id=<?= $journal['id'] ?>" class="btn btn-sm" style="background: var(--warning); color: white; padding: 6px 12px;">
                      <i class="fas fa-edit"></i>
                    </a>
                    <a href="?act=guide-journal-delete&id=<?= $journal['id'] ?>" class="btn btn-sm" style="background: var(--danger); color: white; padding: 6px 12px;" onclick="return confirm('Bạn có chắc muốn xóa nhật ký này?')">
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

