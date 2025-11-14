<?php
/**
 * File: views/admin/tour/phanloai/index.php
 * Quản lý loại tour & tags
 */

ob_start();
?>

<!-- Breadcrumb -->
<ol class="breadcrumb">
    <li><a href="<?= BASE_URL ?>?act=admin">Dashboard</a></li>
    <li><a href="<?= BASE_URL ?>?act=admin-tours">Tour</a></li>
    <li class="active">Phân loại Tour #<?= $idGoi ?></li>
</ol>

<!-- Header -->
<div class="row" style="margin-bottom: 20px;">
    <div class="col-md-8">
        <h2 style="margin: 0;">🏷️ Quản lý Phân loại</h2>
        <p class="text-muted">Tour ID: <?= $idGoi ?></p>
    </div>
    <div class="col-md-4 text-right">
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

<div class="row">
    <!-- LOẠI TOUR -->
    <div class="col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h4 style="margin: 0;">
                    <i class="fa fa-folder-open"></i> Loại Tour
                </h4>
            </div>
            <div class="panel-body">
                <form action="<?= BASE_URL ?>?act=tour-phanloai-loai" method="POST">
                    <input type="hidden" name="id_goi" value="<?= $idGoi ?>">
                    
                    <?php foreach ($tatCaLoai as $loai): ?>
                        <div class="checkbox">
                            <label style="padding: 10px; display: block; border-radius: 5px; transition: all 0.3s;"
                                   onmouseover="this.style.background='#f5f5f5'"
                                   onmouseout="this.style.background='transparent'">
                                <input type="checkbox" 
                                       name="loai_ids[]" 
                                       value="<?= $loai['id'] ?>"
                                       <?= in_array($loai['id'], $loaiIds) ? 'checked' : '' ?>>
                                
                                <span style="color: <?= htmlspecialchars($loai['mau_sac']) ?>; font-weight: bold;">
                                    <i class="<?= htmlspecialchars($loai['icon']) ?>"></i>
                                    <?= htmlspecialchars($loai['ten_loai']) ?>
                                </span>
                                
                                <?php if (!empty($loai['mota'])): ?>
                                    <br>
                                    <small class="text-muted"><?= htmlspecialchars($loai['mota']) ?></small>
                                <?php endif; ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                    
                    <div style="margin-top: 20px;">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fa fa-save"></i> Cập nhật loại tour
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- TAGS -->
    <div class="col-md-6">
        <div class="panel panel-success">
            <div class="panel-heading">
                <h4 style="margin: 0;">
                    <i class="fa fa-tags"></i> Tags
                </h4>
            </div>
            <div class="panel-body">
                <form action="<?= BASE_URL ?>?act=tour-phanloai-tags" method="POST">
                    <input type="hidden" name="id_goi" value="<?= $idGoi ?>">
                    
                    <div style="max-height: 400px; overflow-y: auto;">
                        <?php foreach ($tatCaTags as $tag): ?>
                            <div class="checkbox">
                                <label style="padding: 5px; display: block;">
                                    <input type="checkbox" 
                                           name="tag_ids[]" 
                                           value="<?= $tag['id'] ?>"
                                           <?= in_array($tag['id'], $tagIds) ? 'checked' : '' ?>>
                                    
                                    <span class="label label-default" style="font-size: 13px;">
                                        #<?= htmlspecialchars($tag['ten_tag']) ?>
                                    </span>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div style="margin-top: 20px;">
                        <button type="submit" class="btn btn-success btn-block">
                            <i class="fa fa-save"></i> Cập nhật tags
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Preview -->
<div class="panel panel-info">
    <div class="panel-heading">
        <h4 style="margin: 0;">
            <i class="fa fa-eye"></i> Xem trước phân loại hiện tại
        </h4>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-6">
                <h5><strong>Loại tour đã chọn:</strong></h5>
                <?php if (empty($loaiDaGan)): ?>
                    <p class="text-muted">Chưa chọn loại tour nào</p>
                <?php else: ?>
                    <?php foreach ($loaiDaGan as $loai): ?>
                        <span class="label" style="background: <?= htmlspecialchars($loai['mau_sac']) ?>; font-size: 14px; margin: 5px; padding: 8px 12px; display: inline-block;">
                            <i class="<?= htmlspecialchars($loai['icon']) ?>"></i>
                            <?= htmlspecialchars($loai['ten_loai']) ?>
                        </span>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <div class="col-md-6">
                <h5><strong>Tags đã chọn:</strong></h5>
                <?php if (empty($tagsDaGan)): ?>
                    <p class="text-muted">Chưa chọn tags nào</p>
                <?php else: ?>
                    <?php foreach ($tagsDaGan as $tag): ?>
                        <span class="label label-default" style="font-size: 13px; margin: 3px; padding: 6px 10px; display: inline-block;">
                            #<?= htmlspecialchars($tag['ten_tag']) ?>
                        </span>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include './views/admin/layout.php';
?>