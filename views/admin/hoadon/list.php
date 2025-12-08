<?php
/**
 * Hóa đơn List - Modern Interface
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
?>

<style>
.invoice-header {
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

.invoice-title {
  font-size: 32px;
  font-weight: 700;
  color: var(--text-dark);
  margin: 0;
  display: flex;
  align-items: center;
  gap: 12px;
}

.invoice-title i {
  font-size: 36px;
}

.invoice-actions {
  display: flex;
  gap: 12px;
  align-items: center;
  flex-wrap: wrap;
}

.filter-select {
  padding: 10px 16px;
  border: 1px solid var(--border);
  border-radius: 8px;
  font-size: 14px;
  min-width: 200px;
  transition: all 0.2s;
}

.filter-select:focus {
  outline: none;
  border-color: var(--primary);
  box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 20px;
  margin-bottom: 24px;
}

.stat-box {
  background: white;
  border: 1px solid var(--border);
  border-radius: 16px;
  padding: 24px;
  display: flex;
  align-items: center;
  gap: 20px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
  transition: transform 0.2s, box-shadow 0.2s;
}

.stat-box:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
}

.stat-icon {
  width: 50px;
  height: 50px;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 24px;
  color: white;
}

.stat-icon.blue { background: #2563eb; }
.stat-icon.orange { background: #f59e0b; }
.stat-icon.cyan { background: #06b6d4; }
.stat-icon.green { background: #10b981; }

.stat-info h4 {
  font-size: 28px;
  font-weight: 700;
  color: var(--text-dark);
  margin: 0;
}

.stat-info p {
  font-size: 14px;
  color: var(--text-light);
  margin: 0;
}

.invoice-card {
  background: white;
  border: 1px solid var(--border);
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.invoice-table {
  width: 100%;
  border-collapse: collapse;
}

.invoice-table thead {
  background: var(--bg-light);
}

.invoice-table th {
  padding: 16px;
  text-align: center;
  font-weight: 600;
  font-size: 13px;
  color: var(--text-dark);
  text-transform: uppercase;
  letter-spacing: 0.5px;
  border-bottom: 2px solid var(--border);
}

.invoice-table td {
  padding: 16px;
  border-bottom: 1px solid var(--border);
  font-size: 14px;
  color: var(--text-dark);
  text-align: center;
}

.invoice-table td.tour-cell {
  max-width: 250px;
  text-align: left;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.invoice-table tbody tr:hover {
  background: var(--bg-light);
}

.status-badge {
  display: inline-flex;
  align-items: center;
  gap: 4px;
  padding: 6px 12px;
  border-radius: 20px;
  font-size: 12px;
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

.btn-action {
  padding: 6px 10px;
  border: none;
  border-radius: 6px;
  font-size: 12px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: 4px;
  margin: 2px;
}

.btn-action.view {
  background: #dbeafe;
  color: #1e40af;
}

.btn-action.view:hover {
  background: #2563eb;
  color: white;
}

.btn-action.edit {
  background: #fef3c7;
  color: #78350f;
}

.btn-action.edit:hover {
  background: #f59e0b;
  color: white;
}

.btn-action.refresh {
  background: #d1fae5;
  color: #065f46;
}

.btn-action.refresh:hover {
  background: #10b981;
  color: white;
}

.btn-action.delete {
  background: #fee2e2;
  color: #991b1b;
}

.btn-action.delete:hover {
  background: #ef4444;
  color: white;
}

.people-info {
  font-size: 12px;
  color: var(--text-light);
  margin-top: 4px;
}

@media (max-width: 768px) {
  .invoice-header {
    padding: 16px;
  }
  
  .invoice-title {
    font-size: 24px;
  }
  
  .invoice-title i {
    font-size: 28px;
  }
  
  .invoice-actions {
    width: 100%;
  }
  
  .filter-select {
    width: 100%;
    min-width: auto;
  }
  
  .stats-grid {
    grid-template-columns: 1fr;
  }
  
  .invoice-table {
    font-size: 12px;
  }
  
  .invoice-table th,
  .invoice-table td {
    padding: 10px 8px;
  }
  
  .btn-action {
    padding: 4px 8px;
    font-size: 11px;
  }
}
</style>

<!-- Page Header -->
<div class="invoice-header">
  <h1 class="invoice-title">
    <i class="fas fa-file-invoice" style="color: var(--primary);"></i>
    Quản lý Hóa đơn
  </h1>
  
  <div class="invoice-actions">
    <select id="filterStatus" class="filter-select">
      <option value="">Tất cả trạng thái</option>
      <option value="0" <?php echo (isset($filter_status) && $filter_status == '0') ? 'selected' : ''; ?>>Chưa xuất</option>
      <option value="1" <?php echo (isset($filter_status) && $filter_status == '1') ? 'selected' : ''; ?>>Đã xuất</option>
      <option value="2" <?php echo (isset($filter_status) && $filter_status == '2') ? 'selected' : ''; ?>>Đã gửi</option>
      <option value="3" <?php echo (isset($filter_status) && $filter_status == '3') ? 'selected' : ''; ?>>Hủy</option>
    </select>
    
    <a href="<?php echo BASE_URL; ?>?act=hoadon-create" class="btn btn-primary">
      <i class="fas fa-plus-circle"></i>
      Tạo hóa đơn mới
    </a>
  </div>
</div>

<!-- Stats Grid -->
<div class="stats-grid">
  <div class="stat-box">
    <div class="stat-icon blue">
      <i class="fas fa-file-invoice"></i>
    </div>
    <div class="stat-info">
      <h4><?php echo $statistics['total_hoadon'] ?? 0; ?></h4>
      <p>Tổng hóa đơn</p>
    </div>
  </div>
  
  <div class="stat-box">
    <div class="stat-icon orange">
      <i class="fas fa-file-invoice"></i>
    </div>
    <div class="stat-info">
      <h4><?php echo $statistics['chua_xuat'] ?? 0; ?></h4>
      <p>Chưa xuất</p>
    </div>
  </div>
  
  <div class="stat-box">
    <div class="stat-icon cyan">
      <i class="fas fa-file-pdf"></i>
    </div>
    <div class="stat-info">
      <h4><?php echo $statistics['da_xuat'] ?? 0; ?></h4>
      <p>Đã xuất</p>
    </div>
  </div>
  
  <div class="stat-box">
    <div class="stat-icon green">
      <i class="fas fa-paper-plane"></i>
    </div>
    <div class="stat-info">
      <h4><?php echo $statistics['da_gui'] ?? 0; ?></h4>
      <p>Đã gửi</p>
    </div>
  </div>
</div>

<!-- Table -->
<div class="invoice-card">
  <table class="invoice-table" id="table">
    <thead>
      <tr>
        <th width="80">ID</th>
        <th>Email khách</th>
        <th>Tour</th>
        <th width="120">Số người</th>
        <th width="120">Ngày vào</th>
        <th width="120">Ngày ra</th>
        <th width="150">Ngày đặt</th>
        <th width="140">Trạng thái</th>
        <th width="200">Hành động</th>
      </tr>
    </thead>
    <tbody>
      <?php 
      if (!empty($hoadons)) {
        foreach ($hoadons as $hd) { 
          $id = $hd['id_hoadon'] ?? '';
          $email = $hd['email_nguoidung'] ?? '';
          $ten_goi = $hd['ten_goi'] ?? 'N/A';
          $nguoilon = $hd['nguoilon'] ?? 0;
          $treem = $hd['treem'] ?? 0;
          $trenho = $hd['trenho'] ?? 0;
          $embe = $hd['embe'] ?? 0;
          $ngayvao = $hd['ngayvao'] ?? '';
          $ngayra = $hd['ngayra'] ?? '';
          $ngaydat = $hd['ngaydat'] ?? '';
          // Sử dụng trạng thái hóa đơn mới
          $trangthai = $hd['trang_thai_hoa_don'] ?? 0;
          // Nếu booking bị hủy (trang_thai = 5) thì hóa đơn cũng hủy
          if (($hd['trangthai'] ?? 0) == 5) {
            $trangthai = 3; // Hủy
          }
          
          $total_people = $nguoilon + $treem + $trenho;
      ?>		
        <tr>
          <td><strong>#<?php echo safe_html($id); ?></strong></td>
          <td style="text-align: left;"><?php echo safe_html($email); ?></td>
          <td class="tour-cell" title="<?php echo safe_html($ten_goi); ?>">
            <?php 
            $ten_goi_display = safe_html($ten_goi);
            if (mb_strlen($ten_goi_display) > 40) {
              echo mb_substr($ten_goi_display, 0, 40) . '...';
            } else {
              echo $ten_goi_display;
            }
            ?>
          </td>
          <td>
            <strong><?php echo $total_people; ?></strong>
            <div class="people-info">
              NL:<?php echo $nguoilon; ?> TE:<?php echo $treem; ?><br>
              TNH:<?php echo $trenho; ?>
            </div>
          </td>
          <td><?php echo $ngayvao ? date("d/m/Y", strtotime($ngayvao)) : '-'; ?></td>
          <td><?php echo $ngayra ? date("d/m/Y", strtotime($ngayra)) : '-'; ?></td>
          <td><?php echo $ngaydat ? date("d/m/Y H:i", strtotime($ngaydat)) : '-'; ?></td>
          <td><?php echo getTrangThaiText($trangthai); ?></td>
          <td>
            <a href="<?php echo BASE_URL; ?>?act=hoadon-detail&id=<?php echo $id; ?>" 
               class="btn-action view" title="Xem chi tiết">
              <i class="fas fa-eye"></i>
            </a>
            <a href="<?php echo BASE_URL; ?>?act=hoadon-print&id=<?php echo $id; ?>" 
               class="btn-action" style="background: #10b981; color: white;" title="Xuất hóa đơn" target="_blank">
              <i class="fas fa-file-pdf"></i>
            </a>
            <a href="<?php echo BASE_URL; ?>?act=hoadon-edit&id=<?php echo $id; ?>" 
               class="btn-action edit" title="Chỉnh sửa">
              <i class="fas fa-edit"></i>
            </a>
            <?php if ($trangthai != 3): // Không phải trạng thái Hủy ?>
              <?php if ($trangthai == 0): // Chưa xuất - có thể xuất hóa đơn ?>
              <button onclick="if(confirm('Xuất hóa đơn #<?php echo $id; ?>?')) { updateInvoiceStatus(<?php echo $id; ?>, 1); }" 
                      class="btn-action refresh" title="Xuất hóa đơn">
                <i class="fas fa-file-pdf"></i> Xuất
              </button>
              <?php elseif ($trangthai == 1): // Đã xuất - có thể gửi email ?>
              <button onclick="if(confirm('Gửi hóa đơn #<?php echo $id; ?> qua email?')) { updateInvoiceStatus(<?php echo $id; ?>, 2); }" 
                      class="btn-action refresh" title="Gửi email" style="background: #10b981; color: white;">
                <i class="fas fa-paper-plane"></i> Gửi
              </button>
              <?php endif; ?>
            <?php endif; ?>
            <a href="<?php echo BASE_URL; ?>?act=hoadon-delete&id=<?php echo $id; ?>" 
               class="btn-action delete"
               onclick="return confirm('Bạn có chắc chắn muốn xóa hóa đơn này?')"
               title="Xóa">
              <i class="fas fa-trash"></i>
            </a>
          </td>
        </tr>
      <?php 
        }
      } else {
        echo '<tr><td colspan="9"><div style="text-align: center; padding: 40px; color: var(--text-light);">Chưa có hóa đơn nào</div></td></tr>';
      }
      ?>
    </tbody>
  </table>
</div>

<link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>

<script type="text/javascript">
$(document).ready(function() {
  $('#table').DataTable({
    "dom": '<"pull-right"f>t<"row mt-1" <"col-sm-3" l><"col-sm-6" <"pull-right" p>>>',
    "order": [[0, "desc"]],
    "language": {
      "lengthMenu": "Hiển thị _MENU_ trên 1 trang",
      "zeroRecords": "Không tìm thấy hóa đơn",
      "infoEmpty": "Chưa có hóa đơn",
      "infoFiltered": "(lọc từ _MAX_ bản ghi)",
      "sSearch": "Tìm kiếm",
      "oPaginate": {
        "sFirst": "Đầu",
        "sPrevious": "Trước",
        "sNext": "Tiếp",
        "sLast": "Cuối"
      }
    }
  });
  
  // Lọc theo trạng thái
  $('#filterStatus').on('change', function() {
    var status = $(this).val();
    if (status === '') {
      window.location = '<?php echo BASE_URL; ?>?act=hoadon-list';
    } else {
      window.location = '<?php echo BASE_URL; ?>?act=hoadon-filter&trangthai=' + status;
    }
  });
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

// Hàm cập nhật trạng thái (giữ lại để tương thích nếu cần)
function updateStatus(id) {
  var newStatus = prompt("Nhập trạng thái mới:\n0 = Chưa xuất\n1 = Đã xuất\n2 = Đã gửi\n3 = Hủy");
  
  if (newStatus !== null && newStatus !== '') {
    newStatus = parseInt(newStatus);
    if (newStatus >= 0 && newStatus <= 3) {
      updateInvoiceStatus(id, newStatus);
    } else {
      alert('Trạng thái không hợp lệ!');
    }
  }
}
</script>
