<?php
/**
 * File: views/admin/tour/chinhsach/index.php
 * Quản lý chính sách hủy/đổi
 */

ob_start();
?>

<!-- Breadcrumb -->
<ol class="breadcrumb">
    <li><a href="<?= BASE_URL ?>?act=admin">Dashboard</a></li>
    <li><a href="<?= BASE_URL ?>?act=admin-tours">Tour</a></li>
    <li class="active">Chính sách Tour #<?= $idGoi ?></li>
</ol>

<!-- Header -->
<div class="row" style="margin-bottom: 20px;">
    <div class="col-md-8">
        <h2 style="margin: 0;">📋 Quản lý Chính sách</h2>
        <p class="text-muted">Tour ID: <?= $idGoi ?></p>
    </div>
    <div class="col-md-4 text-right">
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

<!-- Danh sách chính sách -->
<div class="panel panel-default">
    <div class="panel-body">
        <?php if (empty($chinhsach)): ?>
            <div class="text-center" style="padding: 60px 0;">
                <i class="fa fa-file-text-o fa-4x text-muted"></i>
                <h4 class="text-muted" style="margin-top: 20px;">Chưa có chính sách</h4>
                <p class="text-muted">Hãy thêm chính sách hủy/đổi cho tour này</p>
                <a href="<?= BASE_URL ?>?act=tour-chinhsach-them&id_goi=<?= $idGoi ?>" class="btn btn-primary" style="margin-top: 10px;">
                    <i class="fa fa-plus"></i> Thêm chính sách đầu tiên
                </a>
            </div>
        <?php else: ?>
            <?php
            // Nhóm chính sách theo loại
            $grouped = [];
            foreach ($chinhsach as $cs) {
                $grouped[$cs['loai_chinhsach']][] = $cs;
            }
            ?>

            <?php foreach ($grouped as $loai => $policies): ?>
                <div class="panel panel-<?= $loai == 'huy' ? 'danger' : ($loai == 'doi' ? 'warning' : 'info') ?>">
                    <div class="panel-heading">
                        <h4 style="margin: 0;">
                            <?php
                            $icons = [
                                'huy' => 'fa-times-circle',
                                'doi' => 'fa-exchange',
                                'hoantien' => 'fa-money',
                                'khac' => 'fa-info-circle'
                            ];
                            $titles = [
                                'huy' => 'Chính sách hủy tour',
                                'doi' => 'Chính sách đổi lịch',
                                'hoantien' => 'Chính sách hoàn tiền',
                                'khac' => 'Chính sách khác'
                            ];
                            ?>
                            <i class="fa <?= $icons[$loai] ?? 'fa-file-text' ?>"></i>
                            <?= $titles[$loai] ?? 'Chính sách' ?>
                        </h4>
                    </div>
                    <div class="panel-body">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th width="120">Số ngày trước</th>
                                    <th width="120">% Hoàn tiền</th>
                                    <th>Nội dung</th>
                                    <th width="100" class="text-center">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($policies as $cs): ?>
                                    <tr>
                                        <td>
                                            <span class="label label-primary">
                                                <?= $cs['so_ngay_truoc'] ?> ngày
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($cs['phantram_hoantien'] > 0): ?>
                                                <span class="label label-success">
                                                    <?= number_format($cs['phantram_hoantien'], 0) ?>%
                                                </span>
                                            <?php else: ?>
                                                <span class="label label-danger">0%</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= nl2br(htmlspecialchars($cs['noidung'])) ?></td>
                                        <td class="text-center">
                                            <a href="<?= BASE_URL ?>?act=tour-chinhsach-xoa&id=<?= $cs['id'] ?>&id_goi=<?= $idGoi ?>" 
                                               class="btn btn-danger btn-xs"
                                               onclick="return confirm('Bạn có chắc muốn xóa chính sách này?')">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endforeach; ?>

            <!-- Tổng kết -->
            <div class="alert alert-success">
                <strong><i class="fa fa-check"></i> Tổng cộng:</strong> <?= count($chinhsach) ?> chính sách
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
include './views/admin/layout.php';
?>