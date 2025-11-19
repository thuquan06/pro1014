<?php
/**
 * File: views/admin/tours/lichtrinh/edit.php
 * Form sửa lịch trình tour
 */
ob_start();
?>

<!-- Breadcrumb -->
<ol class="breadcrumb">
    <li><a href="<?= BASE_URL ?>?act=admin">Dashboard</a></li>
    <li><a href="<?= BASE_URL ?>?act=admin-tours">Tour</a></li>
    <li><a href="<?= BASE_URL ?>?act=tour-lichtrinh&id_goi=<?= $idGoi ?>">Lịch trình</a></li>
    <li class="active">Sửa lịch trình</li>
</ol>

<!-- Header -->
<div class="row" style="margin-bottom: 20px;">
    <div class="col-md-8">
        <h2 style="margin: 0;">✏️ Sửa Lịch trình</h2>
        <p class="text-muted">Tour ID: <?= $idGoi ?> | Ngày thứ: <?= $lichTrinh['ngay_thu'] ?? '' ?></p>
    </div>
    <div class="col-md-4 text-right">
        <a href="<?= BASE_URL ?>?act=tour-lichtrinh&id_goi=<?= $idGoi ?>" class="btn btn-default">
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
        <h3 class="panel-title">Thông tin lịch trình</h3>
    </div>
    <div class="panel-body">
        <!-- Form submit về chính URL hiện tại -->
        <form method="POST" action="">
            <!-- Hidden fields -->
            <input type="hidden" name="id" value="<?= $lichTrinh['id'] ?? '' ?>">
            <input type="hidden" name="id_goi" value="<?= $idGoi ?>">

            <!-- Tiêu đề -->
            <div class="form-group">
                <label for="tieude">
                    Tiêu đề <span class="text-danger">*</span>
                </label>
                <input 
                    type="text" 
                    class="form-control" 
                    id="tieude" 
                    name="tieude" 
                    value="<?= htmlspecialchars($lichTrinh['tieude'] ?? '') ?>"
                    placeholder="VD: Hà Nội - Sapa"
                    required
                >
            </div>

            <!-- Mô tả -->
            <div class="form-group">
                <label for="mota">
                    Mô tả chi tiết <span class="text-danger">*</span>
                </label>
                <textarea 
                    class="form-control" 
                    id="mota" 
                    name="mota" 
                    rows="5"
                    placeholder="Mô tả chi tiết lịch trình trong ngày..."
                    required
                ><?= htmlspecialchars($lichTrinh['mota'] ?? '') ?></textarea>
            </div>

                        <!-- Điểm đến -->
            <div class="form-group">
                <label for="diemden">
                    <i class="fa fa-map-marker"></i> Điểm đến
                </label>
                <textarea 
                    class="form-control" 
                    id="diemden" 
                    name="diemden" 
                    rows="2"
                    placeholder="VD: Hồ Gươm, Văn Miếu, Chùa Một Cột..."
                ><?= htmlspecialchars($lichTrinh['diemden'] ?? '') ?></textarea>
            </div>

                        <!-- Thời lượng -->
            <div class="form-group">
                <label for="thoiluong">
                    <i class="fa fa-clock-o"></i> Thời lượng
                </label>
                <input 
                    type="text" 
                    class="form-control" 
                    id="thoiluong" 
                    name="thoiluong" 
                    value="<?= htmlspecialchars($lichTrinh['thoiluong'] ?? '') ?>"
                    placeholder="VD: 2 giờ, Cả ngày..."
                >
            </div>

                        <!-- Ghi chú HDV -->
            <div class="form-group">
                <label for="ghichu_hdv">
                    <i class="fa fa-commenting"></i> Ghi chú cho HDV
                </label>
                <textarea 
                    class="form-control" 
                    id="ghichu_hdv" 
                    name="ghichu_hdv" 
                    rows="3"
                    style="border-left: 4px solid #f39c12;"
                ><?= htmlspecialchars($lichTrinh['ghichu_hdv'] ?? '') ?></textarea>
            </div>

            <!-- Hoạt động -->
            <div class="form-group">
                <label for="hoatdong">
                    Hoạt động trong ngày
                </label>
                <textarea 
                    class="form-control" 
                    id="hoatdong" 
                    name="hoatdong" 
                    rows="4"
                    placeholder="Các hoạt động sẽ thực hiện trong ngày (tùy chọn)..."
                ><?= htmlspecialchars($lichTrinh['hoatdong'] ?? '') ?></textarea>
                <small class="text-muted">
                    <i class="fa fa-info-circle"></i> 
                    VD: Tham quan Hồ Gươm, Ăn tối tại nhà hàng...
                </small>
            </div>

            <!-- Row 2 cột -->
            <div class="row">
                <!-- Bữa ăn -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="buaan">
                            <i class="fa fa-cutlery"></i> Bữa ăn
                        </label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="buaan" 
                            name="buaan" 
                            value="<?= htmlspecialchars($lichTrinh['buaan'] ?? '') ?>"
                            placeholder="VD: Sáng, Trưa, Tối"
                        >
                    </div>
                </div>

                <!-- Nơi nghỉ -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="noinghi">
                            <i class="fa fa-hotel"></i> Nơi nghỉ
                        </label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="noinghi" 
                            name="noinghi" 
                            value="<?= htmlspecialchars($lichTrinh['noinghi'] ?? '') ?>"
                            placeholder="VD: Khách sạn 4 sao"
                        >
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="form-group" style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd;">
                <button type="submit" class="btn btn-success" style="min-width: 150px; padding: 10px 20px;">
                    <i class="fa fa-check"></i> Cập nhật
                </button>
                <a href="<?= BASE_URL ?>?act=tour-lichtrinh&id_goi=<?= $idGoi ?>" class="btn btn-default" style="padding: 10px 20px;">
                    <i class="fa fa-arrow-left"></i> Quay lại
                </a>
            </div>
        </form>
    </div>
</div>

<!-- CSS bổ sung -->
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

.text-danger {
    color: #dc3545;
}

.btn-lg {
    padding: 10px 25px;
    font-size: 16px;
}
</style>

<?php
$content = ob_get_clean();
include './views/admin/layout.php';
?>