<?php
/**
 * View: Danh sách hóa đơn
 */

// Helper function để tránh lỗi htmlentities với null
function safe_html($value) {
    return htmlentities($value ?? '', ENT_QUOTES, 'UTF-8');
}

// Hàm chuyển đổi trạng thái
function getTrangThaiText($status) {
    switch($status) {
        case 0: return '<span class="badge badge-warning">Chờ xác nhận</span>';
        case 1: return '<span class="badge badge-info">Đã xác nhận</span>';
        case 2: return '<span class="badge badge-success">Hoàn thành</span>';
        case 3: return '<span class="badge badge-danger">Đã hủy</span>';
        default: return '<span class="badge badge-secondary">Không xác định</span>';
    }
}
?>

<div class="agile-grids">
    <!-- Thống kê nhanh -->
    <div class="row" style="margin-bottom: 20px;">
        <div class="col-md-3">
            <div class="stats-info widget">
                <h4>Tổng hóa đơn</h4>
                <div class="stats-num"><?php echo $statistics['total_hoadon'] ?? 0; ?></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-info widget">
                <h4>Chờ xác nhận</h4>
                <div class="stats-num" style="color: #f39c12;"><?php echo $statistics['cho_xacnhan'] ?? 0; ?></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-info widget">
                <h4>Đã xác nhận</h4>
                <div class="stats-num" style="color: #3498db;"><?php echo $statistics['da_xacnhan'] ?? 0; ?></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-info widget">
                <h4>Hoàn thành</h4>
                <div class="stats-num" style="color: #27ae60;"><?php echo $statistics['hoan_thanh'] ?? 0; ?></div>
            </div>
        </div>
    </div>

    <div class="agile-tables" style="padding: 0px;">
        <div class="w3l-table-info">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <h2>Quản lý hóa đơn</h2>
                <div>
                    <!-- Lọc theo trạng thái -->
                    <select id="filterStatus" class="form-control" style="display: inline-block; width: 200px; margin-right: 10px;">
                        <option value="">Tất cả trạng thái</option>
                        <option value="0" <?php echo (isset($filter_status) && $filter_status == '0') ? 'selected' : ''; ?>>Chờ xác nhận</option>
                        <option value="1" <?php echo (isset($filter_status) && $filter_status == '1') ? 'selected' : ''; ?>>Đã xác nhận</option>
                        <option value="2" <?php echo (isset($filter_status) && $filter_status == '2') ? 'selected' : ''; ?>>Hoàn thành</option>
                        <option value="3" <?php echo (isset($filter_status) && $filter_status == '3') ? 'selected' : ''; ?>>Đã hủy</option>
                    </select>
                    
                    <a href="<?php echo BASE_URL; ?>?act=hoadon-create" class="btn btn-success">
                        <i class="fa fa-plus"></i> Tạo hóa đơn mới
                    </a>
                </div>
            </div>
            
            <table id="table">
                <thead>
                    <tr>
                        <th style="text-align: center;">ID</th>
                        <th style="text-align: center;">Email khách</th>
                        <th style="text-align: center;">Tour</th>
                        <th style="text-align: center;">Số người</th>
                        <th style="text-align: center;">Ngày vào</th>
                        <th style="text-align: center;">Ngày ra</th>
                        <th style="text-align: center;">Ngày đặt</th>
                        <th style="text-align: center;">Trạng thái</th>
                        <th style="text-align: center;">Hành động</th>
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
                            $sophong = $hd['sophong'] ?? 0;
                            $ngaydat = $hd['ngaydat'] ?? '';
                            $trangthai = $hd['trangthai'] ?? 0;
                            $huy = $hd['huy'] ?? 0;
                            
                            $total_people = $nguoilon + $treem + $trenho + $embe;
                            
                            // Nếu đã hủy, hiển thị trạng thái hủy
                            if ($huy == 1) {
                                $trangthai = 3;
                            }
                    ?>		
                        <tr>
                            <td style="text-align: center;"><?php echo safe_html($id); ?></td>
                            <td><?php echo safe_html($email); ?></td>
                            <td><?php echo safe_html($ten_goi); ?></td>
                            <td style="text-align: center;">
                                <?php echo $total_people; ?> người
                                <br><small>(NL: <?php echo $nguoilon; ?>, TE: <?php echo $treem; ?>, TNH: <?php echo $trenho; ?>, EB: <?php echo $embe; ?>)</small>
                            </td>
                            <td style="text-align: center;"><?php echo $ngayvao ? date("d/m/Y", strtotime($ngayvao)) : 'N/A'; ?></td>
                            <td style="text-align: center;"><?php echo $ngayra ? date("d/m/Y", strtotime($ngayra)) : 'N/A'; ?></td>
                            <td style="text-align: center;"><?php echo $ngaydat ? date("d/m/Y H:i", strtotime($ngaydat)) : 'N/A'; ?></td>
                            <td style="text-align: center;"><?php echo getTrangThaiText($trangthai); ?></td>
                            <td style="width: 200px; text-align: center;">
                                <a href="<?php echo BASE_URL; ?>?act=hoadon-detail&id=<?php echo $id; ?>" class="btn btn-info btn-xs" title="Xem chi tiết">
                                    <i class="fa fa-eye"></i> Chi tiết
                                </a>
                                <a href="<?php echo BASE_URL; ?>?act=hoadon-edit&id=<?php echo $id; ?>" class="btn btn-warning btn-xs" title="Chỉnh sửa">
                                    <i class="fa fa-edit"></i> Sửa
                                </a>
                                <?php if ($huy != 1): ?>
                                <button onclick="updateStatus(<?php echo $id; ?>)" class="btn btn-primary btn-xs" title="Cập nhật trạng thái">
                                    <i class="fa fa-refresh"></i>
                                </button>
                                <?php endif; ?>
                                <a href="<?php echo BASE_URL; ?>?act=hoadon-delete&id=<?php echo $id; ?>" 
                                   class="btn btn-danger btn-xs" 
                                   onclick="return confirm('Bạn có chắc chắn muốn xóa hóa đơn này?')"
                                   title="Xóa">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php 
                        } // end foreach
                    } else {
                        echo '<tr><td colspan="9" style="text-align: center;">Chưa có hóa đơn nào</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<link rel="stylesheet" type="text/css" href="assets/css/table-style.css" />
<link rel="stylesheet" type="text/css" href="assets/css/basictable.css" />
<script type="text/javascript" src="assets/js/jquery.basictable.min.js"></script>

<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src="http://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.semanticui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.1/semantic.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.1/semantic.min.css">

<script type="text/javascript">
$(document).ready(function() {
    var dt = $('#table').DataTable({
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

// Hàm cập nhật trạng thái
function updateStatus(id) {
    var newStatus = prompt("Nhập trạng thái mới:\n0 = Chờ xác nhận\n1 = Đã xác nhận\n2 = Hoàn thành");
    
    if (newStatus !== null && newStatus !== '') {
        // Validate input
        newStatus = parseInt(newStatus);
        if (newStatus >= 0 && newStatus <= 2) {
            // Gửi AJAX request
            fetch('<?php echo BASE_URL; ?>?act=hoadon-update-status', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'id=' + id + '&trangthai=' + newStatus
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                alert('Có lỗi xảy ra: ' + error);
            });
        } else {
            alert('Trạng thái không hợp lệ!');
        }
    }
}
</script>

<style type="text/css">
    .dataTables_wrapper { margin-top: 20px; }
    
    .badge {
        padding: 5px 10px;
        border-radius: 3px;
        color: white;
        font-weight: bold;
    }
    
    .badge-warning { background-color: #f39c12; }
    .badge-info { background-color: #3498db; }
    .badge-success { background-color: #27ae60; }
    .badge-danger { background-color: #e74c3c; }
    .badge-secondary { background-color: #95a5a6; }
    
    .stats-info {
        background: white;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    
    .stats-info h4 {
        margin: 0 0 10px 0;
        font-size: 14px;
        color: #7f8c8d;
    }
    
    .stats-num {
        font-size: 28px;
        font-weight: bold;
        color: #2c3e50;
    }
    
    .btn-xs {
        padding: 3px 8px;
        font-size: 11px;
        margin: 2px;
    }
</style>
