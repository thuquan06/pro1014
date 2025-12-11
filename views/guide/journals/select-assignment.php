<?php
// views/guide/journals/select-assignment.php
?>

<div class="card">
  <div class="card-header">
    <h3><i class="fas fa-plus-circle"></i> Chọn phân công để tạo nhật ký</h3>
  </div>
  <div class="card-body">
    <?php if (empty($assignments)): ?>
      <div style="text-align:center; padding: 40px; color: var(--text-light);">
        <p>Không có phân công nào.</p>
        <a href="?act=guide-assignments" class="btn btn-primary">
          <i class="fas fa-calendar-check"></i> Về danh sách phân công
        </a>
      </div>
    <?php else: ?>
      <form method="get" action="">
        <input type="hidden" name="act" value="guide-journal-create">
        <div style="max-width: 400px; margin-bottom: 16px;">
          <label style="display:block; margin-bottom:8px; font-weight:600;">Chọn phân công</label>
          <select name="assignment_id" required style="width:100%; padding:10px; border:1px solid var(--border); border-radius:8px;">
            <option value="">-- Chọn phân công --</option>
            <?php foreach ($assignments as $ass): ?>
              <option value="<?= $ass['id'] ?>">
                <?= htmlspecialchars($ass['ten_tour'] ?? 'Tour #' . $ass['id']) ?> - KH: <?= !empty($ass['ngay_khoi_hanh']) ? date('d/m/Y', strtotime($ass['ngay_khoi_hanh'])) : 'N/A' ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <button type="submit" class="btn btn-primary">
          <i class="fas fa-arrow-right"></i> Tiếp tục
        </button>
        <a href="?act=guide-journals" class="btn" style="margin-left:8px; background: var(--bg-light); color: var(--text-dark);">
          Hủy
        </a>
      </form>
    <?php endif; ?>
  </div>
</div>

