<?php
/**
 * File: views/admin/tours/chinhsach/index.php
 * Danh sách chính sách tour - Tabs theo loại
 */

ob_start();
?>

<!-- Breadcrumb -->
<ol class="breadcrumb">
    <li><a href="<?= BASE_URL ?>?act=admin">Dashboard</a></li>
    <li><a href="<?= BASE_URL ?>?act=admin-tours">Tour</a></li>
    <li class="active">Chính sách Tour #<?= $idGoi ?></li>
</ol>

<!-- Header với Dropdown chọn tour -->
<div class="row" style="margin-bottom: 20px;">
    <div class="col-md-4">
        <h2 style="margin: 0 0 15px 0;">📋 Quản lý Chính sách Tour</h2>
        
        <!-- Dropdown chọn tour -->
        <div class="form-group" style="margin-bottom: 0;">
            <label for="select-tour" style="font-weight: 600; margin-bottom: 8px; display: block;">
                <i class="fa fa-map"></i> Chọn tour để xem:
            </label>
            <select id="select-tour" class="form-control input-lg" style="font-size: 15px;" onchange="if(this.value) window.location.href='<?= BASE_URL ?>?act=tour-chinhsach&id_goi=' + this.value">
                <option value="">-- Chọn tour --</option>
                <?php if (!empty($allTours)): ?>
                    <?php foreach ($allTours as $tour): ?>
                        <option value="<?= $tour['id'] ?>" <?= $tour['id'] == $idGoi ? 'selected' : '' ?>>
                            #<?= $tour['id'] ?> - <?= htmlspecialchars($tour['ten_goi']) ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
    </div>
    
    <div class="col-md-8 text-right" style="padding-top: 45px;">
        <a href="<?= BASE_URL ?>?act=tour-chinhsach-them&id_goi=<?= $idGoi ?>" class="btn btn-primary">
            <i class="fa fa-plus"></i> Thêm chính sách
        </a>
        <a href="<?= BASE_URL ?>?act=admin-tours" class="btn btn-default">
            <i class="fa fa-arrow-left"></i> Quay lại
        </a>
    </div>
</div>

<!-- Thông báo -->
<?php if (isset($_SESSION['success'])): ?>
    <div class="succWrap">
        <i class="fa fa-check-circle"></i> <?= $_SESSION['success'] ?>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="errorWrap">
        <i class="fa fa-exclamation-circle"></i> <?= $_SESSION['error'] ?>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<!-- Tabs chính sách -->
<div class="panel panel-default">
    <div class="panel-body">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist" style="margin-bottom: 20px;">
            <li role="presentation" class="active">
                <a href="#huy_doi" aria-controls="huy_doi" role="tab" data-toggle="tab">
                    <i class="fa fa-exchange"></i> Hủy/Đổi Tour
                    <span class="badge"><?= count($chinhsach['huy_doi']) ?></span>
                </a>
            </li>
            <li role="presentation">
                <a href="#suc_khoe" aria-controls="suc_khoe" role="tab" data-toggle="tab">
                    <i class="fa fa-heartbeat"></i> Sức khỏe
                    <span class="badge"><?= count($chinhsach['suc_khoe']) ?></span>
                </a>
            </li>
            <li role="presentation">
                <a href="#hanh_ly" aria-controls="hanh_ly" role="tab" data-toggle="tab">
                    <i class="fa fa-suitcase"></i> Hành lý
                    <span class="badge"><?= count($chinhsach['hanh_ly']) ?></span>
                </a>
            </li>
            <li role="presentation">
                <a href="#thanh_toan" aria-controls="thanh_toan" role="tab" data-toggle="tab">
                    <i class="fa fa-credit-card"></i> Thanh toán
                    <span class="badge"><?= count($chinhsach['thanh_toan']) ?></span>
                </a>
            </li>
            <li role="presentation">
                <a href="#visa" aria-controls="visa" role="tab" data-toggle="tab">
                    <i class="fa fa-passport"></i> Visa
                    <span class="badge"><?= count($chinhsach['visa']) ?></span>
                </a>
            </li>
            <li role="presentation">
                <a href="#bao_hiem" aria-controls="bao_hiem" role="tab" data-toggle="tab">
                    <i class="fa fa-shield"></i> Bảo hiểm
                    <span class="badge"><?= count($chinhsach['bao_hiem']) ?></span>
                </a>
            </li>
            <li role="presentation">
                <a href="#khac" aria-controls="khac" role="tab" data-toggle="tab">
                    <i class="fa fa-file-text"></i> Khác
                    <span class="badge"><?= count($chinhsach['khac']) ?></span>
                </a>
            </li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <!-- HỦY/ĐỔI TOUR -->
            <div role="tabpanel" class="tab-pane active" id="huy_doi">
                <?php if (empty($chinhsach['huy_doi'])): ?>
                    <div class="alert alert-info">
                        <i class="fa fa-info-circle"></i> Chưa có chính sách hủy/đổi tour.
                    </div>
                <?php else: ?>
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th width="60">#</th>
                                <th>Nội dung</th>
                                <th width="120">Trước X ngày</th>
                                <th width="120">Hoàn tiền %</th>
                                <th width="150">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($chinhsach['huy_doi'] as $index => $cs): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= nl2br(htmlspecialchars($cs['noidung'])) ?></td>
                                    <td class="text-center">
                                        <?php if ($cs['so_ngay_truoc']): ?>
                                            <span class="label label-warning"><?= $cs['so_ngay_truoc'] ?> ngày</span>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($cs['phantram_hoantien']): ?>
                                            <span class="label label-success"><?= $cs['phantram_hoantien'] ?>%</span>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?= BASE_URL ?>?act=tour-chinhsach-sua&id=<?= $cs['id'] ?>&id_goi=<?= $idGoi ?>" 
                                           class="btn btn-warning btn-sm">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="<?= BASE_URL ?>?act=tour-chinhsach-xoa&id=<?= $cs['id'] ?>&id_goi=<?= $idGoi ?>" 
                                           class="btn btn-danger btn-sm"
                                           onclick="return confirm('Bạn có chắc muốn xóa?')">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>

            <!-- SỨC KHỎE -->
            <div role="tabpanel" class="tab-pane" id="suc_khoe">
                <?php if (empty($chinhsach['suc_khoe'])): ?>
                    <div class="alert alert-info">
                        <i class="fa fa-info-circle"></i> Chưa có chính sách về sức khỏe.
                    </div>
                <?php else: ?>
                    <?php foreach ($chinhsach['suc_khoe'] as $cs): ?>
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-10">
                                        <?= nl2br(htmlspecialchars($cs['noidung'])) ?>
                                    </div>
                                    <div class="col-md-2 text-right">
                                        <a href="<?= BASE_URL ?>?act=tour-chinhsach-sua&id=<?= $cs['id'] ?>&id_goi=<?= $idGoi ?>" 
                                           class="btn btn-warning btn-sm">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="<?= BASE_URL ?>?act=tour-chinhsach-xoa&id=<?= $cs['id'] ?>&id_goi=<?= $idGoi ?>" 
                                           class="btn btn-danger btn-sm"
                                           onclick="return confirm('Bạn có chắc muốn xóa?')">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- HÀNH LÝ -->
            <div role="tabpanel" class="tab-pane" id="hanh_ly">
                <?php if (empty($chinhsach['hanh_ly'])): ?>
                    <div class="alert alert-info">
                        <i class="fa fa-info-circle"></i> Chưa có chính sách về hành lý.
                    </div>
                <?php else: ?>
                    <?php foreach ($chinhsach['hanh_ly'] as $cs): ?>
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-10">
                                        <?= nl2br(htmlspecialchars($cs['noidung'])) ?>
                                    </div>
                                    <div class="col-md-2 text-right">
                                        <a href="<?= BASE_URL ?>?act=tour-chinhsach-sua&id=<?= $cs['id'] ?>&id_goi=<?= $idGoi ?>" 
                                           class="btn btn-warning btn-sm">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="<?= BASE_URL ?>?act=tour-chinhsach-xoa&id=<?= $cs['id'] ?>&id_goi=<?= $idGoi ?>" 
                                           class="btn btn-danger btn-sm"
                                           onclick="return confirm('Bạn có chắc muốn xóa?')">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- THANH TOÁN -->
            <div role="tabpanel" class="tab-pane" id="thanh_toan">
                <?php if (empty($chinhsach['thanh_toan'])): ?>
                    <div class="alert alert-info">
                        <i class="fa fa-info-circle"></i> Chưa có chính sách thanh toán.
                    </div>
                <?php else: ?>
                    <?php foreach ($chinhsach['thanh_toan'] as $cs): ?>
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-10">
                                        <?= nl2br(htmlspecialchars($cs['noidung'])) ?>
                                    </div>
                                    <div class="col-md-2 text-right">
                                        <a href="<?= BASE_URL ?>?act=tour-chinhsach-sua&id=<?= $cs['id'] ?>&id_goi=<?= $idGoi ?>" 
                                           class="btn btn-warning btn-sm">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="<?= BASE_URL ?>?act=tour-chinhsach-xoa&id=<?= $cs['id'] ?>&id_goi=<?= $idGoi ?>" 
                                           class="btn btn-danger btn-sm"
                                           onclick="return confirm('Bạn có chắc muốn xóa?')">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- VISA -->
            <div role="tabpanel" class="tab-pane" id="visa">
                <?php if (empty($chinhsach['visa'])): ?>
                    <div class="alert alert-info">
                        <i class="fa fa-info-circle"></i> Chưa có chính sách về visa.
                    </div>
                <?php else: ?>
                    <?php foreach ($chinhsach['visa'] as $cs): ?>
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-10">
                                        <?= nl2br(htmlspecialchars($cs['noidung'])) ?>
                                    </div>
                                    <div class="col-md-2 text-right">
                                        <a href="<?= BASE_URL ?>?act=tour-chinhsach-sua&id=<?= $cs['id'] ?>&id_goi=<?= $idGoi ?>" 
                                           class="btn btn-warning btn-sm">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="<?= BASE_URL ?>?act=tour-chinhsach-xoa&id=<?= $cs['id'] ?>&id_goi=<?= $idGoi ?>" 
                                           class="btn btn-danger btn-sm"
                                           onclick="return confirm('Bạn có chắc muốn xóa?')">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- BẢO HIỂM -->
            <div role="tabpanel" class="tab-pane" id="bao_hiem">
                <?php if (empty($chinhsach['bao_hiem'])): ?>
                    <div class="alert alert-info">
                        <i class="fa fa-info-circle"></i> Chưa có chính sách bảo hiểm.
                    </div>
                <?php else: ?>
                    <?php foreach ($chinhsach['bao_hiem'] as $cs): ?>
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-10">
                                        <?= nl2br(htmlspecialchars($cs['noidung'])) ?>
                                    </div>
                                    <div class="col-md-2 text-right">
                                        <a href="<?= BASE_URL ?>?act=tour-chinhsach-sua&id=<?= $cs['id'] ?>&id_goi=<?= $idGoi ?>" 
                                           class="btn btn-warning btn-sm">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="<?= BASE_URL ?>?act=tour-chinhsach-xoa&id=<?= $cs['id'] ?>&id_goi=<?= $idGoi ?>" 
                                           class="btn btn-danger btn-sm"
                                           onclick="return confirm('Bạn có chắc muốn xóa?')">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- KHÁC -->
            <div role="tabpanel" class="tab-pane" id="khac">
                <?php if (empty($chinhsach['khac'])): ?>
                    <div class="alert alert-info">
                        <i class="fa fa-info-circle"></i> Chưa có chính sách khác.
                    </div>
                <?php else: ?>
                    <?php foreach ($chinhsach['khac'] as $cs): ?>
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-10">
                                        <?= nl2br(htmlspecialchars($cs['noidung'])) ?>
                                    </div>
                                    <div class="col-md-2 text-right">
                                        <a href="<?= BASE_URL ?>?act=tour-chinhsach-sua&id=<?= $cs['id'] ?>&id_goi=<?= $idGoi ?>" 
                                           class="btn btn-warning btn-sm">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="<?= BASE_URL ?>?act=tour-chinhsach-xoa&id=<?= $cs['id'] ?>&id_goi=<?= $idGoi ?>" 
                                           class="btn btn-danger btn-sm"
                                           onclick="return confirm('Bạn có chắc muốn xóa?')">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
.nav-tabs > li > a {
    font-weight: 600;
}

.nav-tabs > li > a .badge {
    margin-left: 5px;
}

.panel-body {
    line-height: 1.8;
}

/* Dropdown chọn tour */
#select-tour {
    border: 2px solid #ddd;
    border-radius: 6px;
    padding: 10px 15px;
    font-size: 15px;
    transition: all 0.3s;
}

#select-tour:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    outline: none;
}

#select-tour option {
    padding: 10px;
}
</style>

<?php
$content = ob_get_clean();
include './views/admin/layout.php';
?>