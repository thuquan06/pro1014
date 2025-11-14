<?php
/**
 * File: views/admin/tour/chinhsach/create.php
 * Form thêm chính sách
 */

ob_start();
?>

<!-- Breadcrumb -->
<ol class="breadcrumb">
    <li><a href="<?= BASE_URL ?>?act=admin">Dashboard</a></li>
    <li><a href="<?= BASE_URL ?>?act=admin-tours">Tour</a></li>
    <li><a href="<?= BASE_URL ?>?act=tour-chinhsach&id_goi=<?= $idGoi ?>">Chính sách</a></li>
    <li class="active">Thêm chính sách</li>
</ol>

<!-- Header -->
<div class="row" style="margin-bottom: 20px;">
    <div class="col-md-8">
        <h2 style="margin: 0;">📝 Thêm chính sách mới</h2>
        <p class="text-muted">Tour ID: <?= $idGoi ?></p>
    </div>
    <div class="col-md-4 text-right">
        <a href="<?= BASE_URL ?>?act=tour-chinhsach&id_goi=<?= $idGoi ?>" class="btn btn-default">
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
        <form action="<?= BASE_URL ?>?act=tour-chinhsach-them&id_goi=<?= $idGoi ?>" method="POST" class="form-horizontal">
            
            <!-- Loại chính sách -->
            <div class="form-group">
                <label class="col-sm-2 control-label">
                    Loại chính sách <span class="text-danger">*</span>
                </label>
                <div class="col-sm-10">
                    <select name="loai_chinhsach" class="form-control" required>
                        <option value="">-- Chọn loại --</option>
                        <option value="huy">❌ Chính sách hủy tour</option>
                        <option value="doi">🔄 Chính sách đổi lịch</option>
                        <option value="hoantien">💰 Chính sách hoàn tiền</option>
                        <option value="khac">📌 Chính sách khác</option>
                    </select>
                </div>
            </div>

            <!-- Số ngày trước -->
            <div class="form-group">
                <label class="col-sm-2 control-label">
                    Số ngày trước khởi hành <span class="text-danger">*</span>
                </label>
                <div class="col-sm-10">
                    <input type="number" 
                           name="so_ngay_truoc" 
                           class="form-control" 
                           min="0" 
                           max="365"
                           placeholder="VD: 30, 15, 7, 0..."
                           required>
                    <small class="help-block">
                        Nhập số ngày trước khi khởi hành. VD: 30 = trước 30 ngày, 0 = dưới 7 ngày
                    </small>
                </div>
            </div>

            <!-- % Hoàn tiền -->
            <div class="form-group">
                <label class="col-sm-2 control-label">
                    % Hoàn tiền <span class="text-danger">*</span>
                </label>
                <div class="col-sm-10">
                    <div class="input-group">
                        <input type="number" 
                               name="phantram_hoantien" 
                               class="form-control" 
                               min="0" 
                               max="100"
                               step="0.01"
                               value="0"
                               placeholder="0-100"
                               required>
                        <span class="input-group-addon">%</span>
                    </div>
                    <small class="help-block">
                        Nhập từ 0 đến 100. VD: 100 = hoàn 100%, 50 = hoàn 50%, 0 = không hoàn
                    </small>
                </div>
            </div>

            <!-- Nội dung -->
            <div class="form-group">
                <label class="col-sm-2 control-label">
                    Nội dung chính sách <span class="text-danger">*</span>
                </label>
                <div class="col-sm-10">
                    <textarea name="noidung" 
                              class="form-control" 
                              rows="4"
                              placeholder="Mo ta chi tiet chinh sach..."
                              required></textarea>
                    <small class="help-block">
                        VD: Huy tour truoc 30 ngay khoi hanh: Hoan lai 100% chi phi
                    </small>
                </div>
            </div>

            <!-- Buttons -->
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save"></i> Thêm mới
                    </button>
                    <a href="<?= BASE_URL ?>?act=tour-chinhsach&id_goi=<?= $idGoi ?>" class="btn btn-default">
                        <i class="fa fa-times"></i> Hủy
                    </a>
                </div>
            </div>

        </form>
    </div>
</div>

<!-- Gợi ý -->
<div class="panel panel-info">
    <div class="panel-heading">
        <h4 style="margin: 0;"><i class="fa fa-lightbulb-o"></i> Gợi ý chính sách mẫu</h4>
    </div>
    <div class="panel-body">
        <h5>Chính sách hủy tour:</h5>
        <ul>
            <li>Hủy trước 30 ngày: Hoàn 100%</li>
            <li>Hủy 15-29 ngày: Hoàn 70%</li>
            <li>Hủy 7-14 ngày: Hoàn 50%</li>
            <li>Hủy dưới 7 ngày: Không hoàn</li>
        </ul>
        
        <h5>Chính sách đổi lịch:</h5>
        <ul>
            <li>Đổi lịch trước 15 ngày: Miễn phí</li>
            <li>Đổi lịch 7-14 ngày: Phí 10%</li>
            <li>Đổi lịch dưới 7 ngày: Phí 20%</li>
        </ul>
    </div>
</div>

<?php
$content = ob_get_clean();
include './views/admin/layout.php';
?>