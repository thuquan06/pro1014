<?php
/**
 * File: views/admin/tours/publish/list-by-status.php
 * Danh sách tour theo trạng thái publish
 */

ob_start();
?>

<ol class="breadcrumb">
    <li><a href="<?= BASE_URL ?>?act=admin">Dashboard</a></li>
    <li><a href="<?= BASE_URL ?>?act=tour-publish-dashboard">Publish Dashboard</a></li>
    <li class="active">Tour <?= $statusName ?></li>
</ol>

<div class="row" style="margin-bottom: 20px;">
    <div class="col-md-8">
        <h2>
            <?php
            $icons = [
                'draft' => '📝',
                'internal' => '🔒',
                'public' => '🌍'
            ];
            echo $icons[$status] ?? '';
            ?>
            Tour <?= $statusName ?>
        </h2>
        <p class="text-muted">Tổng: <?= count($tours) ?> tour</p>
    </div>
    <div class="col-md-4 text-right">
        <a href="<?= BASE_URL ?>?act=tour-publish-dashboard" class="btn btn-default">
            <i class="fa fa-arrow-left"></i> Quay lại Dashboard
        </a>
    </div>
</div>

<!-- Danh sách tour -->
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            <i class="fa fa-list"></i> Danh sách
        </h3>
    </div>
    <div class="panel-body">
        <?php if (empty($tours)): ?>
            <div class="text-center text-muted" style="padding: 40px;">
                <i class="fa fa-inbox fa-4x"></i>
                <p style="margin-top: 20px; font-size: 16px;">Không có tour nào</p>
            </div>
        <?php else: ?>
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th width="80">ID</th>
                        <th>Tên tour</th>
                        <th width="120">Giá</th>
                        <th width="100">Số ngày</th>
                        <th width="150">Ngày tạo</th>
                        <th width="200">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tours as $tour): ?>
                        <tr>
                            <td><?= $tour['id_goi'] ?></td>
                            <td>
                                <strong><?= htmlspecialchars($tour['tengoi']) ?></strong>
                                <?php if (!empty($tour['vitri'])): ?>
                                    <br><small class="text-muted">
                                        <i class="fa fa-map-marker"></i> <?= htmlspecialchars($tour['vitri']) ?>
                                    </small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($tour['giagoi'] > 0): ?>
                                    <span class="text-success">
                                        <strong><?= number_format($tour['giagoi']) ?>đ</strong>
                                    </span>
                                <?php else: ?>
                                    <span class="text-muted">Chưa có</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($tour['songay'] > 0): ?>
                                    <?= $tour['songay'] ?>N<?= ($tour['songay']-1) ?>Đ
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td><?= date('d/m/Y', strtotime($tour['ngaydang'])) ?></td>
                            <td>
                                <a href="<?= BASE_URL ?>?act=tour-publish&id_goi=<?= $tour['id_goi'] ?>" 
                                   class="btn btn-primary btn-xs"
                                   title="Kiểm tra & Publish">
                                    <i class="fa fa-rocket"></i> Publish
                                </a>
                                
                                <a href="<?= BASE_URL ?>?act=admin-tour-edit&id=<?= $tour['id_goi'] ?>" 
                                   class="btn btn-warning btn-xs"
                                   title="Sửa tour">
                                    <i class="fa fa-edit"></i> Sửa
                                </a>
                                
                                <a href="<?= BASE_URL ?>?act=tour-view&id=<?= $tour['id_goi'] ?>" 
                                   class="btn btn-info btn-xs"
                                   title="Xem chi tiết"
                                   target="_blank">
                                    <i class="fa fa-eye"></i> Xem
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<!-- Thống kê nhanh -->
<div class="row">
    <div class="col-md-4">
        <div class="panel panel-info">
            <div class="panel-body">
                <div class="stat-icon">
                    <i class="fa fa-list"></i>
                </div>
                <div class="stat-value">
                    <?= count($tours) ?>
                </div>
                <div class="stat-label">Tổng tour</div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="panel panel-success">
            <div class="panel-body">
                <div class="stat-icon">
                    <i class="fa fa-check"></i>
                </div>
                <div class="stat-value">
                    <?php
                    $coGia = 0;
                    foreach ($tours as $t) {
                        if ($t['giagoi'] > 0) $coGia++;
                    }
                    echo $coGia;
                    ?>
                </div>
                <div class="stat-label">Đã có giá</div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="panel panel-warning">
            <div class="panel-body">
                <div class="stat-icon">
                    <i class="fa fa-calendar"></i>
                </div>
                <div class="stat-value">
                    <?php
                    $coNgay = 0;
                    foreach ($tours as $t) {
                        if ($t['songay'] > 0) $coNgay++;
                    }
                    echo $coNgay;
                    ?>
                </div>
                <div class="stat-label">Có lịch trình</div>
            </div>
        </div>
    </div>
</div>

<style>
.stat-icon {
    font-size: 40px;
    color: #ddd;
    float: left;
    margin-right: 15px;
}

.stat-value {
    font-size: 32px;
    font-weight: bold;
    line-height: 1;
    margin-bottom: 5px;
}

.stat-label {
    color: #777;
    font-size: 14px;
}
</style>

<?php
$content = ob_get_clean();
include './views/admin/layout.php';
?>