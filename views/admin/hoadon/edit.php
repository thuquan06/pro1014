<?php
/**
 * Sửa Hóa đơn - Modern Interface
 * Updated: 2025-11-25
 */

function safe_html($value) {
    return htmlentities($value ?? '', ENT_QUOTES, 'UTF-8');
}

$id = $hoadon['id_hoadon'] ?? '';
$email = $hoadon['email_nguoidung'] ?? '';
$id_goi = $hoadon['id_goi'] ?? '';
$nguoilon = $hoadon['nguoilon'] ?? 1;
$treem = $hoadon['treem'] ?? 0;
$trenho = $hoadon['trenho'] ?? 0;
$embe = $hoadon['embe'] ?? 0;
$ngayvao = $hoadon['ngayvao'] ?? '';
$ngayra = $hoadon['ngayra'] ?? '';
$ghichu = $hoadon['ghichu'] ?? '';
$trangthai = $hoadon['trangthai'] ?? 0;
$huy = $hoadon['huy'] ?? 0;
?>

<style>
.invoice-form-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
}

.invoice-form-title {
  font-size: 28px;
  font-weight: 700;
  color: var(--text-dark);
  margin: 0;
}

.form-card {
  background: white;
  border: 1px solid var(--border);
  border-radius: 12px;
  padding: 24px;
  margin-bottom: 20px;
}

.card-header {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 20px;
  padding-bottom: 16px;
  border-bottom: 2px solid var(--bg-light);
}

.card-header i {
  font-size: 20px;
  color: var(--primary);
}

.card-header h3 {
  font-size: 18px;
  font-weight: 700;
  color: var(--text-dark);
  margin: 0;
}

.form-row {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 20px;
  margin-bottom: 20px;
}

.form-group-modern {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.form-group-modern label {
  font-weight: 600;
  font-size: 14px;
  color: var(--text-dark);
}

.form-group-modern label .required {
  color: #ef4444;
  margin-left: 4px;
}

.form-group-modern input,
.form-group-modern select,
.form-group-modern textarea {
  padding: 12px 16px;
  border: 1px solid var(--border);
  border-radius: 8px;
  font-size: 14px;
  transition: all 0.2s;
}

.form-group-modern input:focus,
.form-group-modern select:focus,
.form-group-modern textarea:focus {
  outline: none;
  border-color: var(--primary);
  box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.form-group-modern input[readonly] {
  background: var(--bg-light);
  cursor: not-allowed;
}

.form-hint {
  font-size: 12px;
  color: var(--text-light);
  margin-top: 4px;
}

.price-preview {
  background: linear-gradient(135deg, var(--primary), #1e40af);
  color: white;
  padding: 20px;
  border-radius: 12px;
  text-align: center;
}

.price-preview-label {
  font-size: 14px;
  opacity: 0.9;
  margin-bottom: 8px;
}

.price-preview-value {
  font-size: 32px;
  font-weight: 700;
}

.form-actions {
  display: flex;
  gap: 12px;
  justify-content: flex-end;
  padding-top: 20px;
}

.btn-submit {
  padding: 12px 32px;
  background: var(--primary);
  color: white;
  border: none;
  border-radius: 8px;
  font-weight: 600;
  font-size: 14px;
  cursor: pointer;
  transition: all 0.2s;
  display: inline-flex;
  align-items: center;
  gap: 8px;
}

.btn-submit:hover {
  background: #1e40af;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
}

.btn-cancel {
  padding: 12px 32px;
  background: white;
  color: var(--text-dark);
  border: 1px solid var(--border);
  border-radius: 8px;
  font-weight: 600;
  font-size: 14px;
  cursor: pointer;
  transition: all 0.2s;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: 8px;
}

.btn-cancel:hover {
  background: var(--bg-light);
}

@media (max-width: 768px) {
  .form-row {
    grid-template-columns: 1fr;
  }
}
</style>

<!-- Page Header -->
<div class="invoice-form-header">
  <h1 class="invoice-form-title">
    <i class="fas fa-edit" style="color: var(--primary);"></i>
    Chỉnh sửa hóa đơn #<?php echo safe_html($id); ?>
  </h1>
  <div style="display: flex; gap: 12px;">
    <a href="<?php echo BASE_URL; ?>?act=hoadon-detail&id=<?php echo $id; ?>" class="btn btn-secondary">
      <i class="fas fa-eye"></i>
      Xem chi tiết
    </a>
    <a href="<?php echo BASE_URL; ?>?act=hoadon-list" class="btn btn-secondary">
      <i class="fas fa-arrow-left"></i>
      Quay lại
    </a>
  </div>
</div>

<?php if ($huy == 1): ?>
<div class="alert alert-error" style="margin-bottom: 20px;">
  <i class="fas fa-exclamation-triangle"></i>
  <strong>Cảnh báo:</strong> Hóa đơn này đã bị hủy. Không thể chỉnh sửa.
</div>
<?php else: ?>

<!-- Form -->
<form method="POST" action="<?php echo BASE_URL; ?>?act=hoadon-edit&id=<?php echo $id; ?>">
  
  <!-- Card 1: Thông tin khách hàng -->
  <div class="form-card">
    <div class="card-header">
      <i class="fas fa-user"></i>
      <h3>Thông tin khách hàng</h3>
    </div>
    
    <div class="form-group-modern">
      <label>Email khách hàng</label>
      <input type="email" name="email_nguoidung" value="<?php echo safe_html($email); ?>" readonly>
      <small class="form-hint">Email không thể thay đổi</small>
    </div>
  </div>

  <!-- Card 2: Thông tin tour -->
  <div class="form-card">
    <div class="card-header">
      <i class="fas fa-map-marked-alt"></i>
      <h3>Thông tin tour</h3>
    </div>
    
    <div class="form-group-modern">
      <label>Tour</label>
      <input type="hidden" name="id_goi" value="<?php echo $id_goi; ?>">
      <?php 
      $tour_hien_tai = null;
      if (!empty($tours)) {
        foreach ($tours as $tour) {
          if ($tour['id_goi'] == $id_goi) {
            $tour_hien_tai = $tour;
            break;
          }
        }
      }
      ?>
      <input type="text" 
             value="<?php echo $tour_hien_tai ? safe_html($tour_hien_tai['tengoi']) . ' - ' . number_format($tour_hien_tai['giagoi'] ?? 0) . ' VNĐ' : 'N/A'; ?>" 
             readonly>
      <small class="form-hint">Tour không thể thay đổi sau khi tạo hóa đơn</small>
      
      <?php if ($tour_hien_tai): ?>
      <input type="hidden" id="tourGiagoi" value="<?php echo $tour_hien_tai['giagoi'] ?? 0; ?>">
      <input type="hidden" id="tourGiatreem" value="<?php echo $tour_hien_tai['giatreem'] ?? 0; ?>">
      <input type="hidden" id="tourGiatrenho" value="<?php echo $tour_hien_tai['giatrenho'] ?? 0; ?>">
      <?php endif; ?>
    </div>

    <div class="form-row">
      <div class="form-group-modern">
        <label for="ngayvao">Ngày vào</label>
        <input type="date" name="ngayvao" id="ngayvao" value="<?php echo $ngayvao; ?>">
      </div>

      <div class="form-group-modern">
        <label for="ngayra">Ngày ra</label>
        <input type="date" name="ngayra" id="ngayra" value="<?php echo $ngayra; ?>">
      </div>
    </div>
  </div>

  <!-- Card 3: Số lượng khách -->
  <div class="form-card">
    <div class="card-header">
      <i class="fas fa-users"></i>
      <h3>Số lượng khách</h3>
    </div>
    
    <div class="form-row">
      <div class="form-group-modern">
        <label for="nguoilon">
          Người lớn <span class="required">*</span>
        </label>
        <input type="number" name="nguoilon" id="nguoilon" class="calculate-price" 
               value="<?php echo $nguoilon; ?>" min="0" required>
      </div>

      <div class="form-group-modern">
        <label for="treem">Trẻ em (6-11 tuổi)</label>
        <input type="number" name="treem" id="treem" class="calculate-price" 
               value="<?php echo $treem; ?>" min="0">
      </div>

      <div class="form-group-modern">
        <label for="trenho">Trẻ nhỏ (2-5 tuổi)</label>
        <input type="number" name="trenho" id="trenho" class="calculate-price" 
               value="<?php echo $trenho; ?>" min="0">
      </div>

      <div class="form-group-modern">
        <label for="embe">Em bé (dưới 2 tuổi)</label>
        <input type="number" name="embe" id="embe" value="<?php echo $embe; ?>" min="0">
      </div>
    </div>

    <!-- Tổng tiền -->
    <div class="price-preview">
      <p class="price-preview-label">Tổng tiền dự kiến</p>
      <h2 class="price-preview-value" id="totalPrice">0 VNĐ</h2>
    </div>
  </div>

  <!-- Card 4: Thông tin bổ sung -->
  <div class="form-card">
    <div class="card-header">
      <i class="fas fa-sticky-note"></i>
      <h3>Thông tin bổ sung</h3>
    </div>
    
    <div class="form-group-modern">
      <label for="ghichu">Ghi chú</label>
      <textarea name="ghichu" id="ghichu" rows="4" placeholder="Ghi chú đặc biệt (nếu có)"><?php echo safe_html($ghichu); ?></textarea>
    </div>

    <div class="form-group-modern">
      <label for="trangthai">Trạng thái</label>
      <select name="trangthai" id="trangthai">
        <option value="0" <?php echo ($trangthai == 0) ? 'selected' : ''; ?>>Chờ xác nhận</option>
        <option value="1" <?php echo ($trangthai == 1) ? 'selected' : ''; ?>>Đã xác nhận</option>
        <option value="2" <?php echo ($trangthai == 2) ? 'selected' : ''; ?>>Hoàn thành</option>
      </select>
    </div>

    <div class="form-actions">
      <a href="<?php echo BASE_URL; ?>?act=hoadon-detail&id=<?php echo $id; ?>" class="btn-cancel">
        <i class="fas fa-times"></i>
        Hủy bỏ
      </a>
      <button type="submit" class="btn-submit">
        <i class="fas fa-save"></i>
        Cập nhật hóa đơn
      </button>
    </div>
  </div>

</form>

<?php endif; ?>

<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script type="text/javascript">
$(document).ready(function() {
  // Tính tổng tiền khi thay đổi
  function calculateTotal() {
    var giagoi = parseFloat($('#tourGiagoi').val()) || 0;
    var giatreem = parseFloat($('#tourGiatreem').val()) || 0;
    var giatrenho = parseFloat($('#tourGiatrenho').val()) || 0;
    
    var nguoilon = parseInt($('#nguoilon').val()) || 0;
    var treem = parseInt($('#treem').val()) || 0;
    var trenho = parseInt($('#trenho').val()) || 0;
    
    var total = (nguoilon * giagoi) + (treem * giatreem) + (trenho * giatrenho);
    
    $('#totalPrice').text(total.toLocaleString('vi-VN') + ' VNĐ');
  }
  
  // Khi thay đổi số lượng
  $('.calculate-price').on('change keyup', function() {
    calculateTotal();
  });
  
  // Tính toán lần đầu
  calculateTotal();
});
</script>
