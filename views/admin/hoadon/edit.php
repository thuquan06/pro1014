<?php
/**
 * View: Chỉnh sửa hóa đơn
 */

// Helper function
function safe_html($value) {
    return htmlentities($value ?? '', ENT_QUOTES, 'UTF-8');
}

$id = $hoadon['id_hoadon'] ?? '';
$email = $hoadon['email_nguoidung'] ?? '';
$id_goi = $hoadon['id_goi'] ?? '';
$id_ks = $hoadon['id_ks'] ?? '';
$nguoilon = $hoadon['nguoilon'] ?? 1;
$treem = $hoadon['treem'] ?? 0;
$trenho = $hoadon['trenho'] ?? 0;
$embe = $hoadon['embe'] ?? 0;
$phongdon = $hoadon['phongdon'] ?? 0;
$ngayvao = $hoadon['ngayvao'] ?? '';
$ngayra = $hoadon['ngayra'] ?? '';
$sophong = $hoadon['sophong'] ?? 1;
$ghichu = $hoadon['ghichu'] ?? '';
$trangthai = $hoadon['trangthai'] ?? 0;
$huy = $hoadon['huy'] ?? 0;
?>

<div class="agile-grids">
    <div class="agile-tables" style="padding: 20px;">
        <div class="w3l-table-info">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2>Chỉnh sửa hóa đơn #<?php echo safe_html($id); ?></h2>
                <div>
                    <a href="<?php echo BASE_URL; ?>?act=hoadon-detail&id=<?php echo $id; ?>" class="btn btn-info">
                        <i class="fa fa-eye"></i> Xem chi tiết
                    </a>
                    <a href="<?php echo BASE_URL; ?>?act=hoadon-list" class="btn btn-default">
                        <i class="fa fa-arrow-left"></i> Quay lại
                    </a>
                </div>
            </div>

            <?php if ($huy == 1): ?>
            <div class="alert alert-danger">
                <strong><i class="fa fa-warning"></i> Cảnh báo:</strong> Hóa đơn này đã bị hủy. Không thể chỉnh sửa.
            </div>
            <?php else: ?>

            <form method="POST" action="<?php echo BASE_URL; ?>?act=hoadon-edit&id=<?php echo $id; ?>" class="form-horizontal">
                
                <!-- Thông tin khách hàng -->
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-user"></i> Thông tin khách hàng</h3>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Email khách hàng <span style="color: red;">*</span></label>
                            <div class="col-sm-10">
                                <input type="email" class="form-control" name="email_nguoidung" 
                                       value="<?php echo safe_html($email); ?>" required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Thông tin tour -->
                <div class="panel panel-success">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-map-marker"></i> Thông tin tour</h3>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Chọn tour <span style="color: red;">*</span></label>
                            <div class="col-sm-10">
                                <select class="form-control" name="id_goi" id="tourSelect" required>
                                    <option value="">-- Chọn tour --</option>
                                    <?php if (!empty($tours)): ?>
                                        <?php foreach ($tours as $tour): ?>
                                            <option value="<?php echo $tour['id_goi']; ?>" 
                                                    <?php echo ($tour['id_goi'] == $id_goi) ? 'selected' : ''; ?>
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
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">ID Khách sạn</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" name="id_ks" 
                                       value="<?php echo safe_html($id_ks); ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Ngày vào</label>
                            <div class="col-sm-10">
                                <input type="date" class="form-control" name="ngayvao" 
                                       value="<?php echo $ngayvao; ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Ngày ra</label>
                            <div class="col-sm-10">
                                <input type="date" class="form-control" name="ngayra" 
                                       value="<?php echo $ngayra; ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Số lượng khách -->
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-users"></i> Số lượng khách</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Người lớn <span style="color: red;">*</span></label>
                                    <div class="col-sm-8">
                                        <input type="number" class="form-control calculate-price" name="nguoilon" 
                                               id="nguoilon" value="<?php echo $nguoilon; ?>" min="0" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Trẻ em (6-11 tuổi)</label>
                                    <div class="col-sm-8">
                                        <input type="number" class="form-control calculate-price" name="treem" 
                                               id="treem" value="<?php echo $treem; ?>" min="0">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Trẻ nhỏ (2-5 tuổi)</label>
                                    <div class="col-sm-8">
                                        <input type="number" class="form-control calculate-price" name="trenho" 
                                               id="trenho" value="<?php echo $trenho; ?>" min="0">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Em bé (dưới 2 tuổi)</label>
                                    <div class="col-sm-8">
                                        <input type="number" class="form-control" name="embe" 
                                               value="<?php echo $embe; ?>" min="0">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tổng tiền dự kiến -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-info" style="margin-top: 15px;">
                                    <strong>Tổng tiền dự kiến: </strong>
                                    <span id="totalPrice" style="font-size: 18px; color: #e74c3c;">0 VNĐ</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Thông tin phòng -->
                <div class="panel panel-warning">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-bed"></i> Thông tin phòng</h3>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Số phòng</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" name="sophong" 
                                       value="<?php echo $sophong; ?>" min="1">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Số phòng đơn</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" name="phongdon" 
                                       value="<?php echo $phongdon; ?>" min="0">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ghi chú và trạng thái -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-sticky-note"></i> Thông tin bổ sung</h3>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Ghi chú</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" name="ghichu" rows="4"><?php echo safe_html($ghichu); ?></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Trạng thái</label>
                            <div class="col-sm-10">
                                <select class="form-control" name="trangthai">
                                    <option value="0" <?php echo ($trangthai == 0) ? 'selected' : ''; ?>>Chờ xác nhận</option>
                                    <option value="1" <?php echo ($trangthai == 1) ? 'selected' : ''; ?>>Đã xác nhận</option>
                                    <option value="2" <?php echo ($trangthai == 2) ? 'selected' : ''; ?>>Hoàn thành</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fa fa-save"></i> Cập nhật hóa đơn
                        </button>
                        <a href="<?php echo BASE_URL; ?>?act=hoadon-detail&id=<?php echo $id; ?>" class="btn btn-default btn-lg">
                            <i class="fa fa-times"></i> Hủy
                        </a>
                    </div>
                </div>

            </form>

            <?php endif; ?>

        </div>
    </div>
</div>

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

<style type="text/css">
    .panel {
        border-radius: 5px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }
    
    .panel-heading {
        border-radius: 5px 5px 0 0;
        padding: 12px 15px;
    }
    
    .panel-title {
        font-size: 16px;
        font-weight: bold;
    }
    
    .panel-body {
        padding: 20px;
    }
    
    .form-horizontal .form-group {
        margin-bottom: 20px;
    }
    
    .control-label {
        padding-top: 7px;
        text-align: left;
        font-weight: 600;
    }
    
    .form-control {
        border-radius: 3px;
        border: 1px solid #ddd;
        padding: 8px 12px;
    }
    
    .form-control:focus {
        border-color: #3498db;
        box-shadow: 0 0 5px rgba(52, 152, 219, 0.3);
    }
    
    .alert {
        padding: 15px;
        border-radius: 3px;
        margin-bottom: 20px;
    }
    
    .alert-danger {
        background-color: #f2dede;
        border-color: #ebccd1;
        color: #a94442;
    }
    
    .alert-info {
        background-color: #d9edf7;
        border-color: #bce8f1;
        color: #31708f;
    }
</style>
