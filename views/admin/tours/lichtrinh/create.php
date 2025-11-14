<?php
/**
 * File: views/admin/tour/lichtrinh/create.php
 * Form thêm lịch trình - Dùng với layout 1 file
 */

// Kiểm tra là thêm hay sửa
$isEdit = isset($lichtrinh);
$title = $isEdit ? 'Sửa lịch trình' : 'Thêm lịch trình mới';
$action = $isEdit ? BASE_URL . "?act=tour-lichtrinh-sua&id={$lichtrinh['id']}&id_goi=$idGoi" : BASE_URL . "?act=tour-lichtrinh-them&id_goi=$idGoi";

// Bắt đầu buffer
ob_start();
?>

<!-- Breadcrumb -->
<ol class="breadcrumb">
    <li><a href="<?= BASE_URL ?>?act=admin">Dashboard</a></li>
    <li><a href="<?= BASE_URL ?>?act=admin-tours">Tour</a></li>
    <li><a href="<?= BASE_URL ?>?act=tour-lichtrinh&id_goi=<?= $idGoi ?>">Lịch trình</a></li>
    <li class="active"><?= $title ?></li>
</ol>

<!-- Header -->
<div class="row" style="margin-bottom: 20px;">
    <div class="col-md-8">
        <h2 style="margin: 0;">📝 <?= $title ?></h2>
        <p class="text-muted">Tour ID: <?= $idGoi ?></p>
    </div>
    <div class="col-md-4 text-right">
        <a href="<?= BASE_URL ?>?act=tour-lichtrinh&id_goi=<?= $idGoi ?>" class="btn btn-default">
            <i class="fa fa-arrow-left"></i> Quay lại
        </a>
    </div>
</div>

<!-- Thông báo lỗi -->
<?php if (isset($_SESSION['errors'])): ?>
    <div class="errorWrap">
        <strong>Có lỗi xảy ra:</strong>
        <ul style="margin: 10px 0 0 20px;">
            <?php foreach ($_SESSION['errors'] as $error): ?>
                <li><?= $error ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php unset($_SESSION['errors']); ?>
<?php endif; ?>

<!-- Form -->
<div class="panel panel-default">
    <div class="panel-body">
        <form action="<?= $action ?>" method="POST" class="form-horizontal">
            
            <!-- Ngày thứ -->
            <div class="form-group">
                <label class="col-sm-2 control-label">
                    Ngày thứ <span class="text-danger">*</span>
                </label>
                <div class="col-sm-10">
                    <input type="number" 
                           name="ngay_thu" 
                           class="form-control" 
                           min="1" 
                           max="30"
                           value="<?= $isEdit ? $lichtrinh['ngay_thu'] : '' ?>"
                           placeholder="VD: 1, 2, 3..."
                           required>
                    <small class="help-block">Nhập số thứ tự ngày trong hành trình</small>
                </div>
            </div>

            <!-- Tiêu đề -->
            <div class="form-group">
                <label class="col-sm-2 control-label">
                    Tiêu đề <span class="text-danger">*</span>
                </label>
                <div class="col-sm-10">
                    <input type="text" 
                           name="tieude" 
                           class="form-control" 
                           value="<?= $isEdit ? htmlspecialchars($lichtrinh['tieude']) : '' ?>"
                           placeholder="VD: Ngay 1: Khoi hanh - TP.HCM → Da Lat"
                           required>
                </div>
            </div>

            <!-- Mô tả -->
            <div class="form-group">
                <label class="col-sm-2 control-label">
                    Mô tả chi tiết <span class="text-danger">*</span>
                </label>
                <div class="col-sm-10">
                    <textarea name="mota" 
                              class="form-control" 
                              rows="5"
                              placeholder="Mo ta tong quan ve lich trinh trong ngay..."
                              required><?= $isEdit ? htmlspecialchars($lichtrinh['mota']) : '' ?></textarea>
                </div>
            </div>

            <!-- Hoạt động -->
            <div class="form-group">
                <label class="col-sm-2 control-label">Hoạt động trong ngày</label>
                <div class="col-sm-10">
                    <textarea name="hoatdong" 
                              class="form-control" 
                              rows="8"
                              placeholder="06:00 - Tap trung san bay&#10;09:00 - Bay den Da Lat&#10;12:00 - An trua..."><?= $isEdit ? htmlspecialchars($lichtrinh['hoatdong']) : '' ?></textarea>
                    <small class="help-block">Liet ke cac hoat dong theo gio (moi hoat dong 1 dong)</small>
                </div>
            </div>

            <!-- Bữa ăn -->
            <div class="form-group">
                <label class="col-sm-2 control-label">Bữa ăn</label>
                <div class="col-sm-10">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" 
                                           id="buaan_sang" 
                                           value="Sang"
                                           <?= ($isEdit && strpos($lichtrinh['buaan'], 'Sang') !== false) ? 'checked' : '' ?>>
                                    🌅 Sáng
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" 
                                           id="buaan_trua" 
                                           value="Trua"
                                           <?= ($isEdit && strpos($lichtrinh['buaan'], 'Trua') !== false) ? 'checked' : '' ?>>
                                    ☀️ Trưa
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" 
                                           id="buaan_toi" 
                                           value="Toi"
                                           <?= ($isEdit && strpos($lichtrinh['buaan'], 'Toi') !== false) ? 'checked' : '' ?>>
                                    🌙 Tối
                                </label>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="buaan" id="buaan_input" value="<?= $isEdit ? $lichtrinh['buaan'] : '' ?>">
                </div>
            </div>

            <!-- Nơi nghỉ -->
            <div class="form-group">
                <label class="col-sm-2 control-label">Nơi nghỉ đêm</label>
                <div class="col-sm-10">
                    <input type="text" 
                           name="noinghi" 
                           class="form-control" 
                           value="<?= $isEdit ? htmlspecialchars($lichtrinh['noinghi']) : '' ?>"
                           placeholder="VD: Khach san 4* trung tam Da Lat">
                    <small class="help-block">De trong neu ngay cuoi khong nghi dem</small>
                </div>
            </div>

            <!-- Buttons -->
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save"></i> <?= $isEdit ? 'Cập nhật' : 'Thêm mới' ?>
                    </button>
                    <a href="<?= BASE_URL ?>?act=tour-lichtrinh&id_goi=<?= $idGoi ?>" class="btn btn-default">
                        <i class="fa fa-times"></i> Hủy
                    </a>
                </div>
            </div>

        </form>
    </div>
</div>

<script>
// Xử lý checkbox bữa ăn
document.addEventListener('DOMContentLoaded', function() {
    function updateBuaAn() {
        var checked = [];
        document.querySelectorAll('[id^="buaan_"]:checked').forEach(function(cb) {
            checked.push(cb.value);
        });
        document.getElementById('buaan_input').value = checked.join(', ');
    }
    
    document.querySelectorAll('[id^="buaan_"]').forEach(function(checkbox) {
        checkbox.addEventListener('change', updateBuaAn);
    });
    
    // Load giá trị ban đầu
    updateBuaAn();
});
</script>

<?php
$content = ob_get_clean();
include './views/admin/layout.php';
?>