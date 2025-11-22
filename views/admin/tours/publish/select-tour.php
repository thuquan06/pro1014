<?php
/**
 * File: views/admin/tours/publish/select-tour.php
 * Trang chọn tour để publish
 */

ob_start();
?>

<ol class="breadcrumb">
    <li><a href="<?= BASE_URL ?>?act=admin">Dashboard</a></li>
    <li class="active">Chọn tour để publish</li>
</ol>

<h2>🚀 Chọn tour để kiểm tra publish</h2>

<div class="panel panel-default" style="margin-top: 20px;">
    <div class="panel-heading">
        <h3 class="panel-title">Danh sách tour</h3>
    </div>
    <div class="panel-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên tour</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($allTours)): ?>
                    <?php foreach ($allTours as $tour): ?>
                        <tr>
                            <td><?= $tour['id_goi'] ?? $tour['id'] ?></td>
                            <td><?= htmlspecialchars($tour['tengoi'] ?? $tour['ten_goi'] ?? 'N/A') ?></td>
                            <td>
                                <?php
                                $status = $tour['publish_status'] ?? 'draft';
                                $badges = [
                                    'draft' => '<span class="label label-default">Draft</span>',
                                    'internal' => '<span class="label label-warning">Nội bộ</span>',
                                    'public' => '<span class="label label-success">Công khai</span>'
                                ];
                                echo $badges[$status] ?? $badges['draft'];
                                ?>
                            </td>
                            <td>
                                <a href="<?= BASE_URL ?>?act=tour-publish&id_goi=<?= $tour['id_goi'] ?? $tour['id'] ?>" 
                                   class="btn btn-primary btn-sm">
                                    <i class="fa fa-check-square-o"></i> Kiểm tra
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center text-muted">
                            <i class="fa fa-inbox fa-3x" style="margin: 20px 0;"></i>
                            <p>Chưa có tour nào</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
$content = ob_get_clean();
include './views/admin/layout.php';
?>