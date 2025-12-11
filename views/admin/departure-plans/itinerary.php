<?php
function safe_html($value) {
    return htmlentities($value ?? '', ENT_QUOTES, 'UTF-8');
}

$departurePlan = $departurePlan ?? [];
$tour = $tour ?? null;
$title = 'Chỉnh sửa Lịch trình tour theo ngày';
?>

<style>
.itinerary-container {
  max-width: 1000px;
  margin: 0 auto;
}
.page-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 12px;
  margin-bottom: 20px;
}
.page-title {
  font-size: 22px;
  font-weight: 700;
  margin: 0;
  display: flex;
  align-items: center;
  gap: 10px;
  color: #1f2937;
}
.card {
  background: #fff;
  border: 1px solid #e5e7eb;
  border-radius: 12px;
  padding: 20px;
  box-shadow: 0 1px 2px rgba(0,0,0,0.05);
}
.card-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 12px;
  border-bottom: 1px solid #f3f4f6;
  padding-bottom: 10px;
}
.card-title {
  font-size: 16px;
  font-weight: 700;
  color: #1f2937;
  display: flex;
  align-items: center;
  gap: 8px;
}
.actions {
  display: flex;
  gap: 10px;
}
.btn {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 10px 16px;
  border-radius: 8px;
  text-decoration: none;
  font-weight: 600;
  border: none;
  cursor: pointer;
}
.btn-primary { background: #3b82f6; color: #fff; }
.btn-primary:hover { background: #2563eb; }
.btn-secondary { background: #6b7280; color: #fff; }
.btn-secondary:hover { background: #4b5563; }
.info-row { margin-bottom: 12px; color: #374151; }
.info-label { font-weight: 600; color: #6b7280; font-size: 13px; }
.editor-label { font-weight: 700; margin-bottom: 8px; color: #1f2937; }
.help-text { color: #6b7280; font-size: 13px; margin-top: 6px; }
</style>

<div class="itinerary-container">
  <div class="page-header">
    <h1 class="page-title">
      <i class="fas fa-route" style="color:#3b82f6;"></i>
      Chỉnh sửa Lịch trình tour theo ngày
    </h1>
    <div class="actions">
      <a class="btn btn-secondary" href="<?= BASE_URL ?>?act=admin-departure-plan-detail&id=<?= $departurePlan['id'] ?>">
        <i class="fas fa-arrow-left"></i> Quay lại chi tiết
      </a>
    </div>
  </div>

  <div class="card">
    <div class="card-header">
      <div class="card-title">
        <i class="fas fa-info-circle" style="color:#3b82f6;"></i> Thông tin
      </div>
    </div>
    <div class="card-body">
      <?php if ($tour): ?>
        <div class="info-row"><span class="info-label">Tour:</span> <?= safe_html($tour['tengoi'] ?? '') ?></div>
      <?php endif; ?>
      <div class="info-row"><span class="info-label">Lịch khởi hành ID:</span> <?= safe_html($departurePlan['id'] ?? '') ?></div>
      <?php if (!empty($departurePlan['ngay_khoi_hanh'])): ?>
        <div class="info-row"><span class="info-label">Ngày khởi hành:</span> <?= date('d/m/Y', strtotime($departurePlan['ngay_khoi_hanh'])) ?></div>
      <?php endif; ?>
    </div>
  </div>

  <div class="card" style="margin-top:16px;">
    <div class="card-header">
      <div class="card-title">
        <i class="fas fa-edit" style="color:#3b82f6;"></i> Lịch trình theo ngày
      </div>
    </div>
    <div class="card-body">
      <form method="post" action="<?= BASE_URL ?>?act=admin-departure-plan-itinerary-save">
        <input type="hidden" name="id" value="<?= safe_html($departurePlan['id'] ?? '') ?>">
        <div class="editor-label">Nội dung lịch trình</div>
        <textarea name="chuongtrinh" id="chuongtrinh-editor"><?php 
        // Làm sạch và hiển thị chuongtrinh
        $content = $departurePlan['chuongtrinh'] ?? '';
        // Loại bỏ ký tự JSON thừa nếu có
        $content = preg_replace('/[\s]*[\}\]\"]+[\s]*$/', '', $content);
        $content = preg_replace('/^[\s]*[\{\[\"]+[\s]*/', '', $content);
        $content = preg_replace('/<[^>]*>[\s]*[\}\]\"]+[\s]*<\/[^>]*>/is', '', $content);
        echo htmlspecialchars(trim($content), ENT_QUOTES, 'UTF-8');
        ?></textarea>
        <div class="help-text">Bạn có thể nhập nội dung theo ngày, ví dụ: &lt;h3&gt;&lt;strong&gt;NGÀY 1: Tiêu đề&lt;/strong&gt;&lt;/h3&gt; ...</div>
        <div style="margin-top:16px; display:flex; gap:10px;">
          <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Lưu lịch trình</button>
          <a href="<?= BASE_URL ?>?act=admin-departure-plan-detail&id=<?= $departurePlan['id'] ?>" class="btn btn-secondary"><i class="fas fa-times"></i> Hủy</a>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="assets/ckeditor/ckeditor.js"></script>
<script>
  CKEDITOR.replace('chuongtrinh-editor', {
    height: 400,
    filebrowserBrowseUrl: 'assets/ckfinder/ckfinder.html',
    filebrowserImageBrowseUrl: 'assets/ckfinder/ckfinder.html?type=Images',
    filebrowserUploadUrl: 'assets/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
    filebrowserImageUploadUrl: 'assets/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images'
  });
</script>

