<?php
/**
 * File: views/admin/tours/chinhsach/edit.php
 * Form sửa chính sách tour
 */

ob_start();
?>

<!-- Breadcrumb -->
<ol class="breadcrumb">
    <li><a href="<?= BASE_URL ?>?act=admin">Dashboard</a></li>
    <li><a href="<?= BASE_URL ?>?act=admin-tours">Tour</a></li>
    <li><a href="<?= BASE_URL ?>?act=tour-chinhsach&id_goi=<?= $idGoi ?>">Chính sách</a></li>
    <li class="active">Sửa chính sách</li>
</ol>

<!-- Header -->
<div class="row" style="margin-bottom: 20px;">
    <div class="col-md-8">
        <h2 style="margin: 0;">✏️ Sửa Chính sách</h2>
        <p class="text-muted">Tour ID: <?= $idGoi ?> | Policy ID: <?= $chinhsach['id'] ?></p>
    </div>
    <div class="col-md-4 text-right">
        <a href="<?= BASE_URL ?>?act=tour-chinhsach&id_goi=<?= $idGoi ?>" class="btn btn-default">
            <i class="fa fa-arrow-left"></i> Quay lại
        </a>
    </div>
</div>

<!-- Thông báo lỗi -->
<?php if (isset($_SESSION['errors']) && !empty($_SESSION['errors'])): ?>
    <div class="errorWrap">
        <i class="fa fa-exclamation-circle"></i>
        <ul style="margin: 5px 0 0 20px;">
            <?php foreach ($_SESSION['errors'] as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php unset($_SESSION['errors']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="errorWrap">
        <i class="fa fa-exclamation-circle"></i> <?= $_SESSION['error'] ?>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<!-- Form sửa -->
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">Thông tin chính sách</h3>
    </div>
    <div class="panel-body">
        <form method="POST" action="">
            <input type="hidden" name="id" value="<?= $chinhsach['id'] ?>">
            <input type="hidden" name="id_goi" value="<?= $idGoi ?>">

            <!-- Loại chính sách -->
            <div class="form-group">
                <label for="loai_chinhsach">
                    Loại chính sách <span class="text-danger">*</span>
                </label>
                <select class="form-control" id="loai_chinhsach" name="loai_chinhsach" required onchange="toggleFields()">
                    <option value="">-- Chọn loại chính sách --</option>
                    <option value="huy_doi" <?= $chinhsach['loai_chinhsach'] == 'huy_doi' ? 'selected' : '' ?>>🔄 Hủy/Đổi Tour</option>
                    <option value="suc_khoe" <?= $chinhsach['loai_chinhsach'] == 'suc_khoe' ? 'selected' : '' ?>>💊 Sức khỏe</option>
                    <option value="hanh_ly" <?= $chinhsach['loai_chinhsach'] == 'hanh_ly' ? 'selected' : '' ?>>🎒 Hành lý</option>
                    <option value="thanh_toan" <?= $chinhsach['loai_chinhsach'] == 'thanh_toan' ? 'selected' : '' ?>>💳 Thanh toán</option>
                    <option value="visa" <?= $chinhsach['loai_chinhsach'] == 'visa' ? 'selected' : '' ?>>🛂 Visa</option>
                    <option value="bao_hiem" <?= $chinhsach['loai_chinhsach'] == 'bao_hiem' ? 'selected' : '' ?>>🛡️ Bảo hiểm</option>
                    <option value="khac" <?= $chinhsach['loai_chinhsach'] == 'khac' ? 'selected' : '' ?>>📝 Khác</option>
                </select>
            </div>

            <!-- Nội dung -->
            <div class="form-group">
                <label for="noidung">
                    Nội dung <span class="text-danger">*</span>
                </label>
                <textarea 
                    class="form-control" 
                    id="noidung" 
                    name="noidung" 
                    rows="6"
                    placeholder="Nhập nội dung chính sách chi tiết..."
                    required
                ><?= htmlspecialchars($chinhsach['noidung']) ?></textarea>
                <small class="text-muted">
                    <i class="fa fa-info-circle"></i> 
                    Mô tả chi tiết chính sách
                </small>
            </div>

            <!-- Các trường đặc biệt cho Hủy/Đổi -->
            <div id="huy_doi_fields" style="<?= $chinhsach['loai_chinhsach'] == 'huy_doi' ? 'display: block;' : 'display: none;' ?>">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="so_ngay_truoc">
                                <i class="fa fa-calendar"></i> Hủy trước (ngày)
                            </label>
                            <input 
                                type="number" 
                                class="form-control" 
                                id="so_ngay_truoc" 
                                name="so_ngay_truoc" 
                                min="0"
                                value="<?= $chinhsach['so_ngay_truoc'] ?? '' ?>"
                                placeholder="VD: 30"
                            >
                            <small class="text-muted">Số ngày trước khi khởi hành</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="phantram_hoantien">
                                <i class="fa fa-percent"></i> Phần trăm hoàn tiền
                            </label>
                            <input 
                                type="number" 
                                class="form-control" 
                                id="phantram_hoantien" 
                                name="phantram_hoantien" 
                                min="0" 
                                max="100" 
                                step="0.01"
                                value="<?= $chinhsach['phantram_hoantien'] ?? '' ?>"
                                placeholder="VD: 100"
                            >
                            <small class="text-muted">% tiền được hoàn lại</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thứ tự hiển thị -->
            <div class="form-group">
                <label for="thutu_hienthi">
                    <i class="fa fa-sort"></i> Thứ tự hiển thị
                </label>
                <input 
                    type="number" 
                    class="form-control" 
                    id="thutu_hienthi" 
                    name="thutu_hienthi" 
                    value="<?= $chinhsach['thutu_hienthi'] ?? 0 ?>"
                    min="0"
                >
                <small class="text-muted">Số thứ tự để sắp xếp (0 = mặc định)</small>
            </div>

            <!-- Buttons -->
            <div class="form-group" style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd;">
                <button type="submit" class="btn btn-primary btn-lg" style="min-width: 150px;">
                    <i class="fa fa-save"></i> Lưu thay đổi
                </button>
                <a href="<?= BASE_URL ?>?act=tour-chinhsach&id_goi=<?= $idGoi ?>" class="btn btn-default btn-lg">
                    <i class="fa fa-times"></i> Hủy bỏ
                </a>
            </div>
        </form>
    </div>
</div>

<!-- JavaScript -->
<script>
function toggleFields() {
    var loai = document.getElementById('loai_chinhsach').value;
    var huyDoiFields = document.getElementById('huy_doi_fields');
    
    if (loai === 'huy_doi') {
        huyDoiFields.style.display = 'block';
    } else {
        huyDoiFields.style.display = 'none';
        document.getElementById('so_ngay_truoc').value = '';
        document.getElementById('phantram_hoantien').value = '';
    }
}
</script>

<style>
.form-group label {
    font-weight: 600;
    color: #333;
    margin-bottom: 8px;
}

.form-control {
    border-radius: 5px;
    border: 1px solid #ddd;
    padding: 10px 15px;
}

.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

textarea.form-control {
    resize: vertical;
}
</style>

<?php
$content = ob_get_clean();
include './views/admin/layout.php';
?>