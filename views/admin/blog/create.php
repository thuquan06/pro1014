<style>
.blog-form-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
}

.blog-form-title {
  font-size: 28px;
  font-weight: 700;
  color: var(--text-dark);
  margin: 0;
}

.form-card {
  background: white;
  border: 1px solid var(--border);
  border-radius: 12px;
  padding: 32px;
}

.form-group {
  margin-bottom: 24px;
}

.form-label {
  display: block;
  font-weight: 600;
  font-size: 14px;
  color: var(--text-dark);
  margin-bottom: 8px;
}

.form-label .required {
  color: #ef4444;
  margin-left: 4px;
}

.form-input,
.form-textarea {
  width: 100%;
  padding: 12px 16px;
  border: 1px solid var(--border);
  border-radius: 8px;
  font-size: 14px;
  transition: all 0.2s;
  font-family: inherit;
}

.form-input:focus,
.form-textarea:focus {
  outline: none;
  border-color: var(--primary);
  box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.form-textarea {
  resize: vertical;
  line-height: 1.6;
}

.form-actions {
  display: flex;
  gap: 12px;
  justify-content: flex-end;
  padding-top: 24px;
  border-top: 2px solid var(--bg-light);
}

.btn-submit {
  padding: 12px 32px;
  background: var(--primary);
  color: white;
  border: none;
  border-radius: 8px;
  font-weight: 600;
  font-size: 14px;
  cursor: pointer;
  transition: all 0.2s;
  display: inline-flex;
  align-items: center;
  gap: 8px;
}

.btn-submit:hover {
  background: #1e40af;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
}

.btn-reset {
  padding: 12px 32px;
  background: white;
  color: var(--text-dark);
  border: 1px solid var(--border);
  border-radius: 8px;
  font-weight: 600;
  font-size: 14px;
  cursor: pointer;
  transition: all 0.2s;
}

.btn-reset:hover {
  background: var(--bg-light);
}
</style>

<!-- Page Header -->
<div class="blog-form-header">
  <div>
    <h1 class="blog-form-title">
      <i class="fas fa-plus-circle" style="color: var(--primary);"></i>
      Tạo blog mới
    </h1>
    <div class="breadcrumb" style="margin-top: 8px;">
      <a href="<?=BASE_URL?>?act=admin" style="color: var(--text-light); text-decoration: none;">Trang chủ</a>
      <i class="fa fa-angle-right" style="margin: 0 8px; color: var(--text-light);"></i>
      <a href="<?=BASE_URL?>?act=blog-list" style="color: var(--text-light); text-decoration: none;">Danh sách blog</a>
      <i class="fa fa-angle-right" style="margin: 0 8px; color: var(--text-light);"></i>
      <span style="color: var(--text-dark);">Tạo mới</span>
    </div>
  </div>
  <a href="<?= BASE_URL ?>?act=blog-list" class="btn btn-secondary">
    <i class="fas fa-arrow-left"></i>
    Quay lại
  </a>
</div>

<!-- Form -->
<form action="<?= BASE_URL ?>?act=blog-store" method="POST" enctype="multipart/form-data">
  <div class="form-card">
    
    <div class="form-group">
      <label class="form-label" for="chude">
        Chủ đề <span class="required">*</span>
      </label>
      <input type="text" name="chude" id="chude" class="form-input" required placeholder="Nhập chủ đề bài viết">
    </div>

    <div class="form-group">
      <label class="form-label" for="nguoiviet">
        Người viết <span class="required">*</span>
      </label>
      <input type="text" name="nguoiviet" id="nguoiviet" class="form-input" required placeholder="Tên tác giả">
    </div>

    <div class="form-group">
      <label class="form-label" for="tomtat">
        Tóm tắt
      </label>
      <textarea name="tomtat" id="tomtat" class="form-textarea" rows="3" placeholder="Tóm tắt ngắn gọn về bài viết"></textarea>
    </div>

    <div class="form-group">
      <label class="form-label" for="ckeditor_blog">
        Nội dung
      </label>
      <textarea name="noidung" id="ckeditor_blog" class="form-textarea" rows="10"></textarea>
    </div>

    <div class="form-group">
      <label class="form-label" for="hinhanh">
        Hình ảnh
      </label>
      <input type="file" name="hinhanh" id="hinhanh" class="form-input">
      <small style="color: var(--text-light); font-size: 12px; display: block; margin-top: 4px;">
        <i class="fas fa-info-circle"></i> Hỗ trợ: JPG, PNG, GIF
      </small>
    </div>

    <div class="form-actions">
      <button type="reset" class="btn-reset">
        <i class="fas fa-redo"></i>
        Làm mới
      </button>
      <button type="submit" class="btn-submit">
        <i class="fas fa-save"></i>
        Tạo blog
      </button>
    </div>

  </div>
</form>

<!-- CKEDITOR -->
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
