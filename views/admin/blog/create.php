<div class="forms-main">
    <div class="graph-form">
        <div class="form-body">

            <!-- BREADCRUMB -->
            <ol class="breadcrumb">
                <li><a href="<?= BASE_URL ?>?act=admin">Trang chủ</a></li>
                <li class="active">Cập nhật blog</li>
            </ol>

            <h2 class="inner-tittle">Tạo blog</h2>

            <form action="<?= BASE_URL ?>?act=blog-store" 
                  method="POST" enctype="multipart/form-data">

                <table class="table">
                    <tr>
                        <td style="width:180px;"><label>Chủ đề</label></td>
                        <td>
                            <input type="text" name="chude" 
                                   class="form-control" required>
                        </td>
                    </tr>

                    <tr>
                        <td><label>Tóm tắt</label></td>
                        <td>
                            <input type="text" name="tomtat" 
                                   class="form-control">
                        </td>
                    </tr>

                    <tr>
                        <td><label>Người viết</label></td>
                        <td>
                            <input type="text" name="nguoiviet" 
                                   class="form-control" required>
                        </td>
                    </tr>

                    <tr>
                        <td><label>Nội dung</label></td>
                        <td>
                            <textarea name="noidung" id="ckeditor_blog" rows="10"
                                      class="form-control"></textarea>
                        </td>
                    </tr>

                    <tr>
                        <td><label>Hình ảnh</label></td>
                        <td>
                            <input type="file" name="hinhanh"
                                   class="form-control">
                        </td>
                    </tr>
                </table>

                <button class="btn btn-primary">TẠO</button>
                <button type="reset" class="btn btn-default">LÀM MỚI</button>
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
