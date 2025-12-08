<?php
function safe_html($v){return htmlentities($v??'',ENT_QUOTES,'UTF-8');}
$filters = $filters ?? [];
$vouchers = $vouchers ?? [];
?>
<div class="page-header">
  <h2>Quản lý voucher</h2>
  <div class="actions">
    <a class="btn btn-primary" href="<?= BASE_URL ?>?act=admin-voucher-create"><i class="fas fa-plus"></i> Thêm voucher</a>
  </div>
</div>

<div class="filter-card">
  <form method="get" action="">
    <input type="hidden" name="act" value="admin-vouchers">
    <input type="text" name="q" placeholder="Tìm mã" value="<?= safe_html($filters['q'] ?? '') ?>">
    <select name="status">
      <option value="">-- Trạng thái --</option>
      <option value="1" <?= (($filters['status'] ?? '')==='1')?'selected':''; ?>>Hoạt động</option>
      <option value="0" <?= (($filters['status'] ?? '')==='0')?'selected':''; ?>>Không hoạt động</option>
      <option value="expired" <?= (($filters['status'] ?? '')==='expired')?'selected':''; ?>>Hết hạn</option>
      <option value="not_started" <?= (($filters['status'] ?? '')==='not_started')?'selected':''; ?>>Chưa bắt đầu</option>
      <option value="out_of_uses" <?= (($filters['status'] ?? '')==='out_of_uses')?'selected':''; ?>>Hết lượt dùng</option>
    </select>
    <button class="btn btn-secondary" type="submit"><i class="fas fa-filter"></i> Lọc</button>
  </form>
</div>

<div class="table-responsive">
  <table class="modern-table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Mã</th>
        <th>Loại</th>
        <th>Giá trị</th>
        <th>Hiệu lực</th>
        <th>Giới hạn / Đã dùng</th>
        <th>Trạng thái</th>
        <th>Hành động</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($vouchers)): ?>
        <tr><td colspan="8" style="text-align:center;">Chưa có voucher</td></tr>
      <?php else: foreach ($vouchers as $voucher): ?>
        <tr>
          <td><?= (int)$voucher['id'] ?></td>
          <td><strong><?= safe_html($voucher['code']) ?></strong></td>
          <td><?= $voucher['discount_type']==='percent' ? 'Phần trăm' : 'Số tiền' ?></td>
          <td><?= $voucher['discount_type']==='percent' ? safe_html($voucher['discount_value']).'%' : number_format($voucher['discount_value'],0,',',',').' đ' ?></td>
          <td>
            <?php
            $startDate = !empty($voucher['start_date']) ? date('d/m/Y', strtotime($voucher['start_date'])) : '---';
            $endDate = !empty($voucher['end_date']) ? date('d/m/Y', strtotime($voucher['end_date'])) : '---';
            echo safe_html($startDate) . ' → ' . safe_html($endDate);
            ?>
          </td>
          <td>
            <?php 
            $limit = $voucher['usage_limit'] ?? null;
            $used = (int)$voucher['used_count'];
            if ($limit === null || $limit === '') {
              echo 'Không giới hạn / ' . $used;
            } else {
              echo number_format((int)$limit, 0, ',', ',') . ' / ' . $used;
            }
            ?>
          </td>
          <td>
            <?php
            $now = date('Y-m-d');
            $status = '';
            $badgeClass = '';
            
            // Kiểm tra trạng thái tự động
            if ((int)$voucher['is_active'] === 0) {
              $status = 'Không hoạt động';
              $badgeClass = 'badge-secondary';
            } elseif (!empty($voucher['usage_limit']) && (int)$voucher['used_count'] >= (int)$voucher['usage_limit']) {
              $status = 'Hết lượt dùng';
              $badgeClass = 'badge-danger';
            } elseif (!empty($voucher['end_date']) && $voucher['end_date'] < $now) {
              $status = 'Hết hạn';
              $badgeClass = 'badge-danger';
            } elseif (!empty($voucher['start_date']) && $voucher['start_date'] > $now) {
              $status = 'Chưa bắt đầu';
              $badgeClass = 'badge-warning';
            } else {
              $status = 'Hoạt động';
              $badgeClass = 'badge-success';
            }
            ?>
            <span class="badge <?= $badgeClass ?>"><?= $status ?></span>
          </td>
          <td>
            <div style="display: flex; flex-direction: column; gap: 8px; align-items: center;">
              <div style="display: flex; gap: 8px;">
                <a class="btn btn-sm btn-outline-primary" href="<?= BASE_URL ?>?act=admin-voucher-edit&id=<?= $voucher['id'] ?>" title="Sửa">
                  <i class="fas fa-edit"></i>
                </a>
                <a class="btn btn-sm btn-outline-danger" onclick="return confirm('Xóa voucher này?');" href="<?= BASE_URL ?>?act=admin-voucher-delete&id=<?= $voucher['id'] ?>" title="Xóa">
                  <i class="fas fa-trash"></i>
                </a>
              </div>
              <div class="status-dropdown">
                <?php
                // Xác định trạng thái hiện tại để hiển thị trong dropdown
                $currentStatus = '';
                $currentStatusValue = '';
                $isAutoStatus = false;
                if ((int)$voucher['is_active'] === 0) {
                  $currentStatus = 'Không hoạt động';
                  $currentStatusValue = '0';
                } elseif (!empty($voucher['usage_limit']) && (int)$voucher['used_count'] >= (int)$voucher['usage_limit']) {
                  $currentStatus = 'Hết lượt dùng';
                  $currentStatusValue = 'out_of_uses';
                  $isAutoStatus = true;
                } elseif (!empty($voucher['end_date']) && $voucher['end_date'] < $now) {
                  $currentStatus = 'Hết hạn';
                  $currentStatusValue = 'expired';
                  $isAutoStatus = true;
                } elseif (!empty($voucher['start_date']) && $voucher['start_date'] > $now) {
                  $currentStatus = 'Chưa bắt đầu';
                  $currentStatusValue = 'not_started';
                  $isAutoStatus = true;
                } else {
                  $currentStatus = 'Hoạt động';
                  $currentStatusValue = '1';
                }
                ?>
                <select onchange="quickChangeVoucherStatus(<?= $voucher['id'] ?>, this.value)" title="Đổi trạng thái">
                  <?php if ($isAutoStatus): ?>
                    <option value="" selected disabled><?= $currentStatus ?></option>
                    <option value="1">Hoạt động</option>
                    <option value="0">Không hoạt động</option>
                  <?php else: ?>
                    <option value="1" <?= ($currentStatusValue === '1') ? 'selected' : '' ?>>Hoạt động</option>
                    <option value="0" <?= ($currentStatusValue === '0') ? 'selected' : '' ?>>Không hoạt động</option>
                  <?php endif; ?>
                </select>
              </div>
            </div>
          </td>
        </tr>
      <?php endforeach; endif; ?>
    </tbody>
  </table>
</div>

<style>
.status-dropdown {
  position: relative;
  display: inline-block;
}

.status-dropdown select {
  padding: 6px 28px 6px 10px;
  border: 1px solid var(--border);
  border-radius: 8px;
  font-size: 12px;
  background: white;
  color: var(--text-dark);
  cursor: pointer;
  appearance: none;
  min-width: 140px;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236b7280' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right 8px center;
  transition: all 0.2s;
}

.status-dropdown select:hover {
  border-color: var(--primary);
  background-color: var(--bg-light);
}

.status-dropdown select:focus {
  outline: none;
  border-color: var(--primary);
  box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}
</style>

<script>
function quickChangeVoucherStatus(voucherId, newStatus) {
  if (!confirm('Bạn có chắc muốn đổi trạng thái voucher này?')) {
    location.reload();
    return;
  }

  fetch('<?= BASE_URL ?>?act=admin-voucher-change-status&id=' + voucherId + '&status=' + newStatus, {
    method: 'GET'
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      alert('Cập nhật trạng thái thành công!');
      location.reload();
    } else {
      alert('Lỗi: ' + (data.message || 'Không thể cập nhật trạng thái'));
      location.reload();
    }
  })
  .catch(error => {
    console.error('Error:', error);
    alert('Có lỗi xảy ra. Vui lòng thử lại.');
    location.reload();
  });
}
</script>

