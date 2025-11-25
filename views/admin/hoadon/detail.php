<?php
/**
 * Chi tiết Hóa đơn - Modern Interface
 * Updated: 2025-11-25
 */

function safe_html($value) {
    return htmlentities($value ?? '', ENT_QUOTES, 'UTF-8');
}

function getTrangThaiText($status) {
    switch($status) {
        case 0: return '<span class="status-badge warning"><i class="fas fa-clock"></i> Chờ xác nhận</span>';
        case 1: return '<span class="status-badge info"><i class="fas fa-check"></i> Đã xác nhận</span>';
        case 2: return '<span class="status-badge success"><i class="fas fa-check-circle"></i> Hoàn thành</span>';
        case 3: return '<span class="status-badge danger"><i class="fas fa-ban"></i> Đã hủy</span>';
        default: return '<span class="status-badge secondary">Không xác định</span>';
    }
}

$id = $hoadon['id_hoadon'] ?? '';
$email = $hoadon['email_nguoidung'] ?? '';
$ten_goi = $hoadon['ten_goi'] ?? 'N/A';
$id_goi = $hoadon['id_goi'] ?? '';
$nguoilon = $hoadon['nguoilon'] ?? 0;
$treem = $hoadon['treem'] ?? 0;
$trenho = $hoadon['trenho'] ?? 0;
$embe = $hoadon['embe'] ?? 0;
$ngayvao = $hoadon['ngayvao'] ?? '';
$ngayra = $hoadon['ngayra'] ?? '';
$ghichu = $hoadon['ghichu'] ?? '';
$ngaydat = $hoadon['ngaydat'] ?? '';
$trangthai = $hoadon['trangthai'] ?? 0;
$huy = $hoadon['huy'] ?? 0;
$ngaycapnhat = $hoadon['ngaycapnhat'] ?? '';

$giagoi = $hoadon['giagoi'] ?? 0;
$giatreem = $hoadon['giatreem'] ?? 0;
$giatrenho = $hoadon['giatrenho'] ?? 0;

$tong_tien = $total ?? 0;

if ($huy == 1) {
    $trangthai = 3;
}
?>

<style>
.detail-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
  flex-wrap: wrap;
  gap: 16px;
}

.detail-title {
  font-size: 28px;
  font-weight: 700;
  color: var(--text-dark);
  margin: 0;
}

.detail-actions {
  display: flex;
  gap: 12px;
}

.status-badge {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 8px 16px;
  border-radius: 20px;
  font-size: 14px;
  font-weight: 600;
}

.status-badge.warning {
  background: #fef3c7;
  color: #78350f;
}

.status-badge.info {
  background: #dbeafe;
  color: #1e40af;
}

.status-badge.success {
  background: #d1fae5;
  color: #065f46;
}

.status-badge.danger {
  background: #fee2e2;
  color: #991b1b;
}

.status-badge.secondary {
  background: #f3f4f6;
  color: #6b7280;
}

.detail-card {
  background: white;
  border: 1px solid var(--border);
  border-radius: 12px;
  padding: 24px;
  margin-bottom: 20px;
}

.card-title {
  font-size: 18px;
  font-weight: 700;
  color: var(--text-dark);
  margin: 0 0 20px 0;
  padding-bottom: 16px;
  border-bottom: 2px solid var(--bg-light);
  display: flex;
  align-items: center;
  gap: 10px;
}

.card-title i {
  color: var(--primary);
}

.info-table {
  width: 100%;
}

.info-table tr {
  border-bottom: 1px solid var(--border);
}

.info-table tr:last-child {
  border-bottom: none;
}

.info-table th {
  padding: 12px 0;
  font-weight: 600;
  color: var(--text-dark);
  width: 40%;
  font-size: 14px;
}

.info-table td {
  padding: 12px 0;
  color: var(--text-dark);
  font-size: 14px;
}

.price-table {
  width: 100%;
  border-collapse: collapse;
}

.price-table thead {
  background: var(--bg-light);
}

.price-table th {
  padding: 12px 16px;
  text-align: left;
  font-weight: 600;
  font-size: 13px;
  color: var(--text-dark);
  text-transform: uppercase;
  border-bottom: 2px solid var(--border);
}

.price-table td {
  padding: 14px 16px;
  border-bottom: 1px solid var(--border);
  font-size: 14px;
  color: var(--text-dark);
}

.price-table .total-row {
  background: var(--bg-light);
  font-weight: 700;
}

.price-table .total-row td {
  font-size: 16px;
  color: var(--primary);
}

.note-box {
  background: #fffbeb;
  border-left: 4px solid #f59e0b;
  border-radius: 8px;
  padding: 20px;
}

.note-content {
  color: var(--text-dark);
  line-height: 1.6;
}

@media (max-width: 768px) {
  .detail-actions {
    width: 100%;
    flex-direction: column;
  }
}
</style>

<!-- Page Header -->
<div class="detail-header">
  <h1 class="detail-title">
    <i class="fas fa-file-invoice" style="color: var(--primary);"></i>
    Chi tiết hóa đơn #<?php echo safe_html($id); ?>
  </h1>
  
  <div class="detail-actions">
    <a href="<?php echo BASE_URL; ?>?act=hoadon-list" class="btn btn-secondary">
      <i class="fas fa-arrow-left"></i>
      Quay lại
    </a>
    <a href="<?php echo BASE_URL; ?>?act=hoadon-edit&id=<?php echo $id; ?>" class="btn btn-primary">
      <i class="fas fa-edit"></i>
      Chỉnh sửa
    </a>
    <?php if ($huy != 1): ?>
    <button onclick="if(confirm('Bạn có chắc chắn muốn hủy hóa đơn này?')) { 
      var form = document.createElement('form');
      form.method = 'POST';
      form.action = '<?php echo BASE_URL; ?>?act=hoadon-cancel';
      var input = document.createElement('input');
      input.type = 'hidden';
      input.name = 'id';
      input.value = '<?php echo $id; ?>';
      form.appendChild(input);
      document.body.appendChild(form);
      form.submit();
    }" class="btn" style="background: #ef4444; color: white;">
      <i class="fas fa-ban"></i>
      Hủy hóa đơn
    </button>
    <?php endif; ?>
  </div>
</div>

<div class="row">
  <!-- Thông tin khách hàng -->
  <div class="col-md-6">
    <div class="detail-card">
      <h3 class="card-title">
        <i class="fas fa-user"></i>
        Thông tin khách hàng
      </h3>
      
      <table class="info-table">
        <tr>
          <th>Email:</th>
          <td><?php echo safe_html($email); ?></td>
        </tr>
        <tr>
          <th>Ngày đặt:</th>
          <td><?php echo $ngaydat ? date("d/m/Y H:i:s", strtotime($ngaydat)) : 'N/A'; ?></td>
        </tr>
        <tr>
          <th>Ngày cập nhật:</th>
          <td><?php echo $ngaycapnhat ? date("d/m/Y H:i:s", strtotime($ngaycapnhat)) : 'Chưa cập nhật'; ?></td>
        </tr>
        <tr>
          <th>Trạng thái:</th>
          <td><?php echo getTrangThaiText($trangthai); ?></td>
        </tr>
      </table>
    </div>
  </div>

  <!-- Thông tin tour -->
  <div class="col-md-6">
    <div class="detail-card">
      <h3 class="card-title">
        <i class="fas fa-map-marked-alt"></i>
        Thông tin tour
      </h3>
      
      <table class="info-table">
        <tr>
          <th>Tên tour:</th>
          <td>
            <?php echo safe_html($ten_goi); ?>
            <?php if ($id_goi): ?>
            <a href="<?php echo BASE_URL; ?>?act=admin-tour-detail&id=<?php echo $id_goi; ?>" target="_blank" style="color: var(--primary);">
              <i class="fas fa-external-link-alt"></i>
            </a>
            <?php endif; ?>
          </td>
        </tr>
        <tr>
          <th>Ngày vào:</th>
          <td><?php echo $ngayvao ? date("d/m/Y", strtotime($ngayvao)) : 'N/A'; ?></td>
        </tr>
        <tr>
          <th>Ngày ra:</th>
          <td><?php echo $ngayra ? date("d/m/Y", strtotime($ngayra)) : 'N/A'; ?></td>
        </tr>
      </table>
    </div>
  </div>
</div>

<!-- Chi tiết giá -->
<div class="detail-card">
  <h3 class="card-title">
    <i class="fas fa-money-bill-wave"></i>
    Chi tiết số người và giá tiền
  </h3>
  
  <table class="price-table">
    <thead>
      <tr>
        <th>Loại khách</th>
        <th style="text-align: center; width: 120px;">Số lượng</th>
        <th style="text-align: right; width: 150px;">Đơn giá</th>
        <th style="text-align: right; width: 180px;">Thành tiền</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>Người lớn</td>
        <td style="text-align: center;"><strong><?php echo $nguoilon; ?></strong></td>
        <td style="text-align: right;"><?php echo number_format($giagoi); ?> VNĐ</td>
        <td style="text-align: right;"><?php echo number_format($nguoilon * $giagoi); ?> VNĐ</td>
      </tr>
      <tr>
        <td>Trẻ em (6-11 tuổi)</td>
        <td style="text-align: center;"><strong><?php echo $treem; ?></strong></td>
        <td style="text-align: right;"><?php echo number_format($giatreem); ?> VNĐ</td>
        <td style="text-align: right;"><?php echo number_format($treem * $giatreem); ?> VNĐ</td>
      </tr>
      <tr>
        <td>Trẻ nhỏ (2-5 tuổi)</td>
        <td style="text-align: center;"><strong><?php echo $trenho; ?></strong></td>
        <td style="text-align: right;"><?php echo number_format($giatrenho); ?> VNĐ</td>
        <td style="text-align: right;"><?php echo number_format($trenho * $giatrenho); ?> VNĐ</td>
      </tr>
      <tr>
        <td>Em bé (dưới 2 tuổi)</td>
        <td style="text-align: center;"><strong><?php echo $embe; ?></strong></td>
        <td style="text-align: right;">0 VNĐ</td>
        <td style="text-align: right;">0 VNĐ</td>
      </tr>
      <tr class="total-row">
        <td colspan="3" style="text-align: right;">TỔNG CỘNG:</td>
        <td style="text-align: right; font-size: 20px;">
          <?php echo number_format($tong_tien); ?> VNĐ
        </td>
      </tr>
    </tbody>
  </table>
</div>

<!-- Ghi chú -->
<?php if ($ghichu): ?>
<div class="detail-card">
  <h3 class="card-title">
    <i class="fas fa-sticky-note"></i>
    Ghi chú
  </h3>
  
  <div class="note-box">
    <div class="note-content">
      <?php echo nl2br(safe_html($ghichu)); ?>
    </div>
  </div>
</div>
<?php endif; ?>
