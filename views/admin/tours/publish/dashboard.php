<?php
/**
 * File: views/admin/tours/publish/dashboard.php
 * Dashboard publish
 */

ob_start();
?>

<ol class="breadcrumb">
    <li><a href="<?= BASE_URL ?>?act=admin">Dashboard</a></li>
    <li class="active">Publish Dashboard</li>
</ol>

<h2>📊 Publish Dashboard</h2>

<!-- Thống kê -->
<div class="row" style="margin-top: 20px;">
    <?php
    $stats = [
        'draft' => ['count' => 0, 'color' => 'default', 'icon' => 'fa-file-o', 'label' => 'Draft'],
        'internal' => ['count' => 0, 'color' => 'warning', 'icon' => 'fa-lock', 'label' => 'Nội bộ'],
        'public' => ['count' => 0, 'color' => 'success', 'icon' => 'fa-globe', 'label' => 'Công khai']
    ];
    
    foreach ($thongke as $tk) {
        if (isset($stats[$tk['publish_status']])) {
            $stats[$tk['publish_status']]['count'] = $tk['total'];
        }
    }
    ?>
    
    <?php foreach ($stats as $key => $stat): ?>
        <div class="col-md-4">
            <div class="panel panel-<?= $stat['color'] ?>">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa <?= $stat['icon'] ?> fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge"><?= $stat['count'] ?></div>
                            <div><?= $stat['label'] ?></div>
                        </div>
                    </div>
                </div>
                <a href="<?= BASE_URL ?>?act=tour-publish-list&status=<?= $key ?>">
                    <div class="panel-footer">
                        <span class="pull-left">Xem tất cả tour</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Tour cần review -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="fa fa-tasks"></i> Tour cần review (<?= count($tourCanReview) ?>)
                </h3>
            </div>
            <div class="panel-body">
                <?php if (empty($tourCanReview)): ?>
                    <div class="text-center text-muted" style="padding: 30px;">
                        <i class="fa fa-check-circle fa-3x"></i>
                        <p style="margin-top: 15px;">Không có tour nào cần review</p>
                    </div>
                <?php else: ?>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tên tour</th>
                                <th>Ngày tạo</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tourCanReview as $tour): ?>
                                <tr>
                                    <td><?= $tour['id_goi'] ?></td>
                                    <td><?= htmlspecialchars($tour['tengoi']) ?></td>
                                    <td><?= date('d/m/Y', strtotime($tour['ngaydang'])) ?></td>
                                    <td>
                                        <a href="<?= BASE_URL ?>?act=tour-publish&id_goi=<?= $tour['id_goi'] ?>" 
                                           class="btn btn-primary btn-sm">
                                            <i class="fa fa-check-square-o"></i> Review
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
.huge {
    font-size: 40px;
    font-weight: bold;
}
</style>

<?php
$content = ob_get_clean();
include './views/admin/layout.php';
?>