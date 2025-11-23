<div class="forms-main">
    <div class="graph-form">
        <div class="form-body">

            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li><a href="<?= BASE_URL ?>?act=admin">Trang chủ</a></li>
                <li class="active">Chỉnh sửa Blog</li>
            </ol>

            <h2 class="inner-tittle">Chỉnh sửa Blog</h2>

            <form action="<?= BASE_URL ?>?act=blog-update" 
                  method="POST" enctype="multipart/form-data">

                <input type="hidden" name="id_blog" value="<?= $blog['id_blog'] ?>">
                <input type="hidden" name="old_hinhanh" value="<?= $blog['hinhanh'] ?>">

                <table class="table">
                    <tr>
                        <td style="width:180px;"><label>Chủ đề</label></td>
                        <td>
                            <input type="text" class="form-control" 
                                   name="chude" value="<?= $blog['chude'] ?>" required>
                        </td>
                    </tr>

                    <tr>
                        <td><label>Tóm tắt</label></td>
                        <td>
                            <textarea name="tomtat" class="form-control" rows="3"><?= $blog['tomtat'] ?></textarea>
                        </td>
                    </tr>

                    <tr>
                        <td><label>Nội dung</label></td>
                        <td>
                            <textarea id="ckeditor_blog" 
                                      name="noidung" rows="8"
                                      class="form-control"><?= $blog['noidung'] ?></textarea>
                        </td>
                    </tr>

                    <tr>
                        <td><label>Người viết</label></td>
                        <td>
                            <input type="text" class="form-control" 
                                   name="nguoiviet" value="<?= $blog['nguoiviet'] ?>" required>
                        </td>
                    </tr>

                    <tr>
                        <td><label>Hình ảnh</label></td>
                        <td>
                            <?php if (!empty($blog['hinhanh'])): ?>
                                <img src="<?= BASE_URL . $blog['hinhanh'] ?>" 
                                     style="width:150px; height:auto; margin-bottom:10px;">
                            <?php endif; ?>

                            <input type="file" name="hinhanh" class="form-control">
                        </td>
                    </tr>
                </table>

                <button type="submit" class="btn btn-success">CẬP NHẬT</button>
                <a href="<?= BASE_URL ?>?act=blog-list" class="btn btn-default">QUAY LẠI</a>

            </form>

        </div>
    </div>
</div>

<!-- CKEDITOR + CKFINDER -->
<script src="<?= BASE_URL ?>assets/ckeditor/ckeditor.js"></script>

<script>
    CKEDITOR.replace('ckeditor_blog', {
        filebrowserBrowseUrl: '<?= BASE_URL ?>assets/ckfinder/ckfinder.html',
        filebrowserImageBrowseUrl: '<?= BASE_URL ?>assets/ckfinder/ckfinder.html?type=Images',
        filebrowserUploadUrl: '<?= BASE_URL ?>assets/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
        filebrowserImageUploadUrl: '<?= BASE_URL ?>assets/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
        height: 350
    });
</script>
