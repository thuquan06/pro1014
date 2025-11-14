<?php
/**
 * File: views/admin/tour/lichtrinh/index.php
 * Danh sách lịch trình - Dùng với layout 1 file
 */

// Bắt đầu buffer để gán vào $content
ob_start();
?>

<!-- Breadcrumb -->
<ol class="breadcrumb">
    <li><a href="<?= BASE_URL ?>?act=admin">Dashboard</a></li>
    <li><a href="<?= BASE_URL ?>?act=admin-tours">Tour</a></li>
    <li class="active">Lịch trình Tour #<?= $idGoi ?></li>
</ol>

<!-- Header -->
<div class="row" style="margin-bottom: 20px;">
    <div class="col-md-8">
        <h2 style="margin: 0;">📅 Quản lý Lịch trình</h2>
        <p class="text-muted">Tour ID: <?= $idGoi ?></p>
    </div>
    <div class="col-md-4 text-right">
        <a href="<?= BASE_URL ?>?act=tour-lichtrinh-them&id_goi=<?= $idGoi ?>" class="btn btn-primary">
            <i class="fa fa-plus"></i> Thêm ngày mới
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


<!-- Danh sách lịch trình -->
<div class="panel panel-default">
    <div class="panel-body">
        <?php if (empty($lichtrinh)): ?>
            <div class="text-center" style="padding: 60px 0;">
                <i class="fa fa-calendar-times-o fa-4x text-muted"></i>
                <h4 class="text-muted" style="margin-top: 20px;">Chưa có lịch trình</h4>
                <p class="text-muted">Hãy thêm lịch trình chi tiết cho tour này</p>
                <a href="<?= BASE_URL ?>?act=tour-lichtrinh-them&id_goi=<?= $idGoi ?>" class="btn btn-primary" style="margin-top: 10px;">
                    <i class="fa fa-plus"></i> Thêm ngày đầu tiên
                </a>
            </div>
        <?php else: ?>
            <!-- Timeline -->
            <div class="timeline-container">
                <?php foreach ($lichtrinh as $index => $ngay): ?>
                    <div class="timeline-item">
                        <div class="timeline-marker">
                            <span class="badge-day"><?= $ngay['ngay_thu'] ?></span>
                        </div>
                        <div class="timeline-content">
                            <div class="panel panel-info">
                                <div class="panel-heading" style="background: #f5f5f5; border-bottom: 2px solid #5cb85c;">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <h4 style="margin: 5px 0; color: #333;">
                                                <?= htmlspecialchars($ngay['tieude']) ?>
                                            </h4>
                                        </div>
                                        <div class="col-md-4 text-right">
                                            <div class="btn-group">
                                                <a href="<?= BASE_URL ?>?act=tour-lichtrinh-sua&id=<?= $ngay['id'] ?>&id_goi=<?= $idGoi ?>" 
                                                   class="btn btn-warning btn-sm">
                                                    <i class="fa fa-edit"></i> Sửa
                                                </a>
                                                <a href="<?= BASE_URL ?>?act=tour-lichtrinh-xoa&id=<?= $ngay['id'] ?>&id_goi=<?= $idGoi ?>" 
                                                   class="btn btn-danger btn-sm"
                                                   onclick="return confirm('Bạn có chắc muốn xóa lịch trình ngày <?= $ngay['ngay_thu'] ?>?')">
                                                    <i class="fa fa-trash"></i> Xóa
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <p style="text-align: justify; line-height: 1.6;">
                                        <?= nl2br(htmlspecialchars($ngay['mota'])) ?>
                                    </p>
                                    
                                    <?php if (!empty($ngay['hoatdong'])): ?>
                                        <div class="alert alert-info" style="margin-top: 15px;">
                                            <strong><i class="fa fa-list"></i> Hoạt động trong ngày:</strong>
                                            <pre style="background: white; border: none; margin-top: 10px; white-space: pre-wrap;"><?= htmlspecialchars($ngay['hoatdong']) ?></pre>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="row" style="margin-top: 15px;">
                                        <?php if (!empty($ngay['buaan'])): ?>
                                            <div class="col-md-6">
                                                <span class="label label-success" style="font-size: 13px; padding: 5px 10px;">
                                                    <i class="fa fa-cutlery"></i> <?= htmlspecialchars($ngay['buaan']) ?>
                                                </span>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if (!empty($ngay['noinghi'])): ?>
                                            <div class="col-md-6">
                                                <span class="label label-info" style="font-size: 13px; padding: 5px 10px;">
                                                    <i class="fa fa-hotel"></i> <?= htmlspecialchars($ngay['noinghi']) ?>
                                                </span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="panel-footer text-muted">
                                    <small>
                                        <i class="fa fa-clock-o"></i> Tạo lúc: <?= date('d/m/Y H:i', strtotime($ngay['thoigian_tao'])) ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Tổng kết -->
            <div class="alert alert-success" style="margin-top: 30px;">
                <strong><i class="fa fa-check"></i> Tổng cộng:</strong> <?= count($lichtrinh) ?> ngày lịch trình
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.timeline-container {
    position: relative;
    padding-left: 60px;
    padding-top: 20px;
}

.timeline-container::before {
    content: '';
    position: absolute;
    left: 25px;
    top: 0;
    bottom: 0;
    width: 4px;
    background: linear-gradient(to bottom, #5cb85c, #5bc0de);
}

.timeline-item {
    position: relative;
    margin-bottom: 30px;
}

.timeline-marker {
    position: absolute;
    left: -50px;
    top: 15px;
}

.badge-day {
    display: inline-block;
    width: 50px;
    height: 50px;
    line-height: 50px;
    text-align: center;
    background: #5cb85c;
    color: white;
    border-radius: 50%;
    font-size: 18px;
    font-weight: bold;
    border: 4px solid white;
    box-shadow: 0 0 0 3px #5cb85c;
}

.timeline-content {
    margin-left: 20px;
}

.timeline-item:hover .panel {
    transform: translateX(5px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.15);
}

.panel {
    transition: all 0.3s ease;
}
</style>

<?php
$content = ob_get_clean();
include './views/admin/layout.php';
?>