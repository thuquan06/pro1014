<?php
/**
 * File: views/admin/tour/lichtrinh/edit.php
 * Form sửa lịch trình
 */

ob_start();
?>

<style>
.edit-wrap {
    max-width: 900px;
    margin: 0 auto;
}

.edit-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 32px 24px;
    border-radius: 16px;
    margin-bottom: 32px;
    box-shadow: 0 10px 40px rgba(102, 126, 234, 0.25);
}

.edit-header h1 {
    margin: 0 0 8px 0;
    font-size: 28px;
}

.edit-header p {
    margin: 0;
    opacity: 0.95;
}

.edit-card {
    background: white;
    border-radius: 16px;
    padding: 32px;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
}

.form-group {
    margin-bottom: 24px;
}

.form-label {
    display: block;
    font-weight: 600;
    margin-bottom: 8px;
    color: #2d3748;
    font-size: 14px;
}

.required {
    color: #e53e3e;
}

.form-control {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    font-size: 14px;
    transition: all 0.3s ease;
    font-family: inherit;
}

.form-control:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

textarea.form-control {
    min-height: 120px;
    resize: vertical;
}

.btn {
    padding: 12px 24px;
    border-radius: 10px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 14px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
}

.btn-secondary {
    background: #e2e8f0;
    color: #4a5568;
}

.btn-secondary:hover {
    background: #cbd5e0;
}

.alert {
    padding: 15px 20px;
    border-radius: 10px;
    margin-bottom: 20px;
    display: flex;
    align-items: flex-start;
    gap: 12px;
}

.alert-error {
    background: #fef2f2;
    border: 2px solid #fca5a5;
    color: #991b1b;
}

.alert ul {
    margin: 0;
    padding-left: 20px;
}

.help-text {
    font-size: 13px;
    color: #718096;
    margin-top: 6px;
}

.day-number {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 10px;
    font-weight: 700;
    font-size: 18px;
    margin-right: 12px;
}

.form-header {
    display: flex;
    align-items: center;
    margin-bottom: 24px;
    padding-bottom: 16px;
    border-bottom: 2px solid #e2e8f0;
}
</style>

<div class="edit-wrap">
    
    <!-- Breadcrumb -->
    <ol class="breadcrumb">
        <li><a href="<?= BASE_URL ?>?act=admin">Dashboard</a></li>
        <li><a href="<?= BASE_URL ?>?act=admin-tours">Tour</a></li>
        <li><a href="<?= BASE_URL ?>?act=tour-lichtrinh&id_goi=<?= $idGoi ?>">Lịch trình</a></li>
        <li class="active">Sửa lịch trình</li>
    </ol>

    <!-- Header -->
    <div class="edit-header">
        <h1><i class="glyphicon glyphicon-pencil"></i> Sửa lịch trình</h1>
        <p>Tour ID: <?= $idGoi ?> - Ngày <?= $lichTrinh['ngay'] ?? '' ?></p>
    </div>

    <!-- Thông báo lỗi -->
    <?php if (isset($_SESSION['errors'])): ?>
        <div class="alert alert-error">
            <i class="glyphicon glyphicon-exclamation-sign"></i>
            <div>
                <strong>Có lỗi xảy ra:</strong>
                <ul>
                    <?php foreach ($_SESSION['errors'] as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <?php unset($_SESSION['errors']); ?>
    <?php endif; ?>

    <!-- Form -->
    <div class="edit-card">
        <div class="form-header">
            <span class="day-number"><?= $lichTrinh['ngay'] ?? '1' ?></span>
            <div>
                <h3 style="margin: 0 0 4px 0; font-size: 18px;">Ngày <?= $lichTrinh['ngay'] ?? '1' ?></h3>
                <p style="margin: 0; color: #718096; font-size: 13px;">Chỉnh sửa thông tin lịch trình</p>
            </div>
        </div>

        <form action="<?= BASE_URL ?>?act=tour-lichtrinh-sua" method="POST">
    <input type="hidden" name="id" value="<?= $lichTrinh['id'] ?? '' ?>">
    <input type="hidden" name="id_goi" value="<?= $idGoi ?>">
    
    <!-- Tiêu đề -->
    <div class="form-group">
        <label class="form-label">
            Tiêu đề <span class="required">*</span>
        </label>
        <input type="text" 
               name="tieude" 
               class="form-control" 
               value="<?= htmlspecialchars($lichTrinh['tieude'] ?? '') ?>"
               required>
    </div>

    <!-- Mô tả -->
    <div class="form-group">
        <label class="form-label">
            Mô tả chi tiết <span class="required">*</span>
        </label>
        <textarea name="mota" 
                  class="form-control" 
                  required><?= htmlspecialchars($lichTrinh['mota'] ?? '') ?></textarea>
    </div>

    <!-- Hoạt động -->
    <div class="form-group">
        <label class="form-label">Hoạt động</label>
        <textarea name="hoatdong" 
                  class="form-control"><?= htmlspecialchars($lichTrinh['hoatdong'] ?? '') ?></textarea>
    </div>

    <!-- Bữa ăn -->
    <div class="form-group">
        <label class="form-label">Bữa ăn</label>
        <input type="text" 
               name="buaan" 
               class="form-control" 
               value="<?= htmlspecialchars($lichTrinh['buaan'] ?? '') ?>">
    </div>

    <!-- Nơi nghỉ -->
    <div class="form-group">
        <label class="form-label">Nơi nghỉ</label>
        <input type="text" 
               name="noinghi" 
               class="form-control" 
               value="<?= htmlspecialchars($lichTrinh['noinghi'] ?? '') ?>">
    </div>

    <!-- Buttons -->
    <div style="display: flex; gap: 12px; margin-top: 32px;">
        <button type="submit" class="btn btn-primary">
            <i class="glyphicon glyphicon-floppy-disk"></i>
            Lưu thay đổi
        </button>
        <a href="<?= BASE_URL ?>?act=tour-lichtrinh&id_goi=<?= $idGoi ?>" class="btn btn-secondary">
            <i class="glyphicon glyphicon-arrow-left"></i>
            Quay lại
        </a>
    </div>
</form>
    </div>

</div>

<script>
// Auto-resize textarea
document.querySelectorAll('textarea').forEach(textarea => {
    textarea.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });
    
    // Trigger on load
    textarea.dispatchEvent(new Event('input'));
});

// Form validation
document.querySelector('form').addEventListener('submit', function(e) {
    const ngay = document.querySelector('input[name="ngay"]').value;
    const tieude = document.querySelector('input[name="tieude"]').value;
    const mota = document.querySelector('textarea[name="mota"]').value;
    
    if (!ngay || ngay < 1) {
        e.preventDefault();
        alert('Vui lòng nhập ngày hợp lệ!');
        return false;
    }
    
    if (!tieude.trim()) {
        e.preventDefault();
        alert('Vui lòng nhập tiêu đề!');
        return false;
    }
    
    if (!mota.trim()) {
        e.preventDefault();
        alert('Vui lòng nhập mô tả!');
        return false;
    }
});
</script>

<?php
$content = ob_get_clean();
include './views/admin/layout.php';
?>