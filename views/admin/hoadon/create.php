<?php
/**
 * Tạo Hóa đơn - Modern Interface
 * Updated: 2025-11-25
 */

function safe_html($value) {
    return htmlentities($value ?? '', ENT_QUOTES, 'UTF-8');
}
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
    <i class="fas fa-plus-circle" style="color: var(--primary);"></i>
    Tạo hóa đơn mới
  </h1>
  <a href="<?php echo BASE_URL; ?>?act=hoadon-list" class="btn btn-secondary">
    <i class="fas fa-arrow-left"></i>
    Quay lại
  </a>
</div>

<!-- Form -->
<form method="POST" action="<?php echo BASE_URL; ?>?act=hoadon-create">
  
  <!-- Card 1: Thông tin khách hàng -->
  <div class="form-card">
    <div class="card-header">
      <i class="fas fa-user"></i>
      <h3>Thông tin khách hàng</h3>
    </div>
    
    <div class="form-group-modern">
      <label for="email_nguoidung">
        Email khách hàng <span class="required">*</span>
      </label>
      <input type="email" name="email_nguoidung" id="email_nguoidung" required placeholder="email@example.com">
    </div>
  </div>

  <!-- Card 2: Thông tin tour -->
  <div class="form-card">
    <div class="card-header">
      <i class="fas fa-map-marked-alt"></i>
      <h3>Thông tin tour</h3>
    </div>
    
    <div class="form-group-modern">
      <label for="tourSelect">
        Chọn tour <span class="required">*</span>
      </label>
      <select name="id_goi" id="tourSelect" required>
        <option value="">-- Chọn tour --</option>
        <?php if (!empty($tours)): ?>
          <?php foreach ($tours as $tour): ?>
            <option value="<?php echo $tour['id_goi']; ?>" 
                    data-giagoi="<?php echo $tour['giagoi'] ?? 0; ?>"
                    data-giatreem="<?php echo $tour['giatreem'] ?? 0; ?>"
                    data-giatrenho="<?php echo $tour['giatrenho'] ?? 0; ?>">
              <?php echo safe_html($tour['tengoi']); ?> - 
              <?php echo number_format($tour['giagoi'] ?? 0); ?> VNĐ
            </option>
          <?php endforeach; ?>
        <?php endif; ?>
      </select>
    </div>

    <div class="form-row">
      <div class="form-group-modern">
        <label for="ngayvao">Ngày vào</label>
        <input type="date" name="ngayvao" id="ngayvao">
      </div>

      <div class="form-group-modern">
        <label for="ngayra">Ngày ra</label>
        <input type="date" name="ngayra" id="ngayra">
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
        <input type="number" name="nguoilon" id="nguoilon" class="calculate-price" value="1" min="0" required>
      </div>

      <div class="form-group-modern">
        <label for="treem">Trẻ em (6-11 tuổi)</label>
        <input type="number" name="treem" id="treem" class="calculate-price" value="0" min="0">
      </div>

      <div class="form-group-modern">
        <label for="trenho">Trẻ nhỏ (2-5 tuổi)</label>
        <input type="number" name="trenho" id="trenho" class="calculate-price" value="0" min="0">
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
      <textarea name="ghichu" id="ghichu" rows="4" placeholder="Ghi chú đặc biệt (nếu có)"></textarea>
    </div>

    <div class="form-group-modern">
      <label for="trangthai">Trạng thái</label>
      <select name="trangthai" id="trangthai">
        <option value="0">Chờ xác nhận</option>
        <option value="1">Đã xác nhận</option>
        <option value="2">Hoàn thành</option>
      </select>
    </div>

    <div class="form-actions">
      <a href="<?php echo BASE_URL; ?>?act=hoadon-list" class="btn-cancel">
        <i class="fas fa-times"></i>
        Hủy bỏ
      </a>
      <button type="submit" class="btn-submit">
        <i class="fas fa-save"></i>
        Tạo hóa đơn
      </button>
    </div>
  </div>

</form>

<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script type="text/javascript">
$(document).ready(function() {
  // Tính tổng tiền khi thay đổi
  function calculateTotal() {
    var tourSelect = $('#tourSelect option:selected');
    var giagoi = parseFloat(tourSelect.attr('data-giagoi')) || 0;
    var giatreem = parseFloat(tourSelect.attr('data-giatreem')) || 0;
    var giatrenho = parseFloat(tourSelect.attr('data-giatrenho')) || 0;
    
    var nguoilon = parseInt($('#nguoilon').val()) || 0;
    var treem = parseInt($('#treem').val()) || 0;
    var trenho = parseInt($('#trenho').val()) || 0;
    
    var total = (nguoilon * giagoi) + (treem * giatreem) + (trenho * giatrenho);
    
    $('#totalPrice').text(total.toLocaleString('vi-VN') + ' VNĐ');
  }
  
  // Khi chọn tour hoặc thay đổi số lượng
  $('#tourSelect, .calculate-price').on('change keyup', function() {
    calculateTotal();
  });
  
  // Tính toán lần đầu
  calculateTotal();
});
</script>
