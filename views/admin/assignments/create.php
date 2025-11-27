<?php
/**
 * Tạo Phân công HDV - Modern Interface với Cảnh báo Trùng lịch
 * UC-Assign-Guide: Phân công HDV với cảnh báo trùng lịch
 */

function safe_html($value) {
    return htmlentities($value ?? '', ENT_QUOTES, 'UTF-8');
}

function formatDate($date) {
    return $date ? date('d/m/Y', strtotime($date)) : '-';
}

$error = $error ?? null;
$conflictDetails = $conflictDetails ?? [];
$departurePlan = $departurePlan ?? null;
$departurePlanId = $departurePlanId ?? null;
$guides = $guides ?? [];
$departurePlans = $departurePlans ?? [];
?>

<style>
.conflict-warning {
  background: #fef3c7;
  border: 2px solid #f59e0b;
  border-radius: 12px;
  padding: 20px;
  margin-bottom: 20px;
}

.conflict-warning h4 {
  color: #78350f;
  margin: 0 0 12px 0;
  font-size: 18px;
}

.conflict-item {
  background: white;
  padding: 12px;
  border-radius: 8px;
  margin-bottom: 8px;
  border-left: 4px solid #f59e0b;
}

.form-card {
  background: white;
  border: 1px solid var(--border);
  border-radius: 12px;
  padding: 24px;
  margin-bottom: 20px;
}

.form-group-modern {
  display: flex;
  flex-direction: column;
  gap: 8px;
  margin-bottom: 20px;
}

.form-group-modern label {
  font-weight: 600;
  font-size: 14px;
  color: var(--text-dark);
}

.form-group-modern input,
.form-group-modern select {
  padding: 12px 16px;
  border: 1px solid var(--border);
  border-radius: 8px;
  font-size: 14px;
}

.form-group-modern input:focus,
.form-group-modern select:focus {
  outline: none;
  border-color: var(--primary);
  box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
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
  cursor: pointer;
}

.btn-warning {
  padding: 12px 32px;
  background: #f59e0b;
  color: white;
  border: none;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
}

.btn-cancel {
  padding: 12px 32px;
  background: white;
  color: var(--text-dark);
  border: 1px solid var(--border);
  border-radius: 8px;
  font-weight: 600;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
}
</style>

<h1 style="font-size: 28px; font-weight: 700; margin-bottom: 24px;">
  <i class="fas fa-calendar-plus" style="color: var(--primary);"></i>
  Phân công Hướng dẫn viên
</h1>

<?php if ($error): ?>
  <div style="padding: 16px; background: #fee2e2; color: #991b1b; border-radius: 8px; margin-bottom: 20px;">
    <i class="fas fa-exclamation-circle"></i> <?= safe_html($error) ?>
  </div>
<?php endif; ?>

<?php if (!empty($conflictDetails)): ?>
  <div class="conflict-warning">
    <h4><i class="fas fa-exclamation-triangle"></i> Cảnh báo: Trùng lịch!</h4>
    <p style="margin-bottom: 16px; color: #78350f;">HDV này đã có lịch phân công trùng với khoảng thời gian bạn chọn:</p>
    <?php foreach ($conflictDetails as $conflict): ?>
      <div class="conflict-item">
        <strong><?= safe_html($conflict['ten_tour'] ?? 'Tour') ?></strong><br>
        <small>
          Ngày: <?= formatDate($conflict['ngay_bat_dau']) ?> - <?= formatDate($conflict['ngay_ket_thuc']) ?><br>
          Lịch khởi hành: <?= formatDate($conflict['ngay_khoi_hanh']) ?> <?= $conflict['gio_khoi_hanh'] ? date('H:i', strtotime($conflict['gio_khoi_hanh'])) : '' ?>
        </small>
      </div>
    <?php endforeach; ?>
    <p style="margin-top: 16px; color: #78350f; font-weight: 600;">
      Bạn có muốn tiếp tục phân công không? (Có thể gây xung đột lịch trình)
    </p>
  </div>
<?php endif; ?>

<form method="POST" action="<?= BASE_URL ?>?act=admin-assignment-create<?= $departurePlanId ? '&departure_plan_id=' . $departurePlanId : '' ?>">
  <?php if (!empty($conflictDetails)): ?>
    <input type="hidden" name="force_assign" value="1">
  <?php endif; ?>

  <div class="form-card">
    <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 20px;">Thông tin phân công</h3>
    
    <?php if ($departurePlan): ?>
      <div style="background: var(--bg-light); padding: 16px; border-radius: 8px; margin-bottom: 20px;">
        <h4><?= safe_html($departurePlan['tengoi'] ?? 'Tour') ?></h4>
        <p><strong>Ngày khởi hành:</strong> <?= formatDate($departurePlan['ngay_khoi_hanh']) ?></p>
        <p><strong>Giờ khởi hành:</strong> <?= $departurePlan['gio_khoi_hanh'] ? date('H:i', strtotime($departurePlan['gio_khoi_hanh'])) : '-' ?></p>
      </div>
      <input type="hidden" name="id_lich_khoi_hanh" value="<?= $departurePlan['id'] ?>">
    <?php else: ?>
      <div class="form-group-modern">
        <label>Lịch khởi hành <span style="color: #ef4444;">*</span></label>
        <select name="id_lich_khoi_hanh" id="id_lich_khoi_hanh" required onchange="loadAssignmentDates()">
          <option value="">-- Chọn lịch khởi hành --</option>
          <?php foreach ($departurePlans as $dp): 
            $ngay_gio = '';
            if ($dp['ngay_khoi_hanh']) {
              $ngay_gio = date('d/m/Y', strtotime($dp['ngay_khoi_hanh']));
              if ($dp['gio_khoi_hanh']) {
                $ngay_gio .= ' ' . date('H:i', strtotime($dp['gio_khoi_hanh']));
              }
            }
            // Format ngày khởi hành cho data attribute (YYYY-MM-DD)
            $ngayKhoiHanhFormatted = '';
            if (!empty($dp['ngay_khoi_hanh'])) {
              $ngayKhoiHanhFormatted = date('Y-m-d', strtotime($dp['ngay_khoi_hanh']));
            }
            // Lấy số ngày của tour
            $songay = $dp['songay'] ?? 0;
          ?>
            <option value="<?= $dp['id'] ?>" 
                    data-ngay-khoi-hanh="<?= $ngayKhoiHanhFormatted ?>"
                    data-songay="<?= $songay ?>">
              <?= safe_html($dp['tengoi'] ?? 'Tour') ?> - <?= $ngay_gio ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
    <?php endif; ?>

    <div class="form-group-modern">
      <label>Hướng dẫn viên <span style="color: #ef4444;">*</span></label>
      <select name="id_hdv" id="id_hdv" required onchange="checkSchedule()">
        <option value="">-- Chọn HDV --</option>
        <?php foreach ($guides as $guide): ?>
          <option value="<?= $guide['id'] ?>" <?= (isset($_GET['id_hdv']) && $_GET['id_hdv'] == $guide['id']) ? 'selected' : '' ?>>
            <?= safe_html($guide['ho_ten']) ?> 
            <?php if ($guide['email']): ?>
              (<?= safe_html($guide['email']) ?>)
            <?php endif; ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="form-group-modern">
      <label>Vai trò</label>
      <select name="vai_tro">
        <option value="HDV chính">HDV chính</option>
        <option value="HDV phụ">HDV phụ</option>
        <option value="Trợ lý">Trợ lý</option>
      </select>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
      <div class="form-group-modern">
        <label>Ngày bắt đầu <span style="color: #ef4444;">*</span></label>
        <input type="date" 
               name="ngay_bat_dau" 
               id="ngay_bat_dau" 
               value="<?= $departurePlan && !empty($departurePlan['ngay_khoi_hanh']) ? date('Y-m-d', strtotime($departurePlan['ngay_khoi_hanh'])) : '' ?>"
               required 
               onchange="checkSchedule()">
      </div>

      <div class="form-group-modern">
        <label>Ngày kết thúc <span style="color: #ef4444;">*</span></label>
        <input type="date" 
               name="ngay_ket_thuc" 
               id="ngay_ket_thuc"
               value="<?php 
                 if ($departurePlan && !empty($departurePlan['ngay_khoi_hanh'])) {
                   $ngayKhoiHanh = strtotime($departurePlan['ngay_khoi_hanh']);
                   $songay = $departurePlan['songay'] ?? 1;
                   // Ngày kết thúc = ngày khởi hành + số ngày - 1
                   $ngayKetThuc = date('Y-m-d', strtotime('+' . ($songay - 1) . ' days', $ngayKhoiHanh));
                   echo $ngayKetThuc;
                 }
               ?>"
               required 
               onchange="checkSchedule()">
      </div>
    </div>

    <div class="form-group-modern">
      <label>Lương/Thù lao (VNĐ)</label>
      <input type="number" name="luong" step="0.01" min="0" placeholder="VD: 5000000">
    </div>

    <div class="form-group-modern">
      <label>Ghi chú</label>
      <textarea name="ghi_chu" style="padding: 12px 16px; border: 1px solid var(--border); border-radius: 8px; min-height: 100px;"></textarea>
    </div>
  </div>

  <div class="form-actions">
    <a href="<?= BASE_URL ?>?act=admin-assignments" class="btn-cancel">
      <i class="fas fa-times"></i> Hủy
    </a>
    <?php if (!empty($conflictDetails)): ?>
      <button type="submit" class="btn-warning">
        <i class="fas fa-exclamation-triangle"></i> Vẫn phân công (Có trùng lịch)
      </button>
    <?php else: ?>
      <button type="submit" class="btn-submit">
        <i class="fas fa-save"></i> Phân công HDV
      </button>
    <?php endif; ?>
  </div>
</form>

<script>
function loadAssignmentDates() {
  const lichKhoiHanhSelect = document.getElementById('id_lich_khoi_hanh');
  const ngayBatDauInput = document.getElementById('ngay_bat_dau');
  const ngayKetThucInput = document.getElementById('ngay_ket_thuc');
  
  if (!lichKhoiHanhSelect || !ngayBatDauInput || !ngayKetThucInput) {
    return;
  }
  
  const selectedOption = lichKhoiHanhSelect.options[lichKhoiHanhSelect.selectedIndex];
  const ngayKhoiHanh = selectedOption.getAttribute('data-ngay-khoi-hanh');
  const songay = parseInt(selectedOption.getAttribute('data-songay')) || 1;
  
  if (ngayKhoiHanh && ngayKhoiHanh.trim() !== '') {
    // Ngày bắt đầu = ngày khởi hành
    ngayBatDauInput.value = ngayKhoiHanh;
    
    // Ngày kết thúc = ngày khởi hành + số ngày - 1
    const ngayKhoiHanhDate = new Date(ngayKhoiHanh);
    ngayKhoiHanhDate.setDate(ngayKhoiHanhDate.getDate() + (songay - 1));
    
    // Format thành YYYY-MM-DD
    const ngayKetThuc = ngayKhoiHanhDate.toISOString().split('T')[0];
    ngayKetThucInput.value = ngayKetThuc;
    
    // Trigger check schedule để kiểm tra trùng lịch
    checkSchedule();
  } else {
    // Nếu không có ngày khởi hành, xóa giá trị
    ngayBatDauInput.value = '';
    ngayKetThucInput.value = '';
  }
}

function checkSchedule() {
  const hdvId = document.getElementById('id_hdv').value;
  const ngayBatDau = document.getElementById('ngay_bat_dau').value;
  const ngayKetThuc = document.getElementById('ngay_ket_thuc').value;
  
  // Validation sẽ được kiểm tra ở server
  // Có thể thêm AJAX check ở đây nếu cần
}

// Tự động load ngày khi trang được tải (nếu đã có lịch khởi hành được chọn)
document.addEventListener('DOMContentLoaded', function() {
  const lichKhoiHanhSelect = document.getElementById('id_lich_khoi_hanh');
  if (lichKhoiHanhSelect && lichKhoiHanhSelect.value) {
    loadAssignmentDates();
  }
  
  // Nếu đã có departurePlan được chọn từ URL
  <?php if ($departurePlan && !empty($departurePlan['ngay_khoi_hanh'])): ?>
    // Đã tự động điền ở server-side, chỉ cần trigger check schedule
    checkSchedule();
  <?php endif; ?>
});
</script>



