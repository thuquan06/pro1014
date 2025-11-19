<?php
/**
 * File: views/admin/tours/versions/create.php
 * Form thêm phiên bản tour
 */

ob_start();
?>

<!-- Breadcrumb -->
<ol class="breadcrumb">
    <li><a href="<?= BASE_URL ?>?act=admin">Dashboard</a></li>
    <li><a href="<?= BASE_URL ?>?act=admin-tours">Tour</a></li>
    <li><a href="<?= BASE_URL ?>?act=tour-versions&id_goi=<?= $idGoi ?>">Phiên bản</a></li>
    <li class="active">Thêm mới</li>
</ol>

<!-- Header -->
<div class="row" style="margin-bottom: 20px;">
    <div class="col-md-8">
        <h2 style="margin: 0;">➕ Thêm Phiên bản mới</h2>
        <p class="text-muted">Tour ID: <?= $idGoi ?></p>
    </div>
    <div class="col-md-4 text-right">
        <a href="<?= BASE_URL ?>?act=tour-versions&id_goi=<?= $idGoi ?>" class="btn btn-default">
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

<!-- Form thêm -->
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">Thông tin phiên bản</h3>
    </div>
    <div class="panel-body">
        <form method="POST" action="">
            <input type="hidden" name="id_goi" value="<?= $idGoi ?>">

            <div class="row">
                <!-- Tên phiên bản -->
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="ten_phienban">
                            Tên phiên bản <span class="text-danger">*</span>
                        </label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="ten_phienban" 
                            name="ten_phienban" 
                            placeholder="VD: Mùa hè 2025, Tết Nguyên Đán 2025..."
                            required
                        >
                    </div>
                </div>

                <!-- Loại phiên bản -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="loai_phienban">
                            Loại phiên bản <span class="text-danger">*</span>
                        </label>
                        <select class="form-control" id="loai_phienban" name="loai_phienban" required>
                            <option value="">-- Chọn loại --</option>
                            <option value="mua">🌞 Theo mùa</option>
                            <option value="holiday">🎄 Holiday/Lễ</option>
                            <option value="special">⭐ Đặc biệt</option>
                            <option value="default">📦 Mặc định</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Mô tả -->
            <div class="form-group">
                <label for="mo_ta">Mô tả phiên bản</label>
                <textarea 
                    class="form-control" 
                    id="mo_ta" 
                    name="mo_ta" 
                    rows="3"
                    placeholder="Mô tả ngắn gọn về phiên bản này..."
                ></textarea>
            </div>

            <div class="row">
                <!-- Ngày bắt đầu -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="ngay_batdau">
                            <i class="fa fa-calendar"></i> Ngày bắt đầu <span class="text-danger">*</span>
                        </label>
                        <input 
                            type="date" 
                            class="form-control" 
                            id="ngay_batdau" 
                            name="ngay_batdau"
                            required
                        >
                    </div>
                </div>

                <!-- Ngày kết thúc -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="ngay_ketthuc">
                            <i class="fa fa-calendar"></i> Ngày kết thúc <span class="text-danger">*</span>
                        </label>
                        <input 
                            type="date" 
                            class="form-control" 
                            id="ngay_ketthuc" 
                            name="ngay_ketthuc"
                            required
                        >
                    </div>
                </div>
            </div>

            <!-- Giá -->
            <div class="panel panel-info" style="margin-top: 20px;">
                <div class="panel-heading">
                    <h4 class="panel-title">💰 Giá cho phiên bản này</h4>
                    <small>Để trống nếu dùng giá mặc định</small>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="gia_nguoilon">
                                    <i class="fa fa-user"></i> Giá người lớn (VNĐ)
                                </label>
                                <input 
                                    type="number" 
                                    class="form-control" 
                                    id="gia_nguoilon" 
                                    name="gia_nguoilon"
                                    min="0"
                                    step="1000"
                                    placeholder="VD: 5000000"
                                >
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="gia_treem">
                                    <i class="fa fa-child"></i> Giá trẻ em (VNĐ)
                                </label>
                                <input 
                                    type="number" 
                                    class="form-control" 
                                    id="gia_treem" 
                                    name="gia_treem"
                                    min="0"
                                    step="1000"
                                    placeholder="VD: 3000000"
                                >
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="gia_embe">
                                    <i class="fa fa-child"></i> Giá em bé (VNĐ)
                                </label>
                                <input 
                                    type="number" 
                                    class="form-control" 
                                    id="gia_embe" 
                                    name="gia_embe"
                                    min="0"
                                    step="1000"
                                    placeholder="VD: 1000000"
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cài đặt -->
            <div class="panel panel-default" style="margin-top: 20px;">
                <div class="panel-heading">
                    <h4 class="panel-title">⚙️ Cài đặt</h4>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="is_active" value="1" checked>
                                    <strong>Kích hoạt phiên bản</strong>
                                </label>
                                <small class="text-muted d-block">Phiên bản có thể được sử dụng</small>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="is_default" value="1">
                                    <strong>Đặt làm mặc định</strong>
                                </label>
                                <small class="text-muted d-block">Dùng khi không có version phù hợp</small>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group" style="margin-bottom: 0;">
                                <label for="priority">
                                    <i class="fa fa-sort-numeric-asc"></i> Độ ưu tiên
                                </label>
                                <input 
                                    type="number" 
                                    class="form-control" 
                                    id="priority" 
                                    name="priority"
                                    value="0"
                                    min="0"
                                >
                                <small class="text-muted">Số càng lớn càng ưu tiên</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="form-group" style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd;">
                <button type="submit" class="btn btn-primary btn-lg" style="min-width: 150px;">
                    <i class="fa fa-save"></i> Lưu phiên bản
                </button>
                <a href="<?= BASE_URL ?>?act=tour-versions&id_goi=<?= $idGoi ?>" class="btn btn-default btn-lg">
                    <i class="fa fa-times"></i> Hủy bỏ
                </a>
            </div>
        </form>
    </div>
</div>

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

.panel-info {
    border-color: #5bc0de;
}

.panel-info > .panel-heading {
    background-color: #d9edf7;
    border-color: #5bc0de;
    color: #31708f;
}
</style>

<?php
$content = ob_get_clean();
include './views/admin/layout.php';
?>