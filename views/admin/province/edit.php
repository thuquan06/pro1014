<ol class="breadcrumb">
    <li><a href="<?= BASE_URL ?>?act=admin">Trang chủ</a></li>
    <li class="active">Cập nhật tỉnh/thành phố</li>
</ol>

<h2 class="inner-tittle">Cập nhật tỉnh</h2>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
<?php endif; ?>

<form action="<?= BASE_URL ?>?act=province-update" method="POST" class="form-horizontal">

    <!-- ĐÚNG: dùng $record, không phải $province -->
    <input type="hidden" name="id" value="<?= $record['id_tinh'] ?>">

    <div class="form-group">
        <label class="col-sm-2 control-label">Tên tỉnh</label>
        <div class="col-sm-6">
            <input type="text" 
                   name="ten_tinh" 
                   class="form-control"
                   value="<?= htmlspecialchars($record['ten_tinh']) ?>" 
                   required>
        </div>
    </div>

    <?php if (!empty($usageCount)): ?>
        <div class="alert alert-info" style="margin-left:15px;">
            Tỉnh này đang được dùng bởi <b><?= $usageCount ?></b> tour.
        </div>
    <?php endif; ?>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-6">
            <button class="btn btn-primary">Lưu thay đổi</button>
            <a href="<?= BASE_URL ?>?act=province-list" class="btn btn-default">Quay lại</a>
        </div>
    </div>
</form>
