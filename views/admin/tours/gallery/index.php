<?php
/**
 * File: views/admin/tour/gallery/index.php
 * Quản lý Gallery - Dùng với layout 1 file
 */

ob_start();
?>

<!-- Breadcrumb -->
<ol class="breadcrumb">
    <li><a href="<?= BASE_URL ?>?act=admin">Dashboard</a></li>
    <li><a href="<?= BASE_URL ?>?act=admin-tours">Tour</a></li>
    <li class="active">Gallery Tour #<?= $idGoi ?></li>
</ol>

<!-- Header -->
<div class="row" style="margin-bottom: 20px;">
    <div class="col-md-8">
        <h2 style="margin: 0;">📸 Quản lý Gallery</h2>
        <p class="text-muted">Tour ID: <?= $idGoi ?></p>
    </div>
    <div class="col-md-4 text-right">
        <a href="<?= BASE_URL ?>?act=tour-gallery-them&id_goi=<?= $idGoi ?>" class="btn btn-primary">
            <i class="fa fa-upload"></i> Upload ảnh
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

<?php if (isset($_SESSION['errors'])): ?>
    <div class="errorWrap">
        <strong>Có lỗi:</strong>
        <ul style="margin: 10px 0 0 20px;">
            <?php foreach ($_SESSION['errors'] as $error): ?>
                <li><?= $error ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php unset($_SESSION['errors']); ?>
<?php endif; ?>

<!-- Gallery Grid -->
<div class="panel panel-default">
    <div class="panel-body">
        <?php if (empty($hinhanh)): ?>
            <div class="text-center" style="padding: 60px 0;">
                <i class="fa fa-picture-o fa-4x text-muted"></i>
                <h4 class="text-muted" style="margin-top: 20px;">Chưa có hình ảnh</h4>
                <p class="text-muted">Hãy upload ảnh cho tour này</p>
                <a href="<?= BASE_URL ?>?act=tour-gallery-them&id_goi=<?= $idGoi ?>" class="btn btn-primary" style="margin-top: 10px;">
                    <i class="fa fa-upload"></i> Upload ảnh đầu tiên
                </a>
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($hinhanh as $anh): ?>
                    <div class="col-md-3 col-sm-4 col-xs-6" style="margin-bottom: 20px;">
                        <div class="thumbnail" style="position: relative;">
                            <?php if ($anh['anh_daodien'] == 1): ?>
                                <div class="ribbon-featured">
                                    <i class="fa fa-star"></i> Đại diện
                                </div>
                            <?php endif; ?>
                            
                            <img src="<?= BASE_URL . $anh['duongdan_anh'] ?>" 
                                 alt="<?= htmlspecialchars($anh['mota_anh']) ?>"
                                 style="width: 100%; height: 200px; object-fit: cover;">
                            
                            <div class="caption">
                                <?php if (!empty($anh['mota_anh'])): ?>
                                    <p style="margin: 5px 0; font-size: 12px; color: #666;">
                                        <?= htmlspecialchars($anh['mota_anh']) ?>
                                    </p>
                                <?php endif; ?>
                                
                                <div class="btn-group btn-group-justified" style="margin-top: 10px;">
                                    <?php if ($anh['anh_daodien'] != 1): ?>
                                        <a href="<?= BASE_URL ?>?act=tour-gallery-dai-dien&id=<?= $anh['id'] ?>&id_goi=<?= $idGoi ?>" 
                                           class="btn btn-sm btn-success"
                                           title="Đặt làm ảnh đại diện">
                                            <i class="fa fa-star"></i>
                                        </a>
                                    <?php endif; ?>
                                    <a href="<?= BASE_URL ?>?act=tour-gallery-xoa&id=<?= $anh['id'] ?>&id_goi=<?= $idGoi ?>" 
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('Bạn có chắc muốn xóa ảnh này?')"
                                       title="Xóa ảnh">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Tổng kết -->
            <div class="alert alert-success" style="margin-top: 20px;">
                <strong><i class="fa fa-check"></i> Tổng cộng:</strong> <?= count($hinhanh) ?> hình ảnh
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.ribbon-featured {
    position: absolute;
    top: 10px;
    right: -5px;
    background: #f0ad4e;
    color: white;
    padding: 5px 10px;
    font-size: 12px;
    font-weight: bold;
    z-index: 10;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}

.ribbon-featured::after {
    content: '';
    position: absolute;
    right: 0;
    bottom: -5px;
    border-left: 5px solid transparent;
    border-right: 5px solid #d58512;
    border-bottom: 5px solid transparent;
}

.thumbnail {
    transition: all 0.3s ease;
}

.thumbnail:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.2);
}
</style>

<?php
$content = ob_get_clean();
include './views/admin/layout.php';
?>