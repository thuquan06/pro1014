<?php
/**
 * Chi tiết Hóa đơn - Modern Interface
 * Updated: 2025-11-25
 */

function safe_html($value) {
    return htmlentities($value ?? '', ENT_QUOTES, 'UTF-8');
}

function getTrangThaiText($status) {
    // Trạng thái hóa đơn mới
    switch($status) {
        case 0: return '<span class="status-badge warning"><i class="fas fa-file-invoice"></i> Chưa xuất</span>';
        case 1: return '<span class="status-badge info"><i class="fas fa-file-pdf"></i> Đã xuất</span>';
        case 2: return '<span class="status-badge success"><i class="fas fa-paper-plane"></i> Đã gửi</span>';
        case 3: return '<span class="status-badge danger"><i class="fas fa-ban"></i> Hủy</span>';
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
$trang_thai_hoa_don = $hoadon['trang_thai_hoa_don'] ?? 0;
$huy = $hoadon['huy'] ?? 0;
$ngaycapnhat = $hoadon['ngaycapnhat'] ?? '';
$ly_do_huy = $hoadon['ly_do_huy'] ?? '';

$giagoi = $hoadon['giagoi'] ?? 0;
$giatreem = $hoadon['giatreem'] ?? 0;
$giatrenho = $hoadon['giatrenho'] ?? 0;

$tong_tien = $total ?? 0;

// Nếu booking bị hủy (trang_thai = 5) thì hóa đơn cũng hủy
if (($trangthai ?? 0) == 5) {
    $trang_thai_hoa_don = 3; // Hủy
}
?>

<style>
.detail-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 32px;
  flex-wrap: wrap;
  gap: 20px;
  padding: 24px;
  background: white;
  border: 1px solid var(--border);
  border-radius: 16px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.detail-title {
  font-size: 32px;
  font-weight: 700;
  color: var(--text-dark);
  margin: 0;
  display: flex;
  align-items: center;
  gap: 12px;
}

.detail-title i {
  font-size: 36px;
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
  border-radius: 16px;
  padding: 28px;
  margin-bottom: 24px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
  transition: box-shadow 0.2s;
}

.detail-card:hover {
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
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
  .detail-header {
    padding: 16px;
  }
  
  .detail-title {
    font-size: 24px;
  }
  
  .detail-title i {
    font-size: 28px;
  }
  
  .detail-actions {
    width: 100%;
    flex-direction: column;
  }
  
  .detail-actions .btn {
    width: 100%;
    justify-content: center;
  }
  
  .detail-card {
    padding: 20px;
  }
  
  .info-table th {
    width: 35%;
    font-size: 13px;
  }
  
  .info-table td {
    font-size: 13px;
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
    <?php if ($trang_thai_hoa_don != 3): // Không phải trạng thái Hủy ?>
      <?php if ($trang_thai_hoa_don == 0): // Chưa xuất - có thể xuất hóa đơn ?>
      <button onclick="if(confirm('Xuất hóa đơn #<?php echo $id; ?>?')) { updateInvoiceStatus(<?php echo $id; ?>, 1); }" 
              class="btn" style="background: #10b981; color: white;">
        <i class="fas fa-file-pdf"></i>
        Xuất hóa đơn
      </button>
      <?php elseif ($trang_thai_hoa_don == 1): // Đã xuất - có thể gửi email ?>
      <a href="<?php echo BASE_URL; ?>?act=hoadon-print&id=<?php echo $id; ?>" class="btn" style="background: #3b82f6; color: white;" target="_blank">
        <i class="fas fa-file-pdf"></i>
        Xem hóa đơn
      </a>
      <button onclick="if(confirm('Gửi hóa đơn #<?php echo $id; ?> qua email?')) { updateInvoiceStatus(<?php echo $id; ?>, 2); }" 
              class="btn" style="background: #10b981; color: white;">
        <i class="fas fa-paper-plane"></i>
        Gửi email
      </button>
      <?php elseif ($trang_thai_hoa_don == 2): // Đã gửi ?>
      <a href="<?php echo BASE_URL; ?>?act=hoadon-print&id=<?php echo $id; ?>" class="btn" style="background: #3b82f6; color: white;" target="_blank">
        <i class="fas fa-file-pdf"></i>
        Xem hóa đơn
      </a>
      <?php endif; ?>
    <?php endif; ?>
    <a href="<?php echo BASE_URL; ?>?act=hoadon-edit&id=<?php echo $id; ?>" class="btn btn-primary">
      <i class="fas fa-edit"></i>
      Chỉnh sửa
    </a>
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
          <th>Trạng thái hóa đơn:</th>
          <td><?php echo getTrangThaiText($trang_thai_hoa_don); ?></td>
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

<!-- Lý do hủy -->
<?php if ($huy == 1 && $ly_do_huy): ?>
<div class="detail-card">
  <h3 class="card-title">
    <i class="fas fa-exclamation-triangle" style="color: #ef4444;"></i>
    Lý do hủy
  </h3>
  
  <div class="note-box" style="background: #fee2e2; border-left-color: #ef4444;">
    <div class="note-content" style="color: #991b1b;">
      <?php echo nl2br(safe_html($ly_do_huy)); ?>
    </div>
  </div>
</div>
<?php endif; ?>

<!-- Modal hủy hóa đơn -->
<div id="cancelModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
  <div style="background: white; padding: 30px; border-radius: 12px; max-width: 500px; width: 90%; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
    <h3 style="margin: 0 0 20px 0; color: #1f2937; font-size: 20px;">
      <i class="fas fa-exclamation-triangle" style="color: #ef4444;"></i>
      Hủy hóa đơn
    </h3>
    <p style="color: #6b7280; margin-bottom: 20px;">
      Vui lòng nhập lý do hủy hóa đơn này:
    </p>
    <form method="POST" action="<?php echo BASE_URL; ?>?act=hoadon-cancel">
      <input type="hidden" name="id" value="<?php echo $id; ?>">
      <textarea 
        name="ly_do_huy" 
        required 
        rows="5" 
        style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; font-family: inherit; resize: vertical;"
        placeholder="Nhập lý do hủy hóa đơn..."></textarea>
      <div style="display: flex; gap: 10px; margin-top: 20px; justify-content: flex-end;">
        <button 
          type="button" 
          onclick="closeCancelModal()" 
          style="padding: 10px 20px; background: #f3f4f6; color: #1f2937; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">
          Hủy
        </button>
        <button 
          type="submit" 
          style="padding: 10px 20px; background: #ef4444; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">
          <i class="fas fa-ban"></i>
          Xác nhận hủy
        </button>
      </div>
    </form>
  </div>
</div>

<script>
function showCancelModal() {
  document.getElementById('cancelModal').style.display = 'flex';
}

function closeCancelModal() {
  document.getElementById('cancelModal').style.display = 'none';
}

// Đóng modal khi click bên ngoài
document.getElementById('cancelModal').addEventListener('click', function(e) {
  if (e.target === this) {
    closeCancelModal();
  }
});

// Hàm cập nhật trạng thái hóa đơn
function updateInvoiceStatus(id, newStatus) {
  // newStatus: 0=Chưa xuất, 1=Đã xuất, 2=Đã gửi, 3=Hủy
  fetch('<?php echo BASE_URL; ?>?act=hoadon-update-invoice-status', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: 'id=' + id + '&trang_thai_hoa_don=' + newStatus
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      alert(data.message || 'Cập nhật trạng thái thành công!');
      location.reload();
    } else {
      alert(data.message || 'Cập nhật trạng thái thất bại!');
    }
  })
  .catch(error => {
    console.error('Error:', error);
    alert('Có lỗi xảy ra. Vui lòng thử lại.');
  });
}
</script>
