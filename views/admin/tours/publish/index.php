<?php
/**
 * File: views/admin/tours/publish/index.php
 * Trang kiểm tra & publish tour
 * 
 * HƯỚNG DẪN: Copy file này vào views/admin/tours/publish/index.php
 */

ob_start();
?>

<!-- Breadcrumb -->
<ol class="breadcrumb">
    <li><a href="<?= BASE_URL ?>?act=admin">Dashboard</a></li>
    <li><a href="<?= BASE_URL ?>?act=admin-tours">Tour</a></li>
    <li class="active">Publish: <?= htmlspecialchars($tour['tengoi']) ?></li>
</ol>

<!-- Header -->
<div class="row" style="margin-bottom: 20px;">
    <div class="col-md-8">
        <h2 style="margin: 0 0 10px 0;">🚀 Publish Tour</h2>
        <h4 class="text-muted" style="margin: 0;"><?= htmlspecialchars($tour['tengoi']) ?></h4>
    </div>
    <div class="col-md-4 text-right">
        <a href="<?= BASE_URL ?>?act=admin-tours" class="btn btn-default">
            <i class="fa fa-arrow-left"></i> Quay lại
        </a>
    </div>
</div>

<!-- Thông báo -->
<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success">
        <i class="fa fa-check-circle"></i> <?= $_SESSION['success'] ?>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger">
        <i class="fa fa-exclamation-circle"></i> <?= $_SESSION['error'] ?>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<!-- Trạng thái hiện tại -->
<div class="row" style="margin-bottom: 30px;">
    <div class="col-md-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="fa fa-info-circle"></i> Trạng thái hiện tại
                </h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-3">
                        <strong>Trạng thái publish:</strong><br>
                        <?php
                        $badges = [
                            'draft' => '<span class="label label-default label-lg">📝 Draft</span>',
                            'internal' => '<span class="label label-warning label-lg">🔒 Nội bộ</span>',
                            'public' => '<span class="label label-success label-lg">🌍 Công khai</span>'
                        ];
                        echo $badges[$tour['publish_status']] ?? $badges['draft'];
                        ?>
                    </div>
                    <div class="col-md-3">
                        <strong>Độ hoàn thiện:</strong><br>
                        <div class="progress" style="margin: 5px 0 0 0; height: 25px;">
                            <div class="progress-bar <?= $tyLeHoanThanh >= 80 ? 'progress-bar-success' : ($tyLeHoanThanh >= 50 ? 'progress-bar-warning' : 'progress-bar-danger') ?>" 
                                 style="width: <?= $tyLeHoanThanh ?>%; line-height: 25px;">
                                <?= $tyLeHoanThanh ?>%
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <strong>Publish lần cuối:</strong><br>
                        <?= $tour['published_at'] ? date('d/m/Y H:i', strtotime($tour['published_at'])) : '<span class="text-muted">Chưa publish</span>' ?>
                    </div>
                    <div class="col-md-3 text-right">
                        <?php if ($coThePublish): ?>
                            <span class="text-success">
                                <i class="fa fa-check-circle fa-2x"></i><br>
                                <strong>Sẵn sàng publish!</strong>
                            </span>
                        <?php else: ?>
                            <span class="text-danger">
                                <i class="fa fa-exclamation-circle fa-2x"></i><br>
                                <strong>Chưa đủ điều kiện</strong>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Checklist & Actions -->
<div class="row">
    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="fa fa-check-square-o"></i> Checklist kiểm tra
                </h3>
            </div>
            <div class="panel-body">
                <?php foreach ($checklist as $categoryKey => $category): ?>
                    <?php
                    $totalItems = count($category['items']);
                    $passedItems = 0;
                    foreach ($category['items'] as $item) {
                        if ($item['status']) $passedItems++;
                    }
                    $categoryPercent = $totalItems > 0 ? round(($passedItems / $totalItems) * 100) : 0;
                    ?>
                    
                    <div class="checklist-category" style="margin-bottom: 25px;">
                        <!-- Category Header -->
                        <div class="checklist-header" style="margin-bottom: 10px;">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4 style="margin: 0;">
                                        <?php if ($passedItems === $totalItems): ?>
                                            <i class="fa fa-check-circle text-success"></i>
                                        <?php else: ?>
                                            <i class="fa fa-circle-o text-muted"></i>
                                        <?php endif; ?>
                                        <?= $category['name'] ?>
                                    </h4>
                                </div>
                                <div class="col-md-6 text-right">
                                    <small class="text-muted"><?= $passedItems ?>/<?= $totalItems ?> hoàn thành</small>
                                    <div class="progress" style="margin: 5px 0 0 0; height: 8px;">
                                        <div class="progress-bar <?= $categoryPercent === 100 ? 'progress-bar-success' : 'progress-bar-warning' ?>" 
                                             style="width: <?= $categoryPercent ?>%;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Category Items -->
                        <div class="checklist-items" style="padding-left: 30px;">
                            <?php foreach ($category['items'] as $itemKey => $item): ?>
                                <div class="checklist-item" style="padding: 8px 0; border-bottom: 1px solid #f0f0f0;">
                                    <label style="margin: 0; font-weight: normal; cursor: default;">
                                        <?php if ($item['status']): ?>
                                            <i class="fa fa-check-square-o fa-lg text-success"></i>
                                        <?php else: ?>
                                            <i class="fa fa-square-o fa-lg text-muted"></i>
                                        <?php endif; ?>
                                        <?= $item['label'] ?>
                                        
                                        <?php if (!$item['status']): ?>
                                            <span class="text-danger"><small>(Chưa hoàn thành)</small></span>
                                        <?php endif; ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    
    <!-- Actions Panel -->
    <div class="col-md-4">
        <!-- Quick Actions -->
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="fa fa-cog"></i> Thao tác
                </h3>
            </div>
            <div class="panel-body">
                <?php if ($tour['publish_status'] === 'draft'): ?>
                    <!-- Draft → Internal hoặc Public -->
                    <?php if ($coThePublish): ?>
                        <a href="<?= BASE_URL ?>?act=tour-publish-change&id_goi=<?= $tour['id_goi'] ?>&status=public" 
                           class="btn btn-success btn-lg btn-block"
                           onclick="return confirm('Publish công khai tour này?')">
                            <i class="fa fa-globe"></i> Publish công khai
                        </a>
                        
                        <a href="<?= BASE_URL ?>?act=tour-publish-change&id_goi=<?= $tour['id_goi'] ?>&status=internal" 
                           class="btn btn-warning btn-lg btn-block"
                           onclick="return confirm('Publish nội bộ để xem trước?')">
                            <i class="fa fa-lock"></i> Publish nội bộ
                        </a>
                    <?php else: ?>
                        <button class="btn btn-default btn-lg btn-block" disabled>
                            <i class="fa fa-exclamation-triangle"></i> Chưa đủ điều kiện
                        </button>
                        <p class="text-danger" style="margin-top: 10px;">
                            <small>Vui lòng hoàn thiện các mục bắt buộc trước khi publish.</small>
                        </p>
                    <?php endif; ?>
                    
                <?php elseif ($tour['publish_status'] === 'internal'): ?>
                    <!-- Internal → Public hoặc Draft -->
                    <a href="<?= BASE_URL ?>?act=tour-publish-change&id_goi=<?= $tour['id_goi'] ?>&status=public" 
                       class="btn btn-success btn-lg btn-block"
                       onclick="return confirm('Publish công khai tour này?')">
                        <i class="fa fa-globe"></i> Publish công khai
                    </a>
                    
                    <a href="<?= BASE_URL ?>?act=tour-publish-change&id_goi=<?= $tour['id_goi'] ?>&status=draft" 
                       class="btn btn-default btn-lg btn-block"
                       onclick="return confirm('Chuyển về Draft?')">
                        <i class="fa fa-undo"></i> Chuyển về Draft
                    </a>
                    
                <?php elseif ($tour['publish_status'] === 'public'): ?>
                    <!-- Public → Unpublish -->
                    <div class="alert alert-success">
                        <i class="fa fa-check-circle"></i> Tour đang công khai!
                    </div>
                    
                    <a href="<?= BASE_URL ?>?act=tour-publish-change&id_goi=<?= $tour['id_goi'] ?>&status=internal" 
                       class="btn btn-warning btn-lg btn-block"
                       onclick="return confirm('Gỡ xuống nội bộ?')">
                        <i class="fa fa-eye-slash"></i> Chuyển về nội bộ
                    </a>
                    
                    <a href="<?= BASE_URL ?>?act=tour-publish-change&id_goi=<?= $tour['id_goi'] ?>&status=draft" 
                       class="btn btn-default btn-lg btn-block"
                       onclick="return confirm('Chuyển về Draft?')">
                        <i class="fa fa-undo"></i> Chuyển về Draft
                    </a>
                <?php endif; ?>
                
                <hr>
                
                <!-- Links nhanh -->
                <h5><strong>Liên kết nhanh:</strong></h5>
                <a href="<?= BASE_URL ?>?act=admin-tour-edit&id=<?= $tour['id_goi'] ?>" class="btn btn-default btn-sm btn-block">
                    <i class="fa fa-edit"></i> Sửa thông tin tour
                </a>
                <a href="<?= BASE_URL ?>?act=tour-lichtrinh&id_goi=<?= $tour['id_goi'] ?>" class="btn btn-default btn-sm btn-block">
                    <i class="fa fa-calendar"></i> Quản lý lịch trình
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.label-lg {
    padding: 8px 15px;
    font-size: 14px;
}

.checklist-item:hover {
    background-color: #f9f9f9;
}
</style>

<?php
$content = ob_get_clean();
include './views/admin/layout.php';
?>