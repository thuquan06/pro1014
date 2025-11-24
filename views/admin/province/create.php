<ol class="breadcrumb">
    <li><a href="<?= BASE_URL ?>?act=admin">Trang chủ</a></li>
    <li class="active">Thêm tỉnh/thành phố</li>
</ol>

<h2 class="inner-tittle">Thêm tỉnh</h2>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
<?php endif; ?>

<form action="<?= BASE_URL ?>?act=province-store" method="POST" class="form-horizontal">

    <div class="form-group">
        <label class="col-sm-2 control-label">Tên tỉnh</label>
        <div class="col-sm-6">
            <input type="text" name="ten_tinh" class="form-control"
                   value="<?= $_SESSION['old_data']['ten_tinh'] ?? '' ?>" required>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-6">
            <button class="btn btn-primary">Thêm mới</button>
            <a href="<?= BASE_URL ?>?act=province-list" class="btn btn-default">Quay lại</a>
        </div>
    </div>

</form>
<?php unset($_SESSION['old_data']); ?>
