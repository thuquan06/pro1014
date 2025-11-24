<?php
/**
 * View: Chi tiết hóa đơn
 */

// Helper function
function safe_html($value) {
    return htmlentities($value ?? '', ENT_QUOTES, 'UTF-8');
}

function getTrangThaiText($status) {
    switch($status) {
        case 0: return '<span class="label label-warning">Chờ xác nhận</span>';
        case 1: return '<span class="label label-info">Đã xác nhận</span>';
        case 2: return '<span class="label label-success">Hoàn thành</span>';
        case 3: return '<span class="label label-danger">Đã hủy</span>';
        default: return '<span class="label label-default">Không xác định</span>';
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
$phongdon = $hoadon['phongdon'] ?? 0;
$ngayvao = $hoadon['ngayvao'] ?? '';
$ngayra = $hoadon['ngayra'] ?? '';
$sophong = $hoadon['sophong'] ?? 0;
$ghichu = $hoadon['ghichu'] ?? '';
$ngaydat = $hoadon['ngaydat'] ?? '';
$trangthai = $hoadon['trangthai'] ?? 0;
$huy = $hoadon['huy'] ?? 0;
$ngaycapnhat = $hoadon['ngaycapnhat'] ?? '';

$giagoi = $hoadon['giagoi'] ?? 0;
$giatreem = $hoadon['giatreem'] ?? 0;
$giatrenho = $hoadon['giatrenho'] ?? 0;

// Tính tổng tiền
$tong_tien = $total ?? 0;

if ($huy == 1) {
    $trangthai = 3;
}
?>

<div class="agile-grids">
    <div class="agile-tables" style="padding: 20px;">
        <div class="w3l-table-info">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2>Chi tiết hóa đơn #<?php echo safe_html($id); ?></h2>
                <div>
                    <a href="<?php echo BASE_URL; ?>?act=hoadon-list" class="btn btn-default">
                        <i class="fa fa-arrow-left"></i> Quay lại
                    </a>
                    <a href="<?php echo BASE_URL; ?>?act=hoadon-edit&id=<?php echo $id; ?>" class="btn btn-warning">
                        <i class="fa fa-edit"></i> Chỉnh sửa
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
                    }" class="btn btn-danger">
                        <i class="fa fa-ban"></i> Hủy hóa đơn
                    </button>
                    <?php endif; ?>
                </div>
            </div>

            <div class="row">
                <!-- Thông tin khách hàng -->
                <div class="col-md-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title"><i class="fa fa-user"></i> Thông tin khách hàng</h3>
                        </div>
                        <div class="panel-body">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">Email:</th>
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
                </div>

                <!-- Thông tin tour -->
                <div class="col-md-6">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h3 class="panel-title"><i class="fa fa-map-marker"></i> Thông tin tour</h3>
                        </div>
                        <div class="panel-body">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">Tên tour:</th>
                                    <td>
                                        <?php echo safe_html($ten_goi); ?>
                                        <?php if ($id_goi): ?>
                                        <a href="<?php echo BASE_URL; ?>?act=admin-tour-edit&id=<?php echo $id_goi; ?>" target="_blank">
                                            <i class="fa fa-external-link"></i>
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
            </div>

            <!-- Chi tiết số người và giá -->
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h3 class="panel-title"><i class="fa fa-users"></i> Chi tiết số người và giá tiền</h3>
                        </div>
                        <div class="panel-body">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Loại khách</th>
                                        <th style="text-align: center;">Số lượng</th>
                                        <th style="text-align: right;">Đơn giá</th>
                                        <th style="text-align: right;">Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Người lớn</td>
                                        <td style="text-align: center;"><?php echo $nguoilon; ?></td>
                                        <td style="text-align: right;"><?php echo number_format($giagoi); ?> VNĐ</td>
                                        <td style="text-align: right;"><?php echo number_format($nguoilon * $giagoi); ?> VNĐ</td>
                                    </tr>
                                    <tr>
                                        <td>Trẻ em (6-11 tuổi)</td>
                                        <td style="text-align: center;"><?php echo $treem; ?></td>
                                        <td style="text-align: right;"><?php echo number_format($giatreem); ?> VNĐ</td>
                                        <td style="text-align: right;"><?php echo number_format($treem * $giatreem); ?> VNĐ</td>
                                    </tr>
                                    <tr>
                                        <td>Trẻ nhỏ (2-5 tuổi)</td>
                                        <td style="text-align: center;"><?php echo $trenho; ?></td>
                                        <td style="text-align: right;"><?php echo number_format($giatrenho); ?> VNĐ</td>
                                        <td style="text-align: right;"><?php echo number_format($trenho * $giatrenho); ?> VNĐ</td>
                                    </tr>
                                    <tr>
                                        <td>Em bé (dưới 2 tuổi)</td>
                                        <td style="text-align: center;"><?php echo $embe; ?></td>
                                        <td style="text-align: right;">0 VNĐ</td>
                                        <td style="text-align: right;">0 VNĐ</td>
                                    </tr>
                                    <tr style="background-color: #f0f0f0; font-weight: bold;">
                                        <td colspan="3" style="text-align: right;">Tổng cộng:</td>
                                        <td style="text-align: right; color: #e74c3c; font-size: 16px;">
                                            <?php echo number_format($tong_tien); ?> VNĐ
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ghi chú -->
            <?php if ($ghichu): ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-warning">
                        <div class="panel-heading">
                            <h3 class="panel-title"><i class="fa fa-sticky-note"></i> Ghi chú</h3>
                        </div>
                        <div class="panel-body">
                            <?php echo nl2br(safe_html($ghichu)); ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

        </div>
    </div>
</div>

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
        padding: 15px;
    }
    
    .table-bordered {
        border: 1px solid #ddd;
    }
    
    .table-bordered th,
    .table-bordered td {
        border: 1px solid #ddd;
        padding: 10px;
    }
    
    .label {
        padding: 5px 10px;
        border-radius: 3px;
        color: white;
        font-weight: bold;
        font-size: 12px;
    }
    
    .label-warning { background-color: #f39c12; }
    .label-info { background-color: #3498db; }
    .label-success { background-color: #27ae60; }
    .label-danger { background-color: #e74c3c; }
    .label-default { background-color: #95a5a6; }
</style>
